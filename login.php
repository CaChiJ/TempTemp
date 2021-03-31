<?php
    session_start();
    $iid=$_POST['id'];
    $ipw=$_POST['pw'];
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    $host = 'localhost';
    $user = 'root';
    $pw = '200502';
    $dbName = 'service';
    $mysqli = new mysqli($host, $user, $pw, $dbName);
    mysqli_query($mysqli, "set session character_set_connection=utf8;");
    mysqli_query($mysqli, "set session character_set_results=utf8;");
    mysqli_query($mysqli, "set session character_set_client=utf8;");
    mysqli_set_charset($mysqli, 'utf8'); 
    $access = 0;    
    $from = '';
    $platform = '';    
    //$from에다가 전체 경로를 저장하는 게 아니라, 플랫폼 정보만 기입하는 방식으로 쓰는 게 나을듯 -> 일단 두고 나중에 공사하자
    if (isset($_POST['login'])) {
        $access = 1;
        $from = '/login.html';
        $platform = '/';
    } else if (isset($_POST['m_login'])) {
        $access = 1;
        $from = '/m/login.html';
        $platform = '/m/';
    } else if (isset($_POST['join'])) {
        $access = 2;
        $from = '/register.html';
        $platform = '/';
    } else if (isset($_POST['m_join'])) {
        $access = 2;
        $from = '/m/register.html';
        $platform = '/m/';
    }
    if ($access == 1){
        $check = "SELECT * FROM user WHERE mb_id='$iid'"; 
        $result = $mysqli->query($check);
        if($result->num_rows==1){
            $row=$result->fetch_array(MYSQLI_ASSOC); //하나의 열을 배열로 가져오기
            if(password_verify($ipw, $row['mb_pw'])){  //MYSQLI_ASSOC 필드명으로 첨자 가능
                $_SESSION['mb_id']=$iid;           //로그인 성공 시 세션 변수 만들기
                if(!isset($_SESSION['mb_id']))    //세션 변수가 설정되지 않았을 때
                {
                    echo "<script>alert('오류가 발생했습니다. 잠시 후 다시 시도해주세요.'); location.href='".$from."';</script>";
                }
                else{
                    if($row['type']=="student"){
                        $_SESSION['name']=$row['name'];
                        $_SESSION['code']=$row['code'];
                        echo "<script>if(window.sessionStorage){sessionStorage.setItem('login', 'S');} location.href='.".$platform."main.php';</script>";
                    }
                    else{
                        $_SESSION['name']=$row['name'];
                        echo "<script>if(window.sessionStorage){sessionStorage.setItem('login', 'T');} location.href='.".$platform."teacher.php';</script>";
                    }
                }
            }
            else{
                echo "<script>alert('패스워드를 잘못 입력하셨습니다.'); location.href='".$from."';</script>";
            }
        }
        else{
            echo "<script>alert('등록되지 않은 아이디입니다.'); location.href='".$from."';</script>";
        }
    }

    else if ($access == 2){
        header('Content-Type: text/html; charset=UTF-8');
        if(isset($_POST['g-recaptcha-response'])){
            $captcha=$_POST['g-recaptcha-response'];
        }
        $secretKey = "6LcSLfcUAAAAAKy6H7lwS7UMIOuPS5pljOac54ja";
        $ip = $_SERVER['REMOTE_ADDR'];

        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$captcha."&remoteip=".$ip);
        $responseKeys = json_decode($response,true);
        
        if(intval($responseKeys["success"]) !== 1) {
            echo "<script>alert('캡차(로봇이 아닙니다) 인증을 완료해주세요.'); location.href='".$from."';</script>";
        }
        else{
            $check = "SELECT * FROM user WHERE mb_id='$iid'"; 
            $result = $mysqli->query($check);
            if($result->num_rows!=1){
                $iname= $_POST['name'];
                $itype=$_POST['type'];
                $icode=$_POST['num'];
                $err=false;
                $agree='disagree';
                if(isset($_POST['agree'])) $agree = $_POST['agree'];
                if(!$captcha) { echo "<script>alert('캡차(로봇이 아닙니다) 인증을 완료해주세요.'); location.href='".$from."';</script>"; $err=true; }
                else if(mb_strlen($iid, "UTF-8") >= 21){ echo "<script>alert('아이디가 너무 깁니다.'); location.href='".$from."';</script>"; $err=true;}
                else if(mb_strlen($iid, "UTF-8") == 0){ echo "<script>alert('아이디는 비워둘 수 없습니다.'); location.href='".$from."';</script>"; $err=true;}
                else if(mb_strlen($iid, "UTF-8") <= 6){ echo "<script>alert('아이디가 너무 짧습니다.'); location.href='".$from."';</script>"; $err=true;}
                else if(mb_strlen($ipw, "UTF-8") == 0){ echo "<script>alert('비밀번호는 비워둘 수 없습니다.'); location.href='".$from."';</script>"; $err=true;}
                else if(mb_strlen($ipw, "UTF-8") >= 21){ echo "<script>alert('비밀번호가 너무 깁니다.'); location.href='".$from."';</script>"; $err=true;}
                else if(mb_strlen($ipw, "UTF-8") <= 7){ echo "<script>alert('비밀번호가 너무 짧습니다.'); location.href='".$from."';</script>"; $err=true;}
                else if(mb_strlen($iname, "UTF-8") == 0){ echo "<script>alert('성함은 비워둘 수 없습니다.'); location.href='".$from."';</script>"; $err=true;}
                else if(mb_strlen($iname, "UTF-8") >= 6){ echo "<script>alert('성함이 너무 깁니다.'); location.href='".$from."';</script>"; $err=true;}
                else if($agree=='disagree'){ echo "<script>alert('약관에 동의하지 않으면 가입하실 수 없습니다.'); location.href='".$from."';</script>"; $err=true;}
                else if($itype=="student" && mb_strlen($icode, "UTF-8") != 7){
                    echo "<script>alert('학생 확인 코드는 7자리이어야 합니다.'); location.href='".$from."'; </script>"; $err=true;
                }
                else if($itype=="student"){
                    $check = "SELECT * FROM user WHERE code='$icode'"; 
                    $result = $mysqli->query($check);
                    if($result->num_rows==1){ echo "<script>alert('이미 사용중인 학생 확인 코드입니다.'); location.href='".$from."'; </script>"; $err=true; }
                }
                else if($ipw!=$_POST['pwConfirm']) {
                    echo "<script>alert('비밀번호와 비밀번호 확인이 일치하지 않습니다.'); location.href='".$from."';</script>"; $err=true;
                }
                //echo $ipw." ".$_POST['pwConfirm'];
                //거를거 다 거르고 나면
                if($err == false){
                    $hashedpw = password_hash($ipw, PASSWORD_DEFAULT);
                    $insert = "INSERT INTO user (name, type, mb_id, mb_pw) VALUES ('$iname','$itype','$iid','$hashedpw');"; 
                    if($itype=="student"){
                        $insert = "INSERT INTO user (name, type, mb_id, mb_pw, code) VALUES ('$iname','$itype','$iid','$hashedpw','$icode');"; 
                    }
                    $result = mysqli_query($mysqli, $insert);
                    if ($result) {
                        echo "<script>alert('회원가입이 완료되었습니다.'); location.href='".$from."';</script></script>";
                        header('Location: '.$platform.'login.html');
                    }
                    else { 
                        echo "<script>alert('회원가입중 오류가 발생하였습니다. '); location.href='".$from."';</script></script>"; // location.href='".$from."';
                    }    // location.href='".$from."';
                }
            }
            else{
                echo "<script>alert('이미 사용중인 아이디입니다.'); location.href='".$from."';</script>";
            }
        }
    }
    else if (isset($_POST['join_page'])){
        header('Location: ./register.html');
    }
    else if (isset($_POST['login_page'])){
        header('Location: ./login.html');
    }
    else if (isset($_POST['m_join_page'])){
        header('Location: ./m/register.html');
    }
    else if (isset($_POST['m_login_page'])){
        header('Location: ./m/login.html');
    }
?>
