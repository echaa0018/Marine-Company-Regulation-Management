<?php
namespace App\Services;

use LdapRecord\Container;
use LdapRecord\Laravel\Auth\ListensForLdapBindFailure;
use LdapRecord\Laravel\LdapRecord;
use LdapRecord\Models\ActiveDirectory\User;

class LdapService
{
    use ListensForLdapBindFailure;

    public function authenticate($username, $password): bool
    {
        $username = trim($username);
        // $username = str_replace('@neutradc.com', '', $username);

        try {
            // Bind langsung ke server menggunakan kredensial user
            $connection = Container::getConnection('default');
            $connection->auth()->bind($username, $password);

            return true;
        } catch (\LdapRecord\Auth\BindException $e) {
            report($e->getDetailedError()->getErrorMessage());
            // dd($e);
            return false;
        }
    }

    public function getUserInfo(string $username) 
    {
        $username = str_replace('@neutradc.com', '', $username);
        return User::where('samaccountname', '=', $username)->first();
    }
}