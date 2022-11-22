<?php
session_start();
if(!isset($_GET['id'])){
    header("Location:main.php");
    die();
}
$id=$_GET['id'];
//vamos a hacer la consulta paratraernos todos los datos
//del usuario cuyo id me llega por get
require_once __DIR__."/../db/conexion.php";
$q="select * from usuarios where id=?";
$stmt = mysqli_stmt_init($llave);
if(mysqli_stmt_prepare($stmt, $q)){
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    //habria que comprobar que efectivamente me da los datos de un usuario.

    //Supuesto que
    /* bind result variables */
    mysqli_stmt_bind_result($stmt, $id, $nombre, $email, $perfil);
    mysqli_stmt_fetch($stmt);
}
mysqli_stmt_close($stmt); //cierro la llave tuneada
$checked=($perfil=="Admin") ? "checked" : "";

function mostrarErrores($nombre){
    if(isset($_SESSION[$nombre])){
        echo "<p class='text-danger mt-2'><b>{$_SESSION[$nombre]}</b></p>";
        unset($_SESSION[$nombre]);
    }
}

if(isset($_POST['btn'])){
    //procesamos los campos
    $nombreF=trim($_POST['nombre']);
    $emailF=trim($_POST['email']);
    $perfilF=(isset($_POST['perfil'])) ? "Admin" : "Normal";
    //compruebo campos
    if(strlen($nombreF)<3){
        $_SESSION['nombre']="*** Error el campo nombre debe tener al menos 3 caracteres";
        mysqli_close($llave);
        header("Location:edit.php?id=$id");
        die();
    }
    if(!filter_var($emailF, FILTER_VALIDATE_EMAIL)){
        $_SESSION['email']="*** Error el campo email debe tener un formato válido"; 
        mysqli_close($llave);

        header("Location:edit.php?id=$id");
        die();
    }
    //comprobamos que el email NO exista en otros usuarios
     //comprobamos que NO existe el email en la tabla usuarios
     $q="select id from usuarios where email = ? AND id!=?";
     $stmt = mysqli_stmt_init($llave);
     if(mysqli_stmt_prepare($stmt, $q)){
         //emparejamos los parametros
         mysqli_stmt_bind_param($stmt, 'si', $emailF, $id);
         mysqli_stmt_execute($stmt);
         //guardamos temporalmente el susultado
         mysqli_stmt_store_result($stmt);
         $filas = mysqli_stmt_num_rows($stmt);
     }
     mysqli_stmt_close($stmt);
     if($filas!=0){
         mysqli_close($llave);
         $_SESSION['email']="*** Error el eMail YA existe";

         header("Location:{$_SERVER['PHP_SELF']}?id=$id");
         die();
     }
     
     //todo ha ido bien edito el usuario
     $q="update usuarios set nombre=?, email=?, perfil=? where id=?";
     $stmt = mysqli_stmt_init($llave);
     if(mysqli_stmt_prepare($stmt, $q)){
        mysqli_stmt_bind_param($stmt, 'sssi', $nombreF, $emailF, $perfilF, $id);
        mysqli_stmt_execute($stmt);
     }
     mysqli_stmt_close($stmt);
     mysqli_close($llave);
     $_SESSION['mensaje']="Usuario con id: $id actualizado";
     header("Location:main.php");
     die();

}else{
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <!-- cdn fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Crear Usuario</title>
</head>

<body style="background-color:gray">
    <h5 class="text-center mt-4">Editar Usuario</h5>
    <div class="container">
        <form name="aw" action="edit.php?id=<?php echo $id; ?>" method="POST" class="text-light">
            <div class="mb-3">
                <label for="n" class="form-label">Nombre Usuario</label>
                <input type="text" class="form-control" id="n"
                 placeholder="Su Nombre" name="nombre" value="<?php echo $nombre ?>">
                <?php
                    mostrarErrores("nombre");
                ?>
            </div>
            <div class="mb-3">
                <label for="e" class="form-label">Email Usuario</label>
                <input type="email" class="form-control" id="e"
                 placeholder="Su Email" name="email"  value="<?php echo $email ?>">
                <?php
                    mostrarErrores("email");
                ?>
            </div>
            <div class="mb-3">
                <label for="p" class="form-label">Perfil Usuario</label>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="p" name='perfil' <?php echo $checked ?>>
                    <label class="form-check-label" for="p">Administrador</label>
                </div>

            </div>
            <div class="d-flex">
                <button type="submit" class="btn btn-primary"  name="btn">
                    <i class="fas fa-edit"></i> Editar
                </button>&nbsp;
                <a href="main.php" class="btn btn-warning">
                    <i class="fas fa-backward"></i> Volver
</a>
            </div>

        </form>
    </div>

</body>

</html>
<?php } ?>