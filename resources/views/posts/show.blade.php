<!-- resources/views/posts/show.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="flex justify-center">

        <div class="w-full max-w-4xl bg-white p-8 mt-8 rounded-lg shadow-lg">
            <h1 class="text-4xl font-semibold mb-6">{{ $post->title }}</h1>
            <p class="text-gray-600 text-lg mb-8">{{ $post->created_at->format('F d, Y') }}</p>
            <p class="text-gray-800 text-xl leading-relaxed mb-10">{{ $post->body }}</p>

            @if ($post->image_path)
                <div class="mb-10">
                    <img src="{{ asset('images/' . $post->image_path) }}" alt="{{ $post->title }}"
                        class="w-full max-h-96 object-cover rounded-lg shadow-md">
                </div>
            @endif

            @if ($post->pdf_path)
                <div class="mb-6">
                    <p class="text-blue-500 hover:underline text-xl">
                        <a href="{{ asset('pdfs/' . $post->pdf_path) }}" target="_blank">View PDF</a>
                    </p>
                </div>
            @endif

            <a href="{{ route('posts.index') }}" class="text-blue-500 hover:underline text-xl">Back to All Posts</a>
        </div>
    </div>
@endsection
