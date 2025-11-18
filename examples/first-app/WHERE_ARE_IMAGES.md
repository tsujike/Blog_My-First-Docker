# イメージはどこにある？コンテナとの関係は？

「ダウンロードしてきたイメージはどこに作成されるのか？」「それがコンテナになるのか？」「目に見えないのか？」という疑問に答えます。

## イメージはどこにある？

### イメージの保存場所

**イメージは、Dockerの内部ストレージに保存されます。**

**確認方法：**
```bash
# イメージ一覧を表示
docker images
```

**出力例：**
```
REPOSITORY      TAG       IMAGE ID       CREATED         SIZE
first-app_web   latest    abc123def456   2 minutes ago   133MB
nginx           alpine    def456ghi789   2 weeks ago     40MB
```

**保存場所：**
- Windows: `C:\ProgramData\Docker\wsl\data\ext4.vhdx`
- Mac: `~/Library/Containers/com.docker.docker/Data/vms/0/data/Docker.raw`
- Linux: `/var/lib/docker/`

**重要なポイント：**
- イメージは**Dockerの内部ストレージ**に保存される
- 通常は直接アクセスしない
- `docker images`コマンドで確認できる

### イメージのサイズを確認

```bash
# Dockerが使用しているディスク容量を確認
docker system df
```

**出力例：**
```
TYPE            TOTAL     ACTIVE    SIZE      RECLAIMABLE
Images          2         2         173MB     0B (0%)
Containers      1         1         2B        0B (0%)
Local Volumes   0         0         0B        0B (0%)
Build Cache     0         0         0B        0B (0%)
```

**確認ポイント：**
- **Images**: イメージの総サイズ（173MB）
- **Containers**: コンテナのサイズ（2B = 読み書き可能レイヤー）
- **Local Volumes**: ボリュームのサイズ

## イメージとコンテナの関係

### イメージとは？

**イメージ = 設計図（テンプレート）**

```
イメージ（Image）
├── OS（Linux）
├── ランタイム（nginx）
├── 設定ファイル
└── アプリケーション
```

**特徴：**
- **読み取り専用**（変更できない）
- **再利用可能**（同じイメージから複数のコンテナを作成できる）
- **軽量**（共有レイヤーを使用）

### コンテナとは？

**コンテナ = イメージから作成された実行中のインスタンス**

```
コンテナ（Container）
├── イメージ（読み取り専用）
└── 読み書き可能レイヤー（変更可能）
```

**特徴：**
- **実行可能**（実際に動いている）
- **読み書き可能**（変更できる）
- **一時的**（削除すると変更は失われる）

### イメージからコンテナへの変換

**プロセス：**

1. **イメージをダウンロード**
   ```bash
   docker pull nginx:alpine
   ```
   → イメージがDockerの内部ストレージに保存される

2. **イメージからコンテナを作成**
   ```bash
   docker run nginx:alpine
   ```
   → イメージを元にコンテナが作成される

3. **コンテナが起動**
   → コンテナ内でアプリケーションが実行される

**図で理解：**

```
イメージ（設計図）
    ↓ docker run
コンテナ（実行中のインスタンス）
    ↓ 起動
アプリケーションが動く
```

### 実際の例

**このサンプルの場合：**

1. **イメージの作成**
   ```bash
   docker-compose build
   ```
   → `first-app_web`イメージが作成される

2. **コンテナの作成**
   ```bash
   docker-compose up -d
   ```
   → `first-app-web`コンテナが作成される

3. **コンテナの起動**
   → nginxサーバーが起動する

**確認方法：**
```bash
# イメージを確認
docker images | grep first-app

# コンテナを確認
docker ps | grep first-app
```

## 目に見えるか？ - 実際に「見る」方法

### 重要なポイント：コンテナ内は「見える」！

**誤解しやすい点：**
- 「ファイルとして直接見ることはできない」という説明は誤解を招きます
- **実際には、コンテナ内のファイルシステムは完全に見えます**
- **コンテナ内のプロセスも見えます**
- **コンテナの状態も見えます**

