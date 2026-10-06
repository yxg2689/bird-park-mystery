<?php
session_start();


// ==============================
// 終極問題正確答案
// （全部寫小寫、不含空白，比對時會自動處理）
// ==============================

$correct_answers = [
    "herbert",
    "飼育員herbert",
];


// ==============================
// 整理玩家答案
// ==============================

function normalize_answer($text) {

    $text = trim($text);

    // 全形英數字、全形空白轉半形
    if (function_exists('mb_convert_kana')) {
        $text = mb_convert_kana($text, 'as', 'UTF-8');
    }

    // 移除所有空白（含全形空白）
    $text = preg_replace('/[\s　]+/u', '', $text);

    // 英文字母轉小寫
    return strtolower($text);
}


// 沒有從表單送出就回首頁
if (!isset($_POST['final_answer'])) {
    header("Location: index.php");
    exit();
}

$user_answer = normalize_answer($_POST['final_answer']);

$is_correct = in_array($user_answer, $correct_answers, true);

// 答對才允許看最後真相
if ($is_correct) {
    $_SESSION['case_solved'] = true;
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

    <title>終極問題</title>


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

            text-align: center;
        }


        h1 {

            font-size: 29px;

            margin-top: 0;

            margin-bottom: 25px;
        }


        .result-text {

            text-align: left;

            font-size: 18px;

            line-height: 1.8;

            margin-bottom: 25px;
        }


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


        @media (max-width:600px) {

            .container {

                width: 94%;

                padding: 18px;
            }


            .result-text {

                font-size: 16px;
            }
        }

    </style>

</head>


<body>

<div class="page-overlay">


    <div class="container">


        <?php if ($is_correct): ?>


            <h1>
                🎉 你找到了真相！
            </h1>


            <div class="result-text">

                你的推理是正確的。

                <br><br>

                你認為這起事件背後的兇手，就是飼育員 Herbert。

                <br><br>

                所有線索似乎都逐漸指向同一個人……

            </div>


            <a
                href="success.php"
                class="btn-link"
            >
                揭開最後真相
            </a>


        <?php else: ?>


            <h1>
                ❌ 目前還不是答案
            </h1>


            <div class="result-text">

                你的推理似乎還缺少了一些關鍵線索。

                <br><br>

                也許你可以再回到園區調查其他事件。

                <br><br>

                不過，如果你已經確定自己的推理，也可以再次嘗試回答終極問題。

            </div>


            <a
                href="index.php"
                class="btn-link"
            >
                回到遊戲首頁
            </a>


        <?php endif; ?>


    </div>

</div>

</body>

</html>