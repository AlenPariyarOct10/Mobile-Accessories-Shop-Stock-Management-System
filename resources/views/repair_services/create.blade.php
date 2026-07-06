@extends('layouts.app')
@section('page_title', 'Add Repair / Service')
@section('content')<div class="card content-card"><div class="card-body"><form method="POST" action="{{ route('repair-services.store') }}">@csrf @include('repair_services._form')</form></div></div>@endsection
