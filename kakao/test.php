<?php

    if(!in_array('application/json',explode(';',$_SERVER['CONTENT_TYPE']))){
        http_response_code(400);
        echo json_encode(array('result_code' => '400'));
        exit;
    }

	$json_data = file_get_contents("php://input"); 
    $obj_json = json_decode($json_data);

    $type = $obj_json->action->detailParams->type->value;
    
    if($type==0){    //테스트
        $text = $obj_json->action->detailParams->id->value;
        $jayParsedAry = [
            "version" => "2.0", 
            "template" => [
                "outputs" => [
                    [
                        "simpleText" => [
                            "text" => $text
                        ]
                    ]
                ]
            ]
        ];
    }

    else if($type==1){    //로그인
        $text = "ok";
        $id = $obj_json->action->detailParams->id->value;
        $pw = $obj_json->action->detailParams->pw->value;
        $kid = $obj_json->userRequest->user->id;
        
        $mysqli = new mysqli('localhost', 'root', '200502', 'service');
        mysqli_query($mysqli, "set session character_set_connection=utf8;");
        mysqli_query($mysqli, "set session character_set_results=utf8;");
        mysqli_query($mysqli, "set session character_set_client=utf8;");
        mysqli_set_charset($mysqli, 'utf8'); 
        
        $check = "SELECT * FROM user WHERE mb_id='$id';"; 
        $result = $mysqli->query($check);
        if($result->num_rows==1){
            $row=$result->fetch_array(MYSQLI_ASSOC); //하나의 열을 배열로 가져오기
            if(password_verify($pw, $row['mb_pw'])){  //MYSQLI_ASSOC 필드명으로 첨자 가능
                if($row['type']=="student"){
                    $insert = "UPDATE user SET kakao = '".$kid."' WHERE mb_id = '".$id."';";
                    if(mysqli_query($mysqli, $insert)){
                        $jayParsedAry = [
                                "version" => "2.0", 
                                "template" => [
                                    "outputs" => [
                                        [
                                            "simpleText" => [
                                                "text" => "로그인이 완료되었습니다. 이제부터 카카오톡을 통해 쉽게 체온을 등록할 수 있습니다. (추후 재이용시 다시 로그인하지 않으셔도 됩니다.)"
                                            ]
                                        ]
                                    ],
                                    "quickReplies" => [
                                        [
                                            "label" => "도움말",
                                            "action" => "message",
                                            "messageText" => "도움말"
                                        ],
                                        [
                                            "label" => "체온 등록",
                                            "action" => "message",
                                            "messageText" => "체온 등록"
                                        ],
                                        [
                                            "label" => "관리자 등록",
                                            "action" => "message",
                                            "messageText" => "관리자등록"
                                        ]
                                    ]
                                ]
                            ];
                    }else{
                        $text="알 수 없는 오류가 발생하였습니다. 관리자에게 문의하세요. 500";
                        $jayParsedAry = [
                            "version" => "2.0", 
                            "template" => [
                                "outputs" => [
                                    [
                                        "simpleText" => [
                                            "text" => $text
                                        ]
                                    ]
                                ]
                            ]
                        ];
                    }
                }
                else{
                    $text = "카카오톡 서비스는 관리자 모드를 지원하지 않습니다.";
                    $jayParsedAry = [
                            "version" => "2.0", 
                            "template" => [
                                "outputs" => [
                                    [
                                        "simpleText" => [
                                            "text" => $text
                                        ]
                                    ]
                                ]
                            ]
                        ];
                }
            }
            else{
                $text = "잘못된 패스워드입니다.";
                $jayParsedAry = [
                            "version" => "2.0", 
                            "template" => [
                                "outputs" => [
                                    [
                                        "simpleText" => [
                                            "text" => $text
                                        ]
                                    ]
                                ],
                                "quickReplies" => [
                                    [
                                        "label" => "재시도",
                                        "action" => "message",
                                        "messageText" => "로그인"
                                    ]
                                ]
                            ]
                        ];
            }
        }
        else{
            $text = "등록되지 않은 아이디입니다.";
            $jayParsedAry = [
                            "version" => "2.0", 
                            "template" => [
                                "outputs" => [
                                    [
                                        "simpleText" => [
                                            "text" => $text
                                        ]
                                    ]
                                ],
                                "quickReplies" => [
                                    [
                                        "label" => "재시도",
                                        "action" => "message",
                                        "messageText" => "로그인"
                                    ]
                                ]
                            ]
                        ];
        }

    }
    else if($type==2){    //체온등록
        $temp = $obj_json->action->detailParams->temp->value;
        $kid = $obj_json->userRequest->user->id;
        
        $mysqli = new mysqli('localhost', 'root', '200502', 'service');
        mysqli_query($mysqli, "set session character_set_connection=utf8;");
        mysqli_query($mysqli, "set session character_set_results=utf8;");
        mysqli_query($mysqli, "set session character_set_client=utf8;");
        mysqli_set_charset($mysqli, 'utf8'); 
        
        $check = "SELECT * FROM user WHERE kakao='$kid';"; 
        $result = $mysqli->query($check);
        if(mb_strlen($temp, "UTF-8") == 4){
            if($result->num_rows==1){
                $row=$result->fetch_array(MYSQLI_ASSOC);
                date_default_timezone_set("Asia/Seoul");
                $sql = "INSERT INTO temp (mb_id, time, temp) VALUES('".$row['mb_id']."', '".date("Y-m-d H:i:s")."', ".$temp.");";
                if($row['teacher']==null){
                    $text = "관리자가 입력되지 않았습니다. 관리자 ID를 설정해주세요.";
                    $jayParsedAry = [
                                    "version" => "2.0", 
                                    "template" => [
                                        "outputs" => [
                                            [
                                                "simpleText" => [
                                                    "text" => $text
                                                ]
                                            ]
                                        ],
                                        "quickReplies" => [
                                            [
                                                "label" => "관리자 설정",
                                                "action" => "message",
                                                "messageText" => "관리자"
                                            ]
                                        ]
                                    ]
                                ];
                }
                else{
                    if(mysqli_query($mysqli, $sql)){
                        $text = "체온 등록 성공!\n\n등록 일시:".date("Y.m.d H:i:s")."\n체온: ".$temp."℃\n관리자: ".$row['teacher'];
                        $jayParsedAry = [
                                    "version" => "2.0", 
                                    "template" => [
                                        "outputs" => [
                                            [
                                                "simpleText" => [
                                                    "text" => $text
                                                ]
                                            ]
                                        ]
                                    ]
                                ];
                    }
                    else{
                        $text = "알맞은 형식이 아닙니다. OO.O의 형식으로 입력해주세요.";
                        $jayParsedAry = [
                                    "version" => "2.0", 
                                    "template" => [
                                        "outputs" => [
                                            [
                                                "simpleText" => [
                                                    "text" => $text
                                                ]
                                            ]
                                        ],
                                        "quickReplies" => [
                                            [
                                                "label" => "재시도",
                                                "action" => "message",
                                                "messageText" => "체온등록"
                                            ]
                                        ]
                                    ]
                                ];
                    }
                }
            }
            else{
                $text = "체온을 등록하시려면 먼저 로그인해주세요.";
                $jayParsedAry = [
                                "version" => "2.0", 
                                "template" => [
                                    "outputs" => [
                                        [
                                            "simpleText" => [
                                                "text" => $text
                                            ]
                                        ]
                                    ],
                                    "quickReplies" => [
                                        [
                                            "label" => "로그인",
                                            "action" => "message",
                                            "messageText" => "로그인"
                                        ]
                                    ]
                                ]
                            ];
            }
        }
        else{
            $text = "알맞은 형식이 아닙니다. OO.O의 형식으로 입력해주세요.";
            $jayParsedAry = [
                                "version" => "2.0", 
                                "template" => [
                                    "outputs" => [
                                        [
                                            "simpleText" => [
                                                "text" => $text
                                            ]
                                        ]
                                    ],
                                    "quickReplies" => [
                                        [
                                            "label" => "재시도",
                                            "action" => "message",
                                            "messageText" => "체온등록"
                                        ]
                                    ]
                                ]
                            ];
        }
    }
    else if($type==3){    //관리자 설정
        $tid = $obj_json->action->detailParams->id->value;
        $kid = $obj_json->userRequest->user->id;
        
        $mysqli = new mysqli('localhost', 'root', '200502', 'service');
        mysqli_query($mysqli, "set session character_set_connection=utf8;");
        mysqli_query($mysqli, "set session character_set_results=utf8;");
        mysqli_query($mysqli, "set session character_set_client=utf8;");
        mysqli_set_charset($mysqli, 'utf8'); 
        
        $check = "SELECT * FROM user WHERE mb_id='$tid';"; 
        $result = $mysqli->query($check);
        if($result->num_rows==1){
            $row=$result->fetch_array(MYSQLI_ASSOC);
            $sql = "UPDATE user SET teacher= '".$tid."' WHERE kakao = '".$kid."';";
            if(mysqli_query($mysqli, $sql)){
                $text = "관리자 등록 성공!\n\n관리자 이름: ".$row['name']."\n관리자 ID: ".$tid;
                $jayParsedAry = [
                                "version" => "2.0", 
                                "template" => [
                                    "outputs" => [
                                        [
                                            "simpleText" => [
                                                "text" => $text
                                            ]
                                        ]
                                    ],
                                    "quickReplies" => [
                                        [
                                            "label" => "체온 등록",
                                            "action" => "message",
                                            "messageText" => "체온등록"
                                        ]
                                    ]
                                ]
                            ];
            }
            else{
                $text = "관리자 등록하시려면 먼저 로그인해주세요.";
                $jayParsedAry = [
                                "version" => "2.0", 
                                "template" => [
                                    "outputs" => [
                                        [
                                            "simpleText" => [
                                                "text" => $text
                                            ]
                                        ]
                                    ],
                                    "quickReplies" => [
                                        [
                                            "label" => "로그인",
                                            "action" => "message",
                                            "messageText" => "로그인"
                                        ]
                                    ]
                                ]
                            ];
            }
        }
        else{
            $text = "존재하지 않는 관리자 ID입니다.";
            $jayParsedAry = [
                                    "version" => "2.0", 
                                    "template" => [
                                        "outputs" => [
                                            [
                                                "simpleText" => [
                                                    "text" => $text
                                                ]
                                            ]
                                        ],
                                        "quickReplies" => [
                                            [
                                                "label" => "재시도",
                                                "action" => "message",
                                                "messageText" => "관리자"
                                            ]
                                        ]
                                    ]
                                ];
        }
    }
    else if($type==4){    //내정보
        $kid = $obj_json->userRequest->user->id;
        
        $show_name = "로그인하지 않음";
        $show_id = "알 수 없음";
        $show_code = "알 수 없음";
        $show_teacher_id = "미등록";
        $show_teacher_name = "미등록";
        $default = true;
        
        $mysqli = new mysqli('localhost', 'root', '200502', 'service');
        $check = "SELECT * FROM user WHERE kakao='".$kid."';"; 
        $result = $mysqli->query($check);
        if($result->num_rows==1){
            $row=$result->fetch_array(MYSQLI_ASSOC);
            $show_name = $row['name'];
            $show_id = $row['mb_id'];
            $show_code = $row['code'];
            if($row['teacher']!=null){
                $show_teacher_id = $row['teacher'];
                $check = "SELECT * FROM user WHERE mb_id='".$show_teacher_id."';"; 
                $result = $mysqli->query($check);
                if($result->num_rows==1){
                    $row=$result->fetch_array(MYSQLI_ASSOC);
                    $show_teacher_name = $row['name'];
                    $check = "SELECT * FROM temp WHERE mb_id='".$show_id."' order by id desc limit 1;"; 
                    $result = $mysqli->query($check);
                    if($result->num_rows>=1){
                        $row=$result->fetch_array(MYSQLI_ASSOC);
                        $default = false;
                        $jayParsedAry = [
                            "version" => "2.0", 
                            "template" => [
                                "outputs" => [
                                    [
                                        "listCard" => [
                                            "header" => [
                                                "title" => "사용자 정보"
                                            ],
                                            "items" => [
                                                [
                                                    "title" => "이름(아이디)",
                                                    "description" => "$show_name($show_id)"
                                                ],
                                                [
                                                    "title" => "식별 코드(학생증 코드)",
                                                    "description" => $show_code
                                                ],
                                                [
                                                    "title" => "담당 관리자",
                                                    "description" => "$show_teacher_name($show_teacher_id)"
                                                ],
                                                [
                                                    "title" => "최근 등록 체온",
                                                    "description" => $row['temp']."℃(".$row['time'].")"
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ];
                    }
                }else{
                    $show_teacher_name = "알 수 없음";
                }
            }
        }
        
        if($default){
            $text="[사용자 정보]\n이름: $show_name\nID: $show_id\n식별 코드: $show_code\n관리자: $show_teacher_name($show_teacher_id)";
            $jayParsedAry = [
                "version" => "2.0", 
                "template" => [
                    "outputs" => [
                        [
                            "simpleText" => [
                                "text" => $text
                            ]
                        ]
                    ]
                ]
            ];
        }
    }
    else if($type==5){    //바코드
        $kid = $obj_json->userRequest->user->id;
        
        $mysqli = new mysqli('localhost', 'root', '200502', 'service');
        $check = "SELECT * FROM user WHERE kakao='".$kid."';"; 
        $result = $mysqli->query($check);
        if($result->num_rows==1){
            $row=$result->fetch_array(MYSQLI_ASSOC);
            $code = $row['code'];
            $image = "http://www.barcodes4.me/barcode/c128b/".$code.".jpg";
            $jayParsedAry = [
                                "version" => "2.0", 
                                "template" => [
                                    "outputs" => [
                                        [
                                            "simpleImage" => [
                                                "imageUrl" => $image,
                                                "altText" => $code
                                            ]
                                        ]
                                    ]
                                ]
                            ];
        }
        else{
            $text = "바코드를 불러오시려면 먼저 로그인해주세요.";
            $jayParsedAry = [
                                "version" => "2.0", 
                                "template" => [
                                    "outputs" => [
                                        [
                                            "simpleText" => [
                                                "text" => $text
                                            ]
                                        ]
                                    ],
                                    "quickReplies" => [
                                        [
                                            "label" => "로그인",
                                            "action" => "message",
                                            "messageText" => "로그인"
                                        ]
                                    ]
                                ]
                            ];
        }
        
    }
    else if($type==6){    //관리자모드
        $kid = $obj_json->userRequest->user->id;
        
        $mysqli = new mysqli('localhost', 'root', '200502', 'service');
        $check = "SELECT * FROM user WHERE kakao='".$kid."';"; 
        $result = $mysqli->query($check);
        if($result->num_rows==1){
            $row=$result->fetch_array(MYSQLI_ASSOC);
            if($row['type']=="teacher"){
                //담당 학생의 체온 중 상위 6명의 체온만 카카오톡 리스트뷰로 전송하는 코드
            }else{
                $text = "관리자 모드는 관리자 계정으로만 접근할 수 있습니다.";
                $jayParsedAry = [
                                    "version" => "2.0", 
                                    "template" => [
                                        "outputs" => [
                                            [
                                                "simpleText" => [
                                                    "text" => $text
                                                ]
                                            ]
                                        ]
                                    ]
                                ];
            }
        }
        else{
            $text = "먼저 로그인해주세요.";
            $jayParsedAry = [
                                "version" => "2.0", 
                                "template" => [
                                    "outputs" => [
                                        [
                                            "simpleText" => [
                                                "text" => $text
                                            ]
                                        ]
                                    ],
                                    "quickReplies" => [
                                        [
                                            "label" => "로그인",
                                            "action" => "message",
                                            "messageText" => "로그인"
                                        ]
                                    ]
                                ]
                            ];
        }
    }
    else if($type==7){
        
        $kid = $obj_json->userRequest->user->id;
        
        $mysqli = new mysqli('localhost', 'root', '200502', 'service');
        mysqli_query($mysqli, "set session character_set_connection=utf8;");
        mysqli_query($mysqli, "set session character_set_results=utf8;");
        mysqli_query($mysqli, "set session character_set_client=utf8;");
        mysqli_set_charset($mysqli, 'utf8'); 
        
        
        $check = "UPDATE user SET kakao=NULL WHERE kakao='$kid';"; 
        $mysqli->query($check);
        
        $text = "로그아웃 되셨습니다.";
        $jayParsedAry = [
                        "version" => "2.0", 
                        "template" => [
                            "outputs" => [
                                [
                                    "simpleText" => [
                                        "text" => $text
                                    ]
                                ]
                            ],
                            "quickReplies" => [
                                [
                                    "label" => "로그인",
                                    "action" => "message",
                                    "messageText" => "로그인"
                                ]
                            ]
                        ]
                    ];
    }
    else{
        $text="알 수 없는 오류가 발생하였습니다. 관리자에게 문의하세요. 400";
        $jayParsedAry = [
            "version" => "2.0", 
            "template" => [
                "outputs" => [
                    [
                        "simpleText" => [
                            "text" => $text
                        ]
                    ]
                ]
            ]
        ];
    }
    http_response_code(200);
    echo json_encode($jayParsedAry,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
?>