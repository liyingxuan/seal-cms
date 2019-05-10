<?php

namespace App\Api\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Jrean\UserVerification\Traits\VerifiesUsers;
use Jrean\UserVerification\Facades\UserVerification;

class UserController extends Controller
{
    use VerifiesUsers;

    public $successStatus = 200;

    /**
     * Login
     *
     * @bodyParam email string required 邮箱
     * @bodyParam password string required 密码
     *
     * @response {
     *     "token": "2019xxx"
     * }
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            $ret['token'] = $user->createToken('SealSC')->accessToken;
            return response()->json($ret, $this->successStatus);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }

    /**
     * Register
     *
     * @bodyParam name string required 用户名
     * @bodyParam email string required 邮箱
     * @bodyParam password string required 密码
     * @bodyParam c_password string required 确认密码
     *
     * @response {
     *     "token": "2019xxx",
     *     "email": "sealsc@sealsc.com"
     * }
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $params = $request->all();
        $params['password'] = bcrypt($params['password']);
        $user = User::create($params);

        $this->sendVerificationEmail($user); // 给用户发送验证邮件

        $ret['token'] = $user->createToken('SealSC')->accessToken;
        $ret['email'] = $user->email;
        return response()->json($ret, $this->successStatus);
    }

    /**
     * 发送验证邮件
     *
     * @param $user
     */
    public function sendVerificationEmail($user)
    {
        // 生成用户的验证 token，并将用户的 verified 设置为 0
        UserVerification::generate($user);

        // 给用户发认证邮件
        $params = [
            'link' => url('api/verification', $user->verification_token) . '?email=' . urlencode($user->email),
            'linkName' => 'Click Here'
        ];
        $to = $user->email;
        $subject = 'Welcome to SealSC! Confirm Your Email';
        Mail::send(
            'emails.user-verification',
            ['content' => $params],
            function ($message) use ($to, $subject) {
                $message->to($to)->subject($subject);
            }
        );
    }

    /**
     * 验证邮箱
     *
     * @response {}
     *
     * @param $token
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function verification($token)
    {
        $user = User::where('verification_token', $token)->first();
        $user->verified = true;
        $user->verification_token = null;
        $user->save();

        session()->flash('success', 'Success!');
        return redirect('https://sealsc.com');
    }

    /**
     * Details
     * Postman测试需要参数：[{"key":"Authorization","value":"Bearer 【你的TOKEN】","description":""}]
     *
     * @response {
     *     "id": 2019,
     *     "name": "Seal SC",
     *     "email": "sealsc@sealsc.com"
     * }
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function details()
    {
        $user = Auth::user();
        $ret = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
        ];
        return response()->json(['data' => $ret], $this->successStatus);
    }
}
