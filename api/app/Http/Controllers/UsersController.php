<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $field = $request->get('field', 'id');
        $order = $request->get('order', 'ASC');
        return UserResource::collection(User::orderBy($field, $order)->paginate(10));
    }
}
