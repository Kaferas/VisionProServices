@extends('template')

@section('title')
    Création d'une approvisionnement
@endsection

@section('content')
<div class="d-flex align-items-center mt-4 mb-3">
    <h2 class="h5 mb-0">Ajout d'approvisionnement</h2>
</div>

<form action="{{ route('supply.store') }}" id="add_form_supply" method="POST">
    @csrf

    <div class="row g-3 mb-3 align-items-center">
        <div class="col-lg-4 col-12">
            <input type="text" name="appro_title" class="form-control form-control-lg" placeholder="titre de l'approvisionnement">
        </div>
        <div class="col-lg-3 col-12 ms-auto">
            <select class="form-select" name="supply_type">
                <option disabled selected>Type d'entré stock</option>
                <option value="EN">Entré Normale</option>
                <option value="ET">Entré Transfert</option>
                <option value="EAJ">Entré Ajustement</option>
            </select>
        </div>
    </div>

    <div class="row g-3 mt-3">
        <div class="col-lg-6 col-12">
            <div class="d-flex mb-3">
                <div class="flex-grow-1 me-2 position-relative">
                    <input oninput="searchByName(this)" type="text" class="form-control" placeholder="Search item...">
                    <i class="bi bi-search position-absolute end-0 top-50 translate-middle-y me-2"></i>
                </div>
                <div class="flex-shrink-1" style="width: 220px;">
                    <select class="form-select" onchange="filterByCategory(this)">
                        <option disabled selected>Filtrer par categorie</option>
                        @foreach ($categories as $val)
                            <option value="{{ $val->category_id }}">{{ $val->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row g-2" id="productBox">
                <div class="col-12 text-center py-3">
                    <div class="spinner-border text-primary" role="status"></div>
                    <div class="small mt-2">Loading</div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-12">
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped module_data_table mb-0">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Qté</th>
                                <th>Prix</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="myTbody">

                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end p-3">
                    <div class="btn btn-primary d-none align-items-center" id="infos">
                        <span class="spinner-border spinner-border-sm ms-2"></span>
                    </div>
                    <button type="button" onclick="addSupply(this)" class="btn btn-primary ms-2">Enregistrer</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('js_content')
<script>
    var chosenProductArray = [];
    var categories = <?= json_encode($categories) ?>;
    var products = <?= json_encode($products) ?>;

    function _setupAllProducts(products){
        var html = '';
        products.forEach(element => {
            html += `
                <div onclick="selectProduct(${element.item_id})" class="col-12 col-sm-6 col-xl-3 card p-3 mb-2 cursor-pointer">
                    <div class="fw-medium">${element.item_name}</div>
                    <div class="text-muted">${element.item_quantity} Qté</div>
                </div>
            `;
        });
        $('#productBox').html(html);
    }

    function loadAllProduct() {
        _setupAllProducts(products);
    }

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

    function selectProduct(id) {
        if (chosenProductArray.indexOf(id) != -1) {
            Swal.fire({
                position: 'top-end',
                icon: 'warning',
                title: "Le produit a déjà été sélectioner",
                showConfirmButton: false,
                timer: 1500
            });
            return;
        }
        var product = products.filter(row => row.item_id == id)[0];
        $('#myTbody').append(`
            <tr id="tr${product.item_id}">
                <td>
                    ${product.item_name}
                    <input type="hidden" name="product[]" value="${product.item_name}">
                    <input type="hidden" name="codebar[]" value="${product.item_codebar}">
                </td>
                <td><input type="number" name="quantity[]" class="form-control form-control-sm"></td>
                <td><input type="number" name="price[]" class="form-control form-control-sm" value="${product.item_costprice}"></td>
                <td class="text-center">
                    <button type="button" onclick="removeTr(${product.item_id})" class="btn btn-outline-danger btn-sm">X</button>
                </td>
            </tr>
        `);
        chosenProductArray.push(id);
    }

    function removeTr(id) {
        $(`#tr${id}`).remove();
        chosenProductArray = chosenProductArray.filter(row => row != id);
    }

    function addSupply(th) {
        $(th).addClass('d-none');
        $('#infos').removeClass('d-none');
        var form = $('#add_form_supply');

        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            dataType: 'json',
            data: form.serialize(),
            success: function(data) {
                if(data.success) {
                    $('#infos').addClass('d-none');
                    form.trigger("reset");
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: data.messages,
                        showConfirmButton: false,
                        timer: 3000
                    });
                    document.location.href = "{{ route('supply.index') }}";
                } else {
                    var errors = Object.values(data.messages);
                    var html = "<ul class='list-group'>";
                    errors.forEach(element => {
                        html += `<li class='list-group-item text-danger'>${element}</li>`;
                    });
                    html += "</ul>";
                    Swal.fire({
                        icon: 'error',
                        title: "Oups,il y a des erreurs",
                        html: html,
                    });
                    $(th).removeClass('d-none');
                    $('#infos').addClass('d-none');
                }
            }
        });
    }
</script>
@endsection
