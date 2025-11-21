# 【Docker入門】必須用語解説：Repository、Image、Container、Volumes

どうも、ケニー（tsujikenzo）です。Dockerを学ぶ上で、**Repository（リポジトリ）**、**Image（イメージ）**、**Container（コンテナ）**、**Volumes（ボリューム）**の4つの用語を理解することは非常に重要です。

この記事では、**Dockerの役割「アプリを作る、運ぶ、動かす」**をイメージしながら、この4つの用語を詳しく解説します。

## Dockerの役割：アプリを作る、運ぶ、動かす

Dockerは、以下の3つの役割を持っています：

1. **アプリを作る**：アプリとその実行環境を一緒にパッケージ化する
2. **アプリを運ぶ**：Macで作ったアプリを、WindowsやLinuxでも動かせる
3. **アプリを動かす**：どこでも同じように動く

この3つの役割を実現するために、以下の4つの用語が重要です：

- **Repository（リポジトリ）**：アプリの設計図（イメージ）を保存・管理する場所
- **Image（イメージ）**：アプリを作るための設計図
- **Container（コンテナ）**：設計図から作られた、実際に動いているアプリ
- **Volumes（ボリューム）**：アプリのデータを保存する場所

**この記事の流れ：**
1. まず、4つの用語の関係を理解する
2. それぞれの用語を詳しく解説する
3. 「アプリを作る、運ぶ、動かす」の流れで具体例を見る

## 4つの用語の関係：「アプリを作る、運ぶ、動かす」の流れ

Dockerの役割「アプリを作る、運ぶ、動かす」を実現するために、以下の4つの用語が使われます：

```
┌─────────────────────────────────┐
│  リポジトリ（Repository）         │
│  「アプリを運ぶ」                 │
│  - Docker Hubなど                 │
│  - イメージを保存・管理する場所    │
└──────────────┬──────────────────┘
               │ docker pull（ダウンロード）
               ↓ 「アプリを運ぶ」
┌─────────────────────────────────┐
│  イメージ（Image）               │
│  「アプリを作る」                 │
│  - 設計図のようなもの             │
│  - アプリと環境をパッケージ化     │
└──────────────┬──────────────────┘
               │ docker run（実行）
               ↓ 「アプリを動かす」
┌─────────────────────────────────┐
│  コンテナ（Container）           │
│  「アプリを動かす」               │
│  - 実際に動いているアプリ         │
│  - 独立して動作                   │
└──────────────┬──────────────────┘
               │ volumes（マウント）
               ↓ 「データを保存」
┌─────────────────────────────────┐
│  ボリューム（Volumes）            │
│  「データを永続化」               │
│  - コンテナ削除後も残る           │
│  - データを保存する場所            │
└─────────────────────────────────┘
```

**流れ：**
1. **リポジトリ**からイメージをダウンロード（アプリを運ぶ）
2. **イメージ**からコンテナを作成（アプリを作る）
3. **コンテナ**を実行（アプリを動かす）
4. **ボリューム**でデータを保存（データを永続化）

## 1. リポジトリ（Repository）

### リポジトリとは？

リポジトリは、**Dockerイメージを保存・管理する場所**です。

**Dockerの役割「アプリを運ぶ」を実現する場所：**
- Macで作ったアプリのイメージを、リポジトリにアップロード
- WindowsやLinuxから、リポジトリからイメージをダウンロード
- どこからでも同じイメージを取得できる

**例：**
```
開発者（Mac）
    ↓ イメージをアップロード
Docker Hub（リポジトリ）
    ↓ イメージをダウンロード
他の開発者（Windows/Linux）
    ↓
同じイメージが使える！
```

### 主なリポジトリ

#### Docker Hub（公式）
- URL: https://hub.docker.com/
- 最も有名で、多くの公式イメージが公開されている
- 無料で使用できる

#### その他のリポジトリ
- **GitHub Container Registry**（GitHubが提供）
- **Amazon ECR**（AWSが提供）
- **Google Container Registry**（GCPが提供）
- **Azure Container Registry**（Azureが提供）

### リポジトリ名の形式

```
ユーザー名/リポジトリ名:タグ
```

