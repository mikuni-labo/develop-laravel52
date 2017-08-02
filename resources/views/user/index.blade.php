@extends('layouts.base')

@section('meta')
    <title>ユーザー情報一覧｜{{ $Fixed['info']['SiteName'] }}</title>
@endsection

@include('layouts.gnavi')

@section('content')
    <section class="section">
        <div class="container">
            <div class="row animated fadeIn">
                <div class="col-md-4 col-md-offset-1">
                    <ul class="breadcrumb">
                        <li><a href="/">Home</a></li>
                        <li class="active">User</li>
                    </ul>
                </div><!-- .col -->
            </div><!-- .row -->
        </div><!-- .container -->
    </section>
    <section class="section">
        <div class="container">
            <div class="row animated fadeIn">
                <div class="col-md-12 col-md-offset-0">
                    
                    @include('flash::message')
                    
                    <h2 class="h2 page-header animated bounce"><span class="glyphicon glyphicon-search"></span>&nbsp;ユーザ検索</h2>
                    
                    {!! Form::open(['class' => 'form-horizontal']) !!}
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped table-condensed">
                                <colgroup>
                                    <col width="15%">
                                    <col width="35%">
                                    <col width="15%">
                                    <col width="35%">
                                </colgroup>
                                
                                <thead></thead>
                                <tbody>
                                    <tr>
                                        <th class="text-center" colspan="1">ユーザID</th>
                                        <td class="text-center" colspan="1">
                                            <div class="{{ $errors->has('search_user_id') ? 'has-error' : '' }}">
                                                {!! Form::text('search_user_id', isset($search['search_user_id']) ? $search['search_user_id'] : '', ['class' => 'form-control', 'maxlength' => '255', 'placeholder' => '']) !!}
                                                
                                                @if ($errors->has('search_user_id'))
                                                    <span class="help-block"><strong>{{ $errors->first('search_user_id') }}</strong></span>
                                                @endif
                                            </div>
                                        </td>
                                        <th class="text-center" colspan="1">ステータス</th>
                                        <td class="text-center" colspan="1">
                                            <div class="{{ $errors->has('search_status_on') || $errors->has('search_status_off') ? 'has-error' : '' }}">
                                                <label>{!! Form::checkbox('search_status_on',  '1', (isset($search['search_status_on'])  && $search['search_status_on']  == 1) ? true : false, ['maxlength' => '1']) !!}&nbsp;<span class="text-success">有効</span></label>&nbsp;&nbsp;
                                                <label>{!! Form::checkbox('search_status_off', '1', (isset($search['search_status_off']) && $search['search_status_off'] == 1) ? true : false, ['maxlength' => '1']) !!}&nbsp;<span class="text-danger">無効</span></label>
                                                
                                                @if ($errors->has('search_status_on') || $errors->has('search_status_off'))
                                                    <span class="help-block"><strong>{{ $errors->first('search_status_on') }}</strong></span>
                                                    <span class="help-block"><strong>{{ $errors->first('search_status_off') }}</strong></span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-center" colspan="1">ユーザ名</th>
                                        <td class="text-center" colspan="1">
                                            <div class="{{ $errors->has('search_user_name') ? 'has-error' : '' }}">
                                                {!! Form::text('search_user_name', isset($search['search_user_name']) ? $search['search_user_name'] : '', ['class' => 'form-control', 'maxlength' => '255', 'placeholder' => '']) !!}
                                                
                                                @if ($errors->has('search_user_name'))
                                                    <span class="help-block"><strong>{{ $errors->first('search_user_name') }}</strong></span>
                                                @endif
                                            </div>
                                        </td>
                                        <th class="text-center" colspan="1">メールアドレス</th>
                                        <td class="text-center" colspan="1">
                                            <div class="{{ $errors->has('search_email') ? 'has-error' : '' }}">
                                                {!! Form::text('search_email', isset($search['search_email']) ? $search['search_email'] : '', ['class' => 'form-control', 'maxlength' => '255', 'placeholder' => '']) !!}
                                                
                                                @if ($errors->has('search_email'))
                                                    <span class="help-block"><strong>{{ $errors->first('search_email') }}</strong></span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-center" colspan="1">削除済みデータ表示</th>
                                        <td class="text-center" colspan="1">
                                            <div class="{{ $errors->has('search_delete_flag') ? 'has-error' : '' }}">
                                                <label>{!! Form::checkbox('search_delete_flag', '1', (isset($search['search_delete_flag']) && $search['search_delete_flag'] == 1) ? true : false, ['maxlength' => '1']) !!}&nbsp;<span class="text-success">ON</span></label>
                                                
                                                @if ($errors->has('search_delete_flag'))
                                                    <span class="help-block"><strong>{{ $errors->first('search_delete_flag') }}</strong></span>
                                                @endif
                                            </div>
                                        </td>
                                        <th class="text-center" colspan="1"></th>
                                        <td class="text-center" colspan="1">
                                        
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center">
                            <a href="/user/search/reset" class="btn btn-default"><span class="glyphicon glyphicon-refresh"></span>&nbsp;検索条件クリア</a>
                            <button class="btn btn-info" type="submit"><span class="glyphicon glyphicon-search"></span>&nbsp;検索する</button>
                        </div>
                    {!! Form::close() !!}
                    
                </div><!-- .col -->
            </div><!-- .row -->
        </div><!-- .container -->
    </section>
    <section class="section">
        <div class="container">
            <div class="row animated fadeIn">
                <div class="col-md-12 col-md-offset-0">
                    
                    <h2 class="h2 page-header animated bounce"><span class="glyphicon glyphicon-user"></span>&nbsp;ユーザ一覧</h2>
                    
                    @if( count($results) )
                        {!! Form::open(['class' => 'form-horizontal']) !!}
                            <table id="user-index-table" class="table table-hover table-condensed table-striped">
                                <colgroup>
                                    <col width="8%">
                                    <col width="8%">
                                    <col width="21%">
                                    <col width="28%">
                                    <col width="15%">
                                    <col width="10%">
                                    <col width="5%">
                                    <col width="5%">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th class="text-center">test</td>
                                        <th class="text-center">ID</td>
                                        <th class="text-center">ユーザー名</td>
                                        <th class="text-center">メールアドレス</td>
                                        <th class="text-center">権限</td>
                                        <th class="text-center">ステータス</td>
                                        <th class="text-center">編集</td>
                                        <th class="text-center">削除</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach( $results as $result )
                                        <tr class="<?php if($result->status == '0' || !empty($result->deleted_at)):?>gray_out<?php endif;?>">
                                            <td class="text-center">{!! Form::checkbox('test[]', $result->id, null, []) !!}</td>
                                            <td class="text-center">{{ $result->id }}</td>
                                            <td class="text-center">{{ $result->last_name }} {{ $result->first_name }}</a></td>
                                            <td class="text-center">{{ $result->email }}</a></td>
                                            <td class="text-center">{{ $Fixed['role'][$result->role] }}</a></td>
                                            <td class="text-center">@if( $result->status == '1' ) <span class="text-success">有効</span> @else <span class="text-danger">無効</span> @endif</td>
                                            
                                            <td class="text-center"><a href="/user/edit/{{ $result->id }}" class="btn btn-sm btn-success">
                                                <span class="glyphicon glyphicon-pencil"></span></a>
                                            </td>
                                            <td class="text-center">
                                                @if($result->deleted_at)
                                                    <a href="/user/restore/{{ $result->id }}" class="btn btn-sm btn-info" data-toggle="confirmation" onclick="if(!confirm('復旧させますか?')) return false;">
                                                    <span class="glyphicon glyphicon-repeat"></span></a>
                                                @else
                                                    <a href="/user/delete/{{ $result->id }}" class="btn btn-sm btn-danger" data-toggle="confirmation" onclick="if(!confirm('本当に削除しますか?')) return false;">
                                                    <span class="glyphicon glyphicon-trash"></span></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            
                            <div class="text-center">
                                <a href="/user/add" class="btn btn-info"><span class="glyphicon glyphicon-import"></span>&nbsp;ユーザ登録</a>
                                <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-import"></span>&nbsp;登録</button>
                            </div>
                        {!! Form::close() !!}
                    @else
                        <p>条件に一致するデータがありません...</p>
                    @endif
                </div><!-- .col -->
            </div><!-- .row -->
        </div><!-- .container -->
    </section>
