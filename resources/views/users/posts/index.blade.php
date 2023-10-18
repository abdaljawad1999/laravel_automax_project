@extends('layouts.app')

@section('content')
    <div class="flex justify-center">
        <div class="w-9/12 bg-white p-6 rounded-lg">
            <div class="p-6">
                <h1 class="text-2xl font-medium mb-1">
                    {{ $user->name }}
                </h1>
                <p>Posted {{ $posts->count() }} {{ Str::plural('post', $posts->count()) }}</p>
                <p>{{ $user->recievedLikes->count() }} {{ Str::plural('like', $user->recievedLikes->count()) }}</p>

            </div>
            @if ($posts->count())
                <ul>
                    @foreach ($posts as $post)
                        <li class="bg-gray-100 hover:bg-gray-200 rounded-lg p-4 mb-4 shadow-md">
                            <div class="flex justify-between items-center">
                                <h2 class="text-xl font-semibold">{{ $post->title }}</h2>
                                <a href="{{ route('users.posts', $post->user) }}" class="font-bold text-gray-600 text-sm">
                                    {{ $post->user->name }}
                                </a>
                            </div>

                            @if ($post->image_path)
                                <div class="mt-4">
                                    <img src="{{ asset('images/' . $post->image_path) }}" alt="{{ $post->title }}"
                                        class="w-1280 h-720 object-cover rounded-lg shadow-md">
                                </div>
                            @endif

                            <p class="text-gray-600 mt-2">{{ $post->body }}</p>
                            <div class="mt-4 flex justify-between items-center">
                                <a href="{{ route('posts.show', $post->id) }}" class="text-blue-500 hover:underline">
                                    Read More
                                </a>

                                <div class="flex items-center space-x-3">
                                    @auth


                                        <!-- Like button -->
                                        @if (!$post->likedBy(auth()->user()))
                                            <form action="{{ route('posts.likes', $post->id) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="text-green-500 hover:text-green-600 focus:outline-none">
                                                    <i class="fas fa-thumbs-up"></i> Like
                                                </button>
                                            </form>
                                        @else
                                            <!-- Dislike button -->
                                            <form action="{{ route('posts.likes', $post->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-500 hover:text-red-600 focus:outline-none">
                                                    <i class="fas fa-thumbs-down"></i> Dislike
                                                </button>
                                            </form>
                                        @endif

                                    @endauth
                                </div>


                                <div class="font-bold text-gray-600 text-sm">
                                    {{ $post->created_at->diffForHumans() }}
                                </div>
                            </div>

                            <div class="mt-4 flex justify-between items-center">
                                <div class="font-bold">{{ $post->likes->count() }}
                                    {{ Str::plural('like', $post->likes->count()) }}</div>
                                @can('delete', $post)
                                    <form action="{{ route('posts.destroy', $post) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-300 ease-in-out">Delete</button>
                                    </form>
                                @endcan



                            </div>







                        </li>
                    @endforeach
                </ul>
                {{ $posts->links() }}
            @else
                <p class="text-gray-600">No posts available.</p>
            @endif
        </div>
    </div>
@endsection
