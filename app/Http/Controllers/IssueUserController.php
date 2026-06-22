<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IssueUserController extends Controller
{
    public function toggle(Request $request, Issue $issue): JsonResponse
    {
        $request->validate(['user_id' => ['required', 'exists:users,id']]);

        $user = User::findOrFail($request->user_id);
        $issue->assignees()->toggle($user->id);

        $attached = $issue->assignees()->where('users.id', $user->id)->exists();

        return response()->json([
            'attached'  => $attached,
            'user'      => ['id' => $user->id, 'name' => $user->name],
            'assignees' => $issue->assignees()->get(['users.id', 'users.name']),
        ]);
    }
}
