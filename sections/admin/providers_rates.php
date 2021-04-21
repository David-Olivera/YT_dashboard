<?php
    require_once '../../config/conexion.php';
    session_start();
    $id_provider = $_GET['id_provider'];
    $name_provider = $_GET['provider'];
    if (isset($_SESSION['id_user'])) {
        if ($_SESSION["id_role"] == 1) {
            
        }else{
            header('location: ../../login.php');
        }
    }else{
        header('location: ../../login.php');
    }
    require_once('../../model/proveedores.php');
    $proveedor = new Provider();
    if ($id_provider) {
        $provider_floor_right = json_decode($proveedor->getFloorRightById($id_provider));
        $area_lists = json_decode($proveedor->getListArea());
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proveedor - <?php echo $name_provider ?></title>
     <!-- FontAwesome Styles-->
     <script src="https://kit.fontawesome.com/48e49b4629.js" crossorigin="anonymous"></script>
    <?php
        include('../include/estilos.php');
    ?>
</head>
<body>
    <div class="wrapper">
        <?php
            include('../include/navigation.php');
        ?>
        <h4 class="">Tarifas de Proveedor - <?php echo $name_provider ?></h4>
        
        <div class=" d-flex justify-content-center">
           <div class="alert alert-success alert-msg alert-dismissible w-100">
                <p style="margin-bottom: 0;">
                    <input id="text-msg" type="text" class="sinbordefondo" value="">
                </p>   
                <button type="button" class="close" id="alert-close">&times;</button>  
           </div>
        </div>
        <div class="row pt-3">
            <div class="col-lg-12">
                <form class="form-inline">
                    <div class="form-group">
                        <label for="inpDDP">Derecho De Piso:</label>
                        <input type="text" id="inpDDP" class="form-control mx-sm-2"  data-provider="<?php echo $id_provider; ?>" data-current="<?php echo $provider_floor_right->{'cost_floorright'}; ?>"  value="<?php echo $provider_floor_right->{'cost_floorright'}; ?>">
                    </div>
                </form>
            </div>
            <div class="col-lg-12 pt-4">
                <div class="row">
                    <?php foreach($area_lists as $al) { ?>
                        <div class="col-xl-2 col-lg-2 col-md-4 pb-3 content_rates_provider">
                            <div class="card">
                                <div class="card-header text-center">
                                <h5><?php echo $al->{'name_zone'}; ?></h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">

                                        <div class="col-md-12  pb-1"">
                                            <h6>Compartido</h6>
                                        </div>
                                        <div class="col-md-8">
                                            <?php $rate_shared_1c = json_decode($proveedor->getRateProvider($al->{'id_zone'}, $id_provider, 'Shared', 1)); ?>
                                            <input type="text" class="form-control-plaintext form-control-sm" placeholder="$0.00" id="<?php echo 'S'.$al->{'id_zone'}.'1'; ?>" data-update="<?php echo $rate_shared_1c->{'id_rate_provider'}; ?>" value="<?php echo $rate_shared_1c->{'cost_service'}; ?>">
                                        </div>
                                        <div class="col-md-4 text-center ">
                                            1CAP
                                        </div>
                                        <div class="col-md-12 pt-2 pb-1">
                                            <h6>Privado</h6>
                                        </div>
                                        <div class="col-md-8 ">
                                            <?php $rate_private_4p = json_decode($proveedor->getRateProvider($al->{'id_zone'}, $id_provider, 'Private', 4)); ?>
                                            <input type="text"  class="form-control-plaintext form-control-sm"  placeholder="$0.00" id="<?php echo 'P'.$al->{'id_zone'}.'4'; ?>" data-update="<?php echo $rate_private_4p->{'id_rate_provider'}; ?>" value="<?php echo $rate_private_4p->{'cost_service'}; ?>">
                                        </div>
                                        <div class="col-md-4 text-center ">
                                            4CAP
                                        </div>
                                        <div class="col-md-8 ">
                                            <?php $rate_private_6p = json_decode($proveedor->getRateProvider($al->{'id_zone'}, $id_provider, 'Private', 6)); ?>
                                            <input type="text"  class="form-control-plaintext form-control-sm"  placeholder="$0.00" id="<?php echo 'P'.$al->{'id_zone'}.'6'; ?>" data-update="<?php echo $rate_private_6p->{'id_rate_provider'}; ?>" value="<?php echo $rate_private_6p->{'cost_service'}; ?>">
                                        </div>
                                        <div class="col-md-4 text-center">
                                            6CAP
                                        </div> 
                                        <div class="col-md-8 ">
                                            <?php $rate_private_8p = json_decode($proveedor->getRateProvider($al->{'id_zone'}, $id_provider, 'Private', 8)); ?>
                                            <input type="text"  class="form-control-plaintext form-control-sm"  placeholder="$0.00" id="<?php echo 'P'.$al->{'id_zone'}.'8'; ?>" data-update="<?php echo $rate_private_8p->{'id_rate_provider'}; ?>" value="<?php echo $rate_private_8p->{'cost_service'}; ?>">
                                        </div>
                                        <div class="col-md-4 text-center">
                                            8CAP
                                        </div>
                                        <div class="col-md-8 ">
                                            <?php $rate_private_10p = json_decode($proveedor->getRateProvider($al->{'id_zone'}, $id_provider, 'Private', 10)); ?>
                                            <input type="text"  class="form-control-plaintext form-control-sm"  placeholder="$0.00" id="<?php echo 'P'.$al->{'id_zone'}.'10'; ?>" data-update="<?php echo $rate_private_10p->{'id_rate_provider'}; ?>" value="<?php echo $rate_private_10p->{'cost_service'}; ?>">
                                        </div>
                                        <div class="col-md-4 text-center">
                                            10CAP
                                        </div>
                                        <div class="col-md-12 pt-2 pb-1">
                                            <h6>Lujo</h6>
                                        </div>
                                        <div class="col-md-8 ">
                                            <?php $rate_private_6l = json_decode($proveedor->getRateProvider($al->{'id_zone'}, $id_provider, 'Luxury', 6)); ?>
                                            <input type="text"  class="form-control-plaintext form-control-sm"  placeholder="$0.00" id="<?php echo 'L'.$al->{'id_zone'}.'6'; ?>" data-update="<?php echo $rate_private_6l->{'id_rate_provider'}; ?>" value="<?php echo $rate_private_6l->{'cost_service'}; ?>">
                                        </div>
                                        <div class="col-md-4 text-center">
                                            6CAP
                                        </div>
                                        <div class="col-md-12 p-0">
                                            <br>
                                            <a href="#" class="btn btn-block btn-black" id="save_rates_provider" data-area="<?php echo $al->{'id_zone'}; ?>" data-provider="<?php echo $id_provider; ?>">G U A R D A R</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                </div>
            </div>
        </div>
        
    </div>
    
    <?php
    include('../include/scrips.php');
    ?>
    <script src="../../assets/js/navigation.js"></script>
    <script src="../../assets/js/providers.js"></script>
</body>
</html>