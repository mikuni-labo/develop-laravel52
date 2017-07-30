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
                            <a href="/user/search/reset" class="btn btn-primary"><span class="glyphicon glyphicon-refresh"></span>&nbsp;検索条件クリア</a>
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
                    
                    {!! $results->render() !!}
                    
                    @if(count($results) > 0)
                            <table id="user-index-table" class="table table-hover table-condensed">
                                <colgroup>
                                    <col width="8%">
                                    <col width="21%">
                                    <col width="26%">
                                    <col width="15%">
                                    <col width="10%">
                                    <col width="10%">
                                    <col width="10%">
                                </colgroup>
                                
                                <tr>
                                    <th class="text-center">ID</td>
                                    <th class="text-center">ユーザー名</td>
                                    <th class="text-center">メールアドレス</td>
                                    <th class="text-center">権限</td>
                                    <th class="text-center">ステータス</td>
                                    <th class="text-center">編集</td>
                                    <th class="text-center">削除</td>
                                </tr>
                                
                                @foreach($results as $result)
                                    <tr class="<?php if($result->status == '0' || !empty($result->deleted_at)):?>gray_out<?php endif;?>">
                                        <td class="text-center">{{ $result->id }}</td>
                                        <td class="text-center">{{ $result->last_name }} {{ $result->first_name }}</a></td>
                                        <td class="text-center">{{ $result->email }}</a></td>
                                        <td class="text-center">{{ $Fixed['role'][$result->role] }}</a></td>
                                        <td class="text-center">@if( $result->status == '1' ) <span class="text-success">有効</span> @else <span class="text-danger">無効</span> @endif</td>
                                        
                                        <td class="text-center"><a href="/user/edit/{{ $result->id }}" class="btn btn-sm btn-success">
                                            <span class="glyphicon glyphicon-edit"></span>&nbsp;編集</a>
                                        </td>
                                        <td class="text-center">
                                            @if($result->deleted_at)
                                                <a href="/user/restore/{{ $result->id }}" class="btn btn-sm btn-info" data-toggle="confirmation" onclick="if(!confirm('復旧させますか?')) return false;">
                                                <span class="glyphicon glyphicon-repeat"></span>&nbsp;復旧</a>
                                            @else
                                                <a href="/user/delete/{{ $result->id }}" class="btn btn-sm btn-danger" data-toggle="confirmation" onclick="if(!confirm('本当に削除しますか?')) return false;">
                                                <span class="glyphicon glyphicon-trash"></span>&nbsp;削除</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                    @else
                        <p>条件に一致するデータがありません...</p>
                    @endif
                    
                    {!! $results->render() !!}
                    
                    <table id="foo" class="table table-bordered">
                        <thead>
                            <tr><th>No</th><th>都道府県</th></tr>
                        </thead>
                        <tbody>
                            <tr><td>1</td><td>北海道</td></tr>
                            <tr><td>2</td><td>青森県</td></tr>
                            <tr><td>3</td><td>岩手県</td></tr>
                            <tr><td>4</td><td>宮城県</td></tr>
                            <tr><td>5</td><td>秋田県</td></tr>
                            <tr><td>6</td><td>山形県</td></tr>
                            <tr><td>7</td><td>福島県</td></tr>
                            <tr><td>8</td><td>茨城県</td></tr>
                            <tr><td>9</td><td>栃木県</td></tr>
                            <tr><td>10</td><td>群馬県</td></tr>
                            <tr><td>11</td><td>埼玉県</td></tr>
                            <tr><td>12</td><td>千葉県</td></tr>
                            <tr><td>13</td><td>東京都</td></tr>
                            <tr><td>14</td><td>神奈川県</td></tr>
                            <tr><td>15</td><td>新潟県</td></tr>
                            <tr><td>16</td><td>富山県</td></tr>
                            <tr><td>17</td><td>石川県</td></tr>
                            <tr><td>18</td><td>福井県</td></tr>
                            <tr><td>19</td><td>山梨県</td></tr>
                            <tr><td>20</td><td>長野県</td></tr>
                            <tr><td>21</td><td>岐阜県</td></tr>
                            <tr><td>22</td><td>静岡県</td></tr>
                            <tr><td>23</td><td>愛知県</td></tr>
                            <tr><td>24</td><td>三重県</td></tr>
                            <tr><td>25</td><td>滋賀県</td></tr>
                            <tr><td>26</td><td>京都府</td></tr>
                            <tr><td>27</td><td>大阪府</td></tr>
                            <tr><td>28</td><td>兵庫県</td></tr>
                            <tr><td>29</td><td>奈良県</td></tr>
                            <tr><td>30</td><td>和歌山県</td></tr>
                            <tr><td>31</td><td>鳥取県</td></tr>
                            <tr><td>32</td><td>島根県</td></tr>
                            <tr><td>33</td><td>岡山県</td></tr>
                            <tr><td>34</td><td>広島県</td></tr>
                            <tr><td>35</td><td>山口県</td></tr>
                            <tr><td>36</td><td>徳島県</td></tr>
                            <tr><td>37</td><td>香川県</td></tr>
                            <tr><td>38</td><td>愛媛県</td></tr>
                            <tr><td>39</td><td>高知県</td></tr>
                            <tr><td>40</td><td>福岡県</td></tr>
                            <tr><td>41</td><td>佐賀県</td></tr>
                            <tr><td>42</td><td>長崎県</td></tr>
                            <tr><td>43</td><td>熊本県</td></tr>
                            <tr><td>44</td><td>大分県</td></tr>
                            <tr><td>45</td><td>宮崎県</td></tr>
                            <tr><td>46</td><td>鹿児島県</td></tr>
                            <tr><td>47</td><td>沖縄県</td></tr>
                        </tbody>
                    </table>
                    
                    <div class="text-center">
                        <a href="/user/add" class="btn btn-primary"><span class="glyphicon glyphicon-import"></span>&nbsp;新規ユーザ登録</a>
                    </div>
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
                    sInfo: " _TOTAL_ 件中 _START_ から _END_ まで表示",
                    sInfoEmpty: " 0 件中 0 から 0 まで表示",
                    sInfoFiltered: "（全 _MAX_ 件より抽出）",
                    sInfoPostFix: "",
                    sSearch: "検索:",
                    sUrl: "",
                    oPaginate: {
                        sFirst: "先頭",
                        sPrevious: "前",
                        sNext: "次",
                        sLast: "最終"
                    }
                } 
            }); 
            
            $("#foo").DataTable();
            $("#user-index-table").DataTable();
        });
    </script>
@endsection