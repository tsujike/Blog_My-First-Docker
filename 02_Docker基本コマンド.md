# 【Docker入門】基本コマンドを覚えよう！実践編

どうも、ケニー（tsujikenzo）です。前回はDockerの基礎概念について学びました。今回は、**実際に使うDockerの基本コマンド**を覚えていきましょう。

## まずは用語を整理（コンテナとイメージ）

前回は「アプリ + 実行環境」をひとつにまとめたものを**コンテナ**と説明しました。  
より正確には、**コンテナは“起動して動いている状態”**です。

では、その元になる設計図は何か？それが **イメージ（Image）** です。  
**イメージとは、アプリを動かすのに必要なファイル一式を読み取り専用でまとめたもの**です。

この回では、**イメージを取得して、コンテナを起動する**という流れをコマンドで体験していきます。

## この記事で学ぶこと

- Dockerの基本コマンド
- イメージの取得と確認
- コンテナの起動・停止・削除
- コンテナの状態確認

## 第2話：実際にDockerコマンドを打ってみよう

この章は、**「とにかく一度動かす」**ことが目的です。  
最短で動作確認できる `hello-world` を使って、Dockerの一連の流れを体験します。

### 最初にDocker Desktopをインストール（Windows 11）

1. Docker Desktop をダウンロード  
   https://www.docker.com/products/docker-desktop/
2. インストーラーを実行  
   - 「Use WSL 2 instead of Hyper-V」にチェック（推奨）
3. 再起動
4. Docker Desktop を起動（初回は利用規約に同意）
5. 動作確認  
   ```bash
   docker --version
   ```
   ※ ブログのハンズオンでは、**Docker Desktop が起動している状態**を前提に進めます。  
   ここから先のコマンドは、**ターミナルで実行する**という意味です。  
   Windows なら **PowerShell** や **Windows Terminal** で同じコマンドが使えます。

**うまくいかない時の代表例：**
- WSL2 が無効 → Windows の機能で「Windows Subsystem for Linux」を有効化
- 仮想化が無効 → BIOSで VT-x/AMD-V を有効化

### ステップ0：Dockerが動くか確認

```bash
docker --version
```

バージョンが表示されればOKです。

### ステップ1：最小イメージを取得してコンテナを起動

```bash
docker run hello-world
```

初回はイメージが自動でダウンロードされ、その後コンテナが起動します。  
最後に「Hello from Docker!」が表示されます。

### ステップ2：イメージが増えたか確認

```bash
docker images
```

`hello-world` のイメージが一覧に出ていれば成功です。

### ステップ3：コンテナの履歴を確認

```bash
docker ps -a
```

`hello-world` のコンテナが「Exited」で表示されます。

### ステップ4：Docker Desktopで確認（視覚的に見る）

Docker Desktop の **Containers** 画面を開くと、`hello-world` が一覧に表示されます。  
コマンドの結果と照らし合わせると、理解が一気に進みます。

### ステップ5：後片付け（任意）

`hello-world` は実行が終わると自動で停止するため、**「止める」操作は不要**です。  
ただし、ディスクを汚したくない場合は削除しておきましょう。

```bash
# コンテナを削除
docker rm <コンテナID>

# イメージを削除
docker rmi hello-world
```

これでディスクを汚さずに、実行体験だけできます。

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

