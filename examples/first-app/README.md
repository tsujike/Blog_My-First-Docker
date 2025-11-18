# 最初のアプリ - HTMLファイル1つで始める

このサンプルは、**「アプリが動く仕組み」を読んだ後に、実際にアプリを作成して動かしてみる**ための最もシンプルな例です。

## なぜHTMLファイルなのか？

### 最もミニマム

- **ファイル1つ**：`index.html`を作るだけ
- **シンプル**：HTMLの基本だけでOK
- **視覚的**：ブラウザで確認できる
- **理解しやすい**：何が起きているかが明確

### Dockerで動かすメリット

- **環境に依存しない**：Mac、Windows、Linuxで同じように動く
- **環境ごとアプリを運ぶ**：HTMLファイルとWebサーバー（nginx）が一緒にパッケージ化される
- **視覚的に確認できる**：ブラウザで動作を確認できる

## このサンプルで学ぶこと

1. **アプリを作成する**：HTMLファイルを1つ作る
2. **Dockerで動かす**：nginxコンテナでHTMLファイルを配信
3. **環境の違いを体験する**：Mac、Windows、Linuxで同じように動くことを確認
4. **アプリが動く仕組みを理解する**：HTMLファイル → Webサーバー → ブラウザの流れを確認

## ファイル構成

```
first-app/
├── README.md          # このファイル
├── docker-compose.yml # Docker Composeの設定
├── index.html         # HTMLファイル（アプリ本体）
├── Dockerfile         # イメージを作成するためのファイル
├── start.bat          # Windows用起動スクリプト
├── start.sh           # Mac/Linux用起動スクリプト
├── WHAT_HAPPENS.md         # docker-compose up -dで何が起きているか
├── WHY_THIS_MATTERS.md     # 「だから何だ？」に答える詳細説明
├── IS_THIS_VIRTUAL_SERVER.md # 仮想サーバーとコンテナの違い
├── WHERE_ARE_IMAGES.md     # イメージはどこにある？コンテナとの関係
├── SEE_CONTAINER.md        # コンテナは「見える」！実際に確認する方法
└── WHAT_IS_COMMAND.md     # 「コマンド」とは何か？Pythonの例で理解する
```

## 起動方法

### 方法1：起動スクリプトを使う（おすすめ）

**Windows：**
```bash
# start.batをダブルクリック
# または、コマンドラインから
start.bat
```

**Mac/Linux：**
```bash
chmod +x start.sh
./start.sh
```

### 方法2：手動で起動

```bash
# first-appディレクトリに移動
cd examples/first-app

# Docker Composeで起動
docker-compose up -d
```

### 方法3：プロジェクトルートから起動

```bash
# プロジェクトルートから実行
docker-compose -f examples/first-app/docker-compose.yml up -d
```

## 手順

### クイックスタート（Windows）

**最も簡単な方法：**

1. `start.bat`をダブルクリック
2. ブラウザで `http://localhost:8082` にアクセス

**注意：** ポート8080が既に使用されている場合は、8082に変更しています。

**または、コマンドラインから：**

```bash
cd examples/first-app
docker-compose up -d
```

### ステップ1：HTMLファイルを作成する

`index.html`を開いてみましょう。

```html
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>最初のアプリ</title>
</head>
<body>
    <h1>Hello World!</h1>
    <p>これは私が作った最初のアプリです。</p>
</body>
</html>
```

**確認ポイント：**
- これは**アプリケーション**です
- HTMLファイルだけで動きます
- ブラウザで開けば表示されます

### ステップ2：Dockerで動かす準備

**必要なもの：**
- HTMLファイル（アプリ）
- Webサーバー（nginx）
- Docker（環境）

**Dockerの役割：**
- HTMLファイルとWebサーバーを一緒にパッケージ化
- 環境に依存しない形で動かす

### ステップ3：Docker Composeで起動

**重要：`first-app`ディレクトリに移動してから実行してください**

#### 方法1：バックグラウンドで起動（通常）

```bash
# first-appディレクトリに移動
cd examples/first-app

# Docker Composeで起動（バックグラウンド）
docker-compose up -d
```

**特徴：**
- バックグラウンドで実行される
- ログが表示されない
- すぐにコマンドプロンプトに戻る

