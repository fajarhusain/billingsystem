@extends('layouts.app')

@section('title', 'Generate Tagihan Bulanan')

@section('content')
<div class="container">
    <h1>Generate Tagihan Bulanan</h1>
    <form action="{{ route('invoices.generateMonthly') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="period" class="form-label">Periode</label>
            <input type="month" name="period" id="period" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="due_date" class="form-label">Jatuh Tempo</label>
            <input type="date" name="due_date" id="due_date" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Generate</button>
    </form>
</div>
@endsection