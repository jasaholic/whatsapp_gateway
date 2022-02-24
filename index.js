const {
	WAConnection,
	MessageType,
	MessageOptions,
	Presence,
	Mimetype,
	WALocationMessage,
	WA_MESSAGE_STUB_TYPES,
	ReconnectMode,
	ProxyAgent,
	waChatKey,
} = require("@adiwajshing/baileys");
const http = require("http");
const https = require("https");
var qrcode = require('qrcode');
const fs = require("fs");
const { body, validationResult } = require('express-validator');
const express = require('express');
const axios = require("axios");
const app = express();
const server = http.createServer(app);
const socketIO = require('socket.io');
const { phoneNumberFormatter } = require('./helper/formatter');
const io = socketIO(server);
const request = require("request");
app.use(express.json());
app.use(express.urlencoded({ extended: true }));


const configs = {
	port: 3000, // custom port to access server
	webhook_url: 'http://localhost/waapi/filewebhook/webhook.php',
	webhook_group: 'http://localhost/waapi/hookgroup.php',
	callback_url: 'http://localhost/project/callback.php' // webhook url
};
const conn = new WAConnection();
var execPHP = require('./execphp.js')();

// app.get("/", (req, res) => {
// 	res.sendFile('index.html', { root: __dirname });
// })

execPHP.phpFolder = 'C:\\xampp\\htdocs\\wa2\\wa2\\';

app.use('*.php', function (request, response, next) {
	execPHP.parseFile(request.originalUrl, function (phpResult) {
		response.write(phpResult);
		response.end();
	});
});
const SESSION_FILE_PATH = './session.json';
let sessionCfg;
if (fs.existsSync(SESSION_FILE_PATH)) {
	sessionCfg = require(SESSION_FILE_PATH);
}


//conn.autoReconnect = ReconnectMode.onAllErrors;
conn.autoReconnect = ReconnectMode.onConnectionLost;
// conn.connectOptions = { reconnectID: "reconnect" };


async function connect() {
	fs.existsSync('./session.json') && conn.loadAuthInfo('./session.json');
	await conn.connect({ timeoutMs: 30 * 1000 });
	const authInfo = conn.base64EncodedAuthInfo(); // get all the auth info we need to restore this session
	fs.writeFileSync('./session.json', JSON.stringify(authInfo, null, '\t'))
	console.log("oh hello " + conn.user.name + " (" + conn.user.jid + ")");
	io.emit('authenticated', "oh hello " + conn.user.name + " (" + conn.user.jid + ")")
}

// conn.on("credentials-updated", async () => {
// 	const authInfo = conn.base64EncodedAuthInfo(); // get all the auth info we need to restore this session
// 	fs.writeFileSync('./session.json', JSON.stringify(authInfo, null, '\t')) // save this info to a file
// 	//io.emit('authenticated', "oh hello " + conn.user.name + " (" + conn.user.jid + ")")
// })

connect().catch((err) => {
	console.log(err);
});

io.on("connection", function (socket) {

	socket.on('ready', () => {
		if (fs.existsSync('./session.json') && conn.state == 'open') {
			io.emit('authenticated', "oh hello " + conn.user.name + " (" + conn.user.jid + ")")
		} else {
			io.emit('loader', '')
			socket.emit('message', 'Please wait..')
			connect()
		}
	})

	conn.on("qr", (qr) => {
		socket.emit('message', 'Getting QR Code')
		qrcode.toDataURL(qr, (err, url) => {
			socket.emit('message', 'QR Code received, scan please!')
			console.log(qr);
			socket.emit("qr", url);
		});
	});

	socket.on('logout', () => {
		if (fs.existsSync("./session.json")) {
			conn.close()
			conn.clearAuthInfo();
			fs.unlinkSync("./session.json");
			socket.emit('isdelete', '<h2 class="text-center text-info mt-4">Logout Success, Lets Scan Again<h2>')
		} else {
			socket.emit('isdelete', '<h2 class="text-center text-danger mt-4">You are have not Login yet!<h2>')
		}
	})

	socket.on('scanqr', () => {
		if (fs.existsSync('./session.json') && conn.state == 'open') {
			io.emit('authenticated', "oh hello " + conn.user.name + " (" + conn.user.jid + ")")
		} else {
			io.emit('loader', '')
			socket.emit('message', 'Please wait..')
			connect()
		}
	})
	socket.on('cekstatus', () => {
		if (fs.existsSync('./session.json') && conn.state == 'open') {
			io.emit('isdelete', '<h2 class="text-center text-primary mt-4">Your whatsapp is Running!</h2>')
		} else {
			io.emit('isdelete', '<h2 class="text-center text-danger mt-4">Your whatsapp is not Running!,Scan Now!<h2>')
		}
	})
});

