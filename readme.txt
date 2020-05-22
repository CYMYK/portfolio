ご覧いただきありがとうございます。
ホテル、旅館の予約管理、宿泊客管理システムを想定して作成しました。

開発環境
XAMPP
PHP Version 7.2.10

開発期間
50時間

------------------------------------------------------------------------
データベース構造

データーベース名
reservation

テーブル名
reservation
カラム名        型        説明
reservation_id  int       予約ID  主キー   AUTO_INCREMENT
guest_id        int       宿泊客ID
room            int       部屋番号
check_in        date　　　チェックイン予定日　
check_out       date      チェックアウト予定日
adult           int       大人の人数
children        int       小人の人数
memo            varchar   備考欄


テーブル名
guest		
カラム名        型        説明
guest_id        int       宿泊客ID  主キー   AUTO_INCREMENT
name            varchar   氏名
address	        varchar   住所
tel             varchar   電話番号
memo            varchar   備考欄
--------------------------------------------------------------------------
ファイル説明

DbManager.php   データーベース接続情報を記載
function.php    エラーチェックに使っているユーザー定義関数を記載
guest_data.php  新規宿泊客の登録、既存宿泊客の更新、削除
guest_search.php  宿泊客検索画面
index.php         メインメニュー  部屋ごとに予約の有無が表示される。
insert_guest.php  宿泊客の新規登録、更新、削除処理を行う
insert_reservation.php  予約の新規登録、更新、削除処理を行う
reservation_data.php    既存の予約データの詳細を表示する。予約データの更新と削除
reservation_new.php     新規の予約データを登録する

---------------------------------------------------------------------------
