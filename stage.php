<?php
session_start();


// ==============================
// 必須先輸入密鑰才能進入事件
// ==============================

$stage = isset($_GET['stage']) ? intval($_GET['stage']) : 0;

// 沒用密鑰解鎖過這一關，就送回首頁
if (empty($_SESSION['unlocked_stages'][$stage])) {
    header("Location: index.php");
    exit;
}


// ==============================
// 所有事件資料
// ==============================

$stages = [

    1 => [
        "title" => "羽色異變",

        "story" => "你走進雨林園，一隻虛弱的鳥蹲在展示板旁，牠的羽毛黯淡無光，喙邊沾著泥漬。牠低聲對你說：

🦜：「我記得我有鮮紅的額頭、綠色的身體、藍色的翅膀……但現在大家都認不出我了。我的羽色變了，我的叫聲也變了。我……還是我嗎？」",

        "question" => "館員遞給你一套鳥貼紙，語氣凝重：「我們懷疑牠的羽色改變是因為水源污染。
重金屬會干擾色素合成，也可能影響牠的繁殖能力。你能幫牠找回身份嗎？」",

        "answer" => "紫蕉鵑"
    ],


    2 => [
        "title" => "窗邊守望",

        "story" => "你走進松鶴館，一股靜謐的氣息瀰漫在空氣中，看見一隻身高體型約1.5公尺的鳥。

🧑‍⚕️館員：「某天牠突然出現在這裡，發現牠是有先拍了X光片，還好他沒有受傷......不過，來了之後總是待在館旁的濕地，牠總是看著窗外，像在守望什麼。直到我們發現，牠是在看那些撞上玻璃的鳥......。」

你心想：「牠是誰？牠從哪裡來？」

你的目光落在牆上的畫作，仔細端詳扇面......也許，找到牠的畫像，就能推出他是____，牠來自哪裡_____(分布)。",

        "questions" => [

            "畫像中的鳥是哪種鳥？",

            "該種鳥的分布範圍是？(提示：請盡可能完整回答，答案在解說板上!)"

        ],

        "answers" => [

            "赤頸鶴",

            "從印度向東延伸至東南亞，南至澳洲東北部"

        ]
    ],


    3 => [
        "title" => "影跡追蹤",

        "story" => "你踏入梅鶯園時，園區入口的告示牌上貼著一張手寫紙條：

「如果你在園內發現15公分的影子，請立刻通報管理員。牠們不屬於這裡。」

旁邊還畫著三個模糊的鳥類剪影。

🕵️‍♂️你（心想）：15公分？是指鳥的體長？還是牠留下的痕跡？

你在園區志工站借到一把特製皮尺，上面印有一排鳥類剪影，好像有些特別的地方......",

        "question" => "你開始在園區三條小徑上比對剪影。找出那個「15公分的影子」是誰的。(提示:答案為該鳥類之籠舍編號)",

        "answer" => "A-9-6"
    ],


    4 => [
        "title" => "錯食之痕",

        "story" => "你來到親禽園，一隻鸚鵡正蹲在角落，羽毛蓬亂，神情疲憊。旁邊的地上散落著幾根薯條。

🧑‍🌾志工小林嘆氣：
「又有人餵牠薯條……牠本來只是在這裡避雨，現在卻吃進了不該吃的東西。」

🕵️‍♂️你：到底是誰把薯條留在這裡？

🧑‍🌾志工小林：我們還在查。園區有一組食物辨識板，你可以去看看哪些東西牠能吃、哪些不能吃。也許有些線索......",

        "question" => "你拿起一塊磁鐵，走向園區的翻牌牆。每塊牌子都畫著一種食物：麵包、果實、爆米花、昆蟲、薯條、種子……你可以翻面查看背後，所有能吃的好像有甚麼特別的地方。

找出所有鳥能吃的食物板子，這些板子剛好可以對應出答案(數字或字母)",

        "answer" => "H"
    ]

];


// ==============================
// 取得玩家選擇的事件
// ==============================

$stage_number = isset($_GET['stage'])
    ? intval($_GET['stage'])
    : 0;


// ==============================
// 如果事件不存在，回首頁
// ==============================

