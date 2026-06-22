@extends('layouts.app')
@section('title', 'Profile')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <h1 class="text-2xl font-bold text-gray-900">Profile</h1>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        @include('profile.partials.update-profile-information-form')
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        @include('profile.partials.update-password-form')
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        @include('profile.partials.delete-user-form')
    </div>
</div>
@endsection
