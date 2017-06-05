<?php

namespace App\Lib;

use Illuminate\Support\Collection;
use League\Csv\Reader;
use League\Csv\Writer;

/**
 * CSVライブラリ操作クラス
 *
 * @author Kuniyasu Wada
 */
class Csv
{
    protected $Reader;
    protected $Writer;
    protected $Csv;

    public function __construct()
    {
        //
    }

    /**
     * Readerインスタンスをセット
     *
     * @param  string $path
     * @param  string $open_mode
     * @return void
     */
    public function createReader($path, $open_mode = 'r+')
    {
        $this->Reader = Reader::createFromPath($path);
    }

    /**
     * Writerインスタンスをセット
     *
     * @param  string $path
     * @param  string $open_mode
     * @return void
     */
    public function createWriter($path, $open_mode = 'r+')
    {
        $this->Writer = Writer::createFromPath($path);
    }

    /**
     * レコード有無チェック
     *
     * @return bool
     */
    protected function validRecordsCount()
    {
        if( ! count($this->Csv) )
        {
            \Flash::error('ファイル内にデータが存在しません。');
            return true;
        }

        return false;
    }

    /**
     * 列数の正当性チェック
     *
     * @param  array $columns
     * @return bool
     */
    protected function validColumnCount($columns)
    {
        if( count($this->Csv[0]) !== count($columns) )
        {
            \Flash::error('レコードの列の数が合いません。');
            return true;
        }

        return false;
    }

    /**
     * カラム名を割り当てながら取り込み処理を実施
     *
     * @param  array $columns
     * @param  bool $isSkip
     * @return Collection
     */
    protected function assignColumns($columns, $isSkip = false)
    {
        $result = [];
        foreach ($this->Csv as $key => $row)
        {
            // インデックス行はスキップ
            if( $key === 0 && $isSkip ) continue;

            $input = [];
            foreach ( $columns as $col )
            {
                $value = array_shift($row);
                $input[$col] = mb_convert_encoding($value, 'UTF-8', 'SJIS-win');
            }

            $result[] = $input;
        }

        return collect($result);
    }

    /**
     * Setter...
     */
    public function setCsv($csv)
    {
        $this->Csv = $csv;
        return $this;
    }

    /**
     * Getter...
     */
    public function getReader()
    {
        return $this->Reader;
    }

    public function getWriter()
    {
        return $this->Writer;
    }

    public function getCsv()
    {
        return $this->Csv;
    }

}
