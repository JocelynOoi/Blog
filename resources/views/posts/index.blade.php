@extends('layouts.app')

@section('title', 'All Posts')

@section('content')
    <h1>All Blog Posts</h1>

    <a href="{{ route('posts.create') }}" class="btn btn-primary mb-3">Create New Post</a>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @foreach ($posts as $post)
        <div class="card mb-4">
            <div class="card-body">
                <h3>{{ $post->title }}</h3>
                <p>{{ $post->content }}</p>

                @if($post->image->count() > 0)
                    <div class="row">
                        @foreach($post->image as $image)
                            <div class="col-md-3">
                                <img src="{{ asset($image->image) }}" class="img-thumbnail" alt="Image">
                            </div>
                        @endforeach
                    </div>
                @endif

                <a href="{{ route('posts.show', $post->id) }}" class="btn btn-info mt-3">View</a>
                <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-warning mt-3">Edit</a>

                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger mt-3">Delete</button>
                </form>
            </div>
        </div>
    @endforeach

    <!-- Pagination -->
    <div class="d-flex justify-content-center">

    </div>
@endsection