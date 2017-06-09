<?php

namespace App\Http\Validator\Csv;

class UserCsvValidator
{
    private $Validator;
    private $Data;

    public function test()
    {
        $this->Validator = \Validator::make( $this->values, $this->rules, $this->messages, $this->attributes );
    }

    private function values()
    {
        //
    }

    private function rules()
    {
        return [
            "user_import_csv" => 'required|mime_csv|max:1024',// 1MB
        ];
    }

    private function attributes()
    {
        return [
            "user_import_csv" => 'ユーザ登録用CSVファイル',
        ];
    }

    private function messages()
    {
        return [
            //
        ];
    }

    public function setData($data)
    {
        $this->Data = $data;
        return $this;
    }

}
