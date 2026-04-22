<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password | BlogNode</title>
    
    <style>
        body {
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #0f172a;
            font-family: 'Inter', -apple-system, sans-serif;
            color: white;
        }
        .container {
            text-align: center;
            padding: 2rem;
            background: #1e293b;
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            max-width: 400px;
            width: 90%;
        }
        .icon-circle {
            width: 80px;
            height: 80px;
            background: #3b82f6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }
        h1 { font-size: 1.5rem; margin-bottom: 0.5rem; }
        p { color: #94a3b8; line-height: 1.6; }
        .btn {
            display: inline-block;
            margin-top: 1.5rem;
            padding: 14px 28px;
            background-color: #3b82f6;
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
        }
        .loader {
            width: 20px;
            height: 20px;
            border: 3px solid #334155;
            border-top: 3px solid #3b82f6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            display: block;
            margin: 1rem auto;
        }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>

    <script>
        window.onload = function() {
            const appLink = "blognode://reset-password?token={{ $token }}";
            window.location.href = appLink;

            setTimeout(() => {
                document.getElementById('status').innerText = "Opening the app...";
                document.getElementById('fallback').style.display = "block";
            }, 2500);
        };
    </script>
</head>
<body>
    <div class="container">
        <div class="icon-circle">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-check"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.5 3.8 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg>
        </div>
        <h1>Security Check</h1>
        <p id="status">We are taking you to the <b>BlogNode</b> app to safely reset your password.</p>
        
        <div class="loader"></div>

        <div id="fallback" style="display: none; border-top: 1px solid #334155; margin-top: 20px; padding-top: 20px;">
            <p style="font-size: 0.85rem;">Didn't redirect? Check if you have the latest app version installed.</p>
            <a href="https://play.google.com/store/apps/details?id=com.blognode.app" class="btn">Get the App</a>
        </div>
    </div>
</body>
</html>