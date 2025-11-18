@echo off
REM first-appを起動するスクリプト（Windows用）

echo 🚀 first-appを起動します...
echo.

REM スクリプトのディレクトリに移動
cd /d "%~dp0"

echo 📁 ディレクトリ: %CD%
echo.

REM docker-compose.ymlが存在するか確認
if not exist "docker-compose.yml" (
    echo ❌ エラー: docker-compose.ymlが見つかりません
    pause
    exit /b 1
)

REM Docker Composeで起動
echo 🐳 Docker Composeで起動中...
docker-compose up -d

if %errorlevel% equ 0 (
    echo.
    echo ✅ 起動成功！
    echo.
    echo 🌐 ブラウザで以下のURLにアクセスしてください：
    echo    http://localhost:8082
    echo.
    echo 📋 確認コマンド：
    echo    docker-compose ps    # コンテナの状態を確認
    echo    docker-compose logs  # ログを確認
    echo    docker-compose stop  # 停止
    echo    docker-compose down  # 停止＋削除
) else (
    echo.
    echo ❌ 起動に失敗しました
    echo    Docker Desktopが起動しているか確認してください
)

pause

