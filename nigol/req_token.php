<!doctype html>
<html lang="en" class="fullscreen-bg">

<head>
  <title>dishub.bangkalankab.go.id</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <!-- VENDOR CSS -->
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/vendor/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="assets/vendor/linearicons/style.css">
  <link rel="stylesheet" href="assets/vendor/toastr/toastr.min.css">
  <!-- MAIN CSS -->
  <link rel="stylesheet" href="assets/css/main.css">
  <!-- FOR DEMO PURPOSES ONLY. You should remove this in your project -->
  <link rel="stylesheet" href="assets/css/demo.css">
  <!-- GOOGLE FONTS -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">
  <!-- ICONS -->
  <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
  <link rel="icon" type="image/png" sizes="96x96" href="assets/img/icon/dishub.png">
</head>

<body>
  <!-- WRAPPER -->
  <div id="wrapper">
    <div class="vertical-align-wrap">
      <div class="vertical-align-middle">
        <div class="auth-box ">
          <div class="left">
            <div class="content">
              <div class="header">
                <div class="logo text-center"><img src="assets/img/icon/dishub.png" width="20%"></div>
                <p class="lead">Request Token Login</p>
              </div>
              <form class="form-auth-small" method="POST">
                <div class="form-group">
                  <label class="control-label sr-only">Username</label>
                  <input type="text" class="form-control" name="user" placeholder="Masukkan Username">
                </div>
                <button type="submit" name="submit" class="btn btn-success btn-sm btn-block">REQ TOKEN</button>
              </form>
              <a href="http://dishub.bangkalankab.go.id/nigol/" class="btn btn-info btn-sm btn-block" style="margin-top: 5px;">Kembali</a>
              <br>
              <?php

              if (isset($_POST['submit'])) {
                include 'database.php';

                function random()
                {
                  $jml_char = 12;
                  $char = "abcdefghijklmnopqrstuvwxyz0123456789";
                  $acak = "";

                  for ($i = 0; $i < $jml_char; $i++) {
                    # code...

                    $pos = rand(0, strlen($char) - 1);

                    $acak .= $char{
                    $pos};
                  }
                  return $acak;
                }
                function filterSQL($string)
                {
                  $string = strtolower($string);
                  $string = str_replace('update', '', $string);
                  $string = str_replace('delete', '', $string);
                  $string = str_replace('insert', '', $string);

                  return $string;
                }

                $token = random();
                $token = strtoupper($token);
                $username = $_POST['user'];
                $username = mysqli_real_escape_string($con, stripslashes(strip_tags(htmlspecialchars($username, ENT_QUOTES))));
                $username = preg_replace('/[^\p{L}\p{N}\s]/u', '', $username);
                $username = preg_replace('/[0-9]+/', '', $username);
                $username = filterSQL($username);
                $tgl = date("Y-m-d");

                $saveToken = mysqli_query($con, "UPDATE sslogin SET SSTOKEN = '$token', TGL_TOKEN = '$tgl' WHERE SSUSER = '$username'");

                $sql = mysqli_query($con, "SELECT * FROM sslogin WHERE SSUSER='$username'");
                $data = mysqli_fetch_array($sql);
                $row = mysqli_num_rows($sql);
                if ($row != 0) {
                  $email = $data['SSEMAIL'];

                  $subject = 'Token Website';
                  $message = 'Hello, ' . $username . '. Gunakan Token untuk login akun Anda. Masa berlaku Token hanya sehari, jika lebih dari itu, maka minta Token yang baru pada link "Request Token". Token Anda : ' . $token;
                  require 'PHPMailer/PHPMailerAutoload.php';
                  //Create a new PHPMailer instance
                  $mail = new PHPMailer;
                  //Tell PHPMailer to use SMTP
                  $mail->isSMTP();
                  //Enable SMTP debugging
                  // 0 = off (for production use)
                  // 1 = client messages
                  // 2 = client and server messages
                  // $mail->SMTPDebug = 2;
                  //Ask for HTML-friendly debug output
                  // $mail->Debugoutput = 'html';
                  //Set the hostname of the mail server
                  $mail->Host = 'smtp.gmail.com';
                  // use
                  // $mail->Host = gethostbyname('smtp.gmail.com');
                  // if your network does not support SMTP over IPv6
                  //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
                  $mail->Port = 465;
                  //Set the encryption system to use - ssl (deprecated) or tls
                  $mail->SMTPSecure = 'ssl';
                  //Whether to use SMTP authentication
                  $mail->SMTPAuth = true;
                  //Username to use for SMTP authentication - use full email address for gmail
                  $mail->Username = "mail.bangkalankab@gmail.com";
                  //Password to use for SMTP authentication
                  $mail->Password = "gnppbfmfhmoqzzxf";
                  //Set who the message is to be sent from
                  $mail->setFrom('admin@bangkalankab.go.id', 'Admin Email Bangkalan');
                  //Set who the message is to be sent to
                  $mail->addAddress($email, 'User Website');
                  //Set the subject line
                  $mail->Subject = $subject;
                  //Set the body
                  $mail->Body = $message;
                  //send the message, check for errors
                  $mail->send();

                  echo "<meta http-equiv='refresh' content='1; url=http://dishub.bangkalankab.go.id/nigol/'>";
                } else {
                  echo "eror!";
                }
              }

              ?>
            </div>
          </div>
          <div class="right">
            <div class="overlay"></div>
            <div class="content text">
              <h1 class="heading">Login Sistem Administrator</h1>
              <p>Dinas Perhubungan Kabupaten Bangkalan</p>
            </div>
          </div>
          <div class="clearfix"></div>
        </div>

      </div>
    </div>
  </div>
  <!-- END WRAPPER -->

  <script src="assets/vendor/jquery/jquery.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>