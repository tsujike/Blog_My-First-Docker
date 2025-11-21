# 【Docker Compose完全ガイド】WordPress・MySQLを使いこなそう！

どうも、ケニー（tsujikenzo）です。今回は、**Docker Composeを完全に使いこなす**ためのガイドをお送りします。

WordPressやMySQLを操作するには、複数のコンテナを連携させる必要があります。Docker Composeを使えば、これを簡単に管理できます！

## この記事で学ぶこと

- Docker Composeの完全な理解
- docker-compose.ymlの詳細な書き方
- サービス間の連携方法
- ボリュームとネットワークの活用
- 実践的なWordPress + MySQL環境の構築
- トラブルシューティング

## Docker Composeとは？

Docker Composeは、**複数のコンテナを定義し、管理するためのツール**です。

### なぜDocker Composeが必要なのか？

WordPressを動かすには：
- Webサーバー（WordPress）
- データベース（MySQL）

の2つが必要です。これらを1つずつ`docker run`で起動するのは大変ですが、Docker Composeを使えば1つのコマンドで全て起動できます。

### docker-compose.ymlファイル

複数のコンテナの設定を1つのYAMLファイルに記述します。

## docker-compose.ymlの基本構造

```yaml
version: '3.8'  # Composeファイルのバージョン

services:       # サービス（コンテナ）の定義
  service1:
    # サービス1の設定
  service2:
    # サービス2の設定

volumes:        # ボリュームの定義（オプション）
  volume1:

networks:       # ネットワークの定義（オプション）
  network1:
```

## 基本的な設定項目

### 1. version（バージョン）

```yaml
version: '3.8'
```

Composeファイルのフォーマットバージョンを指定します。`3.8`が一般的です。

### 2. services（サービス）

各コンテナの設定を記述します。

#### image（イメージ）

使用するDockerイメージを指定します。

```yaml
services:
  web:
    image: nginx:latest
```

#### build（ビルド）

Dockerfileからイメージをビルドします。

```yaml
services:
  web:
    build: .
    # または
    build:
      context: .
      dockerfile: Dockerfile
```

#### container_name（コンテナ名）

コンテナに名前を付けます（オプション）。

```yaml
services:
  web:
    image: nginx:latest
    container_name: my-nginx
```

#### ports（ポートマッピング）

ホストのポートをコンテナのポートにマッピングします。

```yaml
services:
  web:
    image: nginx:latest
    ports:
      - "8080:80"        # ホスト:コンテナ
      - "8443:443"       # 複数指定可能
```

#### environment（環境変数）

環境変数を設定します。

```yaml
services:
  db:
    image: mysql:8.0
    environment:
      MYSQL_ROOT_PASSWORD: rootpass
      MYSQL_DATABASE: mydb
    # または
    environment:
      - MYSQL_ROOT_PASSWORD=rootpass
      - MYSQL_DATABASE=mydb
```

#### .envファイルを使う

環境変数を外部ファイルで管理できます。

`.env`ファイルを作成：
```
MYSQL_ROOT_PASSWORD=rootpass
MYSQL_DATABASE=mydb
```

`docker-compose.yml`で使用：
```yaml
services:
  db:
    image: mysql:8.0
    environment:
      MYSQL_ROOT_PASSWORD: ${MYSQL_ROOT_PASSWORD}
      MYSQL_DATABASE: ${MYSQL_DATABASE}
```

#### volumes（ボリューム）

データを永続化します。

```yaml
services:
  db:
    image: mysql:8.0
    volumes:
      # 名前付きボリューム
      - db_data:/var/lib/mysql
      # バインドマウント（ホストのディレクトリをマウント）
      - ./data:/var/lib/mysql
      # 読み取り専用
      - ./config:/etc/mysql/conf.d:ro
```

#### depends_on（依存関係）

他のサービスが起動してから起動します。

```yaml
services:
  web:
    image: wordpress:latest
    depends_on:
      - db
  db:
    image: mysql:8.0
```

**注意：** `depends_on`は起動順序のみを制御します。データベースが完全に準備できるまで待つには、追加の設定が必要です。

#### restart（再起動ポリシー）

コンテナの再起動ポリシーを設定します。

```yaml
services:
  web:
    image: nginx:latest
    restart: always  # always, unless-stopped, on-failure, no
```

- `always`: 常に再起動
- `unless-stopped`: 手動で停止するまで再起動
- `on-failure`: エラー時のみ再起動
- `no`: 再起動しない

#### networks（ネットワーク）

カスタムネットワークを使用します。

```yaml
services:
  web:
    image: nginx:latest
    networks:
      - frontend
  db:
    image: mysql:8.0
    networks:
      - backend

networks:
  frontend:
  backend:
```

## 実践例1：WordPress + MySQL環境

完全なWordPress環境を構築します。

### docker-compose.yml

