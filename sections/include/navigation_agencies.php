<?php
    include('path.php');
    echo '
        <nav class="navbar navbar-expand-lg" id="nav_agencies">
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>                
            <a class="navbar-brand nav_icon" href="#">
                <img src="../../assets/img/icon/icon.png" alt="">
            </a>               
            <a class="navbar-brand nav_icon_movil" href="#">
                <img src="../../assets/img/icon/icon_25.png" alt="">
            </a>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                    <li  '.(($page=='home.php')?'class="current nav-item "':'class="nav-item "').'>
                        <a class="nav-link" href="home.php">Inicio</a>
                    </li>
                    <li '.(($page=='transfers.php')?'class="current nav-item "':'class="nav-item "').'>
                        <a class="nav-link" href="transfers.php">Traslados</a>
                    </li>
                    <li '.(($page=='reservations.php')?'class="current nav-item "':'class="nav-item "').'>
                        <a class="nav-link" href="reservations.php">Mis Reservas</a>
                    </li>
                    <li '.(($page=='conciliations.php')?'class="current nav-item "':'class="nav-item "').'>
                        <a class="nav-link " href="conciliations.php">Conciliaciones</a>
                    </li>
                    <li '.(($page=='users.php')?'class="current nav-item "':'class="nav-item "').'>
                        <a class="nav-link " href="users.php">Usuarios</a>
                    </li>
                    <li '.(($page=='account.php')?'class="current nav-item "':'class="nav-item "').'>
                        <a class="nav-link " href="account.php">Cuenta</a>
                    </li>
                    <li '.(($page=='tours.php')?'class="current nav-item "':'class="nav-item "').'>
                        <a class="nav-link " href="tours.php">Tours</a>
                    </li>
                </ul>                
                <ul class="navbar-nav ml-md-auto mt-2 mt-lg-0">
                    <li class="nav-item dropdown">
                        <a class="nav-flag dropdown-toggle nav-link" href="#" id="languageDropdown" data-toggle="dropdown" aria-expanded="false">
                            <img src="../../lang/img/us.png" alt="English" width="20" class="align-middle mr-1">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="languageDropdown">
                            <a class="dropdown-item" href="#">
                                <img src="../../lang/img/us.png" alt="English" width="20" class="align-middle mr-1">
                                <span class="align-middle">English</span>
                            </a>
                            <a class="dropdown-item" href="#">
                                <img src="../../lang/img/es.png" alt="Spanish" width="20" class="align-middle mr-1">
                                <span class="align-middle">Spanish</span>
                            </a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="nav-link ">'.$_SESSION['username'].' - '.$_SESSION['id_agency'].' </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="../../helpers/logout_a.php">Salir</a>
                    </li>
                </ul>
            </div>
        </nav>
    ';
?>