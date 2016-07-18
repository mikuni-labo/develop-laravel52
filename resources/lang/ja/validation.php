<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'   => ':attribute を承認してください。',
    'active_url' => ':attribute は、有効なURLではありません。',
    'after'      => ':attribute には、:date以前の日付は指定できません。',
    'alpha'      => ':attribute には、アルファベッドのみ使用できます。',
    'alpha_dash' => ":attribute には、英数字('A-Z','a-z','0-9')とハイフンと下線('-','_')が使用できます。",
    'alpha_num'  => ":attribute には、英数字('A-Z','a-z','0-9')が使用できます。",
    'array'      => ':attribute には、配列を指定してください。',
    'before'     => ':attribute には、:date 以前の日付を指定してください。',
    'between'    => [
        'numeric' => ':attribute には、:min から、:max までの数値を指定してください。',
        'file'    => ':attribute には、:min KBから:max KBまでのサイズのファイルを指定してください。',
        'string'  => ':attribute は、:min 文字から:max 文字にしてください。',
        'array'   => ':attribute の項目は、:min 個から:max 個にしてください。',
    ],
    'boolean'              => ":attribute には、'true'か'false'を指定してください。",
    'confirmed'            => ':attribute の確認が一致しません。',
    'date'                 => ':attribute は、正しい日付ではありません。',
    'date_format'          => ":attribute の形式は、':format'と合いません。",
    'different'            => ':attribute と:otherには、異なるものを指定してください。',
    'digits'               => ':attribute は、:digits 桁にしてください。',
    'digits_between'       => ':attribute は、:min 桁から:max 桁にしてください。',
    'email'                => ':attribute は有効なメールアドレス形式で入力してください。',
    'exists'               => '入力された :attribute は存在しないか、不正なデータです。',
    'filled'               => ':attribute は必須です。',
    'image'                => ':attributeには、画像を指定してください。',
    'in'                   => '選択された :attribute は、有効ではありません。',
    'in_array'             => 'The :attribute field does not exist in :other.',
    'integer'              => ':attribute には、整数を指定してください。',
    'ip'                   => ':attribute には、有効なIPアドレスを指定してください。',
    'json'                 => ':attribute には、有効なJSON文字列を指定してください。',
    'max'                  => [
        'numeric' => ':attribute には、:max 以下の数値を指定してください。',
        'file'    => ':attribute には、:max KB以下のファイルを指定してください。',
        'string'  => ':attribute は、:max 文字以下にしてください。',
        'array'   => ':attribute の項目は、:max 個以下にしてください。',
    ],
    'mimes'                => ':attribute の形式は [:values] です。',
    'min'                  => [
        'numeric' => ':attribute には、:min 以上の数値を指定してください。',
        'file'    => ':attribute には、:min KB以上のファイルを指定してください。',
        'string'  => ':attribute は :min 文字以上入力してください。',
        'array'   => ':attribute の項目は、:max 個以上にしてください。',
    ],
    'not_in'               => '選択された:attribute は、有効ではありません。',
    'numeric'              => ':attribute は数値で入力してください。',
    'regex'                => ':attribute の形式が不正です。',
    'required'             => ':attribute は必須項目です。',
    'required_if'          => ':other が :value の場合、 :attribute を指定してください。',
    'required_unless'      => ':otherが :value 以外の場合、 :attribute を指定してください。',
    'required_with'        => ':values が指定されている場合、 :attribute も指定してください。',
    'required_with_all'    => ':values が全て指定されている場合、 :attribute も指定してください。',
    'required_without'     => ':values が指定されていない場合、 :attribute を指定してください。',
    'required_without_all' => ':values が全て指定されていない場合、 :attribute を指定してください。',
    'same'                 => ':attribute と :other が一致しません。',
    'size'                 => [
        'numeric' => ':attribute には、 :size を指定してください。',
        'file'    => ':attribute には、 :size KBのファイルを指定してください。',
        'string'  => ':attribute は、 :size 文字にしてください。',
        'array'   => ':attribute の項目は、 :size 個にしてください。',
    ],
    'string'               => ':attribute には、文字を指定してください。',
    'timezone'             => ':attribute には、有効なタイムゾーンを指定してください。',
    'unique'               => '指定の :attribute は既に使用されています。',
    'url'                  => ':attribute は、有効なURL形式で指定してください。',

    /**
     * Custom Validate Rules...
     */
    'jp_zip_code'          => ':attribute を正しい形式で入力して下さい。',
    'card_number'          => ':attribute を正しい形式で入力して下さい。',
    'card_holder_name'     => ':attribute を正しい形式で入力して下さい。',
    'security_code'        => ':attribute を正しい形式で入力して下さい。',
    'card_expire'          => ':attribute を正しい形式で入力して下さい。',
    'account_name_kana'    => ':attribute は全角カナのみを入力して下さい。',
    'only_hankaku'         => ':attribute は半角文字のみを入力して下さい。',
    'only_zenkaku'         => ':attribute は全角文字のみを入力して下さい。',
    'mime_images'          => ':attribute の対応拡張子は [.jpg] [.jpeg] [.gif] [.png] です。',
    'mime_csv'             => ':attribute の対応拡張子は [.csv] です。',
    'role_name'            => ':attribute の名称が不正です。',
    'input_status'         => ':attribute を変更する権限が無いか、値が不正です。',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