**例：**
- `nginx:latest` - nginxの最新版
- `wordpress:5.9` - WordPressの5.9バージョン
- `mysql:8.0` - MySQLの8.0バージョン
- `python:3.11-slim` - Python 3.11の軽量版

**タグについて：**
- `latest`：最新版（タグを省略するとこれが使われる）
- `8.0`：特定のバージョン
- `alpine`：Alpine Linuxベースの軽量版
- `slim`：軽量版

### リポジトリからイメージを取得する

```bash
# リポジトリからイメージをダウンロード
docker pull nginx:latest

# タグを省略するとlatestが使われる
docker pull nginx
```

## 2. イメージ（Image）

### イメージとは？

**イメージ（Image）**は、**アプリを作るための設計図**です。

もっと具体的に言うと、**コンテナイメージ（Container Image）**は、コンテナのファイルシステムを提供するものです。

### ファイルシステムとは？

**ファイルシステム**について、まず理解しましょう。

**ファイルシステムとは：**
- ディスクにプログラムファイルやデータファイルを保存・管理する仕組み
- Windowsなら「Cドライブ」、Mac/Linuxなら「/」など
- フォルダやファイルを整理する仕組み

**ファイルシステムに保存されるもの：**
- **プログラムファイル**：WordPress、Python、MySQLなどの実行ファイル
- **データファイル**：設定ファイル、データベースファイルなど
- **フォルダ構造**：`/usr/bin/`、`/etc/`、`/var/www/`など

**例：Linuxのファイルシステム**
```
/（ルート）
├── usr/bin/python3    ← Pythonのプログラムファイル
├── etc/nginx/         ← Nginxの設定ファイル
└── var/www/html/      ← Webサイトのファイル
```

### イメージが提供するファイルシステム

コンテナを実行すると、コンテナは**隔離されたファイルシステム**を使います。この特別なファイルシステムは、コンテナイメージによって提供されます。

**イメージが提供するもの：**
- アプリケーションのプログラムファイル
- 必要なライブラリや依存関係
- 設定ファイル
- フォルダ構造

**例：nginxイメージが提供するファイルシステム**
```
/usr/share/nginx/html/  ← Webサイトのファイルを置く場所
/etc/nginx/             ← Nginxの設定ファイル
/usr/sbin/nginx         ← Nginxのプログラムファイル
```

### イメージに含まれるもの

イメージには、コンテナのファイルシステムが含まれており、**アプリケーションの実行に必要な全てを含む必要があります**：

- **依存関係**：アプリケーションを実行するために必要なパッケージ、ライブラリなど
- **設定ファイル**：アプリケーションの設定
- **スクリプト**：起動スクリプトなど
- **バイナリ**：実行ファイル
- **環境変数**：デフォルトの環境変数
- **デフォルトコマンド**：コンテナ起動時に実行するコマンド
- **メタデータ**：その他の設定情報

### イメージの特徴

#### 読み取り専用
- イメージ自体は変更できない
- 複数のコンテナで同じイメージを共有できる

#### 階層構造（レイヤー）
- イメージは複数のレイヤーで構成されている
- レイヤーを共有することで、ディスク容量を節約できる
- Dockerfileの各命令ごとにレイヤーが生成される
- 変更があったレイヤーのみが再生成されるため、軽量で高速

#### 再利用可能
- 1つのイメージから複数のコンテナを作成できる
- イメージは「設計図」のようなもの

### イメージの例え

**料理に例えると：**
- イメージ = レシピ本を元に作られた完成品の写真
- 写真は何枚でもコピーできる（複数のコンテナを作成できる）
- 写真自体は変更できない（読み取り専用）

### イメージの取得方法

#### 方法1：リポジトリから取得（pull）

```bash
# Docker Hubから取得
docker pull nginx:latest
docker pull wordpress:5.9
docker pull mysql:8.0
```

#### 方法2：Dockerfileから作成（build）

```bash
# Dockerfileからイメージを作成
docker build -t my-app:latest .
```

### イメージの確認

```bash
# ローカルにあるイメージの一覧を表示
docker images

# または
docker image ls
```

