@extends('layouts.app')

@section('title', 'Tambah Buku')

@section('content')
<a href="{{ route('books.index') }}" class="btn btn-outline-primary mb-3">
    <i class="ti ti-arrow-left"></i>
    Kembali
</a>
<div class="card shadow-lg border-0 animate__animated animate__fadeInUp">
    <div class="card-header bg-primary text-white">Form Tambah Buku</div>
    <div class="card-body">
        <form action="{{ route('books.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Judul</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                @error('title')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="author" class="form-label">Pengarang</label>
                <input type="text" class="form-control" id="author" name="author" value="{{ old('author') }}" required>
                @error('author')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="publisher" class="form-label">Penerbit</label>
                <input type="text" class="form-control" id="publisher" name="publisher" value="{{ old('publisher') }}" required>
                @error('publisher')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="isbn" class="form-label">ISBN</label>
                <input type="text" class="form-control" id="isbn" name="isbn" value="{{ old('isbn') }}" required>
                @error('isbn')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="year" class="form-label">Tahun Terbit</label>
                <input type="number" class="form-control" id="year" name="year" value="{{ old('year') }}" required>
                @error('year')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="cover" class="form-label">Gambar Sampul Buku</label>
                <input type="file" class="form-control" id="cover" name="cover"  accept=".jpeg,.jpg,.png,.gif">
                @error('cover')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="jmlh_tersedia" class="form-label">Jumlah Tersedia</label>
                <input type="number" class="form-control" id="jmlh_tersedia" name="jmlh_tersedia" value="{{ old('jmlh_tersedia') }}" required>
                @error('jmlh_tersedia')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="rack_id" class="form-label">Rak</label>
                <select class="form-select" id="rack_id" name="rack_id" required>
                    <option value="" selected disabled>Pilih rak buku</option>
                    @foreach ($racks as $rack)
                        <option value="{{ $rack->id }}" {{ old('rack_id') == $rack->id ? 'selected' : '' }}>{{ $rack->name }}</option>
                    @endforeach
                </select>
                @error('rack_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="category_id" class="form-label">Kategori</label>
                <select class="form-select" id="category_id" name="category_id" required>
                    <option value="" selected disabled>Pilih kategori buku</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
                @error('description')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-custom btn-primary">Simpan</button>
        </form>
    </div>
</div>

<!-- Custom CSS -->
<style>
    .btn-custom {
        background: linear-gradient(90deg, rgba(58,123,213,1) 0%, rgba(0,212,255,1) 100%);
        border: none;
        color: white;
        font-weight: bold;
    }
    .btn-custom:hover {
        background: linear-gradient(90deg, rgba(0,212,255,1) 0%, rgba(58,123,213,1) 100%);
    }
    .card {
        border-radius: 20px;
    }
    .card-body {
        border-radius: 20px;
        background: #f8f9fa;
    }
    .card-header {
        border-radius: 20px 20px 0 0;
    }
</style>
@endsection
