<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'regex:/^[_A-z0-9]*((-|\S)*^[A-Za-z])*$/','max:16', 'unique:'.User::class],
            'email' => ['required', 'string', 'email', 'regex:/^.+@.+$/i','max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
			'g-recaptcha-response' => ['required'],
        ]);
		$response = Http::withHeaders([
            'Content-Type' => 'application/x-www-form-urlencoded; charset=utf-8',
        ])->get('https://www.google.com/recaptcha/api/siteverify', [
            'secret'   => "6LdbAA0pAAAAAJ8IZXRrT8w-Z5kStzjI90kDwCcF",
            'response' => $request->input('g-recaptcha-response'),
            'remoteip' => $_SERVER['REMOTE_ADDR']
        ]);
    
        $data = $response->json();
        if (!$data['success']) {
            return redirect()->back()->withErrors(['g-recaptcha-response' => 'Verificación reCAPTCHA fallida'])->withInput();
        }

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $user->assignRole('lector');

        $profile = new Profile();
        $profile->user_id = $user['id'];
        $profile->avatar = 'avatares/avatar-'.rand(1, 10).'.jpg';
        $profile->save();

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME)->with('success', 'Se ha enviado un enlace de verificación a su correo, recuerda revisar SPAM.');
    }
}
