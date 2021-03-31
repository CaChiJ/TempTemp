<?php
	session_start();
    //error_reporting(E_ALL);
    //ini_set("display_errors", 1);
    $dbName = "service";
    $conn = mysqli_connect("localhost", "root", "200502", $dbName);
    mysqli_query($conn, "set session character_set_connection=utf8;");
    mysqli_query($conn, "set session character_set_results=utf8;");
    mysqli_query($conn, "set session character_set_client=utf8;");
    if($_SESSION['name']==null){
        echo "<script>location.href='./login.html'; alert('로그인이 필요한 기능입니다.');</script>";
    }
?>

<style>
</style>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width" />
    <meta name="robots" content="noindex">
    <title>템템 - 관리자</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css"
          integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <link rel="shortcut icon" href="image/server/favicon.ico" type="image/x-icon">
    <link rel="icon" href="image/server/favicon.ico" type="image/x-icon">
</head>
<body>
    <div style='display:block; background-color:#2194E0; margin-bottom:5%; color:white; font-size:1.5rem;'>안녕하세요. <?php $n=$_SESSION['name']; echo $n; ?>님!</div>
    <div style="display:inline-flex; flex-direction:column; width:80%; height:auto; display:inline-flex; flex-direction:column; margin:0rem 10% 0rem; text-align:center;">
        <h4>학생들의 최근 체온 목록입니다.</h4>
        <form action="/record.php" method='post'>
            <table style="text-align:center; width:100%;">
                <thead>
                    <tr>
                        <th>번호</th><th>이름</th><th>일시</th><th>온도</th>
                    </tr>
                </thead>
                <?php
                    $sql1 = "SELECT name, mb_id, code FROM user WHERE teacher='".$_SESSION['mb_id']."' order by code;";
                    $cnt = 1;
                    $result1 = mysqli_query($conn, $sql1);
                    while($row1 = mysqli_fetch_assoc($result1)) {
                        $sql2 = "SELECT time, temp FROM temp WHERE mb_id='".$row1['mb_id']."';";
                        $result2 = mysqli_query($conn, $sql2);
                        $row2 = mysqli_fetch_assoc($result2);
                        while($tmp = mysqli_fetch_assoc($result2)) {
                            $row2 = $tmp;
                            continue;
                        }
                        echo "
                            <tbody>
                                <tr>
                                    <td>".$cnt."</td>
                                    <td><button type='submit' name='student' class='td' value='".$row1['mb_id']."'>".$row1['name']."(".$row1['code'].")</button></td>
                                    <td>".$row2['time']."</td>
                                    <td>".$row2['temp']."</td>
                                </tr>
                            </tbody>
                        ";
                        $cnt=$cnt+1;
                    }

                ?>
            </table>
        </form>
        <h6>학생 이름을 클릭하여 자세한 정보를 확인하세요.</h6>
        <form action="index.php" onsubmit="return logOut(this);" style="display:inline-flex; flex-direction:column; height:auto; margin:0rem 5% 0rem;">
            <input type="submit" value="로그아웃" class="out" />
        </form>
        <script>
        function logOut(f) {
            if (confirm("로그아웃 하시겠습니까?") == true) {
                if (window.sessionStorage) {
	                sessionStorage.clear();
                }
                return true;
            } else {   //취소
                return false;
            }
        }
        if (window.sessionStorage) {
           var id = sessionStorage.getItem('login');

           if(id == null){
              alert("로그인이 필요한 기능입니다.");
              location.href = "login.html";
           }
            else if(id == "S"){
              location.href = "main.php";
           }
        }
        </script>
    </div>
</body>
</html>