@endsection

@include('layouts.footer')

@section('script')
    <script type="text/javascript">
        jQuery(function($){
            $.extend( $.fn.dataTable.defaults, { 
                language: {
                    sProcessing: "処理中...",
                    sLengthMenu: "_MENU_ 件表示",
                    sZeroRecords: "データはありません。",
                    sInfo: " _TOTAL_ 件中 _START_ - _END_ まで表示",
                    sInfoEmpty: " 該当 0 件",
                    sInfoFiltered: "（全 _MAX_ 件より抽出）",
                    sInfoPostFix: "",
                    sSearch: "検索:",
                    sUrl: "",
                    oPaginate: {
                        sFirst: "&lt;",
                        sPrevious: "&lt;",
                        sNext: "&gt;",
                        sLast: "&gt;&gt;"
                    }
                } 
            }); 
            
            $("#user-index-table").DataTable({
                // 件数切替機能 無効
/*                 lengthChange: false, */

                // 検索機能 無効
/*                 searching: false, */

                // ソート機能 無効
/*                 ordering: false, */

                // 情報表示 無効
/*                 info: false, */

                // ページング機能 無効
/*                 paging: false, */

                // 初期表示時優先並び替え順（[ [ 列番号, 昇順降順 ], ... ] の形式で、指定しない場合は空配列）
/*                 order: [0, "asc"], */
                
                // 横スクロールバーを有効にする (scrollXはtrueかfalseで有効無効を切り替えます)
/*                 scrollX: true */
                
                // 縦スクロールバーを有効にする (scrollYは200, "200px"など「最大の高さ」を指定します)
/*                 scrollY: true */
                
                // 各列デフォルト値
                columnDefs: [
/*                     { targets: 0, visible: false },// 非表示 */
/*                     { targets: 1, width: 150 }// 幅 */
                ],

                // 件数切替の値を10～50の10刻みにする
/*                 lengthMenu: [ 10, 20, 30, 40, 50 ], */
                lengthMenu: [ 2, 10, 20, 30, 40, 50 ],
                
                // 件数のデフォルトの値を50にする
                displayLength: 50, 
                
                // 状態を保存する機能をつける
                stateSave: true,
            });
        });
    </script>
@endsection