conn.on('close', ({ reason }) => {
	console.log(reason);
	if (reason == 'invalid_session') {
		if (fs.existsSync("./session.json")) {
			conn.close()
			conn.clearAuthInfo();
			fs.unlinkSync("./session.json");
			socket.emit('message', 'Connection lost..!')
			connect();
		}
	}
})




// send message
app.post('/v2/send-message', [
	body('number').notEmpty(),
	body('message').notEmpty(),
], async (req, res) => {
	const errors = validationResult(req).formatWith(({
		msg
	}) => {
		return msg;
	});

	if (!errors.isEmpty()) {
		return res.status(422).json({
			status: false,
			message: errors.mapped()
		});
	}
	const message = req.body.message;
	if (req.body.number.length > 15) {
		var number = req.body.number;
		conn.sendMessage(number, message, MessageType.text).then(response => {
			res.status(200).json({
				status: true,
				response: response
			});
		}).catch(err => {
			res.status(500).json({
				status: false,
				response: err
			});
		});
		return
	} else {
		var number = phoneNumberFormatter(req.body.number);
	}
	if (fs.existsSync('./session.json')) {
		var numberExists = await conn.isOnWhatsApp(number);
		if (!numberExists) {
			return res.status(422).json({
				status: false,
				message: 'The number is not registered'
			});
		}
		conn.sendMessage(number, message, MessageType.text).then(response => {
			res.status(200).json({
				status: true,
				response: response
			});
		}).catch(err => {
			res.status(500).json({
				status: false,
				response: err
			});
		});

	} else {
		res.writeHead(401, {
			'Content-Type': 'application/json'
		});
		res.end(JSON.stringify({
			status: false,
			message: 'Please scan the QR before use the API'
		}));
	}
});

// send media
app.post('/v2/send-media', [
	body('number').notEmpty(),
	body('url').notEmpty(),
	body('filetype').notEmpty(),
], async (req, res) => {
	const errors = validationResult(req).formatWith(({
		msg
	}) => {
		return msg;
	});
	if (!errors.isEmpty()) {
		return res.status(422).json({
			status: false,
			message: errors.mapped()
		});
	}
	const getBuffer = async (url, options) => {
		try {
			options ? options : {}
			const res = await axios({
				method: "get",
				url,
				...options,
				responseType: 'arraybuffer'
			})
			return res.data
		} catch (e) {
			console.log(`Error : ${e}`)
		}
	}
	if (fs.existsSync('./session.json')) {
		const number = phoneNumberFormatter(req.body.number);
		const url = req.body.url;
		const filetype = req.body.filetype;
		const filename = req.body.filename;
		const caption = req.body.caption;
		if (filetype == 'pdf' || filetype == 'docx' || filetype == 'doc') {
			const buffer = await getBuffer(url);
			conn.sendMessage(number, buffer, MessageType.document, { mimetype: 'pdf/docx', filename: filename + '.' + filetype }).then(response => {
				return res.status(200).json({
					status: true,
					response: response
				});
			}).catch(err => {
				return res.status(500).json({
					status: false,
					response: err
				});
				console.log('gagal')
			});
		} else if (filetype == 'mp3') {
			const voice = req.body.voice;
			const buffer = await getBuffer(url);
			conn.sendMessage(number, buffer, MessageType.audio, { mimetype: Mimetype.mp4Audio, ptt: voice }).then(response => {
				return res.status(200).json({
					status: true,
					response: response
				});
				fs.unlinkSync(mediaName);

			}).catch(err => {
				return res.status(500).json({
					status: false,
					response: err
				});
				console.log('gagal')
			});
		} else if (filetype == 'gif') {
			const buffer = await getBuffer(url);
			conn.sendMessage(number, mediaBuffer, MessageType.video, { Mimetype: Mimetype.gif }).then(response => {
				return res.status(200).json({
					status: true,
					response: response
				});
				fs.unlinkSync(mediaName);

			}).catch(err => {
				return res.status(500).json({
					status: false,
					response: err
				});
				console.log('gagal')
			});
		} else if (filetype == 'webp') {
			const buffer = await getBuffer(url);
			conn.sendMessage(number, mediaBuffer, MessageType.sticker).then(response => {
				return res.status(200).json({
					status: true,
					response: response
				});
				fs.unlinkSync(mediaName);

			}).catch(err => {
				return res.status(500).json({
					status: false,
					response: err
				});
				console.log('gagal')
			});
		} else if (filetype == 'jpg' || filetype == 'jpeg' || filetype == 'png') {
			var messageOptions = {
				caption: caption
			};
			const buffer = await getBuffer(url)
			conn.sendMessage(number, buffer, MessageType.image, messageOptions).then(response => {
				res.status(200).json({
					status: true,
					response: response
				});
				fs.unlinkSync(mediaName);

			}).catch(err => {
				res.status(500).json({
					status: false,
					response: err
				});
			});

		}





	} else {
		res.writeHead(401, {
			'Content-Type': 'application/json'
		});
		res.end(JSON.stringify({
			status: false,
			message: 'Please scan the QR before use the API'
		}));
	}
});


