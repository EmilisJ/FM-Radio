<?php
session_start();
require_once('vendor/autoload.php');
use App\BitRadio;
use App\RadioSetting;

BitRadio::playTheRadio($_SESSION);


