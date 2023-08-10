<?php
// Modifier le mot de passe en "" pour un SGBD n'ayant pas de mot de passe
try {
    $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
    $connection = new PDO('mysql:host=localhost;dbname=chat_bd', 'root', 'jenesaispas', $pdo_options);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

function fetch_utilisateur_derniere_activité($user_id, $connection){
    $query = "SELECT * FROM login_details WHERE user_id = '$user_id' ORDER BY last_activity DESC LIMIT 1";
    $statement = $connection->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    foreach($result as $row){
        echo $row["last_activity"];
    }
}

function fetch_utilisateur_chat_historique($from_user_id, $to_user_id, $connection){
    $query = "SELECT * FROM chat_message WHERE (from_user_id = '".$from_user_id."' AND to_user_id = '".$to_user_id."') OR (from_user_id = '".$to_user_id."' AND to_user_id = '".$from_user_id."') ORDER BY timestamp DESC";
    $statement = $connection->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    $output = '<ul class="list-unstyled">';
    foreach($result as $row){
        echo $row["chat_message"];
    }
}
    
?>
