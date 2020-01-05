<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate as FacadesGate;
// import the Intervention Image Manager Class
use Intervention\Image\ImageManagerStatic as Image;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (FacadesGate::allows('isAdmin') || FacadesGate::allows('isAuthor')) {
            return User::latest()->paginate(10);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:5',
        ]);

        return User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'type' => $request['type'],
            'bio' => $request['bio'],
            'photo' => 'profile.png',
            'password' => Hash::make($request['password']),
        ]);
    }

    public function profile()
    {
        return auth('api')->user();
    }

    public function updateProfile(Request $request)
    {
        $user = auth('api')->user();

        $this->validate($request, [
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users,email,' . $user->id
        ]);

        $currentPhoto = $user->photo;

        if ($request->photo !=  $currentPhoto) {
            $name = time() . '.' . explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];

            Image::make($request->photo)->save(public_path('img/profile/') . $name);

            $request->merge(['photo' => $name]);

            $userCurrentPhoto = public_path("img/profile/" . $currentPhoto);
            if (file_exists($userCurrentPhoto)) {
                @unlink($userCurrentPhoto);
            }
        }

        if (!empty($request->password)) {
            $this->validate($request, [
                'password' => 'sometimes|required|min:6'
            ]);
            $request->merge(['password' => Hash::make($request['password'])]);
        }

        $user->update($request->all());
        return ["message" => "success"];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users,email,' . $user->id,
            'password' => 'sometimes|min:5',
        ]);

        $user->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize("isAdmin");

        $user = User::findOrFail($id);

        $user->delete();
    }

    public function search(Request $request)
    {
        if ($search = $request->get('q')) {
            $users = User::where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%$search%")
                    ->orWhere('email', 'LIKE', "%$search%");
            })->paginate(10);
        } else {
            $users = User::latest()->paginate(10);
        }
        return $users;
    }
}