**出力例：**
```
REPOSITORY    TAG       IMAGE ID       CREATED         SIZE
nginx         latest    abc123def456   2 weeks ago     133MB
wordpress     5.9       def456ghi789   1 month ago     612MB
mysql         8.0       ghi789jkl012   3 weeks ago     516MB
```

### イメージの削除

```bash
# イメージを削除
docker rmi nginx:latest

# または
docker image rm nginx:latest

# 未使用のイメージを一括削除
docker image prune -a
```

## 3. コンテナ（Container）

### コンテナとは？

**コンテナ（Container）**は、**イメージから作られた、実際に動いているアプリ**です。

もっと具体的に言うと：
- **イメージ**：設計図（読み取り専用、変更不可）
- **コンテナ**：設計図から作られた、実際に動いているアプリ（読み書き可能、変更可能）

**例え：**
- **イメージ** = レシピ本を元に作られた完成品の写真
- **コンテナ** = その写真を見ながら実際に作った料理

### コンテナの特徴：隔離されたプロセス

コンテナは、**ホストマシン上にある他のプロセスから隔離されたプロセス**です。

**「隔離されたプロセス」とは？**

**プロセスとは：**
- プログラムが実行されている状態
- 例：WordPressが動いている、MySQLが動いている

**隔離とは：**
- 他のプロセスに影響を与えない
- 他のプロセスから影響を受けない
- 独立して動作する

**例：**
```
ホストマシン上：
├── 通常のプロセス（WordPress、MySQLなど）
└── コンテナ内のプロセス（WordPress、MySQLなど）
    ↑ これらは互いに影響を与えない（隔離されている）
```

**なぜ隔離するのか？**
- アプリ同士が干渉しない
- セキュリティが向上する
- 環境を独立させられる

### コンテナの技術的な仕組み

コンテナの隔離は、**カーネルの名前空間（namespaces）とcgroup**の活用によって実現されています。これらは長らくLinuxに存在する機能です。Dockerはこれらの能力を、分かりやすく簡単に使えるようにしています。

**chrootを知っている場合：**
- コンテナは**chrootの拡張バージョン**と考えられます
- ファイルシステムはイメージから由来します
- ただし、コンテナの場合は、単純なchrootではできない付加的な隔離を追加します

### コンテナの特徴

#### イメージの実行可能な実体（インスタンス）
- Docker APIやCLIを使い、コンテナの作成、開始、停止、移動、削除ができます
- イメージから作成されますが、実行中の状態を持ちます

#### 読み書き可能なファイルシステム
- コンテナ内でファイルを作成・変更・削除できる
- イメージ（読み取り専用）の上に、読み書き可能なレイヤーが追加されます
- ただし、コンテナを削除すると変更は失われる

#### 独立して動作（隔離）
- 各コンテナは独立した環境で動作する
- 他のコンテナに影響を与えない
- ホストマシン上の他のプロセスからも隔離されている
- コンテナはお互いに隔離され、実行にはそれぞれが自身のソフトウェア、バイナリ、設定を使います

#### 環境に依存しない実行（可搬性）
- **Mac、Windows、Linuxなど、どのOSでも同じように動く**
- **環境ごとアプリを運ぶ**：アプリケーションとその実行環境が一緒にパッケージ化されている
- ローカルマシン上や仮想マシン上で実行できる
- クラウドにもデプロイできます
- Macで作ったコンテナが、WindowsでもLinuxでもそのまま動く

#### 一時的
- コンテナは停止・削除できる
- データを永続化するにはボリュームを使用する

### コンテナの作成と起動

```bash
# イメージからコンテナを作成・起動
docker run -d -p 8080:80 --name my-nginx nginx

# オプションの説明：
# -d: バックグラウンドで実行
# -p 8080:80: ポートマッピング（ホスト:コンテナ）
# --name my-nginx: コンテナに名前を付ける
# nginx: 使用するイメージ
```

### コンテナの確認

**コマンドの説明：**

`docker ps`は、**実行中のコンテナを確認するコマンド**です。

**「ps」とは？**
- 「process status」の略
- プロセスの状態を確認する、という意味
- コンテナは「隔離されたプロセス」なので、`docker ps`でコンテナの状態を確認できます

