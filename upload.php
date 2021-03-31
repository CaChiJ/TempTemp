<?php
    session_start();
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    $dbName = "service";
    $conn = mysqli_connect("localhost", "root", "200502", $dbName);
    
    $from = "";
    if ( isset($_POST['m']) ) {
        $from = "m/";
    }
    
    if ( !isset($_SESSION['mb_id']) ) {
        header("Location: http://tmp-checker.run.goorm.io/");
    }
    
    if ( isset($_POST['temp']) ) {
        date_default_timezone_set("Asia/Seoul");
        $sql = "INSERT INTO temp (mb_id, time, temp) VALUES('".$_SESSION['mb_id']."', '".date("Y-m-d H:i:s")."', ".$_POST['temp'].");";
        if(mysqli_query($conn, $sql)) echo "<script> alert('업로드 성공!'); document.location.href='".$from."main.php'; </script>";
        else echo "<script> alert('실패!'); document.location.href='".$from."main.php'; </script>";
    } else {
        header("Location: http://tmp-checker.run.goorm.io/");
    }
?>