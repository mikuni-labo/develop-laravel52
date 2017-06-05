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
     * バリデート
     */
    public function validate();

    /**
     * メイン処理
     */
    public function proccess();

}
