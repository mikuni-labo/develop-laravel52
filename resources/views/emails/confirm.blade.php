<p>
    ようこそ、{{ $user['last_name'] }} さん
</p>
 
<p>
    以下のリンクをクリックしてユーザーを有効化してください。
</p>
 
<p>
    <a href="{{ url('auth/resend', [$token]) }}">ユーザーを有効化する</a>
</p>