**使い方：**
```bash
# 実行中のコンテナを確認
docker ps

# 停止中のコンテナも含めて確認
docker ps -a

# または
docker container ls -a
```

**出力例：**
```
CONTAINER ID   IMAGE     COMMAND                  CREATED         STATUS         PORTS                  NAMES
abc123def456   nginx     "/docker-entrypoint..."   2 hours ago     Up 2 hours     0.0.0.0:8080->80/tcp   my-nginx
```

**各項目の説明：**
- **CONTAINER ID**：コンテナの識別子（短縮版）
- **IMAGE**：このコンテナを作成するときに使用したイメージ名
  - DAEMONが自動で付与したものではなく、**ユーザーが指定したイメージ名**が表示されます
  - `docker run`コマンドや`docker-compose.yml`で指定したイメージ名がそのまま表示されます
  - 例：`nginx`、`nginx:latest`、`wordpress:5.9`、`mysql:8.0`など
  - イメージ名の形式：`リポジトリ名:タグ`（タグを省略すると`latest`が使われます）
- **COMMAND**：コンテナ起動時に実行されるコマンド
- **CREATED**：コンテナが作成された時刻
- **STATUS**：コンテナの状態（Up = 実行中、Exited = 停止中）
- **PORTS**：ポートマッピング（ホスト:コンテナ）
- **NAMES**：コンテナの名前

**IMAGEの例：**
```bash
# docker runでイメージを指定
docker run -d nginx:latest
# → IMAGE列に「nginx」と表示される

docker run -d wordpress:5.9
# → IMAGE列に「wordpress」と表示される（タグは省略される場合がある）

docker run -d mysql:8.0
# → IMAGE列に「mysql」と表示される
```

**IMAGEとコンテナの関係：**
- 1つのイメージから複数のコンテナを作成できます
- 同じイメージを使っているコンテナは、IMAGE列に同じ名前が表示されます
- イメージを削除しても、そのイメージから作られたコンテナは残ります（ただし、イメージを再ダウンロードする必要があります）

**詳しくは：** [docker psの見方](./10_docker_psの見方.md)を参照してください。

### コンテナの操作

#### コンテナを停止する

**実行中のコンテナを確認：**
```bash
# 実行中のコンテナを確認
docker ps
```

**コンテナを停止：**
```bash
# 1つのコンテナを停止
docker stop my-nginx

# コンテナIDで停止
docker stop abc123def456

# 複数のコンテナを一度に停止
docker stop my-nginx mysql-test wordpress-app

# すべての実行中のコンテナを停止
docker stop $(docker ps -q)
```

**停止の確認：**
```bash
# 停止したか確認
docker ps

# 停止中のコンテナも含めて確認
docker ps -a
```

**出力例（停止前）：**
```
CONTAINER ID   IMAGE     COMMAND                  CREATED         STATUS         PORTS                  NAMES
abc123def456   nginx     "/docker-entrypoint..."   2 hours ago     Up 2 hours     0.0.0.0:8080->80/tcp   my-nginx
```

**出力例（停止後）：**
```
CONTAINER ID   IMAGE     COMMAND                  CREATED         STATUS         PORTS                  NAMES
abc123def456   nginx     "/docker-entrypoint..."   2 hours ago     Exited (0)     0.0.0.0:8080->80/tcp   my-nginx
```

**STATUSの見方：**
- `Up`：実行中
- `Exited (0)`：正常終了（停止）
- `Exited (1)`：エラーで終了

**強制停止：**
```bash
# 通常の停止（SIGTERMシグナルを送信、アプリが正常終了処理を行う）
docker stop my-nginx

# 強制停止（SIGKILLシグナルを送信、即座に停止）
docker kill my-nginx
```

**注意：**
- `docker stop`は、アプリに停止シグナルを送り、正常終了処理を待ちます（通常は10秒以内）
- `docker kill`は、即座に強制停止します（データが失われる可能性があります）
- 通常は`docker stop`を使いましょう

#### コンテナを再開・再起動する

