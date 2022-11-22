<?php
session_start();
if(!isset($_POST['id'])){
    header("Location:main.php");
    die();
}
$id=$_POST['id'];
//die($id);
require_once __DIR__."/../db/conexion.php";
$q="delete from usuarios where id=?";
$stmt=mysqli_stmt_init($llave);
if(mysqli_stmt_prepare($stmt, $q)){
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
}
mysqli_stmt_close($stmt);
mysqli_close($llave);
$_SESSION['mensaje']="Se borró el usuario";
header("Location:main.php");