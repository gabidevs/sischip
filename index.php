<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./css/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link href="./css/style.css" rel="stylesheet">
    <link rel="shortcut icon" href="./css/unnamed (1).ico">
    <title>Chips RedePOS</title>
</head>
<body>
    <main class="container-md">
    <div id="image">
    <img src="unnamed.png" class="img-fluid" alt="Logo Redepos" size="20px">
    </div>

    <h4><b>Consulta de Chips</button></b></h4>

    <form method="POST" action="">
        <b>ICCID:</b> <input type="text"  name="iccid" autofocus>
        <button type="submit" class="btn btn-primary btn-sm">Buscar</button> 
    </form>

    <br>

    <form method="POST" action="">

        <b>LINHA:</b> <input type="text"  name="linha">
        <button type="submit" class="btn btn-primary btn-sm">Buscar</button>

    </form>


    <br>

   <!--  <form method="GET" action="">
        <b>Situação:</b> <select name="busca-situacao">
            <option value="" selected><b></b></option>
            <option value="BLOQUEADO"><b>BLOQUEADOS</b></option>
            <option value="CANCELADO" ><b>CANCELADOS</b></option>
            <option value="SUSPENSO"><b>SUSPENSOS</b></option>
            <option value="ATIVO"><b>ATIVOS</b></option>
        </select>

        <button type="submit" class="btn btn-primary btn-sm">Buscar</button>
    </form>

    <br>

    <form method="GET" action="">
        <b>Operadora:</b> <select name="busca-operadora">
            <option value="" selected><b></b></option>
            <option value="VIVO"><b>VIVO</b></option>
            <option value="CLARO" ><b>CLARO</b></option>
            <option value="OI"><b>OI</b></option>
            <option value="ARQIA"><b>ARQIA</b></option>
        </select>

        <button type="submit" class="btn btn-primary btn-sm">Buscar</button><br>
    </form> -->
    </main>
</body>
<figure style="margin-left: 34%;">
  <figcaption class="blockquote-footer">
    <b>Obs.: Ao consultar chips da OI favor não inserir o último dígito.</b>
  </figcaption>
</figure>
</html>

<?php
require __DIR__."/vendor/autoload.php";
require __DIR__."/source/Model/Chip.php";

use function Source\Common\load;
use function Source\Models\cleanPost;
use function Source\Models\ColorCheck;
use function Source\Models\Query;


if(isset($_POST['iccid'])){
    
    $method = "iccid";

    $list = cleanPost($_POST['iccid']);
    
    set_time_limit(0);

    $list = explode(",",$list);

    $data = [];


    foreach($list as $value) {
        $value = trim($value,'\n');
        $value = trim($value,' ');
        $value = trim($value,',');
        $return = Query($value,$method);
        if (gettype($return) == 'string') {
            $naoEncontrado = [$value];
           /*  echo "<b>".$value."- Não encontrado</b><br>"; */
        }
        array_push($data, $return);
    }


    $count = count($data);

    echo "<form method='POST' action='edit.php'>

            <div style='margin-left: 1%;'>
                <img src='./css/green.ico' width='20px'> ATIVO | <img src='./css/grey.ico' width='20px'> BLOQUEADO | <img src='./css/red.ico' width='20px'> CANCELADO | <img src='./css/yellow.ico' width='20px'> SUSPENSO
            </div><br>

            <table class='table'> ";   
    echo    "<thead class='table-light table-bordered table-hover'>
                    <tr>
                        <th scope='col'>ICCID</th>
                        <th scope='col'>LINHA</th>
                        <th scope='col'>OPERADORA</th>
                        <th scope='col'>TIPO</th>
                        <th scope='col'>BASE</th>
                        <th scope='col'>SITUAÇÃO</th>
                        <th scope='col'>DATA (CANCEL/SUSP/BLOC)</th>
                        <th scope='col'>TOTAL DE REGISTROS: ".count($data)."  <a class='btn btn-primary btn-sm'  href='index.php'>Limpar</a></th>
                </thead>";

                if(isset($naoEncontrado)) {

                    foreach ($naoEncontrado as $iccid => $dado)
                    echo "<tbody class='text-bg-dark p-3 table-bordered table-hover'>   
                                <td>".$dado."</td>    
                                <td> Não encontrado </td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                            </tr>
                            </tbody>";
                }
                
                foreach($data as $key => $valor){


                     if(isset($valor[0]->iccid) ) {

                        $color = ColorCheck($valor[0]->situacao);


                        echo "<tbody class='".$color." table-bordered table-hover'>   
                                    <td>".$valor[0]->iccid."</td>    
                                    <td>".$valor[0]->linha."</td>
                                    <td>".$valor[0]->operadora."</td>
                                    <td>".$valor[0]->tipo."</td>
                                    <td>".$valor[0]->base."</td>
                                    <td>".$valor[0]->situacao."</td>
                                    <td>".$valor[0]->data_cancel."</td>
                                    <td> </td>
                                </tr>
                                </tbody>";

                    }
                }
                    echo "</table>
                    </form>";

}


/*   <button type='submit' name=".$valor[0]->iccid.">Editar</button>   */


