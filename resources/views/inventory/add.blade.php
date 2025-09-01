@extends('template')

@section('title')
    Création d'un Inventaire
@endsection

@section('content')
<div class="container mt-4">
    <div class="d-flex align-items-center mb-4">
        <h2 class="h5 mb-0">Ajout d'un Inventaire</h2>
    </div>

    <form action="{{ route('inventory.store') }}" id="add_form_supply" method="POST">
        @csrf

        <div class="mb-3 d-flex flex-column flex-sm-row align-items-start align-items-sm-center">
            <input type="text" name="inventory_title"
                class="form-control form-control-lg me-sm-2 mb-2 mb-sm-0"
                placeholder="Titre de la sortie">
        </div>

        <div class="row g-3">
            <div class="col-12 col-lg-6">
                <div class="d-flex flex-column flex-lg-row gap-2 mb-3">
                    <div class="position-relative flex-grow-1">
                        <input oninput="searchByName(this)" type="text" class="form-control" placeholder="Search item...">
                        <i class="bi bi-search position-absolute top-50 end-0 translate-middle-y me-3"></i>
                    </div>

                    <div style="width: 200px;">
                        <select class="form-select" onchange="filterByCategory(this)">
                            <option disabled selected>Filtrer par catégorie</option>
                            @foreach ($categories as $val)
                                <option value="{{ $val->category_id }}">{{ $val->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row g-2" id="productBox">
                    <div class="col-12 col-sm-4 d-flex flex-column justify-content-center align-items-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div class="text-center text-muted small mt-2">Loading</div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle module_data_table mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>Produit</th>
                                    <th>Qté System</th>
                                    <th>Qté Physique</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="myTbody"></tbody>
                        </table>
                    </div>
                    <div class="card-footer d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-primary d-none" id="infos">
                            <span class="spinner-border spinner-border-sm me-2"></span>Enregistrement
                        </button>
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

    function _setupAllProducts(products){
        var html = '';
        products.forEach(element => {
            html += `
                <div onclick="selectProduct(${element.item_id})" class="col-12 col-sm-4 col-xl-3 card p-3 mb-2 cursor-pointer">
                    <div class="fw-bold">${element.item_name}</div>
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
        var search = $(th).val().toLowerCase();
        var result = products.filter(row => row.item_name.toLowerCase().includes(search));
        _setupAllProducts(result);
    }

    function selectProduct(id) {
        if (chosenProductArray.includes(id)) {
            Swal.fire({
                position: 'top-end',
                icon: 'warning',
                title: "Le produit a déjà été sélectionné",
                showConfirmButton: false
            });
            return;
        }

        var product = products.find(row => row.item_id === id);

        $('#myTbody').append(`
            <tr id="tr${product.item_id}">
                <td>
                    ${product.item_name}
                    <input type="hidden" name="product[]" value="${product.item_name}">
                    <input type="hidden" name="codebar[]" value="${product.item_codebar}">
                    <input type="hidden" name="price[]" value="${product.item_costprice}">
                </td>
                <td>
                    ${product.item_quantity ?? 0}
                    <input type="hidden" name="s_quantity[]" value="${product.item_quantity ?? 0}">
                </td>
                <td>
                    <input type="number" name="p_quantity[]" class="form-control form-control-sm">
                </td>
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
                    document.location.href = "{{ route('inventory.index') }}";
                } else {
                    var errors = Object.values(data.messages);
                    var html = "<ul class='list-group'>";
                    errors.forEach(element => {
                        html += `<li class="list-group-item list-group-item-danger">${element}</li>`;
                    });
                    html += "</ul>";
                    Swal.fire({
                        icon: 'error',
                        title: "Oups, il y a des erreurs",
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
