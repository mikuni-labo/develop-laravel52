<?php

namespace App\Lib;

/**
 * Utility Class...
 *
 * @author Kuniyasu Wada
 */
class Util
{
    /**
     * 整数チェックとレコードの存在確認
     *
     * @param $id
     * @param $table
     * @return bool falseが返れば存在
     */
    public static function checkExistsID($id, $table)
    {
        return ( !is_numeric($id) || empty(\DB::table($table)->where('id', '=', $id)->get()) );
    }

    /**
     * Get Current Time...
     *
     * @param  $format = "Y-m-d H:i:s"など
     * @return $param 日付データ
     */
    public static function getDate($format = 'Y-m-d H:i:s')
    {
        return date($format, time());
    }

    /**
     * Get PullDown Years...
     * デフォルトは現在年から未来5年分
     *
     * @param string $year
     * @param string $default
     * @return multitype:string
     */
    public static function getArrYear($start = null, $cnt = null)
    {
        $year = !$start ? (int)(DATE("Y")) : (int)$start;
        $end_year = !$cnt ? DATE("Y") + 4 : DATE("Y") + ((int)$cnt - 1);

        $year_array = array();

        for ($i=$year; $i<=($end_year); $i++){
            $year_array[$i] = $i;
        }
        return $year_array;
    }

    /**
     * Generating Random Slug...
     *
     * @param number $length
     * @return string
     */
    public static function getRandomSlug($length = 6)
    {
        $parts = 'abcefghijklmnopqrstuvwxyz1234567890';
        return substr(str_shuffle($parts), 0, $length);
    }

    /**
     * Output To File...
     *
     * @param string $data
     * @param string $filepath
     * @param string $mode
     * @return number
     */
    public static function fwrite($data, $filepath, $mode = 'a+')
    {
        $fp = fopen($filepath, $mode);
        $res = fwrite($fp, $data. "\n");
        fclose($fp);

        return $res;
    }

    /**
     * 配列をデリミタで連結した文字列を返す
     *
     * @param array   $params
     * @param string  $delimiter
     * @return string $str
     */
    public static function implodeArrToString($params, $delimiter)
    {
        $str = "";

        if(is_array($params))
        {
            foreach ($params as $key => $val)
            {
                if($str != "") $str .= "{$delimiter} ";
                $str .= $val;
            }
        }
        else
            $str = $params;

        return $str;
    }

    /**
     * Get Salt...
     */
    public static function get_salt() {
        $bytes = openssl_random_pseudo_bytes(SALT_LENGTH);
        return bin2hex($bytes);
    }

    /**
     * ソルト＋ストレッチングでハッシュ化したパスワードを取得
     */
    public static function getHushPassword($salt, $pass){
        $hash_pass = "";

        for ($i = 0; $i < STRETCH_COUNT; $i++){
            $hash  = hash("sha256", ($hash_pass . $salt . $pass));
        }
        return $hash;
    }

    /**
     * Get CSRF Token...
     */
    public static function get_csrf_token() {
        $bytes = openssl_random_pseudo_bytes(TOKEN_LENGTH);
        return bin2hex($bytes);
    }

    /**
     * Do Redirect...
     */
    public static function sendRedirect($url) {
        header("location: {$url}");
        exit();
    }

    /**
     * 指定階層まで遡ったパスを取得
     *
     * @param     string $current カレントパス
     * @param     int $cnt 遡る階層数
     * @param     bool $full フルパスかどうか TRUE＝フルパス FALSE＝ディレクトリ名のみ
     * @return string
     * @author K.Wada
     */
    public static function getBackDir($current, $cnt = 1, $full = TRUE)
    {
        $arrDir = explode('/', $current);

        // 指定回数分遡る
        for($i=0; $i < $cnt; $i++){
            array_pop($arrDir);
        }
        if($full){
            // フルパス
            $fullPath = implode('/', $arrDir);
            return $fullPath;
        }
        else {
            // ディレクトリ名
            $dirName = array_pop($arrDir);
            return $dirName;
        }
    }

    /**
     * 指定されたサーバー環境変数を取得する
     */
    public static function getServer($key, $default = null)
    {
        return (isset($_SERVER[$key])) ? $_SERVER[$key] : $default;
    }

    /**
     * セッションを安全かつ完全に破壊する
     * (セッションハイジャック対策)
     *
     * @return void
     * @author K.Wada
     */
    public static function sessionDestroy()
    {
        // セッション変数を全て解除する
        $_SESSION = array();

        // セッションを切断するにはセッションクッキーも削除する。
        // セッションクッキーを有効期限外に設定
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time()-42000, '/');
        }
        // 最終的に、セッションを破壊する
        session_destroy();
    }

    /**
     * クライアントのIPアドレスを取得する
     *
     * @param string $checkProxy
     * @return Ambigous <unknown, string>
     */
    public static function getClientIp($checkProxy = true)
    {
        /*
         *  プロキシサーバ経由の場合は、プロキシサーバではなく
         *  接続もとのIPアドレスを取得するために、サーバ変数
         *  HTTP_CLIENT_IP および HTTP_X_FORWARDED_FOR を取得する。
         */
        if ($checkProxy && Util::getServer('HTTP_CLIENT_IP') != null){
            $ip = Util::getServer('HTTP_CLIENT_IP');
        }
        else if ($checkProxy && Util::getServer('HTTP_X_FORWARDED_FOR') != null){
            $ip = Util::getServer('HTTP_X_FORWARDED_FOR');
        }
        else {
            // プロキシサーバ経由でない場合は、REMOTE_ADDR から取得する
            $ip = Util::getServer('REMOTE_ADDR');
        }
        return $ip;
    }
}
