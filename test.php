<?php
$clientId = $_POST['clientId'];
$secretKey = $_POST['secretKey'];
$invoice = $_POST['invoice'];
$requestId = rand(1, 100000);
date_default_timezone_set('UTC');
$url = 'https://api-sandbox.doku.com';
$path = '/orders/v1/status/'.$invoice;
$timestamp      = date('Y-m-d\TH:i:s\Z');
$abc 			= 'Client-Id:'.$clientId."\n".'Request-Id:'.$requestId."\n".'Request-Timestamp:'.$timestamp."\n".'Request-Target:'.$path;
$signature  = base64_encode(hash_hmac('sha256', $abc, $secretKey, true));
$header = 'HMACSHA256='.$signature;
$curl = curl_init();
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt_array($curl, array(
  CURLOPT_URL => $url.$path,
  CURLOPT_RETURNTRANSFER => 1,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Client-Id: '.$clientId,
    'Request-Id: '.$requestId,
    'Request-Timestamp: '.$timestamp,
    'Signature: '."HMACSHA256=".$signature
  ),
));


$response = curl_exec($curl);

curl_close($curl);
$hasil = json_decode($response, true);
$sts = $hasil['transaction']['status'];
$invoicenumber = $hasil['order']['invoice_number'];
$paymentchannel = $hasil['channel']['id'];
$waktutransaksi = $hasil['transaction']['date'];
$waktutransaksi7 = date('h:i:s d-m-Y', strtotime($waktutransaksi.'+7 hours'));
$amount = $hasil['order']['amount'];
$failed = $hasil['error']['code'];

?>

<!DOCTYPE html>
<html>
  <head>
    <title>Jokul Check Status - @ashddq</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <style>
      html, body {
      display: flex;
      justify-content: center;
      font-family: Roboto, Arial, sans-serif;
      font-size: 15px;
      }
      form {
      border: 5px solid #f1f1f1;
      }
      input[type=text], input[type=password] {
      width: 100%;
      padding: 16px 8px;
      margin: 8px 0;
      display: inline-block;
      border: 1px solid #ccc;
      box-sizing: border-box;
      }
      button {
      background-color: #FF0000;
      color: white;
      padding: 14px 0;
      margin: 10px 0;
      border: none;
      cursor: grabbing;
      width: 100%;
      }
      h1 {
      text-align:center;
      font-size:18;
      }
      button:hover {
      opacity: 0.8;
      }
      .formcontainer {
      text-align: left;
      margin: 24px 50px 12px;
      }
      .container {
      padding: 16px 0;
      text-align:left;
      }
      span.psw {
      float: right;
      padding-top: 0;
      padding-right: 15px;
      }
      @media screen and (max-width: 300px) {
      span.psw {
      display: block;
      float: none;
      }
    </style>
  </head>
  <body>
  <section class="result" id="result">
      <div class="container">
      <?php if ($sts == "SUCCESS"){ ?>
        <div class="row mb-2">
          <div class="col text-center">
          <h1>Status Transaksi</h1>
            <h3>Invoice Number : <?= $invoicenumber ?></h3>
            <h3>Payment Channel : <?= $paymentchannel ?></h3>
            <h3>Date : <?= $waktutransaksi7 ?></h3>
            <h3>Amount : <?= $amount ?></h3>
            <h3>Status : <?= $sts ?></h3>
          </div>
        </div>
        </div>
        <?php }else if($sts == "FAILED"){?>
          <div class="row mb-2">
          <div class="col text-center">
          <h1>Status Transaksi</h1>
            <h3>Invoice Number : <?= $invoicenumber ?></h3>
            <h3>Payment Channel : <?= $paymentchannel ?></h3>
            <h3>Date : <?= $waktutransaksi7 ?></h3>
            <h3>Amount : <?= $amount ?></h3>
            <h3>Status : <?= $sts ?></h3>
          </div>
        </div>
        </div>
        <?php }else{ ?>
        <div class="row mb-2">
          <div class="col text-center">
          <h1>Status Transaksi</h1>
            <h3>Status : <?= $failed ?></h3>
            <h3>Please enter the correct invoice number</h3>
          </div>
        </div>
        </div>
        <?php } ?>
      </div>
      <a href="index.html"><button type="submit">Check Status</button></a>
      <div class="container" style="background-color: #eee">
        <label> <center><a href="https://www.instagram.com/ashddq">@ashddq</a></center>
    </section>
  </body>
</html>