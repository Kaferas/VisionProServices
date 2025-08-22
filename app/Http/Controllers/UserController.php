<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!empty($request->search)) {
            $users = User::where('user_status', 0)
                ->where("name", 'LIKE', '%' . $request->search . '%')
                ->orWhere("email", 'LIKE', '%' . $request->search . '%');
        } else {
            $users = User::where('user_status', 0)->get();
        }

        return view('users.index', ['all_users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('users.add', [
            'roles' => $roles
        ]);
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'name' => 'required|min:4',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
            'pin_code' => 'unique:users,pin_code'
        ]);

        if (!empty($request->profilePath)) {
            $path = public_path('profiles/');
            !is_dir($path) &&
                mkdir($path, 0777, true);
            $imageName = time() . '.' . $request->profilePath->extension();
            $request->profilePath->move($path, $imageName);
        }
        $adresse = $request->adresse;
        if (Auth::user()->isAdmin()) {
            $user = DB::table('users')->insertGetId(
                [
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' =>  Hash::make($request->password),
                    'aboutMe' => $request->aboutMe,
                    'phone' => $request->phone,
                    'pin_code' => $request->pin_code,
                    'profilePath' =>  $imageName ?? '',
                    'adresse' => $adresse,
                ]
            );
            RoleUser::create([
                'user_id' => $user,
                'role_id' => $request->user_right,
            ]);
            Session::flash('success', "Utilisateur Cree avec Success");
        }
        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', ['roles' => $roles, 'user' => $user]);
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $valid = $request->validate(
            empty($request->password) ? [
                'email' => 'required|email|unique:users,email,' . $user->id,
                'name' => 'required|min:4',
                'pin_code' => 'unique:users,pin_code,' . $user->id
            ] :
                [
                    'email' => 'required|email|unique:users,email,' . $user->id,
                    'name' => 'required|min:4',
                    'password' => 'required|min:8|confirmed',
                    'password_confirmation' => 'required',
                    'pin_code' => 'unique:users,pin_code,' . $user->id
                ]
        );

        if (!empty($request->profilePath)) {
            $path = public_path('profiles/');
            !is_dir($path) &&
                mkdir($path, 0777, true);
            $imageName = time() . '.' . $request->profilePath->extension();
            $request->profilePath->move($path, $imageName);
        }
        $adresse = $request->adresse;
        $updatable = empty($request->password) ?
            [
                'name' => $request->name,
                'email' => $request->email,
                'aboutMe' => $request->aboutMe,
                'phone' => $request->phone,
                'pin_code' => $request->pin_code,
                'profilePath' =>  $imageName ?? '',
                'adresse' => $adresse,
            ] :
            [
                'name' => $request->name,
                'email' => $request->email,
                'password' =>  Hash::make($request->password),
                'aboutMe' => $request->aboutMe,
                'phone' => $request->phone,
                'pin_code' => $request->pin_code,
                'profilePath' =>  $imageName ?? '',
                'adresse' => $adresse,
            ];
        $user->update($updatable);
        RoleUser::create([
            'user_id' => $user->id,
            'role_id' => $request->user_right,
        ]);
        Session::flash('success', "Utilisateur Cree avec Success");
        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
