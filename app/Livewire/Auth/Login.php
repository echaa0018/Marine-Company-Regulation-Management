<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Services\LdapService;
use Livewire\Component;
// use LdapRecord\Container;
use LdapRecord\Laravel\Auth\ListensForLdapBindFailure;
use LdapRecord\Laravel\LdapRecord;
// use LdapRecord\Models\ActiveDirectory\User as ActiveDirectoryUser;

class Login extends Component
{
    use ListensForLdapBindFailure;
    public string $email = '';
    public string $password = '';

    public static function middleware()
    {
        return ['guest'];
    }

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $email = trim(strtolower($this->email));
        $password = trim($this->password);

        if (env('LOGIN_TYPE', 'local') == 'local') {
            if (strpos($email, '@neutradc.com') > 0) {
                if (Auth::attempt(['email' => $email, 'password' => $password])) {
                    session()->regenerate();
                    redirect()->intended(route('home'));
                    return;
                }
            }
            // if ($email == $password) {
            //     session()->regenerate();
            //     return redirect()->intended(route('home'));
            // }
        }

        if ($this->ldapLogin($email, $password)) {
            $username = str_replace('@neutradc.com', '', $email);
            $user = User::where('ad_name', '=', $username)->first();
            Auth::login($user);
            session()->regenerate();
            return redirect()->intended(route('home'));
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    public function ldapLogin($email, $password)
    {
        $ldap = new LdapService();

        if ($ldap->authenticate($email, $password)) {
            $ldapUser = $ldap->getUserInfo($email);

            // dd($ldapUser);
            if (!$ldapUser) {
                $this->addError('username', 'User tidak ditemukan di LDAP.');
                return;
            }

            $localUser = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $ldapUser->getFirstAttribute('name'),
                    'phone' => '08123456789',
                    'nik' => $ldapUser->getFirstAttribute('employeeid'),
                    'ad_name' => $ldapUser->getFirstAttribute('samaccountname'),
                    'email' => $ldapUser->getFirstAttribute('mail'),
                    'title' => $ldapUser->getFirstAttribute('title'),
                    // 'department' => $ldapUser->getFirstAttribute('department'),
                    // 'unit_name' => $ldapUser->getFirstAttribute('department'),
                    'last_login_at' => now(),
                ]
            );

            // $realUser = User::find('email',$ldapUser->getFirstAttribute('mail'));

            // Auth::login($realUser);

            return true;

            // return redirect()->intended('/');
        }

        $this->addError('username', 'Autentikasi LDAP gagal.');
    }

    public function getUserInfo(string $username): ?User
    {
        return ActiveDirectoryUser::where('samaccountname', '=', $username)->first();
    }

    public function render()
    {
        return view("livewire.auth.login")
            ->layout("components.layouts.auth", [
                "title" => "Login"
            ]);
    }
}
