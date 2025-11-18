# docker-compose up -d で何が起きているのか？

`docker-compose up -d`を実行すると、たくさんのメッセージが表示されますが、**何が起きているのか**を理解することが重要です。

## docker-compose up -d を実行すると

### ステップ1：イメージのビルド（build）

**何が起きているか：**
- `Dockerfile`を読み込む
- `nginx:alpine`イメージをダウンロード（初回のみ）
- イメージをビルドする

**確認方法：**
```bash
# ビルドの過程を見る（-dオプションなし）
docker-compose up

# または、ビルドだけ実行
docker-compose build
```

**出力例：**
```
[+] Building 2.5s (6/6) FINISHED
 => [internal] load build definition from Dockerfile
 => => transferring dockerfile: 32B
 => [internal] load .dockerignore
 => => transferring context: 2B
 => [internal] load metadata for docker.io/library/nginx:alpine
 => [1/2] FROM docker.io/library/nginx:alpine
 => [2/2] RUN rm /usr/share/nginx/html/index.html
 => => exporting to image
```

**何が起きているか：**
1. `Dockerfile`を読み込む
2. `nginx:alpine`イメージをダウンロード（初回のみ）
3. `RUN rm /usr/share/nginx/html/index.html`を実行
4. 新しいイメージを作成

**確認：**
```bash
# イメージが作成されたか確認
docker images | grep first-app
```

### ステップ2：ネットワークの作成

**何が起きているか：**
- Dockerネットワークを作成する
- コンテナ間の通信を可能にする

**確認方法：**
```bash
# ネットワークを確認
docker network ls | grep first-app
```

**出力例：**
```
NETWORK ID     NAME                DRIVER    SCOPE
abc123def456   first-app_default   bridge    local
```

**何が起きているか：**
- `first-app_default`というネットワークが作成される
- このネットワーク上でコンテナが通信できる

### ステップ3：コンテナの作成

**何が起きているか：**
- イメージからコンテナを作成する
- ポートマッピングを設定する
- ボリュームをマウントする

**確認方法：**
```bash
# コンテナが作成されたか確認（停止中も含む）
docker ps -a | grep first-app
```

**出力例：**
```
CONTAINER ID   IMAGE           COMMAND                  CREATED         STATUS         PORTS     NAMES
3ebf371bebab   first-app-web   "/docker-entrypoint.…"   2 minutes ago   Created                  first-app-web
```

**何が起きているか：**
- コンテナが作成される（STATUS: Created）
- まだ起動していない

### ステップ4：コンテナの起動

**何が起きているか：**
- コンテナを起動する
- nginxサーバーが起動する

**確認方法：**
```bash
# コンテナが起動したか確認
docker ps | grep first-app
```

**出力例：**
```
CONTAINER ID   IMAGE           COMMAND                  CREATED         STATUS         PORTS                                     NAMES
3ebf371bebab   first-app-web   "/docker-entrypoint.…"   2 minutes ago   Up 2 minutes   0.0.0.0:8082->80/tcp                   first-app-web
```

**何が起きているか：**
- コンテナが起動する（STATUS: Up）
- nginxサーバーが起動する
- ポート8082でアクセス可能になる

## 段階的に確認する方法

### 方法1：-dオプションなしで実行（推奨）

**通常：**
```bash
docker-compose up -d
```

**詳細を見る：**
```bash
docker-compose up
```

**違い：**
- `-d`なし：ログが表示される（何が起きているか見える）
- `-d`あり：バックグラウンドで実行（ログが表示されない）

**出力例：**
```
[+] Building 2.5s (6/6) FINISHED
 => [internal] load build definition from Dockerfile
 => [internal] load .dockerignore
 => [internal] load metadata for docker.io/library/nginx:alpine
 => [1/2] FROM docker.io/library/nginx:alpine
 => [2/2] RUN rm /usr/share/nginx/html/index.html
 => => exporting to image
[+] Running 2/2
 ✔ Network first-app_default  Created
 ✔ Container first-app-web    Created
 ✔ Container first-app-web    Started
```

**何が起きているか：**
1. イメージをビルド（Building）
2. ネットワークを作成（Network Created）
3. コンテナを作成（Container Created）
4. コンテナを起動（Container Started）

### 方法2：各ステップを個別に実行

#### ステップ1：イメージをビルド

```bash
docker-compose build
```

**何が起きているか：**
- Dockerfileを読み込む
- イメージをビルドする
- イメージが作成される

**確認：**
```bash
docker images | grep first-app
```

#### ステップ2：コンテナを作成（起動しない）

```bash
docker-compose create
```

**何が起きているか：**
- コンテナを作成する
- まだ起動していない

**確認：**
```bash
docker ps -a | grep first-app
# STATUS: Created と表示される
```

#### ステップ3：コンテナを起動

```bash
docker-compose start
```

**何が起きているか：**
- コンテナを起動する
- nginxサーバーが起動する

**確認：**
```bash
docker ps | grep first-app
# STATUS: Up と表示される
```

## 視覚的に確認する方法

### 1. イメージがダウンロードされているか確認

```bash
# イメージ一覧を確認
docker images
```