```bash
# コンテナを再開（停止中のコンテナを起動）
docker start my-nginx

# コンテナを再起動（停止→起動）
docker restart my-nginx
```

#### コンテナを削除する

```bash
# コンテナを削除（停止中のみ）
docker rm my-nginx

# 実行中のコンテナを強制削除（停止→削除）
docker rm -f my-nginx
```

### 使っていないコンテナを一括削除

**使っていないコンテナとは：**
- 停止中のコンテナ
- 実行中だが、もう使わないコンテナ

**一括削除の方法：**

```bash
# 停止中のコンテナを一括削除（確認あり）
docker container prune

# 確認なしで一括削除
docker container prune -f
```

**出力例：**
```
WARNING! This will remove all stopped containers.
Are you sure you want to continue? [y/N] y
Deleted Containers:
abc123def456
def456ghi789
ghi789jkl012

Total reclaimed space: 150MB
```

**注意：**
- 実行中のコンテナは削除されません
- 削除前に確認メッセージが表示されます（`-f`オプションで確認をスキップ）
- コンテナを削除すると、コンテナ内のデータも削除されます（ボリュームに保存したデータは残ります）

**特定のコンテナだけ削除したい場合：**
```bash
# 停止中のコンテナを確認
docker ps -a

# 特定のコンテナを削除
docker rm コンテナ名またはコンテナID
```

### コンテナ内でコマンドを実行

```bash
# コンテナ内でbashを起動
docker exec -it my-nginx /bin/bash

# コンテナ内でコマンドを実行
docker exec my-nginx ls /usr/share/nginx/html
```

## 3つの関係を図で理解

```
┌─────────────────────────────────┐
│  リポジトリ（Docker Hub）        │
│  - nginx:latest                 │
│  - wordpress:5.9                │
│  - mysql:8.0                    │
└──────────────┬──────────────────┘
               │ docker pull
               ↓
┌─────────────────────────────────┐
│  イメージ（Image）               │
│  - 読み取り専用                  │
│  - 設計図のようなもの            │
│  - 複数のコンテナで共有可能      │
└──────────────┬──────────────────┘
               │ docker run
               ↓
┌─────────────────────────────────┐
│  コンテナ（Container）          │
│  - 読み書き可能                  │
│  - 実際に動いているアプリ        │
│  - 独立して動作                  │
└─────────────────────────────────┘
```

## 具体例：nginxで理解する

### ステップ1：リポジトリからイメージを取得

```bash
docker pull nginx:latest
```

**何が起こるか：**
- Docker Hubからnginxのイメージがダウンロードされる
- ローカルのイメージストレージに保存される

### ステップ2：イメージの確認

```bash
docker images
```

**出力：**
```
REPOSITORY   TAG       IMAGE ID       CREATED         SIZE
nginx        latest    abc123def456   2 weeks ago     133MB
```

### ステップ3：イメージからコンテナを作成・起動

```bash
docker run -d -p 8080:80 --name my-nginx nginx
```

**何が起こるか：**
- nginxイメージからコンテナが作成される
- コンテナが起動し、nginxサーバーが動作し始める
- ホストの8080番ポートでアクセス可能になる

### ステップ4：コンテナの確認

**コマンドの説明：**

`docker ps`は、**実行中のコンテナを確認するコマンド**です。

```bash
docker ps
```

**出力：**
```
CONTAINER ID   IMAGE   COMMAND                  CREATED         STATUS         PORTS                  NAMES
abc123def456   nginx   "/docker-entrypoint..."   5 seconds ago   Up 4 seconds   0.0.0.0:8080->80/tcp   my-nginx
```

**見方：**
- **STATUS**が「Up」なら、コンテナは実行中です
- **PORTS**が「0.0.0.0:8080->80/tcp」なら、ホストの8080番ポートでアクセスできます

**詳しくは：** [docker psの見方](./10_docker_psの見方.md)を参照してください。

### ステップ5：ブラウザでアクセス

```
http://localhost:8080
```

nginxのデフォルトページが表示されます！

## よくある質問

### Q1: イメージとコンテナの違いは？

**A:** 
- **イメージ**：設計図（読み取り専用、変更不可）
- **コンテナ**：実際に動いているアプリ（読み書き可能、変更可能）

