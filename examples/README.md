# 実践例

このフォルダには、記事で紹介した実践例のサンプルファイルが含まれています。

## 🎯 最初のアプリ（最もミニマム！）

**「アプリが動く仕組み」を読んだ後に、実際にアプリを作成して動かしてみる**

- **[first-app/](./first-app/)** ⭐⭐⭐ - HTMLファイル1つで始める最初のアプリ
  - **最もシンプル**：HTMLファイル1つだけ
  - **視覚的**：ブラウザで確認できる
  - **理解しやすい**：アプリが動く仕組みを実体験

**「アプリが動く仕組み」を読んだら、まずはこちらから始めましょう！**

## 🎯 視覚的に学ぶサンプル（おすすめ！）

**Dockerが目に見えないことを解決するための実践サンプル**

- **[visual-learning/](./visual-learning/)** ⭐ - Dockerを視覚的に理解するためのサンプル集
  - **hello-visual/** - 最も簡単な例。コンテナの動作を視覚的に確認
  - **counter-app/** - データの永続化を視覚的に確認。ボリュームの動作を理解

**first-appを試した後、こちらも試してみましょう！**

## フォルダ構成

### wordpress/（基本版）
WordPress環境を構築するためのdocker-compose.ymlファイルです。

**使い方：**
```bash
cd wordpress
docker-compose up -d
```

ブラウザで `http://localhost:8080` にアクセスしてください。

### wordpress-complete/（完全版）⭐
WordPress + MySQL + phpMyAdminの完全な環境です。
ヘルスチェック、ネットワーク設定など、実践的な設定が含まれています。

**使い方：**
```bash
cd wordpress-complete
docker-compose up -d
```

- WordPress: `http://localhost:8080`
- phpMyAdmin: `http://localhost:8081`

### mysql/（基本版）
MySQL学習環境を構築するためのdocker-compose.ymlファイルです。
phpMyAdminも含まれています。

**使い方：**
```bash
cd mysql
docker-compose up -d
```

- MySQL: `localhost:3306`
- phpMyAdmin: `http://localhost:8080`

### mysql-complete/（完全版）⭐
MySQL + phpMyAdmin + Adminerの完全な環境です。
初期SQLファイル、ヘルスチェックなどが含まれています。

**使い方：**
```bash
cd mysql-complete
docker-compose up -d
```

- MySQL: `localhost:3306`
- phpMyAdmin: `http://localhost:8080`
- Adminer: `http://localhost:8082`

### hello-docker/
簡単なHTMLファイルを使ったnginxベースのDockerイメージの例です。

**使い方：**
```bash
cd hello-docker
docker build -t hello-docker .
docker run -d -p 8080:80 --name my-hello hello-docker
```

ブラウザで `http://localhost:8080` にアクセスしてください。

### python-app/
FlaskアプリケーションをDocker化した例です。

**使い方：**
```bash
cd python-app
docker build -t python-docker .
docker run -d -p 5000:5000 --name my-python-app python-docker
```

ブラウザで `http://localhost:5000` にアクセスしてください。

## おすすめの学習順序

### 初心者向け（推奨順序）

1. **first-app/** ⭐⭐⭐ - HTMLファイル1つで始める最初のアプリ（最もミニマム！）
2. **visual-learning/hello-visual/** ⭐ - コンテナの動作を視覚的に確認
3. **visual-learning/counter-app/** - データの永続化を視覚的に確認
4. **wordpress/** または **mysql/** で基本を理解

### 実践重視

1. **wordpress-complete/** または **mysql-complete/** で実践的な設定を学ぶ
2. **Docker Compose完全ガイド**の記事を読んで、設定の意味を理解する
3. 自分でカスタマイズしてみる

## 注意事項

- 各例は独立して動作します
- ポート番号が競合する場合は、docker-compose.ymlやdocker runコマンドのポート番号を変更してください
- コンテナを停止する場合は、`docker-compose down` または `docker stop コンテナ名` を使用してください
- **完全版**のサンプルは、実践的な設定が含まれているため、学習に最適です

