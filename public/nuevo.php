<?php
    session_start();
    function mostrarErrores($nombre){
        if(isset($_SESSION[$nombre])){
            echo "<p class='text-danger mt-2'>{$_SESSION[$nombre]}</p>";
            unset($_SESSION[$nombre]);
        }
    }

    if(isset($_POST['btn'])){
        $error=false;
        //procesamos el form
        require_once __DIR__."/../db/conexion.php";

        $nombre=trim($_POST['nombre']);
        $email=trim($_POST['email']);
        $perfil = (isset($_POST['perfil'])) ? "Admin" : "Normal";
        if(strlen($nombre)<5){
            $error=true;
            $_SESSION['nombre']="*** El campo nombre debe tener al menos 5 caracteres";

        }
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $error=true;
            $_SESSION['email']="*** Introduza un email válido";
        }
        if($error){
            mysqli_close($llave);
            header("Location:nuevo.php");
            die();
        }
        //comprobamos que NO existe el email en la tabla usuarios
        $q="select id from usuarios where email = ?";
        $stmt = mysqli_stmt_init($llave);
        if(mysqli_stmt_prepare($stmt, $q)){
            //emparejamos los parametros
            mysqli_stmt_bind_param($stmt, 's', $email);
            mysqli_stmt_execute($stmt);
            //guardamos temporalmente el susultado
            mysqli_stmt_store_result($stmt);
            $filas = mysqli_stmt_num_rows($stmt);
        }
        mysqli_stmt_close($stmt);
        if($filas!=0){
            mysqli_close($llave);
            $_SESSION['email']="*** Error el eMail YA existe";

            header("Location:nuevo.php");
            die();
        }
        //si he llegado aqui todo va bien gardaremos el usuario
        $q="insert into usuarios(nombre, email, perfil) values(?, ?, ?)";
        $stmt=mysqli_stmt_init($llave);
        if(mysqli_stmt_prepare($stmt, $q)){
            mysqli_stmt_bind_param($stmt, 'sss', $nombre, $email, $perfil);
            mysqli_stmt_execute($stmt);
        }else{
            die("Error al insertar");
        }
        mysqli_stmt_close($stmt);
        mysqli_close($llave);
        $_SESSION['mensaje']="Usuario creado con éxito";
        header("Location:main.php");
        die();

    }
    else{
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
    <h5 class="text-center mt-4">Nuevo Usuario</h5>
    <div class="container">
        <form name="aw" action="nuevo.php" method="POST" class="text-light">
            <div class="mb-3">
                <label for="n" class="form-label">Nombre Usuario</label>
                <input type="text" class="form-control" id="n" placeholder="Su Nombre" name="nombre">
                <?php
                    mostrarErrores("nombre");
                ?>
            </div>
            <div class="mb-3">
                <label for="e" class="form-label">Email Usuario</label>
                <input type="email" class="form-control" id="e" placeholder="Su Email" name="email">
                <?php
                    mostrarErrores("email");
                ?>
            </div>
            <div class="mb-3">
                <label for="p" class="form-label">Perfil Usuario</label>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="p" name='perfil'>
                    <label class="form-check-label" for="p">Administrador</label>
                </div>

            </div>
            <div class="d-flex">
                <button type="submit" class="btn btn-primary"  name="btn">
                    <i class="fas fa-save"></i> Guardar
                </button>&nbsp;
                <button type="reset" class="btn btn-warning">
                    <i class="fas fa-paintbrush"></i> Limpiar
                </button>
            </div>

        </form>
    </div>

</body>

</html>
<?php } ?>