<?php


function element($needle, $haystack, $default = NULL)
{
    return array_key_exists($needle, $haystack) ? $haystack[$needle] : $default;
}

function elements($needles, $haystack, $default = NULL)
{
    $ret = [];
    foreach ($needles as $needle) {
        $ret[$needle] = element($needle, $haystack, $default);
    }
    return $ret;
}

function __($s)
{
    return htmlentities($s, ENT_QUOTES, "utf-8");
}

function redirect($url)
{
    header("Location: " . $url);
    die();
}

function isLoggedIn()
{
    return array_key_exists("user", $_SESSION);
}

function isCurrentUser($username)
{
    return user()["username"] == $username;
}

function isCurrentUserId($id)
{
    return user()["id"] == $id;
}

function isAdmin()
{
    return user()['group'] == 'ADMIN';
}

function isPost()
{
    return count($_POST) > 0;
}

function post($key, $d = "")
{
    return trim(element($key, $_POST, $d));
}

function get($key, $d = null)
{
    return element($key, $_GET, $d);
}

function user()
{
    return element("user", $_SESSION, [
        "username" => "",
        "id" => "",
        "email" => "",
        "group" => ""
    ]);
}

function pngStringFromImageInfo($file)
{
    if (empty($file["tmp_name"])) {
        return "";
    }
    $dim = getimagesize($file['tmp_name']);

    switch(strtolower($dim['mime'])) {
        case 'image/png':
            $image = imagecreatefrompng($file['tmp_name']);
            break;
        case 'image/jpeg':
            $image = imagecreatefromjpeg($file['tmp_name']);
            break;
        case 'image/gif':
            $image = imagecreatefromgif($file['tmp_name']);
            break;
        default: die();
    }

    ob_start();
    imagepng($image, null, 9);
    $contents =  ob_get_contents();
    ob_end_clean();
    return $contents;
//    return base64_encode($contents);
}
