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
        $this->down();

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');                              // int unsigned auto increment

            $table->string('role');                                // 権限
            $table->smallInteger('status')->default(1);            // 有効/無効
            $table->integer('version')->default(1);                // 世代管理
            $table->integer('modified_user')->nullable();          // データ更新者

            $table->string('last_name')->nullable();               // 姓
            $table->string('first_name')->nullable();              // 名
            $table->string('company')->nullable();                 // 会社名
            $table->string('department')->nullable();              // 部署名
            $table->string('position')->nullable();                // 役職名
            $table->mediumInteger('postal_code')->nullable();      // 郵便番号
            $table->tinyInteger('pref_code')->nullable();          // 都道府県番号
            $table->string('address')->nullable();                 // 住所
            $table->string('building')->nullable();                // 建物名
            $table->string('tel', 15)->nullable();                 // 電話番号
            $table->string('fax', 15)->nullable();                 // FAX番号
            $table->string('email')->unique();                     // Eメール

            $table->string('password', 60);                        // パスワード
            $table->rememberToken();                               // クッキー認証用トークン

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
