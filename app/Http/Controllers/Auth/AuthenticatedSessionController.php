<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;

use App\Models\TypActivity;
use Spatie\Activitylog\Contracts\Activity;
use Illuminate\Support\Facades\DB;
use App\Models\LogActivity;
use Auth;


class AuthenticatedSessionController extends Controller
{

    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
       
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();
        

        activity(TypActivity::CONNEXION)->log('Connexion à la plateforme');
        $lastID = LogActivity::latest('id')->first();
        $query = LogActivity::where("id", $lastID['id'])->update(["subject_type" => $user->entites_id]);
               

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
