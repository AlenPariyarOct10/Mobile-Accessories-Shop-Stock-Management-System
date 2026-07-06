@include('partials.errors')
@php
    $selectedItemValue = old('item_id', $stockEntry->item_id ?? $selectedItemId ?? '');
    $selectedSupplierValue = old('supplier_id', $stockEntry->supplier_id ?? $selectedSupplierId ?? '');
    $selectedItem = $items->firstWhere('id', (int) $selectedItemValue);
    $selectedSupplier = $suppliers->firstWhere('id', (int) $selectedSupplierValue);
    $selectedItemLabel = $selectedItem ? $selectedItem->name.' (Stock: '.$selectedItem->current_stock.')' : '';
    $selectedSupplierLabel = $selectedSupplier?->name ?? '';
@endphp
<div class="row g-3">
    <div class="col-md-4"><label class="form-label">Date</label><input class="form-control" type="date" name="date" value="{{ old('date', isset($stockEntry) ? $stockEntry->date->format('Y-m-d') : now()->format('Y-m-d')) }}" required></div>
    <div class="col-md-4">
        <div class="d-flex justify-content-between align-items-center">
            <label class="form-label mb-1">Item</label>
            @if($allowQuickCreate ?? false)<button class="btn btn-sm btn-outline-primary quick-create-btn" type="button" data-bs-toggle="modal" data-bs-target="#quickItemModal" title="Add item" aria-label="Add item">+</button>@endif
        </div>
        <div class="searchable-picker">
            <input class="form-control searchable-picker-input" type="search" autocomplete="off" placeholder="Search item" value="{{ $selectedItemLabel }}">
            <input class="searchable-picker-value stock-item-id" type="hidden" name="item_id" value="{{ $selectedItemValue }}">
            <div class="searchable-picker-menu list-group d-none">
                @foreach($items as $item)
                    <button class="list-group-item list-group-item-action" type="button" data-value="{{ $item->id }}" data-label="{{ $item->name }} (Stock: {{ $item->current_stock }})">{{ $item->name }} <small class="text-muted">Stock: {{ $item->current_stock }}</small></button>
                @endforeach
                <div class="list-group-item text-muted searchable-picker-empty d-none">No items found.</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="d-flex justify-content-between align-items-center">
            <label class="form-label mb-1">Supplier</label>
            @if($allowQuickCreate ?? false)<button class="btn btn-sm btn-outline-primary quick-create-btn" type="button" data-bs-toggle="modal" data-bs-target="#quickSupplierModal" title="Add supplier" aria-label="Add supplier">+</button>@endif
        </div>
        <div class="searchable-picker">
            <input class="form-control searchable-picker-input" type="search" autocomplete="off" placeholder="Search supplier" value="{{ $selectedSupplierLabel }}">
            <input class="searchable-picker-value stock-supplier-id" type="hidden" name="supplier_id" value="{{ $selectedSupplierValue }}">
            <div class="searchable-picker-menu list-group d-none">
                <button class="list-group-item list-group-item-action" type="button" data-value="" data-label="">None</button>
                @foreach($suppliers as $supplier)
                    <button class="list-group-item list-group-item-action" type="button" data-value="{{ $supplier->id }}" data-label="{{ $supplier->name }}">{{ $supplier->name }}</button>
                @endforeach
                <div class="list-group-item text-muted searchable-picker-empty d-none">No suppliers found.</div>
            </div>
        </div>
    </div>
    <div class="col-md-3"><label class="form-label">Quantity</label><input class="form-control calc-qty" type="number" min="1" name="quantity" value="{{ old('quantity', $stockEntry->quantity ?? 1) }}" required></div>
    <div class="col-md-3"><label class="form-label">Price Per Item</label><input class="form-control calc-price" type="number" step="0.01" min="0" name="price_per_item" value="{{ old('price_per_item', $stockEntry->price_per_item ?? 0) }}" required></div>
    <div class="col-md-3"><label class="form-label">Selling Price</label><input class="form-control" type="number" step="0.01" min="0" name="selling_price" value="{{ old('selling_price', $stockEntry->selling_price ?? '') }}"></div>
    <div class="col-md-3"><label class="form-label">Total Purchase</label><input class="form-control calc-total" type="number" step="0.01" readonly></div>
    <div class="col-md-6"><label class="form-label">Image</label><input class="form-control" type="file" name="image" accept=".jpg,.jpeg,.png,.webp"></div>
    <div class="col-12"><label class="form-label">Notes</label><textarea class="form-control" name="notes" rows="3">{{ old('notes', $stockEntry->notes ?? '') }}</textarea></div>
</div>
<div class="mt-3"><button class="btn btn-primary">Save</button><a class="btn btn-light" href="{{ route('stock-entries.index') }}">Cancel</a></div>
@push('scripts')<script>
function calcStock(){const q=parseFloat(document.querySelector('.calc-qty')?.value||0);const p=parseFloat(document.querySelector('.calc-price')?.value||0);document.querySelector('.calc-total').value=(q*p).toFixed(2)}
function closeStockPickers(except){document.querySelectorAll('.searchable-picker-menu').forEach(menu=>{if(menu!==except){menu.classList.add('d-none')}})}
function filterStockPicker(picker){const input=picker.querySelector('.searchable-picker-input');const value=picker.querySelector('.searchable-picker-value');const buttons=[...picker.querySelectorAll('.list-group-item-action')];const empty=picker.querySelector('.searchable-picker-empty');const term=input.value.trim().toLowerCase();let shown=0;if(input.value!==input.dataset.selectedLabel){value.value=''}buttons.forEach(button=>{const matches=!term || button.textContent.toLowerCase().includes(term);button.classList.toggle('d-none',!matches);if(matches){shown++}});empty?.classList.toggle('d-none',shown>0)}
function chooseStockPickerOption(picker,button){const input=picker.querySelector('.searchable-picker-input');const value=picker.querySelector('.searchable-picker-value');input.value=button.dataset.label;input.dataset.selectedLabel=button.dataset.label;value.value=button.dataset.value;picker.querySelector('.searchable-picker-menu')?.classList.add('d-none');const returnItem=document.querySelector('.quick-return-item-id');const returnSupplier=document.querySelector('.quick-return-supplier-id');if(returnItem){returnItem.value=document.querySelector('.stock-item-id')?.value||''}if(returnSupplier){returnSupplier.value=document.querySelector('.stock-supplier-id')?.value||''}}
document.querySelectorAll('.calc-qty,.calc-price').forEach(el=>el.addEventListener('input',calcStock));
document.querySelectorAll('.searchable-picker').forEach(picker=>{const input=picker.querySelector('.searchable-picker-input');const menu=picker.querySelector('.searchable-picker-menu');input.dataset.selectedLabel=input.value;input.addEventListener('focus',()=>{closeStockPickers(menu);menu.classList.remove('d-none');filterStockPicker(picker)});input.addEventListener('input',()=>{menu.classList.remove('d-none');filterStockPicker(picker)});input.addEventListener('keydown',event=>{if(event.key==='Enter'){const first=[...picker.querySelectorAll('.list-group-item-action:not(.d-none)')][0];if(first){event.preventDefault();chooseStockPickerOption(picker,first)}}});picker.querySelectorAll('.list-group-item-action').forEach(button=>button.addEventListener('click',()=>chooseStockPickerOption(picker,button)))});
document.addEventListener('click',event=>{if(!event.target.closest('.searchable-picker')){closeStockPickers()}});
calcStock();
</script>@endpush
