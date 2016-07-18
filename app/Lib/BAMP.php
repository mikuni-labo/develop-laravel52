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
 * BAMP関連共通処理クラス
 * 
 * @author Kuniyasu Wada
 */
class BAMP
{
	/**
	 * cURL関数でBAMP(地上波)への接続を行い、JSONをパースして返す
	 *
	 * @param string or array txcms_program_id
	 * @return JSON $response
	 */
	public static function accessToBamp($params)
	{
		$param = \Util::implodeArrToString($params, ',');
		
		$ch = new cURL();
		$ch->init();
		$ch->setUrl(\Config::get('TX.BAMP.URL'));
		$ch->setUserAgent('Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1');
		$ch->setMethod('POST');
		$ch->setUserPwd(\Config::get('TX.BAMP.AUTH_USER'), \Config::get('TX.BAMP.AUTH_PASS'));
		$ch->setParameter('txcms_program_id', $param);
		//$ch->setHeader($header);
		
		$response = $ch->exec();
		
		//$ch->getInfo();
		//$ch->getErrorMessage();
		
		$ch->close();
		
		return json_decode($response);
	}
	
	/**
	 * cURL関数でBAMP:BSJ(BS放送)への接続を行い、JSONをパースして返す
	 *
	 * @param string or array txcms_program_id
	 * @return JSON $response
	 */
	public static function accessToBSJ($params)
	{
		$param = \Util::implodeArrToString($params, ',');
	
		$ch = new cURL();
		$ch->init();
		$ch->setUrl(\Config::get('TX.BSJ.URL'));
		$ch->setUserAgent('Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1');
		$ch->setMethod('POST');
		$ch->setUserPwd(\Config::get('TX.BSJ.AUTH_USER'), \Config::get('TX.BSJ.AUTH_PASS'));
		$ch->setParameter('txcms_program_id', $param);
		//$ch->setHeader($header);
		
		$response = $ch->exec();
		
		//$ch->getInfo();
		//$ch->getErrorMessage();
		
		$ch->close();
		
		return json_decode($response);
	}
	
