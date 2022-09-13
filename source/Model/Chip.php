<?php

namespace Source\Models;

use CoffeeCode\DataLayer\Connect;
use League\Csv\Reader;
use PDO;

require __DIR__."/../../vendor/autoload.php";

function InsertCSV() {



        $conexao = Connect::getInstance();
        $error = Connect::getError();

        $query = $conexao->prepare(
            "INSERT INTO chip (iccid,linha,operadora,tipo,base,situacao,data_cancel) VALUES (:iccid,:linha,:operadora,:tipo,:base,:situacao,:data_cancel)"
        );

        $csv = Reader::createFromPath('chips.csv')->setHeaderOffset(0);

        set_time_limit(0);

        foreach ($csv as $insert) {
            $query->bindValue(':iccid', $insert['ICCID'], PDO::PARAM_STR);
            $query->bindValue(':linha', $insert['LINHA'], PDO::PARAM_STR);
            $query->bindValue(':operadora', $insert['OPERADORA'], PDO::PARAM_STR);
            $query->bindValue(':tipo', $insert['TIPO'], PDO::PARAM_STR);
            $query->bindValue(':base', $insert['BASE'], PDO::PARAM_STR);
            $query->bindValue(':situacao', $insert['SITUAÇÃO'], PDO::PARAM_STR);
            $query->bindValue(':data_cancel',$insert['DATA'], PDO::PARAM_STR);
            $query->execute();
        }

        if(isset($error)) {
            return $error->getMessage();
            die();
        }
        
        return "Sucess";

    }

    
    function Query($list,$method) {
        
        if($method == "iccid") {
            $sql = "SELECT * FROM chip WHERE iccid IN (?)";
        } elseif ($method == "linha"){
            $sql = "SELECT * FROM chip WHERE linha IN (?)";
        } elseif($method == "operadora") {
            $sql = "SELECT * FROM chip WHERE operadora = ?";
        } elseif($method == "situacao") {
            $sql = "SELECT * FROM chip WHERE situacao = ?";
        }

        $conexao = Connect::getInstance();
        $error = Connect::getError();
        $query = $conexao->prepare($sql);
        $query->execute(array($list));
        $list = $query->fetchAll();
    
        if(isset($error)) {
            return $error->getMessage();
            die();
        }
    
        if($list == null) {
    
            return "<P class='container-md'><b>Dados não encontrados</b></P>";
        } else {
    
            return $list;
        }
    
        
    }

    function ColorCheck($color) {
        switch ($color) {
            case 'ATIVO':
                $color = "text-bg-success p-3";
                return $color;
            case 'BLOQUEADO':
                $color = "text-bg-secondary p-3";
                return $color;
            case 'SUSPENSO':
                $color = "text-bg-warning p-3";
                return $color;
            case 'CANCELADO':
                $color = "text-bg-danger p-3";
                return $color;
            break;
        }
    }
    
    function formCheck() {

        if (isset($_POST['iccid']) and isset($_POST['linha']) and isset($_GET['busca-operadora']) and isset($_GET['busca-situacao'])) {

            echo "Preencha somente um dos campos";

            die();

        }elseif(isset($_POST['linha']) and isset($_GET['busca-operadora']) and isset($_GET['busca-situacao'])) {

            echo "Preencha somente um dos campos";

            die();

        }elseif(isset($_GET['busca-operadora']) and isset($_GET['busca-situacao'])){
            
            echo "Preencha somente um dos campos";

            die();

        }elseif(isset($_POST['iccid']) or isset($_POST['linha']) or isset($_GET['busca-operadora']) or isset($_GET['busca-situacao'])) {



        }
            
        }




    function cleanPost($post){
        $post = trim($post);
        $post = stripslashes($post);
        $post = htmlspecialchars($post);
    
        return $post;
    }