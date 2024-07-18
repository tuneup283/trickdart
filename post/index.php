<?php
// header("Content-Security-Policy: script-src 'self' trickdart.tokyo;");
header('X-Frame-Options: SAMEORIGIN');
ini_set('display_errors', "Off");

$path = "";

/**
 *  CSV読み込み
 *  @return 
 */
function init()
{
    session_start();
    
    if(isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] == "dev.trickdart.tokyo"){
        $path = "/home/r6726901/public_html/dev.trickdart.tokyo/file/data.csv";
    }else{
        $path = "/home/r6726901/public_html/trickdart.tokyo/file/data.csv";
    }

    $token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $token;
    
    if (!$fp = fopen($path, 'rb')) { return false; }

    flock($fp, LOCK_SH);
    $_arr = stream_get_contents($fp, -1, -1);
    // $_arr = mb_convert_encoding($_arr, 'UTF-8');
    $arr = explode("\n", $_arr);

    $data = [];
    foreach($arr as $key => $value){
        if(!$value) continue; //空白行が含まれていたら除外
        $_row = explode(",", $value);
        if(mb_strlen(trim($_row[0])) == 0 && mb_strlen(trim($_row[1])) == 0) continue;
        $data[$key]['nickname'] = mb_convert_encoding(trim($_row[0]), 'UTF-8'). " さん";
        $data[$key]['msg'] = mb_convert_encoding(trim($_row[1]), 'UTF-8');
    }

    flock($fp, LOCK_UN);
    fclose($fp);

    // シャッフル
    shuffle($data);

    if(isset($data)){
        $response = array(
            'csrf_token' => $token
            ,'res' => "success"
            ,'list' => array_slice($data, 0, 20) // 上位20件
        );
    }else {
        $response = array(
            'res' => "error"
        );
    }

    header('Content-Type: application/json');
    print(json_encode($response));
    exit();
}

init();

?>