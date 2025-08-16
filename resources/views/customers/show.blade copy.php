use SimpleSoftwareIO\QrCode\Facades\QrCode;
@extends('layouts.app')

@section('title', 'Detail Pelanggan')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-info text-white">
            <h3 class="card-title">
                <i class="fas fa-user mr-2"></i>
                Detail Pelanggan: {{ $customer->name }}
            </h3>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Nama</th>
                            <td>{{ $customer->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $customer->email }}</td>
                        </tr>
                        <tr>
                            <th>Telepon</th>
                            <td>{{ $customer->phone }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>{!! $customer->status_badge !!}</td>
                        </tr>
                        <tr>
                            <th>QR Code Tagihan</th>
                            <td>
                                {!! QrCode::size(150)->generate(route('invoices.index', ['customer_id' =>
                                $customer->id])) !!}
                                <small class="d-block mt-1">Scan untuk melihat tagihan</small>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Paket</th>
                            <td>
                                {{ $customer->package->name }}<br>
                                <small>{{ $customer->package->speed_mbps }} Mbps -
                                    {{ $customer->package->formatted_price }}</small>
                            </td>
                        </tr>
                        <tr>
                            <th>Tanggal Daftar</th>
                            <td>{{ $customer->formatted_registration_date }}</td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>{{ $customer->address }}</td>
                        </tr>

                    </table>
                </div>
            </div>

            @if($customer->notes)
            <div class="card mt-3">
                <div class="card-header bg-light">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-sticky-note mr-2"></i>
                        Catatan
                    </h4>
                </div>
                <div class="card-body">
                    {{ $customer->notes }}
                </div>
            </div>
            @endif

            <div class="mt-4">
                <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>
                <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>

        </div>
    </div>
</div>
@endsection