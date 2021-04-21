

<?php
    require_once '../config/conexion.php';
    $path="../docs/";//server path
    $id_agency = $_POST['id_agency'];
    $today = date('Y-m-d H:i:s');
    date_default_timezone_set('America/Cancun');
    foreach ($_FILES as $key) {
        if($key['error'] == UPLOAD_ERR_OK ){
            $name = $id_agency.'_'.$key['name'];
            $temp = $key['tmp_name'];
            $size= ($key['size'] / 1000)."Kb";
            $ext = $key['type'];
            if ($ext == "application/pdf" || $ext == "image/jpeg" || $ext == "image/jpg" || $ext == "image/png") {
                $query_select= "SELECT * FROM agencies_docs WHERE name_doc like '$name' and id_agency like $id_agency;";
                $result_select = mysqli_query($con, $query_select);
                if ($result_select) {
                    if (mysqli_num_rows($result_select) > 0) {
                        echo "
                        <div>
                            <h12><strong>Archivo: $name</strong></h2><br />
                            <h12><strong>Tamaño: $size</strong></h2><br />
                            <hr>
                        </div>
                        <div class='text-center'>
                            <p><small>Ya existe un archivo con el mismo nombre, favor de modificarlo e intentarlo de nuevo</small> <i class='denied fas fa-times-circle'></i></p>
                        </div>
    
                        ";
                        
                    }else{
                        $query = "INSERT into agencies_docs(name_doc,id_agency,date_register_doc)VALUES('$name', $id_agency, '$today');";
                        $result = mysqli_query($con, $query);
                        if ($result) {
                            if (move_uploaded_file($temp, $path . $name)) {
                            echo "
                                <div>
                                    <h12><strong>Archivo: $name</strong></h2><br />
                                    <h12><strong>Tamaño: $size</strong></h2><br />
                                    <h12><strong>Tamaño: $ext</strong></h2><br />
                                    <hr>
                                </div>
                                <div class='form-group text-center'>
                                        <p><small>Los siguientes archivos fueron agregados a la agencia</small> <i class='approved fas fa-check-circle'></i></p>
                                </div>
                                ";
                            }
                        }else{
                            echo "
                            <div>
                                <h12><strong>Archivo: $name</strong></h2><br />
                                <h12><strong>Tamaño: $size</strong></h2><br />
                                <hr>
                            </div>
                            <div class='text-center'>
                                <p><small>Error al registrar los documentos a la agencia</small> <i class='denied fas fa-times-circle'></i></p>
                            </div>
        
                                ";
                        }  
                    }
                }
            }else{
                echo "
                <div>
                    <h12><strong>Archivo: $name</strong></h2><br />
                    <h12><strong>Tamaño: $size</strong></h2><br />
                    <hr>
                </div>
                <div class='text-center'>
                    <p><small>El archivo tiene una extension que no es permitida</small> <i class='denied fas fa-times-circle'></i></p>
                </div>

                ";
                
            }
        
        }else{
            echo $key['error'];
        }
    }
?>