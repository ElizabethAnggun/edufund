@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Admin Dashboard')

@section('content')
    <div class="bg-surface p-8 rounded-xl border border-neutral-200">
        <h3 class="text-lg font-semibold text-neutral-900 mb-2">Welcome, {{ auth()->user()->name }}</h3>
        <p class="text-neutral-500">Role: {{ ucfirst(auth()->user()->getRoleNames()->first()) }}</p>
    </div>
@endsection
