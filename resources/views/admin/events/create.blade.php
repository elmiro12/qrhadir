@extends('layouts.app')
@section('title', 'Tambah Event')

@section('content')
<form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @include('admin.events._form')
    <div class="mt-6">
        <x-button variant="primary" type="submit">Simpan</x-button>
    </div>
</form>
@endsection
