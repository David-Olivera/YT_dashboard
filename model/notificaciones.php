<?php 
    include('../config/conexion.php');
    $id = mysqli_real_escape_string($con,$_POST['id']);
    $fecha_actual = date("Y-m-d");
    $date = date("Y-m-d",strtotime($fecha_actual."- 3 day"));
    $query = "";
    if ($_POST['type']) {
        if ($_POST['type'] == 'load_msj_bitacora') {
            $query = "SELECT * FROM bitacora AS B INNER JOIN users AS U ON B.id_user = U.id_user INNER JOIN reservations as R on B.id_reservation = R.id_reservation WHERE B.register_date >= '$date' ORDER BY B.id_bitacora DESC;";
        }
        
        if ($_POST['type'] == 'load_msj_activity') {
            $query = "SELECT * FROM activities AS A INNER JOIN users AS U ON A.id_user = U.id_user INNER JOIN reservations as R on A.id_reservation = R.id_reservation WHERE A.change_date >= '$date' ORDER BY A.id_activity DESC;";
        }
    }
    $result = mysqli_query($con, $query);
    
    if ($_POST['type'] == 'load_msj_bitacora') {
        $sql = "UPDATE bitacora SET `status` = 1 WHERE `status` = 0";	
        $result_up = mysqli_query($con, $sql);   
    }
    if ($_POST['type'] == 'load_msj_activity') {
        $sql_ac = "UPDATE activities SET status_activity = 1 WHERE status_activity = 0";	
        $result_ac = mysqli_query($con, $sql_ac); 
    }
    

    $template= " ";
    $labelChange ="";
    $status = 0;
    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $status = 1;
            $message = "";
            if ($_POST['type'] == 'load_msj_bitacora') {
                $template.="
                    <div id='content-totle-notifi' class='pt-2 pl-3 pr-2 pb-2'>
                        <span id='title-notifi' class='m-0'>Mensajes</span>
                    </div>
                ";

            }
            if ($_POST['type'] == 'load_msj_activity') {
                $template.="
                    <div id='content-totle-notifi' class='pt-2 pl-3 pr-2 pb-2'>
                        <span id='title-notifi' class='m-0'>Notificaciones</span>
                    </div>
                ";
                
            }
            while ($row = mysqli_fetch_assoc($result)) {
                //if ($id == $row['id_user']) {
					$newidreserva = MD5($row['id_reservation']);
                    if ($_POST['type'] == 'load_msj_bitacora') {
                        $template.= "
                        <a  href='reservation_profile.php?reservation=".$newidreserva."&coinv=".$row['code_invoice']."&reedit=0' target='_blank'><div class='notification-item '>" .
                        "<div class='notification-subject'><strong>@". $row["username"] . "</strong></div>" . 
                        "<div class='notification-text'><span>Realizo un comentario en la reservación " . $row["code_invoice"]  . "</span></div>" . 
                        '<div class="notification-comment"><span>"' . $row["comments"]  . '"</span></div>' . 
                        "<div class='notification-date'><small>". $row['register_date'] . "</small></div>" .
                        "</div></a>";
                    }
                    
                    if ($_POST['type'] == 'load_msj_activity') {
                        switch ($row['activity_type']) {
                            case 'STATE':
                                $labelChange = 'Cambio de estado';
                                break;
                            case 'EDITSALE':
                                $labelChange = 'Edición de reserva';
                                break;
                            case 'SETPROVIDER':
                                $labelChange = 'Asignación de Proveedor';
                                break;
                            case 'UPDATEPROVIDER':
                                $labelChange = 'Cambio de Proveedor';
                                break;
                            case 'METHODPAYMENT':
                                $labelChange = 'Cambio de Método de Pago';
                                break;
                            case 'SETREP':
                                $labelChange = 'Asignación de REP';
                                break;
                            case 'UPDATEREP':
                                $labelChange = 'Cambio de REP';
                                break;
                            case 'PICKUP':
                                $labelChange = 'Asignación de Pickup';
                                break;
                        }
                        $template.= "
                        <a  href='reservation_profile.php?reservation=".$newidreserva."&coinv=".$row['code_invoice']."&reedit=0' target='_blank'><div class='notification-item '>" .
                        "<div class='notification-subject'><strong>@". $row["username"] . "</strong></div>" . 
                        "<div class='notification-text'><span>Realizo un cambio de estado en la reservación " . $row["code_invoice"]  . "</span></div>" . 
                        '<div class="notification-comment"><span>"' . $labelChange  . ' - ' . $row["activity_status"]  . '"</span></div>' . 
                        "<div class='notification-date'><small>". $row['change_date'] . "</small></div>" .
                        "</div></a>";
                    }

                //}
            }
        }else{
            if ($_POST['type'] == 'load_msj_bitacora') {
                $template= "
                <div class='p-3'>
                <p><small>No se han agregado comentarios.</small></p>
                </div>
                ";
            }
            if ($_POST['type'] == 'load_msj_activity') {
                $template= "
                <div class='p-3'>
                <p><small>No hay notificaciones.</small></p>
                </div>
                ";
            }
       
        }
    }else{
        if ($_POST['type'] == 'load_msj_bitacora') {
            $template= "
            <div class='p-3'>
            <p><small>No se han agregado comentarios.</small></p>
            </div>
            ";
        }
        if ($_POST['type'] == 'load_msj_activity') {
            $template= "
            <div class='p-3'>
            <p><small>No hay notificaciones.</small></p>
            </div>
            ";
        }
    }
    echo json_encode(array('msj' => $template,'status' => $status));
?>