	/**
	 * BAMP またはBSJから取り込んだJSONデータを処理する
	 * 
	 * @param JSON $data
	 * @param string $mode
	 * @return boolean
	 */
	public static function getBampData($data, $mode)
	{
		try {
			// 取り込み開始
			if(count($data->programs) > 0)
			{
				$source = array_flip(\Config::get('TX.source'));
				
				\DB::beginTransaction();
				
				/*
				 * プログラムループ
				 */
				foreach ($data->programs as $key => $program)
				{
//if($key == 5){
					if($program->delete_flag === true)
						continue;
						
					// 既存データの存在確認
					$resultProgram = Program::getProgramWithTxCmsProgramID($program->txcms_program_id, $source[$mode]);
					$inputs = [];
					$inputs['source'] = $source[$mode];
					$objProgram = false;// 後に必要なので定義しておく
					
					// INSERT
					if(count($resultProgram) == 0)
					{
						// キーマッピング配列を取得
						$keyMap = Program::getInsertKeyMap();
						
						foreach ($program as $_k => $_v)
						{
							// 値があればマッピングしたカラム名で格納
							if( !empty($_v) && array_key_exists($_k, $keyMap) )
								$inputs[$keyMap[$_k]] = $_v;
						}
						
						$inputs = BAMP::cleanFormat($inputs, 'program');
						$objProgram = Program::create($inputs);
					}
					// UPDATE
					elseif ($program->modified_on != $resultProgram[0]->bamp_modified_on)// BAMP側で更新があったかを判定
					{
						// 論理削除されていないデータ
						if( !empty($objProgram = Program::find($resultProgram[0]->id)) )
						{
							// キーマッピング配列を取得
							$keyMap = Program::getUpdateKeyMap();
							
							foreach ($program as $_k => $_v)
							{
								// 値があればマッピングしたカラム名で格納
								if( !empty($_v) && array_key_exists($_k, $keyMap) )
									$inputs[$keyMap[$_k]] = $_v;
							}
							$inputs = BAMP::cleanFormat($inputs, 'program');
							$objProgram->update($inputs);
						}
					}
					
					$mainFlagOnCodes = [];
						
					/*
					 * コードループ
					*/
					foreach ($program->codes as $code)
					{
						$resultCode = Code::getCodeWithEpgAndTxCmsProgramID($program->txcms_program_id, $code->code, $source[$mode]);
						$inputs = [];
						$inputs['source'] = $source[$mode];
						
						// INSERT
						if(count($resultCode) == 0)
						{
							// キーマッピング配列を取得
							$keyMap = Code::getInsertKeyMap();
							
							foreach ($code as $_k => $_v)
							{
								// 値があればマッピングしたカラム名で格納
								if( !empty($_v) && array_key_exists($_k, $keyMap) )
									$inputs[$keyMap[$_k]] = $_v;
							}
							// TXWEB番組マスタID
							$inputs['txcms_program_id'] = $program->txcms_program_id;
							$objCode = Code::create($inputs);
						}
						// UPDATE
						elseif ($code->modified_on != $resultCode[0]->bamp_modified_on)// BAMP側で更新があったかを判定
						{
							// 論理削除されていないデータ
							if( !empty($objCode = Code::find($resultCode[0]->id)) )
							{
								// キーマッピング配列を取得
								$keyMap = Code::getUpdateKeyMap();
								
								foreach ($code as $_k => $_v)
								{
									// 値があればマッピングしたカラム名で格納
									if( !empty($_v) && array_key_exists($_k, $keyMap))
										$inputs[$keyMap[$_k]] = $_v;
								}
								$objCode->update($inputs);
							}
						}
						
						// BSJからのデータはmain_flag が false固定なので、全て取得する
						if($mode === 'BSJ')
							$mainFlagOnCodes[] = $code->code;
						
						// 本編フラグがTRUEのコードを配列へ
						elseif( $code->main_flag === true )
							$mainFlagOnCodes[] = $code->code;
					}
					
					/*
					 * エピソードループ
					 */
					foreach ($program->episodes as $k => $episode)
					{
						$oa_date = new Carbon($episode->oa_date);
						
						$resultEpisodes = Episode::getEpisode($program->txcms_program_id, $episode->code, $oa_date->format('Y-m-d'), $source[$mode]);
						$inputs = [];
						$inputs['source'] = $source[$mode];
						
						// 本編フラグ がTRUE, かつ再放送フラグ がFALSE, かつ削除フラグがFALSE
						if ( in_array( $episode->code, $mainFlagOnCodes )
								&& $episode->oa_repeat_flag === false
								&& $episode->delete_flag === false )
						{
							// INSERT
							if(count($resultEpisodes) == 0)
							{
								// キーマッピング配列を取得
								$keyMap = Episode::getInsertKeyMap();
								
								foreach ($episode as $_k => $_v)
								{
									// 値があればマッピングしたカラム名で格納
									if( !empty($_v) && array_key_exists($_k, $keyMap))
										$inputs[$keyMap[$_k]] = $_v;
								}
								$inputs = BAMP::cleanFormat($inputs, 'episode');
									
								// TXWEB番組マスタID
								$inputs['txcms_program_id'] = $program->txcms_program_id;
								$objEpisode = Episode::create($inputs);
								
								// 各シンジケーションテーブルへ、ユニークIDとともに新規登録
								TxVideo::InsertWithTxUniqueID($objEpisode->id);
								GyaoVideo::InsertWithGyaoUniqueID($objEpisode->id);
								NicoVideo::InsertWithNicoUniqueID($objEpisode->id);
								
							}
							// UPDATE
							elseif ($episode->modified_on == $resultEpisodes[0]->bamp_modified_on)// BAMP側で更新があったかを判定
							{
								// 論理削除されていないデータ
								if( !empty($objEpisode = Episode::find($resultEpisodes[0]->id)) )
								{
									// キーマッピング配列を取得
									$keyMap = Episode::getUpdateKeyMap();
										
									foreach ($episode as $_k => $_v)
									{
										// 値があればマッピングしたカラム名で格納
										if( !empty($_v) && array_key_exists($_k, $keyMap))
											$inputs[$keyMap[$_k]] = $_v;
									}
									$inputs = BAMP::cleanFormat($inputs, 'episode');
										
									$objEpisode->update($inputs);
								}
							}
						}
						// 削除フラグが立っていて、かつEPGコードがメインフラグでないエピソードは紐付け解除で保留対象データ
						elseif ( !in_array( $episode->code, $mainFlagOnCodes)
								&& $episode->delete_flag === false
								&& count($resultEpisodes) > 0 )
						{
							// 論理削除されていないデータ
							if( count($objEpisode = Episode::find($resultEpisodes[0]->id)) > 0 )
							{
								// 各配信先で一箇所でも配信中なら保留フラグを立てる
								if( (bool)$objEpisode->tx_provide_status === true
									|| (bool)$objEpisode-> gyao_provide_status === true
									|| (bool)$objEpisode->nico_provide_status === true
									|| (bool)$objEpisode->tver_provide_status === true )
								{
									$objEpisode->update(['hold_flag' => true]);
								}
								else 
									$objEpisode->delete();
							}
						}
					}
//}
				}
				\DB::commit();
				\Flash::success('EPG情報を取得しました。');
				return $objProgram;// add → edit画面へジャンプさせるため、最後に取得したPROGRAMオブジェクトを返す
			}
			else {
				\Flash::error('取得可能なEPG情報がありませんでした。');
			}
		}
		catch (\Exception $e) {
			\Flash::error($e->getMessage());
		}
		return false;
	}
	
