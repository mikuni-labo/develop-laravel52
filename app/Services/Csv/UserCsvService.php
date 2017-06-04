<?php

namespace App\Services\Csv;

use App\Lib\Csv;
use App\Services\Csv\CsvServiceInterface;

/**
 * ユーザ登録CSVクラス
 *
 * @author Kuniyasu Wada
 */
class UserCsvService extends Csv implements CsvServiceInterface
{
    const CSV_COLUMNS = [
            'pos_bill_code',       // 商品コード（紙幣）
            'column2',             // 商品名称
            'column3',             // ランク
            'column4',             // 期首在庫数
            'column5',             // 期首在庫金額
            'column6',             // 販売数
            'column7',             // 販売返品数
            'column8',             // 実質販売数
            'column9',             // 販売金額
            'column10',            // 買取数
            'column11',            // 買取金額
            'column12',            // 仕入数
            'column13',            // 仕入返品数
            'column14',            // 実質仕入数
            'column15',            // 仕入金額
            'column16',            // 委託仕入数
            'column17',            // 委託仕入金額
            'column18',            // 委託出荷数
            'column19',            // 委託出荷金額
            'column20',            // 粗利
            'column21',            // 移動入庫数
            'column22',            // 移動入庫金額
            'column23',            // 移動出庫数
            'column24',            // 移動出庫金額
            'column25',            // 在庫振替数
            'column26',            // 在庫振替金額
            'column27',            // 棚卸ロス数
            'column28',            // 棚卸ロス金額
            'column29',            // 在庫調整数
            'column30',            // 在庫調整金額
            'stock_amount',        // 期末在庫数
            'stock_price',         // 期末在庫金額
            'column33',            // 在庫増減数
            'column34',            // 在庫増減金額
            'column35',            // 販売在庫単価
            'column36',            // 販売在庫金額
            'column37',            // 期末在庫金額(税込)
            'column38',            // 店舗コード
            'column39',            // 店舗名称
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
     * 各種バリデート
     *
     * @return bool
     */
    public function validate()
    {
        /**
         * バリデートルールを追加するならここで
         */
        return parent::validColumns(self::CSV_COLUMNS);
    }

    /**
     * メイン処理
     *
     * {@inheritDoc}
     * @see \App\Services\Csv\CsvServiceInterface::proccess()
     */
    public function proccess()
    {
        $result = $this->assignColumns(self::CSV_COLUMNS, true);
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
