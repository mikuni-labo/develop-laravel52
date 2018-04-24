<?php

use App\Lib\ExtendedDateTimeImmutable;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ConvertDateTest extends TestCase
{
    /** @var string */
    private $format = 'Y-m-d H:i:s';

    /**
     * @test
     */
    public function 変則日付変換テスト()
    {
        /**
         * 24H => 29H
         */
        $date = new ExtendedDateTimeImmutable('2018-08-16');
        $this->assertEquals('2018-08-15 24:00:00', $date->to30HoursFormatString());

        $date = new ExtendedDateTimeImmutable('2018-08-16 00:00:00');
        $this->assertEquals('2018-08-15 24:00:00', $date->to30HoursFormatString());

        $date = new ExtendedDateTimeImmutable('2018-08-16 02:00:00');
        $this->assertEquals('2018-08-15 26:00:00', $date->to30HoursFormatString());

        $date = new ExtendedDateTimeImmutable('2018-08-16 04:59:59');
        $this->assertEquals('2018-08-15 28:59:59', $date->to30HoursFormatString(true));

        $date = new ExtendedDateTimeImmutable('2018-08-16 04:59:59');
        $this->assertEquals('2018-08-15 28:59:59', $date->to30HoursFormatString());

        $date = new ExtendedDateTimeImmutable('2018-08-16 05:00:00');
        $this->assertEquals('2018-08-15 29:00:00', $date->to30HoursFormatString(true));

        $date = new ExtendedDateTimeImmutable('2018-08-16 05:00:00');
        $this->assertEquals('2018-08-16 05:00:00', $date->to30HoursFormatString());

        $date = new ExtendedDateTimeImmutable('2018-08-16 23:00:00');
        $this->assertEquals('2018-08-16 23:00:00', $date->to30HoursFormatString());

        $date = new ExtendedDateTimeImmutable('2018-08-16 23:00:00');
        $this->assertEquals('2018-08-16 23:00:00', $date->to30HoursFormatString(true));

        /**
         * 29H => 24H
         */
        $date = ExtendedDateTimeImmutable::from30HoursFormatString('2018-08-16 00:00:00');
        $this->assertEquals('2018-08-16 00:00:00', $date->format($this->format));

        $date = ExtendedDateTimeImmutable::from30HoursFormatString('2018-08-16 12:00:00');
        $this->assertEquals('2018-08-16 12:00:00', $date->format($this->format));

        $date = ExtendedDateTimeImmutable::from30HoursFormatString('2018-08-16 24:00:00');
        $this->assertEquals('2018-08-17 00:00:00', $date->format($this->format));

        $date = ExtendedDateTimeImmutable::from30HoursFormatString('2018-08-16 26:00:00');
        $this->assertEquals('2018-08-17 02:00:00', $date->format($this->format));

        $date = ExtendedDateTimeImmutable::from30HoursFormatString('2018-08-16 28:59:59');
        $this->assertEquals('2018-08-17 04:59:59', $date->format($this->format));

        $date = ExtendedDateTimeImmutable::from30HoursFormatString('2018-08-16 29:00:00');
        $this->assertEquals('2018-08-17 05:00:00', $date->format($this->format));

        $date = ExtendedDateTimeImmutable::from30HoursFormatString('2018-08-16 29:30:00');
        $this->assertEquals('2018-08-17 05:30:00', $date->format($this->format));
    }

}
