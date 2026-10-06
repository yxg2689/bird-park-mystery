<?php
session_start();


// ==============================
// 遊戲設定：各關卡密鑰
// ==============================

// 左邊是玩家在園區找到的「密鑰」，右邊是對應的關卡編號
// 之後要換成真正的密鑰，只要改左邊的字串即可
// 例如：'FEATHER' => 1,
$stage_keys = [
    '1' => 1,
    '2' => 2,
    '3' => 3,
    '4' => 4,
];

// 各關卡名稱（顯示在已解鎖列表）
$stage_names = [
    1 => '羽色異變',
    2 => '窗邊守望',
    3 => '影跡追蹤',
    4 => '錯食之痕',
];

$stage_labels = [
    1 => '第一個事件',
    2 => '第二個事件',
    3 => '第三個事件',
    4 => '第四個事件',
];


// ==============================
// 記錄已解鎖的關卡
// ==============================

if (!isset($_SESSION['unlocked_stages']) || !is_array($_SESSION['unlocked_stages'])) {
    $_SESSION['unlocked_stages'] = [];
}


// ==============================
// 判斷輸入的密鑰
// ==============================

$key_message = "";

if (isset($_POST['game_key'])) {

    $user_key = trim($_POST['game_key']);

    // 把全形英數字轉成半形（避免手機中文輸入法打出「１」）
    if (function_exists('mb_convert_kana')) {
        $user_key = mb_convert_kana($user_key, 'as', 'UTF-8');
        $user_key = trim($user_key);
    }

    $matched_stage = null;

    foreach ($stage_keys as $key => $stage) {
        // 不分大小寫比對
        if (strcasecmp($user_key, (string)$key) === 0) {
            $matched_stage = $stage;
            break;
        }
    }

    if ($matched_stage !== null) {

        // 密鑰正確：解鎖該關卡並直接進入
        $_SESSION['unlocked_stages'][$matched_stage] = true;

        header("Location: stage.php?stage=" . $matched_stage);
        exit;

    } else {

        $key_message = "❌ 密鑰不正確，請再找找看園區中的線索。";
    }
}

$unlocked_stages = $_SESSION['unlocked_stages'];

?>

<!DOCTYPE html>

<html>

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>鳥園奇案事件簿</title>


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

            line-height: 1.7;
        }


        /* 整個頁面的深色遮罩 */

        .page-overlay {

            min-height: 100vh;

            background: rgba(30, 30, 30, 0.4);

            padding-bottom: 30px;
        }


        /* =========================
           Header
        ========================= */

        .header-image {
            width: 100%;
            height: auto;
            display: block;
        }


        /* =========================
           主要內容
        ========================= */

        .container {

            width: 92%;

            max-width: 750px;

            margin: 20px auto 0;

            background: rgba(35, 35, 35, 0.82);

            border-radius: 12px;

            padding: 22px;

            box-shadow: 0 4px 15px rgba(0,0,0,0.35);
        }


        h1 {

            text-align: center;

            font-size: 30px;

            margin-top: 0;

            margin-bottom: 25px;
        }


        h2 {

            text-align: left;

            font-size: 23px;

            margin-top: 25px;

            margin-bottom: 12px;

            color: #ffdf70;
        }


        /* =========================
           劇情文字
        ========================= */

        .story {

            text-align: left;

            font-size: 17px;

            line-height: 1.9;

            white-space: pre-wrap;

            margin-bottom: 25px;
        }


        /* =========================
           遊戲提示
        ========================= */

        .tips {

            text-align: left;

            background: rgba(0, 0, 0, 0.35);

            border-left: 5px solid #ffcc00;

            padding: 15px 18px;

            margin: 20px 0;

            font-size: 16px;

            line-height: 1.8;
        }


        /* =========================
           地圖
        ========================= */

        .map-container {

            text-align: center;

            margin: 25px 0;
        }


        .map-container img {

            width: 100%;

            height: auto;

            display: block;

            border-radius: 10px;

            margin: auto;
        }


        /* =========================
           密鑰區域
        ========================= */

        .game-section {

            border-top: 1px solid rgba(255,255,255,0.3);

            margin-top: 30px;

            padding-top: 25px;
        }


        .game-section h2 {

            text-align: center;
        }


        .input-label {

            display: block;

            text-align: left;

            margin-bottom: 5px;

            font-size: 16px;
        }


        input[type="text"] {

            width: 100%;

            padding: 13px;

            border: none;

            border-radius: 7px;

            font-size: 17px;

            margin-bottom: 12px;
        }


        button {

            width: 100%;

            padding: 13px;

            border: none;

            border-radius: 7px;

            background-color: #28a745;

            color: white;

            font-size: 18px;

            cursor: pointer;

            transition: 0.2s;
        }


        button:hover {

            background-color: #218838;
        }


        .message {

            text-align: center;

            font-size: 17px;

            margin: 15px 0;

            color: #ffdf70;
        }


        /* =========================
           事件進度（已解鎖 / 未解鎖）
        ========================= */

        .case-list {

            display: grid;

            grid-template-columns: 1fr 1fr;

            gap: 12px;

            margin-top: 20px;
        }


        .case-btn {

            display: flex;

            flex-direction: column;

            align-items: center;

            justify-content: center;

            min-height: 75px;

            padding: 12px;

            background: rgba(255,255,255,0.12);

            border: 2px solid #ffcc00;

            border-radius: 8px;

            color: #ffcc00;

            text-decoration: none;

            font-size: 17px;

            font-weight: bold;

            text-align: center;

            transition: 0.2s;
        }


        a.case-btn:hover {

            background: #ffcc00;

            color: #222;

            transform: translateY(-2px);
        }


        .case-btn.locked {

            border-color: rgba(255,255,255,0.3);

            color: rgba(255,255,255,0.45);

            background: rgba(0,0,0,0.25);

            cursor: default;
        }


        .case-note {

            text-align: center;

            font-size: 14px;

            color: rgba(255,255,255,0.75);

            margin-top: 10px;
        }


        /* =========================
           終極問題
        ========================= */

        .final-question {

            border-top: 1px solid rgba(255,255,255,0.3);

            margin-top: 35px;

            padding-top: 25px;
        }


        .final-question h2 {

            text-align: center;

            color: #ff8c8c;
        }


        .final-question-text {

            text-align: center;

            font-size: 20px;

            font-weight: bold;

            margin: 15px 0 20px;
        }


        /* =========================
           Footer
        ========================= */

        footer {

            text-align: center;

            font-size: 13px;

            margin-top: 30px;

            color: rgba(255,255,255,0.8);
        }


        /* =========================
           手機版
        ========================= */

        @media (max-width: 600px) {


            .container {

                width: 94%;

                padding: 18px;

                margin-top: 15px;
            }


            h1 {

                font-size: 26px;
            }


            h2 {

                font-size: 21px;
            }


            .story {

                font-size: 16px;
            }


            .case-list {

                grid-template-columns: 1fr;
            }
        }

    </style>

