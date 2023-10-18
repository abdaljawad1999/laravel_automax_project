@extends('layouts.app')

@section('content')
    <div class="flex justify-center">
        <div class="w-8/12 bg-white p-6 rounded-lg">
            <form action="{{ route('posts.store') }}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="mb-6">
                    <label for="title" class="block text-gray-600 text-sm font-medium mb-2">Title</label>
                    <input required type="text" name="title" id="title"
                        class="w-full px-3 py-2 border rounded-lg bg-gray-100 focus:outline-none focus:border-blue-500 @error('title') border-red-500 @enderror"
                        placeholder="Title">
                    @error('title')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="body" class="block text-gray-600 text-sm font-medium mb-2">Body</label>
                    <textarea required name="body" id="body" rows="4"
                        class="w-full px-3 py-2 border rounded-lg bg-gray-100 focus:outline-none focus:border-blue-500 @error('body') border-red-500 @enderror"
                        placeholder="Post Something!"></textarea>
                    @error('body')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-gray-600 text-sm font-medium mb-2">Upload Image (Max. 800x400px)</label>
                    <!-- Label for the image input -->
                    <label for="image"
                        class="w-32 h-32 flex flex-col items-center justify-center bg-gray-100 border border-dashed rounded-lg cursor-pointer hover:bg-gray-200">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 21a9 9 0 01-9-9V7a3 3 0 013-3h12a3 3 0 013 3v5a9 9 0 01-9 9z" />
                        </svg>
                        <span class="mt-2 text-gray-600 text-xs">Select Image</span>
                    </label>
                    <!-- Actual input field, visually hidden -->
                    <input required name="image" type="file" id="image" class="hidden"
                        onchange="displaySelectedImage(this)" />
                    <img src="" id="image-preview"
                        class="w-32 h-32 rounded-lg object-cover border border-gray-300 @error('image') border-red-500 @enderror"
                        alt="Image Preview" style="display: none;">
                    @error('image')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-gray-600 text-sm font-medium mb-2">Upload PDF (Max. 5MB)</label>
                    <!-- Label for the PDF input -->
                    <label for="pdf"
                        class="w-32 h-32 flex flex-col items-center justify-center bg-gray-100 border border-dashed rounded-lg cursor-pointer hover:bg-gray-200">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 21a9 9 0 01-9-9V7a3 3 0 013-3h12a3 3 0 013 3v5a9 9 0 01-9 9z" />
                        </svg>
                        <span class="mt-2 text-gray-600 text-xs">Select PDF</span>
                    </label>
                    <!-- Actual input field, visually hidden -->
                    <input required name="pdf" type="file" id="pdf" class="hidden"
                        onchange="displaySelectedPDF(this)" />
                    <p id="pdf-name" class="text-gray-600 text-sm" style="display: none;">Selected PDF: None</p>
                    <a href="#" id="pdf-link" class="text-blue-500 text-sm" style="display: none;"
                        target="_blank">Open
                        Selected PDF</a>

                    <!-- Add a div for displaying the PDF preview -->
                    <div id="pdf-preview" style="display: none;">
                        <canvas id="pdf-canvas" width="200" height="200"></canvas>
                    </div>
                </div>

                <div class="mb-6">
                    <button type="submit"
                        class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-300 ease-in-out">Post</button>
                </div>
            </form>

        </div>
    </div>

    <script>
        function displaySelectedImage(input) {
            const selectedImage = document.getElementById('image-preview');
            const uploadContainer = document.getElementById('image-upload-container');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    selectedImage.src = e.target.result;
                    selectedImage.style.display = 'block';
                    uploadContainer.style.display = 'none'; // Hide the upload container
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        function displaySelectedPDF(input) {
            const selectedFile = input.files[0];
            const pdfName = document.getElementById('pdf-name');
            const pdfLink = document.getElementById('pdf-link');

            if (selectedFile) {
                pdfName.textContent = `Selected PDF: ${selectedFile.name}`;
                pdfName.style.display = 'block';
                pdfLink.href = URL.createObjectURL(selectedFile);
                pdfLink.style.display = 'block';
            } else {
                pdfName.textContent = 'Selected PDF: None';
                pdfName.style.display = 'none';
                pdfLink.href = '#';
                pdfLink.style.display = 'none';
            }
        }
    </script>
@endsection