//
server.listen(configs.port, () => {
	console.log(`Server listening on 8000`);
});

conn.on('group-participants-update', m => {
	console.log(m);
	var number = m.participants[0];
	var participants = phoneNumberFormatter(number);

	const webhook_group = {
		number: participants,
		groupid: m.jid,
		action: m.action
	}
	request({ url: configs.webhook_group, method: "POST", json: webhook_group },

	);
})



conn.on('chat-update', async chat => {
	if (chat.presences) { // receive presence updates -- composing, available, etc.
		Object.values(chat.presences).forEach(presence => console.log(`${presence.name}'s presence is ${presence.lastKnownPresence} in ${chat.jid}`))
	}
	if (chat.imgUrl) {
		console.log('imgUrl of chat changed ', chat.imgUrl)
		return
	}
	// only do something when a new message is received
	if (!chat.hasNewMessage) {
		if (chat.messages) {
			console.log('updated message: ', chat.messages.first)
		}
		return
	}

	const m = chat.messages.all()[0] // pull the new message from the update
	const messageStubType = WA_MESSAGE_STUB_TYPES[m.messageStubType] || 'MESSAGE'
	console.log('got notification of type: ' + messageStubType)

	const messageContent = m.message
	// if it is not a regular text or media message
	if (!messageContent) return

	if (m.key.fromMe) {
		console.log('relayed my own message')
		return
	}

	let sender = m.key.remoteJid
	if (m.key.participant) {
		l
		// participant exists if the message is in a group
		sender += ' (' + m.key.participant + ')'
	}
	const messageType = Object.keys(messageContent)[0] // message will always contain one key signifying what kind of message
	if (messageType === MessageType.text) {
		const text = m.message.conversation
		console.log(sender + ' sent: ' + text)
		const pesan = {
			sender: phoneNumberFormatter(sender),
			msg: text
		}
		request({ url: configs.callback_url, method: "POST", json: pesan },
			async function (error, response) {
			}
		);
		var senddd = kirimwebhook(sender, text, m);
	} else if (messageType === MessageType.extendedText) {
		const text = m.message.extendedTextMessage.text
		console.log(sender + ' sent: ' + text + ' and quoted message: ' + JSON.stringify(m.message))
		var senddd = kirimwebhook(sender, text, m);
	} else if (messageType === MessageType.contact) {
		const contact = m.message.contactMessage
		console.log(sender + ' sent contact (' + contact.displayName + '): ' + contact.vcard)
	} else if (messageType === MessageType.location || messageType === MessageType.liveLocation) {

		console.log(`${sender} sent location (lat: ${locMessage.degreesLatitude}, long: ${locMessage.degreesLongitude})`)

		await conn.downloadAndSaveMediaMessage(m, './Media/media_loc_thumb_in_' + m.key.id) // save location thumbnail

		if (messageType === MessageType.liveLocation) {
			console.log(`${sender} sent live location for duration: ${m.duration / 60}`)
		}
	} else {
		// if it is a media (audio, image, video, sticker) message
		// decode, decrypt & save the media.
		// The extension to the is applied automatically based on the media type
		try {
			const savedFile = await conn.downloadAndSaveMediaMessage(m, './Media/media_in_' + m.key.id)
			console.log(sender + ' sent media, saved at: ' + savedFile)
		} catch (err) {
			console.log('error in decoding message: ' + err)
		}
	}
	// send a reply after 3 seconds

})

