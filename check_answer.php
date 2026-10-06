<?php
session_start();


// ==============================
// 取得事件編號
// ==============================

$stage_number = isset($_POST['stage'])
    ? intval($_POST['stage'])
    : 0;


// ==============================
// 必須先用密鑰解鎖這一關
// ==============================

if (empty($_SESSION['unlocked_stages'][$stage_number])) {

    header("Location: index.php");

    exit();
}


// ==============================
// 正確答案
// ==============================

$stages = [

    1 => "紫蕉鵑",

    2 => [
        "赤頸鶴",
        "從印度向東延伸至東南亞，南至澳洲東北部"
    ],

    3 => "A-9-6",

    4 => "H"
];


// ==============================
// 答對後的線索
// ==============================

$success_hints = [

    1 => "線索1：謝謝你……我終於記得我是誰了，我是紫蕉鵑，我原本有著漂亮的羽色。

因為受到重金屬汙染導致我的羽色變了，到底是誰造成這裡汙染！！",

    2 => "線索2：你找到了……赤頸鶴。牠原本棲息在開闊且水源充沛的地方，根本不可能出現在這裡，肯定是有人偷偷帶他來......

不過能偷偷帶鳥進來這裡，想必不是有人默許，就是園區內的工作人員親自帶進來！！。",

    3 => "線索3：A-9-6……這不是某個飼育員的工作證嗎？",

    4 => "線索4：你翻開所有背面標示「可食」的牌子，剛好拼出一個字母H。

🧑‍🌾志工小林：「H……這不是是飼育員Herbert的標記嗎？他負責這區的食物管理。」

你（神情複雜）：「如果是他……那就不只是遊客亂丟那麼簡單了。」"
];


// ==============================
// 答錯提示
// ==============================

$fail_hints = [

    1 => "提示：這裡好像只有我的頭顏色很鮮豔、豐富，頭頂羽毛是紅色、眼睛周圍也裸露紅色、黃色的嘴喙、耳部是白色的，身體是深紫色的...拜託你了！！",

    2 => "提示：那幅畫與扇面之間，有種難以言喻的重疊感。

你不確定是巧合，還是某種刻意的安排......附近的骨骼標本好像也有牠的資訊......",

    3 => "提示：地板上的剪影好像剛好有三種，藍色框框好像在哪裡見過......",

    4 => "提示：🧑‍🌾志工小林（搖頭）：「這些食物太多是人類加工品，鳥類根本不該碰。

你再看看哪些是牠們在自然環境中真正會吃的吧。」而且能吃的位置好像剛好可以拼出些甚麼符號或字母......"
];
$fail_images = [

    4 => [
        'image/caneat.jpg',
        'image/cannoteat.jpg',
    ],
];

// ==============================
// 檢查事件是否存在
// ==============================

if (!isset($stages[$stage_number])) {

    header("Location: index.php");

    exit();
}


// ==============================
// 整理答案：全形轉半形、去空白、英文轉大寫
// 讓 h / Ｈ / a-9-6 這類輸入也能算對
// ==============================

function normalize_answer($text) {

    $text = trim((string)$text);

    if (function_exists('mb_convert_kana')) {
        $text = mb_convert_kana($text, 'as', 'UTF-8');
    }

    $text = preg_replace('/[\s　]+/u', '', $text);

    return strtoupper($text);
}


// ==============================
// 取得玩家答案
// ==============================

$user_answer = $_POST['answer'] ?? '';

$correct_answer = $stages[$stage_number];

$is_correct = false;


// ==============================
// 第二關：兩個答案
// ==============================

if (is_array($correct_answer)) {

    if (is_array($user_answer)
        && count($user_answer) === count($correct_answer)) {

        $is_correct = true;

        foreach ($correct_answer as $index => $answer) {

            if (
                !isset($user_answer[$index]) ||
                normalize_answer($user_answer[$index]) !== normalize_answer($answer)
            ) {

                $is_correct = false;

                break;
            }
        }
    }


// ==============================
// 其他事件：單一答案
// ==============================

} else {

    if (is_string($user_answer)
        && normalize_answer($user_answer) === normalize_answer($correct_answer)) {

        $is_correct = true;
    }
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

    <title>答案檢查</title>


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


        .hint-text {

            text-align: left;

            white-space: pre-wrap;

            font-size: 18px;

            line-height: 1.8;

            margin-bottom: 25px;
        }


        .hint-text img {

            display: block;

            max-width: 100%;

            height: auto;

            margin: 15px auto;
        }

        .hint-images {

            margin: -10px 0 25px;
        }


        .hint-images img {

            display: block;

            width: 100%;

            max-width: 100%;

            height: auto;

            margin: 0 auto 15px;

            border-radius: 8px;
        }


        .hint-images img:last-child {

            margin-bottom: 0;
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


            .hint-text {

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
                ✅ 答對了！
            </h1>


            <div class="hint-text"><?php echo $success_hints[$stage_number]; ?></div>


        <?php else: ?>


            <h1>
                ❌ 答錯了！
            </h1>


            <div class="hint-text"><?php echo $fail_hints[$stage_number]; ?></div>

            <?php if (!empty($fail_images[$stage_number])): ?>

                <div class="hint-images">

                    <?php foreach ($fail_images[$stage_number] as $i => $img): ?>

                        <img
                            src="<?php echo htmlspecialchars($img); ?>"
                            alt="提示圖片 <?php echo $i + 1; ?>"
                        >

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>

            <a
                href="stage.php?stage=<?php echo $stage_number; ?>"
                class="btn-link"
            >
                再試一次
            </a>


        <?php endif; ?>


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