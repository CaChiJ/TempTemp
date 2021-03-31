<?php
    

	$code = array_keys($_POST)[0];
    $host = 'localhost';
    $user = 'root';
    $pw = '200502';
    $dbName = 'service';
    $mysqli = new mysqli($host, $user, $pw, $dbName);
    mysqli_set_charset($mysqli, 'utf8');

    $check = "SELECT * FROM user WHERE code='$code'";
    $result = $mysqli->query($check);

    if($result->num_rows==1){
        $row=$result->fetch_array(MYSQLI_ASSOC);
        $id = $row['mb_id'];
        $name = $row['name'];
        
        $check = "SELECT * FROM temp WHERE mb_id='$id' ORDER BY 
    id DESC";
        $result = $mysqli->query($check);
        
        if($result->num_rows>=1){
            $row=$result->fetch_array(MYSQLI_ASSOC);
            date_default_timezone_set("Asia/Seoul");
            $date = date("Y-m-d");
            if(strpos($row['time'], $date) !== false){
                $tmp = $row['temp'];
                echo "3|$name|$tmp";
            }else{
                echo "2|$name|None";
            }
        }
        else{ echo "1|$name|None"; }
    }
    else{ echo "0|None|None"; }
?>