<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
			Commands\Inspire::class,// 偉人の有り難いお言葉をランダム表示するLaravel5のデフォルトコマンド
			Commands\Refresh::class,
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		// Path to Cron Log
		$log_path = '/var/www/html/laravel/storage/logs/cron.log';
		
		/**
		 * Laravel5 Default Command...
		 */
		$schedule->command('inspire')
			->everyFiveMinutes()
			->sendOutputTo($log_path);
		
		/**
		 * メソッド呼び出し型
		 * テストメール送信
		 */
		$schedule->call('\App\Controllers\Admin\AdminTestController@sendMailTest')
			->cron('*/5 * * * *')// 5分毎
			->sendOutputTo($log_path);
		
		/**
		 * コマンド実行
		 */
// 		$schedule->exec('コマンド実行時');
// 				->everyFiveMinutes()     // 5分毎
// 				->environments('local')  // 実行環境の指定
// 				->evenInMaintenanceMode()// メンテナンスモードでも実行するか
//				->sendOutputTo($log_path);
		
	}
}
