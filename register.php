<?php
include('connexion.php');
session_start();
$message = '';
if(isset($_SESSION['user_id']))
{
	header('location:index.php');
}

if(isset($_POST["register"]))
{
	$username = trim($_POST["username"]);
	$password = trim($_POST["password"]);
	$check_query = "
	SELECT * FROM register 
	WHERE username = :username
	";
	$statement = $connection->prepare($check_query);
	$check_data = array(
		':username'		=>	$username
	);
	if($statement->execute($check_data))	
	{
		if($statement->rowCount() > 0)
		{
			$message .= '<p><label>Le nom d\'utilisateur a deja eté enregistré</label></p>';
		}
		else
		{
			if(empty($username))
			{
				$message .= '<p><label>Le nom d\'utilisateuur est obligatoire</label></p>';
			}
			if(empty($password))
			{
				$message .= '<p><label>Le mot de passe est obligatoire</label></p>';
			}
			else
			{
				if($password != $_POST['confirm_password'])
				{
					$message .= '<p><label>Le mot de passe que vous avez saisi est incorrect</label></p>';
				}
			}
			if($message == '')
			{
				$data = array(
					':username'		=>	$username,
					':password'		=>	password_hash($password, PASSWORD_DEFAULT)
				);

				$query = "
				INSERT INTO register 
				(username, password) 
				VALUES (:username, :password)
				";
				$statement = $connection->prepare($query);
				if($statement->execute($data))
				{
					$message = "<label>Enregistremment reussi</label>";
				}
			}
		}
	}
}

?>

<html>  
    <head>  
        <title>Inscription | Chat Application</title>  
		<link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9"
            crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    </head>  
    <body>  
        <div class="container">
			<br />
			<div class="row justify-content-center">
				<div class="col-md-6">
					<div class="panel panel-default">
						  <div class="panel-heading">Enregistrement utilisateur</div>
						<div class="panel-body">
							<form method="post">
								<span class="text-danger"><?php echo $message; ?></span>
								<div class="form-group">
									<label>Entrer votre nom utilisateur</label>
									<input type="text" name="username" class="form-control" />
								</div>
								<div class="form-group">
									<label>Entrer le mot de passe</label>
									<input type="password" name="password" class="form-control" />
								</div>
								<div class="form-group">
									<label>Confirmer votre mot de passe</label>
									<input type="password" name="confirm_password" class="form-control" />
								</div>
								<div class="form-group">
									<input type="submit" name="register" class="btn btn-primary mt-2" value="Enregistrer" />
								</div>
								<div textalign="center">
									<a href="login.php" style="text-decoration: none; underline: none">Connexion</a>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			
		</div>
    </body>  
</html>
