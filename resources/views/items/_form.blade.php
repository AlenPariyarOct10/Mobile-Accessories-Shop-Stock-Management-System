@include('partials.errors')
<div class="row g-3">
    <div class="col-md-6"><label class="form-label">Name</label><input class="form-control item-name" name="name" value="{{ old('name', $item->name ?? '') }}" required></div>
    <div class="col-md-6">
        <label class="form-label">SKU / Code</label>
        <input class="form-control item-code" name="code" value="{{ old('code', $item->code ?? '') }}">
        <input class="item-code-auto" type="hidden" name="code_auto_generated" value="{{ old('code_auto_generated', empty($item->code ?? '') ? 1 : 0) }}">
    </div>
    <div class="col-md-6"><label class="form-label">Default Purchase Price</label><input class="form-control item-purchase-price" type="number" step="0.01" min="0" name="default_purchase_price" value="{{ old('default_purchase_price', $item->default_purchase_price ?? 0) }}"></div>
    <div class="col-md-6">
        <label class="form-label">Default Selling Price</label>
        <input class="form-control item-selling-price" type="number" step="0.01" min="0" name="default_selling_price" value="{{ old('default_selling_price', $item->default_selling_price ?? 0) }}">
        <small class="text-warning item-loss-warning d-none">Selling price is below purchase price. This item may sell at a loss.</small>
    </div>
    <div class="col-md-6"><label class="form-label">Low Stock Alert Quantity</label><input class="form-control" type="number" min="0" name="low_stock_alert_quantity" value="{{ old('low_stock_alert_quantity', $item->low_stock_alert_quantity ?? 5) }}" required></div>
    <div class="col-md-6"><label class="form-label">Image</label><input class="form-control" type="file" name="image" accept=".jpg,.jpeg,.png,.webp"></div>
    <div class="col-12"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="3">{{ old('description', $item->description ?? '') }}</textarea></div>
    <div class="col-12"><div class="form-check"><input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', $item->is_active ?? true))><label class="form-check-label" for="is_active">Active</label></div></div>
</div>
<div class="mt-3"><button class="btn btn-primary">Save</button><a class="btn btn-light" href="{{ route('items.index') }}">Cancel</a></div>
@push('scripts')
<script>
const existingItemCodes = new Set(@json(($existingItemCodes ?? collect())->map(fn ($code) => strtoupper($code))->values()));
const itemName = document.querySelector('.item-name');
const itemCode = document.querySelector('.item-code');
const itemCodeAuto = document.querySelector('.item-code-auto');
const purchasePrice = document.querySelector('.item-purchase-price');
const sellingPrice = document.querySelector('.item-selling-price');
const lossWarning = document.querySelector('.item-loss-warning');
let previousPurchasePrice = purchasePrice?.value || '';
let sellingTouched = false;

function codeBaseFromName(name) {
    return (name || '').trim().toUpperCase().replace(/[^A-Z0-9]+/g, '-').replace(/^-+|-+$/g, '') || 'ITEM';
}

function nextItemCode(name) {
    const base = codeBaseFromName(name);
    let code = base;
    let number = 2;

    while (existingItemCodes.has(code)) {
        code = `${base}-${number}`;
        number++;
    }

    return code;
}

function syncItemCode() {
    if (itemCodeAuto?.value === '1') {
        itemCode.value = nextItemCode(itemName.value);
    }
}

function syncSellingPrice() {
    if (!sellingTouched || !sellingPrice.value || sellingPrice.value === previousPurchasePrice) {
        sellingPrice.value = purchasePrice.value;
    }

    previousPurchasePrice = purchasePrice.value;
    updateLossWarning();
}

function updateLossWarning() {
    const purchase = parseFloat(purchasePrice?.value || 0);
    const selling = parseFloat(sellingPrice?.value || 0);
    lossWarning?.classList.toggle('d-none', !(selling < purchase));
}

itemName?.addEventListener('input', syncItemCode);
itemCode?.addEventListener('input', () => {
    itemCodeAuto.value = '0';
});
purchasePrice?.addEventListener('input', syncSellingPrice);
purchasePrice?.addEventListener('change', syncSellingPrice);
sellingPrice?.addEventListener('input', () => {
    sellingTouched = true;
    updateLossWarning();
});

syncItemCode();
updateLossWarning();
</script>
@endpush
