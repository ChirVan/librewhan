@extends('layouts.app')
@section('title', 'Stock Movement History')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold">Stock Movement History</h2>
            <p class="text-muted">Track all changes made to product stock levels.</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('inventory.stock') }}" class="btn btn-outline-primary">Back to Stock Levels</a>
        </div>
    </div>
    <div class="card">
        <div class="card-header bg-secondary text-white fw-bold">Stock Movements</div>
        <div class="card-body">
            <div class="alert alert-info mb-0">Stock movement tracking will be available once the audit log is implemented.</div>
        </div>
    </div>
</div>
@endsection
