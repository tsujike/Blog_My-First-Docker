# 【Docker実践】docker psの見方 - コンテナの状態を理解する

どうも、ケニー（tsujikenzo）です。`docker ps`コマンドを実行すると、たくさんの情報が表示されますが、**これらは何を意味するのか**を理解することが重要です。

## docker psとは？

`docker ps`は、**実行中のコンテナの一覧を表示する**コマンドです。

**実行例：**
```bash
docker ps
```

**出力例：**
```
CONTAINER ID   IMAGE                   COMMAND                   CREATED         STATUS         PORTS                                     NAMES                 
3ebf371bebab   first-app-web           "/docker-entrypoint.…"   2 minutes ago   Up 2 minutes   0.0.0.0:8082->80/tcp, [::]:8082->80/tcp   first-app-web          
649d5843d124   wordpress:latest        "docker-entrypoint.s…"   6 hours ago     Up 6 hours     0.0.0.0:8080->80/tcp, [::]:8080->80/tcp   app_shunnhokkaido-wordpress2025-wordpress-1
```

## 各項目の意味

### 1. CONTAINER ID（コンテナID）

**意味：** コンテナの**一意の識別子**（ID）

**例：** `3ebf371bebab`

**特徴：**
- コンテナごとに異なるIDが割り当てられる
- 短縮形（最初の12文字）が表示される
- 完全なIDは64文字（16進数）

**使い方：**
```bash
# コンテナIDを使って操作
docker stop 3ebf371bebab
docker logs 3ebf371bebab
docker exec 3ebf371bebab ls
```

**重要ポイント：**
- コンテナIDは、コンテナを操作する際の識別子として使える
- コンテナ名（NAMES）でも操作できるが、IDも使える

### 2. IMAGE（イメージ）

**意味：** このコンテナが作成された**元のイメージ名**

**例：** `first-app-web`、`wordpress:latest`、`mysql:8.0`

**特徴：**
- **DAEMONが自動で付与した名前ではありません**
- **ユーザーが指定したイメージ名**がそのまま表示されます
- `docker run`コマンドや`docker-compose.yml`で指定したイメージ名が表示されます
- イメージ名の形式：`リポジトリ名:タグ`（タグを省略すると`latest`が使われます）

**重要ポイント：**
- 同じイメージから複数のコンテナを作成できます
- イメージは「設計図」、コンテナは「実際に動いているもの」
- イメージ名は、コンテナを作成するときに使用したイメージを表します

**例：**
```bash
# docker runでイメージを指定
docker run -d nginx:latest
# → IMAGE列に「nginx」と表示される

docker run -d wordpress:5.9
# → IMAGE列に「wordpress」と表示される

docker run -d mysql:8.0
# → IMAGE列に「mysql」と表示される
```

**IMAGE列の見方：**
```
IMAGE: wordpress:latest
→ WordPressの最新版イメージから作成されたコンテナ

IMAGE: first-app-web
→ first-app-webという名前のイメージから作成されたコンテナ
（docker-compose.ymlでbuildしたイメージの場合）
```

**よくある質問：**

**Q: IMAGEはDAEMONが自動で付与した名前ですか？**
A: いいえ、違います。IMAGEは、コンテナを作成するときに**ユーザーが指定したイメージ名**が表示されます。DAEMONが自動で付与するものではありません。

**Q: 同じイメージから複数のコンテナを作成した場合、IMAGE列はどうなりますか？**
A: 同じイメージ名が表示されます。例えば、`nginx:latest`から3つのコンテナを作成した場合、すべてのコンテナのIMAGE列に`nginx`と表示されます。

### 3. COMMAND（コマンド）

**意味：** コンテナ起動時に実行される**コマンド**

**例：** `"/docker-entrypoint.…"`

**特徴：**
- 長いコマンドは`…`で省略表示される
- 完全なコマンドを見るには`docker inspect`を使う

**確認方法：**
```bash
# 完全なコマンドを確認
docker inspect first-app-web | grep -A 5 "Cmd"
```

**重要ポイント：**
- このコマンドがコンテナ内で実行されている
- 通常は、アプリケーションの起動コマンド

**例：**
```
COMMAND: "/docker-entrypoint.sh nginx -g daemon off;"
→ nginxサーバーを起動するコマンド
```

### 4. CREATED（作成日時）

**意味：** コンテナが**作成された日時**

**例：** `2 minutes ago`、`6 hours ago`

**特徴：**
- 相対的な時間で表示される（○分前、○時間前）
- 正確な日時を見るには`docker inspect`を使う

**確認方法：**
```bash
# 正確な作成日時を確認
docker inspect first-app-web | grep Created
```

