<?php
session_start();
require_once __DIR__ . "/../db/conexion.php";

$q = "select * from usuarios order by id desc";
$resultado = mysqli_query($llave, $q);
//cerramos la conexion
mysqli_close($llave);
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
    <!-- cdn sweetalert2 -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <title>Ver Usuarios</title>
</head>

<body style="background-color: #a2d9ce ">
    <h5 class="text-center mt-4">Listado de Usuarios</h5>
    <div class="container">
        <a href="nuevo.php" class="my-2 btn btn-primary btn-sm">
            <i class="fa-solid fa-user-plus"></i> Crear Usuario
        </a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">NOMBRE</th>
                    <th scope="col">EMAIL</th>
                    <th scope="col">PERFIL</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($usuario = mysqli_fetch_assoc($resultado)) {
                    $color = ($usuario['perfil'] == 'Admin') ? 'text-danger' : 'text-light';
                    echo <<<TXT
                <tr>
                    <th scope="row">{$usuario['id']}</th>
                    <td>{$usuario['nombre']}</td>
                    <td>{$usuario['email']}</td>
                    <td class="$color"><b>{$usuario['perfil']}</b></td>
                    <td>
                    <form class ="form-inline" name="a" method="POST" action="borrar.php">
                    <input type="hidden" name="id" value="{$usuario['id']}" />
                    <a href="edit.php?id={$usuario['id']}" class="btn btn-warning bt-sm">
                    <i class="fas fa-edit"></i>
                    </a>
                    <button type="submit" class="btn btn-danger bt-sm">
                    <i class="fas fa-trash"></i>
                    </button>
                    </form>
                    </td>
                </tr>
                
                TXT;
                }   
                ?>

            </tbody>
        </table>
    </div>
    <?php
    if (isset($_SESSION['mensaje'])) {
        echo <<<TXT
        <script> 
            Swal.fire({
            icon: 'success',
            title: '{$_SESSION['mensaje']}',
            showConfirmButton: false,
            timer: 1500
            })
        </script>
        TXT;
        unset($_SESSION['mensaje']);
    }
    ?>
</body>

</html>