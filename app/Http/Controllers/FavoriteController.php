<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Micropost;

class FavoriteController extends Controller
{
    public function store(Request $request, $id)
    {
        \Auth::user()->favorites($id);
        return redirect()->back();
    }

 public function favoritings($id)
    {
        $user = User::find($id);
        $favoritings = $user->favoritings()->paginate(10);

        $data = [
            'user' => $user,
            'users' => $favoritings,
        ];

        $data += $this->counts($user);

        return view('users.favoritings', $data);
    }

    

    public function destroy($id)
    {
        \Auth::user()->unfavorite($id);
        return redirect()->back();
    }

}


