@extends('template')

@section('title')
    Nouveau Utilisateur
@endsection

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">
        Ajouter Utilisateur
    </h2>
    <hr class="mt-3" style="color :aqua;text-align:center">
    <form action="{{ route('users.store') }}" method="POST" class="validate-form" enctype="multipart/form-data">
        @method('POST')
        @csrf

        @include('users._form')
    </form>
@endsection
