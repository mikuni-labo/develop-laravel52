<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Config\Repository as Config;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */
    
    use AuthenticatesAndRegistersUsers { getCredentials as _getCredentials; }
    use ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:user', ['except' => ['getLogin', 'postLogin', 'getRegister', 'postRegister', 'getConfirm', 'getResend', 'postResend']]);
        
        /**
         * postLogin() メソッドにだけ、'confirm'ミドルウェアを実行する
         * (登録前の認証が済んでいるかどうかのチェック)
         */
        $this->middleware('confirm', ['only' => 'postLogin']);
    }

    /**
     * Index
     */
    public function index()
    {
        //return redirect($this->redirectTo);
        return view('index');
    }
    
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'last_name'    => 'required|max:255',
            'first_name'    => 'required|max:255',
            'email'    => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

    }
    
    /**
     * 確認メールの送信
     *
     * @param Mailer $mailer
     * @param User $user
     */
    private function sendConfirmMail(Mailer $mailer, User $user)
    {
        $mailer->send(
                'emails.confirm',
                ['user' => $user, 'token' => $user->confirmation_token],
                function($message) use ($user) {
                    $message->to($user->email, $user->name)->subject('ユーザー登録確認');
                }
        );
    }
    
    /**
     * ユーザー登録アクション
     * バリデーションチェックを行い、ユーザーを作成する
     *
     * @param Request $request
     * @param Mailer $mailer
     * @param Config $config
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postRegister(Request $request, Mailer $mailer, Config $config)
    {
        /**
         * バリデーション
         */
        $this->doValidate($request, 'register');
        
        $user = new User;
        $data = $request->all();
        $app_key = $config->get('app.key');
        
        $user->last_name = $data['last_name'];
        $user->first_name = $data['first_name'];
        $user->email = $data['email'];
        $user->password = bcrypt($data['password']);
        
        $user->makeConfirmationToken($app_key);
        $user->confirmation_sent_at = Carbon::now();
        
        $user->save();
        
        $this->sendConfirmMail($mailer, $user);
        
        \Flash::success('ユーザー登録確認メールを送りました。');
    
        return redirect('auth/login');
    }
    
    /**
     * ユーザーを確認済にする
     *
     * @param $token
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getConfirm($token) {
        $user = User::where('confirmation_token', '=', $token)->first();
        if (! $user) {
            \Flash::error('無効なトークンです。');
            
            return redirect('auth/login');
        }
    
        $user->confirm();
        $user->save();
    
        \Flash::success('ユーザー登録が完了しました。ログインしてください。');
        
        return redirect('auth/login');
    }
    
    /**
     * 確認メール再送画面を表示する
     *
     * @return \Illuminate\View\View
     */
    public function getResend()
    {
        return view('auth.resend');
    }
    
    /**
     * 確認メールの再送信する
     *
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function postResend(Request $request, Mailer $mailer, Config $config)
    {
        /**
         * バリデーション
         */
        $this->doValidate($request, 'resend');
        
        $user = User::where('email', '=', $request->input('email'))->first();
        if(! $user) {
            return redirect()->back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => trans('passwords.user')]);
        }
        if($user->isConfirmed()) {
            \Flash::info('既に、ユーザー登録が完了しています。ログインしてください。');
            
            return redirect('auth/login');
        }
        
        $this->sendConfirmMail($mailer, $user);
    
        \Flash::success('ユーザー登録確認メールを送りました。');
        
        return redirect()->guest('auth/login');
    }
    
    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function getCredentials(Request $request)
    {
        $credentials = $this->_getCredentials($request);
    
        return array_merge($credentials, ['status' => 1]);
    }
    
    /**
     * 認証
     */
    public function postLogin(Request $request)
    {
        /**
         * バリデーション
         */
        $this->doValidate($request, 'login');
        
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        $throttles = $this->isUsingThrottlesLoginsTrait();
        
        if ($throttles && $lockedOut = $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            
            return $this->sendLockoutResponse($request);
        }
        
        $credentials = $this->getCredentials($request);
        
        if (\Auth::guard('user')->attempt($credentials, $request->has('remember')))
        {
            $this->handleUserWasAuthenticated($request, $throttles);
            
            return redirect()->intended($this->redirectTo);
        }
        
        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        if ($throttles && ! $lockedOut) {
            $this->incrementLoginAttempts($request);
        }
        
        \Flash::error('ログイン認証情報が正しくありません。');
        
        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Logout
     */
    public function getLogout() {
        \Auth::guard('user')->logout();
        \Flash::info('ログアウトしました');
    
        return view('auth/login');
    }
    

    /**
     * バリデーションオプションをセットしてバリデーションを実行する
     *
     * @param  Request $request
     * @param  string $mode
     * @return void
     * @author Kuniyasu.Wada
     */
    private function doValidate($request, $mode = null, $id = null)
    {
        // モードによってルールを切り分ける
        switch ($mode)
        {
            case ('register'):
                $rules = [
                    'last_name'                 => 'required|max:255',
                    'first_name'                 => 'required|max:255',
                    'company'               => 'max:255',
                    'department'              => 'max:255',
                    'email'                 => 'required|email|unique:users,email|max:255',
                    'password'              => 'required|min:8|max:16|confirmed',
                    'password_confirmation' => 'required_with:password',
                ];
                break;
                
            case ('login'):
                $rules = [
                    'email'    => 'required|email|max:255',
                    'password' => 'required|min:8|max:16',
                ];
                break;
                
            case ('resend'):
                $rules = [
                    'email'    => 'required|email|max:255',
                ];
                break;
                
            default:
        }
        
        $messages = [
                
        ];
        
        $customAttributes = [
                'last_name'                 => '姓',
                'first_name'                 => '名',
                'company'               => '会社名',
                'department'              => '部署',
                'email'                 => 'メールアドレス',
                'password'              => 'パスワード',
                'password_confirmation' => 'パスワード(確認用)',
        ];
        
        $this->validate($request, $rules, $messages, $customAttributes);
    }
    
}