```yaml
version: '3.8'

services:
  # データベース
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
    networks:
      - wordpress-network
    # ヘルスチェック（データベースが準備できるまで待つ）
    healthcheck:
      test: ["CMD", "mysqladmin", "ping", "-h", "localhost"]
      timeout: 20s
      retries: 10

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
      - ./wp-content:/var/www/html/wp-content  # テーマやプラグインを永続化
    depends_on:
      db:
        condition: service_healthy  # データベースが準備できるまで待つ
    networks:
      - wordpress-network

  # phpMyAdmin（オプション）
  phpmyadmin:
    image: phpmyadmin/phpmyadmin
    container_name: phpmyadmin
    restart: always
    ports:
      - "8081:80"
    environment:
      PMA_HOST: db
      PMA_USER: root
      PMA_PASSWORD: rootpassword
    depends_on:
      - db
    networks:
      - wordpress-network

volumes:
  db_data:
  wordpress_data:

networks:
  wordpress-network:
    driver: bridge
```

### 使い方

```bash
# 起動
docker-compose up -d

# 停止
docker-compose stop

# 停止＋削除
docker-compose down

# 停止＋削除（ボリュームも削除）
docker-compose down -v
```

### アクセス

- WordPress: http://localhost:8080
- phpMyAdmin: http://localhost:8081

## 実践例2：MySQL学習環境（完全版）

MySQL + phpMyAdmin + Adminerの環境を構築します。

### docker-compose.yml

```yaml
version: '3.8'

services:
  # MySQLデータベース
  mysql:
    image: mysql:8.0
    container_name: mysql-learning
    restart: always
    environment:
      MYSQL_ROOT_PASSWORD: rootpassword
      MYSQL_DATABASE: testdb
      MYSQL_USER: testuser
      MYSQL_PASSWORD: testpass
    ports:
      - "3306:3306"
    volumes:
      - mysql_data:/var/lib/mysql
      - ./init.sql:/docker-entrypoint-initdb.d/init.sql  # 初期SQLを実行
    networks:
      - db-network
    healthcheck:
      test: ["CMD", "mysqladmin", "ping", "-h", "localhost"]
      timeout: 20s
      retries: 10

  # phpMyAdmin
  phpmyadmin:
    image: phpmyadmin/phpmyadmin
    container_name: phpmyadmin
    restart: always
    ports:
      - "8080:80"
    environment:
      PMA_HOST: mysql
      PMA_USER: root
      PMA_PASSWORD: rootpassword
    depends_on:
      mysql:
        condition: service_healthy
    networks:
      - db-network

  # Adminer（別のデータベース管理ツール）
  adminer:
    image: adminer
    container_name: adminer
    restart: always
    ports:
      - "8082:8080"
    depends_on:
      - mysql
    networks:
      - db-network

volumes:
  mysql_data:

networks:
  db-network:
    driver: bridge
```

### 初期SQLファイル（init.sql）

`init.sql`を作成：

```sql
-- サンプルテーブルの作成
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- サンプルデータの挿入
INSERT INTO users (name, email) VALUES
('山田太郎', 'yamada@example.com'),
('佐藤花子', 'sato@example.com'),
('鈴木一郎', 'suzuki@example.com');
```

## Docker Composeのコマンド

### 基本的なコマンド

```bash
# コンテナを起動（バックグラウンド）
docker-compose up -d

# コンテナを起動（フォアグラウンド、ログ表示）
docker-compose up

# コンテナを停止
docker-compose stop

# コンテナを再開
docker-compose start

# コンテナを停止＋削除
docker-compose down

# コンテナを停止＋削除（ボリュームも削除）
docker-compose down -v

# コンテナの状態確認
docker-compose ps

# ログの確認
docker-compose logs

# 特定のサービスのログ
docker-compose logs wordpress

# リアルタイムでログを確認
docker-compose logs -f

# イメージを再ビルド
docker-compose build

# イメージを再ビルド（キャッシュなし）
docker-compose build --no-cache

# コンテナ内でコマンドを実行
docker-compose exec wordpress bash
docker-compose exec db mysql -u root -p
```

### 便利なコマンド

```bash
# コンテナのリソース使用状況を確認
docker-compose top

# 設定ファイルの検証
docker-compose config

# 設定ファイルの検証（設定を表示）
docker-compose config --services  # サービス一覧
docker-compose config --volumes   # ボリューム一覧

# コンテナを再起動
docker-compose restart

# 特定のサービスのみ再起動
docker-compose restart wordpress
```

## ボリュームの詳細

### 名前付きボリューム

```yaml
volumes:
  db_data:  # Dockerが管理するボリューム
```

**メリット：**
- Dockerが最適な場所に保存
- バックアップや移行が簡単

### バインドマウント

```yaml
volumes:
  - ./data:/var/lib/mysql  # ホストのディレクトリをマウント
```

**メリット：**
- ホストから直接ファイルにアクセス可能
- 開発時に便利

### ボリュームの確認