#### 方法2：ログを見ながら起動（おすすめ！）

```bash
# first-appディレクトリに移動
cd examples/first-app

# Docker Composeで起動（ログを表示）
docker-compose up
```

**特徴：**
- ログが表示される
- 何が起きているか見える
- **「ダウンロード」「起動」が視覚的に確認できる**

**停止する：**
- `Ctrl + C`を押す

**その後、バックグラウンドで実行：**
```bash
docker-compose up -d
```

#### 方法3：プロジェクトルートから実行

```bash
# プロジェクトルートから実行
docker-compose -f examples/first-app/docker-compose.yml up -d
```

**何が起こるか：**

#### ステップ1：イメージのビルド（build）
- `Dockerfile`を読み込む
- `nginx:alpine`イメージをダウンロード（初回のみ）
- イメージをビルドする

**確認方法：**
```bash
# ビルドの過程を見る
docker-compose build

# イメージが作成されたか確認
docker images | grep first-app
```

#### ステップ2：ネットワークの作成
- Dockerネットワークを作成する
- コンテナ間の通信を可能にする

**確認方法：**
```bash
docker network ls | grep first-app
```

#### ステップ3：コンテナの作成
- イメージからコンテナを作成する
- ポートマッピングを設定する
- ボリュームをマウントする

**確認方法：**
```bash
docker ps -a | grep first-app
```

#### ステップ4：コンテナの起動
- コンテナを起動する
- nginxサーバーが起動する

**確認方法：**
```bash
docker ps | grep first-app
# STATUS: Up と表示されれば起動成功
```

**詳しくは：[WHAT_HAPPENS.md](./WHAT_HAPPENS.md)を読んでください**

**エラーが出た場合：**
- `no configuration file provided: not found` → `first-app`ディレクトリに移動してから実行してください
- `cd examples/first-app`を実行してから、`docker-compose up -d`を実行してください

### ステップ4：ブラウザで確認する

ブラウザで以下のURLにアクセス：
```
http://localhost:8082
```

**注意：** ポート8080が既に使用されている場合は、`docker-compose.yml`で8082に変更しています。

**確認ポイント：**
- HTMLファイルが表示される
- **これはアプリが動いている状態です**
- Webサーバー（nginx）がHTMLファイルを配信している

**「だから何だ？」という疑問に答える：**

このページが表示されているということは：

1. **アプリが動いている**：HTMLファイルが実際に動作している
2. **Webサーバーが動いている**：nginxがコンテナ内で動いている
3. **OSが動いている**：Linuxがコンテナ内で動いている
4. **環境ごとアプリを運んでいる**：アプリと環境が一緒にパッケージ化されている
5. **環境に依存しない**：Mac、Windows、Linuxで同じように動く

**従来の問題が解決されている：**
- 「Macで動いたのに、Windowsで動かない」→ **解決！**（環境ごとアプリを運ぶから）
- 「Webサーバーをインストールする必要がある」→ **不要！**（コンテナ内にあるから）
- 「環境構築に時間がかかる」→ **一瞬！**（`docker-compose up -d`だけでOK）

これがDockerの価値です！

### ステップ5：ファイルを編集してみる

`index.html`を編集してみましょう。

```html
<h1>Hello World! 編集しました！</h1>
<p>これは私が作った最初のアプリです。</p>
<p>Dockerで動いています！</p>
```

**確認ポイント：**
- ファイルを保存する
- ブラウザをリロード（F5キー）
- 変更が反映される

### ステップ6：アプリが動く仕組みを確認する

**アプリが動くために必要なもの：**

1. **アプリケーション**：`index.html`（HTMLファイル）
2. **Webサーバー**：nginx（コンテナ内で動いている）
3. **OS**：Linux（コンテナ内）
4. **ランタイム**：nginx（Webサーバーソフトウェア）

**確認コマンド：**
```bash
# コンテナが動いているか確認
docker ps

# コンテナ内のファイルを確認
docker exec first-app-web ls /usr/share/nginx/html

# コンテナのログを確認
docker logs first-app-web
```

### ステップ7：環境の違いを体験する

**Mac、Windows、Linuxで同じように動くことを確認：**

1. **Macで作成** → Windowsでも動く
2. **Windowsで作成** → Linuxでも動く
3. **Linuxで作成** → Macでも動く

