# 「だから何だ？」- このサンプルで学べること

WEBページが起動したけど、**だから何だ？**という疑問に答えます。

## このページが表示されているということは

### 1. アプリが実際に動いている

**見えているもの：**
- HTMLページが表示されている

**見えていないが、実際に起きていること：**
- HTMLファイル（アプリ）がコンテナ内で動いている
- Webサーバー（nginx）がコンテナ内で動いている
- Linux（OS）がコンテナ内で動いている

**確認方法：**
```bash
# コンテナが動いているか確認
docker ps

# コンテナ内のファイルを確認
docker exec first-app-web ls /usr/share/nginx/html

# コンテナ内でコマンドを実行
docker exec first-app-web uname -a  # Linuxと表示される
```

### 2. 環境ごとアプリを運んでいる

**あなたのPC：**
- Windows（おそらく）

**コンテナ内：**
- Linux（OS）
- nginx（Webサーバー）
- HTMLファイル（アプリ）

**重要なポイント：**
- あなたのPCはWindowsでも、アプリはLinux上で動いている
- これが「環境ごとアプリを運ぶ」ということ
- Macでも、Linuxでも、同じように動く

**確認方法：**
```bash
# コンテナ内のOSを確認
docker exec first-app-web cat /etc/os-release

# コンテナ内のプロセスを確認
docker exec first-app-web ps aux
```

### 3. 従来の問題が解決されている

#### 問題1：「Macで動いたのに、Windowsで動かない」

**従来の方法：**
- MacでHTMLファイルを作成
- Windowsに移行する際、Webサーバーをインストール
- 設定ファイルを調整
- 環境の違いでエラーが発生...

**Dockerを使う方法：**
- MacでDockerコンテナを作成
- Windowsで同じコンテナを実行
- **そのまま動く！**

**確認方法：**
1. このHTMLファイルをMacで作成
2. Windowsで同じコンテナを実行
3. 同じように動くことを確認

#### 問題2：「Webサーバーをインストールする必要がある」

**従来の方法：**
- nginxをインストール
- 設定ファイルを編集
- サービスを起動
- 時間がかかる...

**Dockerを使う方法：**
- `docker-compose up -d`を実行
- **一瞬で完了！**

**確認方法：**
```bash
# コンテナを停止
docker-compose stop

# コンテナを削除
docker-compose down

# 再起動（一瞬で完了）
docker-compose up -d
```

#### 問題3：「環境構築に時間がかかる」

**従来の方法：**
- OSのセットアップ
- Webサーバーのインストール
- 設定ファイルの編集
- 1時間以上かかる...

**Dockerを使う方法：**
- `docker-compose up -d`を実行
- **数秒で完了！**

### 4. 実際に確認できる

#### コンテナが動いていることを確認

```bash
docker ps
```

**出力例：**
```
CONTAINER ID   IMAGE                    COMMAND                  CREATED         STATUS         PORTS                  NAMES
abc123def456   first-app_web:latest     "/docker-entrypoint..."   5 seconds ago   Up 4 seconds   0.0.0.0:8082->80/tcp   first-app-web
```

**各項目の意味：**
- **CONTAINER ID**: `abc123def456` - コンテナの識別子
- **IMAGE**: `first-app_web:latest` - 元のイメージ名
- **COMMAND**: `"/docker-entrypoint..."` - 起動コマンド（nginxを起動）
- **CREATED**: `5 seconds ago` - 5秒前に作成された
- **STATUS**: `Up 4 seconds` - 実行中（4秒間動いている）
- **PORTS**: `0.0.0.0:8082->80/tcp` - ホストの8082番ポートがコンテナの80番ポートにマッピング
- **NAMES**: `first-app-web` - コンテナの名前

**確認ポイント：**
- コンテナが動いている（STATUS: Up）
- ポート8082でアクセス可能（PORTS: 0.0.0.0:8082->80/tcp）

**詳しくは：[docker psの見方](../../10_docker_psの見方.md)を読んでください**

#### コンテナ内のファイルを確認

```bash
docker exec first-app-web ls -la /usr/share/nginx/html
```

**出力例：**
```
-rw-r--r-- 1 root root 1234 Jan 1 12:00 index.html
```

**確認ポイント：**
- HTMLファイルがコンテナ内にある
- コンテナ内のファイルシステムが見える

#### コンテナ内のOSを確認

```bash
docker exec first-app-web cat /etc/os-release
```

**出力例：**
```
NAME="Alpine Linux"
ID=alpine
VERSION_ID=3.18.0
```

**確認ポイント：**
- コンテナ内はLinux（Alpine Linux）
- あなたのPCはWindowsでも、コンテナ内はLinux

#### コンテナ内のプロセスを確認

```bash
docker exec first-app-web ps aux
```

**出力例：**
```
PID   USER     TIME   COMMAND
1     root     0:00   nginx: master process nginx -g daemon off;
6     nginx    0:00   nginx: worker process
```

**確認ポイント：**
- nginxプロセスが動いている
- コンテナ内で実際にプロセスが実行されている

## 実験：環境の違いを体験する

### 実験1：ファイルを編集してみる

1. `index.html`を編集
2. ブラウザをリロード（F5キー）
3. 変更が反映される

**確認ポイント：**
- ファイルがホスト（あなたのPC）上にある
- コンテナ内でも見える（マウントされている）
- 変更が即座に反映される

### 実験2：コンテナを停止してみる

```bash
docker-compose stop
```

**確認ポイント：**
- ブラウザでアクセスできなくなる
- コンテナが停止している

```bash
docker-compose start
```

**確認ポイント：**
- ブラウザで再度アクセスできる
- コンテナが再開している

### 実験3：コンテナを削除してみる

```bash
docker-compose down
```

**確認ポイント：**
- コンテナが削除される
- ブラウザでアクセスできなくなる

```bash
docker-compose up -d
```

**確認ポイント：**
- コンテナが再作成される
- ブラウザで再度アクセスできる
- **HTMLファイルは残っている**（ホスト上にあるから）

## まとめ：「だから何だ？」

### このサンプルで学べること

1. **アプリが動くために必要なものが見える**
   - アプリケーション（HTMLファイル）
   - ランタイム環境（Webサーバー）
   - OS（Linux）
   - これらが一緒にパッケージ化されている

2. **環境に依存しないことが実感できる**
   - Mac、Windows、Linuxで同じように動く
   - 環境の違いを気にしない

3. **従来の問題が解決されている**
   - 「Macで動いたのに、Windowsで動かない」→ 解決！
   - 「Webサーバーをインストールする必要がある」→ 不要！
   - 「環境構築に時間がかかる」→ 一瞬！

4. **実際に確認できる**
   - コンテナが動いている
   - ファイルがコンテナ内にある
   - 環境がLinux
   - これが目に見える形で確認できる

### 重要なポイント

**このページが表示されているということは：**

- HTMLファイル（アプリ）が**実際に動いている**
- Webサーバー（nginx）が**コンテナ内で動いている**
- Linux（OS）が**コンテナ内で動いている**
- これらが**一緒にパッケージ化**されている
- Mac、Windows、Linuxで**同じように動く**

**これが「環境ごとアプリを運ぶ」Dockerの力です！**

---

**次のステップ**
- [Docker用語解説](../../00_Docker用語解説.md)で、Dockerの重要な用語を学びましょう
- [Docker基礎入門](../../01_Docker基礎入門.md)で、Dockerの基本を学びましょう
- [counter-app](../visual-learning/counter-app/)で、データの永続化を学びましょう

