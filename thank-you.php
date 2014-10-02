<?php

include 'config.php';
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

if( isset($_GET['model_id']) and isset($_GET['part_number'])){
    $model_id_from_url=$_GET['model_id'];
    $part_id_from_url=$_GET['part_number'];
    $model_name=$_GET['model'];
    $part_list=$_GET['part_list'];
    } 
else{
    $db_error=1;
}


$twig->display('header.twig', array('microfiche'=>1, 'title' => $global_meta_title,'domain' => $domain_name,
        'fiche'=>1));        
        $twig->display('fiche-thank-you.twig',array('domain' => $domain_name,'part_image'=>$microfiche_file_name,'db_error'=>$db_error,'direct_access'=>$direct_access,
                'model_id'=>$model_id_from_url,'part_number'=>$part_id_from_url,'model_name'=>$model_name,'part_list'=>$part_list));
        $twig->display('side-bar.twig',array('domain' => $domain_name));
        $twig->display('footer.twig',array('domain' => $domain_name));
        

