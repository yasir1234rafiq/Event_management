<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\Ticket;



class AdminController extends Controller
{

    public function index()
    {
        return view('auth.login');
    }


    public function adminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {

            return redirect()->intended('dashboard')
                        ->withSuccess('Signed in');
        }
        return redirect("login")->withSuccess('Login details are not valid');
    }

     public function registration()
     {
         return view('auth.registration');
     }


     public function customRegistration(Request $request)
     {
         $request->validate([
             'name' => 'required',
             'email' => 'required|email|unique:users',
             'password' => 'required|min:6',

         ]);

         $data = $request->all();

         $check = $this->create($data);


         return redirect("home")->withSuccess('You have signed-in');
     }

     public function create(array $data)
     {
       return User::create([
         'name' => $data['name'],
         'email' => $data['email'],
         'password' => Hash::make($data['password'])
       ]);
     }

    public function dashboard()
    {
        if(Auth::check()){
            return redirect("events");
        }

        return redirect("login")->withSuccess('You are not allowed to access');
    }

    public function signOut() {
        Session::flush();
        Auth::logout();

        return Redirect('login');
    }

    public function admindashboard()
{
    // Get total events
    $totalEvents = Event::count();

    // Get total bookings
    $totalBookings = Ticket::count();

    // Get upcoming events
    $upcomingEvents = Event::where('date', '>', now())->count();

    // Get total attendees
    $totalAttendees = Ticket::distinct('user_id')->count('user_id');

    return view('admin_dasboard', compact('totalEvents', 'totalBookings', 'upcomingEvents', 'totalAttendees'));
}

}
