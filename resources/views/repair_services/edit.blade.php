@extends('layouts.app')
@section('page_title', 'Edit Repair / Service')
@section('content')<div class="card content-card"><div class="card-body"><form method="POST" action="{{ route('repair-services.update', $repairService) }}">@csrf @method('PUT') @include('repair_services._form')</form></div></div>@endsection
