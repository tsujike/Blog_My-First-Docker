# コンテナは「見える」！実際に確認してみよう

「コンテナは目に見えない」という説明は誤解を招きます。**実際には、コンテナ内のファイルシステムは完全に見えます。**

## 「コマンド」とは？

**Pythonで`python app.py`と入力するように、Dockerでも`docker ps`と入力します。**

**「コマンド」= ターミナルに入力する文字列**

詳しくは：[WHAT_IS_COMMAND.md](./WHAT_IS_COMMAND.md)を読んでください

## コンテナ内は「見える」！

### 誤解しやすい点

**よくある誤解：**
- 「コンテナは目に見えない」
- 「ファイルとして直接見ることはできない」

**実際には：**
- **コンテナ内のファイルシステムは完全に見えます**
- **コンテナ内のプロセスも見えます**
- **コンテナ内に入って操作できます**

## 実際に「見る」方法

### 1. コンテナ内のファイルを見る

**これは実際に見えます！**

```bash
# コンテナ内のファイル一覧を見る
# Pythonで「python app.py」と入力するように、ターミナルにこの文字列を入力してEnterキーを押します
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

### 2. コンテナ内のファイルの内容を見る

```bash
# コンテナ内のファイルの内容を見る
# Pythonで「python app.py」と入力するように、ターミナルにこの文字列を入力してEnterキーを押します
docker exec first-app-web cat /usr/share/nginx/html/index.html
```

**出力例：**
```
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    ...
</head>
<body>
    <h1>Hello World!</h1>
    ...
</body>
</html>
```

**確認ポイント：**
- **HTMLファイルの中身が見える**
- **ファイルの内容が完全に見える**
- **これは実際のファイル内容です！**

### 3. コンテナ内のプロセスを見る

```bash
# コンテナ内で動いているプロセスを見る
# Pythonで「python app.py」と入力するように、ターミナルにこの文字列を入力してEnterキーを押します
docker exec first-app-web ps aux
```

**出力例：**
```
PID   USER     TIME   COMMAND
1     root     0:00   nginx: master process nginx -g daemon off;
6     nginx    0:00   nginx: worker process
```

**確認ポイント：**
- **nginxプロセスが見える**
- **プロセスIDが見える**：PID 1, 6
- **これは実際に動いているプロセスです！**

### 4. コンテナ内に入って操作する

```bash
# コンテナ内に入る（シェルを起動）
# Pythonで「python app.py」と入力するように、ターミナルにこの文字列を入力してEnterキーを押します
docker exec -it first-app-web sh
```

**コンテナ内で：**
```bash
# 現在のディレクトリを確認
pwd
# → /usr/share/nginx/html

# ファイルを見る
ls -la
# → index.htmlが見える

# ファイルの内容を見る
cat index.html
# → HTMLファイルの中身が見える

# プロセスを見る
ps aux
# → nginxプロセスが見える

# ディレクトリを移動
cd /
ls -la
# → ルートディレクトリの内容が見える
```

**確認ポイント：**
- **コンテナ内に入れる**
- **ファイルシステムを操作できる**
- **これは実際のLinux環境です！**

### 5. コンテナ内のディレクトリ構造を見る

```bash
# コンテナ内のディレクトリ構造を見る
docker exec first-app-web find /usr/share/nginx/html -type f
```

**出力例：**
```
/usr/share/nginx/html/index.html
```

**確認ポイント：**
- **ディレクトリ構造が見える**
- **ファイルの場所が見える**
- **これは実際のディレクトリ構造です！**

## 視覚的に理解する

### コンテナ内のファイルシステム

```
コンテナ内（実際に見える）
├── /usr/share/nginx/html/
│   └── index.html  ← これが見える！
├── /etc/nginx/
│   └── nginx.conf  ← これも見える！
└── /var/log/nginx/
    └── access.log  ← これも見える！
```

**確認方法：**
```bash
# ファイルを見る
docker exec first-app-web ls -la /usr/share/nginx/html

# 設定ファイルを見る
docker exec first-app-web cat /etc/nginx/nginx.conf

# ログファイルを見る
docker exec first-app-web cat /var/log/nginx/access.log
```

### コンテナ内のプロセス

```
コンテナ内（実際に見える）
├── PID 1: nginx (master process)  ← これが見える！
└── PID 6: nginx (worker process)  ← これも見える！
```

**確認方法：**
```bash
docker exec first-app-web ps aux
```

## 実際に試してみる

### ステップ1：コンテナ内のファイルを見る

```bash
docker exec first-app-web ls -la /usr/share/nginx/html
```

**確認ポイント：**
- `index.html`が見える
- ファイルサイズが見える
- 作成日時が見える

### ステップ2：ファイルの内容を見る

```bash
docker exec first-app-web cat /usr/share/nginx/html/index.html
```

**確認ポイント：**
- HTMLファイルの中身が見える
- これは実際のファイル内容

### ステップ3：コンテナ内に入る

```bash
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

# 終了する
exit
```

**確認ポイント：**
- コンテナ内に入れる
- ファイルシステムを操作できる
- これは実際のLinux環境

## まとめ

### コンテナは「見える」！

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

**コンテナは「見える」！実際に確認してみましょう！**

---

**次のステップ**
- [WHERE_ARE_IMAGES.md](./WHERE_ARE_IMAGES.md)で、イメージとコンテナの関係を学びましょう
- [WHAT_HAPPENS.md](./WHAT_HAPPENS.md)で、docker-compose up -dで何が起きているかを学びましょう

