@extends('layouts.app')
@section('title', 'Data Denda')

@section('content')
    <div class="pb-2">
        @if (session('msg'))
            <div class="alert {{ session('error') ? 'alert-danger' : 'alert-success' }} alert-dismissible fade show" role="alert">
                {{ session('msg') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-12 col-lg-5">
                    <h5 class="card-title fw-semibold mb-4">Data Denda</h5>
                </div>
                <div class="col-12 col-lg-7">
                    <div class="d-flex gap-2 justify-content-md-end">
                        <!-- Tambahkan elemen tambahan jika diperlukan -->
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table datatable table-hover table-striped">
                        <thead class="custom-thead">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Resi Peminjaman</th>
                                <th scope="col">Nama Member</th>
                                <th scope="col">Tanggal Pinjam</th>
                                <th scope="col">Telat (hari)</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($peminjamans as $key => $peminjaman)
                                @php
                                    $createdAt = \Carbon\Carbon::parse($peminjaman->created_at);
                                    $returnDate = \Carbon\Carbon::parse($peminjaman->return_date);
                                    $telatHari = $returnDate->diffInDays($createdAt) - 7;
                                    $totalDenda = $telatHari * 5000;

                                    // Periksa apakah ada data denda yang sudah dibayar
                                    if ($peminjaman->denda) {
                                        $status = $peminjaman->denda->denda_yg_dibyr >= $peminjaman->denda->uang_yg_dibyrkn ? 'lunas' : 'belum lunas';
                                    } else {
                                        $status = $peminjaman->status === 'lunas' ? 'lunas' : 'belum lunas';
                                    }
                                @endphp

                                @if ($status !== 'lunas')
                                    <tr>
                                        <th scope="row">{{ $key + 1 }}</th>
                                        <td>{{ $peminjaman->resi_pjmn }}</td>
                                        <td>{{ $peminjaman->member->first_name ?? 'Unknown' }} {{ $peminjaman->member->last_name ?? '' }}</td>
                                        <td>{{ $createdAt->format('Y-m-d') }}</td>
                                        <td>{{ $telatHari }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-success mt-1 w-40" data-bs-toggle="modal" data-bs-target="#payModal{{ $peminjaman->id }}">
                                                <i class="ti ti-credit-card"></i> Bayar
                                            </button>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Placeholder Paginasi -->
            {{ $peminjamans->links() }}
        </div>
    </div>

    @foreach ($peminjamans as $peminjaman)
        <!-- Modal -->
        <div class="modal fade" id="payModal{{ $peminjaman->id }}" tabindex="-1" aria-labelledby="payModalLabel{{ $peminjaman->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="payModalLabel{{ $peminjaman->id }}">Bayar Denda</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('denda.bayar') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="id_pjmn" value="{{ $peminjaman->id }}">
                            <p>Nama Member: {{ $peminjaman->member->first_name ?? 'Unknown' }} {{ $peminjaman->member->last_name ?? '' }}</p>
                            <p>Jumlah Telat: {{ $telatHari }} hari</p>
                            <p>Total Denda: Rp {{ $totalDenda }}</p>

                            <div class="mb-3">
                                <label for="uang_dibayarkan" class="form-label">Uang yang Dibayarkan</label>
                                <input type="number" class="form-control" id="uang_dibayarkan_{{ $peminjaman->id }}" name="uang_yg_dibyrkn" required min="{{ $totalDenda }}" max="{{ $totalDenda }}">
                                <div class="invalid-feedback">
                                    Uang yang dibayarkan harus tepat sejumlah Rp {{ $totalDenda }}.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <input type="text" class="form-control" id="status_{{ $peminjaman->id }}" value="{{ $status }}" readonly>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-success" id="payButton_{{ $peminjaman->id }}">Bayar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var inputUangDibayarkan = document.getElementById("uang_dibayarkan_{{ $peminjaman->id }}");

                inputUangDibayarkan.addEventListener("input", function() {
                    var uangDibayarkan = parseFloat(inputUangDibayarkan.value);
                    var status = ({{ $totalDenda }} == uangDibayarkan) ? 'lunas' : 'belum lunas';
                    document.getElementById("status_{{ $peminjaman->id }}").value = status;

                    if (uangDibayarkan !== {{ $totalDenda }}) {
                        inputUangDibayarkan.classList.add('is-invalid');
                    } else {
                        inputUangDibayarkan.classList.remove('is-invalid');
                    }
                });
            });
        </script>
    @endforeach
@endsection
