
<html>
	<head>
		<link rel="stylesheet" href="style.css">
        <link rel="canonical" href="http://temptemp.site" />
        <title>템템 - 스마트 체온 관리</title>
        
        <meta name="title" content="템템">
        <meta name="description" content="스마트 체온 등록 및 관리 시스템">
        <meta name="keywords" content="체온, 감염병, 코로나, 건강">
        <meta name="author" content="Temptemp">
        <meta name="robots" content="noimageindex" />
        <meta property="og:type" content="website"> 
        <meta property="og:title" content="템템">
        <meta property="og:description" content="스마트 체온 등록 및 관리 시스템">
        <meta property="og:image" content="http://temptemp-vfbsp.run.goorm.io/server/og.png">
        <meta property="og:url" content="http://temptemp-vfbsp.run.goorm.io/">
        
        <link rel="shortcut icon" href="image/server/favicon.ico" type="image/x-icon">
        <link rel="icon" href="image/server/favicon.ico" type="image/x-icon">
	</head>
	<body>
        <div class="sign" style="font-family: Clip;">
            TempTemp
        </div>
        <div style="text-align : center; margin-bottom:2rem; margin-top:-3rem;" class="mini">
			스마트 체온 관리 시스템 - 템템
		</div>
        <div style="text-align : center;">
            <br/>
            <br/>
            <br/>
            <br/>
            <button type="button" class="kakao" onclick="location.href='http://pf.kakao.com/_xnxjINxb';" title="카카오톡채널 바로가기">
                <img src="image/server/btn.png" alt="카카오톡 연동" class="카카오톡 연동" style="width:300px; height:auto;">
            </button>
            <br/>
            <br/>
            <button onclick="location.href='login.html?type=student';" class="a_button" style="padding: 0.5em 0.9em;">Uploader</button>
            <button onclick="location.href='login.html?type=teacher';" class="a_button">Manager</button>
        </div>
        <div style="text-align : right; font-size:13px; margin-top:-0.5rem">
            <br/>
            <br/>
            <br/>
            <br/>
            <a href="https://github.com/Mango-Juice/temptemp/wiki/%EA%B0%80%EC%9D%B4%EB%93%9C-%EB%AA%A9%EC%B0%A8" style="color: black; text-decoration: none;" target="_blank">가이드</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="terms.html" style="color: black; text-decoration: none;" target="_blank">이용약관</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="mailto:wjsalsrb5@kakao.com?subject=[템템] 사이트 이용 관련 문의&body=성함: %0D%0A전화번호: %0D%0A홈페이지 아이디: %0D%0A문의 내용(구체적으로 입력해주세요): " style="color: black; text-decoration: none;" target="_blank">문의하기</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="mailto:wjsalsrb5@kakao.com?subject=[템템] 홈페이지 버그 제보&body=성함: %0D%0A전화번호: %0D%0A홈페이지 아이디: %0D%0A버그 내용(구체적으로 입력해주세요): " style="color: black; text-decoration: none;" target="_blank">버그제보</a>&nbsp;&nbsp;&nbsp;&nbsp;
        </div>
        <script>
            function isMobile() {
                return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
            }

            if (isMobile()){
                location.href="m/index.php";
            }
            else if (window.sessionStorage) {
               var id = sessionStorage.getItem('login');

               if(id == "S"){
                  location.href = "main.php";
               }
                else if(id == "T"){
                  location.href = "teacher.php";
               }
            }
        </script>
	</body>
</html>