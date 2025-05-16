<?php


namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Http\Requests\AccountRecovery\ResetPasswordFormRequest;
use App\Http\Requests\AccountRecovery\UsernameLookupFormRequest;
use App\Models\Employee;
use App\Models\JoEmployees;
use App\Models\SuEmailVerification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AccountRecoveryController extends Controller
{
    public function username_lookup(UsernameLookupFormRequest $request){
        //return $request;

        $employee = Employee::query()->where('lastname','like','%'.$request->lastname.'%')
            ->where('firstname','like','%'.$request->firstname.'%')
            ->where('date_of_birth','=',$request->birthday)->first();

        if(empty($employee)){
            $employee = JoEmployees::query()->where('lastname','like','%'.$request->lastname.'%')
                ->where('firstname','like','%'.$request->firstname.'%')
                ->where('birthday','=',$request->birthday)->first();
        }

        if(empty($employee)){
            abort(503,'Cannot find employee based on your search values.');
        }

        $user = User::query()->where('employee_no','=',$employee->employee_no)->first();
        if(empty($employee)){
            abort(503,'Cannot find user based on your search values.');
        }

        return [
            'fullname' => $employee->firstname.' '.$employee->lastname,
            'username' => $user->username,
        ];
    }

    public function reset_password(ResetPasswordFormRequest $request){

        $user = User::with('employee')->where('username','=',$request->username)->first();
        if($user->employee->email == null){
            abort(503,'Account recovery is not possible on this account. Contact MIS Office for account recovery. Possible reason: email address not set.');
        }
        $string_to_arr = explode("@",$user->employee->email);
        $length_of_string = strlen($string_to_arr[0])-4;
        $first_three = substr($user->employee->email, 0, 3);
        for($x = 0; $x< $length_of_string;$x++){
            $first_three = $first_three.'*';
        }

        return [
            'email' => $first_three.substr($string_to_arr[0], -1, 1).'@'.$string_to_arr[1],
            'slug' => $user->slug,
        ];

    }

    public function verify_email(Request $request){
        $user = User::with('employee')->where('slug','=',$request->slug)->first();
        if(empty($user)){
            abort(404,'User not found');
        }
        if($user->employee->email == null){
            abort(404,'Email cannot be empty');
        }

        if($request->email != $user->employee->email){
            abort(404,'Incorrect email');
        }
        //clean previous
        $clean_ev = SuEmailVerification::query()->where('user_slug','=',$user->slug)->where('type','=',null)->delete();
        $email = $request->email;
        $slug = Str::random(99);
        $user_slug = $user->slug;
        $ev = new SuEmailVerification;
        $ev->verification_slug = $slug;
        $ev->user_slug = $user_slug;
        if($ev->save()){
            try{
                \Illuminate\Support\Facades\Mail::mailer('system')->send(new \App\Mail\ResetPasswordMailer($email,$user_slug,$slug));
            }catch (\Exception $e){
//                $r->delete();
                abort(503,'Error sending email verification: '.$e->getMessage());
            }

//            // Backup your default mailer
//            $backup = Mail::getSwiftMailer();
//
//            // Setup your gmail mailer
//            $transport = new \Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl');
//            $transport->setUsername(env('SWIFTMAILER_EMAIL',null));
//            $transport->setPassword(env('SWIFTMAILER_PASSWORD',null));
//
//            // Any other mailer configuration stuff needed...
//            $gmail = new \Swift_Mailer($transport);
//
//            // Set the mailer as gmail
//            Mail::setSwiftMailer($gmail);
//            $data = [
//                'verification_slug' => $slug,
//                'user_slug' => $user_slug,
//                'name' => $user->employee->firstname,
//            ];
//
//            // Send your message
//            try{
//                Mail::send('mailables.verify_email',$data, function($message) use($email) {
//                    $message->to($email, '')
//                        ->subject('SWEP Email Verification - '.strtoupper(Str::random(5)));
//                    $message->from('sys.srawebportal@gmail.com','SWEP System');
//                });
//            }catch (\Exception $e){
//                $ev->delete();
//                abort(503,'Error sending email verification');
//            }

            // Restore your original mailer
//            Mail::setSwiftMailer($backup);
            return 1;
        }

        return $request;
    }

    public function  reset_password_via_email(Request $request){

        if(empty($request)){
            return 'INVALID TOKEN';
        }
        $check_ev = SuEmailVerification::query()->where('verification_slug','=',$request->ev)
            ->where('user_slug','=',$request->u)
            ->where('type','=',0)
            ->orWhere('type','=',null)->first();
        if(!empty($check_ev)){
            $user = User::with('employee')->where('slug','=',$request->u)->first();

            if(empty($user)){
                abort(404);
            }
            if(empty($user->employee())){
                abort(404);
            }

            $newpass = Hash::make(Carbon::parse($user->employee->date_of_birth)->format('mdy'));

            $user->password = $newpass;
            if($user->update()){
                $check_ev->type = 1;
                $check_ev->update();
                $request->session()->flash('PASSWORD_RESET_SUCCESS','You have successfully reset your password. You may now login to your account using your default password: MMDDYY format of your birthday');
                return redirect('/');
            }else{
                abort(505);
            }
            return 'Verified';
        }
        $request->session()->flash('PASSWORD_RESET_FAILED','Invalid reset token provided');
        return redirect('/');
        abort(404);
        return $request;
    }
}