function kirimwebhook(sender, message, m) {
	var webhook_response = {
		from: phoneNumberFormatter(sender),
		message: message
	}
	const getBuffer = async (url, options) => {
		try {
			options ? options : {}
			const res = await axios({
				method: "get",
				url,
				...options,
				responseType: 'arraybuffer'
			})
			return res.data
		} catch (e) {
			console.log(`Error : ${e}`)
		}
	}

	request({ url: configs.webhook_url, method: "POST", json: webhook_response },
		async function (error, response) {
			if (!error && response.statusCode == 200) {
				// process hook
				if (response.body == null) {
					return 'gagal send webhook';
				}
				const res = response.body;
				console.log(res);
				if (res.mode == 'chat') {
					conn.sendMessage(sender, res.pesan, MessageType.text)
				} else if (res.mode == 'reply') {
					conn.sendMessage(sender, res.pesan, MessageType.extendedText, { quoted: m })
				} else if (res.mode == 'picture') {
					const url = res.data.url;
					const caption = res.data.caption;
					var messageOptions = {};
					const buffer = await getBuffer(url);
					if (caption != '') messageOptions.caption = caption;
					conn.sendMessage(sender, buffer, MessageType.image, messageOptions);
				}
			} else { console.log('error'); }
		}
	);
}


// webhook
// conn.on("message-new", async m => {
// 	console.log(m);
// 	if (!m.message) return // if there is no text or media message

// 	// ketika pesan masuknya gambar
// 	if (m.message.imageMessage) {
// 		console.log('sdfs');
// 	}


// 	if (m.hasOwnProperty('participant') == true) {
// 		var sender = m.key.remoteJid
// 	} else {
// 		var sender = phoneNumberFormatter(m.key.remoteJid)
// 	}
// 	var chatFromMe = m.key.fromMe;
// 	var chatId = m.key.remoteJid;
// 	//	const sender = phoneNumberFormatter(m.key.remoteJid)
// 	var chatBody = m.message.conversation;
// 	if ('conversation' in m.message) {
// 		var type = 'chat';
// 	}
// 	var webhook_response = {
// 		fromMe: chatFromMe,
// 		data: {
// 			from: phoneNumberFormatter(chatId),
// 			body: chatBody
// 		},
// 		type: type
// 	};
	// const getBuffer = async (url, options) => {
	// 	try {
	// 		options ? options : {}
	// 		const res = await axios({
	// 			method: "get",
	// 			url,
	// 			...options,
	// 			responseType: 'arraybuffer'
	// 		})
	// 		return res.data
	// 	} catch (e) {
	// 		console.log(`Error : ${e}`)
	// 	}
	// }
// 	request({ url: configs.webhook_url, method: "POST", json: webhook_response },


// 		async function (error, response, body) {
// 			//	console.log(response)
// 			if (!error && response.statusCode == 200) {
// 				// process hook
// 				if (response.body == null) {
// 					return 'gagal send webhook';
// 				}
// 				const res = response.body;
// 				if (res.type == 'message') {
// 					if (res.data.mode == 'chat') {
// 						conn.sendMessage(sender, res.data.pesan, MessageType.text)
// 					} else if (res.data.mode == 'reply') {
// 						conn.sendMessage(sender, res.data.pesan, MessageType.extendedText, { quoted: m })
// 					}
// 				} else if (res.type == 'picture') {
// 					console.log(res);
// 					const url = res.data.url;
// 					const caption = res.data.caption;
// 					var messageOptions = {};
// 					const buffer = await getBuffer(url);
// 					console.log(buffer);
// 					if (caption != '') messageOptions.caption = caption;
// 					conn.sendMessage(sender, buffer, MessageType.image, messageOptions);

// 				} 	//
// 			} else { console.log('error'); }
// 		}
// 	);
// 	//	await conn.chatRead(m.key.remoteJid);
// });





