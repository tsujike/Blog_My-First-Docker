FROM nginx:alpine

# デフォルトのindex.htmlを削除（ボリュームマウントで上書きするため）
RUN rm /usr/share/nginx/html/index.html

# ポート80を公開
EXPOSE 80

# nginxを起動
CMD ["nginx", "-g", "daemon off;"]

