<?php
    session_start();
    $dbName = "service";
    $conn = mysqli_connect("localhost", "root", "200502", $dbName);    

    $from = "";
    if ( isset($_POST['m']) ) {
        $from = "m/";
    }

    if ( isset($_POST['code']) ) {
        $sql = "UPDATE user SET teacher='".$_POST['code']."' where mb_id='".$_SESSION['mb_id']."';";
        if(mysqli_query($conn, $sql)) echo "<script> alert('설정을 저장했습니다.'); document.location.href='".$from."main.php'; </script>";
        else echo "<script> alert('설정 저장에 실패했습니다.'); document.location.href='".$from."main.php'; </script>";
    } else {
        echo "<script> document.location.href='http://tmp-checker.run.goorm.io/' </script>";
    }
?>