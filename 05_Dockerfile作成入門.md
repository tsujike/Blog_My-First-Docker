# 【Docker実践】Dockerfileを作成して、オリジナルイメージを作ろう！

どうも、ケニー（tsujikenzo）です。今回は、**Dockerfileを作成して、オリジナルのDockerイメージを作成**してみましょう。

これまで、既存のイメージ（nginx、WordPress、MySQLなど）を使ってきましたが、自分でカスタマイズしたイメージを作成できるようになると、さらに便利になります。

## この記事で学ぶこと

- Dockerfileの書き方
- オリジナルイメージの作成
- 簡単なWebアプリケーションをDocker化
- ベストプラクティス

## Dockerfileとは？

Dockerfileは、**Dockerイメージを作成するためのレシピ**です。どのOSを使うか、どのソフトウェアをインストールするか、どのファイルをコピーするかを記述します。

## 簡単な例：Hello Worldアプリケーション

### ステップ1：プロジェクトフォルダを作成

```bash
mkdir hello-docker
cd hello-docker
```

### ステップ2：簡単なHTMLファイルを作成

`index.html` を作成：

```html
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hello Docker!</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .container {
            text-align: center;
        }
        h1 {
            font-size: 3em;
            margin-bottom: 20px;
        }
        p {
            font-size: 1.2em;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Hello Docker! 🐳</h1>
        <p>Dockerで動いています！</p>
    </div>
</body>
</html>
```

### ステップ3：Dockerfileを作成

`Dockerfile` を作成（拡張子なし）：

```dockerfile
# ベースイメージを指定（nginxを使用）
FROM nginx:alpine

# HTMLファイルをコンテナにコピー
COPY index.html /usr/share/nginx/html/

# ポート80を公開
EXPOSE 80

# nginxを起動（nginxイメージには既に設定されているが、明示的に記述）
CMD ["nginx", "-g", "daemon off;"]
```

### Dockerfileの説明

#### FROM
ベースとなるイメージを指定します。
```dockerfile
FROM nginx:alpine
```
- `nginx` : nginxのイメージ
- `alpine` : 軽量なLinuxディストリビューション

#### COPY
ホストのファイルをコンテナにコピーします。
```dockerfile
COPY index.html /usr/share/nginx/html/
```
- 左側：ホストのファイル
- 右側：コンテナ内のパス

#### EXPOSE
コンテナが使用するポートを指定します（実際には公開されません。ドキュメント的な意味）。
```dockerfile
EXPOSE 80
```

#### CMD
コンテナ起動時に実行するコマンドを指定します。
```dockerfile
CMD ["nginx", "-g", "daemon off;"]
```

### ステップ4：イメージをビルド

```bash
docker build -t hello-docker .
```

- `-t hello-docker` : イメージに名前を付ける
- `.` : カレントディレクトリのDockerfileを使用

### ステップ5：コンテナを起動

```bash
docker run -d -p 8080:80 --name my-hello hello-docker
```

### ステップ6：動作確認

ブラウザで以下のURLにアクセス：
```
http://localhost:8080
```

「Hello Docker! 🐳」と表示されれば成功です！

## Pythonアプリケーションの例

### ステップ1：プロジェクトフォルダを作成

```bash
mkdir python-docker
cd python-docker
```

### ステップ2：Pythonアプリケーションを作成

`app.py` を作成：

```python
from flask import Flask

app = Flask(__name__)

@app.route('/')
def hello():
    return '''
    <html>
        <head>
            <title>Python Docker App</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    margin: 0;
                    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
                    color: white;
                }
                .container {
                    text-align: center;
                }
                h1 {
                    font-size: 3em;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h1>Hello from Python! 🐍</h1>
                <p>FlaskアプリケーションがDockerで動いています！</p>
            </div>
        </body>
    </html>
    '''

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
```

### ステップ3：requirements.txtを作成

`requirements.txt` を作成：

```
Flask==3.0.0
```

### ステップ4：Dockerfileを作成

`Dockerfile` を作成：

