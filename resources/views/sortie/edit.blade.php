@extends('template')

@section('title')
    Modification d'une sortie stock
@endsection

@section('content')
<div class="container mt-4">

    <!-- Header -->
    <div class="d-flex align-items-center mb-3">
        <h2 class="h5 me-auto">Modifier la sortie stock</h2>
    </div>

    <form action="{{ route('sortie.update',['sortie' => $sortie]) }}" id="add_form_supply" method="POST">
        @csrf
        @method('PUT')

        <!-- Titre et Type -->
        <div class="row mb-3">
            <div class="col-sm-6">
                <input type="text" name="sortie_title" class="form-control form-control-lg" placeholder="Titre de la sortie" value="{{ $sortie->sortie_title }}">
                <input type="hidden" name="type" value="update">
            </div>
            <div class="col-sm-4 ms-auto">
                <select class="form-select" name="sortie_type" id="sortie_type">
                    <option disabled selected>Type de sortie</option>
                    <option value="ST">Sortie Transfert</option>
                    <option value="SP">Sortie Perte</option>
                    <option value="SV">Sortie Vol</option>
                    <option value="SD">Sortie Périmé</option>
                    <option value="SC">Sortie Casse</option>
                </select>
            </div>
        </div>

        <div class="row">
            <!-- Sélection produits -->
            <div class="col-lg-6 mb-3">
                <div class="d-flex mb-2 gap-2 position-relative">
                    <input oninput="searchByName(this)" type="text" class="form-control" placeholder="Search item...">
                    <i class="bi bi-search position-absolute end-0 me-2"></i>
                </div>
                <div class="mb-2">
                    <select class="form-select" onchange="filterByCategory(this)">
                        <option disabled selected>Filtrer par categorie</option>
                        @foreach ($categories as $val)
                            <option value="{{ $val->category_id }}">{{ $val->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="row row-cols-1 row-cols-sm-2 g-2" id="productBox">
                    <div class="col d-flex flex-column align-items-center justify-content-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <small class="text-center mt-2">Loading</small>
                    </div>
                </div>
            </div>

            <!-- Table produits sélectionnés -->
            <div class="col-lg-6 mb-3">
                <div class="card">
                    <div class="table-responsive">
                        <table class="table align-middle table-striped" id="myTbody">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th>Qté</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Produits ajoutés via JS -->
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end p-3 gap-2">
                        <div id="infos" class="d-none">
                            <div class="spinner-border spinner-border-sm text-light" role="status"></div>
                        </div>
                        <button type="button" onclick="handleAddData(this)" class="btn btn-primary">Enregistrer</button>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>
@endsection

@section('js_content')
<script>
    var chosenProductArray = [];
    var categories = <?= json_encode($categories) ?>;
    var products = <?= json_encode($products) ?>;
    var sortie = <?= $sortie ?>;
    var sortieDetails = <?= json_encode($sortieDetails) ?>;

    // Préselection du type
    $('#sortie_type').val(sortie.sortie_type).trigger('change');

    function _setupAllProducts(products){
        var html = '';
        products.forEach(element => {
            html += `
                <div onclick="selectProduct('${element.item_codebar}')" class="col mb-2">
                    <div class="card p-2 cursor-pointer">
                        <div class="fw-bold">${element.item_name}</div>
                        <div class="text-muted">${element.item_quantity} Qté</div>
                    </div>
                </div>
            `;
        });

        $('#productBox').html(html);
    }

    function loadAllProduct() { _setupAllProducts(products); }
    loadAllProduct();

    function filterByCategory(th) {
        var id = $(th).val();
        var result = products.filter(row => row.item_category == id);
        _setupAllProducts(result);
    }

    function searchByName(th) {
        var search = $(th).val();
        var result = products.filter(row => row.item_name.toLowerCase().includes(search.toLowerCase()));
        _setupAllProducts(result);
    }

    function loadEditData() {
        var html = "";
        sortieDetails.forEach(row => {
            html += `
                <tr id="tr${row.ref_product_code}">
                    <td>
                        ${row.item_sortie_name}
                        <input type="hidden" name="product[]" value="${row.item_sortie_name}">
                        <input type="hidden" name="codebar[]" value="${row.ref_product_code}">
                        <input type="hidden" name="price[]" value="${row.item_sortie_price}">
                    </td>
                    <td>
                        <input type="number" name="quantity[]" value="${row.item_sortie_quantity}" class="form-control form-control-sm">
                    </td>
                    <td>
                        <button type="button" onclick="removeTr('${row.ref_product_code}')" class="btn btn-outline-danger btn-sm">X</button>
                    </td>
                </tr>
            `;
            chosenProductArray.push(row.ref_product_code);
        });

        $('#myTbody').html(html);
    }
    loadEditData();

    function selectProduct(id) {
        if (chosenProductArray.indexOf(id) != -1) {
            Swal.fire({position:'top-end', icon:'warning', title:"Le produit a déjà été sélectioné", showConfirmButton:false});
            return;
        }
        var product = products.find(row => row.item_codebar == id);

        $('#myTbody').append(`
            <tr id="tr${product.item_codebar}">
                <td>
                    ${product.item_name}
                    <input type="hidden" name="product[]" value="${product.item_name}">
                    <input type="hidden" name="codebar[]" value="${product.item_codebar}">
                    <input type="hidden" name="price[]" value="${product.item_costprice}">
                </td>
                <td>
                    <input type="number" name="quantity[]" class="form-control form-control-sm">
                </td>
                <td>
                    <button type="button" onclick="removeTr('${product.item_codebar}')" class="btn btn-outline-danger btn-sm">X</button>
                </td>
            </tr>
        `);
        chosenProductArray.push(product.item_codebar);
    }

    function removeTr(id) {
        $(`#tr${id}`).remove();
        chosenProductArray = chosenProductArray.filter(row => row != id);
    }

    function handleAddData(th) {
        $(th).addClass('d-none');
        $('#infos').removeClass('d-none');
        var form = $('#add_form_supply');

        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            dataType: 'json',
            data: form.serialize(),
            success: function(data) {
                $('#infos').addClass('d-none');
                if(data.success) {
                    form.trigger("reset");
                    Swal.fire({position:'top-end', icon:'success', title:data.messages, showConfirmButton:false, timer:3000});
                    window.location.href = "{{ route('sortie.index') }}";
                } else {
                    var html = "<ul class='list-group'>";
                    Object.values(data.messages).forEach(el => html += `<li class="list-group-item text-danger">${el}</li>`);
                    html += "</ul>";
                    Swal.fire({icon:'error', title:"Oups, il y a des erreurs", html: html});
                    $(th).removeClass('d-none');
                }
            }
        });
    }
</script>
@endsection
