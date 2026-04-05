<head>
    <meta property="og:title" content="{{ $post->title }}">
    <meta property="og:description" content="{{ Str::limit($post->excerpt, 150) }}">
    <meta property="og:image" content="{{ $post->featured_image_url }}">
    <meta property="og:url" content="{{ route('posts.share', $post->id) }}">
    
    <script>
        // If the user is on mobile, try to open the Flutter app automatically
        window.location.href = "blognode://post/{{ $post->id }}";
        
        // Fallback: If they don't have the app, stay on web or show a "Download" button
    </script>
</head>
<body>
    <h1>{{ $post->title }}</h1>
    <p>Opening in BlogNode...</p>
</body>