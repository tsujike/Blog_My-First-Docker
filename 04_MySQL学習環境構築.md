# 【Docker実践】MySQL学習環境をDockerで構築しよう！

どうも、ケニー（tsujikenzo）です。今回は、**Dockerを使ってMySQLの学習環境を構築**してみましょう。

SQLを学ぶには、データベース環境が必要です。Dockerを使えば、簡単にMySQL環境を作成できます！

## この記事で学ぶこと

- MySQLコンテナの起動方法
- データベースへの接続方法
- 基本的なSQL操作
- データの永続化

## MySQLコンテナを起動する

### ステップ1：MySQLイメージを取得

```bash
docker pull mysql:8.0
```

### ステップ2：MySQLコンテナを起動

```bash
docker run -d \
  --name mysql-learning \
  -e MYSQL_ROOT_PASSWORD=rootpassword \
  -e MYSQL_DATABASE=testdb \
  -e MYSQL_USER=testuser \
  -e MYSQL_PASSWORD=testpass \
  -p 3306:3306 \
  mysql:8.0
```

**パラメータの説明：**
- `-d` : バックグラウンドで実行
- `--name mysql-learning` : コンテナに名前を付ける
- `-e MYSQL_ROOT_PASSWORD` : rootユーザーのパスワード
- `-e MYSQL_DATABASE` : 作成するデータベース名
- `-e MYSQL_USER` : 作成するユーザー名
- `-e MYSQL_PASSWORD` : ユーザーのパスワード
- `-p 3306:3306` : ポートのマッピング（MySQLのデフォルトポート）

### ステップ3：コンテナの状態確認

```bash
docker ps
```

`mysql-learning` が表示されていれば成功です。

## MySQLに接続する方法

### 方法1：コンテナ内でMySQLコマンドを使用

```bash
docker exec -it mysql-learning mysql -u root -p
```

パスワードを聞かれたら、`rootpassword` を入力します。

### 方法2：作成したユーザーで接続

```bash
docker exec -it mysql-learning mysql -u testuser -p
```

パスワードは `testpass` です。

### 方法3：外部ツールから接続

以下の情報で接続できます：
- **ホスト**: `localhost`
- **ポート**: `3306`
- **ユーザー名**: `testuser` または `root`
- **パスワード**: `testpass` または `rootpassword`
- **データベース**: `testdb`

**おすすめツール：**
- MySQL Workbench（公式ツール）
- DBeaver（無料、多機能）
- phpMyAdmin（Webベース）

## 基本的なSQL操作

MySQLに接続したら、以下のコマンドを試してみましょう。

### データベースの確認

```sql
SHOW DATABASES;
```

### データベースの選択

```sql
USE testdb;
```

### テーブルの作成

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### データの挿入

```sql
INSERT INTO users (name, email) VALUES
('山田太郎', 'yamada@example.com'),
('佐藤花子', 'sato@example.com'),
('鈴木一郎', 'suzuki@example.com');
```

### データの取得

```sql
-- 全件取得
SELECT * FROM users;

-- 条件を指定して取得
SELECT * FROM users WHERE name LIKE '%山田%';

-- 件数を取得
SELECT COUNT(*) FROM users;
```

### データの更新

```sql
UPDATE users SET email = 'yamada-new@example.com' WHERE id = 1;
```

### データの削除

```sql
DELETE FROM users WHERE id = 3;
```

### テーブルの削除

```sql
DROP TABLE users;
```

## データの永続化（ボリュームを使用）

コンテナを削除してもデータを残したい場合は、ボリュームを使用します。

### ボリューム付きでコンテナを起動

```bash
docker run -d \
  --name mysql-learning \
  -e MYSQL_ROOT_PASSWORD=rootpassword \
  -e MYSQL_DATABASE=testdb \
  -e MYSQL_USER=testuser \
  -e MYSQL_PASSWORD=testpass \
  -p 3306:3306 \
  -v mysql_data:/var/lib/mysql \
  mysql:8.0
```

`-v mysql_data:/var/lib/mysql` でボリュームをマウントします。

### ボリュームの確認

```bash
docker volume ls
```

### ボリュームの削除

```bash
docker volume rm mysql_data
```

## Docker Composeを使った方法

複数のデータベースや、設定を管理しやすくするために、Docker Composeを使うこともできます。

### docker-compose.yml の作成

```yaml
version: '3.8'

services:
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

volumes:
  mysql_data:
```

### 起動と停止

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

## phpMyAdminを追加する（オプション）

WebブラウザからMySQLを操作できるツールです。

### docker-compose.yml に追加

```yaml
version: '3.8'

services:
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
      - mysql

volumes:
  mysql_data:
```

### アクセス

ブラウザで以下のURLにアクセス：
```
http://localhost:8080
```

## よくあるトラブルシューティング

### ポート3306が既に使用されている

**対処法1：** 別のポートを使用

```bash
docker run -d \
  --name mysql-learning \
  -e MYSQL_ROOT_PASSWORD=rootpassword \
  -p 3307:3306 \  # 3307番ポートに変更
  mysql:8.0
```

**対処法2：** 既存のMySQLサービスを停止

Windowsの場合、サービスからMySQLを停止できます。

### 接続できない

1. コンテナが起動しているか確認
   ```bash
   docker ps
   ```

2. ログを確認
   ```bash
   docker logs mysql-learning
   ```

3. データベースが完全に起動するまで待つ（初回起動時は時間がかかります）

### パスワードを忘れた

コンテナを削除して、新しいパスワードで再作成します。

```bash
docker stop mysql-learning
docker rm mysql-learning
# 新しいパスワードで再作成
```

## まとめ

- DockerでMySQL環境を簡単に構築できる
- `docker exec` でコンテナ内のMySQLに接続できる
- ボリュームを使うことで、データを永続化できる
- Docker Composeを使うと、設定を管理しやすい
- phpMyAdminを追加すれば、Webブラウザから操作できる

次回は、実際にアプリケーションを作成して、Dockerで動かしてみましょう！

---

**練習問題**
1. MySQLコンテナを起動して、データベースに接続してみましょう
2. テーブルを作成して、データを挿入・取得してみましょう
3. phpMyAdminを追加して、Webブラウザから操作してみましょう

**参考リンク**
- [MySQL公式Dockerイメージ](https://hub.docker.com/_/mysql)
- [phpMyAdmin公式Dockerイメージ](https://hub.docker.com/_/phpmyadmin)
- [MySQL公式ドキュメント](https://dev.mysql.com/doc/)

