<?php
    session_start();
    
    require_once('vendor/autoload.php');
    use App\BitRadio;
    use App\RadioSetting;

    $domain = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; 
    if($_SESSION['power'] == 'on' && (strcasecmp($domain, 'localhost/_radio/') == 0 || strcasecmp($domain, 'localhost/_radio/index.php') == 0)){
        if(floatval($_SESSION['volume']) > 0){
            $volume = '%2B'.$_SESSION['volume'];
        } else {
            $volume = $_SESSION['volume'];
        }
        header('Location: http://localhost/_radio/index.php?volume='.$volume.' db&tune='.$_SESSION['tune'].' FM&station='.$_SESSION['station']);
        exit;
    }

    $connector = new RadioSetting;
    $connector->initiateRadio();

    //Prašom naudoti debuginimui :)
    var_dump($_SESSION);
    echo '<br>';
    var_dump($connector->getSavedRadioStation(1));
    echo '<br>';
    var_dump($connector->getSavedRadioStation(2));
    echo '<br>';
    var_dump($connector->getSavedRadioStation(3));
    echo '<br>';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Radio</title>
    <link rel="stylesheet" href="sass/main.css">
</head>
<body>
    <div class="container">
        <div class="indicators">
            <div class="indicator"><?php echo (isset($_GET['station'])) ? $_GET['station'] : ''; ?></div>
            <div class="indicator"><?php echo (isset($_GET['tune'])) ? $_GET['tune'] :''; ?></div>
            <div class="indicator"><?php echo (isset($_GET['volume'])) ? $_GET['volume'] :''; ?></div>
        </div>
        <form action="controller.php" method="POST">
            <div class="btn">
                <button name="vol" value="down">Volume Down</button>
                <button name="vol" value="up">Volume Up</button>
            </div>
            <br>
            <div class="btn">
                <button name="tune" value="down">Tune Down</button>
                <button name="tune" value="up">Tune Up</button>
            </div>
            <div class="btn">
                <button name="station" value="down">Previous Station</button>
                <button name="station" value="up">Next Station</button>
            </div>
            <br>
            <div class="btn">
                <button name="save" value="1">Save 1</button>
                <button name="load" value="1">Load 1</button>
            </div>
            <div class="btn">
                <button name="save" value="2">Save 2</button>
                <button name="load" value="2">Load 2</button>
            </div>
            <div class="btn">
                <button name="save" value="3">Save 3</button>
                <button name="load" value="3">Load 3</button>
            </div>
            <!-- Žudymo mygtukas -->
            <div class="btn"><button name="kill" value="kill">kill</buttodrop table radio_settings;n></div>
            <div class="btn"><button name="power" value="power">On/Off</button></div>
        </form>
    </div>
</body>
</html>