**出力例：**
```
REPOSITORY      TAG       IMAGE ID       CREATED         SIZE
first-app_web   latest    abc123def456   2 minutes ago   133MB
nginx           alpine    def456ghi789   2 weeks ago     40MB
```

**確認ポイント：**
- `nginx:alpine`がダウンロードされている
- `first-app_web:latest`が作成されている

### 2. コンテナが作成されているか確認

```bash
# コンテナ一覧を確認（停止中も含む）
docker ps -a
```

**確認ポイント：**
- `first-app-web`が表示される
- STATUSが`Up`なら起動中、`Created`なら作成済みだが未起動

### 3. ネットワークが作成されているか確認

```bash
# ネットワーク一覧を確認
docker network ls
```

**確認ポイント：**
- `first-app_default`が表示される

### 4. コンテナ内で何が動いているか確認

```bash
# コンテナ内のプロセスを確認
docker exec first-app-web ps aux
```

**出力例：**
```
PID   USER     TIME   COMMAND
1     root     0:00   nginx: master process nginx -g daemon off;
6     nginx    0:00   nginx: worker process
```

**確認ポイント：**
- nginxプロセスが動いている
- これが「起動している」状態

## 実際に試してみる

### 実験1：ビルドの過程を見る

```bash
cd examples/first-app

# 既存のコンテナを削除
docker-compose down

# ビルドの過程を見る（-dオプションなし）
docker-compose build
```

**確認ポイント：**
- `nginx:alpine`がダウンロードされる（初回のみ）
- イメージがビルドされる
- 各ステップのログが表示される

### 実験2：起動の過程を見る

```bash
# 起動の過程を見る（-dオプションなし）
docker-compose up
```

**確認ポイント：**
- ネットワークが作成される
- コンテナが作成される
- コンテナが起動する
- nginxのログが表示される

**停止する：**
- `Ctrl + C`を押す

### 実験3：段階的に確認する

```bash
# ステップ1：イメージをビルド
docker-compose build

# ステップ2：イメージを確認
docker images | grep first-app

# ステップ3：コンテナを作成（起動しない）
docker-compose create

# ステップ4：コンテナを確認（まだ起動していない）
docker ps -a | grep first-app
# STATUS: Created と表示される

# ステップ5：コンテナを起動
docker-compose start

# ステップ6：コンテナを確認（起動している）
docker ps | grep first-app
# STATUS: Up と表示される
```

## よくある質問

### Q1: 「ダウンロード」とは何をダウンロードしているの？

**A:** `nginx:alpine`イメージをダウンロードしています。

**確認方法：**
```bash
# nginxイメージがダウンロードされているか確認
docker images | grep nginx
```

**初回実行時：**
- Docker Hubから`nginx:alpine`イメージをダウンロード
- ローカルに保存される

**2回目以降：**
- 既にダウンロード済みなので、ダウンロードしない
- すぐにビルドが始まる

### Q2: 「起動」とは何が起動しているの？

**A:** nginxサーバーが起動しています。

**確認方法：**
```bash
# コンテナ内のプロセスを確認
docker exec first-app-web ps aux
```

**何が起動しているか：**
- nginxプロセス（Webサーバー）
- これがHTMLファイルを配信している

### Q3: 「ビルド」とは何をビルドしているの？

**A:** Dockerfileからイメージをビルドしています。

**確認方法：**
```bash
# ビルドの過程を見る
docker-compose build --progress=plain
```

**何が起きているか：**
1. `Dockerfile`を読み込む
2. `FROM nginx:alpine`でベースイメージを取得
3. `RUN rm /usr/share/nginx/html/index.html`を実行
4. 新しいイメージを作成

### Q4: なぜ時間がかかるの？

**A:** 初回実行時は、イメージのダウンロードに時間がかかります。

**時間がかかる理由：**
- `nginx:alpine`イメージをダウンロード（初回のみ、約40MB）
- イメージをビルド
- コンテナを作成・起動

**2回目以降：**
- イメージは既にダウンロード済み
- ビルドもキャッシュが使われる
- 数秒で完了

## まとめ

`docker-compose up -d`を実行すると：

1. **イメージのビルド**：Dockerfileからイメージを作成
2. **ネットワークの作成**：コンテナ間の通信を可能にする
3. **コンテナの作成**：イメージからコンテナを作成
4. **コンテナの起動**：nginxサーバーを起動

**視覚的に確認する方法：**
- `docker-compose up`（-dオプションなし）でログを見る
- `docker ps`でコンテナの状態を確認
- `docker images`でイメージを確認
- `docker exec`でコンテナ内を確認

**「ダウンロード」とは：**
- `nginx:alpine`イメージをDocker Hubからダウンロード（初回のみ）

**「起動」とは：**
- nginxサーバーがコンテナ内で起動する
- HTMLファイルを配信できるようになる

これらを理解することで、`docker-compose up -d`で何が起きているのかが明確になります！

---

**次のステップ**
- [docker psの見方](../../10_docker_psの見方.md)で、コンテナの状態を確認する方法を学びましょう
- [Docker基本コマンド](../../02_Docker基本コマンド.md)で、他のコマンドも学びましょう

