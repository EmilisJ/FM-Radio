<?php
namespace App;

class RadioSetting extends Dbh {
    //Pravartus metodas :)
    // public function dropRadioTable(){
    //     $pdo = $this->connect();
    //     $sql = "DROP TABLE IF EXISTS radio_settings;";
    //     $stmt = $pdo->prepare($sql);
    //     $stmt->execute();
    // }

    public function migrateRadioTable(){
        $pdo = $this->connect();
        $sql = "CREATE TABLE IF NOT EXISTS radio_settings (
            ID   INT  AUTO_INCREMENT NOT NULL,
            STATION VARCHAR (20)        NOT NULL,
            TUNE  DECIMAL (4,1)      NOT NULL,     
            PRIMARY KEY (ID)
         );";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }

    public function insertRadioStation(){
        $station = BitRadio::defaultStation;
        $pdo = $this->connect();
        $sql = "INSERT INTO radio_settings (STATION,TUNE)
                VALUES
                ('".$station."', 88.0);";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }

    public function updateRadioStation($id, $station, $tune){
        $pdo = $this->connect();
        $sql = "UPDATE radio_settings
                SET STATION = '".$station."', TUNE = ".$tune."
                WHERE ID = ".$id.";";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    } 

    public function getSavedRadioStation($id){
        $stmt = $this->connect()->query("select * from radio_settings where ID = ".$id.";");
        $row = $stmt->fetch();
        return $row;
    }

    public function initiateRadio(){
        $this->migrateRadioTable();
        $result = $this->connect()->prepare("SELECT count(*) FROM radio_settings");
        $result->execute();
        $columnsCount = $result->fetchColumn();
        if($columnsCount == 0){
            for($i=0;$i<3;$i++){
                $this->insertRadioStation();
            }
            header('Location: http://localhost/_radio/index.php');
            exit;
        }
    }
}