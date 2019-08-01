<?php

    namespace Artjoker\Redirect\Http\Controllers;

    use App\Http\Controllers\Controller;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Http\Request;
    use Artjoker\Redirect\Http\Requests\SaveRedirectRequest;
    use Artjoker\Redirect\Models\Redirect;

    /**
     * Class RedirectController
     *
     * @package Artjoker\Redirect\Http\Controllers
     */
    class RedirectController extends Controller
    {
        /**
         * Display a listing of the resource.
         *
         * @param  \Illuminate\Http\Request  $request
         *
         * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
         */
        public function index(Request $request)
        {
            $redirects = Redirect::when(
                $request->filled('search'),
                function(Builder $builder) use ($request) {
                    return $builder->where('url_from', 'LIKE', '%' . $request->input('search') . '%')
                        ->orWhere('url_to', 'LIKE', '%' . $request->input('search') . '%');
                })
                ->orderByPosition()
                ->paginate(config('redirect.per_page'))
                ->appends($request->all());

            return view('redirect::index', compact('redirects'));
        }

        /**
         * Show the form for creating a new resource.
         *
         * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
         */
        public function create()
        {
            return view('redirect::create');
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param  \Artjoker\Redirect\Http\Requests\SaveRedirectRequest  $request
         *
         * @return mixed
         */
        public function store(SaveRedirectRequest $request)
        {
            Redirect::create($request->all());

            return redirect()
                ->route(config('redirect.route_as') . 'redirect.index')
                ->withSuccess(__('redirect::redirect.stored'));
        }

        /**
         * Show the form for editing the specified resource.
         *
         * @param  \Artjoker\Redirect\Models\Redirect  $redirect
         *
         * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
         */
        public function edit(Redirect $redirect)
        {
            return view('redirect::edit', compact('redirect'));
        }

        /**
         * Update the specified resource in storage.
         *
         * @param  \Artjoker\Redirect\Http\Requests\SaveRedirectRequest  $request
         * @param  \Artjoker\Redirect\Models\Redirect                    $redirect
         *
         * @return mixed
         */
        public function update(SaveRedirectRequest $request, Redirect $redirect)
        {
            $redirect->update($request->all());

            return redirect()
                ->route(config('redirect.route_as') . 'redirect.index')
                ->withSuccess(__('redirect::redirect.updated'));
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param  \Artjoker\Redirect\Models\Redirect  $redirect
         *
         * @return mixed
         * @throws \Exception
         */
        public function destroy(Redirect $redirect)
        {
            $redirect->delete();

            return redirect()->route(config('redirect.route_as') . 'redirect.index')
                ->withDanger(__('redirect::redirect.destroyed'));
        }
    }
