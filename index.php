<?php
include("connexion.php");
session_start();
if(!isset($_SESSION['user_id'])){
    header("location: register.php");
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Accueil | Chat Application</title>
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
            <br>
            <h3 textalign="center">Votre boite de messagerie</h3><br>

            <div class="table-responsive">
                <h4 textalign="center">utilisateur en ligne</h4>
                <p textalign="right">Bonjour -
                    <?php echo $_SESSION['username'];?>
                    -
                    <a href="logout.php" class="btn btn-danger">Déconnexion</a>
                </p>
                <div>
                    <p>Votre boite de dialogue</p>
                    <input type="text" id="user_details" class="form-control" style="height: 200px;">
                </div>
                <p class=" justify-content-center align-items-center d-flex mt-5" style="font-size: 20px"><a href="" class="btn btn-primary start_chat">Envoyer le message</a></p>
                <div class="user-details"></div>
                <div class="user-model-details"></div>
            </div>
        </div>

        <script>
            $(document).ready(function () {
                fetch_user();
                function fetch_user() {
                    $.ajax({
                        url: "fetch_utilisateur.php",
                        method: "POST",
                        success: function (data) {
                            $('#user_details').html(data);
                        }
                    })
                }
                
                setInterval(function () {
                    mise_a_jour_derniere_activité();
                    fetch_user();
                }, 5000);


                function mise_a_jour_derniere_activité() {
                    $.ajax({url: "m_a_j.php", success: function () {}})
                }

                function creation_boite_dialogue(to_user_id, to_user_name) {
                    let modal_content = '<div id="user_dialog' + to_user_id + '" class=user-dialog"' +
                            ' title="Vous avez un chat avec ' + to_user_name + '">';
                    modal_content += '<div style="height:400px; border:1px solid #ccc; overflow-y: scroll; margin-bo' +
                            'ttom:24px; padding:16px;" class="chat_history" data-touserid="' +
                            to_user_id + '" id="chat_history_' + to_user_id + '">';
                    modal_content += '</div>';
                    modal_content += '<div class="form-group">';
                    modal_content += '<textarea name="chat_message_' + to_user_id + '" id="chat_message_' +
                            to_user_id + '" class="form-control"></textarea>';
                    modal_content += '</div><div class="form-group" align="right">';
                    modal_content += '<button type="button" name="send_chat" id="' + to_user_id + '" class="btn btn-' +
                            'info send_chat">Envoyer</button></div></div>';
                    $('#user_model_details').html(modal_content);
                }

                $(document).on('click', '.start_chat', function () {
                    let to_user_id = $(this).data('touserid');
                    let to_user_name = $(this).data('tousername');
                    creation_boite_dialogue(to_user_id, to_user_name);
                    $('#user_dialog' + to_user_id).dialog({
                        autoOpen: false,
                        width:400
                    });
                    $('#user_dialog' + to_user_id).dialog('open');
                });

                $(document).on('click', '.send_chat', function () {
                    let to_user_id = $(this).attr('id');
                    let chat_message = $('#chat_message_' + to_user_id).val();
                    $.ajax({
                        url: "envoyer_chat.php",
                        method: "POST",
                        data: {
                            to_user_id: to_user_id,
                            chat_message: chat_message},
                            success: function (data) {
                                $('#chat_history_' + to_user_id).val('data');
                                $('#chat_message_' + to_user_id).val('');
                        }
                    })
                });

                function fetch_user_chat_history(to_user_id) {
                    $.ajax({
                        url: "fetch_user_chat_history.php",
                        method: "POST",
                        data: {
                            to_user_id: to_user_id},
                        success: function (data) {
                            $('#chat_history_' + to_user_id).html(data);
                        }
                    })
                }

                function update_chat_history_data(){
                    $('.chat_history').each(function(){
                        let to_user_id = $(this).data('touserid');
                        fetch_user_chat_history(to_user_id);
                });

                $(document).on('click', '.ui-button-icon', function () {
                    $('.user_dialog').dialog('destroy').remove;
                });

                $(document).on('focus', '.chat_message', function () {
                    let is_type = 'yes';
                    $.ajax({
                        url: "update_is_type_status.php",
                        method : "POST",
                        data: {
                            is_type: is_type},
                        success: function (data) {
                            
                        }
                    })
                });

                $(document).on('blur', '.chat_message', function () {
                    let is_type = 'no';
                    $.ajax({
                        url: "update_is_type_status.php",
                        method : "POST",
                        data: {
                            is_type: is_type},
                        success: function (data) {
                            
                        }
                    })
                });
                
            }
        });
        </script>
    </body>
</html>