### Q2: 1つのイメージから複数のコンテナを作れますか？

**A:** はい、可能です。同じイメージから複数のコンテナを作成できます。

```bash
docker run -d -p 8080:80 --name nginx1 nginx
docker run -d -p 8081:80 --name nginx2 nginx
docker run -d -p 8082:80 --name nginx3 nginx
```

### Q3: コンテナを削除すると、イメージも消えますか？

**A:** いいえ、消えません。イメージとコンテナは別物です。コンテナを削除しても、イメージは残ります。

### Q4: イメージを削除すると、そのイメージから作ったコンテナはどうなりますか？

**A:** 実行中のコンテナがある場合、イメージは削除できません。まずコンテナを削除する必要があります。

### Q5: コンテナとプロセスの違いは？

**A:** コンテナは、**隔離されたプロセス**です。通常のプロセスと異なり、名前空間とcgroupによって隔離されています。これにより、他のプロセスやコンテナに影響を与えずに動作できます。

**「隔離されたプロセス」とは？**
- 他のプロセスに影響を与えない
- 他のプロセスから影響を受けない
- 独立して動作する

**例：**
- 通常のプロセス：WordPressとMySQLが同じ環境で動いている（干渉する可能性がある）
- コンテナ：WordPressコンテナとMySQLコンテナが別々に動いている（干渉しない）

### Q6: イメージとコンテナのファイルシステムの関係は？

**A:** 
- **イメージ**：読み取り専用のファイルシステムを含む
- **コンテナ**：イメージのファイルシステムの上に、読み書き可能なレイヤーが追加される
- コンテナ内でファイルを変更しても、イメージは変更されない
- コンテナを削除すると、読み書き可能なレイヤーも削除される

## 4. ボリューム（Volumes）

### ボリュームとは？

ボリュームは、**コンテナのデータを永続化するための仕組み**です。

### なぜボリュームが必要なのか？

コンテナには以下の問題があります：
- **コンテナを削除すると、コンテナ内のデータも消える**
- **データベースのデータ、アップロードされたファイルなどが失われる**

ボリュームを使うことで、コンテナを削除してもデータを保持できます。

### ボリュームの種類

#### 1. 名前付きボリューム（Named Volumes）

Dockerが管理するボリュームです。

**特徴：**
- Dockerが最適な場所に保存
- バックアップや移行が簡単
- 複数のコンテナで共有可能

**使用例：**
```bash
# docker runで使用
docker run -d -v my_data:/var/lib/mysql mysql

# docker-compose.ymlで使用
volumes:
  - db_data:/var/lib/mysql

volumes:
  db_data:
```

#### 2. バインドマウント（Bind Mounts）

ホストのディレクトリをコンテナにマウントします。

**特徴：**
- ホストから直接ファイルにアクセス可能
- 開発時に便利
- パスを指定する必要がある

**使用例：**
```bash
# docker runで使用
docker run -d -v /host/path:/container/path nginx

# docker-compose.ymlで使用
volumes:
  - ./data:/var/lib/mysql
  - ./config:/etc/nginx/conf.d
```

#### 3. 匿名ボリューム（Anonymous Volumes）

名前を付けずに作成されるボリュームです。

**特徴：**
- 自動的に名前が付けられる
- 通常は使用しない（名前付きボリュームを推奨）

### ボリュームの作成と使用

#### docker runで使用

```bash
# 名前付きボリューム
docker run -d -v my_data:/var/lib/mysql mysql

# バインドマウント
docker run -d -v ./data:/var/www/html nginx

# 複数のボリューム
docker run -d \
  -v db_data:/var/lib/mysql \
  -v ./config:/etc/mysql/conf.d \
  mysql
```

#### docker-compose.ymlで使用

```yaml
version: '3.8'

services:
  db:
    image: mysql:8.0
    volumes:
      - db_data:/var/lib/mysql        # 名前付きボリューム
      - ./init.sql:/docker-entrypoint-initdb.d/init.sql  # バインドマウント
    environment:
      MYSQL_ROOT_PASSWORD: rootpass

volumes:
  db_data:  # 名前付きボリュームの定義
```

