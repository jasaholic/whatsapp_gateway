<html>

<head>
    <title>Export Data Ke Excel Dengan PHP - www.malasngoding.com</title>
</head>

<body>
    <style type="text/css">
        body {
            font-family: sans-serif;
        }

        table {
            margin: 20px auto;
            border-collapse: collapse;
        }

        table th,
        table td {
            border: 1px solid #3c3c3c;
            padding: 3px 8px;

        }

        a {
            background: blue;
            color: #fff;
            padding: 8px 10px;
            text-decoration: none;
            border-radius: 2px;
        }
    </style>

    <?php
    require('helper/koneksi.php');
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=Data Nomor.xls");
    ?>

    <center>
        <h1>Export Data Ke Excel Dengan PHP <br /> www.malasngoding.com</h1>
    </center>

    <table border="1">
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Alamat</th>
            <th>No.Telp</th>
        </tr>
        <?php


        // menampilkan data pegawai
        $data = mysqli_query($koneksi, "select * from nomor");
        $no = 1;
        while ($d = mysqli_fetch_array($data)) {
        ?>
            <tr>
                <td><?php echo $d['nama']; ?></td>
                <td><?php echo $d['nomor']; ?></td>
                <td><?php echo $d['pesan']; ?></td>
            </tr>
        <?php
        }
        ?>
    </table>
</body>

</html>