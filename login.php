<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Inicio de sesión</title>
	<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
</head>

<body class="bg-dark text-white">
	<section class="h-100">
		<div class="container h-100">
			<div class="row justify-content-sm-center h-100">
				<div class="col-xxl-4 col-xl-5 col-lg-5 col-md-7 col-sm-9 d-flex align-items-center justify-content-center vh-100">
					<div class="card shadow-lg vw-100 bg-dark bg-gradient">
                        <?php
                        if (isset($_SESSION['account_status'])) {
                            $type_alert = "alert-danger";
                            $message = "Error al crear la cuenta, intentelo de nuevo!";

                            if ($_SESSION['account_status'] == "success") {
                                $type_alert = "alert-success";
                                $message = "Cuenta creada satisfactoriamente";
                            }

                            echo "
                                <div class='container alert $type_alert'>
                                    <div class='text-center'>
                                        $message
                                    </div>
                                </div>";
                            unset($_SESSION['account_status']);
                        }
                        ?>
						<div class="card-body p-5">
							<h1 class="fs-4 card-title fw-bold mb-4">Iniciar Sesión</h1>
                            <form action="" method="POST">
                            <p class="text-center text-danger" id="errMessage"></p>
                            <!-- Usuario input -->
                            <div class="form-outline mb-4">
                                <label class="form-label" for="loginName">Usuario</label>
                                <input type="text" id="" class="form-control" name="username" />
                            </div>

                            <!-- Password input -->
                            <div class="form-outline mb-4">
                                <label class="form-label" for="loginPassword">Contraseña</label>
                                <input type="password" id="loginPassword" class="form-control" name="password" />
                            </div>

                            <!-- Submit button -->
                            <div class="d-flex justify-content-end">
                                <input type="submit" class="btn btn-outline-primary mb-4" name="btn_login" value="Continuar">
                            </div>

                            <div class="text-center">
                                <span>Aún no tiene una cuenta, <a href="crear_cuenta" style="text-decoration: none;">cree una aquí</a></span>
                            </div>

                            </form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php
	include_once "resources/conn.php";

	if (isset($_POST['btn_login'])) {
		$username = $_POST['username']; 
		$password = $_POST['password'];
        $salt = "";

        $users_salt_consult = "SELECT salt FROM usuarios WHERE nombre_usuario = '$username'";        
        $result_users_salt = mysqli_query($conn, $users_salt_consult);

        if (mysqli_num_rows($result_users_salt) > 0) {
            $data_salt_user = mysqli_fetch_array($result_users_salt);
            
            $salt = $data_salt_user['salt'];

            $encoded_passcode = sha1($password . $salt);

            $user_data_consult = "SELECT * FROM usuarios WHERE nombre_usuario = '$username' AND password = '$encoded_passcode'";
            $result_users_data = mysqli_query($conn, $user_data_consult);
        
            if (mysqli_num_rows($result_users_data) > 0) {
                $row = mysqli_fetch_array($result_users_data);
                
                $_SESSION['id_usuario'] = $row['id_usuario'];
                $_SESSION['nombre_usuario'] = $row['nombre_usuario'];
                $_SESSION['rol'] = $row['rol'];
                $_SESSION['owner'] = $row['owner'];

                $id_user_log = $_SESSION['id_usuario'];

                $consult_insert_log = "INSERT INTO logs(id_usuario) VALUES($id_user_log)";
                $result_insert_log = mysqli_query($conn, $consult_insert_log);

                header('Location: home');
            }
        } else {
            echo "
            <script> 
                document.getElementById('errMessage').textContent = '* Usuario y/o contraseña incorrectos!'; 
            </script>";
        }
	}
	?>

		<script src="js/bootstrap.bundle.js"></script>
	</body>
</html>