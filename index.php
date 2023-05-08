<?php

function file_get_contents_ssl($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_REFERER, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3000); // 3 sec.
    curl_setopt($ch, CURLOPT_TIMEOUT, 10000); // 10 sec.
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

if (isset($_SERVER['HTTP_CF_CONNECTING_IP']) && filter_var($_SERVER['HTTP_CF_CONNECTING_IP'], FILTER_VALIDATE_IP)) {
    $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CF_CONNECTING_IP'];
}

$ip = $_SERVER['REMOTE_ADDR'];
if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
    $ipv6 = $ip;
    $ipv4 = "Niet gevonden";
} else {
    $ipv6 = "Niet gevonden";
    $ipv4 = $ip;
}

$provider = file_get_contents_ssl('https://ipwho.is/' . $ip);
$json = json_decode($provider, true); // true flag converts JSON to associative array
$isp = $json["connection"]["isp"];

?>

<!DOCTYPE html>
<html>

<head>
    <title>MijnIP | IT-Wizzard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Lars de Rover">
    <meta name="description" content="IT-Wizzard - IPcheck">
    <link rel="stylesheet" href="//use.fontawesome.com/releases/v5.0.7/css/all.css">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro' rel='stylesheet' type='text/css'>
    <link href='https://allfont.net/allfont.css?fonts=source-sans-pro-bold' rel='stylesheet' type='text/css' />
    <style type="text/css">
    body {
        background-image: url("https://mijn.it-wizzard.nl/assets/img/background.jpg");
        height: 100%;
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-size: cover;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        font-family: 'source sans pro', sans-serif;
    }

    .logo {
        display: block;
        margin-top: 50px;
        margin-left: auto;
        margin-right: auto;
        max-height: 100px;
    }

    h4 {
        font-family: 'Source Sans Pro Bold', arial;
        font-size: 18px;
    }

    h1 {
        font-family: 'Source Sans Pro Bold', arial;
        margin-top: 30px;
        margin-bottom: -5px;
        color: black;
    }

    h3 {
        color: black;
    }

    footer {
        background-color: #000c;
        color: #fff;
        text-align: center;
        position: fixed;
        bottom: 0;
        width: 100%;
        height: 50px;
        line-height: 50px;
        font-weight: lighter;
    }

    a {
        color: white;
    }

    /* voeg de onderstaande CSS-regels toe */
    .text-container {
        background-color: #ffffff8a;
        padding: 20px;
        border-radius: 10px;
        text-align: center;
        margin-top: 60px;
    }

    @media screen and (max-width: 1000px) {
        .text-container {
            width: 350px;
        }

        h4 {
            font-size: 15px;
        }
    }

    @media screen and (min-width: 1000px) {
        h1 {
            font-size: 30px;
        }

        p {
            font-size: 20px;
            width: 600px
        }

        h3 {
            font-size: 25px;
        }
    }

    .bar-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .bar {
        width: 190px;
        height: 25px;
        background-color: #fffc;
        /* border-right-style: solid; */
        /* border-color: #f3f3f3; */
        font-weight: 700;
        font-size: 20px;
        border-radius: 3px;
    }

    .bar1 {
        margin-top: 10px;
        width: 190px;
        height: 200px;
        background-color: #ffffffe6;
        /* border-right-style: solid; */
        /* border-color: #f3f3f3; */
        border-radius: 3px;
    }

    .bar.space {
        width: 20px;
        background: transparent;
    }

    .bar-container.up {
        margin-top: 30px;
    }

    h4.IPv6 {
        word-break: break-word;
    }
    </style>
</head>

<body>

    <!-- voeg een laadbalk toe -->
    <div class="loader"></div>
    <!-- wrap de tekst in een container div met de class "text-container" -->
    <div class="text-container">
        <img src="logo-small.png" alt="IT-Wizzard logo" class="logo">
        <h1>MijnIP</h1>
        <h3>Bekijk hier de gegegevens van uw netwerkverbinding.</h3>
        <div class="bar-container up">
            <div class="bar">IPv4</div>
            <div class="bar space"></div>
            <div class="bar">IPv6</div>
            <div class="bar space"></div>
            <div class="bar">Provider</div>
        </div>
        <div class="bar-container">
            <div class="bar1">
                <h4 class="ipv4"><span><?php echo $ipv4 ?></span></h4>
                </h4>
            </div>
            <div class="bar space"></div>
            <div class="bar1">
                <h4 class="IPv6"><span><?php echo $ipv6 ?></span></h4>
            </div>
            <div class="bar space"></div>
            <div class="bar1">
                <h4 class="provider"><span><?php echo $isp ?></span></h4>
            </div>
        </div>
    </div>
    <footer>
        <div class="footer-bar">
            <strong>Onderdeel van <a href="https://it-wizzard.nl/">IT-Wizzard</a></strong>
        </div>
    </footer>
</body>

</html>
