<!DOCTYPE html>
<html>
	<head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1.0, minimum-scale=1.0">
        <meta name="robots" content="noindex">
		<link rel="stylesheet" href="/style.css">
        <title>템템 - 스마트 체온 관리</title>
	</head>
	<body>
		<div class="m_sign" style="font-family: 'event';">
            TempTemp
        </div>
        <div style="text-align : center; margin-top:0.2rem;" class="m_mini">
            <a href="https://forms.gle/BWEfiKBgQSB3YXi79" target="_blank" class="rbw" style="font-family: 'kor';">[간단한 설문조사 참여하고 선물 받아가세요!]</a><br/>
            <button type="button" onclick="location.href='http://pf.kakao.com/_xnxjINxb';" style=" margin-top:11rem; border:0px; background-color:transparent;">
                <img src="../image/server/btn.png" alt="카카오톡 연동" class="카카오톡 연동" style="width:150px; height:auto;">
            </button>
		</div>
        <br/>
        <div style="text-align : center; margin-top:1rem">
            <a href="/m/login.html?type=student" class="m_a_button" style="padding: 0.5rem 0.9rem;">Uploader</a>
            <a href="/m/login.html?type=teacher" class="m_a_button">Manager</a>
        </div>
        <script>
            if (window.sessionStorage) {
               var id = sessionStorage.getItem('login');

               if(id == "S"){
                  location.href = "./main.php";
               }
                else if(id == "T"){
                  location.href = "./teacher.php";
               }
            }
        </script>
	</body>
</html>