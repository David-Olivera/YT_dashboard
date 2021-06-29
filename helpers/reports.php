<?php
    include('../model/reportes.php');
    $report = new Reports();

    //Download report reservations
    if (isset($_POST['action']) && $_POST['action'] == 'download_report') {
        $request = (object) $_POST;
         $req = json_encode($request);
         echo $report->downloadReport($req);
    }
    //Download report services
    if (isset($_POST['action']) && $_POST['action'] == 'download_report_s') {
        $request = (object) $_POST;
         $req = json_encode($request);
         echo $report->downloadReportS($req);
    }
    //Download report services
    if (isset($_POST['action']) && $_POST['action'] == 'download_report_c') {
        $request = (object) $_POST;
         $req = json_encode($request);
         echo $report->downloadReportC($req);
    }
    //Download report services
    if (isset($_POST['action']) && $_POST['action'] == 'download_report_agencies') {
        $request = (object) $_POST;
         $req = json_encode($request);
         echo $report->downloadReportA($req);
    }
    //Download report services
    if (isset($_POST['action']) && $_POST['action'] == 'download_report_o') {
        $request = (object) $_POST;
         $req = json_encode($request);
         echo $report->downloadReportO($req);
    }
    
?>