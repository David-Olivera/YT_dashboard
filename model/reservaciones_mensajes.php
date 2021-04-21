<?php
    require_once('../config/conexion.php');
    if (isset($_POST['id_reservation'])) {
        $id_reservation = $_POST['id_reservation'];
        $id_user = $_POST['id_user'];
        $query_messages = "SELECT * FROM bitacora AS B INNER JOIN users AS U ON B.id_user = U.id_user WHERE B.id_reservation = '$id_reservation' ORDER BY B.register_date asc   ;";
        $result_message = mysqli_query($con, $query_messages);
        if ($result_message) {
            if (mysqli_num_rows($result_message) > 0) {
                $template = "";
                $message = "";
                while ($row = mysqli_fetch_assoc($result_message)) {
                    if ($id_user == $row['id_user']) {
                        $template = "    
                            <div class='chat-messages-me'>
                                <div class='message-me'>
                                    <div class='me-user'>                    
                                    <small class=''>@Yo</small><br>
                                    </div>
                                    <small>{$row['comments']} </small><br>
                                </div>
                                <div class='from-me'>
                                    <small class='msj-date'>{$row['register_date']}</small>
                                </div>
                            </div>
                        ";
                        echo $template;
                    }
                    if($id_user != $row['id_user']){
                        $template = "
                            <div class='chat-messages'>
                                <div class='message'>
                                    <div class='user'>                    
                                        <small class=''>@{$row['username']}</small><br>
                                    </div>
                                    <small>{$row['comments']}</small><br>
                                </div>
                                <div class='from'>
                                    <small class='msj-date'>{$row['register_date']}</small>
                                </div>
                            </div>
                        ";
                        echo $template;
                    }
                }
            }else{
                $template= "
                            <div class='text-center'>
                                <small><p>No se encontró ningún mensaje.</p></small>
                            </div>
                ";
                echo $template;
            }
        }else{
            $template= "
                        <div class='text-center'>
                            <small><p>No se encontró ningún mensaje.</p></small>
                        </div>
            ";
            echo $template;
        }
        
    }

?>