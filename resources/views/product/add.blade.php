@extends('template')

@section('title')
    Création d'une article
@endsection

@section('content')
    <div class="grid grid-cols-12 gap-6 mt-1">
        <div class="intro-y col-span-12 lg:col-span-12">
            <!-- BEGIN: Form Validation -->
            <div class="intro-y box">
                <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                    <h2 class="font-medium text-base mr-auto">
                        Formulaire
                    </h2>
                </div>
                <div class="p-5" id="basic-datepicker">
                    <div class="preview">
                        <form action="{{ route('product.store') }}" method="POST" class=" validate-form">
                            @method('POST')
                            @csrf
                            @include('product.prodForm')
                            <button type="submit" class="button btn btn-primary self-center text-white mt-5">Enregistrer</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- END: Form Validation -->
        </div>
    </div>

@endsection
