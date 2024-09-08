<?php 
    $dbHost = 'localhost';
    $dbUsername = 'root';
    $dbPassword = '';
    $dbName ='crud';

    $conection = new mysqli(  $dbHost,$dbUsername,  $dbPassword,  $dbName);

    // if($conection->connect_errno){
    //     echo"erro de conexao";
    // } else{
    //     echo "conexao efectuada <br/>";
    // }
?>