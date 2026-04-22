<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post->title }} | BlogNode</title>

    <meta property="og:title" content="{{ $post->title }}">
    <meta property="og:description" content="{{ Str::limit($post->excerpt, 150) }}">
    <meta property="og:image" content="{{ $share_image }}">

    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #0f172a;
            font-family: -apple-system, sans-serif;
            color: white;
        }

        .card {
            background: #1e293b;
            padding: 2rem;
            border-radius: 24px;
            max-width: 650px;
            width: 90%;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
        }

        .thumbnail-container {
            width: 100%;
            border-radius: 16px;
            overflow: hidden;
            margin-bottom: 1.5rem;
            border: 1px solid #334155;
        }

        .thumbnail-image {
            width: 100%;
            display: block;
            object-fit: cover;
        }

       
        .content-layout {
            display: flex;
            gap: 20px;
            text-align: left;
            align-items: flex-start;
        }

        .main-text {
            flex: 2;
        }

        .author-sidebar {
            flex: 1;
            background: #334155;
            padding: 15px;
            border-radius: 16px;
            text-align: center;
            min-width: 140px;
        }

        .author-img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
            border: 2px solid #3b82f6;
        }

        .author-name {
            font-weight: bold;
            font-size: 1rem;
            display: block;
            margin-bottom: 5px;
        }

        .author-bio {
            font-weight: bold;
            font-size: 0.8rem;
            color: #c0bfbfff;
            display: block;
            margin-bottom: 2px;
        }

        .stats {
            font-size: 0.7rem;
            color: #94a3b8;
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
            gap: 5px;
        }

        .stat-box {
            flex: 1;
        }

        .stat-box b {
            color: #3b82f6;
            display: block;
            font-size: 0.9rem;
        }

        h1 {
            font-size: 1.4rem;
            margin: 0 0 10px 0;
            color: #f8fafc;
        }

        .excerpt {
            color: #94a3b8;
            font-size: 0.9rem;
            margin-bottom: 20px;
        }

        .footer-action {
            text-align: center;
            margin-top: 20px;
            border-top: 1px solid #334155;
            padding-top: 20px;
        }

        .btn {
            background-color: #3b82f6;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 10px;
            font-weight: bold;
            display: inline-block;
        }

        @media (max-width: 600px) {
            .content-layout {
                flex-direction: column;
            }

            .author-sidebar {
                width: 100%;
                box-sizing: border-box;
            }
        }
        .publish-at{
            color: #94a3b8;
            font-size: 0.9rem;
            margin-bottom: 20px;

        }
        .loader {
            border: 2px solid #1e293b;
            border-top: 2px solid #3b82f6;
            border-radius: 50%;
            width: 14px;
            height: 14px;
            animation: spin 1s linear infinite;
            display: inline-block;
            margin-right: 5px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="thumbnail-container">
            <img src="{{ $share_image }}" class="thumbnail-image">
        </div>

        <div class="content-layout">
            <div class="main-text">
                 <p class="publish-at">{{'Published ' .$post->time_ago }}</p>
                <h1>{{ $post->title }}</h1>
                <p class="excerpt">{{ $post->excerpt }}</p>
            </div>               
            <div class="author-sidebar">
                <img src="{{ $post->author->profile_picture_url ? $post->author->profile_picture_url : 'https://ui-avatars.com/api/?name='.urlencode($post->author->name).'&background=3b82f6&color=fff' }}"
                    class="author-img">
                <span class="author-name">{{ $post->author->name }}</span>
                <span class="author-bio">{{ $post->author->bio??'' }}</span>
                <div class="stats">

                    <div class="stat-box">
                        <b>{{ $post->author->followings_count ?? 0 }}</b>
                        Following
                    </div>

                    <div class="stat-box">
                        <b>{{ $post->author->followers_count ?? 0 }}</b>
                        Followers
                    </div>

                    <div class="stat-box">
                        <b>{{ $post->author->blog_posts_count ??0 }}</b>
                        Posts
                    </div>

                </div>
            </div>
        </div>

        <div class="footer-action">
            <div id="status-container">
                <div class="loader"></div>
                <span id="status-text" style="color: #94a3b8; font-size: 0.85rem;">Trying to open BlogNode app...</span>
            </div>
            <a id="download-btn" href="https://play.google.com/store/apps/details?id=com.blognode.app" class="btn" style="display: none; margin-top: 10px;">
                Download App to Read More
            </a>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.location.href = "blognode://post/{{ $post->id }}";
            setTimeout(function() {
                document.getElementById('status-text').innerHTML = "App not opening? Use the button below:";
                document.querySelector('.loader').style.display = "none";
                document.getElementById('download-btn').style.display = "inline-block";
            }, 4000);
        };
    </script>
</body>

</html>