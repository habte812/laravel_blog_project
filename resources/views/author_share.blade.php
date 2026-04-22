<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>{{ $user->name }} on BlogNode</title>
    <meta property="og:title" content="{{ $user->name }}">
    <meta property="og:description" content="Check out {{ $user->name }}'s profile on BlogNode. {{ $user->blog_posts_count }} posts and {{ $user->followers_count }} followers.">
    <meta property="og:image" content="{{ $profile_image }}">
    <meta property="og:type" content="profile">

    <style>
        body { margin: 0; display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #0f172a; font-family: sans-serif; color: white; }
        .profile-card { background: #1e293b; padding: 2.5rem; border-radius: 30px; text-align: center; width: 90%; max-width: 400px; border: 1px solid #334155; }
        .avatar { width: 120px; height: 120px; border-radius: 50%; border: 4px solid #3b82f6; object-fit: cover; margin-bottom: 1rem; }
        .name { font-size: 1.5rem; margin: 10px 0; }
        .bio { color: #94a3b8; font-size: 0.9rem; margin-bottom: 1.5rem; }
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-bottom: 2rem; }
        .stat-item b { display: block; font-size: 1.2rem; color: #3b82f6; }
        .stat-item span { font-size: 0.7rem; color: #64748b; text-transform: uppercase; }
        .btn { background: #3b82f6; color: white; padding: 14px 28px; text-decoration: none; border-radius: 12px; font-weight: bold; display: block; }
    </style>

    <script>
        window.onload = function() {
            // ✅ DEEP LINK: This triggers your GoRouter '/author-profile/:id'
            // We don't pass 'isfollowing' here because the app will fetch the fresh status anyway
            window.location.href = "blognode://author/{{ $user->id }}";
            
            setTimeout(() => {
                document.getElementById('download-hint').style.display = "block";
            }, 3000);
        };
    </script>
</head>
<body>
    <div class="profile-card">
        <img src="{{ $profile_image }}" class="avatar">
        <h1 class="name">{{ $user->name }}</h1>
        <p class="bio">{{ $user->bio ?? '' }}</p>
        
        <div class="stats-grid">
            <div class="stat-item"><b>{{ $user->followings_count }}</b><span>Following</span></div>
            <div class="stat-item"><b>{{ $user->followers_count }}</b><span>Followers</span></div>
            <div class="stat-item"><b>{{ $user->blog_posts_count }}</b><span>Posts</span></div>
        </div>

        <a href="https://play.google.com/store/apps/details?id=com.blognode.app" class="btn">View Full Profile</a>
        <p id="download-hint" style="display:none; font-size: 0.8rem; color: #64748b; margin-top: 15px;">Opening in BlogNode app...</p>
    </div>
</body>
</html>