<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class DotInstallController extends Controller
{
    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->middleware('guest:user', ['except' => []]);
    }
    
    /**
     * Carbon Test...
     *
     * @method GET
     */
    public function testCarbon()
    {
        /**
         * インスタンスの取得方法
         */
        $time = new Carbon();
        //$time = Carbon::now();
        //$time = Carbon::today();
        //$time = Carbon::tomorrow();
        //$time = Carbon::yesterday();
        //$time = Carbon::parse('2016/7/9 143027');                       // 文字列から解釈
        //$time = Carbon::create(2016, 8, 7, 15, 35, 42);                 // カンマ区切りで指定
        //$time = Carbon::createFromFormat('Y/m/d H:i', '2016/8/7 14:00');// 固定フォーマットから取得したい場合
        //$time = Carbon::createFromTimestamp(1423987651);                // Timestampから取得
        //$time = Carbon::create(2016, 8, 7, 15, 35, 42);                 // カンマ区切りで指定
        
        /**
         * 各パーツの取り出し
         */
        //dd($time->year);
        //dd($time->month);
        //dd($time->day);
        //dd($time->hour);
        //dd($time->minute);
        //dd($time->second);
        //dd($time->dayOfWeek);        // 曜日 0〜6
        //dd($time->dayOfYear);        // 元日からの経過日数
        //dd($time->weekOfMonth);      // 月間の経過週数
        //dd($time->weekOfYear);       // 年間の経過週数
        //dd($time->timestamp);        // Timestamp
        //dd($time->tzName);           // TimeZone (例：Asia/Tokyo)
        //dd($time->format('r'));      // フォーマット指定 (例：Sat, 09 Jul 2016 14:30:27 +0900)
        //dd($time->tzName);           // TimeZone (例：Asia/Tokyo)
        
        /**
         * 日付の判定
         */
        $now = Carbon::now();
        //$now->isToday();
        //$now->isTomorrow();
        //$now->isYesterday();
        //$now->isSunday();           // 特定の曜日かどうか
        //$now->isFuture();           // 未来の日付かどうか
        //$now->isPast();             // 過去の日付かどうか
        //$now->isLeapYear();         // 閏年かどうか
        //$now->isWeekday();          // 平日かどうか
        //$now->isWeekend();          // 週末かどうか
        //$now->isSameDay($time);     // 特定の日付と同日かどうか
        
        /**
         * 日付の比較
         */
        //$now->eq($time);                      // eq は イコール
        //$now->gt($time);                      // gt, gte は >, >= グレーターザン(イコール)
        //$now->lt($time);                      // lt, lte は <, <= レスザン(イコール)
        //$now->between($time1, $time2, false); // 指定日の間に含まれるかどうか (第３引数でイコールを除外)
        //$now->max($time->addDay(1));          // 比較して大きい方の日付を返す
        //$now->min($time->addDay(1));          // 比較して小さい方の日付を返す
        
        /**
         * 日付の計算
         * (copy()を作成することによって、元のインスタンスは変化させないようにできる)
         */
        //$time->copy()->addYear();
        //$time->addDay();                       // 1日加算
        //$time->addDays(2);                     // 2日加算
        //$time->subDay();                       // 1日減算
        //$time->subDays(2);                     // 2日減算
        //$time->addWeekdays(8);                 // 8営業日加算
        //$time->startOfDay();                   // その日の開始時間
        //$time->endOfDay();                     // その日の最後の時間
        //$time->next(Carbon::MONDAY);           // 次の引数分に変更(この場合は次の月曜)
        //$time->previous(Carbon::FRIDAY);       // 前の引数分に変更(この場合は前の金曜)
        //$time->firstOfMonth(Carbon::SATURDAY); // 当月最初の土曜
        //$time->lastOfMonth(Carbon::SATURDAY);  // 当月最後の土曜
        //$time->nthOfMonth(3, Carbon::FRIDAY);  // 第3金曜
        
        /**
         * 差分
         */
        $dd1 = Carbon::create(2016, 7, 9);
        $dd2 = Carbon::create(2016, 8, 16);
        
        //$diff = $dd1->diffInDays($dd2);   // 日付の差分
        //$diff = $dd1->diffInHours($dd2);  // 時間の差分
        //$diff = $dd1->diffInMinutes($dd2);// 分の差分
        //$diff = $dd1->diffInSeconds($dd2);// 秒の差分
        
        Carbon::setLocale('ja');
        //$diff = $dd1->diffForHumans($dd2);// 人間が理解しやすい表現
        
        /**
         * setTestNow()
         */
        $birthday = Carbon::create(1983, 8, 16);
        Carbon::setTestNow(Carbon::create(2020, 8, 16));// 現在の日付をテスト用に調整する
        Carbon::setTestNow();                           // 引数無しで解除
        
        if($birthday->isBirthday(Carbon::now()))
            dd(':)');
        else 
            dd(':<');
        
        /**
         * Carbon定数
         */
        Carbon::SUNDAY;
        Carbon::MONDAY;
        Carbon::TUESDAY;
        Carbon::WEDNESDAY;
        Carbon::THURSDAY;
        Carbon::FRIDAY;
        Carbon::SATURDAY;
    }
    
}