	/**
	 * 本編フラグ切り替え
	 *
	 * @param Request $param
	 * @param integer $source
	 */
	public static function changeHonpenFlag($request, $source)
	{
		$honpens = isset($request->code_honpen_flag) ? $request->code_honpen_flag : [];
		$codes = Code::getMainFlgOnCodes($request->txcms_program_id, $source);
		
		if(count($codes) > 0)
		{
			foreach($codes as $key => $val)
			{
				$Code = Code::find($val->id);
				
				// 現在のフラグと比較し、本編に切り替わる場合、対応するミニ枠も決定し、付随するマージ処理を行う
				if(array_key_exists($val->id, $honpens) && $val->honpen_flag == '0')
				{
					$Code->update(['honpen_flag' => '1']);
					BAMP::mergeMiniFrame($request->txcms_program_id, $val->epg_code, $source);
				}
				// 現在のフラグと比較し、本編フラグが解除になる場合、本編のデータをマージ前に戻す
				elseif ( !(array_key_exists($val->id, $honpens)) && $val->honpen_flag == '1')
				{
					$Code->update(['honpen_flag' => '0']);
					
					// ミニ枠とペアになっている本編のみ取得
					$honpen_episodes = Episode::getHonpenData($request->txcms_program_id, $val->epg_code, $source);
					BAMP::resetMiniFrame($honpen_episodes);
				}
			}
		}
	}
	
	/**
	 * 本編にミニ枠をマージする
	 *
	 * @param $txcms_program_id
	 * @param $epg_code
	 * @param $source
	 */
	public static function mergeMiniFrame($txcms_program_id, $epg_code, $source)
	{
		$resultEpisodes = Episode::getEpisodesWithTxCmsProgramID($txcms_program_id, $source);
		
		if(count($resultEpisodes) > 0)
		{
			foreach($resultEpisodes as $key => $val)
			{
				// 本編フラグに設定されたEPGコード
				if($val->epg_code == $epg_code)
				{
					foreach ($resultEpisodes as $k => $v)
					{
						// 本編フラグではないEPGコード、かつOA日が同日
						if( ($v->epg_code != $epg_code) && ($val->oa_date == $v->oa_date) )
						{
							$Honpen = Episode::withTrashed()->find($val->id);
							$Mini   = Episode::withTrashed()->find($v->id);
								
							// ミニ枠 -> 本編
							if( $val->oa_start_time == $v->oa_end_time )
							{
								$Honpen->update([
										'oa_start_time' => $v->oa_start_time,
										'target_mini_id' => $v->id,
								]);
								
								$Mini->update([
										'target_honpen_id' => $val->id,
								]);
							}
							// 本編 -> ミニ枠
							elseif( $val->oa_end_time == $v->oa_start_time )
							{
								$Honpen->update([
										'oa_end_time' => $v->oa_end_time,
										'target_mini_id' => $v->id,
								]);
								
								$Mini->update([
										'target_honpen_id' => $val->id,
								]);
							}
						}
					}
				}
			}
		}
	}
	
	/**
	 * 本編のデータをマージ前に戻す
	 *
	 * @param $honpen_episodes
	 */
	public static function resetMiniFrame($honpen_episodes)
	{
		if(count($honpen_episodes) > 0)
		{
			foreach ($honpen_episodes as $key => $honpen)
			{
				$Honpen = Episode::withTrashed()->find($honpen->id);
				$Mini   = Episode::withTrashed()->find($honpen->target_mini_id);
				
				// 開始時間が同時間
				if( $honpen->oa_start_time == $Mini->oa_start_time )
				{
					$Honpen->update([
							'oa_start_time' => $Mini->oa_end_time,
							'target_mini_id' => null,
					]);
						
					$Mini->update([
							'target_honpen_id' => null,
					]);
				}
				// 終了時間が同時間
				elseif( $honpen->oa_end_time == $Mini->oa_end_time )
				{
					$Honpen->update([
							'oa_end_time' => $Mini->oa_start_time,
							'target_mini_id' => null,
					]);
						
					$Mini->update([
							'target_honpen_id' => null,
					]);
				}
			}
		}
	}
	
	/**
	 * Clean Format...
	 *
	 * @param $param
	 * @param $mode
	 */
	public static function cleanFormat($param, $mode)
	{
		// DB格納前にモード毎にデータフォーマットを整える
		switch ($mode)
		{
			case ('program'):
				if(!empty($param['oa_start_time']))
					$param['oa_start_time'] .= '00';
						
					if(!empty($param['oa_end_time']))
						$param['oa_end_time'] .= '00';
					
						break;
					
			case ('episode'):
				if(!empty($param['oa_start_time']))
					$param['oa_start_time'] .= '00';
						
					if(!empty($param['oa_end_time']))
						$param['oa_end_time'] .= '00';
						
						break;
						
			default:
		}
		return $param;
	}
	
}
