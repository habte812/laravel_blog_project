<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlogNode | Elevate Your Voice</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap');
        
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        .blob {
            position: absolute;
            width: 500px;
            height: 500px;
            background: linear-gradient(180deg, rgba(99, 102, 241, 0.2) 0%, rgba(168, 85, 247, 0.2) 100%);
            filter: blur(80px);
            border-radius: 50%;
            z-index: -1;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .hero-gradient {
            background: radial-gradient(circle at top right, #fdfcfb 0%, #e2d1c3 100%);
        }

        @keyframes floating {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(2deg); }
            100% { transform: translateY(0px) rotate(0deg); }
        }
        .phone-mockup { animation: floating 5s ease-in-out infinite; }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-900 antialiased overflow-x-hidden">

    <div class="blob top-[-10%] left-[-10%]"></div>
    <div class="blob bottom-[10%] right-[-5%] bg-indigo-200"></div>

    <nav class="sticky top-0 z-50 px-6 py-4">
        <div class="max-w-7xl mx-auto flex items-center justify-between glass-card rounded-2xl px-6 py-3 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="bg-gradient-to-br from-indigo-600 to-violet-600 p-2 rounded-xl text-white shadow-lg">
                    <i class="fas fa-feather-pointed text-xl"></i>
                </div>
                <span class="text-2xl font-black bg-clip-text text-transparent bg-gradient-to-r from-slate-900 to-slate-600 tracking-tight">BlogNode</span>
            </div>
            <a href="#download" class="hidden sm:block bg-slate-900 text-white px-6 py-2.5 rounded-xl font-semibold hover:bg-indigo-600 transition-all duration-300 shadow-md">
                Download Now
            </a>
        </div>
    </nav>

    <section class="max-w-7xl mx-auto px-6 pt-16 lg:pt-24 pb-32">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            
            <div class="text-left space-y-8">
                <div class="inline-flex items-center gap-2 bg-indigo-50 border border-indigo-100 text-indigo-700 px-4 py-1.5 rounded-full text-sm font-bold">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-600"></span>
                    </span>
                    Join 2,000+ creators
                </div>

                <h1 class="text-6xl lg:text-8xl font-extrabold tracking-tighter leading-[1.1]">
                    Where ideas <br/>
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-violet-500">take root.</span>
                </h1>

                <p class="text-xl text-slate-500 max-w-lg leading-relaxed">
                    BlogNode is the premier cross-platform destination for readers and writers. Experience a world of content with zero distractions.
                </p>

                <div class="flex flex-col sm:flex-row gap-5 pt-4" id="download">
                    <a href="#" class="group relative flex items-center gap-4 bg-slate-900 text-white p-1 pr-8 rounded-2xl hover:bg-slate-800 transition-all shadow-2xl overflow-hidden">
                        <div class="bg-white/10 p-4 rounded-xl group-hover:bg-indigo-500 transition-colors">
                            <i class="fab fa-apple text-3xl"></i>
                        </div>
                        <div>
                            <p class="text-[11px] uppercase opacity-70 font-bold">Available on</p>
                            <p class="text-xl font-bold leading-tight">App Store</p>
                        </div>
                    </a>

                    <a href="#" class="group relative flex items-center gap-4 bg-white border border-slate-200 p-1 pr-8 rounded-2xl hover:border-indigo-500 transition-all shadow-lg overflow-hidden">
                        <div class="bg-slate-50 p-4 rounded-xl group-hover:bg-indigo-50 transition-colors">
                            <i class="fab fa-google-play text-2xl text-indigo-600"></i>
                        </div>
                        <div>
                            <p class="text-[11px] uppercase text-slate-400 font-bold">Get it on</p>
                            <p class="text-xl font-bold text-slate-800 leading-tight">Play Store</p>
                        </div>
                    </a>
                </div>
            </div>

            <div class="relative flex justify-center perspective-1000">
                <div class="phone-mockup relative w-[300px] h-[600px] bg-slate-900 rounded-[3.5rem] border-[12px] border-slate-800 shadow-[0_50px_100px_-20px_rgba(0,0,0,0.3)] p-4">
                    <div class="bg-white h-full w-full rounded-[2.5rem] overflow-hidden">
                        <div class="h-1/3 bg-gradient-to-br from-indigo-600 to-violet-600 p-6 text-white">
                            <div class="flex justify-between items-center mb-6">
                                <div class="w-10 h-10 rounded-full bg-white/20"></div>
                                <i class="fas fa-bell text-white/80"></i>
                            </div>
                            <div class="space-y-2">
                                <div class="h-2 w-24 bg-white/30 rounded"></div>
                                <div class="h-5 w-40 bg-white rounded"></div>
                            </div>
                        </div>
                        <div class="p-4 space-y-6">
                            <div class="space-y-3">
                                <div class="h-32 bg-slate-100 rounded-2xl"></div>
                                <div class="h-3 w-full bg-slate-100 rounded"></div>
                                <div class="h-3 w-2/3 bg-slate-100 rounded"></div>
                            </div>
                            <div class="flex gap-4">
                                <div class="h-24 w-1/2 bg-slate-50 rounded-xl"></div>
                                <div class="h-24 w-1/2 bg-slate-50 rounded-xl"></div>
                            </div>
                        </div>
                    </div>
                    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-32 h-7 bg-slate-800 rounded-b-3xl"></div>
                </div>
                
                <div class="absolute top-20 right-0 glass-card p-4 rounded-2xl shadow-xl animate-bounce">
                    <div class="flex items-center gap-3">
                        <div class="bg-green-500 w-2 h-2 rounded-full"></div>
                        <p class="text-sm font-bold text-slate-700">Live Reading Now</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="relative py-24 bg-slate-900 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-12">
                <div class="space-y-2">
                    <p class="text-indigo-400 font-bold uppercase tracking-widest text-xs">Total Stories</p>
                    <h4 class="text-5xl font-extrabold text-white tracking-tighter">{{ number_format($stats['posts'] ?? 2450) }}</h4>
                </div>
                <div class="space-y-2">
                    <p class="text-indigo-400 font-bold uppercase tracking-widest text-xs">Active Readers</p>
                    <h4 class="text-5xl font-extrabold text-white tracking-tighter">{{ number_format($stats['users'] ?? 1820) }}</h4>
                </div>
                <div class="space-y-2">
                    <p class="text-indigo-400 font-bold uppercase tracking-widest text-xs">Countries</p>
                    <h4 class="text-5xl font-extrabold text-white tracking-tighter">12+</h4>
                </div>
                <div class="space-y-2">
                    <p class="text-indigo-400 font-bold uppercase tracking-widest text-xs">App Rating</p>
                    <h4 class="text-5xl font-extrabold text-white tracking-tighter">4.9/5</h4>
                </div>
            </div>
        </div>
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(#4f46e5_1px,transparent_1px)] [background-size:40px_40px]"></div>
        </div>
    </section>

    <footer class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6 text-center space-y-8">
            <div class="flex items-center justify-center gap-3 opacity-50">
                <div class="bg-slate-900 w-8 h-8 rounded-lg flex items-center justify-center text-white">
                    <i class="fas fa-feather-pointed text-xs"></i>
                </div>
                <span class="text-lg font-bold">BlogNode</span>
            </div>
            <div class="h-px w-20 bg-slate-100 mx-auto"></div>
            <p class="text-slate-400 text-xs uppercase tracking-widest font-bold">© {{ date('Y') }} BlogNode. made with love.</p>
        </div>
    </footer>

</body>
</html>