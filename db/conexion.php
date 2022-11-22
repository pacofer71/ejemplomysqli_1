<?php
//creamos la conexion a la base de datos ejemplo1
try{
    $llave=mysqli_connect("127.0.0.1", "usuario", "secret0", "ejemplo1");
}catch(Exception $ex){
    $codError=mysqli_connect_errno();
    die("Error 
     codigo =$codError al conectar a la base de datod: ". $ex->getMessage());
}