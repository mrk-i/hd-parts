<!--Harley Davidson microfiche finder-->
<?php
include 'config.php';
require 'lib/class.phpmailer.php';
require 'lib/class.smtp.php';
////////////////////////////////////////
// Include ezSQL core DB wrapper
include_once "lib/ez_sql_core.php";
include_once "lib/functions.php";
// Include ezSQL database specific component
include_once "lib/ez_sql_mysql.php";
////////////////////////////////////////
require_once('lib/Twig/Autoloader.php');
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('templates');

//check if caching is set
if (set_caching == 0) {
    $cache_directory = array('cache' => false);
} else {
    $cache_directory = array('cache' => 'cache');
}
//set caching directory
$twig = new Twig_Environment($loader, $cache_directory);
require_once('lib/recaptcha/recaptchalib.php');
//session_start();

//var_dump($_POST);
//var_dump($_SESSION);

if(empty($_POST) and !(isset($_GET['id']) and isset($_GET['part']))){
    $direct_access=1;
}

else{
    if (!empty($_POST)){
    //         var_dump($_POST);
    $mail = new PHPMailer;
    //$mail->SMTPDebug = 3;                               // Enable verbose debug output

    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.zoho.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'your email here';                 // SMTP username
    $mail->Password = 'your password here';                           // SMTP password
    $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 465;                                    // TCP port to connect to

    $mail->From = 'your email here';
    $mail->FromName = 'hd-parts.org'; //your domain here
    $mail->addAddress($_POST['email']);     // Add a recipient
    //$mail->addAddress('mrkson@zoho.com');               // Name is optional
    $mail->addReplyTo('no-reply@hd-parts.org', 'No Reply');
    //$mail->addCC('cc@example.com');q
    $mail->addBCC('mrkson@zoho.com');

    $mail->WordWrap = 50;                                 // Set word wrap to 50 characters
    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
    $mail->isHTML(true);                                  // Set email format to HTML

    $mail->Subject = 'Your FREE microfiche';
    $mail->Body    = '<a href="http://your-url-to-motorcycle-fiche-here'.$_POST['microfiche'].'">Free microfiche</a> <br>'
            .$_POST['part_list']. '<br> '
            . 'Thank you for using <a href="http://hd-parts.org">hd-parts.org</a>';
    $mail->AltBody = 'Copy url: http://your-url-to-motorcycle-fiche-here/'.$_POST['microfiche'].'and past into your browser <br>'
            . 'Thanks for using hd-parts.org';

    if(!$mail->send()) {
        $error_sending_email=1;
        $mail_error='Mailer Error: ' . $mail->ErrorInfo;
    } else {
        header( 'HTTP/1.1 303 See Other' );
        header( 'Location: http://your-domain.com/thank-you.php?model_id='.$_POST['model_id'].'&part_number='.$_POST['part_number'].'&model='.$_POST['model_name'].
                '&part_list='.$_POST['part_list'] );
        exit();
    }

}
    if( isset($_GET['id']) and isset($_GET['part'])){
        $model_id_from_url=$_GET['id'];
        $part_id_from_url=$_GET['part'];
        $model=$_GET['model'];
        $part_list=$_GET['part_list'];
        $db = new ezSQL_mysql($username,$password,$database, 'localhost');
        $scheme_url=$db->get_row("SELECT image_url FROM part_list_image_urls WHERE model_id='$model_id_from_url' AND part_number='$part_id_from_url'");
        if($scheme_url){
        $part_image=explode('/',$scheme_url->image_url);
        $microfiche_file_name=end($part_image);
        }
        else{ 
            $db_error=1;}
    } 

}
$twig->display('header.twig', array('microfiche'=>1, 'title' => $global_meta_title,'domain' => $domain_name,
        'fiche'=>1));        
        $twig->display('fiche.twig',array('domain' => $domain_name,'recaptcha'=>$recaptcha,'submit_button'=>$submit_button,'captcha_error'=>$captcha_error,
           'send_email'=>$send_email,'thank_you'=>$thank_you,'part_image'=>$microfiche_file_name,'db_error'=>$db_error,'direct_access'=>$direct_access,
            'model_id'=>$model_id_from_url,'part_number'=>$part_id_from_url,'mail_error'=>$mail_error,'error_sending_email'=>$error_sending_email,
            'model'=>$model,'part_list'=>$part_list));
        $twig->display('side-bar.twig',array('domain' => $domain_name));
        $twig->display('footer.twig',array('domain' => $domain_name));