<?php

namespace App\Services\Csv;

/**
 * CSVサービスインターフェース
 *
 * @author Kuniyasu Wada
 */
Interface CsvServiceInterface
{
    /**
     * ファイルバリデート
     */
    public function validCsv();

    /**
     * パラメータバリデート
     */
    public function validParam();

    /**
     * メイン処理
     */
    public function proccess();

}
