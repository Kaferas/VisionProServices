@extends('template')

@section('title')
    Editer Utilisateur
@endsection

@section('content')
    <form action="{{ route('users.update', $user) }}" method="POST" class="validate-form">
        @method('PUT')
        @csrf
        @include('users._form')
    </form>
@endsection
