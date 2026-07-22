<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSystemUserRequest;
use App\Http\Requests\UpdateSystemUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class SystemUserController extends Controller
{
    private const ROLE_LABELS = [
        1 => 'Admin',
        2 => 'User B',
        3 => 'User C',
    ];

    public function index(): View
    {
        return view('Pages.system_user.system_user');
    }

    public function data(): JsonResponse
    {
        $users = User::query()
            ->get()
            ->map(fn (User $user): array => [
                'id'         => $user->id,
                'name'       => $user->name,
                'email'      => $user->email,
                'role'       => self::ROLE_LABELS[$user->role] ?? 'User D',
                'active'     => $user->active == 1 ? 'active' : 'Disactive',
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ])
            ->all();

        return response()->json(['data' => $users]);
    }

    /**
     * The password is never sent to the browser — the edit form leaves the
     * box empty and only submits one when it is actually being changed.
     */
    public function show(User $system_user): JsonResponse
    {
        return response()->json([
            'data' => [[
                'id'    => $system_user->id,
                'name'  => $system_user->name,
                'email' => $system_user->email,
                'role'  => $system_user->role,
            ]],
        ]);
    }

    public function store(StoreSystemUserRequest $request): RedirectResponse
    {
        User::create([
            'name'     => $request->validated('name'),
            'email'    => $request->validated('email'),
            'password' => Hash::make($request->validated('password')),
            'active'   => 0,
            'role'     => $request->validated('role'),
        ]);

        return redirect()->back()->with('status', 'User created.');
    }

    public function update(UpdateSystemUserRequest $request, User $system_user): RedirectResponse
    {
        $attributes = [
            'name'  => $request->validated('name'),
            'email' => $request->validated('email'),
            'role'  => $request->validated('role'),
        ];

        // The old edit() compared the submitted value against a session copy of
        // the password *hash*, which could never match — so every edit rehashed
        // whatever was in the box, and the box was populated from a hidden
        // field that serialised as undefined. Editing a user therefore set
        // their password to the literal string "undefined".
        if (filled($request->validated('password'))) {
            $attributes['password'] = Hash::make($request->validated('password'));
        }

        $system_user->update($attributes);

        return redirect()->back()->with('status', 'User updated.');
    }

    public function destroy(User $system_user): Response
    {
        $system_user->delete();

        return response()->noContent();
    }

    public function activate(User $system_user): RedirectResponse
    {
        $system_user->update(['active' => 1]);

        return redirect()->back()->with('status', 'User activated.');
    }

    public function deactivate(User $system_user): RedirectResponse
    {
        $system_user->update(['active' => 0]);

        return redirect()->back()->with('status', 'User deactivated.');
    }
}
