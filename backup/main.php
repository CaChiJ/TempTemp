<?php
    session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width" />
    <title>학생 체온 관리 시스템</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css"
          integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">

    <link rel="shortcut icon" href="image/server/favicon.ico" type="image/x-icon">
    <link rel="icon" href="image/server/favicon.ico" type="image/x-icon">
    
    <script src="https://code.jquery.com/jquery-2.1.0.min.js"></script>
    <script>
        $(document).ready(function() {
            if (!('url' in window) && ('webkitURL' in window)) {
                window.URL = window.webkitURL;
            }
            $('#camera').change(function(e) {
                $('#pic').attr('src', URL.createObjectURL(e.target.files[0]));
            });
        });
        
        $(function() {
            $("#camera").on('change', function(){
                readURL(this);
            });
        });
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#img').attr('src', e.target.result);
                }
            reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</head>
<body>
    <div style='display:block; background-color:#2194E0; color:white; font-size:4rem;'>안녕하세요. <?php $n=$_SESSION['name']; $c=$_SESSION['code']; echo "$n($c)";?>님!</div>
    
    <form name="uploadForm" id="uploadForm" method="post" action="upload.php" enctype="multipart/form-data" onsubmit="return formSubmit(this);">
        <script>
            function formSubmit(f) {
                // 업로드 할 수 있는 파일 확장자 제한
                var extArray = new Array('jpg','gif','png','jpeg');
                var path = document.getElementById("camera").value;

                if(path == "") {
                    alert("파일을 선택해 주세요.");
                    return false;
                }

                var pos = path.indexOf(".");
                if(pos < 0) {
                    alert("확장자가 없는파일 입니다.");
                    return false;
                }

                var ext = path.slice(path.indexOf(".") + 1).toLowerCase();
                var checkExt = false;
                for(var i = 0; i < extArray.length; i++) {
                    if(ext == extArray[i]) {
                        checkExt = true;
                        break;
                    }
                }

                if(checkExt == false) {
                    alert("업로드 할 수 없는 파일 입니다. jpg, jpeg, png, gif 파일만 업로드할 수 있습니다.");
                    return false;
                }

                return true;
            }
        </script>
        
        <div style="display:inline-flex; flex-direction:column; width:40%; height:auto; margin:0rem 30% 0rem;">
            <br />
            <input type="file" id="camera" name="camera" capture="camera" accept="image/*" style="flex:1 1 0;"/>
            <br />
            <image id="img" name="img" src="${pageContext.request.contextPath}/saveFile/${noticeVO.filename}" onerror='this.src="image/server/unknown.png"' style="max-width:100%; height:auto; flex:10 10 0;"/>
            <br />
            <input type="submit" value="업로드" style="flex:1 1 0; margin-bottom:2rem" />
        </div>
        
    </form>
</body>
</html>