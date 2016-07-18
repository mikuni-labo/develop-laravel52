<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function (Blueprint $table) {
			$table->increments('id');
			
			$table->string('role');                                // 権限
			$table->smallInteger('status')->default(1);            // 有効/無効
			$table->integer('version')->default(1);                // 世代管理
			$table->integer('modified_user')->nullable();          // データ更新者
			
			$table->string('name1')->nullable();
			$table->string('name2')->nullable();
			$table->string('company')->nullable();
			$table->string('position')->nullable();
			$table->string('email')->unique();
			$table->string('password', 60);
			$table->rememberToken();
			
			$table->string('confirmation_token')->nullable();      // 確認用トークン
			$table->timestamp('confirmed_at')->nullable();         // 確認日時
			$table->timestamp('confirmation_sent_at')->nullable(); // 確認メール送信日時
			
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('users');
	}
}