**重要ポイント：**
- コンテナがいつ作成されたかがわかる
- 古いコンテナを整理する際に役立つ

### 5. STATUS（状態）

**意味：** コンテナの**現在の状態**

**例：** `Up 2 minutes`、`Up 6 hours`

**主な状態：**
- **Up ○○**：実行中（起動してから○○経過）
- **Exited**：停止中
- **Restarting**：再起動中
- **Paused**：一時停止中

**確認方法：**
```bash
# 停止中のコンテナも含めて確認
docker ps -a
```

**重要ポイント：**
- `Up`が表示されていれば、コンテナは動いている
- `Exited`が表示されていれば、コンテナは停止している

**例：**
```
STATUS: Up 2 minutes
→ コンテナは実行中で、2分前に起動した
```

### 6. PORTS（ポート）

**意味：** **ポートマッピング**の情報

**例：** `0.0.0.0:8082->80/tcp`

**読み方：**
```
0.0.0.0:8082 -> 80/tcp
    ↓         ↓    ↓
  ホスト    コンテナ プロトコル
```

**意味：**
- **ホストの8082番ポート** → **コンテナの80番ポート**にマッピング
- `0.0.0.0`は「全てのネットワークインターフェース」を意味する
- `tcp`は通信プロトコル

**具体例：**
```
PORTS: 0.0.0.0:8082->80/tcp
→ ブラウザで http://localhost:8082 にアクセスすると、
  コンテナ内の80番ポートに接続される
```

**ポートが表示されない場合：**
```
PORTS: 3306/tcp, 33060/tcp
→ ポートマッピングが設定されていない（コンテナ内でのみアクセス可能）
```

**重要ポイント：**
- ポートマッピングがないと、ホストからコンテナにアクセスできない
- 複数のポートをマッピングできる

### 7. NAMES（名前）

**意味：** コンテナの**名前**

**例：** `first-app-web`、`wordpress-1`

**特徴：**
- コンテナに付けられた名前
- `--name`オプションで指定した名前、または自動生成された名前

**使い方：**
```bash
# コンテナ名を使って操作
docker stop first-app-web
docker logs first-app-web
docker exec first-app-web ls
```

**重要ポイント：**
- コンテナIDよりも覚えやすい
- コンテナを操作する際に便利

## 実際の出力例で理解する

### first-app-webコンテナの場合

```
CONTAINER ID   IMAGE           COMMAND                  CREATED         STATUS         PORTS                                     NAMES
3ebf371bebab   first-app-web   "/docker-entrypoint.…"   2 minutes ago   Up 2 minutes   0.0.0.0:8082->80/tcp                   first-app-web
```

**読み方：**
- **CONTAINER ID**: `3ebf371bebab` - このコンテナのID
- **IMAGE**: `first-app-web` - first-app-webイメージから作成
- **COMMAND**: `"/docker-entrypoint.…"` - nginxを起動するコマンド
- **CREATED**: `2 minutes ago` - 2分前に作成された
- **STATUS**: `Up 2 minutes` - 実行中（2分間動いている）
- **PORTS**: `0.0.0.0:8082->80/tcp` - ホストの8082番ポートがコンテナの80番ポートにマッピング
- **NAMES**: `first-app-web` - コンテナの名前

**つまり：**
- このコンテナは実行中
- `http://localhost:8082`でアクセスできる
- nginxサーバーが動いている

### wordpressコンテナの場合

```
CONTAINER ID   IMAGE            COMMAND                  CREATED         STATUS         PORTS                                     NAMES
649d5843d124   wordpress:latest "docker-entrypoint.s…"   6 hours ago     Up 6 hours     0.0.0.0:8080->80/tcp                   wordpress-1
```

**読み方：**
- **IMAGE**: `wordpress:latest` - WordPressの最新版イメージから作成
- **PORTS**: `0.0.0.0:8080->80/tcp` - ホストの8080番ポートがコンテナの80番ポートにマッピング
- **STATUS**: `Up 6 hours` - 6時間動いている

**つまり：**
- WordPressが実行中
- `http://localhost:8080`でアクセスできる

### mysqlコンテナの場合

```
CONTAINER ID   IMAGE      COMMAND                  CREATED         STATUS         PORTS                                     NAMES
babdc8dfa003   mysql:8.0  "docker-entrypoint.…"   6 hours ago     Up 6 hours     3306/tcp, 33060/tcp                       db-1
```

**読み方：**
- **PORTS**: `3306/tcp, 33060/tcp` - ポートマッピングがない（コンテナ内でのみアクセス可能）
- **STATUS**: `Up 6 hours` - 6時間動いている

**つまり：**
- MySQLが実行中
- ポートマッピングがないので、他のコンテナからはアクセスできるが、ホストからは直接アクセスできない

