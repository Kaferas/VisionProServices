<style>
    /* Strong colored borders and taller inputs */
    .form-control-strong, .form-select-strong {
        border: 2px solid #007bff;
        border-radius: 8px;
        transition: all 0.3s ease;
        padding: 0.75rem 1rem;
        font-size: 1.05rem;
    }

    .form-control-strong:focus {
        border-color: #ff4d4f;
        box-shadow: 0 0 5px rgba(255,77,79,0.7);
        outline: none;
    }

    .form-select-strong:focus {
        border-color: #ffc107;
        box-shadow: 0 0 5px rgba(255,193,7,0.7);
        outline: none;
    }

    .btn-primary {
        padding: 0.6rem 1.5rem;
        font-size: 1rem;
    }

    img#previewImg {
        max-height: 120px;
        border-radius: 6px;
        margin-top: 5px;
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="card shadow-lg rounded-4 p-3">
                <h3 class="text-center mb-3">
                    {{ isset($user) ? 'Modifier Utilisateur' : 'Créer Utilisateur' }}
                </h3>

                <form action="{{ isset($user) ? route('users.update', $user) : route('users.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(isset($user)) @method('PUT') @endif

                    {{-- Row 1: Name + Email --}}
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nom Utilisateur <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-strong" value="{{ $user->name ?? '' }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control form-control-strong" value="{{ $user->email ?? '' }}" required>
                        </div>
                    </div>

                    {{-- Row 2: Phone + Address --}}
                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control form-control-strong" value="{{ $user->phone ?? '' }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Adresse <span class="text-danger">*</span></label>
                            <input type="text" name="adresse" class="form-control form-control-strong" value="{{ $user->adresse ?? '' }}" required>
                        </div>
                    </div>

                    {{-- Row 3: Password + Retype Password --}}
                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <label class="form-label">Password {{ !isset($user) ? '*' : '' }}</label>
                            <input type="password" name="password" class="form-control form-control-strong" {{ !isset($user) ? 'required' : '' }}>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Retype Password {{ !isset($user) ? '*' : '' }}</label>
                            <input type="password" name="password_confirmation" class="form-control form-control-strong" {{ !isset($user) ? 'required' : '' }}>
                        </div>
                    </div>

                    {{-- Row 4: PIN + Role --}}
                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <label class="form-label">Code PIN <span class="text-danger">*</span></label>
                            <input type="text" name="pin_code" class="form-control form-control-strong" value="{{ $user->pin_code ?? '' }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Droits <span class="text-danger">*</span></label>
                            <select name="user_right" class="form-select form-select-strong" required>
                                <option disabled selected>Selectionner le Role</option>
                                @foreach ($roles as $item)
                                    <option value="{{ $item->id }}" {{ isset($user) && $item->id == ($user->roles[0]->id ?? null) ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Row 5: Banned + Profile Picture --}}
                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <label class="form-label">Utilisateur Banni <span class="text-danger">*</span></label>
                            <select name="isBanned" class="form-select form-select-strong" required>
                                <option disabled>Selectionner le Type</option>
                                <option value="0" {{ isset($user) && $user->isBanned == 0 ? 'selected' : '' }}>NON</option>
                                <option value="1" {{ isset($user) && $user->isBanned == 1 ? 'selected' : '' }}>OUI</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Profile Picture</label>
                            <input type="file" name="profilePath" class="form-control form-control-strong" accept="image/*" onchange="previewImage(event)">
                            <img id="previewImg" src="{{ isset($user) ? asset($user->profilePath) : '' }}" alt="Profile Preview">
                        </div>
                    </div>

                    {{-- Row 6: About Me --}}
                    <div class="row g-3 mt-2">
                        <div class="col-12">
                            <label class="form-label">A propos de Moi</label>
                            <textarea name="aboutMe" rows="4" class="form-control form-control-strong">{{ $user->aboutMe ?? '' }}</textarea>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-primary">
                            {{ isset($user) ? 'Modifier Utilisateur' : 'Enregistrer Utilisateur' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function previewImage(event) {
        const [file] = event.target.files;
        if(file) document.getElementById('previewImg').src = URL.createObjectURL(file);
    }
</script>
