<?php

namespace App\Http\Controllers;

use App\Models\RepairService;
use App\Models\ServiceOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RepairServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $pendingServiceOrders = ServiceOrder::where('status', 'Pending')
            ->orderByRaw('expected_at is null')
            ->orderBy('expected_at')
            ->latest()
            ->get();
        $repairServices = RepairService::latest()->paginate(12);

        return view('repair_services.index', compact('pendingServiceOrders', 'repairServices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('repair_services.create', [
            'paymentModes' => RepairService::PAYMENT_MODES,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $this->prepareTotals($this->validated($request));
        RepairService::create($data);

        return redirect()->route('repair-services.index')->with('success', 'Repair/service record created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(RepairService $repairService): View
    {
        return view('repair_services.show', compact('repairService'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RepairService $repairService): View
    {
        return view('repair_services.edit', [
            'repairService' => $repairService,
            'paymentModes' => RepairService::PAYMENT_MODES,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RepairService $repairService): RedirectResponse
    {
        $repairService->update($this->prepareTotals($this->validated($request)));

        return redirect()->route('repair-services.index')->with('success', 'Repair/service record updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RepairService $repairService): RedirectResponse
    {
        $repairService->delete();

        return redirect()->route('repair-services.index')->with('success', 'Repair/service record deleted successfully.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'date' => ['required', 'date'],
            'serviced_at' => ['required', 'date'],
            'customer_name' => ['nullable', 'string', 'max:191'],
            'customer_phone' => ['nullable', 'string', 'max:191'],
            'customer_address' => ['nullable', 'string', 'max:191'],
            'service_type' => ['required', 'string', 'max:191'],
            'description' => ['nullable', 'string'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'charged_price' => ['required', 'numeric', 'min:0'],
            'payment_mode' => ['nullable', Rule::in(RepairService::PAYMENT_MODES)],
            'notes' => ['nullable', 'string'],
        ]);
    }

    private function prepareTotals(array $data): array
    {
        $data['cost_price'] = $data['cost_price'] ?? 0;
        $data['payment_mode'] = $data['payment_mode'] ?: 'Cash';
        $data['profit'] = $data['charged_price'] - $data['cost_price'];

        return $data;
    }
}
