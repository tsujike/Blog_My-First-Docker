# 【Docker詳細】Dockerのアーキテクチャを理解しよう

どうも、ケニー（tsujikenzo）です。今回は、**Dockerのアーキテクチャ**について詳しく解説します。

Dockerをより深く理解するために、Dockerがどのように動作しているかを知ることは重要です。

## Dockerのアーキテクチャ概要

Dockerは**クライアント・サーバ型のアーキテクチャ**を採用しています。

```
┌─────────────────────────────────────┐
│      Docker クライアント (docker)     │
│  ユーザーがコマンドを実行する場所      │
└──────────────┬──────────────────────┘
               │ REST API
               │ (UNIXソケット or ネットワーク)
               ↓
┌─────────────────────────────────────┐
│     Docker デーモン (dockerd)       │
│  実際の処理を行うバックグラウンド     │
│                                      │
│  ┌──────────────────────────────┐   │
│  │ イメージ管理                 │   │
│  │ - イメージの取得・保存        │   │
│  │ - イメージのビルド           │   │
│  └──────────────────────────────┘   │
│                                      │
│  ┌──────────────────────────────┐   │
│  │ コンテナ管理                 │   │
│  │ - コンテナの作成・起動・停止   │   │
│  │ - コンテナの削除             │   │
│  └──────────────────────────────┘   │
│                                      │
│  ┌──────────────────────────────┐   │
│  │ ネットワーク管理             │   │
│  │ - ネットワークの作成         │   │
│  │ - コンテナ間の通信           │   │
│  └──────────────────────────────┘   │
│                                      │
│  ┌──────────────────────────────┐   │
│  │ ボリューム管理               │   │
│  │ - ボリュームの作成・削除      │   │
│  │ - データの永続化             │   │
│  └──────────────────────────────┘   │
└─────────────────────────────────────┘
```

## Dockerデーモン（dockerd）

### Dockerデーモンとは？

Dockerデーモンは、Docker APIリクエストを受け付け、イメージ、コンテナ、ネットワーク、ボリュームといったDockerオブジェクトを管理する**バックグラウンドプロセス**です。

### Dockerデーモンの役割

#### 1. イメージの管理
- イメージの取得（pull）
- イメージの保存
- イメージのビルド（build）
- イメージの削除

#### 2. コンテナの管理
- コンテナの作成（create）
- コンテナの起動（start）
- コンテナの停止（stop）
- コンテナの削除（rm）
- コンテナの状態監視

#### 3. ネットワークの管理
- ネットワークの作成
- コンテナ間の通信設定
- ポートマッピング
- DNS設定

#### 4. ボリュームの管理
- ボリュームの作成
- ボリュームのマウント
- ボリュームの削除
- データの永続化

#### 5. 他のデーモンとの通信
- Docker Swarmクラスタの管理
- 複数のDockerデーモン間の通信

### Dockerデーモンの確認

```bash
# Dockerデーモンの状態を確認
docker info

# Dockerデーモンのバージョン確認
docker version
```

## Dockerクライアント（docker）

### Dockerクライアントとは？

Dockerクライアントは、Dockerとのやりとりを行うために、たいていのユーザが利用する**コマンドラインツール**です。

### Dockerクライアントの役割

#### 1. コマンドの実行
ユーザーがコマンドを実行すると、DockerクライアントがDockerデーモンに処理を依頼します。

**例：**
```bash
docker run nginx
```

このコマンドを実行すると：
1. Dockerクライアントがコマンドを受け取る
2. Dockerデーモンに「nginxコンテナを起動して」と伝える
3. Dockerデーモンが実際の処理を行う
4. 結果をDockerクライアントに返す
5. ユーザーに結果を表示

#### 2. Docker APIの利用
Dockerクライアントは、Docker APIを利用してDockerデーモンと通信します。

#### 3. 複数のデーモンとの通信
Dockerクライアントは、複数のDockerデーモンと通信することができます。

**例：**
```bash
# リモートのDockerデーモンに接続
docker -H tcp://192.168.1.100:2375 ps
```

### Dockerクライアントの確認

```bash
# Dockerクライアントのバージョン確認
docker --version

# Dockerクライアントとデーモンの両方のバージョン確認
docker version
```

## Docker Desktop

### Docker Desktopとは？

Docker Desktopは、Mac、Windows、Linux環境へ簡単にインストールできる**統合アプリケーション**です。

### Docker Desktopに含まれるもの

#### 1. Dockerデーモン（dockerd）
- バックグラウンドで動作するDockerエンジン

#### 2. Dockerクライアント（docker）
- コマンドラインツール

#### 3. Docker Compose
- 複数のコンテナを管理するツール

#### 4. Docker Content Trust
- イメージの署名と検証

#### 5. Kubernetes
- コンテナオーケストレーションツール（オプション）

