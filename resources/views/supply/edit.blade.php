@extends('layouts.template')

@section('title')
    Création d'une approvisionnement
@endsection

@section('content')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Ajout d'approvisionnement
    </h2>
</div>

<form action="{{ route('supply.update',['supply' => $supply]) }}" id="add_form_supply" method="PUT">
    @csrf

    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
        <input type="text" name="appro_title" class="input input--lg w-full lg:w-64 box pr-10 placeholder-theme-13" value="{{ $supply->title }}" placeholder="titre de l'approvisionnement">
        <input type="hidden" name="type" value="update" >
        <div class="w-72 lg:w-80 mt-3 lg:mt-0 ml-auto" style="width: 200px!important">
            <select class="select2 w-full" name="supply_type">
                <option disabled selected>Type d'entré stock</option>
                <option value="EN" {{old('supply_type',$supply->supply_type) == "EN" ? 'selected' : ''}}>Entré Normale</option>
                <option value="ET" {{old('supply_type',$supply->supply_type) == "ET" ? 'selected' : ''}}>Entré Transfert</option>
                <option value="EAJ" {{old('supply_type',$supply->supply_type) == "EAJ" ? 'selected' : ''}}>Entré Ajustement</option>
            </select>
        </div>
    </div>

    <div class="pos intro-y grid grid-cols-12 gap-5 mt-5">
        <div class="intro-y col-span-12 lg:col-span-6 md:col-span-5">
            <div class="lg:flex intro-y">
                <div class="relative text-gray-700">
                    <input oninput="searchByName(this)" type="text" class="input input--md w-full lg:w-64 box pr-10 placeholder-theme-13" placeholder="Search item...">
                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-feather="search"></i>
                </div>
                <div class="w-64 lg:w-70 mt-3 lg:mt-0 ml-auto">
                    <select class="select2 w-full" onchange="filterByCategory(this)">
                        <option disabled selected>Filtrer par categorie</option>
                        @foreach ($categories as $val)
                            <option value="{{ $val->category_id }}">{{ $val->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-12 gap-2 mt-4" id="productBox">
                {{-- <div class="col-span-12 sm:col-span-3 xl:col-span-3 box p-2 cursor-pointer zoom-in">
                    <div class="font-medium text-base">Soup</div>
                    <div class="text-gray-600">5 Items</div>
                </div>
                <div class="col-span-12 sm:col-span-4 xxl:col-span-3 box bg-theme-1 p-5 cursor-pointer zoom-in">
                    <div class="font-medium text-base text-white">Pancake & Toast</div>
                    <div class="text-theme-25">8 Items</div>
                </div> --}}

                <div class="col-span-12 sm:col-span-4 xl:col-span-3 flex flex-col justify-end items-center">
                    <i data-loading-icon="tail-spin" class="w-10 h-10"></i>
                    <div class="text-center text-xs mt-2">Loading</div>
                </div>
            </div>
        </div>

        <div class="col-span-12 lg:col-span-6 md:col-span-7">
            <div class="intro-y box">
                <table class="table table-report -mt-2 module_data_table">
                    <thead>
                        <tr class="border-solid border-b border-gray-200">
                            <td>Produit</td>
                            <td>Qté</td>
                            <td>Prix</td>
                            <td>Action</td>
                        </tr>
                    </thead>
                    <tbody id="myTbody">

                    </tbody>

                </table>
                <div class="flex justify-end p-5">
                    <div class="button bg-theme-1 text-white items-center hidden mt-5" id="infos">
                        {{-- Enregistrement  --}}
                        <i data-loading-icon="oval" data-color="white" class="w-4 h-4 ml-auto"></i>
                    </div>
                    <button type="button" onclick="addSupply(this)" class="button bg-theme-1 self-center text-white mt-5 ">Enregistrer</button>
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
        var supplyDetails = <?= json_encode($supplyDetails) ?>;

        function _setupAllProducts(products){
            var html = '';
            products.forEach(element => {
                html += `
                    <div onclick="selectProduct('${element.item_codebar}')" class="col-span-12 sm:col-span-4 xxl:col-span-3 box p-5 cursor-pointer zoom-in">
                        <div class="font-medium text-base">${element.item_name}</div>
                        <div class="text-gray-600 text-left">${element.item_quantity} Qté</div>
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

        function loadEditData() {
            var html = "";
            supplyDetails.forEach(row => {
                html += `
                    <tr id="tr${row.ref_product_code}">
                        <td>
                            ${row.product_name}
                            <input type="hidden" name="product[]" value="${row.product_name}">
                            <input type="hidden" name="codebar[]" value="${row.ref_product_code}">
                        </td>
                        <td>
                            <input type="number" name="quantity[]" value="${row.item_quantity}" class="input border input--sm w-full">
                        </td>
                        <td>
                            <input type="number" name="price[]" class="input border input--sm w-full" value="${row.purchase_price}">
                        </td>
                        <td class="table-report__action w-10">
                            <div class="flex justify-center items-center">
                                <a href="javascript:;" onclick="removeTr('${row.ref_product_code}')" class="btn btn-outline btn-danger text-theme-6">
                                    X
                                </a>

                            </div>
                        </td>
                    </tr>
                `;
                chosenProductArray.push(row.ref_product_code);

            });

            $('#myTbody').html(html);
        }
        loadEditData();

        function selectProduct(code) {

            if (chosenProductArray.indexOf(code) != -1) {
                swal.fire({
                    position: 'top-end',
                    icon: 'warning',
                    title: "Le produit a déjà été sélectioner",
                    showConfirmButton: false,
                });
                return;
            }
            var product = products.filter(row => row.item_codebar == code)[0];

            $('#myTbody').append(`
                <tr id="tr${product.item_codebar}">
                    <td>
                        ${product.item_name}
                        <input type="hidden" name="product[]" value="${product.item_name}">
                        <input type="hidden" name="codebar[]" value="${product.item_codebar}">
                    </td>
                    <td>
                        <input type="number" name="quantity[]" class="input border input--sm w-full">
                    </td>
                    <td>
                        <input type="number" name="price[]" class="input border input--sm w-full" value="${product.item_costprice}">
                    </td>
                    <td class="table-report__action w-10">
                        <div class="flex justify-center items-center">
                            <a href="javascript:;" onclick="removeTr('${product.item_codebar}')" class="btn btn-outline btn-danger text-theme-6">
                                X
                            </a>

                        </div>
                    </td>
                </tr>
            `);
            chosenProductArray.push(product.item_codebar);
        }

        function removeTr(id) {
            $(`#tr${id}`).remove();
            chosenProductArray = chosenProductArray.filter((row) => {
                return row != id
            });
        }

        function addSupply(th) {

            // $(th).attr('disabled',true);
            $(th).addClass('hidden');
            $('#infos').removeClass('hidden');
            var form = $('#add_form_supply');

            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                dataType: 'json',
                data: form.serialize(),
                success: function(data) {
                    if(data.success) {
                        $(th).attr('disabled',false);
                        // $(th).removeClass('hidden');
                        $('#infos').addClass('hidden');
                        // $('.module_data_table').load(' .module_data_table');
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
                            html += `<li class="list-group-item text-danger">${element}</li>`;
                        });
                        html += "</ul>";
                        Swal.fire({
                            icon: 'error',
                            title: "Oups,il y a des erreurs",
                            html: html,
                        });
                        $(th).attr('disabled',false);
                        $(th).removeClass('hidden');
                        $('#infos').addClass('hidden');
                    }
                }
            })

        }
    </script>
@endsection
