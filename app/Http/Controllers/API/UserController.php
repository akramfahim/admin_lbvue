<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
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
        //$this->authorize('isAdmin');

        if (\Gate::allows('isAdmin') || \Gate::allows('isAuthor')) {
            return User::latest()->paginate(10);
            //return User::orderBy('id','DESC')->get();
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

        $this->validate($request,[
            'name'  => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users',
            'password' => 'required|string|min:5'
        ]);

        return User::create([
            
            'name' => $request['name'],
            'email' => $request['email'],
            'bio' => $request['bio'],
            'type' => $request['type'],
            'password' => Hash::make($request['password']),
            'photo' => $request['photo']
            
        ]);
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

    //Show Login User Profile
    public function profile()
    {
        return auth('api')->user();
    }
    
    //Update User Information by User
    public function updateProfile(Request $request)
    {
        $user = auth('api')->user();

        $this->validate($request,[
            'name'  => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,
            'password' => 'sometimes|required|min:5'
        ]);

        $currentPhoto = $user->photo;

        if($request->photo != $currentPhoto){
            //Create Name from User Uploaded File
            $name = time().'.' .explode('/', explode(':', substr($request->photo,0, strpos($request->photo, ';')))[1])[1];

            \Image::make($request->photo)->save(public_path('img/profile/').$name);

            $request->merge(['photo' => $name]);


            $userPhoto = public_path('img/profile/').$currentPhoto;
            if($userPhoto){
                @unlink($userPhoto);
            }

        }

        //Make New Password Hashed
        if(!empty($request->password)){
            $request->merge(['password' => Hash::make($request['password'])]);
        }


        $user->update($request->all());
        return ['message' => 'Successfully Updated'];

        // return $request->photo;
    }



    //Update User data by Admin
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $this->validate($request,[
            'name'  => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,
            'password' => 'sometimes|min:5'
        ]);

        $user->update($request->all());

        return ['message' => 'User Information Updated'];
    }

    //Search Data
    public function search()
    {
        if($search = \Request::get('q')){
            $users = User::where(function($query) use ($search){
                $query->where('name' , 'LIKE' , '%'.$search.'%')->orWhere('email' , 'LIKE' , '%'.$search.'%')
                        ->orWhere('email' , 'LIKE', '%'.$search.'%')
                        ->orWhere('type' , 'LIKE' , '%'.$search .'%');
                    })->paginate(5);
        }else{
            $users = User::latest()->paginate(10);
        }

        return $users;

        // return ['message' => 'Search Item Get'];
    }

    //Delete Data By Admin
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return ['message' => 'User Deleted Successfully'];
    }
}
