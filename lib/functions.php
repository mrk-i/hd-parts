<?php
//return current url
function current_url() {
    $isHTTPS = ( isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on" );
    $isPort = ( isset($_SERVER["SERVER_PORT"]) && ((!$isHTTPS && $_SERVER["SERVER_PORT"] != "80") || ($isHTTPS && $_SERVER["SERVER_PORT"] != "443")));

    $port = ( $isPort ) ? ( ':' . $_SERVER["SERVER_PORT"] ) : '';

    //On some setups like nginx and php-fastcgi, REQUEST_URI include the query string
    if (($pos = strpos($_SERVER['REQUEST_URI'], '?')) === false) {
        // REQUEST_URI include the query string, it should be appended:

        $isQuery = ( isset($_SERVER["QUERY_STRING"]) && $_SERVER["QUERY_STRING"] != '');
        $query = ( $isQuery ) ? ( '?' . $_SERVER["QUERY_STRING"] ) : '';

        $url = ( $isHTTPS ? 'https://' : 'http://')
                . $_SERVER["SERVER_NAME"] . $port . $_SERVER["REQUEST_URI"] . $query;
    } else {
        // the query string is already included in $_SERVER["REQUEST_URI"], no need to append it
        $url = ( $isHTTPS ? 'https://' : 'http://')
                . $_SERVER["SERVER_NAME"] . $port . $_SERVER["REQUEST_URI"];
    }

    return $url;
}

//extract_state_name function will extract a part of text between two delimeters /georgia/ it will return georgia
function extract_state_name($string, $start, $end) {
    $pos = stripos($string, $start);
    $str = substr($string, $pos);
    $str_two = substr($str, strlen($start));
    $second_pos = stripos($str_two, $end);
    $str_three = substr($str_two, 0, $second_pos);
    $state_name = trim($str_three); // remove whitespaces
    return $state_name;
}

//function that checks if a subdomain exists in URL
function hasSubdomain($url) {
    $parsed = parse_url($url);
    $exploded = explode('.', $parsed["host"]);
    return (count($exploded) > 2);
}

//get subdomain from url, if subdomain exist it will return 1, url must include http://
function get_subdomain($url) {
    $parsedUrl = parse_url($url);
    $host = explode('.', $parsedUrl['host']);
    $subdomain = $host[0];
    return $subdomain;
}

function check_subdomain($sub,$action){
    $str = $sub;
    if ($action=='cut'){
    $str = preg_replace("/ /", '-', $str);
    }
 else {
    $str = preg_replace("/-/", ' ', $str);
    }
    return $str;
}

//search for categroy with same letter and returns an array
function find_category($first_letter,$categories){
    
  while ($i < count($categories)) {
            //$first_letter_of_city = substr($category[$i]->city, 0, 1);
            if ($first_letter_of_city == strtoupper($subfolder)) {
                //store city name into array
                $city[$i] = $category[$i]->city;
                echo $city[$i];
                //check if city is made of two words
                if (preg_match('/\s/',$city[$i]) == TRUE){$city[$i]= check_subdomain($city[$i],'cut');}
                }
            $i++;
        }
        //s   
}

function save_google_image_local($img_name,$img_url) {
$ch = curl_init($thumb);
$fp = fopen('/my/folder/flower.gif', 'wb');
curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_exec($ch);
curl_close($ch);
fclose($fp);
}

function create_img($domain,$oem_number){
// Set the content-type, olny if you want to show image in browser
//header('Content-Type: image/png');
// The text to draw
$text=$oem_number;
$text1=$domain;
// Replace path by your own font path
$font_2 = './ttf/arial.ttf';
$font = './ttf/arial.ttf';
$font_size=20;
$font_size_2=18;
// Create the image
//$imgg_width='160';
//$imgg_height='160';
//$img = imagecreatetruecolor($imgg_width,$imgg_height);
$img=imagecreatefrompng ( "images/hd-parts.png" );
// Create some colors
$white = imagecolorallocate($img, 255, 255, 255);
$orange = imagecolorallocate($img, 204, 102, 0);
//imagefilledrectangle($img, 0, 0, 399, 29, $black);
//$text=wrap($font_size,0,$font,$text,$imgg_width);
$size_name = imagettfbbox(35, 5, $font_2, $text1);
$x = ceil((250 - $size_name[2]) / 2); 
$y = ceil((20 - $size_name[5]) / 2); 
$size = imagettfbbox(28, 18, $font, $text);
$x1 = ceil((180 - $size[2]) / 2); 
$y1 = ceil((70 - $size_name[5]) / 2); 
// Add some shadow to the text
//imagettftext($img, $font_size_2, 0, abs($size_name[0]), abs($size_name[5]), $grey, $font_2, $text1);
// Add the text
//imagettftext($img, $font_size_2, 10, abs($size[0]),abs($size[5]), $grey, $font, "OEM#:");
imagettftext($img, $font_size_2, 0, $x,$y, $orange, $font_2, $text1);
imagettftext($img, $font_size, 0, $x1,$y1, $white, $font, $text);
// output image to browser
//imagepng($img);
// save image
$save_as="images/oem/".strtolower($oem_number)."-hd-oem".".png";
imagepng($img,$save_as);
imagedestroy($img);
return $save_as;
}


function check_email_address($email) {
        // First, we check that there's one @ symbol, and that the lengths are right
        if (!preg_match("/^[^@]{1,64}@[^@]{1,255}$/", $email)) {
            // Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
            return false;
        }
        // Split it into sections to make life easier
        $email_array = explode("@", $email);
        $local_array = explode(".", $email_array[0]);
        for ($i = 0; $i < sizeof($local_array); $i++) {
            if (!preg_match("/^(([A-Za-z0-9!#$%&'*+\/=?^_`{|}~-][A-Za-z0-9!#$%&'*+\/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/", $local_array[$i])) {
                return false;
            }
        }
        if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
            $domain_array = explode(".", $email_array[1]);
            if (sizeof($domain_array) < 2) {
                return false; // Not enough parts to domain
            }
            for ($i = 0; $i < sizeof($domain_array); $i++) {
                if (!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/", $domain_array[$i])) {
                    return false;
                }
            }
        }

        return true;
    }