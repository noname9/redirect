<?php

    namespace Artjoker\Redirect\Http\Middleware;

    use Closure;
    use Artjoker\Redirect\Models\Redirect;

    /**
     * Class RedirectMiddleware
     *
     * @package Artjoker\Redirect\Http\Middleware
     */
    class RedirectMiddleware
    {
        /**
         * Handle an incoming request.
         *
         * @param            $request
         * @param  \Closure  $next
         *
         * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|mixed
         */
        public function handle($request, Closure $next)
        {
            $redirect = Redirect::whereUrlFrom($request->url())->first();

            if (is_null($redirect)) {
                return $next($request);
            }

            return redirect($redirect->url_to, $redirect->status_code);
        }
    }
