<?= header('X-Frame-Options: SAMEORIGIN')// クリックジャッキング対策(同一生成元のみ許可) ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <!-- Meta Data -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @yield('meta')

    <title>{{ $Fixed['info']['SiteName'] }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>

    <!-- Styles -->
    <link type="text/css" href="/bootstrap/css/bootstrap-umi.min.css" rel="stylesheet">
    <link type="text/css" href="/css/style.css" rel="stylesheet" media="screen">
    <link type="text/css" href="/css/datepicker.css" rel="stylesheet" media="screen">
    
</head>
<body>

    <!-- Global Navigation -->
    @yield('gnavi')
    
    <!-- Header -->
    @yield('header')
    
    <!-- Contents -->
    @yield('content')
    
    <!-- Footer -->
    @yield('footer')
    
    <!-- Scripts -->
    <script type="text/javascript" src="/js/jquery-2.1.4.min.js"></script>
    <script type="text/javascript" src="/bootstrap/js/bootstrap-3.3.6.min.js"></script>
    <script type="text/javascript" src="/bootstrap/js/bootstrap-confirmation.min.js"></script>
    <script type="text/javascript" src="/js/common.js"></script>
    <script type="text/javascript" src="/ckeditor/ckeditor.js"></script>
    @yield('script')
    
</body>
</html>