#### 6. Credential Helper（認証情報ヘルパー）
- レジストリへの認証情報を安全に保存

### WindowsでのDocker Desktop

Windowsでは、Docker Desktopは**WSL2（Windows Subsystem for Linux）**上で動作します。

**動作の流れ：**
1. Docker Desktopを起動
2. WSL2上でDockerデーモンが起動
3. Windows上でDockerクライアントが動作
4. DockerクライアントがWSL2上のDockerデーモンと通信

### Docker Desktopの確認

```bash
# Docker Desktopが起動しているか確認
docker ps

# Docker Desktopの情報を確認
docker info
```

## Dockerレジストリ（Registry）

### Dockerレジストリとは？

Dockerレジストリは、**Dockerイメージを保管する場所**です。

### 主なレジストリ

#### 1. Docker Hub（公開レジストリ）
- URL: https://hub.docker.com/
- 最も有名で、多くの公式イメージが公開されている
- 無料で使用できる
- DockerはデフォルトでDocker Hubのイメージを探すよう設定されている

#### 2. プライベートレジストリ
- 独自にプライベートレジストリを運用することも可能
- 企業内での利用に適している

#### 3. その他のレジストリ
- **GitHub Container Registry**（GitHubが提供）
- **Amazon ECR**（AWSが提供）
- **Google Container Registry**（GCPが提供）
- **Azure Container Registry**（Azureが提供）

### レジストリの操作

#### イメージの取得（pull）

```bash
# Docker Hubからイメージを取得
docker pull nginx:latest

# デフォルトでDocker Hubから取得される
docker pull nginx
```

#### イメージの送信（push）

```bash
# イメージをレジストリに送信
docker push username/my-image:tag
```

### レジストリの設定

```bash
# レジストリにログイン
docker login

# レジストリからログアウト
docker logout

# 特定のレジストリにログイン
docker login registry.example.com
```

## Dockerクライアントとデーモンの通信

### 通信方法

Dockerクライアントとデーモンは、**REST API**を使って通信します。

#### 1. UNIXソケット（同一システム）
- 同一システム上で動かす場合のデフォルト
- Windows: `\\.\pipe\docker_engine`
- Linux/Mac: `/var/run/docker.sock`

#### 2. ネットワークインターフェース（リモート）
- 別のシステム上のDockerデーモンにアクセスする場合
- TCP接続を使用

**例：**
```bash
# リモートのDockerデーモンに接続
docker -H tcp://192.168.1.100:2375 ps
```

### 通信の流れ

```
1. ユーザーがコマンドを実行
   ↓
2. Dockerクライアントがコマンドを受け取る
   ↓
3. DockerクライアントがREST APIでDockerデーモンにリクエストを送信
   ↓
4. Dockerデーモンがリクエストを受け取り、処理を実行
   ↓
5. Dockerデーモンが結果をREST APIで返す
   ↓
6. Dockerクライアントが結果を受け取り、ユーザーに表示
```

## Docker Composeとの関係

### Docker Composeとは？

Docker Composeは、**複数のコンテナで構成されるアプリケーションを操作するツール**です。

### Docker Composeの役割

- `docker-compose.yml`ファイルを読み込む
- 複数のコンテナを一括で管理
- サービス間の依存関係を管理
- ネットワークとボリュームを自動的に作成

### Docker ComposeとDockerデーモン

Docker Composeも、内部的にはDocker APIを使ってDockerデーモンと通信します。

```bash
# Docker Composeでコンテナを起動
docker-compose up -d

# 内部的には以下のような処理が行われます：
# 1. docker-compose.ymlを読み込む
# 2. Docker APIを使ってDockerデーモンにコンテナを作成・起動を依頼
# 3. Dockerデーモンが実際の処理を行う
```

## まとめ

- Dockerは**クライアント・サーバ型のアーキテクチャ**を採用している
- **Dockerデーモン（dockerd）**：バックグラウンドで動作し、イメージ、コンテナ、ネットワーク、ボリュームを管理
- **Dockerクライアント（docker）**：ユーザーがコマンドを実行するインターフェース
- **Docker Desktop**：Mac、Windows、Linux向けの統合アプリケーション
- **Dockerレジストリ**：Dockerイメージを保管する場所（Docker Hubがデフォルト）
- Dockerクライアントとデーモンは**REST API**を使って通信する
- 同一システム上でも、別のシステム上でも通信可能

Dockerのアーキテクチャを理解することで、Dockerがどのように動作しているかを深く理解できます！

---

**参考リンク**
- [Docker公式ドキュメント（日本語）](https://docs.docker.jp/)
- [Docker概要（公式）](https://docs.docker.jp/get-started/overview/)
- [Docker Desktop](https://www.docker.com/products/docker-desktop/)

