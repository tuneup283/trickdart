<?php
// header("Content-Security-Policy: script-src 'self' trickdart.tokyo;");
header('X-Frame-Options: SAMEORIGIN');
ini_set('display_errors', "Off");

$path = "";

function init() {

    session_start();
    $success_message = "";
    $error_message = [];
    $max_count = 30;
    $max_count2 = 100;

    $response = array(
        'res' => "none"
        ,'msg' => "申し訳ございません。投稿処理に失敗しました。"
    );

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        //トークンが送信されているかの確認
        if (!isset($_POST['csrf_token'])) {
            $error_message[] = '不正なリクエストです。';
        //トークンが合っているかの検証
        }else if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $error_message[] = '不正なリクエストです';
        }

        if(count($error_message) > 0){
            $response['res'] = "error";
            $response['msg'] = $error_message;

            setReturnData($response);
            die();
        }
    
        // フォームからのデータを取得
        $data['nickname'] = htmlspecialchars($_POST["nickname"]);
        $data['message'] = htmlspecialchars($_POST["message"]);
    
        // 入力値のバリデーション
        if (empty($data['nickname'])) {
            $error_message[] = "ニックネームを入力してください。";
        }else if(isset($data['nickname']) && mb_strlen($data['nickname']) > $max_count){
            $error_message[] = "ニックネームは ".$max_count."文字以内で入力してください。";
        }else if (preg_match('/[&"\'<>]/', $data['nickname'])) {
            $error_message[] = "ニックネームに使用できない文字が含まれています";
        }
    
        if (empty($data['message'])) {
            $error_message[] = "メッセージを入力してください。";
        }else if(isset($data['message']) && mb_strlen($data['message']) > $max_count2){
            $error_message[] = "メッセージは ".$max_count2."文字以内で入力してください。";
        }else if (preg_match('/[&"\'<>]/', $data['message'])) {
            $error_message[] = "メッセージに使用できない文字が含まれています";
        }
    
        if(count($error_message) > 0){
            $response['res'] = "error";
            $response['msg'] = $error_message;

            setReturnData($response);
            exit();
        }
    
        $response['res'] = "success";
        $response['msg'] = "メッセージの投稿ありがとうございます。";
    
        // csvファイルへ書き込み
        $res = file_append($data['nickname'].",".$data['message']);

        if($res == false){
            $response['res'] = "error";
            $error_message[] = "投稿データの取得に失敗しました。";
            $response['msg'] = $error_message;

            setReturnData($response);
            exit();
        }
             
        // 入力データ削除
        $data['nickname'] = null;
        $data['message'] = null;
    
        setReturnData($response);
        die();
    }
}

/**
 *  投稿処理の結果を返す
 *  @param array 結果 
 *  @return void
 */
function setReturnData($response) {
    // レスポンスヘッダーをJSONに指定する
    header('Content-Type: application/json');
    print(json_encode($response));
}

/**
 *  CSV書き込み
 *  @param array 投稿データ
 *  @return void
 */
function file_append($data)
{
    if(isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] == "dev.trickdart.tokyo"){
        $path = "/home/r6726901/public_html/dev.trickdart.tokyo/file/data.csv";
    }else{
        $path = "/home/r6726901/public_html/trickdart.tokyo/file/data.csv";
    }
    

    // ファイルコピー
    copy($path, str_replace('data.csv', 'backup/data_'.date('m').'.csv', $path));
    
    if (!$fp = fopen($path, 'ab')) {
        return false;
    }

    $_add_data = ",".date("Ymd H:i:s");
    $_add_data .= ",".$_SERVER['REMOTE_ADDR']."[".$_SERVER['REMOTE_PORT']."]";

    flock($fp, LOCK_EX);

    $result = fwrite($fp, "\r\n".$data.$_add_data);
    fflush($fp);
    flock($fp, LOCK_UN);
    fclose($fp);

    return $result;
}

init();

?>