### ボリュームの確認

```bash
# ボリューム一覧を表示
docker volume ls

# ボリュームの詳細を確認
docker volume inspect db_data

# 使用されていないボリュームを確認
docker volume ls -f dangling=true
```

**出力例：**
```
DRIVER    VOLUME NAME
local     db_data
local     wordpress_data
local     my_data
```

### ボリュームの削除

```bash
# 特定のボリュームを削除
docker volume rm db_data

# 使用されていないボリュームを一括削除
docker volume prune

# docker-composeでボリュームも削除
docker-compose down -v
```

### ボリュームのバックアップ

```bash
# ボリュームの内容をバックアップ
docker run --rm \
  -v db_data:/data \
  -v $(pwd):/backup \
  alpine tar czf /backup/backup.tar.gz -C /data .

# バックアップから復元
docker run --rm \
  -v db_data:/data \
  -v $(pwd):/backup \
  alpine tar xzf /backup/backup.tar.gz -C /data
```

### ボリュームの実用例

#### 例1：MySQLデータの永続化

```yaml
version: '3.8'

services:
  mysql:
    image: mysql:8.0
    volumes:
      - mysql_data:/var/lib/mysql  # データベースファイルを永続化
    environment:
      MYSQL_ROOT_PASSWORD: rootpass

volumes:
  mysql_data:
```

**メリット：**
- コンテナを削除してもデータベースのデータが残る
- コンテナを再作成しても同じデータが使える

#### 例2：WordPressのファイルを永続化

```yaml
version: '3.8'

services:
  wordpress:
    image: wordpress:latest
    volumes:
      - wordpress_data:/var/www/html           # WordPressファイル
      - ./wp-content:/var/www/html/wp-content  # テーマ・プラグイン
    ports:
      - "8080:80"

volumes:
  wordpress_data:
```

**メリット：**
- アップロードした画像やファイルが残る
- テーマやプラグインをホストから直接編集できる

#### 例3：設定ファイルの共有

```yaml
version: '3.8'

services:
  nginx:
    image: nginx:latest
    volumes:
      - ./nginx.conf:/etc/nginx/nginx.conf:ro  # 読み取り専用
      - ./html:/usr/share/nginx/html
    ports:
      - "8080:80"
```

**メリット：**
- 設定ファイルをホストから直接編集できる
- コンテナを再起動するだけで設定が反映される

### ボリュームの注意点

#### 1. ボリュームのマウントポイント

コンテナ内の既存のディレクトリにボリュームをマウントすると、**既存のファイルが隠される**可能性があります。

**対処法：**
- 空のディレクトリにマウントする
- または、既存のファイルを事前にコピーする

#### 2. パーミッション（権限）

WindowsとLinuxでパーミッションの扱いが異なる場合があります。

**対処法：**
```yaml
services:
  app:
    user: "1000:1000"  # ユーザーID:グループIDを指定
    volumes:
      - ./data:/app/data
```

#### 3. ボリュームのサイズ

ボリュームはディスク容量を使用します。定期的に確認しましょう。

```bash
# ディスク使用量を確認
docker system df -v
```

## 4つの関係を図で理解

```
┌─────────────────────────────────┐
│  リポジトリ（Docker Hub）        │
│  - nginx:latest                 │
│  - wordpress:5.9                │
│  - mysql:8.0                    │
└──────────────┬──────────────────┘
               │ docker pull
               ↓
┌─────────────────────────────────┐
│  イメージ（Image）               │
│  - 読み取り専用                  │
│  - 設計図のようなもの            │
│  - 複数のコンテナで共有可能      │
└──────────────┬──────────────────┘
               │ docker run
               ↓
┌─────────────────────────────────┐
│  コンテナ（Container）          │
│  - 読み書き可能                  │
│  - 実際に動いているアプリ        │
│  - 独立して動作                  │
└──────────────┬──────────────────┘
               │ volumes（マウント）
               ↓
┌─────────────────────────────────┐
│  ボリューム（Volumes）           │
│  - データを永続化                │
│  - コンテナ削除後も残る          │
│  - 複数のコンテナで共有可能      │
└─────────────────────────────────┘
```

