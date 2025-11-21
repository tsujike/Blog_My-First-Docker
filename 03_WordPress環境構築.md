# 【Docker実践】WordPress環境をDockerで構築しよう！

どうも、ケニー（tsujikenzo）です。今回は、**Dockerを使ってWordPress環境を構築**してみましょう。

WordPressを動かすには、通常以下の環境が必要です：
- Webサーバー（Apache/Nginx）
- PHP
- データベース（MySQL/MariaDB）

これらを手動でインストール・設定するのは大変ですが、Dockerを使えば簡単に構築できます！

## この記事で学ぶこと

- Docker Composeの基礎
- WordPress環境の構築
- データベースとの連携
- 実際にWordPressを動かす

## Docker Composeとは？

複数のコンテナを連携させて動かすためのツールです。WordPressのように、Webサーバーとデータベースを同時に動かす場合に便利です。

### docker-compose.ymlファイル

複数のコンテナの設定を1つのファイルに記述できます。

## WordPress環境を構築する手順

### ステップ1：プロジェクトフォルダを作成

```bash
mkdir wordpress-docker
cd wordpress-docker
```

### ステップ2：docker-compose.ymlファイルを作成

プロジェクトフォルダ内に `docker-compose.yml` というファイルを作成します。

```yaml
version: '3.8'

services:
  # データベース（MySQL）
  db:
    image: mysql:8.0
    container_name: wordpress_db
    restart: always
    environment:
      MYSQL_DATABASE: wordpress
      MYSQL_USER: wordpress
      MYSQL_PASSWORD: wordpress
      MYSQL_ROOT_PASSWORD: rootpassword
    volumes:
      - db_data:/var/lib/mysql

  # WordPress
  wordpress:
    image: wordpress:latest
    container_name: wordpress_app
    restart: always
    ports:
      - "8080:80"
    environment:
      WORDPRESS_DB_HOST: db
      WORDPRESS_DB_USER: wordpress
      WORDPRESS_DB_PASSWORD: wordpress
      WORDPRESS_DB_NAME: wordpress
    volumes:
      - wordpress_data:/var/www/html
    depends_on:
      - db

volumes:
  db_data:
  wordpress_data:
```

### ファイルの説明

#### services（サービス）
- **db** : MySQLデータベース
- **wordpress** : WordPressアプリケーション

#### 各サービスの設定

**db（データベース）:**
- `image: mysql:8.0` : MySQL 8.0のイメージを使用
- `environment` : データベースの設定（ユーザー名、パスワードなど）
- `volumes` : データを永続化（コンテナを削除してもデータが残る）

**wordpress:**
- `ports: "8080:80"` : ホストの8080番ポートをコンテナの80番ポートにマッピング
- `environment` : WordPressのデータベース接続設定
- `depends_on` : dbサービスが起動してから起動する

**volumes:**
- データを永続化するための領域

### ステップ3：コンテナを起動

```bash
docker-compose up -d
```

`-d` オプションでバックグラウンド実行します。

初回実行時は、イメージのダウンロードに時間がかかります。

### ステップ4：動作確認

ブラウザで以下のURLにアクセス：
```
http://localhost:8080
```

WordPressのインストール画面が表示されれば成功です！

### ステップ5：WordPressの初期設定

1. 言語を選択（日本語を選択）
2. 「さあ、始めましょう！」をクリック
3. データベース接続情報を入力
   - データベース名: `wordpress`
   - ユーザー名: `wordpress`
   - パスワード: `wordpress`
   - データベースのホスト: `db`
   - テーブル接頭辞: `wp_`（デフォルトのまま）
4. 「送信」をクリック
5. WordPressの基本情報を入力
   - サイトのタイトル
   - ユーザー名
   - パスワード
   - メールアドレス

### ステップ6：コンテナの状態確認

```bash
docker-compose ps
```

実行中のコンテナが表示されます。

### ステップ7：ログの確認

```bash
docker-compose logs
```

特定のサービスのログを確認：
```bash
docker-compose logs wordpress
docker-compose logs db
```

### ステップ8：コンテナの停止

```bash
docker-compose stop
```

### ステップ9：コンテナの再開

```bash
docker-compose start
```

### ステップ10：コンテナの削除

```bash
docker-compose down
```

データボリュームも削除する場合：
```bash
docker-compose down -v
```

## よくあるトラブルシューティング

### ポート8080が既に使用されている

**対処法1：** 別のポートを使用する

`docker-compose.yml` の `ports` を変更：
```yaml
ports:
  - "8081:80"  # 8081番ポートに変更
```

**対処法2：** 既存のコンテナを停止・削除

```bash
docker ps
docker stop コンテナ名
docker rm コンテナ名
```

### WordPressに接続できない

**確認事項：**
1. コンテナが起動しているか確認
   ```bash
   docker-compose ps
   ```

2. ログを確認してエラーがないか確認
   ```bash
   docker-compose logs
   ```

3. データベースが起動するまで待つ（初回起動時は時間がかかることがあります）

### データが消えた

`docker-compose down -v` を実行すると、ボリュームも削除されるため、データが消えます。

通常の `docker-compose down` では、ボリュームは残ります。

## カスタマイズ：PHPの設定を変更する

WordPressのPHP設定を変更したい場合、`docker-compose.yml` に以下を追加：

```yaml
wordpress:
  # ... 既存の設定 ...
  volumes:
    - wordpress_data:/var/www/html
    - ./php.ini:/usr/local/etc/php/conf.d/custom.ini  # PHP設定ファイル
```

`php.ini` ファイルを作成して、設定を記述します。

## まとめ

- Docker Composeを使えば、複数のコンテナを簡単に連携できる
- WordPress環境を数分で構築できる
- `docker-compose up -d` で起動、`docker-compose down` で停止・削除
- ボリュームを使うことで、データを永続化できる

次回は、データベース（MySQL）を単体で動かして、SQLの学習環境を構築してみましょう！

---

**参考リンク**
- [WordPress公式Dockerイメージ](https://hub.docker.com/_/wordpress)
- [MySQL公式Dockerイメージ](https://hub.docker.com/_/mysql)
- [Docker Compose公式ドキュメント](https://docs.docker.com/compose/)

