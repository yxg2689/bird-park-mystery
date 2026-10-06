<?php
session_start();


// ==============================
// 必須先答對終極問題才能看真相
// ==============================

if (empty($_SESSION['case_solved'])) {
    header("Location: index.php");
    exit();
}

?>

<!DOCTYPE html>

<html>

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>最後真相</title>


    <style>

        * {
            box-sizing: border-box;
        }


        body {

            margin: 0;

            font-family: "Microsoft JhengHei", Arial, sans-serif;

            background-image: url('image/background.jpg');

            background-size: cover;

            background-position: center;

            background-attachment: fixed;

            color: white;
        }


        .page-overlay {

            min-height: 100vh;

            padding: 20px 0 30px;

            background: rgba(30,30,30,0.55);
        }


        .container {

            width: 92%;

            max-width: 700px;

            margin: auto;

            background: rgba(40,40,40,0.85);

            padding: 25px;

            border-radius: 12px;
        }


        h1 {

            text-align: center;

            font-size: 29px;

            margin-top: 0;

            margin-bottom: 25px;
        }


        /* =========================
           劇本結尾（淺黃色）
        ========================= */

        .ending-text {

            text-align: left;

            white-space: pre-wrap;

            font-size: 18px;

            line-height: 1.9;

            color: #fff2b3;

            margin-bottom: 45px;
        }


        /* =========================
           領獎提示（淺綠色）
        ========================= */

        .prize {

            text-align: left;

            font-size: 18px;

            line-height: 1.8;

            color: #c9f5c4;

            background: rgba(140, 220, 140, 0.12);

            border: 2px dashed #9be39b;

            border-radius: 10px;

            padding: 22px 18px 18px;

            margin: 0 0 25px;
        }


        .prize::before {

            content: "🎖️ 偵探任務完成";

            display: block;

            text-align: center;

            font-size: 20px;

            font-weight: bold;

            color: #b4f0ad;

            margin-bottom: 8px;
        }


        /* =========================
           按鈕
        ========================= */

        .btn-link {

            display: block;

            width: 100%;

            text-align: center;

            padding: 12px;

            margin-top: 12px;

            border: 2px solid #ffcc00;

            border-radius: 7px;

            color: #ffcc00;

            background: rgba(255,204,0,0.08);

            text-decoration: none;

            font-weight: bold;

            font-size: 17px;
        }


        .btn-link:hover {

            background: #ffcc00;

            color: #222;
        }


        /* =========================
           手機版
        ========================= */

        @media (max-width:600px) {

            .container {

                width: 94%;

                padding: 18px;
            }


            .ending-text,
            .prize {

                font-size: 16px;
            }


            .ending-text {

                margin-bottom: 35px;
            }


            .prize {

                padding: 18px 14px 14px;
            }
        }

    </style>

</head>


<body>

<div class="page-overlay">


    <div class="container">


        <h1>
            🔍 最後真相
        </h1>


        <!-- ==========================
             劇本結尾
        =========================== -->

        <div class="ending-text">紫蕉鵑的羽色、被偷偷帶進園區的赤頸鶴、工作證 A-9-6、食物牌上的字母 H……
所有線索都指向同一個人——飼育員 Herbert。

原來是飼育員Herbert!!! 他熱愛稀有鳥類，認為園方照顧不周，私自攜入外來種，並以人類食物餵養牠們，想偷偷將牠們轉移到其他環境，但他的做法卻讓鳥類驚恐、園區生態失衡。

在你揭發真相後，Herbert低下頭，神情複雜地說：「我只是想讓牠們過得更好……沒想到反而傷害了牠們。」

園方感謝你的協助，並表示會加強管理與環境改善。

你收起筆記本，微笑著走出鳥園——鳥鳴再次此起彼落，節奏回歸自然。</div>


        <!-- ==========================
             領獎提示
        =========================== -->

        <div class="prize">
            憑此畫面或畫面截圖至入口處服務台領取偵探證章，
            可於明信片寫下對本次遊玩的心得與朋友、家人分享，鳥園會幫您寄出~~~~
        </div>


        <a
            href="index.php"
            class="btn-link"
        >
            回到遊戲首頁
        </a>


    </div>

</div>

</body>

</html>