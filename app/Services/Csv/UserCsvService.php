<?php

namespace App\Services\Csv;

use App\Lib\Csv;
use App\Http\Requests\Csv\UserCsvRequest;
use App\Services\Csv\CsvServiceInterface;

/**
 * ユーザ登録CSVクラス
 *
 * @author Kuniyasu Wada
 */
class UserCsvService extends Csv implements CsvServiceInterface
{
    // フォーマット未定
    const CSV_COLUMNS = [
        'last_name',        // お名前
        'company',          // 会社名
        'department',       // 部署名
        'position',         // 役職名
        'postal_code',      // 郵便番号
        'address',          // 住所
        'tel',              // 電話番号
        'fax',              // FAX番号
        'email',            // Eメール
    ];

    /**
     * Create a new class instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * {@inheritDoc}
     * @see \App\Services\Csv\CsvServiceInterface::validate()
     */
    public function validate()
    {
        // レコード有無
        if( parent::validRecordsCount() ) return true;

        // カラム数正当性
        if( parent::validColumnCount(self::CSV_COLUMNS) ) return true;

        return false;
    }

    /**
     * {@inheritDoc}
     * @see \App\Services\Csv\CsvServiceInterface::proccess()
     */
    public function proccess()
    {
        $result = $this->assignColumns(self::CSV_COLUMNS, true);

        dd($result);

        $csv = $result->groupBy('pos_bill_code');

        /**
         * 通貨毎ループ
         */
        foreach ( Currency::all() as $Currency )
        {
            $price  = 0;
            $amount = 0;

            foreach ( $Currency->bills()->get() as $Bill )
            {
                if( $csv->has($Bill->pos_code) )
                {
                    $price  += intval( $csv->get($Bill->pos_code)->first()['stock_price'] );               // 期末在庫金額
                    $amount += intval( $csv->get($Bill->pos_code)->first()['stock_amount']) * $Bill->value;// 額面合計 = 期末在庫数 * 紙幣の額面
                }
            }

            $Currency->update([
                    'cost' => $this->culculateCost($price, $amount),
            ]);
        }
    }

    /**
     * 原価計算
     *
     * @param  integer $price
     * @param  integer $amount
     * @return integer
     */
    private function culculateCost($price, $amount)
    {
        if( $price === 0 || $amount === 0 ) return 0;

        return $price / $amount;// 原価 = 合計期末金額 / 額面合計
    }

}
