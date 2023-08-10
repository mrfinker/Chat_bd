<?php
include("connexion.php");
session_start();
$query = "
SELECT * FROM register WHERE user_id != '".$_SESSION['user_id']."'";
$statement = $connection->prepare($query);
$statement->execute();
$result = $statement->fetchAll();

$output = '
<table class="table table-bordered table-striped">
 <tr>
  <td>Nom utilisateur</td>
  <td>Status</td>
  <td>Action</td>
 </tr>
';

foreach($result as $row)
{
 $status = '';
 $current_timestamp = strtotime(date("Y-m-d H:i:s") . ' - 10 second');
 $current_timestamp = date("Y-m-d H:i:s", $current_timestamp);
 $user_last_activity = fetch_utilisateur_derniere_activité($row['user_id'], $connection);
 if($user_last_activity > $current_timestamp){
    $status = '<span class="label label-success">En ligne</span>';
 } else{
    $status = '<span class="label label-danger">Déconnecté</span>';
 }
 $output .= '
 <tr>
  <td>'.$row['username'].'</td>
  <td>'.$status.'</td>
  <td><button type="button" class="btn btn-info btn-xs start_chat" data-touserid="'.$row['user_id'].'" data-tousername="'.$row['username'].'">commencer le chat</button></td>
 </tr>
 ';
}

$output .= '</table>';

echo $output;

?>