# ベースイメージを指定（nginxを使用）
FROM nginx:alpine

# HTMLファイルをコンテナにコピー
COPY index.html /usr/share/nginx/html/

# ポート80を公開
EXPOSE 80

# nginxを起動（nginxイメージには既に設定されているが、明示的に記述）
CMD ["nginx", "-g", "daemon off;"]

