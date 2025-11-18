# 「コマンド」とは何か？Pythonの例で理解する

「Dockerコマンド」という言葉が分かりづらいかもしれません。**Pythonの例を使って説明します。**

## 「コマンド」とは？

### Pythonの例

**Pythonでアプリを動かすとき：**

```bash
python app.py
```

**これは「コマンド」です。**

**意味：**
- ターミナル（コマンドプロンプト）に入力する文字列
- `python`というプログラムに、`app.py`というファイルを渡す
- 実行すると、Pythonアプリが動く

### Dockerでも同じ

**Dockerでコンテナを確認するとき：**

```bash
docker ps
```

**これも「コマンド」です。**

**意味：**
- ターミナル（コマンドプロンプト）に入力する文字列
- `docker`というプログラムに、`ps`という指示を渡す
- 実行すると、コンテナの一覧が表示される

## もっと分かりやすく

### 「コマンド」= ターミナルに入力する文字列

**Pythonの場合：**
```
ターミナルに「python app.py」と入力
    ↓
Pythonアプリが動く
```

**Dockerの場合：**
```
ターミナルに「docker ps」と入力
    ↓
コンテナの一覧が表示される
```

### 実際の使い方

#### Pythonの例

1. **ターミナルを開く**
2. **`python app.py`と入力**
3. **Enterキーを押す**
4. **Pythonアプリが動く**

#### Dockerの例

1. **ターミナルを開く**
2. **`docker ps`と入力**
3. **Enterキーを押す**
4. **コンテナの一覧が表示される**

## よく使う「コマンド」

### Pythonでよく使うコマンド

```bash
# Pythonアプリを動かす
python app.py

# Pythonのバージョンを確認
python --version

# Pythonパッケージをインストール
pip install flask
```

### Dockerでよく使うコマンド

```bash
# コンテナの一覧を表示
docker ps

# コンテナを起動
docker-compose up -d

# コンテナを停止
docker-compose stop
```

## まとめ

**「コマンド」とは：**
- ターミナル（コマンドプロンプト）に入力する文字列
- Pythonで`python app.py`と入力するように、Dockerでも`docker ps`と入力する
- 実行すると、何かが起こる（アプリが動く、情報が表示されるなど）

**重要なポイント：**
- Pythonのコマンドと同じように使う
- ターミナルに入力して、Enterキーを押すだけ
- 難しいことはない

---

**次のステップ**
- [SEE_CONTAINER.md](./SEE_CONTAINER.md)で、実際にコマンドを使ってコンテナを確認してみましょう
- [WHAT_HAPPENS.md](./WHAT_HAPPENS.md)で、docker-compose up -dで何が起きているかを学びましょう

