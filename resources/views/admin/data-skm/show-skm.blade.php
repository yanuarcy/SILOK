@extends('Template.template')

@section('title', 'Detail Data SKM')

@section('Content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Detail Survey Kepuasan Masyarakat</h3>
                    <a href="{{ route('admin.Data-SKM.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Nama</strong></td>
                                    <td>:</td>
                                    <td>{{ $dataSkm->nama }}</td>
                                </tr>
                                <tr>
                                    <td><strong>NIK</strong></td>
                                    <td>:</td>
                                    <td>{{ $dataSkm->nik }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Alamat</strong></td>
                                    <td>:</td>
                                    <td>{{ $dataSkm->alamat }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tingkat Kepuasan</strong></td>
                                    <td>:</td>
                                    <td>
                                        @if($dataSkm->tingkat_kepuasan == 'Sangat Puas')
                                            <span class="badge bg-success fs-6">{{ $dataSkm->tingkat_kepuasan }}</span>
                                        @elseif($dataSkm->tingkat_kepuasan == 'Puas')
                                            <span class="badge bg-primary fs-6">{{ $dataSkm->tingkat_kepuasan }}</span>
                                        @else
                                            <span class="badge bg-warning fs-6">{{ $dataSkm->tingkat_kepuasan }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Status</strong></td>
                                    <td>:</td>
                                    <td>
                                        @if($dataSkm->status == 'active')
                                            <span class="badge bg-success fs-6">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary fs-6">Tidak Aktif</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Submit</strong></td>
                                    <td>:</td>
                                    <td> WIB</td>
                                </tr>
                                <tr>
                                    <td><strong>User</strong></td>
                                    <td>:</td>
                                    <td>{{ $dataSkm->user->name ?? 'User tidak ditemukan' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Kritik dan Saran:</h5>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <p class="mb-0">{{ $dataSkm->kritik_saran }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="btn-group" role="group">
                                <form action="{{ route('admin.data-skm.toggle-status', $dataSkm->id) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="btn btn-warning"
                                            onclick="return confirm('Yakin ingin mengubah status?')">
                                        <i class="fas fa-toggle-on"></i> Toggle Status
                                    </button>
                                </form>
                                <form action="{{ route('admin.data-skm.destroy', $dataSkm->id) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-danger"
                                            onclick="return confirm('Yakin ingin menghapus data ini?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
