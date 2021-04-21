<?php
    include('path.php');
    if($_SESSION["id_role"]==1) //Condicion admin
    {
        echo'
        <!-- Sidebar  -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h4>Yamevi Travel</h4>
                <h6>Cancún Tours and Fun</h6>
            </div>
            <ul class="list-unstyled components">
                <div class="pb-2  ">
                    <li '.(($page=='index.php')?'class="current"':"").'>
                        <a href="index.php"><i class="fas fa-home"></i> Dashboard</a>
                    </li>
                </div>
                
                <li class="p-1">
                    <a href="#trasladoMenu" data-toggle="collapse" id="dropTraslados" aria-expanded="true" class="dropdown-toggle"><i class="fas fa-shuttle-van"></i> Traslados</a>
                    <ul class="collapse list-unstyled show" id="trasladoMenu">
                        <li '.(($page=='agencies.php')?'class="current"':"").'>
                            <a  href="agencies.php">Agencias</a>
                        </li>
                        <li '.(($page=='amenities.php')?'class="current"':"").'>
                            <a  href="amenities.php">Amenidades</a>
                        </li>
                        <li '.(($page=='letters.php')?'class="current"':"").'>
                            <a href="letters.php">Cartas</a>
                        </li>
                        <li>
                            <a class="text-danger" href="#">Conciliaciones</a>
                        </li>
                        <!-- 
                        <li>
                            <a class="text-danger" href="#">Habitaciones</a>
                        </li>
                        -->
                        <li '.(($page=='hotels.php')?'class="current"':"").'>
                            <a href="hotels.php">Hoteles</a>
                        </li>
                        <li '.(($page=='providers.php')?'class="current"':"").'>
                            <a href="providers.php">Proveedores</a>
                        </li>
                        <li>
                            <a class="text-danger" href="#">Reportes</a>
                        </li>
                        
                        <li pl-1 '.(($page=='public_rates.php')?'class="current"':"").'>
                            <a href="public_rates.php">Tarifas</a>
                        </li>
                        <li '.(($page=='reps.php')?'class="current"':"").'>
                            <a href="reps.php">REPS</a>
                        </li>
                        <li '.(($page=='reservations.php')?'class="current"':"").'>
                            <a  href="reservations.php">Reservaciones</a>
                        </li>
                        <li '.(($page=='servicies.php')?'class="current"':"").'>
                            <a href="servicies.php">Servicios</a>
                        </li>
                        <!--
                        <li '.(($page=='tours.php')?'class="current"':"").'>
                            <a class="text-danger" href="tours.php">Tours</a>
                        </li>
                        -->
                        <li '.(($page=='users.php')?'class="current"':"").'>
                            <a  href="users.php">Usuarios</a>
                        </li>
                    </ul>
                </li>
                <li class="p-1">
                    <a href="#toursMenu" data-toggle="collapse"  aria-expanded="false" class="dropdown-toggle"><i class="fas fa-route"></i> Tours</a>
                    <ul class="collapse list-unstyled" id="toursMenu">
                        <li>
                            <a href="#">Home 1</a>
                        </li>
                        <li>
                            <a href="#">Home 2</a>
                        </li>
                        <li>
                            <a href="#">Home 3</a>
                        </li>
                    </ul>
                </li>
                <li class="p-1">
                    <a href="#hotelesMenu" data-toggle="collapse"  aria-expanded="false" class="dropdown-toggle"><i class="fas fa-hotel"></i> Hoteles</a>
                    <ul class="collapse list-unstyled" id="hotelesMenu">
                        <li>
                            <a href="#">Home 1</a>
                        </li>
                        <li>
                            <a href="#">Home 2</a>
                        </li>
                        <li>
                            <a href="#">Home 3</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>

        <!-- Page Content  -->
        <div id="content">
        <nav class="navbar navbar-expand-lg navbar-light " style="background-color:#fff;">
            <button type="button" id="sidebarCollapse" title="Manú Lateral" class="btn">
                <i class="fas fa-align-left"></i>
            </button>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-md-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">
                            <i class="far fa-comment"><span class="indicator">4</span> </i>
                        </a>
                    </li>
                    <li class="nav-item dropdown active" id="options-user">
                        <a class="nav-item nav-link dropdown-toggle mr-md-4" href="#" id="bd-versions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-user fa-fw"></i> '.$_SESSION['username'].' 
                            <input type="hidden" id="value" value="'.$_SESSION['id_user'].'">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="bd-versions">
                            <a class="dropdown-item" href="usersetting.php"><i class="fa fa-user fa-fw"></i> Perfil del usuario</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="../../helpers/logout.php"><i class="fa fa-sign-out fa-fw"></i> Cerrar sesión</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>'  ;  
    }
    if($_SESSION["id_role"]==2) //Condicion personal
    {
        echo'<nav class="navbar navbar-expand-lg navbar-dark p-3 " style="background-color: #000000;">
            <a class="navbar-brand welcome" href="#">Bienvenido Operador</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-md-auto">
                <li class="nav-item dropdown">
                    <a class="nav-item nav-link dropdown-toggle mr-md-2" href="#" id="bd-versions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-user fa-fw"></i> '.$_SESSION['username'].'
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="bd-versions">
                        <a class="dropdown-item" href="usersetting.php"><i class="fa fa-user fa-fw"></i> Perfil del usuario</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="../../helpers/logout.php"><i class="fa fa-sign-out fa-fw"></i> Cerrar sesión</a>
                    </div>
                </li>
            </ul>
            </div>
        </nav>'  ;  
    }
    if($_SESSION["id_role"]==3) //Condicion Usuarios
    {
        echo'<nav class="navbar navbar-expand-lg navbar-dark p-3 " style="background-color: #000000;">
            <a class="navbar-brand welcome" href="#">Bienvenido Vendedor</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-md-auto">
                <li class="nav-item dropdown">
                    <a class="nav-item nav-link dropdown-toggle mr-md-2" href="#" id="bd-versions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-user fa-fw"></i> '.$_SESSION['username'].'
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="bd-versions">
                        <a class="dropdown-item" href="usersetting.php"><i class="fa fa-user fa-fw"></i> Perfil del usuario</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="../../helpers/logout.php"><i class="fa fa-sign-out fa-fw"></i> Cerrar sesión</a>
                    </div>
                </li>
            </ul>
            </div>
        </nav>'  ;   
    }
?>