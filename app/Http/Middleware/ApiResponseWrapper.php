<?php

namespace PockDoc\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class ApiResponseWrapper
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if ($request->expectsJson() && !($response instanceof BinaryFileResponse) && $response->status() != 500) {
//        dd($next($request));
            $closure = null;
            $message = $response->exception ? $response->exception->getMessage() : '';
            $data = [
                'success' => $response->exception == null,
                'message' => $response->exception == null ? 'Ok' : get_class($response->exception) . ': ' . $message,
            ];
            if ($response->original && (
                    is_array($response->original)
                    || ($response->original instanceof Collection)
                    || ($response->original instanceof Model)
                    || is_string($response->original)
                    || is_bool($response->original)
                )
            ) {
                $data['body'] = $response->original;
                if($response->exception && (
                    ($response->exception instanceof TooManyRequestsHttpException) ||
                    ($response->exception instanceof AuthorizationException)
                    )) {
                    unset($data['body']);
                }
            }
            if (method_exists($response, 'setData')) {
                $response->setData($data);
            } else {
                $response->setContent($data);
            }
        }
        //\Log::info($response->getContent());


        return $response;
    }
}
