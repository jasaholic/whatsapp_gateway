<?php
include_once("../helper/koneksi.php");
include_once("../helper/function.php");

$login = cekSession();
if ($login == 0) {
  redirect("../login.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Web Whatsapp</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
  <link rel="stylesheet" href="./style.css">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
  <style>
    * {
      font-family: 'Poppins', sans-serif;
    }
  </style>
</head>

<body>
  <!-- partial:index.partial.html -->
  <section class="main-grid">
    <aside class="main-side">
      <header class="common-header">
        <div class="common-header-start">
          <button class="u-flex js-user-nav">
            <img class="profile-image" src="../img/empty.png" alt="">
            <div class="common-header-content">
              <h1 class="common-header-title">Me</h1>
            </div>
          </button>
        </div>
        <nav class="common-nav">
          <ul class="common-nav-list">
            <li class="common-nav-item">
              <button class="common-button">
                <span class="icon">🕘</span>
              </button>
            </li>
            <li class="common-nav-item">
              <button class="common-button">
                <span class="icon icon-status">💬</span>
              </button>
            </li>
            <li class="common-nav-item">
              <button class="common-button">
                <span class="icon icon-menu" aria-label="menu"></span>
              </button>
            </li>
          </ul>
        </nav>
      </header>
      <section class="common-alerts">
        <a class="btn btn-block btn-refresh btn-primary" style="font-size:20px"> refresh </a>

      </section>
      <!-- 
    <section class="common-search">
        <input type="search" class="text-input" placeholder="Search or start new chat">
    </section>
    -->
      <section class="chats">
        <ul class="chats-list">
          <?php
          $q = getContact();
          while ($row = mysqli_fetch_assoc($q)) {
            $last = getLastMsg($row['nomor']);
            $nama = getSingleValDB("nomor", "nomor", $row['nomor'], "nama");
            if ($nama) {
              $n = $nama . " - ";
            } else {
              $n = "";
            }
          ?>
            <li class="chats-item">
              <div class="chats-item-button js-chat-button chatdiv" role="button" tabindex="0" data-nomor="<?= $row['nomor'] ?>" data-name="<?= $n ?><?= $row['nomor'] ?>">
                <img class="profile-image" src="../img/empty.png" alt="">
                <header class="chats-item-header">
                  <h3 class="chats-item-title"><?= $n ?><?= $row['nomor'] ?></h3>
                  <time class="chats-item-time tanggal-terakhir-<?= $row['nomor'] ?>"><?= $last['tanggal'] ?></time>
                </header>
                <div class="chats-item-content">
                  <p class="chats-item-last pesan-terakhir-<?= $row['nomor'] ?>"><?= $last['pesan'] ?></p>
                  <ul class="chats-item-info">
                    <li class="chats-item-info-item"><span class="unread-messsages"></span></li>
                  </ul>
                </div>
              </div>
            </li>
          <?php
          }
          ?>
          <!--
        <li class="chats-item">
          <div class="chats-item-button js-chat-button" role="button" tabindex="0">
            <img class="profile-image" src="https://pbs.twimg.com/profile_images/507217425/rachelbyserge_400x400.jpg" alt="Rachel Bratt Tannenbaum">
            <header class="chats-item-header">
              <h3 class="chats-item-title">Rachel Bratt Tannenbaum</h3>
              <time class="chats-item-time">12:05</time>
            </header>
            <div class="chats-item-content">
              <p class="chats-item-last">When is our next meetup?</p>
              <ul class="chats-item-info">
                <li class="chats-item-info-item u-hide"><span class="icon-silent">🔇</span></li>
                <li class="chats-item-info-item"><span class="unread-messsages">1</span></li>
              </ul>
            </div>
          </div>
        </li>
        -->

        </ul>
      </section>
    </aside>
    <main class="main-content">
      <header class="common-header">
        <div class="common-header-start">
          <button class="common-button is-only-mobile u-margin-end js-back"><span class="icon icon-back">⬅</span></button>
          <button class="u-flex js-side-info-button">
            <img class="profile-image" src="../img/empty.png">
            <div class="common-header-content">
              <h2 class="common-header-title nama-container">DVLPR</h2>
              <p class="common-header-status"></p>
            </div>
          </button>
        </div>
        <nav class="common-nav">
          <ul class="common-nav-list">
            <li class="common-nav-item">
              <button class="common-button">
                <span class="icon">🔎</span>
              </button>
            </li>
            <li class="common-nav-item">
              <button class="common-button">
                <span class="icon icon-attach">📎</span>
              </button>
            </li>
            <li class="common-nav-item">
              <button class="common-button u-animation-click js-side-info-button">
                <span class="icon icon-menu" aria-label="menu"></span>
              </button>
            </li>
          </ul>
        </nav>
      </header>
      <div class="messanger" id="msg-container">
        <ol class="messanger-list pesan-container">
          <li class="common-message is-time">
            <p class="common-message-content">
              DEVELOP BY DVLPR
            </p>
          </li>
          <!--
        <li class="common-message is-you">
          <p class="common-message-content">
            Just take a look
          </p>
          <span class="status is-seen">✔️✔️</span>
          <time datetime>14:12</time>
        </li> 
        <li class="common-message is-other">        
          <p class="common-message-content">
            Who are you?
          </p>
          <time datetime>14:33</time>          
        </li>
        -->
        </ol>
      </div>
      <div class="message-box">
        <!-- <button class="common-button"><span class="icon">😃</span></button> -->
        <input class="text-input pesan" id="message-box" placeholder="Type a message" contenteditable required>
        <input type="hidden" class="nomor">
        <button id="voice-button" class="common-button send-button" type="submit"><span class="icon">➤</span></button>
      </div>
    </main>
    <aside class="main-info u-hide">
      <header class="common-header">
        <button class="common-button js-close-main-info"><span class="icon">❌</span></button>
        <div class="common-header-content">
          <h3 class="common-header-title">Info</h3>
        </div>
      </header>
      <div class="main-info-content">
        <section class="common-box">
          <img class="main-info-image" src="../img/empty.png" alt="">
          <h4 class="big-title nama-container"></h4>
          <p class="info-text"></p>
        </section>
        <section class="common-box">
          <h5 class="section-title">Description</h5>
          <p>==COMING SOON==</p>
        </section>
      </div>
    </aside>
  </section>
  <!-- partial -->
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js'></script>
  <script src="./script.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous"></script>

  <script>
    getContact();
    setInterval(sync, 4000);

    let input = document.getElementById("message-box");
    input.addEventListener("keyup", function(event) {
      if (event.keyCode === 13) {
        event.preventDefault();
        document.getElementById("voice-button").click();
      }
    });

    $('.btn-refresh').click(function(data) {
      $.get("refresh.php", function(data) {
        location.reload();
      });
    });

    function getContact() {
      $('.chatdiv').click(function(data) {
        let nomor = $(this).data("nomor");
        let name = $(this).data("name");
        $(".pesan-container").empty();
        $(".nomor").empty();
        $(".nomor").val(nomor);
        $(".nama-container").html(name);
        $.get("get_chat.php?nomor=" + nomor, function(data) {
          r = JSON.parse(data);

          jQuery.each(r, function(i, val) {
            let from_me = "";
            console.log(val.from_me);
            if (val.from_me == "0") {
              from_me = "is-other";
            } else {
              from_me = "is-you";
            }
            let chat = "<li class='common-message " + from_me + "'><p class='common-message-content'>" + val.pesan + "</p><time datetime>" + val.tanggal + "</time></li>";
            $(".pesan-container").append(chat);
            updateScroll();
          });
          localStorage.setItem('nomor', nomor);
        });
      });
    }


    $(".send-button").click(function(e) {

      let nomor = $(".nomor").val();
      let pesan = $(".pesan").val();
      let url = "tes.php";

      let post = {
        number: nomor,
        message: pesan
      };
      let tanggal = moment().format("HH:mm");

      $(".pesan-terakhir-" + nomor).html(pesan);
      $(".tanggal-terakhir-" + nomor).html(tanggal);
      $.ajax({
        type: "POST",
        url: url,
        data: post, // serializes the form's elements.
        //  dataType: "json",
        //  contentType: "application/json; charset=utf-8",
        success: function(data) {
          console.log(data);
          let chat = "<li class='common-message is-you'><p class='common-message-content'>" + pesan + "</p><time datetime>" + tanggal + "</time></li>";
          $(".pesan-container").append(chat);
          updateScroll();
          $(".pesan").val("");
        }
      });
    });


    function sync() {
      let sync = localStorage.getItem('sync');
      let nomor = localStorage.getItem('nomor');

      if (nomor != null) {
        if (sync == null) {
          sync = moment().format("YYYY-MM-DD HH:mm:ss");
          localStorage.setItem('sync', sync);
        }

        $.get("longpooling.php?nomor=" + nomor + "&lastsync=" + sync, function(data) {
          r = JSON.parse(data);

          jQuery.each(r, function(i, val) {
            let from_me = "";
            console.log(val.from_me);
            if (val.from_me == "0") {
              from_me = "is-other";
            } else {
              from_me = "is-you";
            }
            let chat = "<li class='common-message " + from_me + "'><p class='common-message-content'>" + val.pesan + "</p><time datetime>" + val.tanggal + "</time></li>";
            $(".pesan-container").append(chat);
            $(".pesan-terakhir-" + nomor).html(val.pesan);
            $(".tanggal-terakhir-" + nomor).html(val.tanggal);
            updateScroll();
          });
        });
        localStorage.setItem('sync', moment().format("YYYY-MM-DD HH:mm:ss"));
      } else {
        console.log("nothing");
      }

    }

    function updateScroll() {
      var element = document.getElementById("msg-container");
      element.scrollTop = element.scrollHeight;
    }
  </script>
</body>

</html>