if (!isset($stages[$stage_number])) {

    header("Location: index.php");

    exit();
}


$stage = $stages[$stage_number];

?>

<!DOCTYPE html>

<html>

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?php echo htmlspecialchars($stage['title']); ?>
    </title>


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

            padding: 60px 0 30px;

            background: rgba(30, 30, 30, 0.4);
        }


        .container {

            width: 92%;

            max-width: 700px;

            margin: auto;

            background: rgba(40, 40, 40, 0.82);

            padding: 22px;

            border-radius: 12px;

            box-shadow: 0 4px 15px rgba(0,0,0,0.35);
        }


        h1 {

            text-align: center;

            font-size: 29px;

            margin-top: 0;

            margin-bottom: 25px;
        }


        .story-text {

            text-align: left;

            white-space: pre-wrap;

            font-size: 17px;

            line-height: 1.8;

            margin-bottom: 25px;
        }


        .question-area {

            border-top: 1px solid rgba(255,255,255,0.25);

            padding-top: 20px;
        }


        .question-text {

            text-align: left;

            font-size: 17px;

            line-height: 1.8;

            margin-bottom: 10px;
        }


        input[type="text"] {

            width: 100%;

            padding: 12px;

            border: none;

            border-radius: 7px;

            font-size: 17px;

            margin-top: 8px;

            margin-bottom: 18px;
        }


        button {

            width: 100%;

            padding: 12px;

            border: none;

            border-radius: 7px;

            background-color: #28a745;

            color: white;

            font-size: 17px;

            cursor: pointer;
        }


        button:hover {

            background-color: #218838;
        }


        .back-btn {

            display: block;

            width: 100%;

            text-align: center;

            padding: 11px;

            margin-top: 12px;

            border: 2px solid #ffcc00;

            border-radius: 7px;

            background: rgba(255,204,0,0.08);

            color: #ffcc00;

            text-decoration: none;

            font-weight: bold;
        }


        .back-btn:hover {

            background: #ffcc00;

            color: #222;
        }


        hr {

            border: none;

            border-top: 1px solid rgba(255,255,255,0.25);

            margin: 20px 0;
        }


        @media (max-width: 600px) {

            .container {

                width: 94%;

                padding: 18px;
            }

            .page-overlay {

                padding-top: 35px;

            }


            h1 {

                font-size: 26px;
            }


            .story-text,
            .question-text {

                font-size: 16px;
            }
        }

    </style>

</head>


<body>

<div class="page-overlay">


    <div class="container">


        <h1>

            <?php echo htmlspecialchars($stage['title']); ?>

        </h1>


        <!-- ==========================
             劇情
        =========================== -->

        <div class="story-text">

            <?php echo nl2br($stage['story']); ?>

        </div>


        <!-- ==========================
             問題
        =========================== -->

        <div class="question-area">


            <form
                action="check_answer.php"
                method="POST"
            >

                <!-- 把現在是哪一個事件一起送出去 -->

                <input
                    type="hidden"
                    name="stage"
                    value="<?php echo $stage_number; ?>"
                >


                <?php if (
                    isset($stage['questions'])
                    && is_array($stage['questions'])
                ): ?>


                    <?php foreach (
                        $stage['questions']
                        as $index => $question
                    ): ?>

                        <div class="question-text">

                            <label>

                                <?php
                                echo htmlspecialchars($question);
                                ?>

                            </label>


                            <br>


                            <input
                                type="text"
                                name="answer[]"
                                required
                                autocomplete="off"
                            >

                        </div>


                        <?php if (
                            $index < count($stage['questions']) - 1
                        ): ?>

                            <hr>

                        <?php endif; ?>


                    <?php endforeach; ?>


                <?php else: ?>


                    <div class="question-text">

                        <label>

                            <?php
                            echo htmlspecialchars($stage['question']);
                            ?>

                        </label>


                        <br>


                        <input
                            type="text"
                            name="answer"
                            required
                            autocomplete="off"
                        >

                    </div>


                <?php endif; ?>


                <button type="submit">

                    提交答案

                </button>

            </form>


            <a
                href="index.php"
                class="back-btn"
            >
                回到遊戲首頁
            </a>


        </div>


    </div>


</div>

</body>

</html>