@extends('layouts.app')

@section('title', 'Edit Post')

@section('content')
    <h1>Edit Post</h1>

    <!-- Display validate errors if any -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form to edit the post -->
    <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Title Input -->
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ $post->title }}" required>
        </div>

        <!-- Content Textarea -->
        <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea class="form-control" name="content" id="content" rows="4" required>{{ $post->content }}</textarea>
        </div>

        <!-- File Input for new images -->
        <div class="mb-3">
            <label for="images" class="form-label">Images</label>
            <input type="file" class="form-control" name="images[]" id="images" multiple>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Update Post</button>
    </form>

    <!-- Display Existing Images -->
    @if($post->images->count() > 0)
        <h3>Existing Images</h3>
        <div class="row">
            @foreach($post->images as $image)
                <div class="col-md-3">
                    <img src="{{ asset($image->image) }}" class="img-thumbnail" alt="Image">
                </div>
            @endforeach
        </div>
    @endif
@endsection