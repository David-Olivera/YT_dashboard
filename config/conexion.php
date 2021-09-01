<?php
    $servername = "localhost";
    $database = "yamevi_bd";
    $username = "root";
    $password = "";
    // $count = date("i"); // Minutos dentro de una hora
    // switch (true) {
    //     case $count <= 15: // primeros 16 minutos
    //         $user = 'yame_travel_bd_x'; // primer usuario
    //         break;

    //     case $count <= 30:
    //         $user = 'yame_travel_bd_y'; // segundo usuario
    //         break;

    //     case $count <= 45:
    //         $user = 'yame_travel_bd_z'; // tercer usuario
    //         break;

    //     default:
    //         $user = 'yame_admin_trav'; // usuario por defecto
    //         break;
    // }
    // Create connection
    $con = mysqli_connect($servername, $username, $password, $database);
    // Check connection
    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }
    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }
    mysqli_set_charset($con, 'utf8');
?>