## 具体例：MySQL + ボリュームで理解する

### ステップ1：ボリューム付きでMySQLコンテナを起動

```bash
docker run -d \
  --name mysql-test \
  -e MYSQL_ROOT_PASSWORD=rootpass \
  -v mysql_data:/var/lib/mysql \
  mysql:8.0
```

### ステップ2：データベースにデータを追加

```bash
docker exec -it mysql-test mysql -u root -p
```

MySQLに接続して、テーブルを作成し、データを追加します。

### ステップ3：コンテナを削除

```bash
docker stop mysql-test
docker rm mysql-test
```

### ステップ4：同じボリュームで新しいコンテナを起動

```bash
docker run -d \
  --name mysql-test2 \
  -e MYSQL_ROOT_PASSWORD=rootpass \
  -v mysql_data:/var/lib/mysql \
  mysql:8.0
```

### ステップ5：データが残っているか確認

```bash
docker exec -it mysql-test2 mysql -u root -p
```

**結果：** ステップ2で追加したデータが残っています！

## よくある質問

### Q1: ボリュームを使わないとどうなりますか？

**A:** コンテナを削除すると、コンテナ内のデータも全て消えます。データベースのデータ、アップロードされたファイルなどが失われます。

### Q2: 名前付きボリュームとバインドマウント、どちらを使うべき？

**A:** 
- **名前付きボリューム**：本番環境、データベースなど
- **バインドマウント**：開発環境、設定ファイルなど

### Q3: ボリュームはどこに保存されますか？

**A:** 
- **Windows**: `\\wsl$\docker-desktop-data\data\docker\volumes\`
- **Linux**: `/var/lib/docker/volumes/`
- **Mac**: Docker Desktop内の仮想マシン内

### Q4: ボリュームを削除するとどうなりますか？

**A:** ボリューム内のデータが全て削除されます。**重要なデータは必ずバックアップを取ってから削除してください。**

### Q5: 複数のコンテナで同じボリュームを共有できますか？

**A:** はい、可能です。複数のコンテナで同じボリュームをマウントできます。

```yaml
services:
  app1:
    volumes:
      - shared_data:/data
  app2:
    volumes:
      - shared_data:/data

volumes:
  shared_data:
```

## まとめ：Dockerの役割「アプリを作る、運ぶ、動かす」

### 4つの用語とDockerの役割

| 用語 | Dockerの役割 | 説明 |
|------|-------------|------|
| **リポジトリ（Repository）** | **アプリを運ぶ** | イメージを保存・管理する場所（Docker Hubなど） |
| **イメージ（Image）** | **アプリを作る** | アプリケーションを動かすための設計図（読み取り専用） |
| **コンテナ（Container）** | **アプリを動かす** | イメージから作られた実際に動いているアプリケーション（読み書き可能） |
| **ボリューム（Volumes）** | **データを保存** | コンテナのデータを永続化する仕組み（コンテナ削除後も残る） |

### 流れ

```
1. リポジトリからイメージをダウンロード（アプリを運ぶ）
   ↓
2. イメージからコンテナを作成（アプリを作る）
   ↓
3. コンテナを実行（アプリを動かす）
   ↓
4. ボリュームでデータを保存（データを永続化）
```

### 重要なポイント

- **イメージ**：設計図（読み取り専用、変更不可）
- **コンテナ**：実際に動いているアプリ（読み書き可能、変更可能）
- **ファイルシステム**：プログラムファイルやデータファイルを保存・管理する仕組み
- **隔離されたプロセス**：他のプロセスに影響を与えない、独立して動作するプロセス

この4つの関係を理解することで、Dockerの基本をマスターできます！

---

**次のステップ**
- [Docker基本コマンド](./02_Docker基本コマンド.md)で、実際のコマンドを学びましょう
- [Docker Compose完全ガイド](./06_Docker_Compose完全ガイド.md)で、複数のコンテナを管理する方法を学びましょう
- [MySQL学習環境構築](./04_MySQL学習環境構築.md)で、ボリュームの実践的な使い方を学びましょう

