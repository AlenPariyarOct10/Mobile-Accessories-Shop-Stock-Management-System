<?php

namespace App\Http\Controllers;

use App\Models\RepairService;
use App\Models\ServiceOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ServiceOrderController extends Controller
{
    public function index(): View
    {
        $serviceOrders = ServiceOrder::with('repairService')
            ->latest()
            ->paginate(12);

        return view('service_orders.index', compact('serviceOrders'));
    }

    public function create(): View
    {
        return view('service_orders.create');
    }

    public function store(Request $request): RedirectResponse
    {
        ServiceOrder::create($this->validated($request) + ['status' => 'Pending']);

        return redirect()->route('repair-services.index')->with('success', 'Service order added successfully.');
    }

    public function show(ServiceOrder $serviceOrder): View
    {
        $serviceOrder->load('repairService');

        return view('service_orders.show', compact('serviceOrder'));
    }

    public function edit(ServiceOrder $serviceOrder): View
    {
        return view('service_orders.edit', compact('serviceOrder'));
    }

    public function update(Request $request, ServiceOrder $serviceOrder): RedirectResponse
    {
        $serviceOrder->update($this->validated($request));

        return redirect()->route('repair-services.index')->with('success', 'Service order updated successfully.');
    }

    public function complete(ServiceOrder $serviceOrder): View
    {
        if ($serviceOrder->status === 'Completed') {
            abort(404);
        }

        return view('service_orders.complete', [
            'serviceOrder' => $serviceOrder,
            'paymentModes' => RepairService::PAYMENT_MODES,
        ]);
    }

    public function storeCompletion(Request $request, ServiceOrder $serviceOrder): RedirectResponse
    {
        if ($serviceOrder->status === 'Completed') {
            return redirect()->route('service-orders.show', $serviceOrder)->with('error', 'This service order is already completed.');
        }

        $data = $request->validate([
            'date' => ['required', 'date'],
            'serviced_at' => ['required', 'date'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'charged_price' => ['required', 'numeric', 'min:0'],
            'payment_mode' => ['nullable', Rule::in(RepairService::PAYMENT_MODES)],
            'notes' => ['nullable', 'string'],
        ]);

        $repairService = DB::transaction(function () use ($data, $serviceOrder): RepairService {
            $data['cost_price'] = $data['cost_price'] ?? 0;
            $data['payment_mode'] = $data['payment_mode'] ?: 'Cash';
            $data['profit'] = $data['charged_price'] - $data['cost_price'];

            $repairService = RepairService::create($data + [
                'customer_name' => $serviceOrder->customer_name,
                'customer_phone' => $serviceOrder->customer_phone,
                'customer_address' => $serviceOrder->customer_address,
                'service_type' => $serviceOrder->service_type,
                'description' => $serviceOrder->description,
            ]);

            $serviceOrder->update([
                'status' => 'Completed',
                'completed_at' => $data['serviced_at'],
                'repair_service_id' => $repairService->id,
            ]);

            return $repairService;
        });

        return redirect()->route('repair-services.show', $repairService)->with('success', 'Service order completed and moved to services.');
    }

    public function destroy(ServiceOrder $serviceOrder): RedirectResponse
    {
        $serviceOrder->delete();

        return redirect()->route('repair-services.index')->with('success', 'Service order deleted successfully.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'date' => ['required', 'date'],
            'expected_at' => ['nullable', 'date'],
            'customer_name' => ['nullable', 'string', 'max:191'],
            'customer_phone' => ['nullable', 'string', 'max:191'],
            'customer_address' => ['nullable', 'string', 'max:191'],
            'service_type' => ['required', 'string', 'max:191'],
            'description' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        return $data;
    }
}
