<?php
session_start();
require_once('vendor/autoload.php');
use App\BitRadio;
use App\RadioSetting;

//Atkomentuoti mygtuką index.php
if($_POST['kill']){
     session_unset(); 
}

if(empty($_SESSION)){
     $radio = new BitRadio(number_format(10.0,1), number_format(88.0,1));
     $_SESSION = $radio->startSession($_SESSION);
} else {
     $radio = new BitRadio($_SESSION['volume'], $_SESSION['tune'], $_SESSION['station']);
}

$radio->identifyButton();   
$radio->loadIndicators($_SESSION);

