#!/bin/bash
# first-appを起動するスクリプト

echo "🚀 first-appを起動します..."
echo ""

# 現在のディレクトリを確認
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd "$SCRIPT_DIR"

echo "📁 ディレクトリ: $SCRIPT_DIR"
echo ""

# docker-compose.ymlが存在するか確認
if [ ! -f "docker-compose.yml" ]; then
    echo "❌ エラー: docker-compose.ymlが見つかりません"
    exit 1
fi

# Docker Composeで起動
echo "🐳 Docker Composeで起動中..."
docker-compose up -d

if [ $? -eq 0 ]; then
    echo ""
    echo "✅ 起動成功！"
    echo ""
    echo "🌐 ブラウザで以下のURLにアクセスしてください："
    echo "   http://localhost:8082"
    echo ""
    echo "📋 確認コマンド："
    echo "   docker-compose ps    # コンテナの状態を確認"
    echo "   docker-compose logs  # ログを確認"
    echo "   docker-compose stop  # 停止"
    echo "   docker-compose down  # 停止＋削除"
else
    echo ""
    echo "❌ 起動に失敗しました"
    echo "   Docker Desktopが起動しているか確認してください"
fi

