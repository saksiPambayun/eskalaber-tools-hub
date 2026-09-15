@extends('layouts.app')

@section('title', 'Update Tool')
@section('page-title', 'Update Tool')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Form Update Tool</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.tools.doUpdate', $tool->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Tool <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                        name="name" value="{{ old('name', $tool->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="code" class="form-label">Kode Tool <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('code') is-invalid @enderror" id="code"
                                        name="code" value="{{ old('code', $tool->code) }}" required>
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description"
                                rows="3">{{ old('description', $tool->description) }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Category <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control @error('category_id') is-invalid @enderror" id="category_id"
                                        name="category_id" required>
                                        <option value="">Pilih Category</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ old('category_id', $tool->category_id) == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="type_id" class="form-label">Type <span class="text-danger">*</span></label>
                                    <select class="form-control @error('type_id') is-invalid @enderror" id="type_id"
                                        name="type_id" required>
                                        <option value="">Pilih Type</option>
                                        @foreach($types as $type)
                                            <option value="{{ $type->id }}" {{ old('type_id', $tool->type_id) == $type->id ? 'selected' : '' }}>
                                                {{ $type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('type_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="place_id" class="form-label">Place <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control @error('place_id') is-invalid @enderror" id="place_id"
                                        name="place_id" required>
                                        <option value="">Pilih Place</option>
                                        @foreach($places as $place)
                                            <option value="{{ $place->id }}" {{ old('place_id', $tool->place_id) == $place->id ? 'selected' : '' }}>
                                                {{ $place->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('place_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="stock" class="form-label">Stock <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('stock') is-invalid @enderror"
                                        id="stock" name="stock" value="{{ old('stock', $tool->stock) }}" min="0" required>
                                    @error('stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="image" class="form-label">Image</label>
                                    @if($tool->image)
                                        <div class="mb-2">
                                            <img src="{{ asset($tool->image) }}" width="100" style="border-radius: 5px;">
                                        </div>
                                    @endif
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image"
                                        name="image" accept="image/*">
                                    <small class="text-muted">Max 2MB (JPG, PNG, JPEG)</small>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Update
                            </button>
                            <a href="{{ route('admin.tools.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection