<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Middlewares\RoleMiddleware;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(RoleMiddleware::class.':super_admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('id', '!=', auth()->user()->id)
                    ->with(['roles'])
                    ->get();
                    
        return Inertia::render('User/Index', [
            'users' => $users,
            'isSuperAdmin' => auth()->user()->hasRole('super_admin'),
            'status' => session('status'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Convert a normal user to super admin
     */
    public function makeSuperAdmin(User $user)
    {
        $user->assignRole('super_admin');

        return response('', 200); 
    }

    /**
     * Convert a super admin to normal user
     */
    public function removeSuperAdmin(User $user)
    {
        $user->removeRole('super_admin');

        return response('', 200); 
    }

    /**
     * List all users
     */
    public function listAllUsers()
    {
        $users = User::all(['id', 'name']);
        return response($users, 200); 
    }
}
