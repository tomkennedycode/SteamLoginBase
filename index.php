<?php
require 'steam_api.php';
require 'openid.php';
session_start();
try {
    # Change 'example.org' to your domain name.
    $domain = '';
    $openid = new LightOpenID($domain);
    
    if (!$openid->mode) {
        if (isset($_GET['login'])) {
            $openid->identity = 'http://steamcommunity.com/openid';
            header('Location: ' . $openid->authUrl());
        } }
        if(!isset($_SESSION["steamID"])) { echo '<form action="?login" method="post">
        <a href="?login=1"><img src="https://steamcommunity-a.akamaihd.net/public/images/signinthroughsteam/sits_01.png"></a>
        </form>'; } else {$profilePicURL = 'http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=' . $apiKey . '&steamids=' . $_SESSION["steamID"]; 
                   $json_object = file_get_contents($profilePicURL);
                   $json_decoded = json_decode($json_object, true);
                   echo "<img src=\"" . $json_decoded['response']['players'][0]['avatarfull'] . "\">";
                   } 

    if(!$openid->mode) {
    } elseif($openid->mode == 'cancel') {
        echo 'User has cancelled authentication!';
    } elseif($openid->validate()) {
            $id = $openid->identity;
            $ptn = '/^http:\/\/steamcommunity\.com\/openid\/id\/(7[0-9]{15,25}+)$/';
            preg_match($ptn, $id, $matches);
            $_SESSION["steamID"] = $matches[1];
            header("Location: http://localhost");
    } else {throw new Exception("Error processing login."); }

} catch(ErrorException $e) {
    echo $e->getMessage();
}