</head>


<body>

<div class="page-overlay">


    <!-- =====================================
         首頁 Header
         圖片放在：image/header.png
         ===================================== -->

    <img
        src="image/header.png"
        alt="鳥園奇案事件簿"
        class="header-image"
    >


    <div class="container">


        <h1>
            🕵️ 鳥園奇案事件簿
        </h1>


        <!-- ==============================
             劇情
        =============================== -->

        <div class="story">

鳳凰谷鳥園的清晨，一如往常，鳥島此起彼落，陽光落在濕潤的林地間。

園長卻神情緊張地闖入偵探社的臨時辦公室。

「出事了！出事了！園區連續發生幾件奇怪的事，鳥的行為可疑、羽毛變色，有些鳥甚至出現異常反應！」

園長看著你，神情嚴肅地說：

「我懷疑這不是單純的意外，而是有人在暗中破壞園區的平衡。請你幫忙調查，找出真相！」

你拿起筆記本，準備展開調查。

不過，園區中似乎藏著許多只有細心觀察的人才能發現的線索。

你必須親自走訪園區、尋找密鑰，並透過一個個事件拼湊出真相。

究竟是誰在背後操縱這一切？
        </div>


        <!-- ==============================
             遊戲提示
        =============================== -->

        <h2>
            🔎 遊戲提示
        </h2>


        <div class="tips">

            1. 四個事件各有自己的「密鑰」，藏在園區各處，輸入後會直接進入該事件。<br>

            2. 事件沒有固定順序，先找到哪一把密鑰，就先調查哪個事件。<br>

            3. 每次回答問題所獲得的劇情或線索，都可能成為推理真相的重要資訊。<br>

            4. 不一定要解完所有事件才能回答終極問題。<br>

            5. 當你認為已經知道真相，就可以直接回答終極問題。

        </div>


        <!-- ==============================
             園區地圖
        =============================== -->

        <h2>
            🗺️ 園區地圖
        </h2>


        <div class="map-container">

            <!-- 園區地圖放在：image/map.png -->

            <img
                src="image/map.png"
                alt="園區地圖"
            >

        </div>


        <!-- ==============================
             密鑰區域
        =============================== -->

        <div class="game-section">

            <h2>
                🔐 輸入事件密鑰
            </h2>


            <form method="POST" action="index.php">



                <input
                    type="text"
                    name="game_key"
                    required
                    autocomplete="off"
                    placeholder="輸入在園區找到的密鑰"
                >


                <button type="submit">
                    進入事件
                </button>

            </form>


            <?php if ($key_message !== ""): ?>

                <div class="message">
                    <?php echo htmlspecialchars($key_message); ?>
                </div>

            <?php endif; ?>


            <!-- ==========================
                 調查進度：已解鎖的事件可再次點入
            =========================== -->

            <h2>
                📒 調查進度
            </h2>


            <div class="case-list">

                <?php foreach ($stage_names as $num => $name): ?>

                    <?php if (!empty($unlocked_stages[$num])): ?>

                        <a href="stage.php?stage=<?php echo $num; ?>" class="case-btn">
                            <span><?php echo $stage_labels[$num]; ?></span>
                            <span><?php echo htmlspecialchars($name); ?></span>
                        </a>

                    <?php else: ?>

                        <div class="case-btn locked">
                            <span><?php echo $stage_labels[$num]; ?></span>
                            <span>🔒 尚未解鎖</span>
                        </div>

                    <?php endif; ?>

                <?php endforeach; ?>

            </div>


            <div class="case-note">
                已解鎖的事件可以點擊重新查看。
            </div>

        </div>


        <!-- ==============================
             終極問題
        =============================== -->

        <div class="final-question">

            <h2>
                🔍 終極問題
            </h2>


            <div class="final-question-text">
                誰是這個劇本的兇手？
            </div>


            <form
                method="POST"
                action="final_answer.php"
            >

                <input
                    type="text"
                    name="final_answer"
                    required
                    autocomplete="off"
                    placeholder="輸入你認為的兇手"
                >


                <button type="submit">
                    提交答案
                </button>

            </form>

        </div>


    </div>


    <footer>

        © 國立自然科學博物館｜
        最佳瀏覽體驗：Chrome、Firefox、Edge、Safari

    </footer>


</div>

</body>

</html>