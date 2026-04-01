@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-6">{{ isset($article) ? 'Edit Article' : 'New Article' }}</h1>
<form action="{{ isset($article) ? route('admin.articles.update', $article) : route('admin.articles.store') }}"
    method="POST" enctype="multipart/form-data" class="max-w-3xl space-y-6">
    @csrf
    @if(isset($article)) @method('PUT') @endif
    <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Title</label>
        <input type="text" name="title" value="{{ old('title', $article->title ?? '') }}" required
            class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Excerpt</label>
        <textarea name="excerpt" rows="2"
            class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none resize-none">{{ old('excerpt', $article->excerpt ?? '') }}</textarea>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Content</label>
        <textarea name="content" id="article-content" rows="20"
            class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none resize-none">{{ old('content', $article->content ?? '') }}</textarea>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Hero Image</label>
            @if(isset($article) && $article->hero_image)
            <div class="mb-2">
                <img src="{{ asset('storage/' . $article->hero_image) }}" alt="Current hero" class="h-24 rounded-lg border border-white/10 object-cover">
            </div>
            @endif
            <input type="file" name="hero_image" accept="image/*"
                class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none file:bg-purple-600 file:text-white file:border-0 file:rounded-lg file:px-4 file:py-1 file:mr-4">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-red-400" fill="currentColor" viewBox="0 0 24 24"><path d="M21.543 6.498C22 8.28 22 12 22 12s0 3.72-.457 5.502c-.254.985-.997 1.76-1.938 2.022C17.896 20 12 20 12 20s-5.893 0-7.605-.476c-.945-.266-1.687-1.04-1.938-2.022C2 15.72 2 12 2 12s0-3.72.457-5.502c.254-.985.997-1.76 1.938-2.022C6.107 4 12 4 12 4s5.896 0 7.605.476c.945.266 1.687 1.04 1.938 2.022zM10 15.5l6-3.5-6-3.5v7z"/></svg>
                    YouTube Video URL
                </span>
            </label>
            <input type="url" name="youtube_url" value="{{ old('youtube_url', $article->youtube_url ?? '') }}"
                placeholder="https://www.youtube.com/watch?v=..."
                class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none">
            <p class="text-xs text-gray-500 mt-1">Paste a YouTube video URL to embed in the article</p>
        </div>
    </div>
    <div>
        <label class="flex items-center gap-3">
            <input type="checkbox" name="is_published" value="1" {{ old('is_published', $article->is_published ?? false)
            ? 'checked' : '' }} class="w-5 h-5 rounded bg-white/5 border-white/10 text-purple-600
            focus:ring-purple-500">
            <span class="text-sm text-gray-300">Published</span>
        </label>
    </div>
    <div class="flex gap-3">
        <button type="submit"
            class="bg-purple-600 hover:bg-purple-500 text-white font-medium px-6 py-3 rounded-xl transition-all">{{
            isset($article) ? 'Update' : 'Create' }} Article</button>
        <a href="{{ route('admin.articles.index') }}"
            class="bg-white/5 hover:bg-white/10 text-gray-300 font-medium px-6 py-3 rounded-xl transition-all">Cancel</a>
    </div>
</form>

<script src="https://cdn.tiny.cloud/1/2ybotr2gj2jba7rs525xlvymht3kg2qv4833vglziifs7kj8/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    tinymce.init({
        selector: '#article-content',
        skin: 'oxide-dark',
        content_css: 'dark',
        menubar: false,
        plugins: 'lists link image code table media hr autolink',
        toolbar: 'undo redo | styles | bold italic underline strikethrough | alignleft aligncenter alignright | bullist numlist | link image media hr | table | code',
        height: 500,
        branding: false,
        content_style: 'body { font-family: Inter, sans-serif; font-size: 14px; color: #e5e7eb; background: #111827; } img { max-width: 100%; height: auto; } video { max-width: 100%; height: auto; }',
        images_upload_url: '{{ route("admin.articles.upload-image") }}',
        images_upload_credentials: true,
        automatic_uploads: true,
        paste_data_images: true,
        file_picker_types: 'image media',
        media_live_embeds: true,
        convert_urls: false,
        setup: function(editor) {
            editor.on('init', function() {
                var token = document.querySelector('meta[name="csrf-token"]');
                if (token) {
                    editor.getDoc().cookie = 'XSRF-TOKEN=' + token.content;
                }
            });
        },
        images_upload_handler: function(blobInfo, progress) {
            return new Promise(function(resolve, reject) {
                var formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());
                formData.append('_token', document.querySelector('input[name="_token"]').value);

                fetch('{{ route("admin.articles.upload-image") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json',
                    }
                })
                .then(function(response) {
                    if (!response.ok) {
                        throw new Error('Upload failed: ' + response.status);
                    }
                    return response.json();
                })
                .then(function(result) {
                    if (result.location) {
                        resolve(result.location);
                    } else {
                        reject('No image URL returned');
                    }
                })
                .catch(function(err) {
                    reject('Image upload failed: ' + err.message);
                });
            });
        },
    });

    // Ensure TinyMCE content syncs to textarea before form submit
    var form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        if (tinymce.get('article-content')) {
            tinymce.get('article-content').save();
        }
        tinymce.triggerSave();
        form.submit();
    });
});
</script>
@endsection