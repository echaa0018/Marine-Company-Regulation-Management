<?php

namespace App\Http\Middleware;

use App\Models\Log_login;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DomainCheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
    
        $allowedHosts = explode(',', config('app.allowed_domain') );
        // $requestHost = parse_url($request->headers->get('origin'),  PHP_URL_HOST);
        $requestHost = $request->host();
  
        if (!app()->runningUnitTests()) {
            if (!\in_array($requestHost, $allowedHosts, false)) {
                $requestInfo = [
                    'host' => $requestHost,
                    'ip' => $request->getClientIp(),
                    'url' => $request->getRequestUri(),
                    'agent' => $request->header('User-Agent'),
                ];
                // Log_login::logUserAttempt('Unauthorized Access', Carbon::now(), $requestInfo['ip'], 'Unauthorized Access', 'Unauthorized Access', $requestInfo);
           
                abort(401);
            }
        }

        return $next($request);
    }
}