```bash
# ボリューム一覧
docker volume ls

# ボリュームの詳細
docker volume inspect プロジェクト名_db_data

# ボリュームの削除
docker volume rm プロジェクト名_db_data
```

## ネットワークの詳細

### デフォルトネットワーク

Docker Composeは自動的にネットワークを作成し、サービス名で通信できます。

```yaml
services:
  web:
    image: wordpress:latest
    # dbというサービス名でアクセス可能
    environment:
      WORDPRESS_DB_HOST: db
  db:
    image: mysql:8.0
```

### カスタムネットワーク

```yaml
services:
  web:
    networks:
      - frontend
  db:
    networks:
      - backend

networks:
  frontend:
    driver: bridge
  backend:
    driver: bridge
```

### ネットワークの確認

```bash
# ネットワーク一覧
docker network ls

# ネットワークの詳細
docker network inspect プロジェクト名_wordpress-network
```

## 環境変数の管理

### .envファイルを使う

`.env`ファイルを作成：

```
# データベース設定
MYSQL_ROOT_PASSWORD=rootpassword
MYSQL_DATABASE=wordpress
MYSQL_USER=wordpress
MYSQL_PASSWORD=wordpress

# WordPress設定
WORDPRESS_DB_HOST=db
WORDPRESS_DB_NAME=wordpress
WORDPRESS_DB_USER=wordpress
WORDPRESS_DB_PASSWORD=wordpress
```

`docker-compose.yml`で使用：

```yaml
services:
  db:
    environment:
      MYSQL_ROOT_PASSWORD: ${MYSQL_ROOT_PASSWORD}
      MYSQL_DATABASE: ${MYSQL_DATABASE}
```

### 環境変数の確認

```bash
# 環境変数を確認
docker-compose config
```

## よくあるトラブルシューティング

### 1. ポートが既に使用されている

**エラー：**
```
Error: bind: address already in use
```

**対処法：**
- `docker-compose.yml`のポート番号を変更
- 既存のコンテナを停止・削除

```bash
docker ps
docker stop コンテナ名
docker rm コンテナ名
```

### 2. データベースに接続できない

**原因：**
- データベースが完全に起動していない
- サービス名が間違っている

**対処法：**
- ヘルスチェックを追加
- `depends_on`に`condition: service_healthy`を追加
- ログを確認

```bash
docker-compose logs db
```

### 3. ボリュームの権限エラー

**エラー：**
```
Permission denied
```

**対処法：**
- ホストのディレクトリの権限を確認
- ユーザーIDを指定

```yaml
services:
  wordpress:
    user: "1000:1000"  # ユーザーID:グループID
```

### 4. コンテナが起動しない

**対処法：**
1. ログを確認
   ```bash
   docker-compose logs
   ```

2. 設定ファイルを検証
   ```bash
   docker-compose config
   ```

3. イメージを再取得
   ```bash
   docker-compose pull
   ```

### 5. データが消えた

**原因：**
- `docker-compose down -v`を実行した
- ボリュームがマウントされていない

**対処法：**
- ボリュームを確認
  ```bash
  docker volume ls
  ```
- バックアップを取る習慣をつける

## ベストプラクティス

### 1. プロジェクトごとにフォルダを作成

```
project1/
  docker-compose.yml
  .env

project2/
  docker-compose.yml
  .env
```

### 2. .envファイルで機密情報を管理

`.env`ファイルを`.gitignore`に追加：
```
.env
```

### 3. ヘルスチェックを追加

データベースなど、準備に時間がかかるサービスにはヘルスチェックを追加：

```yaml
healthcheck:
  test: ["CMD", "mysqladmin", "ping", "-h", "localhost"]
  timeout: 20s
  retries: 10
```

### 4. 適切な再起動ポリシーを設定

```yaml
restart: always  # 本番環境
restart: unless-stopped  # 開発環境
```

### 5. ボリュームでデータを永続化

```yaml
volumes:
  - db_data:/var/lib/mysql
```

## まとめ

- Docker Composeで複数のコンテナを簡単に管理できる
- `docker-compose.yml`で全ての設定を記述できる
- ボリュームでデータを永続化できる
- ネットワークでサービス間を連携できる
- 環境変数で設定を管理できる

WordPressやMySQLを使いこなすには、Docker Composeの理解が不可欠です。このガイドを参考に、実際に手を動かしながら学んでいきましょう！

---

**練習問題**
1. WordPress + MySQL環境を構築してみましょう
2. phpMyAdminを追加してみましょう
3. .envファイルで環境変数を管理してみましょう
4. ヘルスチェックを追加してみましょう

**参考リンク**
- [Docker Compose公式ドキュメント](https://docs.docker.com/compose/)
- [Composeファイルリファレンス](https://docs.docker.com/compose/compose-file/)
- [WordPress公式Dockerイメージ](https://hub.docker.com/_/wordpress)
- [MySQL公式Dockerイメージ](https://hub.docker.com/_/mysql)