### 実際に「見る」方法

#### 1. コンテナ内のファイルを見る

**これは実際に見えます！**

```bash
# コンテナ内のファイル一覧を見る
docker exec first-app-web ls -la /usr/share/nginx/html
```

**出力例：**
```
total 8
drwxr-xr-x    1 root     root          4096 Jan  1 12:00 .
drwxr-xr-x    1 root     root          4096 Jan  1 12:00 ..
-rw-r--r--    1 root     root          1234 Jan  1 12:00 index.html
```

**確認ポイント：**
- **ファイル名が見える**：`index.html`
- **ファイルサイズが見える**：`1234`バイト
- **作成日時が見える**：`Jan  1 12:00`
- **これは実際のファイルです！**

#### 2. コンテナ内のディレクトリ構造を見る

```bash
# コンテナ内の現在のディレクトリを確認
docker exec first-app-web pwd

# コンテナ内のディレクトリ構造を見る
docker exec first-app-web find / -type d -maxdepth 2
```

**確認ポイント：**
- **ディレクトリ構造が見える**
- **ファイルシステムが見える**
- **これは実際のディレクトリです！**

#### 3. コンテナ内のファイルの内容を見る

```bash
# コンテナ内のファイルの内容を見る
docker exec first-app-web cat /usr/share/nginx/html/index.html
```

**確認ポイント：**
- **ファイルの内容が見える**
- **HTMLファイルの中身が見える**
- **これは実際のファイル内容です！**

#### 4. コンテナ内のプロセスを見る

```bash
# コンテナ内で動いているプロセスを見る
docker exec first-app-web ps aux
```

**出力例：**
```
PID   USER     TIME   COMMAND
1     root     0:00   nginx: master process nginx -g daemon off;
6     nginx    0:00   nginx: worker process
```

**確認ポイント：**
- **プロセスが見える**：nginxが動いている
- **プロセスIDが見える**：PID 1, 6
- **これは実際に動いているプロセスです！**

#### 5. コンテナ内に入って操作する

```bash
# コンテナ内に入る（シェルを起動）
docker exec -it first-app-web sh
```

**コンテナ内で：**
```bash
# ファイルを見る
ls -la /usr/share/nginx/html

# ファイルの内容を見る
cat /usr/share/nginx/html/index.html

# プロセスを見る
ps aux

# ディレクトリを移動
cd /usr/share/nginx/html

# ファイルを編集（読み取り専用ボリュームの場合は編集できない）
```

**確認ポイント：**
- **コンテナ内に入れる**
- **ファイルシステムを操作できる**
- **これは実際のLinux環境です！**

### イメージは「見える」

**確認方法：**
```bash
# イメージ一覧を表示
# Pythonで「python app.py」と入力するように、ターミナルに「docker images」と入力してEnterキーを押します
docker images
```

**出力例：**
```
REPOSITORY      TAG       IMAGE ID       CREATED         SIZE
first-app_web   latest    abc123def456   2 minutes ago   133MB
nginx           alpine    def456ghi789   2 weeks ago     40MB
```

**確認ポイント：**
- **イメージ名が見える**：`first-app_web`、`nginx`
- **サイズが見える**：`133MB`、`40MB`
- **作成日時が見える**：`2 minutes ago`
- **これは実際のイメージ情報です！**

**ただし：**
- イメージファイル自体はDockerの内部ストレージに保存されている
- しかし、イメージの**情報**は見える
- イメージから作成されたコンテナ内の**ファイルシステム**は見える

**「コマンド」について：**
- Pythonで`python app.py`と入力するように、Dockerでも`docker images`と入力します
- 詳しくは：[WHAT_IS_COMMAND.md](./WHAT_IS_COMMAND.md)を読んでください

### コンテナの状態は「見える」