**ポイント：**
- HTMLファイルは同じ
- Dockerコンテナも同じ
- **環境に依存しない**

## アプリが動く仕組み（この例の場合）

```
ブラウザ
    ↓ HTTPリクエスト
Webサーバー（nginx）
    ↓
HTMLファイル（index.html）
    ↓
ブラウザに表示
```

**必要なもの：**
1. **HTMLファイル**（アプリ）
2. **Webサーバー**（nginx）
3. **OS**（Linux - コンテナ内）
4. **Docker**（環境に依存しない実行）

## 次のステップ

このシンプルな例で、アプリが動く仕組みを理解したら：

1. **Docker用語解説**：Repository、Image、Container、Volumesを学ぶ
2. **より複雑なアプリ**：JavaScriptを追加してみる
3. **データベースを使うアプリ**：[counter-app](../visual-learning/counter-app/)を試す
4. **WordPress**：より実践的なアプリを動かす

## トラブルシューティング

### エラー：ポート8080が既に使用されている

**エラーメッセージ：**
```
Error: Bind for 0.0.0.0:8080 failed: port is already allocated
```

**原因：**
- 他のコンテナやアプリケーションが既にポート8080を使用している

**解決方法1：既存のコンテナを確認・停止**

```bash
# 実行中のコンテナを確認
docker ps

# ポート8080を使用しているコンテナを停止
docker stop <コンテナ名>

# または、全てのコンテナを停止
docker stop $(docker ps -q)
```

**解決方法2：ポート番号を変更する**

`docker-compose.yml`のポート番号を変更：

```yaml
ports:
  - "8082:80"  # 8082番ポートに変更
```

変更後、再度起動：
```bash
docker-compose up -d
```

ブラウザで `http://localhost:8082` にアクセスしてください。

**解決方法3：既存のコンテナを削除**

```bash
# ポート8080を使用しているコンテナを削除
docker rm -f <コンテナ名>

# または、全てのコンテナを削除（注意：他のコンテナも削除されます）
docker rm -f $(docker ps -aq)
```

### エラー：docker-compose.ymlが見つからない

**エラーメッセージ：**
```
no configuration file provided: not found
```

**解決方法：**
- `first-app`ディレクトリに移動してから実行してください
- `cd examples/first-app`を実行してから、`docker-compose up -d`を実行してください

### エラー：Docker Desktopが起動していない

**エラーメッセージ：**
```
Cannot connect to the Docker daemon
```

**解決方法：**
- Docker Desktopを起動してください
- Docker Desktopが完全に起動するまで待ってから、再度実行してください

## 「だから何だ？」- このサンプルで学べること

WEBページが起動したけど、**だから何だ？**という疑問に答えます。

### このページが表示されているということは

1. **アプリが実際に動いている**
   - HTMLファイル（アプリ）がコンテナ内で動いている
   - Webサーバー（nginx）がコンテナ内で動いている
   - Linux（OS）がコンテナ内で動いている

2. **環境ごとアプリを運んでいる**
   - あなたのPCはWindowsでも、アプリはLinux上で動いている
   - Macでも、Linuxでも、同じように動く
   - これが「環境ごとアプリを運ぶ」ということ

3. **従来の問題が解決されている**
   - 「Macで動いたのに、Windowsで動かない」→ **解決！**
   - 「Webサーバーをインストールする必要がある」→ **不要！**
   - 「環境構築に時間がかかる」→ **一瞬！**

4. **実際に確認できる**
   - コンテナが動いている → `docker ps`で確認
   - ファイルがコンテナ内にある → `docker exec`で確認
   - 環境がLinux → コンテナ内はLinux

**詳しくは：[WHY_THIS_MATTERS.md](./WHY_THIS_MATTERS.md)を読んでください**

## まとめ

- **最もミニマムなアプリ**：HTMLファイル1つ
- **Dockerで動かす**：環境に依存しない
- **視覚的に確認できる**：ブラウザで動作を確認
- **アプリが動く仕組みを理解**：HTMLファイル → Webサーバー → ブラウザ
- **「だから何だ？」に答える**：環境ごとアプリを運ぶDockerの力を実感できる

HTMLファイル1つから始めることで、アプリが動く仕組みを理解し、Dockerの必要性を実感できます！

