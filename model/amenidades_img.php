<?php
    require_once '../config/conexion.php';
        $name_ame_img = $_POST['name_ame_img'];
        $nombre_archivo_temporar=$_FILES["imagen"]["tmp_name"];		
        $nombre_archivo_original=$_FILES["imagen"]["name"];
        $query="SELECT * FROM amenities WHERE img = '$name_ame_img';";
        $result2 = mysqli_query($con, $query);
            if ($result2) {
               if (mysqli_num_rows($result2) > 0) {    
                    $message = " Ya existe un archivo con el mismo nombre, favor de modificarlo e intentarlo de nuevo";      
               }else{
                    $id_amenidad = mysqli_real_escape_string($con, $_POST['id_amenity_img']);
        
                    $carpeta="../assets/img/amenidades";
            
                    if (move_uploaded_file($nombre_archivo_temporar,"$carpeta/$name_ame_img")) {
                        $sql = "UPDATE amenities SET img = '$name_ame_img' WHERE id_amenity = '$id_amenidad';";
                        $result = mysqli_query($con, $sql);
                        if(!$result) {
                            $message = "Error al agregar la imagen de la amenidad";
                        }
                        $message = 'La imagen de la amenidad, ha sido agregado correctamente.';
                    }
               }
                echo $message;
            }
            
    
?>