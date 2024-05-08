<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\StoreUser;
use App\Http\Requests\UpdateUser;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $searchQuery = [];
        if ($request->has('name')) {
            $searchQuery[] = ['name', 'like', '%' . $request->name . '%'];
        }
        if ($request->has('email')) {
            $searchQuery[] = ['email', 'like', '%' . $request->email . '%'];
        }

        $users = User::where($searchQuery)->get();
        if ($users->count()>0) {
            $status = '200';
        } else {
            $status = '404';
        }

        $respone = [
            'status' => $status,
            'data' => $users ?? 'no data',
        ];
        return $respone;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUser $request)
    {
        $user = User::create($request->validated());
        $respone = [
            'status' => '200',
            'data' => $user,
        ];
        return $respone;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        $status = $user == null ? '404' : '200';
        return [
            'status' => $status,
            'message' => 'User\'s id: ' . $id,
            'data' => $user ?? 'no data',
        ];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUser $request, string $id)
    {
        $user = User::find($id);
        $status = $user == null ? '404' : '200';
        $user->update($request->all());
        return [
            'status' => $status,
            'message' => 'success',
            'data' => $user ?? 'no data',
            'method' => $request->method(),
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $id;
    }
}