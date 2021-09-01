<?php
session_start();
$id_user = "";
if (isset($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];
  if ($_SESSION["id_role"] == 1) //Condicion admin
  {
  } else {
    header('location: ../../login.php');
  }
} else {
  header('location: ../../login.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YameviTravel - Agencias</title>
    <!-- FontAwesome Styles-->
    <script src="https://kit.fontawesome.com/48e49b4629.js" crossorigin="anonymous"></script>
    <?php
    include('../include/estilos.php');
    ?>
</head>
<body>
    <div class="wrapper">
        <?PHP
            include('../include/navigation.php');
        ?>
        <div class=" d-flex justify-content-center" id="content-alert-msg">
           <div class="alert alert-info alert-msg alert-dismissible w-100">
                <div class="row">
                    <div class="pl-3 pt-2">
                        <i class="far fa-fw fa-bell"></i>
                    </div>
                    <div class="p-1 w-80">
                        <p style="margin-bottom: 0;">
                            <input id="text-msg" type="text" class="sinbordefondo" value="">
                        </p>   
                    </div>
                </div>
                <button type="button" class="close" id="alert-close">&times;</button>  
           </div>
        </div>
        <h3 class="pb-2">Agencias</h3>
        <div class="row">
        <!-- <input id="btnShow" type="button" value="Show PDF" />
            <div class="modal fade " id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel2">Documentación</h5>
                            <button type="button" class="close" id="cancelButtonModal"  aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="dialog" style="display: none">
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
            <div class="col-lg-12">
                <div class=" pb-3">
                        <a href="#" class="btn btn-success btn-sm"  data-toggle="modal" id="btn_dowload_report_a" data-target="#downloadConciModal">Generar Reporte</a>
                </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <div>
                    <button id="formButton" type="button" class="btn btn-dark" data-toggle="modal"><i class="fas fa-plus-square"></i> Nueva agencia</button>
                </div>
            </div>
            <div class="col-xl-3 col-md-4">
                <div class="form-group"> 
                    <form class="form-inline" id="form-discount"  accept-charset="UTF-8" >
                        <div class="flex-fill mr-2">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="porcentaje">%</span>
                                </div>
                                <input type="text" class="form-control form-control-sm " id="inp_descuento_ao" placeholder="Ingrese el numero "  aria-describedby="inp_descuento_ao" required>
                            </div>
                        </div>
                        <button class="btn btn-black btn-sm my-2 my-sm-0" type="button" id="btn_save_discount" title="Guardar"><i class="fas fa-save" aria-hidden="true"></i></button>
                        <small id="inp_descuento_ao" class="form-text text-muted">Descuento agencias opreadoras.</small>
                    </form>
                </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="form-group"> 
                    <form class="form-inline" id="form-search"  accept-charset="UTF-8" >
                        <div class="flex-fill mr-2">
                            <input type="search" name="search" id="search" placeholder="ID o Nombre de agencia" class="form-control  form-control-sm w-100" label="Search this site">
                        </div>
                        <button class="btn btn-black btn-sm my-2 my-sm-0" type="submit"><i class="fas fa-search" aria-hidden="true"></i></button>
                    </form>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6" id="crud-form">
                <div class="card">
                    <div class="card-body">
                        <h3>Nuevo</h3>
                        <form role="form" id="agencyForm">
                            <input type="hidden" id="agency-id">
                            <div class="form-group">
                                <input type="text" id="nombre_agencia" placeholder="Nombre de agencia" class="form-control">
                            </div>
                            <div class="form-group">
                                <input type="email" id="email_agencia" placeholder="Email de contacto" class="form-control" >
                            </div>
                            <div class="form-group">
                                <input type="email" id="email_agencia_pay" placeholder="Email de pagos" class="form-control" >
                            </div>
                            <div class="form-group">
                                <input type="text" id="telefono_agencia" placeholder="Teléfono de agencia" class="form-control">
                            </div>
                            <div class="form-group">
                                <input type="text" id="name_contact_agencia" placeholder="Nombre de contacto" class="form-control">
                            </div>
                            <div class="form-group">
                                <input type="text" id="last_contact_agencia" placeholder="Apellidos de contacto" class="form-control">
                            </div>
                            <div class="form-group">
                                <input type="text" id="usuario_agencia" placeholder="Nombre de Usuario" class="form-control" >
                            </div>
                            <div class="form-group">
                                <input type="password" id="password" placeholder="Password" autocomplete="off" class="form-control" aria-describedby="passwordHelpBlock" >
                                <small id="passwordHelpBlock" class="form-text text-muted">
                                Por motivos de <i class="fas fa-lock"></i> el password estara cifrado y se vera mas largo</small>
                            </div>
                            <div class="row mx-auto">
                                <div class="w-50 p-1">
                                    <button type="submit" id="saveButton" class="roomupdate btn btn-primary btn-block text-center">Guardar</button>
                                </div>
                                <div class="w-50 p-1">
                                    <button type="button" id="cancelButton" class=" btn btn-light btn-block  text-center ">Cancelar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6" id="crud-form-edit">
                <div class="card">
                    <div class="card-body">
                        <h2>Editar</h2>
                        <form role="form" id="agencyFormEdit">
                            <input type="hidden" id="agency-id-edit">
                            <div class="form-group">
                                <input type="text" id="nombre_agencia_edit" placeholder="Nombre de agencia" class="form-control">
                            </div>
                            <div class="form-group">
                                <input type="email" id="email_agencia_edit" placeholder="Email de agencia" class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <input type="email" id="email_agencia_edit_pay" placeholder="Email de pagos" class="form-control" >
                            </div>
                            <div class="form-group">
                                <input type="text" id="telefono_agencia_edit" placeholder="Teléfono de agencia" class="form-control">
                            </div>
                            <div class="form-group">
                                <input type="text" id="name_contact_agencia_edit" placeholder="Nombre de contacto" class="form-control">
                            </div>
                            <div class="form-group">
                                <input type="text" id="last_contact_agencia_edit" placeholder="Apellidos de contacto" class="form-control">
                            </div>
                            <div class="form-group">
                                <input type="text" id="usuario_agencia_edit" placeholder="Nombre de Usuario" class="form-control" >
                            </div>
                            <div class="form-group">
                                <input type="password" id="password_edit" autocomplete="off" value="" placeholder="Password" class="form-control" aria-describedby="passwordHelpBlock" >
                                <small id="passwordHelpBlock" class="form-text text-muted">
                                Por motivos de <i class="fas fa-lock"></i> el password no se muestra pero puede cambiarla si lo desea</small>
                            </div>
                            <div class="row mx-auto">
                                <div class="w-50 p-1">
                                    <button type="submit" id="saveButtonEdit" class="roomupdate btn btn-primary btn-block text-center">Guardar</button>
                                </div>
                                <div class="w-50 p-1">
                                    <button type="button" id="cancelButtonEdit" class=" btn btn-light btn-block  text-center ">Cancelar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12" id="resultSearch">
                    <p class="text-table">Puedes dar clic sobre cualquier columna para ordenar de manera ascendente o descendente.</p>
                    <div class="card my-3" id="result-search">
                        <div class="card-body p-2">
                            <div class="row">
                                    <ul id="container" class="row w-100" ></ul>
                            </div>
                        </div>
                    </div>
                    <div id="table-data">
                        
                    </div>
            </div>
            <div class="modal fade " id="exampleModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Documentación</h5>
                            <button type="button" class="close" id="cancelButtonModal"  aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form id="agenciDocsModal" >
                                        <!-- <div class="form-group">
                                            <input type="show" id="id_agencia_doc" name="id_agencia_doc" placeholder="ID" class="form-control" >
                                        </div>
                                        <div class="form-group pb-2 elimined border-bottom">
                                            <div class="row">
                                                <div class="col-lg-10 col-md-8">
                                                    <input type="text" class="form-control" id="name_docs" disabled>
                                                    <small id="name_docs" class="form-text text-muted">El nombre de la imagen actual de la amenidad.</small>
                                                </div>
                                                <div class="col-lg-2 col-md-4">
                                                    <a href="#" id="deleteDoc" class="btn btn-danger btn-block"><i class="fas fa-trash-alt"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group mb-4">
                                            <input type="file" class="form-control-sm" id="archivo[]" name="archivo[]" multiple="">
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" id="saveButtonDoc" class="btn btn-success btn-block text-center">Guardar</button>
                                        </div> -->
                                        <div class="form-group">
                                            <label for="">Seleccione archivos de agencia</label><br>
                                            <p><small>Solo permite archivos en formato PDF, JPG, JPEG y PNG</small></p>
                                            <div id="upload" class="form-control-sm">
                                                <div class="fileContainer">
                                                    <input id="myfiles" type="file" name="myfiles[]" multiple="multiple" accept="application/pdf, image/jpeg, image/jpg, image/png" required />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <input type="hidden" name="id_agen" id="id_agen" class="form-control">
                                            </div>  
                                        </div>
                                        <div id="loadedfiles">
                                              
                                        </div>
                                        <div class="form-group" id="content_btn">
                                            <button type="submit" id="saveButtonDoc" class="btn btn-success btn-block text-center">Guardar</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="row ml-3 mr-3 mb-3" id="storaged_documents">
                                    
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            

            <div class="modal fade" id="modalUsersAgency" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Lista de usuarios</h5>
                            <button type="button" class="close" id="btn_close_usersa" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class=" d-flex justify-content-center">
                                <div class="alert alert-dismissible w-100" id="alert-msg-user">
                                        <p style="margin-bottom: 0;">
                                            <input id="text-msg-user" type="text" class="sinbordefond w-100 form-control-plaintext" value="">
                                        </p>   
                                         
                                </div>
                            </div>
                            <input type="hidden" id="inp_edit_user_agency">
                            <div id="content_users_agency">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="electronicPurseModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="label_name_agency"></h5>
                    <button type="button" class="close btn_cancel_balance" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert  alert-dismissible fade show" id="alert_msg" role="alert">
                        <div id="text_alert_msg" class="mb-0">
                            
                        </div>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="form-group mx-sm-3 mb-2">
                        <input type="hidden" class="form-control" id="inp_id_agency" placeholder="">
                        <input type="hidden" class="form-control" id="inp_user" value="<?php echo $id_user ?>">
                    </div>
                    <div id="load_electronic_purse">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    include('../include/scrips.php');
    ?>
    <!-- <script type="text/javascript">
    $(function () {
        var fileName = "Omnibees Pull Interface Specification.V2.6.pdf";
        $("#btnShow").click(function () {
            var url = '../../docs/'+fileName;
            window.open(url, '_blank');
        });
    });
    </script> -->
    <script src="../../assets/js/navigation.js"></script>
    <script src="../../assets/js/agencies.js"></script>
</body>
</html>