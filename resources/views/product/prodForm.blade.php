<div class="row  p-3">
    <!-- Colonne gauche -->
    <div class="col-lg-6 col-12 mb-3">
        <!-- Nom de l'Article -->
        <div class="mb-3">
            <x-input :type="'text'" :name="'item_name'" :required="true"
                     :value="$product->item_name ?? ''" :label="'Nom de l\'Article'" />
        </div>

        <!-- Catégorie -->
        <div class="mb-3">
            <label class="form-label">Catégorie <x-required /></label>
            <select class="form-select @error('item_category') is-invalid @enderror" name="item_category">
                <option disabled selected>Selectionner la categorie</option>
                @foreach ($categories as $value)
                    <option value="{{ $value->category_id }}" {{ $product->item_category == $value->category_id ? 'selected' : '' }}>
                        {{ $value->title }}
                    </option>
                @endforeach
            </select>
            @error('item_category')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Unité de mesure -->
        <div class="mb-3">
            <label class="form-label">Unité de Mesure <x-required /></label>
            <select class="form-select @error('item_unity') is-invalid @enderror" name="item_unity">
                <option disabled selected>Selectionner l'unité de mesure</option>
                @foreach ($unities as $value)
                    <option value="{{ $value->unity_id }}" {{ $product->item_unity == $value->unity_id ? 'selected' : '' }}>
                        {{ $value->title }}
                    </option>
                @endforeach
            </select>
            @error('item_unity')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Type d'article -->
        <div class="mb-3">
            <label class="form-label">Type d'article <x-required /></label>
            <select class="form-select @error('item_type') is-invalid @enderror" name="item_type">
                <option disabled selected>Selectionner le type</option>
                <option value="0" {{ $product->item_type == 0 ? 'selected' : '' }}>Article Stockable</option>
                <option value="1" {{ $product->item_type == 1 ? 'selected' : '' }}>Article Non Stockable</option>
            </select>
            @error('item_type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Article vendable -->
        <div class="mb-3">
            <label class="form-label">L'article est à vendre <x-required /></label>
            <select class="form-select @error('item_isSellable') is-invalid @enderror" name="item_isSellable">
                <option disabled selected>Selectionner le statut</option>
                <option value="0" {{ $product->item_isSellable == 0 ? 'selected' : '' }}>OUI</option>
                <option value="1" {{ $product->item_isSellable == 1 ? 'selected' : '' }}>NON</option>
            </select>
            @error('item_isSellable')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Colonne droite -->
    <div class="col-lg-6 col-12 mb-3">
        <!-- TVA -->
        <div class="mb-3">
            <label class="form-label">TVA de l'article</label>
            <select class="form-select @error('item_tva') is-invalid @enderror" name="item_tva">
                <option disabled selected>Selectionner la TVA</option>
                <option value="1" {{ $product->item_tva == 1 ? 'selected' : '' }}>0%</option>
                <option value="1.1" {{ $product->item_tva == 1.1 ? 'selected' : '' }}>10%</option>
                <option value="1.18" {{ $product->item_tva == 1.18 ? 'selected' : '' }}>18%</option>
            </select>
        </div>

        <!-- Autres taxes -->
        <div class="mb-3">
            <label class="form-label">Autres Taxe</label>
            <div class="row">
                <div class="col-md-6 mb-2">
                    <div class="input-group">
                        <span class="input-group-text">TC</span>
                        <input type="number" class="form-control" placeholder="Taxe de consommation"
                               name="item_tc" value="{{ old('item_tc', $product->item_tc) }}">
                    </div>
                </div>
                <div class="col-md-6 mb-2">
                    <div class="input-group">
                        <span class="input-group-text">PF</span>
                        <input type="number" class="form-control" placeholder="Prélevement forfaiteur"
                               name="item_pf" value="{{ old('item_pf', $product->item_pf) }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- Prix de vente -->
        <div class="mb-3">
            <x-input :type="'number'" :name="'item_sellprice'" :required="true"
                     :value="$product->item_sellprice ?? 0" :label="'Prix de vente'" />
        </div>

        <!-- Prix d'achat -->
        <div class="mb-3">
            <x-input :type="'number'" :name="'item_costprice'"
                     :value="$product->item_costprice ?? 0" :label="'Prix d\'achat'" />
        </div>

        <!-- Prix de revient -->
        <div class="mb-3">
            <x-input :type="'number'" :name="'item_corprice'" :required="true"
                     :value="$product->item_corprice ?? 0" :label="'Prix de Reviens'" />
        </div>
    </div>
</div>
