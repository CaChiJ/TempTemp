<?php
    session_start();
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    $dbName = "service";
    $conn = mysqli_connect("localhost", "root", "200502", $dbName);
    if($_SESSION['name']==null){
        echo "<script>location.href='./login.html'; alert('로그인이 필요한 기능입니다.');</script>";
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="robots" content="noindex">
    <title>템템 - 스마트 체온 등록</title>
    <link rel="stylesheet" href="/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css"
          integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">

    <link rel="shortcut icon" href="image/server/favicon.ico" type="image/x-icon">
    <link rel="icon" href="image/server/favicon.ico" type="image/x-icon">
</head>
<body>
    <div id ="barcode" style ="display:none">
        <?php
            echo $_SESSION['code'];
        ?>
    </div>
    <div style='display:block; background-color:#2194E0; margin-bottom:5%; color:white; font-size:1.5rem;'>안녕하세요. <?php $n=$_SESSION['name']; $c=$_SESSION['code']; echo "$n($c)";?>님!</div>
    <script>
        function formSubmit(f) {
            // 체온 제출 양식 규정
            var temp = document.getElementById("temp").value;
            var teacher = document.getElementById("t_code").value;
            
            if(teacher == "담당 관리자 ID를 입력해주세요." || teacher == "" || teacher == null){
                alert("담당 관리자의 ID를 입력해주세요.");
                return false;
            }
            
            if(temp == "") {
                alert("체온을 입력해 주세요.");
                return false;
            }

            var pos1 = temp.indexOf(".");
            if(pos1 < 0) {
                alert("소수점 아래 한자리까지 입력해주세요.");
                return false;
            }
                
            var pos2 = temp.lastIndexOf(".");
            if(pos1 != pos2) {
                alert("잘못된 형식입니다.")
                return false;
            }
                
            var splited = temp.split(".");
            if(splited[0].length != 2) {
                alert("잘못된 형식입니다.");
                return false;
            }
                
            if(splited[1].length != 1) {
                alert("소수점 아래 한자리까지 입력해주세요.");
                return false;
            }
                
            if (confirm("업로드 하시겠습니까?\n입력된 온도: "+temp) == true) {
                return true;
            } else {   //취소
                return false;
            }
        }
        
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
    </script>
    <div style="display:inline-flex; flex-direction:column; width:80%; height:auto; margin:0rem 10% 0rem;">
        <form name="uploadForm" id="uploadForm" method="post" action="/upload.php" enctype="multipart/form-data" onsubmit="return formSubmit(this);" style="display:inline-flex; flex-direction:column; width:90%; height:auto; margin:0rem 5% 0rem;">
            <h4>체온을 입력해주세요!</h4>
            <input type="number" step="0.1" id="temp" name="temp" placeholder="예) 36.5" style="font-size:3rem; border: 2px solid #2194E0;"  maxlength="4" oninput="numberMaxLength(this);"/>
            <script>
                function numberMaxLength(e){
                    if(e.value.length > e.maxLength){
                        e.value = e.value.slice(0, e.maxLength);
                    }
                }
            </script>
            <h6>* 체온은 1년간 서버에 저장됩니다.</h6>
            <input type="submit" class='m_a_button' name='m' value="업로드"  style="margin-top:10%; padding:4% 0%; -webkit-animation: pulsate-fwd 1s ease-in-out infinite both; animation: pulsate-fwd 1s ease-in-out infinite both; font-family: 'kor'; font-size: 1.8rem;" />
        </form>
        
        <form name="uploadForm" method="post" action="/teacherset.php" enctype="multipart/form-data" style="display:inline; flex-direction:column; width:100%; height:auto; margin:2rem 5% 0rem;">
            <input type="text" name="code" id="t_code" style="font-size:1rem; border: 2px solid #2194E0; margin:0%; width:73%;" value=
                <?php
                    $sql="SELECT teacher FROM user WHERE mb_id='".$_SESSION['mb_id']."';";
                    $result = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_assoc($result);
                    if ($row['teacher'] == NULL) {
                        echo "'담당 관리자 ID를 입력해세주요.'";
                    } else {
                        echo $row['teacher'];
                    }
                ?>
            />
            <input type="submit" value="저장" name="m" class='a_button' style="margin:0%; padding:0% 0%; font-size:1rem; width:15%;" />
        </form>
        <br/><br/>
        <img src="http://www.barcodes4.me/barcode/c128b/<?php echo $_SESSION['code']; ?>.jpg" style="width:match_parant; height:auto;">
        
        <form action="index.php" onsubmit="return logOut(this);" style="display:inline-flex; flex-direction:column; width:90%; height:auto; margin:0rem 5% 0rem;">
            <input type="submit" value="로그아웃" class="out" />
        </form>
    </div>
 <script>
    if (window.sessionStorage) {
       var id = sessionStorage.getItem('login');

       if(id == null){
          alert("로그인이 필요한 기능입니다.");
          location.href = "./login.html";
       }
        else if(id == "T"){
          location.href = "./teacher.php";
       }
    }
</script>
</body>
</html>