if(isset($_POST['linha'])){

    $method = "linha";

    $list = cleanPost($_POST['linha']);
    
    set_time_limit(0);

    $list = explode(",",$list);

    $data = [];


    foreach($list as $value) {
        $value = trim($value,'\n');
        $value = trim($value,' ');
        $value = trim($value,',');
        $return = Query($value,$method);
        if (gettype($return) == 'string') {
            echo "<b>".$value."- Não encontrado</b><br>";
        }
        array_push($data, $return);
    }



    echo "<form method='POST' action='edit.php'>

            <div style='margin-left: 1%;'>
                <img src='./css/green.ico' width='20px'> ATIVO | <img src='./css/red.ico' width='20px'> BLOQUEADO | <img src='./css/grey.ico' width='20px'> CANCELADO | <img src='./css/yellow.ico' width='20px'> SUSPENSO
            </div><br>
    
            <table class='table'> ";   
    echo    "<thead class='table-light table-bordered table-hover'>
                    <tr>
                        <th scope='col'>ICCID</th>
                        <th scope='col'>LINHA</th>
                        <th scope='col'>OPERADORA</th>
                        <th scope='col'>TIPO</th>
                        <th scope='col'>BASE</th>
                        <th scope='col'>SITUAÇÃO</th>
                        <th scope='col'>DATA (CANCEL/SUSP/BLOC)</th>
                        <th scope='col'>TOTAL DE REGISTROS: ".count($data)."  <a class='btn btn-primary btn-sm'  href='index.php'>Limpar</a></th>
                    </tr>
                </thead>";
                foreach($data as $key => $valor){

                    if(isset($valor[0]->linha)) {
                    $color = ColorCheck($valor[0]->situacao);


                    echo "<tbody class='".$color." table-bordered table-hover'>   
                                <td>".$valor[0]->iccid."</td>    
                                <td>".$valor[0]->linha."</td>
                                <td>".$valor[0]->operadora."</td>
                                <td>".$valor[0]->tipo."</td>
                                <td>".$valor[0]->base."</td>
                                <td>".$valor[0]->situacao."</td>
                                <td>".$valor[0]->data_cancel."</td>
                                <td> </td>
                            </tr>
                            </tbody>";
                }
            }
    echo "</table>
    </form>";

/*     <td> <button type='submit' name=".$valor[0]->iccid.">Editar</button> */
}


if(isset($_GET['busca-operadora'])){

    $method = "operadora";

    $list = cleanPost($_GET['busca-operadora']);
    
    set_time_limit(0);

    //$list = explode("",$list);

    $data = [];

    $return = Query($list,$method);
    array_push($data, $return);

  
    if(gettype($return) != "array") {
        echo $return;
        die();
    }

    echo "<form method='POST' action=''>
    
            <table class='table'> ";   
    echo    "<thead class='table-light table-bordered table-hover'>
                    <tr>
                        <th scope='col'>ICCID</th>
                        <th scope='col'>LINHA</th>
                        <th scope='col'>OPERADORA</th>
                        <th scope='col'>TIPO</th>
                        <th scope='col'>BASE</th>
                        <th scope='col'>SITUAÇÃO</th>
                        <th scope='col'>DATA (CANCEL/SUSP/BLOC)</th>
                        <th scope='col'>TOTAL DE REGISTROS: ".count($return)."</th>
                    </tr>
                </thead>";
                foreach($return as $key => $valor){

                    $color = ColorCheck($valor->situacao);


                    echo "<tbody class='".$color." table-bordered table-hover'>   
                                <td>".$valor->iccid."</td>    
                                <td>".$valor->linha."</td>
                                <td>".$valor->operadora."</td>
                                <td>".$valor->tipo."</td>
                                <td>".$valor->base."</td>
                                <td>".$valor->situacao."</td>
                                <td>".$valor->data_cancel."</td>
                                <td></td>

                            </tr>
                            </tbody>";
                }
    echo "</table>
    </form>";

    /* <td> <button type='submit' name=".$valor[0]->iccid.">Editar</button> */
}


if(isset($_GET['busca-situacao'])){

    $method = "situacao";

    $list = cleanPost($_GET['busca-situacao']);
    
    set_time_limit(0);

    //$list = explode("",$list);

    $data = [];

    $return = Query($list,$method);
    array_push($data, $return);

  /*   echo  "<pre>";
    print_r($return);
    echo "</pre>"; */

    echo "<form method='POST' action='edit.php'>
    
            <table class='table'> ";   
    echo    "<thead class='table-light table-bordered table-hover'>
                    <tr>
                        <th scope='col'>ICCID</th>
                        <th scope='col'>LINHA</th>
                        <th scope='col'>OPERADORA</th>
                        <th scope='col'>TIPO</th>
                        <th scope='col'>BASE</th>
                        <th scope='col'>SITUAÇÃO</th>
                        <th scope='col'>DATA (CANCEL/SUSP/BLOC)</th>
                        <th scope='col'>TOTAL DE REGISTROS: ".count($return)."</th>
                    </tr>
                </thead>";
                foreach($return as $key => $valor){

                    $color = ColorCheck($valor->situacao);


                    echo "<tbody class='".$color." table-bordered table-hover'>   
                                <td>".$valor->iccid."</td>    
                                <td>".$valor->linha."</td>
                                <td>".$valor->operadora."</td>
                                <td>".$valor->tipo."</td>
                                <td>".$valor->base."</td>
                                <td>".$valor->situacao."</td>
                                <td>".$valor->data_cancel."</td>
                                <td></td>

                            </tr>
                            </tbody>";
                }
    echo "</table>
    </form>";

/*     <td> <button type='submit' name=".$valor[0]->iccid.">Editar</button> */
}

?>

