<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\IssueTagController;
use App\Http\Controllers\IssueUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('projects.index'));

Route::middleware(['auth'])->group(function () {
    // Projects
    Route::resource('projects', ProjectController::class);

    // Issues
    Route::resource('issues', IssueController::class);

    // Tags
    Route::get('tags', [TagController::class, 'index'])->name('tags.index');
    Route::post('tags', [TagController::class, 'store'])->name('tags.store');

    // AJAX: toggle tag on issue
    Route::post('issues/{issue}/tags/toggle', [IssueTagController::class, 'toggle'])
        ->name('issues.tags.toggle');

    // AJAX: comments
    Route::get('issues/{issue}/comments', [CommentController::class, 'index'])
        ->name('issues.comments.index');
    Route::post('issues/{issue}/comments', [CommentController::class, 'store'])
        ->name('issues.comments.store');

    // AJAX: toggle assignee on issue (bonus)
    Route::post('issues/{issue}/users/toggle', [IssueUserController::class, 'toggle'])
        ->name('issues.users.toggle');

    // Profile (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
