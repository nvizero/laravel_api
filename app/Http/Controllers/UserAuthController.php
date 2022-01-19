<?PHP
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Validator; //驗證器
use Hash; //雜湊
use Mail; //寄信
use Socialite;
use App\Shop\Entity\User; //使用者 Eloquent ORM Model

class UserAuthController extends Controller
{
    //Facebook登入
    public function facebookSignInProcess()
    {
        $redirect_url = env('FB_REDIRECT');

        return Socialite::driver('facebook')
            ->scopes(['user_friends'])
            ->redirectUrl($redirect_url)
            ->redirect();
    }

    //Facebook登入重新導向授權資料處理
    public function facebookSignInCallbackProcess()
    {
    	if(request()->error=="access_denied")
        {
            throw new Exception('授權失敗，存取錯誤');
        }
        //依照網域產出重新導向連結 (來驗證是否為發出時同一callback)
        $redirect_url = env('FB_REDIRECT');
        //取得第三方使用者資料
        /*
        $user = Socialite::driver('facebook')->user();
        var_dump($user);
        //*/
        $FacebookUser = Socialite::driver('facebook')
            ->fields([
                'name',
                'email',
            ])
            ->redirectUrl($redirect_url)->user();
       
        $facebook_email = $FacebookUser->email;

        if(is_null($facebook_email))
        {
            throw new Exception('未授權取得使用者 Email');
        }
        //取得 Facebook 資料
        $facebook_id = $FacebookUser->id;
        $facebook_name = $FacebookUser->name;

        echo "facebook_id=".$facebook_id.", facebook_name=".$facebook_name;
        
        //...以上省略...
        $facebook_email = $FacebookUser->email;
        if(is_null($facebook_email))
        {
            throw new Exception('未授權取得使用者 Email');
        }
        //取得 Facebook 資料
        $facebook_id = $FacebookUser->id;
        $facebook_name = $FacebookUser->name;

        //取得使用者資料是否有此Facebook_id資料
        $User = User::where('facebook_id', $facebook_id)->first();

        if(is_null($User))
        {
            //沒有綁定Facebook Id的帳號，透過Email尋找是否有此帳號
            $User = User::where('email', $facebook_email)->first();
            if(!is_null($User))
            {
                //有此帳號，綁定Facebook Id
                $User->facebook_id = $facebook_id;
                $User->save();
            }
        }

        if(is_null($User))
        {
            //尚未註冊
            $input = [
                'email' => $facebook_email, //E-mail
                'nickname' => $facebook_name, //暱稱
                'password' => uniqid(), //隨機產生密碼
                'facebook_id' => $facebook_id, //Facebook ID
                'type' => 'G', //一般使用者
            ];
            //密碼加密
            $input['password'] = Hash::make($input['password']);
            //新增會員資料
            $User = User::create($input);

            //寄送註冊通知信
            $mail_binding = [
                'nickname' => $input['nickname']
            ];

            Mail::send('email.signUpEmailNotification', $mail_binding,
                function($mail) use ($input){
                    $mail->to($input['email']);
                    $mail->from('henrychang0202@gmail.com');
                    $mail->subject('恭喜註冊 Shop Laravel 成功');
                });
        }

        echo "登入成功!";
    }
}
?>