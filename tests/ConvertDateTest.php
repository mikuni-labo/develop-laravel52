<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ConvertDateTest extends TestCase
{
    /**
     * @test
     */
    public function 変則日付変換テスト()
    {
        /**
         * 24H => 29H
         */
        $result = $this->convert24DateTo29Date('2017-08-17', '02:00:00');
        $this->assertEquals('2017-08-16 26:00:00', $result);

        $result = $this->convert24DateTo29Date('2017-08-17', '05:00:00', true);
        $this->assertEquals('2017-08-16 29:00:00', $result);

        $result = $this->convert24DateTo29Date('2017-08-17', '05:00:00');
        $this->assertEquals('2017-08-17 05:00:00', $result);

        $result = $this->convert24DateTo29Date('2017-08-17', '04:59:59', true);
        $this->assertEquals('2017-08-16 28:59:59', $result);

        $result = $this->convert24DateTo29Date('2017-08-17', '04:59:59');
        $this->assertEquals('2017-08-16 28:59:59', $result);

        $result = $this->convert24DateTo29Date('2017-08-16', '23:00:00');
        $this->assertEquals('2017-08-16 23:00:00', $result);

        $result = $this->convert24DateTo29Date('2017-08-16', '23:00:00', true);
        $this->assertEquals('2017-08-16 23:00:00', $result);

        $result = $this->convert24DateTo29Date('2017-08-16', '00:00:00');
        $this->assertEquals('2017-08-15 24:00:00', $result);

        $result = $this->convert24DateTo29Date('2017-08-16');
        $this->assertEquals('2017-08-15 24:00:00', $result);

        /**
         * 29H => 24H
         */
        $result = $this->convert29DateTo24Date('2017-08-16', '26:00:00');
        $this->assertEquals('2017-08-17 02:00:00', $result);

        $result = $this->convert29DateTo24Date('2017-08-16', '26:00:00');
        $this->assertEquals('2017-08-17 02:00:00', $result);

        $result = $this->convert29DateTo24Date('2017-08-16', '28:59:59');
        $this->assertEquals('2017-08-17 04:59:59', $result);

        $result = $this->convert29DateTo24Date('2017-08-16', '24:00:00');
        $this->assertEquals('2017-08-17 00:00:00', $result);

        $result = $this->convert29DateTo24Date('2017-08-16', '12:00:00');
        $this->assertEquals('2017-08-16 12:00:00', $result);

        $result = $this->convert29DateTo24Date('2017-08-16', '00:00:00');
        $this->assertEquals('2017-08-16 00:00:00', $result);

        $result = $this->convert29DateTo24Date('2017-08-16');
        $this->assertEquals('2017-08-16 00:00:00', $result);
    }

    /**
     * 24時間形式を29時間形式へ変換する
     *
     * @param  $datetime
     * @param  $isEnd 開始・終了のどちらを意味するか
     * @return string 日付データ
     * @author Kuniyasu Wada
     */
    public function convert24DateTo29Date($date, $time = '00:00:00', $isEnd = false)
    {
        // 00:00:00 ～ 04:59:59 または、"終了時間"かつ "05:00:00" なら29時間形式へ変換
        if( ("{$date} 00:00:00" <= "{$date} {$time}" && "{$date} {$time}" < "{$date} 05:00:00") || ( $time === "05:00:00" && $isEnd ) )
        {
            $arrDate = explode('-', $date);
            $arrTime = explode(':', $time);
            $timestamp = mktime(0, 0, 0, $arrDate[1], $arrDate[2], $arrDate[0]);
            return date('Y-m-d', $timestamp - 60 * 60 * 24) . ' ' . str_pad(intval($arrTime[0]) + 24, 2, 0, STR_PAD_LEFT) . ":{$arrTime[1]}:{$arrTime[2]}";
        }

        return "{$date} {$time}";
    }

    /**
     * 29時間形式を24時間形式へ変換する
     *
     * @param  $date
     * @param  $time
     * @return datetime 日付
     * @author Kuniyasu Wada
     */
    public function convert29DateTo24Date($date, $time = '00:00:00')
    {
        if(empty($date)) return null;

        $arrTime = explode(':', $time);

        if( intval($arrTime[0]) >= 24 && intval($arrTime[0]) <= 29)
        {
            $arrDate = explode('-', $date);
            $timestamp = mktime(intval($arrTime[0]) - 24, $arrTime[1], $arrTime[2], $arrDate[1], $arrDate[2], $arrDate[0]);
            return date('Y-m-d H:i:s', $timestamp + 60 * 60 * 24);
        }

        return "{$date} {$time}";
    }

}