**確認方法：**
```bash
# コンテナ一覧を表示
# Pythonで「python app.py」と入力するように、ターミナルに「docker ps」と入力してEnterキーを押します
docker ps
```

**出力例：**
```
CONTAINER ID   IMAGE           STATUS         PORTS                  NAMES
abc123def456   first-app-web   Up 2 minutes   0.0.0.0:8082->80/tcp   first-app-web
```

**確認ポイント：**
- **コンテナ名が見える**：`first-app-web`
- **状態が見える**：`Up 2 minutes`（実行中）
- **ポートが見える**：`0.0.0.0:8082->80/tcp`
- **これは実際のコンテナの状態です！**

### 実際に「見る」方法

#### 1. イメージを確認

```bash
# イメージ一覧
docker images

# イメージの詳細
docker inspect nginx:alpine
```

**出力例：**
```
[
    {
        "Id": "sha256:def456ghi789...",
        "RepoTags": ["nginx:alpine"],
        "Created": "2024-01-01T00:00:00Z",
        "Size": 40000000,
        ...
    }
]
```

#### 2. コンテナを確認

```bash
# コンテナ一覧
docker ps

# コンテナの詳細
docker inspect first-app-web
```

**出力例：**
```
[
    {
        "Id": "abc123def456...",
        "Name": "/first-app-web",
        "State": {
            "Status": "running",
            ...
        },
        ...
    }
]
```

#### 3. コンテナ内を見る

```bash
# コンテナ内に入る
docker exec -it first-app-web sh

# コンテナ内のファイルを確認
docker exec first-app-web ls -la /usr/share/nginx/html

# コンテナ内のプロセスを確認
docker exec first-app-web ps aux
```

## 視覚的に理解する

### イメージとコンテナの関係図

```
┌─────────────────────────────────┐
│  イメージ（Image）               │
│  ┌───────────────────────────┐ │
│  │ nginx:alpine              │ │
│  │ - OS: Linux               │ │
│  │ - Webサーバー: nginx      │ │
│  │ - サイズ: 40MB           │ │
│  │ - 読み取り専用            │ │
│  └───────────────────────────┘ │
└─────────────────────────────────┘
            ↓ docker run
┌─────────────────────────────────┐
│  コンテナ（Container）          │
│  ┌───────────────────────────┐ │
│  │ first-app-web             │ │
│  │ ├─ イメージ（読み取り専用）│ │
│  │ └─ 読み書き可能レイヤー   │ │
│  │    - 変更可能             │ │
│  │    - サイズ: 2B           │ │
│  └───────────────────────────┘ │
└─────────────────────────────────┘
```

### 実際のファイルシステム

**イメージ：**
- Dockerの内部ストレージに保存
- 直接アクセスできない
- `docker images`で確認

**コンテナ：**
- イメージの上に読み書き可能レイヤーを追加
- コンテナ内のファイルシステムが見える
- `docker exec`で確認

## よくある質問

### Q1: イメージはどこに保存されているの？

**A:** Dockerの内部ストレージに保存されています。

**Windowsの場合：**
- `C:\ProgramData\Docker\wsl\data\ext4.vhdx`
- WSL2の仮想ディスク内

**Macの場合：**
- `~/Library/Containers/com.docker.docker/Data/vms/0/data/Docker.raw`
- Docker Desktopの仮想ディスク内

**Linuxの場合：**
- `/var/lib/docker/`
- 直接アクセス可能

**確認方法：**
```bash
docker system df
```

### Q2: イメージがコンテナになるの？

**A:** はい、イメージからコンテナが作成されます。

**プロセス：**
1. イメージをダウンロード（`docker pull`）
2. イメージからコンテナを作成（`docker run`）
3. コンテナが起動

**重要なポイント：**
- イメージは「設計図」
- コンテナは「実行中のインスタンス」
- 1つのイメージから複数のコンテナを作成できる

### Q3: 目に見えないの？

**A:** **見えます！** コンテナ内のファイルシステムは完全に見えます。

**実際に見えるもの：**

