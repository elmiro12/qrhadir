@extends('layouts.app')
@section('title', 'Edit Event')

@section('content')
<form action="{{ route('admin.events.update', $event) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')
    @include('admin.events._form')
    <div class="mt-6">
        <x-button variant="primary" type="submit">Update</x-button>
    </div>
</form>
@endsection
