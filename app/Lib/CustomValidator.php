<?php

namespace App\Lib;

/**
 * カスタムバリデート実装クラス
 * 
 * @author Kuniyasu Wada
 */
class CustomValidator
{
	/**
	 * 真であるかどうか
	 */
	public function validateTrue($attribute, $value, $parameters)
	{
		return in_array($value, [true, 1, '1']);
	}
	
	/**
	 * 偽であるかどうか
	 */
	public function validateFalse($attribute, $value, $parameters)
	{
		return in_array($value, [false, 0, '0']);
	}
	
	/**
	 * 権限グループに属しているか判定
	 *
	 * @param $attribute
	 * @param $value
	 * @param $parameters
	 * @return int
	 */
	public function validateRoleName($attribute, $value, $parameters)
	{
		return array_key_exists($value, \Config::get('Fixed.role'));
	}
	
	/**
	 * 権限別にで入力ステータスをバリデート
	 *
	 * @param $attribute
	 * @param $value
	 * @param $parameters
	 * @return int
	 */
	public function validateInputStatus($attribute, $value, $parameters)
	{
		// 入力ステータス配列内に存在するか
		if( !array_key_exists($value, \Config::get('Fixed.input_status')) )
			return false;
		
		// 運用担当者はステータスを確定にする事は不可
		if(\Auth::guard('user')->user()->role === 'OPERATOR' && ($value == 3 || $value == 4) )
			return false;
			
		return true;
	}
	
	/**
	 * 日本の郵便番号形式チェック
	 * 
	 * @param $attribute
	 * @param $value
	 * @param $parameters
	 * @return int
	 */
	public function validateJpZipCode($attribute, $value, $parameters)
	{
		return preg_match("/^[0-9\-]{7,8}+$/i", $value);
	}
	
	/**
	 * クレジットカード番号
	 *
	 * @param $attribute
	 * @param $value
	 * @param $parameters
	 * @return int
	 */
	public function validateCardNumber($attribute, $value, $parameters)
	{
		return preg_match("/^(4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|3[47][0-9]{13}|3(?:0[0-5]|[68][0-9])[0-9]{11}|6(?:011|5[0-9]{2})[0-9]{12}|^(?:2131|1800|35\d{3})\d{11}$)$/", $value);
	}
	
	/**
	 * クレジットカード名義人名用
	 * (半角大文字アルファベットと姓名の間の半角スペースのみ許可する)
	 *
	 * @param $attribute
	 * @param $value
	 * @param $parameters
	 * @return int
	 */
	public function validateCardHolderName($attribute, $value, $parameters)
	{
		return preg_match("/^[A-Z]+\s[A-Z]+\z/", $value);
	}
	
	/**
	 * セキュリティコード
	 *
	 * @param $attribute
	 * @param $value
	 * @param $parameters
	 * @return int
	 */
	public function validateSecurityCode($attribute, $value, $parameters)
	{
		return preg_match("/^[0-9]{3,4}\z/", $value);
	}
	
	/**
	 * 有効期限 （MMYY）
	 * 
	 * @param $attribute
	 * @param $value
	 * @param $parameters
	 * @return int

	 */
	public function validateCardExpire($attribute, $value, $parameters)
	{
		// 形式チェック
		if (! preg_match('/^([0-9]{2})([0-9]{2})\z/', $value, $matches))
		{
			return false;
		} else {
			// 年月を変換する場合はここで
			$year = sprintf('20%s', $matches[1]);
			$month = $matches[2];
			
			// 日付妥当性チェック
			if (! checkdate($month, 1, $year)) {
				return false;
			}
			// 範囲
			$expiration = date('Y-m-d', mktime(0, 0, 0, $month, 1, $year));
			// 過去の場合
			$today = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
			if ($expiration < $today) {
				return false;
			}
			// 未来の場合（有効期限は最長10年程度？）
			$future = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y') + 10));
			if ($expiration > $future) {
				return false;
			}
		}
		return true;
	}

	/**
	 * 口座名義人カナ（全角）
	 *
	 * @param $attribute
	 * @param $value
	 * @param $parameters
	 * @return int
	 */
	public function validateAccountNameKana($attribute, $value, $parameters)
	{
		return preg_match("/^[ァ-ヶＡ-Ｚ０-９ー（）．−　]+$/u", $value);
	}
	
	/**
	 * 口座名義（全角）
	 * 全角文字のみかどうかを判定する
	 *
	 * @param $attribute
	 * @param $value
	 * @param $parameters
	 * @return int
	 */
	public function validateOnlyZenkaku($attribute, $value, $parameters)
	{
		$encoding = mb_internal_encoding();
		
		$len = mb_strlen($value, $encoding);
		
		for ($i = 0; $i < $len; $i++)
		{
			$char = mb_substr($value, $i, 1, $encoding);
			
			if ($this->validateOnlyHankaku(null, $char, null))
			{
				return false;
			}
		}
		return true;
	}
	
	/**
	 * 半角文字のみかどうかを判定する
	 * 
	 * @param $attribute
	 * @param $value
	 * @param $parameters
	 * @return int
	 */
	public function validateOnlyHankaku($attribute, $value, $parameters)
	{
		$encoding = mb_internal_encoding();
		$to_encoding = 'UTF-8';
		
		if (is_null($encoding)) {
			$encoding = mb_internal_encoding();
		}
		
		$str = mb_convert_encoding($value, $to_encoding, $encoding);
		
		if (strlen($str) === mb_strlen($str, $to_encoding)) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Mime Type Check of Images...
	 *
	 * @param $attribute
	 * @param $value
	 * @param $parameters
	 * @return int
	 */
	public function validateImages($attribute, $value, $parameters)
	{
		return in_array($value->getMimeType(), [
				'image/jpg',
				'image/jpeg',
				'image/pjpeg',
				'image/png',
				'image/x-png',
				'image/gif',
		]);
	}
	
	/**
	 * Mime Type Check of CSV...
	 *
	 * @param $attribute
	 * @param $value
	 * @param $parameters
	 * @return int
	 */
	public function validateCSV($attribute, $value, $parameters)
	{
		return in_array($value->getMimeType(), [
				'application/vnd.ms-excel',
				'text/csv',
				'text/plain',
				'text/tsv'
		]);
	}
}
