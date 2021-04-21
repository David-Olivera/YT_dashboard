<?php 
    class Cartas{
        public function getLetters($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            $nav = $ins->{'nav'};
            $table = "";
            $vr = "";
            $query ="";
            switch ($nav) {
                case 'yt-es':
                    $table = "letter";
                    $vr = "mx";
                    break;
                case 'yt-en':
                    $table = "letter";
                    $vr = "us";
                    break;
                case 'yt-po':
                    $table = "letter";
                    $vr = "pt";
                    break;
                case 'tu-es':
                    $table = "letter_tureando";
                    $vr = "mx";
                    break;
                case 'tu-en':
                    $table = "letter_tureando";
                    $vr = "us";
                    break;
                case 'tu-po':
                    $table = "letter_tureando";
                    $vr = "pt";
                    break;
            }
            $query = "SELECT * FROM $table where language_type = '$vr'";
           
            $result = mysqli_query($con, $query);
            if (!$result) {
                die('Error de consulta'. mysqli_error($con));
            }
            $json = array();
            while($row = mysqli_fetch_array($result)){
                $json[] = array(
                    'id_letter' => $row['id_letter'],
                    'footer' => $row['footer'],
                    'language' => $row['language_type']
                );
            }
            $jsonString = json_encode($json[0]);
            echo $jsonString;
        }

        public function updateLetter($letter, $id, $table){
            include('../../config/conexion.php');
            $id_letter = mysqli_real_escape_string($con, $id);
            $no_table = mysqli_real_escape_string($con, $table);
            $letter_footer = mysqli_real_escape_string($con,$letter);
            $query ="UPDATE $no_table SET footer = '$letter_footer' WHERE id_letter = $id_letter";
            $result=mysqli_query($con, $query);
            if ($result) {
                echo '            
                <div class=" d-flex justify-content-center">
                    <div class="alert alert-success alert-msg alert-dismissible w-100">
                        <p style="margin-bottom: 0;">
                            <input id="text-msg" type="text" class="sinbordefondo" value="La carta a sido actualizada correctamente">
                        </p>   
                        <button type="button" class="close" id="alert-close">&times;</button>  
                    </div>
                </div>';
            }else{
                echo '            
                <div class=" d-flex justify-content-center">
                    <div class="alert alert-success alert-msg alert-dismissible w-100">
                        <p style="margin-bottom: 0;">
                            <input id="text-msg" type="text" class="sinbordefondo" value="Error al editar la carta.">
                        </p>   
                        <button type="button" class="close" id="alert-close">&times;</button>  
                    </div>
                </div>';
            }
        }
    }
?>