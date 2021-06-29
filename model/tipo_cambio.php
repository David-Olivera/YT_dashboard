<?php

    class Exchange{
        
        //Get Exchange
        public function getExchangeRate($obj){
            include('../config/conexion.php');
            $query = "SELECT * FROM exchange_rate WHERE status = 1;";
            $result = mysqli_query($con, $query);
            if ($result) {
                $ins = mysqli_fetch_object($result);
                return $ins->amount_change;
            }
        }

        public function updateExchangeRate($obj){
            $ins = json_decode($obj);
            $status = 0;
            include('../config/conexion.php');
            $value =  mysqli_real_escape_string($con,$ins->value);
            $query = "UPDATE exchange_rate SET amount_change = '$value' where status = 1;";
            $result = mysqli_query($con,$query);
            if ($result) {
                $status = 1;
            }
            return $status;
        }
    }
?>