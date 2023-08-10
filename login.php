<?php
include("connexion.php");
session_start();
$message="";
if(isset($_SESSION["user_id"])){
    header("location: index.php");
}
if(isset($_POST["login"])){
    $query="SELECT * FROM register WHERE username = :username";
    $statement = $connection->prepare($query);
    $statement->execute([
        ":username"=>$_POST["username"]
    ]);
    $count = $statement->rowCount();
    if($count>0){
        $result = $statement->fetchAll();
        foreach($result as $row){
            if(password_verify($_POST["password"],$row["password"])){
                $_SESSION["user_id"] = $row["user_id"];
                $_SESSION["username"] = $row["username"];
                $sub_query = "INSERT INTO login_details (user_id) VALUES ('".$row['user_id']."')";
                $statement = $connection->prepare($sub_query);
                $statement->execute();
                $_SESSION["login_details_id"] = $connection->lastInsertId();
                header("location: index.php");
            }
            else{
                $message = "<label>Mot de passe incorrect</label>";
            }
        }
    }
    else{
        $message = "<label>Utilisateur introuvable</label>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Connexion | Chat Application</title>
        <link
            rel="stylesheet"
            href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
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
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">Connexion à votre compte</div>
                        <div class="panel-body">
                            <form action="" method="post">
                                <p class="text-danger"><?php echo $message; ?></p>
                                <div class="form-group">
                                    <label class="mb-2">Nom d'utilisateur</label>
                                    <input type="text" name="username" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label class="mb-2">Entrer votre mot de passe</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <input type="submit" name="login" class="btn btn-primary mt-2" value="Connexion">
                                </div>

                                <div class="d-flex justify-content-end"></div>
                                <p  class="mt-2"><a href="register.php" style="text-decoration: none; underline: none">Créer un compte</a></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <br>
        </div>
    </body>
</html>