<?php
// データベース接続設定
$db_host = getenv('DB_HOST') ?: 'db';
$db_name = getenv('DB_NAME') ?: 'counter_db';
$db_user = getenv('DB_USER') ?: 'counter_user';
$db_pass = getenv('DB_PASSWORD') ?: 'counter_pass';

// データベースに接続
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// 接続エラーの確認
if ($conn->connect_error) {
    die("接続エラー: " . $conn->connect_error);
}

// カウントアップ処理
if (isset($_POST['increment'])) {
    $conn->query("UPDATE counter SET count_value = count_value + 1 WHERE id = 1");
}

// リセット処理
if (isset($_POST['reset'])) {
    $conn->query("UPDATE counter SET count_value = 0 WHERE id = 1");
}

// 現在のカウント値を取得
$result = $conn->query("SELECT count_value, updated_at FROM counter WHERE id = 1");
$row = $result->fetch_assoc();
$count = $row['count_value'];
$updated_at = $row['updated_at'];

$conn->close();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Counter App - データの永続化</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            padding: 20px;
        }
        
        .container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            text-align: center;
        }
        
        h1 {
            font-size: 2.5em;
            margin-bottom: 30px;
        }
        
        .counter {
            font-size: 5em;
            font-weight: bold;
            margin: 30px 0;
            padding: 30px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            animation: pulse 2s infinite;
        }
        
        .buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin: 30px 0;
        }
        
        button {
            padding: 15px 30px;
            font-size: 1.2em;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: bold;
        }
        
        .btn-increment {
            background: #4CAF50;
            color: white;
        }
        
        .btn-increment:hover {
            background: #45a049;
            transform: scale(1.05);
        }
        
        .btn-reset {
            background: #f44336;
            color: white;
        }
        
        .btn-reset:hover {
            background: #da190b;
            transform: scale(1.05);
        }
        
        .info-box {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            text-align: left;
        }
        
        .info-box h2 {
            margin-bottom: 15px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.3);
            padding-bottom: 10px;
        }
        
        .info-item {
            margin: 10px 0;
        }
        
        .command {
            background: rgba(0, 0, 0, 0.3);
            padding: 10px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            margin: 10px 0;
            font-size: 0.9em;
        }
        
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔢 Counter App</h1>
        <div class="counter"><?php echo $count; ?></div>
        
        <form method="POST" style="display: inline;">
            <div class="buttons">
                <button type="submit" name="increment" class="btn-increment">
                    ➕ カウントアップ
                </button>
                <button type="submit" name="reset" class="btn-reset">
                    🔄 リセット
                </button>
            </div>
        </form>
        
        <div class="info-box">
            <h2>💡 学習ポイント</h2>
            <div class="info-item">
                <strong>データの保存場所:</strong> MySQLデータベース（ボリュームに保存）
            </div>
            <div class="info-item">
                <strong>最終更新:</strong> <?php echo $updated_at; ?>
            </div>
            <div class="info-item">
                <strong>確認コマンド:</strong>
                <div class="command">
                    docker exec -it counter-app-db mysql -u root -prootpass counter_db -e "SELECT * FROM counter;"
                </div>
            </div>
        </div>
        
        <div class="info-box">
            <h2>🔍 実験してみよう</h2>
            <ol style="margin-left: 20px; line-height: 1.8;">
                <li>カウンターをいくつか増やしてみる</li>
                <li><code>docker-compose down</code>でコンテナを削除</li>
                <li><code>docker-compose up -d</code>で再作成</li>
                <li>カウンターの値が残っていることを確認！</li>
            </ol>
        </div>
    </div>
</body>
</html>

