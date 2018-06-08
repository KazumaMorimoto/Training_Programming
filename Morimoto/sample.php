<?php
require_once '../util/defineUtil.php'; // 定数の読み込み

//DBへの接続を行う。成功ならPDOオブジェクトを、失敗なら中断、メッセージの表示を行う
function connect2MySQL(){
    try{
        $pdo = new PDO(DNS, USER_NAME, PASSWORD);
        //SQL実行時のエラーをtry-catchで取得できるように設定
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die('DB接続に失敗しました。次記のエラーにより処理を中断します:'.$e->getMessage());
    }
}

// ログインのための関数。ユーザ名を引き数として、そのユーザのレコードを返す。
function login_check($user_name){
    // db接続を確立
    $login_db = connect2MySQL();

    // SQL文の作成
    $login_sql =
    "SELECT *
    FROM kagoyume_db
    WHERE mail = :user_name
    AND deleteFlg = 0
    ";

    // クエリとして用意
    $login_query = $login_db->prepare($login_sql);
    $login_query->bindValue(":user_name", $user_name);

    try {
        $login_query->execute();
    } catch (PDOException $e) {
        $login_db = null; // DBの切断
        return $e->getMessage();
    }

    $login_db = null; // DBの切断
    // 結果の返却
    return $login_query->fetchAll(PDO::FETCH_ASSOC);
}