```dockerfile
# Pythonのベースイメージを使用
FROM python:3.11-slim

# 作業ディレクトリを設定
WORKDIR /app

# requirements.txtをコピー
COPY requirements.txt .

# 依存関係をインストール
RUN pip install --no-cache-dir -r requirements.txt

# アプリケーションファイルをコピー
COPY app.py .

# ポート5000を公開
EXPOSE 5000

# アプリケーションを起動
CMD ["python", "app.py"]
```

### ステップ5：イメージをビルド

```bash
docker build -t python-docker .
```

### ステップ6：コンテナを起動

```bash
docker run -d -p 5000:5000 --name my-python-app python-docker
```

### ステップ7：動作確認

ブラウザで以下のURLにアクセス：
```
http://localhost:5000
```

## Dockerfileの主な命令

### FROM
ベースイメージを指定
```dockerfile
FROM ubuntu:20.04
FROM python:3.11
FROM node:18
```

### WORKDIR
作業ディレクトリを設定
```dockerfile
WORKDIR /app
```

### RUN
コマンドを実行（イメージビルド時に実行）
```dockerfile
RUN apt-get update
RUN pip install flask
```

### COPY / ADD
ファイルをコピー
```dockerfile
COPY app.py /app/
COPY . /app/
```

**COPYとADDの違い：**
- `COPY` : 単純にファイルをコピー
- `ADD` : URLからのダウンロードや圧縮ファイルの展開も可能（通常はCOPYを使う）

### ENV
環境変数を設定
```dockerfile
ENV PYTHONUNBUFFERED=1
ENV APP_NAME=myapp
```

### EXPOSE
ポートを公開（ドキュメント的な意味）
```dockerfile
EXPOSE 80
EXPOSE 5000
```

### CMD / ENTRYPOINT
コンテナ起動時に実行するコマンド
```dockerfile
CMD ["python", "app.py"]
ENTRYPOINT ["python"]
```

**CMDとENTRYPOINTの違い：**
- `CMD` : 上書き可能
- `ENTRYPOINT` : 上書き不可（常に実行される）

## ベストプラクティス

### 1. 軽量なベースイメージを使用

```dockerfile
# 良い例
FROM python:3.11-slim

# 避けるべき例
FROM python:3.11  # より重い
```

### 2. レイヤーを効率的に配置

```dockerfile
# 良い例：変更頻度の低いものを先に
COPY requirements.txt .
RUN pip install -r requirements.txt
COPY app.py .

# 悪い例：変更頻度の高いものを先に
COPY app.py .
COPY requirements.txt .
RUN pip install -r requirements.txt
```

### 3. 不要なファイルをコピーしない

`.dockerignore` ファイルを作成：

```
node_modules
.git
.env
*.log
```

### 4. キャッシュを活用

```dockerfile
# 依存関係のインストールを先に（変更頻度が低い）
COPY requirements.txt .
RUN pip install -r requirements.txt

# アプリケーションコードは後（変更頻度が高い）
COPY app.py .
```

### 5. セキュリティを考慮

```dockerfile
# rootユーザーで実行しない
RUN useradd -m appuser
USER appuser
```

## .dockerignoreファイルの作成

`.dockerignore` を作成して、不要なファイルをビルドコンテキストから除外：

```
node_modules
.git
.gitignore
.env
*.log
.DS_Store
__pycache__
*.pyc
```

## まとめ

- Dockerfileは、Dockerイメージを作成するためのレシピ
- `FROM`、`COPY`、`RUN`、`CMD`などの命令を組み合わせて作成
- 軽量なベースイメージを使用し、レイヤーを効率的に配置する
- `.dockerignore`で不要なファイルを除外する

次回は、実際のアプリケーションをDocker化して、本番環境にデプロイする方法を学びましょう！

---

**練習問題**
1. 簡単なHTMLファイルを使って、nginxベースのイメージを作成してみましょう
2. PythonアプリケーションをDocker化してみましょう
3. `.dockerignore`ファイルを作成して、不要なファイルを除外してみましょう

**参考リンク**
- [Dockerfile公式リファレンス](https://docs.docker.com/engine/reference/builder/)
- [Docker Hub](https://hub.docker.com/) - 様々なベースイメージが公開されています

