# 【Docker入門】基本コマンドを覚えよう！実践編

どうも、ケニー（tsujikenzo）です。前回はDockerの基礎概念について学びました。今回は、**実際に使うDockerの基本コマンド**を覚えていきましょう。

## この記事で学ぶこと

- Dockerの基本コマンド
- イメージの取得と確認
- コンテナの起動・停止・削除
- コンテナの状態確認

## よく使うDockerコマンド一覧

### 1. イメージ関連のコマンド

#### イメージの検索
```bash
docker search キーワード
```

例：WordPressのイメージを検索
```bash
docker search wordpress
```

#### イメージの取得（ダウンロード）
```bash
docker pull イメージ名:タグ
```

例：最新のUbuntuイメージを取得
```bash
docker pull ubuntu:latest
```

#### イメージの一覧表示
```bash
docker images
```

または

```bash
docker image ls
```

#### イメージの削除
```bash
docker rmi イメージ名
```

または

```bash
docker image rm イメージ名
```

### 2. コンテナ関連のコマンド

#### コンテナの起動（新規作成＋起動）
```bash
docker run オプション イメージ名 コマンド
```

**よく使うオプション：**
- `-d` : バックグラウンドで実行（デタッチモード）
- `-p ホストポート:コンテナポート` : ポートのマッピング
- `--name 名前` : コンテナに名前を付ける
- `-it` : インタラクティブモード（ターミナルで操作）

**例：**
```bash
# バックグラウンドでnginxを起動、ポート80をマッピング
docker run -d -p 80:80 --name my-nginx nginx

# インタラクティブモードでUbuntuを起動
docker run -it ubuntu /bin/bash
```

#### コンテナの一覧表示
```bash
docker ps
```

実行中のコンテナのみ表示

```bash
docker ps -a
```

停止中のコンテナも含めて全て表示

#### コンテナの停止
```bash
docker stop コンテナ名またはID
```

#### コンテナの再開
```bash
docker start コンテナ名またはID
```

#### コンテナの削除
```bash
docker rm コンテナ名またはID
```

停止中のコンテナを削除

```bash
docker rm -f コンテナ名またはID
```

実行中のコンテナを強制削除

#### コンテナのログ確認
```bash
docker logs コンテナ名またはID
```

リアルタイムでログを確認
```bash
docker logs -f コンテナ名またはID
```

#### コンテナ内でコマンドを実行
```bash
docker exec -it コンテナ名またはID コマンド
```

例：コンテナ内でbashを起動
```bash
docker exec -it my-nginx /bin/bash
```

### 3. その他の便利なコマンド

#### Dockerのバージョン確認
```bash
docker --version
```

#### Dockerの情報確認
```bash
docker info
```

#### 使用していないリソースの削除
```bash
docker system prune
```

停止中のコンテナ、未使用のイメージ、ネットワークを削除

## 実践：簡単なWebサーバーを動かしてみよう

### ステップ1：nginxイメージを取得

```bash
docker pull nginx
```

### ステップ2：nginxコンテナを起動

```bash
docker run -d -p 8080:80 --name my-web-server nginx
```

- `-d` : バックグラウンドで実行
- `-p 8080:80` : ホストの8080番ポートをコンテナの80番ポートにマッピング
- `--name my-web-server` : コンテナに名前を付ける
- `nginx` : 使用するイメージ

### ステップ3：動作確認

ブラウザで以下のURLにアクセス：
```
http://localhost:8080
```

nginxのデフォルトページが表示されれば成功です！

### ステップ4：コンテナの状態確認

```bash
docker ps
```

実行中のコンテナが表示されます。

### ステップ5：コンテナの停止

```bash
docker stop my-web-server
```

### ステップ6：コンテナの削除

```bash
docker rm my-web-server
```

## よくあるエラーと対処法

### エラー：ポートが既に使用されている

```
Error response from daemon: driver failed programming external connectivity
```

**対処法：** 別のポート番号を使用するか、既存のコンテナを停止・削除する

```bash
docker ps
docker stop コンテナ名
docker rm コンテナ名
```

### エラー：イメージが見つからない

```
Unable to find image 'xxx:latest' locally
```

**対処法：** 自動的にダウンロードされますが、明示的にpullすることもできます

```bash
docker pull イメージ名
```

## コマンドの覚え方のコツ

1. **よく使うコマンドから覚える**
   - `docker ps`（コンテナ一覧）
   - `docker run`（コンテナ起動）
   - `docker stop`（コンテナ停止）
   - `docker rm`（コンテナ削除）

2. **オプションは必要に応じて覚える**
   - `-d`（バックグラウンド実行）
   - `-p`（ポートマッピング）
   - `--name`（名前付け）

3. **実際に手を動かしながら覚える**
   - コマンドを実行してみる
   - エラーが出たら調べる
   - 繰り返し使う

## まとめ

- `docker pull` : イメージを取得
- `docker run` : コンテナを起動
- `docker ps` : コンテナの一覧を表示
- `docker stop` : コンテナを停止
- `docker rm` : コンテナを削除
- `docker logs` : ログを確認

次回は、実際にWordPressやMySQLをDockerで動かしてみましょう！

---

**練習問題**
1. nginxコンテナを起動して、ブラウザでアクセスしてみましょう
2. コンテナのログを確認してみましょう
3. コンテナを停止・削除してみましょう

