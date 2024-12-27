# 飲食店予約アプリ　Rese
概要説明：未ログイン状態で店舗情報とレビューを見ることができます。
ログインすると飲食店の予約、お気に入り登録、来店後にレビューの投稿ができるようになります。
予約当日の朝8時にリマインドメールが届きます。
マイページの予約欄から予約内容の変更、削除ができ、「QRコードを表示する」ボタンを押すとQRコードが作成されます。
店舗入口で店員さんに読み取ってもらうと予約内容を確認してもらえ、「確認しました」ボタンを押すことで予約データが”来店”に更新されます。
決済機能はStripeを使用しています。
また、ユーザー、管理者、店舗代表者の３つの権限を用意しました。

![アプリのトップ画面](./docs/top_screen.png)

## 作成した目的
外部の飲食店予約サービス手数料を取られるので自社で予約サービスを持つため。

## アプリケーションのURL


## 機能一覧
＊ユーザー＊　会員登録、ログイン/ログアウト、お気に入り追加/削除、予約の追加/変更/削除、店舗検索（ジャンル/エリア/店舗名の部分一致）、
　　　　　　　レビュー機能、リマインドメール送信、QRコードで予約確認/来店に更新、決済機能（Stripe）

＊管理者＊　店舗代表者作成/更新/削除、代表者検索、お知らせメール送信

＊店舗代表者＊　店舗情報作成/更新/削除、予約確認


## 使用技術
docker、Laravel Framework 8.X、PHP 8.X、nginx 1.X、mysql 8.X、myadmin 5.X、mailhog、
larevel-fortify、laravel-permission、Stripe、Javascript


## テーブル設計
![スプレッドシートのテーブル設計図](./docs/table-1.png)
![スプレッドシートのテーブル設計図](./docs/table-2.png)
![スプレッドシートのテーブル設計図](./docs/table-3.png)
![スプレッドシートのテーブル設計図](./docs/table-4.png)
![スプレッドシートのテーブル設計図](./docs/table-5.png)
![スプレッドシートのテーブル設計図](./docs/table-6.png)
![スプレッドシートのテーブル設計図](./docs/table-7.png)
## ER図
![ER図](./docs/er.drawio.png)

# 環境構築
1.リポジトリの設定
開発環境をGitHubからクローン。

クローンを作りたいディレクトリ下で以下のコマンドを実行。

`$ git clone git@github.com:kozuka-yuko/Rese.git`

リポジトリ名の変更。

`$ mv laravel-docker-template 新しいリポジトリ名`

2.個人のリモートリポジトリURLを変更
GitHubで、上記コマンドで指定した「新しいリポジトリ名」で変更先のリモートリポジトリをpublicで作成。

ローカルリポジトリから紐づけ先を変更するために、GitHubで新しいリポジトリ名で作成したリポジトリのURLを取得する。

URLはQuik setup～内の四角が二つ重なったアイコンから取得する。

下記コマンドの実行。
```
$ cd contact-form
$ git remote set-url origin 作成したリポジトリのURL
$ git remote -v
```
３つ目のコマンドを実行した時に変更先のURLが表示されれば成功。

3.現在のローカルリポジトリのデータをリモートリポジトリに反映させる

下記コマンドの実行。
```
$ git add .
$ git commit -m "リモートリポジトリの変更"
$ git push origin main
```
GitHubのページを見てdockerフォルダやsrcフォルダが反映されていれば成功。

エラーが発生する場合は

`$ sudo chmod -R 777 *`

コマンドを実行後、もう一度コマンドを実行し直してみる。

4.Dockerの設定
下記コマンドの実行。
```
$ docker-compose up -d --build`
$ code .
```
Dockerにコンテナができているか確認。

5.laravelのパッケージのインストール
共有元が作成したcomposer.jsonファイルやcomposer.lockファイルを元に必要なパッケージをインストールする。

PHPコンテナ内にログインする。

`$ docker-compose exec php bash`

下記コマンドでcomposer.jsonに記載されたパッケージのリストをインストール。

`$ composer install`

6.「.envファイル」の作成
データベースに接続するために .envファイルを作成。PHPコンテナ内で以下のコマンドを実行。
```
$ cp .env.example .env
$ exit
```
作成できたらVSCodeから .envファイルの11行目以降を以下のように修正。
```
// 前略

DB_CONNECTION=mysql
- DB_HOST=127.0.0.1
+ DB_HOST=mysql
DB_PORT=3306
- DB_DATABASE=laravel
- DB_USERNAME=root
- DB_PASSWORD=
+ DB_DATABASE=laravel_db
+ DB_USERNAME=laravel_user
+ DB_PASSWORD=laravel_pass

// 後略
```
上記はdocker-compose.ymlで作成したデータベース名、ユーザー名、パスワードを記述している。
（mysqlのenvironent部分）
docker-compose.ymlで設定したphpmyadminにデータベース（laravel_db）が存在しているか確認。
http://localhost:8080/ で確認。

7.アプリケーションを実行できるようにキーを作成する
'$ php artisan key:generate'

データベースにダミーデータが存在するので以下のコマンドを実行することで表示される。

PHPコンテナ内
'''
$ php artisan migrate
$ php artisan db:seed
'''


＊メール認証＊
.envファイルの MAIL_FORM_ADRESS= をnullではなく任意のメールアドレスにしてください。
＜MailHogについて＞


・キューの実行
　大量のデータ処理やメール送信など時間のかかる重たい処理をバックグラウンドで非同期で行うために以下のコマンドを実行してください。
 
 PHPコンテナ内
 '$ php artisan queue:work'
（停止はCtrl+C）

 ・スケジューラー実行
 　リマインドメールを送信できるようにするために実行してください。

  PHPコンテナ内（キューを実行しているので同じターミナル内でコマンドを打てないため新しいターミナルを開いて実行してください）
  '$ php artisan schedule:work'
  （停止はCtrl+C）

  ・Stripe決済の実行
  　Stripe決済を使用するために実行してください。

   PHPコンテナ内（さらに新しいターミナルを開いて実行してください）
   '$ php artisan serve'
   （停止はCtrl+C）


   ##アカウントの種類
   1.管理者　　　　email: admin@sample.com
   2.店舗代表者　　email: shop_rep@sample.com
   3.ユーザー　　　email: gest@sample.com
   
   ※パスワードは全て”password”でログインできます。
   