1. **コンテナ内のファイル**
   ```bash
   docker exec first-app-web ls -la /usr/share/nginx/html
   ```
   → ファイル名、サイズ、日時が見える

2. **コンテナ内のファイルの内容**
   ```bash
   docker exec first-app-web cat /usr/share/nginx/html/index.html
   ```
   → HTMLファイルの中身が見える

3. **コンテナ内のプロセス**
   ```bash
   docker exec first-app-web ps aux
   ```
   → nginxプロセスが見える

4. **コンテナ内に入って操作**
   ```bash
   docker exec -it first-app-web sh
   ```
   → コンテナ内のシェルに入れる

**重要なポイント：**
- **コンテナ内のファイルシステムは完全に見える**
- **コンテナ内のプロセスも見える**
- **コンテナ内に入って操作できる**
- 「見えない」のは、Dockerの内部ストレージのファイル構造だけ
- しかし、それは重要ではない。重要なのは、コンテナ内のファイルシステムが見えること

### Q4: イメージとコンテナの違いは？

**A:** 

**イメージ：**
- 読み取り専用
- 設計図（テンプレート）
- 再利用可能
- 変更できない

**コンテナ：**
- 読み書き可能
- 実行中のインスタンス
- 一時的
- 変更できる

**例：**
- イメージ = クッキーの型
- コンテナ = 型から作られたクッキー

## 実際に確認してみる

### ステップ1：イメージを確認

```bash
# イメージ一覧
docker images

# イメージのサイズを確認
docker system df
```

### ステップ2：コンテナを確認

```bash
# コンテナ一覧
docker ps

# コンテナの詳細
docker inspect first-app-web
```

### ステップ3：コンテナ内を見る

```bash
# コンテナ内のファイルを確認
docker exec first-app-web ls -la /usr/share/nginx/html

# コンテナ内のプロセスを確認
docker exec first-app-web ps aux
```

### ステップ4：イメージとコンテナの関係を確認

```bash
# イメージからコンテナが作成されたことを確認
docker inspect first-app-web | grep Image

# コンテナが使用しているイメージを確認
docker ps --format "table {{.Names}}\t{{.Image}}"
```

## まとめ

### イメージはどこにある？

- **Dockerの内部ストレージ**に保存される
- Windows: `C:\ProgramData\Docker\wsl\data\ext4.vhdx`
- Mac: `~/Library/Containers/com.docker.docker/Data/vms/0/data/Docker.raw`
- Linux: `/var/lib/docker/`

### イメージがコンテナになる？

- **はい**、イメージからコンテナが作成される
- イメージ = 設計図（読み取り専用）
- コンテナ = 実行中のインスタンス（読み書き可能）

### 目に見えるか？

**答え：完全に見えます！**

**実際に見えるもの：**

1. **コンテナ内のファイル**
   - ファイル名、サイズ、日時が見える
   - `docker exec first-app-web ls -la /usr/share/nginx/html`

2. **コンテナ内のファイルの内容**
   - HTMLファイルの中身が見える
   - `docker exec first-app-web cat /usr/share/nginx/html/index.html`

3. **コンテナ内のプロセス**
   - nginxプロセスが見える
   - `docker exec first-app-web ps aux`

4. **コンテナ内に入って操作**
   - コンテナ内のシェルに入れる
   - `docker exec -it first-app-web sh`

**重要なポイント：**
- **コンテナ内のファイルシステムは完全に見える**
- **コンテナ内のプロセスも見える**
- **コンテナ内に入って操作できる**
- 「見えない」のは、Dockerの内部ストレージのファイル構造だけ
- しかし、それは重要ではない。重要なのは、コンテナ内のファイルシステムが見えること

---

**次のステップ**
- [WHAT_HAPPENS.md](./WHAT_HAPPENS.md)で、docker-compose up -dで何が起きているかを学びましょう
- [docker psの見方](../../10_docker_psの見方.md)で、コンテナの状態を確認する方法を学びましょう

