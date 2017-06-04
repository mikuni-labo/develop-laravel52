<?php

namespace App\Http\Requests\Csv;

use App\Http\Requests\Request;

class UserCsvRequest extends Request
{
    public function __construct()
    {
        parent::__construct();
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            "user_import_csv" => 'required|mime_csv|max:1024',// 1MB
        ];
    }

    public function attributes()
    {
        return [
            "user_import_csv" => 'ユーザ登録用CSVファイル',
        ];
    }

    public function messages()
    {
        return [
            //
        ];
    }

}
