<?php
namespace App;

class BitRadio implements Radio{
  private $volume;
  private $tune;
  private $station;
  private $power;
  const defaultStation = '----------------';

    // constructor
  public function __construct($volume, $tune, $station = Self::defaultStation, $power = 'off'){
    $this->setVolume($volume);
    $this->setTune($tune);
    $this->setStation($station);
    $this->power = $power;
  }  

    // setter & getter
  public function setVolume($volume){
    $this->volume = $volume;
  }
  public function getVolume(){
    return $this->volume;
  }
  public function setTune($tune){
    $this->tune = $tune;
  }
  public function getTune(){
    return $this->tune;
  }
  public function setStation($station){
    $this->station = $station;
  }
  public function getStation(){
    return $this->station;
  }

  public function loadIndicators($data){
    if($_SESSION['power'] == 'on' ){
      if(floatval($_SESSION['volume']) > 0){
        $volume = '%2B'.$_SESSION['volume'];
      } else {
        $volume = $_SESSION['volume'];
      }
      return  header('Location: http://localhost/_radio/index.php?volume='.$volume.' db&tune='.$_SESSION['tune'].' FM&station='.$_SESSION['station']);
    } else {
      return  header('Location: http://localhost/_radio/index.php');
    }
  }

    //Indicators
  public function showTune($data){
      return $data['tune'] = $this->getTune();
  }
  public function showRadioStationName($data){
    $strTune = str_replace('.',',',strval($data['tune']));
    $stations = $this::stationInArea;
    if(in_array($strTune, $stations)){
      $key = array_search($strTune, $stations);
      return $data['station'] = $key;
    } else {
      return $data['station'] = Self::defaultStation;
    }
  }
  public function showVolume($data){
    return $data['volume'] = $this->getVolume();
  }
  public function startSession(){
    if(empty($_SESSION)){
      $_SESSION['volume'] = $this->getVolume();
      $_SESSION['tune'] = $this->getTune();
      $_SESSION['station'] = $this->getStation();
      $_SESSION['power'] = $this->power;
      return $_SESSION;
    }
  }

  public function identifyButton(){
    if($_POST){
      if($_POST['power']){
        $this->power();
      }
      if($_SESSION['power']=='on'){
        if($_POST['vol'] == 'down'){
          $this->volumeDown();
        }
        if($_POST['vol'] == 'up'){
          $this->volumeUp();
        }
        if($_POST['tune'] == 'down'){
          $this->tuneDown();
        }
        if($_POST['tune'] == 'up'){
          $this->tuneUp();
        }
        if($_POST['station'] == 'down'){
          $this->goToNexStationDown();
        }
        if($_POST['station'] == 'up'){
          $this->goToNexStationUp();
        }
        if($_POST['save'] == 1){
          $this->savePresets1();
        }
        if($_POST['load'] == 1){
          $this->loadPresets1();
        }
        if($_POST['save'] == 2){
          $this->savePresets2();
        }
        if($_POST['load'] == 2){
          $this->loadPresets2();
        }
        if($_POST['save'] == 3){
          $this->savePresets3();
        }
        if($_POST['load'] == 3){
          $this->loadPresets3();
        }
      }
    }
    return $data;
  }
  //Buttons
  public function power(){
    if($_SESSION['power']=='off'){
      $_SESSION['power'] = 'on';
    } elseif($_SESSION['power'] = 'on'){
      $_SESSION['power']='off';
    }
    return null;
  }
  public function volumeUp(){
    if(number_format(floatval($_SESSION['volume']),1) < Self::maxVol){
      $_SESSION['volume'] = number_format(floatval($_SESSION['volume']) + 0.5 ,1);
    }
  }
  public function volumeDown(){
    if(number_format(floatval($_SESSION['volume']),1) > Self::minVol){
      $_SESSION['volume'] = number_format(floatval($_SESSION['volume']) - 0.5 ,1);
    }
  }
  public function goToNexStationUp(){
    $tune = $_SESSION['tune'];
    $stations = $this::stationInArea;
    $code = true;
    if( $tune <= Self::maxFm){
      while($code == true){
        $tune += 0.1;
        if($tune >= Self::maxFm){
          $tune = Self::minFm;
        }
        $stringTune = str_replace('.',',',strval(number_format($tune,1)));
        if(in_array($stringTune, $stations)){
          $code = false;
        }
      }
      $_SESSION['tune'] = number_format($tune,1);
      $_SESSION['station'] = Self::showRadioStationName($_SESSION);
    }
  }
  public function goToNexStationDown(){
    $tune = $_SESSION['tune'];
    $stations = $this::stationInArea;
    $code = true;
    if( $tune >= Self::minFm){
      while($code == true){
        $tune -= 0.1;
        if($tune <= Self::minFm){
          $tune = Self::maxFm;
        }
        $stringTune = str_replace('.',',',strval(number_format($tune,1)));
        if(in_array($stringTune, $stations)){
          $code = false;
        }
      }
      $_SESSION['tune'] = number_format($tune,1);
      $_SESSION['station'] = Self::showRadioStationName($_SESSION);
    }
  }
  public function tuneDown(){
    if(number_format(floatval($_SESSION['tune']),1) > Self::minFm){
      $_SESSION['tune'] = number_format(floatval($_SESSION['tune'] - 0.1),1);
      $_SESSION['station'] = Self::showRadioStationName($_SESSION);
    } elseif (number_format(floatval($_SESSION['tune']),1) == Self::minFm){
      $_SESSION['tune'] = number_format(floatval(Self::maxFm),1);
      $_SESSION['station'] = Self::showRadioStationName($_SESSION);
    }
  }
  public function tuneUp(){
    if(number_format(floatval($_SESSION['tune']),1) < Self::maxFm){
      $_SESSION['tune'] = number_format(floatval($_SESSION['tune'] + 0.1),1);
      $_SESSION['station'] = Self::showRadioStationName($_SESSION);
    } elseif (number_format(floatval($_SESSION['tune']),1) == Self::maxFm){
      $_SESSION['tune'] = number_format(floatval(Self::minFm),1);
      $_SESSION['station'] = Self::showRadioStationName($_SESSION);
    }
  }
  public function savePresets1(){
    $con = new RadioSetting;
    $con->updateRadioStation(1, $_SESSION['station'],$_SESSION['tune']);
  }
  public function savePresets2(){
    $con = new RadioSetting;
    $con->updateRadioStation(2, $_SESSION['station'],$_SESSION['tune']);
  }
  public function savePresets3(){
    $con = new RadioSetting;
    $con->updateRadioStation(3, $_SESSION['station'],$_SESSION['tune']);
  }
  public function loadPresets1(){
    $con = new RadioSetting;
    $row = $con->getSavedRadioStation(1);
    $_SESSION['station'] = $row['STATION'];
    $_SESSION['tune'] = $row['TUNE'];
  }
  public function loadPresets2(){
    $con = new RadioSetting;
    $row = $con->getSavedRadioStation(2);
    $_SESSION['station'] = $row['STATION'];
    $_SESSION['tune'] = $row['TUNE'];
  }
  public function loadPresets3(){
    $con = new RadioSetting;
    $row = $con->getSavedRadioStation(3);
    $_SESSION['station'] = $row['STATION'];
    $_SESSION['tune'] = $row['TUNE'];
  }
}