## docker psのオプション

### 停止中のコンテナも含めて表示

```bash
docker ps -a
```

**出力例：**
```
CONTAINER ID   IMAGE           COMMAND                  CREATED         STATUS                     PORTS     NAMES
3ebf371bebab   first-app-web   "/docker-entrypoint.…"   2 minutes ago   Up 2 minutes               0.0.0.0:8082->80/tcp   first-app-web
abc123def456   nginx:latest    "/docker-entrypoint.…"   1 day ago       Exited (0) 1 day ago                 stopped-container
```

**確認ポイント：**
- `STATUS: Exited` - 停止中のコンテナ
- 停止中のコンテナも表示される

### 最新のコンテナのみ表示

```bash
docker ps -n 3
```

**意味：**
- 最新の3つのコンテナのみ表示

### サイズ情報も表示

```bash
docker ps -s
```

**出力例：**
```
CONTAINER ID   IMAGE           COMMAND                  CREATED         STATUS         PORTS     NAMES                 SIZE
3ebf371bebab   first-app-web   "/docker-entrypoint.…"   2 minutes ago   Up 2 minutes   0.0.0.0:8082->80/tcp   first-app-web   2B (virtual 133MB)
```

**意味：**
- **SIZE**: コンテナが使用しているディスク容量
- **2B (virtual 133MB)**: 読み書き可能レイヤーが2バイト、イメージサイズが133MB

## よくある使い方

### 1. コンテナが動いているか確認

```bash
docker ps
```

**確認ポイント：**
- `STATUS`が`Up`になっているか
- コンテナ名が表示されているか

### 2. 特定のコンテナを探す

```bash
docker ps | grep first-app
```

**出力例：**
```
3ebf371bebab   first-app-web   "/docker-entrypoint.…"   2 minutes ago   Up 2 minutes   0.0.0.0:8082->80/tcp   first-app-web
```

### 3. ポート番号を確認

```bash
docker ps | grep 8082
```

**確認ポイント：**
- どのコンテナが8082番ポートを使用しているか

### 4. コンテナの詳細情報を確認

```bash
docker inspect first-app-web
```

**確認ポイント：**
- コンテナの詳細な設定情報
- ネットワーク設定
- ボリューム設定
- 環境変数

## 視覚的に理解する

### docker psの出力を図で理解

```
┌─────────────────────────────────────────────────────────────────┐
│ CONTAINER ID   IMAGE           STATUS         PORTS     NAMES   │
├─────────────────────────────────────────────────────────────────┤
│ 3ebf371bebab   first-app-web   Up 2 minutes   8082->80  first   │
│                ↑                ↑              ↑        ↑       │
│                イメージ         状態           ポート    名前    │
└─────────────────────────────────────────────────────────────────┘
```

### コンテナの状態を理解

```
コンテナのライフサイクル：

作成（CREATED）
    ↓
起動（Up）
    ↓
停止（Exited）
    ↓
削除（削除されるとdocker ps -aにも表示されない）
```

## 実践：docker psを使って確認する

### ステップ1：コンテナの状態を確認

```bash
docker ps
```

**確認ポイント：**
- `first-app-web`が表示されているか
- `STATUS`が`Up`になっているか
- `PORTS`が`8082->80`になっているか

### ステップ2：コンテナを停止

```bash
docker-compose stop
# または
docker stop first-app-web
```

### ステップ3：再度確認

```bash
docker ps
```

**確認ポイント：**
- `first-app-web`が表示されなくなる

```bash
docker ps -a
```

**確認ポイント：**
- `STATUS`が`Exited`になっている

### ステップ4：コンテナを再開

```bash
docker-compose start
# または
docker start first-app-web
```

### ステップ5：再度確認

```bash
docker ps
```

**確認ポイント：**
- `STATUS`が`Up`に戻る
- `CREATED`の時間は変わらない（コンテナは再作成されていない）

## まとめ

`docker ps`で表示される情報：

- **CONTAINER ID**：コンテナの一意の識別子
- **IMAGE**：コンテナが作成された元のイメージ
- **COMMAND**：コンテナ起動時に実行されるコマンド
- **CREATED**：コンテナが作成された日時
- **STATUS**：コンテナの現在の状態（Up、Exitedなど）
- **PORTS**：ポートマッピングの情報（ホスト:コンテナ）
- **NAMES**：コンテナの名前

これらの情報を理解することで、**コンテナの状態を視覚的に把握**できます！

---

**次のステップ**
- [Docker基本コマンド](./02_Docker基本コマンド.md)で、他のコマンドも学びましょう
- [視覚的に学ぶサンプル](../examples/visual-learning/)で、実際に確認してみましょう

