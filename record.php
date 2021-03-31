<?php
	session_start();
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    $dbName = "service";
    $conn = mysqli_connect("localhost", "root", "200502", $dbName);
    mysqli_query($conn, "set session character_set_connection=utf8;");
    mysqli_query($conn, "set session character_set_results=utf8;");
    mysqli_query($conn, "set session character_set_client=utf8;");
    if($_SESSION['name']==null){
        echo "<script>location.href='./login.html'; alert('로그인이 필요한 기능입니다.');</script>";
    }
    
    //student에 대한 정보 열람 권한 갖고 있는지 확인.
    $sql = "SELECT name, mb_id FROM user WHERE teacher='".$_SESSION['mb_id']."';";
    $result = mysqli_query($conn, $sql);
    $access = false;
    while($row = mysqli_fetch_assoc($result)) {
        if($row['mb_id'] == $_POST['student']) {
            $access = true;
            break;
        }
    }
    if(!$access) {
        echo "<script>history.back(); alert('".$row['name']."학생의 정보에 대한 접근이 비활성화 되어 있습니다.');</script>";
    }
?>

<style>
</style>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width" />
    <title>학생 체온 관리 시스템 - 관리자</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css"
          integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <link rel="shortcut icon" href="image/server/favicon.ico" type="image/x-icon">
    <link rel="icon" href="image/server/favicon.ico" type="image/x-icon">
    
    <script src="https://d3js.org/d3.v5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/billboard.js/dist/billboard.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/billboard.js/dist/billboard.min.css">
</head>
<body>
    <div style='display:block; background-color:#2194E0; margin-bottom:5%; color:white; font-size:1.5rem;'>안녕하세요. <?php $n=$_SESSION['name']; echo $n; ?>님!</div>
    <div style="display:inline-flex; flex-direction:column; width:80%; height:auto; display:inline-flex; flex-direction:column; margin:0rem 10% 0rem; text-align:center;">
        <h4><?php echo $row['name']."(".$_POST['student'].")";?>님의 최근 체온 목록입니다.</h4>
        <table style="text-align:center; width:100%;">
            <thead>
                <tr>
                    <th>번호</th><th>일시</th><th>온도</th>
                </tr>
            </thead>
            <?php
                $sql2 = "SELECT time, temp FROM temp WHERE mb_id='".$_POST['student']."';";
                $result2 = mysqli_query($conn, $sql2);
                $cnt = 1;
                while($row2 = mysqli_fetch_assoc($result2)) {
                    echo "<tbody>
                            <tr>
                                <td>".$cnt."</td>
                                <td id='".$cnt."t'>".$row2['time']."</td>
                                <td id='".$cnt."'>".$row2['temp']."</td>
                            </tr>
                        </tbody>";
                    $cnt += 1;
                }
            ?>
        </table>
        <br/>
        <div id="chart"></div>
        <form action="login.html" onsubmit="return logOut(this);" style="display:inline-flex; flex-direction:column; height:auto;">
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
<script>
    var cnt = 0;
    var temp = new Array();
    var xs = new Array();
    temp[0] = "체온";
    while(1){
        cnt = cnt + 1;
        var data = document.getElementById(cnt);
        if(data==null) break;
        temp[cnt]=data.innerText;
        xs[cnt] = document.getElementById(String(cnt)+'t').innerText.split(' ')[0];
    }
  bb.generate({
      bindto: "#chart",
      data: {
          columns: [
              temp
          ],
      },
      axis: {
          x: {
            tick: {
              format: function(x) {
                  return xs[x+1];
              }
            }
          }
        }
  });
</script>
</html>