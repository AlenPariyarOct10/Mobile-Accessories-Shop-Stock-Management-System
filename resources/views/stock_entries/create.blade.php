@extends('layouts.app')
@section('page_title', 'Add Stock Entry')
@section('content')
@php($allowQuickCreate = true)
<div class="card content-card"><div class="card-body"><form method="POST" action="{{ route('stock-entries.store') }}" enctype="multipart/form-data">@csrf @include('stock_entries._form')</form></div></div>

<div class="modal fade" id="quickItemModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form class="modal-content" method="POST" action="{{ route('stock-entries.quick-items.store') }}">
            @csrf
            <input class="quick-return-supplier-id" type="hidden" name="return_supplier_id" value="{{ old('supplier_id', $selectedSupplierId ?? '') }}">
            <input class="quick-item-code-auto" type="hidden" name="code_auto_generated" value="1">
            <div class="modal-header"><h2 class="modal-title h5">Add Item</h2><button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button></div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label">Name</label><input class="form-control quick-item-name" name="name" required></div>
                    <div class="col-md-6"><label class="form-label">SKU / Code</label><input class="form-control quick-item-code" name="code"></div>
                    <div class="col-md-4"><label class="form-label">Purchase Price</label><input class="form-control quick-item-purchase" type="number" step="0.01" min="0" name="default_purchase_price" value="0"></div>
                    <div class="col-md-4"><label class="form-label">Selling Price</label><input class="form-control quick-item-selling" type="number" step="0.01" min="0" name="default_selling_price" value="0"></div>
                    <div class="col-md-4"><label class="form-label">Low Stock Alert</label><input class="form-control" type="number" min="0" name="low_stock_alert_quantity" value="5" required></div>
                    <div class="col-12"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="2"></textarea></div>
                </div>
            </div>
            <div class="modal-footer"><button class="btn btn-light" type="button" data-bs-dismiss="modal">Cancel</button><button class="btn btn-primary">Create Item</button></div>
        </form>
    </div>
</div>

<div class="modal fade" id="quickSupplierModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form class="modal-content" method="POST" action="{{ route('stock-entries.quick-suppliers.store') }}">
            @csrf
            <input class="quick-return-item-id" type="hidden" name="return_item_id" value="{{ old('item_id', $selectedItemId ?? '') }}">
            <div class="modal-header"><h2 class="modal-title h5">Add Supplier</h2><button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button></div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label">Name</label><input class="form-control" name="name" required></div>
                    <div class="col-md-6"><label class="form-label">Phone</label><input class="form-control" name="phone"></div>
                    <div class="col-md-6"><label class="form-label">Email</label><input class="form-control" type="email" name="email"></div>
                    <div class="col-md-6"><label class="form-label">Address</label><input class="form-control" name="address"></div>
                    <div class="col-12"><label class="form-label">Notes</label><textarea class="form-control" name="notes" rows="2"></textarea></div>
                </div>
            </div>
            <div class="modal-footer"><button class="btn btn-light" type="button" data-bs-dismiss="modal">Cancel</button><button class="btn btn-primary">Create Supplier</button></div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
const quickItemPurchase = document.querySelector('.quick-item-purchase');
const quickItemSelling = document.querySelector('.quick-item-selling');
const quickItemCode = document.querySelector('.quick-item-code');
const quickItemCodeAuto = document.querySelector('.quick-item-code-auto');
const quickItemName = document.querySelector('.quick-item-name');
let quickSellingTouched = false;
function quickCodeFromName(name){return (name||'').trim().toUpperCase().replace(/[^A-Z0-9]+/g,'-').replace(/^-+|-+$/g,'') || 'ITEM'}
quickItemName?.addEventListener('input',()=>{if(quickItemCodeAuto?.value==='1'){quickItemCode.value=quickCodeFromName(quickItemName.value)}});
quickItemCode?.addEventListener('input',()=>{quickItemCodeAuto.value='0'});
quickItemSelling?.addEventListener('input',()=>quickSellingTouched=true);
quickItemPurchase?.addEventListener('input',()=>{if(!quickSellingTouched || !quickItemSelling.value){quickItemSelling.value=quickItemPurchase.value}});
</script>
@endpush
