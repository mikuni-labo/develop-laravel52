<?php

namespace App\Lib;

use App\Lib\cURL;
use App\Models\Program;
use App\Models\Episode;
use App\Models\Code;
use App\Models\TxVideo;
use App\Models\GyaoVideo;
use App\Models\NicoVideo;
use Carbon\Carbon;

/**
 * ブライトコーブAPI関連共通処理クラス
 * 
 * @author Kuniyasu Wada
 */
class BCAPI
{
	/**
	 * request to CMS API to Create video object
	 */
	public static function createObject($param)
	{
		$request_url = urlencode(\Config::get('BCAPI.BRIGHTCOVE_API.CMS_URL') . \Config::get('BCAPI.VIDEOCLOUD.ACCOUNT_ID') . "/videos");
		
		$request_body = [
				"folder name"       => urlencode($param["title"]),// タイトル
				"description"       => urlencode($param["comment"]),
				"long_description"  => urlencode($this->arr_companies[$param["company_id"]]),
				"state"             => "ACTIVE"
				,"schedule" => [
						"starts_at" => "{$param["release_date_y"]}-{$param["release_date_m"]}-{$param["release_date_d"]}",
						"ends_at"   => "{$param["end_date_y"]}-{$param["end_date_m"]}-{$param["end_date_d"]}"
				],
				"reference_id"      => "ref_id_terry_DIproxytest",  // 参照ID
				"name"              => "terry_DI_testDIproxytest",  // 名前・タイトル
				"short_description" => "terry_DI_testDIproxytest",  // 短い説明文
				"long_description"  => "terry_di_testDIproxytest desu Proxy API changed",  // 長い説明文
				"link_url"          => "http://www.brightcove.com",  // 関連リンク URL
				"link_text"         => "related text",  // 関連リンクテキスト
				"tags"              => "tag1,tag2,tag3",  // タグ（カンマ区切り）
				
		];
		
		$request_body = json_encode($request_body);
		$post_fields = "client_id=" . \Config::get('BCAPI.VIDEOCLOUD.CLIENT_ID')     . "&" .
				"client_secret="    . \Config::get('BCAPI.VIDEOCLOUD.CLIENT_SECRET') . "&" .
				"url="              . $request_url  . "&" .
				"requestBody="      . $request_body . "&" .
				"requestType="      . "POST";
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, \Config::get('BCAPI.BRIGHTCOVE_API.CMS_PROXY'));
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($ch);
		curl_close($ch);
		
		$cms_res = json_decode($response);
		return $cms_res;
	}
	
	/**
	 * request to DI API for Dynamic Ingest
	 */
	public static function dynamicIngest($cms_res)
	{
		//S3保存時
		$movie_url     = S3_MOVIE_URL.S3_UPLOAD_MOVIE_DIR.$_SESSION["form.parms"]["movie_name"];
		$thumimage_url = S3_IMAGE_URL.S3_UPLOAD_IMAGE_DIR.$_SESSION["form.parms"]["thumbimage_name"];
		
		$request_url = \Config::get('BCAPI.BRIGHTCOVE_API.DI_URL') . \Config::get('BCAPI.VIDEOCLOUD.ACCOUNT_ID');
		$request_url.= "/videos/" . /* ブライトコーブ動画ID .*/ "/ingest-requests";
		$request_url = urlencode($request_url);
		
		//同一画像でスチル画像も設定する（サイズは別）
		if($_SESSION["form.parms"]["thumbimage_name"]){
			$request_body = json_encode([
					"master" => [
							"url" => urlencode($movie_url)
					],
					"profile"   => \Config::get('BCAPI.BRIGHTCOVE_API.DI_PROFILE'),
					"poster"    => [
							"url" => urlencode($thumimage_url),
							"width"  => DI_POSTER_WIDTH,
							"height" => DI_POSTER_HEIGHT
					],
					"thumbnail" => [
							"url"    => urlencode($thumimage_url),
							"width"  => DI_THUMBNAIL_WIDTH,
							"height" => DI_THUMBNAIL_HEIGHT
					],
					"callbacks" => URL_HTTPS.DI_CALLBACK_URL,
					"capture-images" =>  FALSE,//自動生成オフ
			]);
		}
		else {
			$request_body = json_encode([
					"master"         => [
							"url" => urlencode($movie_url)
					],
					"profile"        => \Config::get('BCAPI.BRIGHTCOVE_API.DI_PROFILE'),
					"capture-images" => TRUE,//自動生成オン
					"callbacks" => URL_HTTPS.DI_CALLBACK_URL,
			]);
		}
		
		$post_fields = "client_id=" . \Config::get('BCAPI.VIDEOCLOUD.CLIENT_ID')     . "&" .
				"client_secret="    . \Config::get('BCAPI.VIDEOCLOUD.CLIENT_SECRET') . "&" .
				"url="              . $request_url  . "&" .
				"requestBody="      . $request_body . "&" .
				"requestType="      . "POST";
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, \Config::get('BCAPI.BRIGHTCOVE_API.DI_PROXY'));
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($ch);
		curl_close($ch);
		
		$di_res = json_decode($response);
		return $di_res;
	}
	
}
