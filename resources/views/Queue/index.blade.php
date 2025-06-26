@php
    include_once app_path('Helpers/GeneralSettings.php');
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- <link rel="shortcut icon" href="{{ asset('assets/img/favicon.png') }}" type="image/x-icon"> --}}
    @if(getSetting('site_favicon'))
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . getSetting('site_favicon')) }}">
    @endif
    <title>Queue System - {{ getSiteTitle() }}</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Custom Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }

        .header-gradient {
            background: linear-gradient(135deg, #00b4a6 0%, #007991 100%);
        }

        .queue-number-display {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            box-shadow: 0 25px 50px rgba(255, 107, 107, 0.3);
            border-radius: 25px;
            position: relative;
            overflow: hidden;
        }

        .queue-number-display::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, transparent 100%);
            pointer-events: none;
        }

        .current-number {
            font-size: 12rem;
            font-weight: 900;
            text-shadow: 4px 4px 8px rgba(0,0,0,0.3);
            line-height: 1;
        }

        .loket-text {
            font-size: 4rem;
            font-weight: 800;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .antrian-text {
            font-size: 2.5rem;
            font-weight: 600;
            opacity: 0.9;
        }

        @keyframes blink {
            0%, 50%, 100% { opacity: 1; }
            25%, 75% { opacity: 0.4; }
        }

        .blink-animation {
            animation: blink 2s ease-in-out infinite;
        }

        @keyframes pulse-glow {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 25px 50px rgba(255, 107, 107, 0.3);
            }
            50% {
                transform: scale(1.02);
                box-shadow: 0 30px 60px rgba(255, 107, 107, 0.5);
            }
        }

        .pulse-glow {
            animation: pulse-glow 3s ease-in-out infinite;
        }

        .next-queue-item {
            background: linear-gradient(135deg, rgba(255,255,255,0.15), rgba(255,255,255,0.05));
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255,255,255,0.2);
            border-radius: 15px;
            transition: all 0.3s ease;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        }

        .next-queue-item:hover {
            background: linear-gradient(135deg, rgba(255,255,255,0.25), rgba(255,255,255,0.15));
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        }

        .video-container {
            background: rgba(0,0,0,0.3);
            backdrop-filter: blur(10px);
            border-radius: 25px;
            overflow: hidden;
            border: 2px solid rgba(255,255,255,0.1);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }

        .time-display {
            background: linear-gradient(135deg, #4facfe, #00f2fe);
            color: white;
            padding: 20px 30px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 1.25rem;
            box-shadow: 0 15px 30px rgba(79, 172, 254, 0.4);
            border: 2px solid rgba(255,255,255,0.2);
        }

        .logo-container {
            width: 100px;
            height: 100px;
            background: rgba(255,255,255,0.95);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
            border: 3px solid rgba(255,255,255,0.3);
        }

        .header-title {
            font-size: 3rem;
            font-weight: 800;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            letter-spacing: 1px;
        }

        .header-subtitle {
            font-size: 1.25rem;
            font-weight: 500;
            opacity: 0.9;
        }

        .next-queue-title {
            background: linear-gradient(135deg, rgba(255,255,255,0.2), rgba(255,255,255,0.1));
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 20px;
            border: 2px solid rgba(255,255,255,0.2);
            margin-bottom: 25px;
        }

        .queue-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        @media (max-width: 768px) {
            .queue-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .current-number {
                font-size: 8rem;
            }
            .loket-text {
                font-size: 2.5rem;
            }
            .header-title {
                font-size: 2rem;
            }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="min-h-screen">
    <!-- Header Section -->
    <div class="header-gradient p-6 shadow-2xl">
        <div class="container mx-auto flex items-center justify-between">
            <div class="flex items-center space-x-8">
                <div class="logo-container">
                    <img src="{{ getOrganizationLogo() }}" alt="Logo {{ getOrganizationName() }}" class="w-16 h-16 object-contain">
                </div>
                <div class="text-white">
                    <h1 class="header-title">{{ getOrganizationName() }}</h1>
                    <p class="header-subtitle mt-2">{{ getOrganizationAddress() }}</p>
                </div>
            </div>
            <div class="time-display">
                <span id="current-time"></span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-6 py-8">
        <!-- Queue Display Section -->
        <div class="grid lg:grid-cols-5 gap-8 mb-8">
            <!-- Current Queue Number -->
            <div class="lg:col-span-2">
                <div class="queue-number-display pulse-glow p-12 text-center text-white h-full flex flex-col justify-center min-h-[500px]">
                    <p class="antrian-text mb-6">Antrian Sekarang</p>
                    <div class="flex-1 flex flex-col justify-center">
                        <p id="antrian-sekarang" class="current-number blink-animation mb-8">--</p>
                        <p id="loket" class="loket-text">LOKET --</p>
                    </div>
                </div>
            </div>

            <!-- Video Section -->
            <div class="lg:col-span-3">
                <div class="video-container p-6 h-full min-h-[500px]">
                    <div id="video-container" class="w-full h-full rounded-2xl">
                        <!-- Video player akan diinject di sini -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Next Queue Numbers Section -->
        <div class="next-queue-title text-center">
            <h3 class="text-white text-2xl font-bold">Antrian Selanjutnya</h3>
        </div>

        <div class="queue-grid">
            <div class="next-queue-item p-6 text-center text-white">
                <p id="antrian-selanjutnya-1" class="text-3xl font-bold">--</p>
            </div>
            <div class="next-queue-item p-6 text-center text-white">
                <p id="antrian-selanjutnya-2" class="text-3xl font-bold">--</p>
            </div>
            <div class="next-queue-item p-6 text-center text-white">
                <p id="antrian-selanjutnya-3" class="text-3xl font-bold">--</p>
            </div>
            <div class="next-queue-item p-6 text-center text-white">
                <p id="antrian-selanjutnya-4" class="text-3xl font-bold">--</p>
            </div>
            <div class="next-queue-item p-6 text-center text-white">
                <p id="antrian-selanjutnya-5" class="text-3xl font-bold">--</p>
            </div>
            <div class="next-queue-item p-6 text-center text-white">
                <p id="antrian-selanjutnya-6" class="text-3xl font-bold">--</p>
            </div>
            <div class="next-queue-item p-6 text-center text-white">
                <p id="antrian-selanjutnya-7" class="text-3xl font-bold">--</p>
            </div>
            <div class="next-queue-item p-6 text-center text-white">
                <p id="antrian-selanjutnya-8" class="text-3xl font-bold">--</p>
            </div>
        </div>
    </div>

    <!-- Audio Element -->
    <audio id="tingtung" preload="auto">
        <source src="{{ asset('audio/tingtung.mp3') }}" type="audio/mpeg">
        <source src="{{ asset('audio/tingtung.wav') }}" type="audio/wav">
    </audio>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://code.responsivevoice.org/responsivevoice.js?key=jQZ2zcdq"></script>

    <script>
        // ========================================
        // YOUTUBE PLAYER API LOADER
        // ========================================
        var tag = document.createElement('script');
        tag.src = "https://www.youtube.com/iframe_api";
        var firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

        // ========================================
        // GLOBAL VARIABLES
        // ========================================
        let isPlaying = false;
        let currentVideo = '';
        let currentVideoId = null;
        let currentVideoVolume = 50;
        let currentVideoType = 'youtube'; // 'youtube', 'upload', 'url'
        let retryCount = 0;
        let lastProcessTime = 0;
        const maxRetries = 3;
        let youtubePlayer = null;
        let htmlVideoPlayer = null;
        let originalVideoVolume = 100;
        let audioContext = null;
        let playerReady = false;

        // ========================================
        // CONFIGURATION
        // ========================================
        const Config = {
            api: {
                baseUrl: '{{ url("/api") }}',
                endpoints: {
                    next: '/queue/next',
                    complete: '/queue/complete',
                    nextList: '/queue/next-list',
                    stats: '/queue/stats',
                    activeVideo: '/antarmuka/active-video'
                }
            },
            polling: {
                queue: 3000,
                nextList: 4000,
                video: 15000,
                connection: 60000
            },
            audio: {
                enabled: true,
                bellDuration: 2000,
                voiceRate: 0.9,
                voicePitch: 1,
                voiceVolume: 1,
                speechDelay: 1000,
                debugMode: true
            },
            speech: {
                engine: 'responsivevoice',
                fallback: true,
            },
            video: {
                enabled: true,
                muteOnCall: true,
                reducedVolume: 10,
                fadeOutDuration: 500,
                fadeInDuration: 1000,
                pauseOnCall: false
            },
            throttle: {
                processQueue: 2000,
                loadNextQueues: 3000,
                updateVideo: 10000
            }
        };

        // ========================================
        // YOUTUBE PLAYER API FUNCTIONS
        // ========================================

        function onYouTubeIframeAPIReady() {
            console.log('🎥 YouTube Player API loaded');
            // Don't initialize immediately, wait for video source
        }

        function initializeYouTubePlayer(videoId) {
            console.log('🎥 Initializing YouTube Player with video:', videoId);

            // Clear any existing HTML video
            clearHtmlVideoPlayer();

            // Clear the container first
            const container = document.getElementById('video-container');
            if (container) {
                container.innerHTML = '';
            }

            try {
                youtubePlayer = new YT.Player('video-container', {
                    height: '100%',
                    width: '100%',
                    videoId: videoId,
                    playerVars: {
                        'autoplay': 1,
                        'loop': 1,
                        'controls': 0,
                        'modestbranding': 1,
                        'playsinline': 1,
                        'rel': 0,
                        'enablejsapi': 1,
                        'playlist': videoId,
                        'mute': 0
                    },
                    events: {
                        'onReady': onYouTubePlayerReady,
                        'onStateChange': onYouTubePlayerStateChange,
                        'onError': onYouTubePlayerError
                    }
                });

                currentVideoType = 'youtube';

            } catch (error) {
                console.error('❌ Error initializing YouTube Player:', error);
                // Fallback to HTML5 player if YouTube fails
                initializeHtmlVideoPlayer('https://www.youtube.com/embed/' + videoId);
            }
        }

        function onYouTubePlayerReady(event) {
            console.log('✅ YouTube Player ready');
            playerReady = true;

            if (currentVideoVolume !== null) {
                youtubePlayer.setVolume(currentVideoVolume);
                console.log('🔊 YouTube initial volume set to:', currentVideoVolume + '%');
            }

            youtubePlayer.playVideo();
        }

        function onYouTubePlayerStateChange(event) {
            if (event.data === YT.PlayerState.ENDED) {
                youtubePlayer.playVideo();
            }
        }

        function onYouTubePlayerError(event) {
            console.error('❌ YouTube Player error:', event.data);

            switch(event.data) {
                case 2:
                    console.error('Invalid video ID');
                    break;
                case 5:
                    console.error('HTML5 player error');
                    break;
                case 100:
                    console.error('Video not found');
                    break;
                case 101:
                case 150:
                    console.error('Video not allowed to be played in embedded players');
                    break;
            }

            // Fallback to default video on error
            console.log('🔄 Attempting fallback to default video...');
            setTimeout(() => {
                if (youtubePlayer) {
                    youtubePlayer.loadVideoById('tXWuQbGTfxM');
                }
            }, 2000);
        }

        // ========================================
        // HTML5 VIDEO PLAYER FUNCTIONS
        // ========================================

        function clearHtmlVideoPlayer() {
            if (htmlVideoPlayer) {
                htmlVideoPlayer.pause();
                htmlVideoPlayer.src = '';
                htmlVideoPlayer = null;
            }
        }

        function clearYouTubePlayer() {
            if (youtubePlayer) {
                try {
                    youtubePlayer.destroy();
                } catch (error) {
                    console.warn('Error destroying YouTube player:', error);
                }
                youtubePlayer = null;
            }
            playerReady = false;
        }

        function initializeHtmlVideoPlayer(videoUrl) {
            console.log('🎥 Initializing HTML5 Video Player with URL:', videoUrl);

            // Clear any existing YouTube player
            clearYouTubePlayer();
            currentVideoType = 'upload';

            const videoContainer = document.getElementById('video-container');
            if (!videoContainer) {
                console.error('❌ Video container not found');
                return;
            }

            // Create video element with proper error handling
            videoContainer.innerHTML = `
                <video id="html-video-player"
                    class="w-full h-full object-cover rounded-2xl"
                    autoplay
                    loop
                    muted
                    playsinline
                    preload="metadata"
                    style="width: 100%; height: 100%; background: #000;">
                    <source src="${videoUrl}" type="video/mp4">
                    <source src="${videoUrl}" type="video/webm">
                    <source src="${videoUrl}" type="video/ogg">
                    <p class="text-white text-center p-4">Your browser does not support the video tag.</p>
                </video>
            `;

            htmlVideoPlayer = document.getElementById('html-video-player');

            if (htmlVideoPlayer) {
                // Add event listeners
                htmlVideoPlayer.addEventListener('loadstart', function() {
                    console.log('🎥 HTML5 Video: Loading started');
                });

                htmlVideoPlayer.addEventListener('loadedmetadata', function() {
                    console.log('🎥 HTML5 Video: Metadata loaded');
                    console.log('🎥 Video duration:', htmlVideoPlayer.duration);
                });

                htmlVideoPlayer.addEventListener('loadeddata', function() {
                    console.log('✅ HTML5 Video Player ready');
                    playerReady = true;

                    // Set volume
                    if (currentVideoVolume !== null) {
                        htmlVideoPlayer.volume = Math.max(0, Math.min(1, currentVideoVolume / 100));
                        console.log('🔊 HTML5 Video initial volume set to:', currentVideoVolume + '%');
                    }

                    // Try to unmute and play
                    htmlVideoPlayer.muted = false;
                    htmlVideoPlayer.play().catch(e => {
                        console.warn('⚠️ Auto-play prevented, keeping muted');
                        htmlVideoPlayer.muted = true;
                        htmlVideoPlayer.play().catch(err => {
                            console.error('❌ Failed to play video even when muted:', err);
                        });
                    });
                });

                htmlVideoPlayer.addEventListener('error', function(e) {
                    console.error('❌ HTML5 Video Player error:', e);
                    console.error('❌ Error details:', {
                        error: htmlVideoPlayer.error,
                        code: htmlVideoPlayer.error?.code,
                        message: htmlVideoPlayer.error?.message,
                        src: htmlVideoPlayer.src
                    });

                    // Show error in video container
                    videoContainer.innerHTML = `
                        <div class="w-full h-full flex items-center justify-center bg-gray-800 rounded-2xl">
                            <div class="text-center text-white p-8">
                                <i class="fas fa-exclamation-triangle text-4xl mb-4 text-yellow-500"></i>
                                <h3 class="text-xl mb-2">Video Load Error</h3>
                                <p class="text-sm opacity-75">Unable to load video file</p>
                                <p class="text-xs mt-2 opacity-50">${videoUrl}</p>
                            </div>
                        </div>
                    `;
                });

                htmlVideoPlayer.addEventListener('ended', function() {
                    // Manual loop
                    htmlVideoPlayer.currentTime = 0;
                    htmlVideoPlayer.play().catch(e => {
                        console.warn('⚠️ Failed to restart video:', e);
                    });
                });

                htmlVideoPlayer.addEventListener('canplay', function() {
                    console.log('🎥 HTML5 Video: Can start playing');
                });

                htmlVideoPlayer.addEventListener('waiting', function() {
                    console.log('🎥 HTML5 Video: Waiting for more data');
                });

                htmlVideoPlayer.addEventListener('stalled', function() {
                    console.log('🎥 HTML5 Video: Stalled');
                });
            }
        }

        // ========================================
        // UNIVERSAL VIDEO CONTROL FUNCTIONS
        // ========================================

        function setVideoVolume(volume) {
            const newVolume = Math.max(0, Math.min(100, volume));
            currentVideoVolume = newVolume;

            if (currentVideoType === 'youtube' && youtubePlayer && playerReady) {
                try {
                    youtubePlayer.setVolume(newVolume);
                    console.log('🔊 YouTube volume set to:', newVolume + '%');
                } catch (error) {
                    console.error('❌ Error setting YouTube volume:', error);
                }
            } else if (currentVideoType === 'upload' && htmlVideoPlayer) {
                try {
                    htmlVideoPlayer.volume = newVolume / 100;
                    console.log('🔊 HTML5 Video volume set to:', newVolume + '%');
                } catch (error) {
                    console.error('❌ Error setting HTML5 Video volume:', error);
                }
            }
        }

        function muteVideoPlayer() {
            if (currentVideoType === 'youtube' && youtubePlayer && playerReady) {
                try {
                    youtubePlayer.mute();
                    if (Config.video.pauseOnCall) {
                        youtubePlayer.pauseVideo();
                    }
                    console.log('🔇 YouTube Player muted');
                } catch (error) {
                    console.error('❌ Error muting YouTube Player:', error);
                }
            } else if (currentVideoType === 'upload' && htmlVideoPlayer) {
                try {
                    htmlVideoPlayer.muted = true;
                    if (Config.video.pauseOnCall) {
                        htmlVideoPlayer.pause();
                    }
                    console.log('🔇 HTML5 Video Player muted');
                } catch (error) {
                    console.error('❌ Error muting HTML5 Video Player:', error);
                }
            }
        }

        function unmuteVideoPlayer() {
            if (currentVideoType === 'youtube' && youtubePlayer && playerReady) {
                try {
                    youtubePlayer.unMute();
                    youtubePlayer.setVolume(currentVideoVolume || 50);
                    if (Config.video.pauseOnCall) {
                        youtubePlayer.playVideo();
                    }
                    console.log('🔊 YouTube Player unmuted with volume:', currentVideoVolume + '%');
                } catch (error) {
                    console.error('❌ Error unmuting YouTube Player:', error);
                }
            } else if (currentVideoType === 'upload' && htmlVideoPlayer) {
                try {
                    htmlVideoPlayer.muted = false;
                    htmlVideoPlayer.volume = (currentVideoVolume || 50) / 100;
                    if (Config.video.pauseOnCall) {
                        htmlVideoPlayer.play().catch(e => {
                            console.warn('⚠️ Failed to resume video playback:', e);
                        });
                    }
                    console.log('🔊 HTML5 Video Player unmuted with volume:', currentVideoVolume + '%');
                } catch (error) {
                    console.error('❌ Error unmuting HTML5 Video Player:', error);
                }
            }
        }

        function updateYouTubeVideo(videoId, volume = null) {
            if (currentVideoType === 'youtube' && youtubePlayer && playerReady) {
                try {
                    youtubePlayer.loadVideoById({
                        'videoId': videoId,
                        'startSeconds': 0
                    });

                    if (volume !== null) {
                        setTimeout(() => {
                            youtubePlayer.setVolume(volume);
                            console.log('✅ YouTube video updated with volume:', volume + '%');
                        }, 500);
                    }
                } catch (error) {
                    console.error('❌ Error updating YouTube video:', error);
                }
            }
        }

        // ========================================
        // SPEECH ENGINE FUNCTIONS (unchanged)
        // ========================================

        function initializeWebSpeech() {
            if ('speechSynthesis' in window) {
                console.log('✅ Web Speech API available');
                const loadVoices = () => {
                    const voices = speechSynthesis.getVoices();
                    console.log('🎤 Available voices:', voices.length);
                    return voices;
                };

                if (speechSynthesis.getVoices().length > 0) {
                    loadVoices();
                } else {
                    speechSynthesis.addEventListener('voiceschanged', loadVoices);
                }
                return true;
            } else {
                console.error('❌ Web Speech API not available');
                return false;
            }
        }

        function speakWithWebSpeech(text) {
            return new Promise((resolve, reject) => {
                if (!('speechSynthesis' in window)) {
                    reject(new Error('Web Speech API not available'));
                    return;
                }

                const utterance = new SpeechSynthesisUtterance(text);
                utterance.rate = Config.audio.voiceRate;
                utterance.pitch = Config.audio.voicePitch;
                utterance.volume = Config.audio.voiceVolume;

                const voices = speechSynthesis.getVoices();
                const indonesianVoice = voices.find(voice =>
                    voice.lang.includes('id') || voice.lang.includes('ID') ||
                    voice.name.toLowerCase().includes('indonesia')
                );

                if (indonesianVoice) {
                    utterance.voice = indonesianVoice;
                }

                utterance.onend = () => resolve();
                utterance.onerror = (error) => reject(error);

                speechSynthesis.cancel();
                speechSynthesis.speak(utterance);
            });
        }

        function speakWithResponsiveVoice(text) {
            return new Promise((resolve, reject) => {
                console.log('🎤 Speaking with ResponsiveVoice:', text);

                if (typeof responsiveVoice === 'undefined') {
                    reject(new Error('ResponsiveVoice not available'));
                    return;
                }

                if (!responsiveVoice.isReady) {
                    reject(new Error('ResponsiveVoice not ready'));
                    return;
                }

                responsiveVoice.speak(text, "Indonesian Female", {
                    rate: Config.audio.voiceRate,
                    pitch: Config.audio.voicePitch,
                    volume: Config.audio.voiceVolume,
                    onend: resolve,
                    onerror: reject
                });
            });
        }

        function speakText(text) {
            return new Promise(async (resolve, reject) => {
                try {
                    if (Config.speech.engine === 'webspeech') {
                        await speakWithWebSpeech(text);
                    } else if (Config.speech.engine === 'responsivevoice') {
                        await speakWithResponsiveVoice(text);
                    }
                    resolve();
                } catch (primaryError) {
                    if (Config.speech.fallback) {
                        try {
                            if (Config.speech.engine === 'webspeech') {
                                await speakWithResponsiveVoice(text);
                            } else {
                                await speakWithWebSpeech(text);
                            }
                            resolve();
                        } catch (fallbackError) {
                            reject(new Error('Both speech engines failed'));
                        }
                    } else {
                        reject(primaryError);
                    }
                }
            });
        }

        // ========================================
        // AUDIO CONTROL FUNCTIONS
        // ========================================

        function initializeAudioContext() {
            try {
                audioContext = new (window.AudioContext || window.webkitAudioContext)();
                if (audioContext.state === 'suspended') {
                    console.log('🎵 AudioContext suspended, waiting for user interaction');
                }
            } catch (error) {
                console.error('🎵 Failed to create AudioContext:', error);
            }
        }

        function resumeAudioContext() {
            if (audioContext && audioContext.state === 'suspended') {
                audioContext.resume().then(() => {
                    console.log('🎵 AudioContext resumed');
                }).catch(error => {
                    console.error('🎵 Failed to resume AudioContext:', error);
                });
            }
        }

        async function muteVideo() {
            if (!Config.video.enabled) return;
            console.log('🔇 Muting video for queue announcement');

            muteVideoPlayer();

            const videoContainer = document.querySelector('.video-container, #video-container');
            if (videoContainer) {
                videoContainer.style.transition = `opacity ${Config.video.fadeOutDuration}ms ease`;
                videoContainer.style.opacity = '0.3';
            }
        }

        async function unmuteVideo() {
            if (!Config.video.enabled) return;
            console.log('🔊 Unmuting video after queue announcement');

            setTimeout(() => {
                unmuteVideoPlayer();

                const videoContainer = document.querySelector('.video-container, #video-container');
                if (videoContainer) {
                    videoContainer.style.transition = `opacity ${Config.video.fadeInDuration}ms ease`;
                    videoContainer.style.opacity = '1';
                }
            }, 500);
        }

        function playAudioAndSpeak(antrian, nama, loket, whatsapp) {
            return new Promise(async (resolve) => {
                if (!Config.audio.enabled) {
                    resolve();
                    return;
                }

                console.log('🔊 === STARTING AUDIO SEQUENCE ===');
                resumeAudioContext();
                await muteVideo();

                const bell = document.getElementById('tingtung');
                if (!bell) {
                    console.error('❌ Bell audio element not found');
                    unmuteVideo();
                    resolve();
                    return;
                }

                try {
                    bell.currentTime = 0;
                    const playPromise = bell.play();

                    if (playPromise !== undefined) {
                        await playPromise;
                    }

                    const whatsappExist = whatsapp && whatsapp !== "-";
                    setTimeout(async function() {
                        bell.pause();

                        let speechText = whatsappExist
                            ? `Nomor Antrian, ${antrian}, menuju loket, ${loket}`
                            : `Nomor Antrian, ${antrian}, atas nama, ${nama}, menuju loket, ${loket}`;

                        try {
                            await speakText(speechText);
                            unmuteVideo();
                            setTimeout(resolve, Config.audio.speechDelay);
                        } catch (error) {
                            console.error('❌ Speech error:', error);
                            unmuteVideo();
                            setTimeout(resolve, 1000);
                        }
                    }, Config.audio.bellDuration);

                } catch (error) {
                    console.error('❌ Bell audio error:', error);
                    unmuteVideo();
                    setTimeout(resolve, 1000);
                }
            });
        }

        // ========================================
        // API AND UTILITY FUNCTIONS
        // ========================================

        function getCSRFToken() {
            return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        }

        function updateCurrentTime() {
            const now = new Date();
            const options = {
                day: '2-digit',
                month: 'long',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                timeZone: 'Asia/Jakarta'
            };
            document.getElementById('current-time').textContent =
                now.toLocaleString('id-ID', options);
        }

        async function apiRequest(endpoint, options = {}) {
            const url = Config.api.baseUrl + endpoint;
            const defaultOptions = {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCSRFToken(),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            };

            try {
                const response = await fetch(url, { ...defaultOptions, ...options });
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return await response.json();
            } catch (error) {
                console.error(`API request failed for ${endpoint}:`, error);
                throw error;
            }
        }

        // ========================================
        // QUEUE PROCESSING FUNCTIONS
        // ========================================

        async function processQueue() {
            const now = Date.now();
            if (now - lastProcessTime < Config.throttle.processQueue || isPlaying) {
                return;
            }
            lastProcessTime = now;

            try {
                const data = await apiRequest(Config.api.endpoints.next);
                if (data.status === 'empty') {
                    return;
                }

                if (data.id && data.antrian && data.loket) {
                    isPlaying = true;

                    const antrianElement = document.getElementById('antrian-sekarang');
                    const loketElement = document.getElementById('loket');

                    antrianElement.textContent = data.antrian;
                    loketElement.textContent = `LOKET ${data.loket}`;

                    await playAudioAndSpeak(
                        data.antrian,
                        data.nama || '',
                        data.loket,
                        data.whatsapp || ''
                    );

                    try {
                        await apiRequest(Config.api.endpoints.complete, {
                            method: 'POST',
                            body: JSON.stringify({ id: data.id })
                        });
                    } catch (error) {
                        console.error('❌ Error completing queue:', error);
                    }

                    isPlaying = false;
                }
            } catch (error) {
                console.error('❌ Error processing queue:', error);
                isPlaying = false;
                unmuteVideo();
            }
        }

        let lastLoadTime = 0;
        async function loadNextQueues() {
            const now = Date.now();
            if (now - lastLoadTime < Config.throttle.loadNextQueues) {
                return;
            }
            lastLoadTime = now;

            try {
                const data = await apiRequest(Config.api.endpoints.nextList);
                if (data.success && data.queues) {
                    data.queues.forEach((queue, index) => {
                        if (index < 8) {
                            const element = document.getElementById(`antrian-selanjutnya-${index + 1}`);
                            if (element) {
                                element.textContent = queue.antrian;
                            }
                        }
                    });

                    for (let i = data.queues.length; i < 8; i++) {
                        const element = document.getElementById(`antrian-selanjutnya-${i + 1}`);
                        if (element) {
                            element.textContent = '--';
                        }
                    }
                }
            } catch (error) {
                console.error('Error loading next queues:', error);
            }
        }

        // ========================================
        // ENHANCED VIDEO SOURCE UPDATE
        // ========================================
        let lastVideoUpdateTime = 0;
        async function updateVideoSource() {
            const now = Date.now();
            if (now - lastVideoUpdateTime < Config.throttle.updateVideo) {
                return;
            }
            lastVideoUpdateTime = now;

            try {
                console.log('🎥 Checking for active video...');
                const data = await apiRequest(Config.api.endpoints.activeVideo);

                if (data.success && data.video_url) {
                    // Store volume from database
                    if (data.video_data && data.video_data.volume !== undefined) {
                        const newVolume = data.video_data.volume;

                        if (currentVideoVolume !== newVolume) {
                            currentVideoVolume = newVolume;
                            console.log('🔊 Video volume from database:', currentVideoVolume + '%');
                            setVideoVolume(currentVideoVolume);
                        }
                    }

                    // Check if video URL changed
                    if (data.video_url !== currentVideo) {
                        console.log('🎥 Updating video:', data.video_data?.nama || 'Unknown');
                        console.log('🎥 Source type:', data.video_data?.source_type);
                        console.log('🎥 Is local file:', data.video_data?.is_local_file);

                        const sourceType = data.video_data?.source_type || 'url';

                        if (sourceType === 'youtube') {
                            // Extract video ID and initialize YouTube player
                            const videoId = extractVideoId(data.video_url);
                            console.log('🎥 Extracted YouTube video ID:', videoId);

                            if (currentVideoType !== 'youtube' || !youtubePlayer || !playerReady) {
                                initializeYouTubePlayer(videoId);
                            } else {
                                updateYouTubeVideo(videoId, currentVideoVolume);
                            }
                        } else {
                            // Upload file or other URL - use HTML5 video
                            console.log('🎥 Using HTML5 video for:', data.video_url);

                            if (currentVideoType !== 'upload' || !htmlVideoPlayer) {
                                initializeHtmlVideoPlayer(data.video_url);
                            } else {
                                // Update existing HTML5 video source
                                if (htmlVideoPlayer.src !== data.video_url) {
                                    htmlVideoPlayer.src = data.video_url;
                                    htmlVideoPlayer.load();
                                    setVideoVolume(currentVideoVolume);
                                }
                            }
                        }

                        currentVideo = data.video_url;
                        console.log('✅ Video updated successfully with volume:', currentVideoVolume + '%');
                    }
                } else {
                    // No active video, use default YouTube
                    if (!currentVideo) {
                        console.log('🎥 No active video found, using default');
                        initializeYouTubePlayer('tXWuQbGTfxM');
                        currentVideo = 'default';
                    }
                }
            } catch (error) {
                console.error('❌ Error updating video:', error);
            }
        }

        function extractVideoId(url) {
            console.log('🔍 Extracting video ID from URL:', url);

            // Handle various YouTube URL formats
            let videoId = 'tXWuQbGTfxM'; // default fallback

            if (url.includes('youtube.com/watch?v=')) {
                const match = url.match(/[?&]v=([^&]+)/);
                videoId = match ? match[1] : videoId;
            } else if (url.includes('youtu.be/')) {
                const match = url.match(/youtu\.be\/([^?&]+)/);
                videoId = match ? match[1] : videoId;
            } else if (url.includes('youtube.com/embed/')) {
                const match = url.match(/\/embed\/([^?&]+)/);
                videoId = match ? match[1] : videoId;
            }

            console.log('🎯 Extracted video ID:', videoId);
            return videoId;
        }

        async function checkConnection() {
            try {
                await apiRequest(Config.api.endpoints.stats);
                console.log('Connection Status: Connected');
                retryCount = 0;
            } catch (error) {
                console.error('Connection check failed:', error);
                console.log('Connection Status: Disconnected - Retrying...');
            }
        }

        // ========================================
        // INITIALIZE APPLICATION
        // ========================================
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🚀 Queue Display System with Multi-Video Support starting...');

            initializeAudioContext();
            initializeWebSpeech();

            updateCurrentTime();
            setInterval(updateCurrentTime, 1000);

            setInterval(processQueue, Config.polling.queue);
            setInterval(loadNextQueues, Config.polling.nextList);
            setInterval(updateVideoSource, Config.polling.video);
            setInterval(checkConnection, Config.polling.connection);

            loadNextQueues();

            setTimeout(() => {
                updateVideoSource();
            }, 2000);

            const enableAudioOnInteraction = () => {
                resumeAudioContext();
                document.removeEventListener('click', enableAudioOnInteraction);
                document.removeEventListener('keydown', enableAudioOnInteraction);
                console.log('🔊 Audio enabled by user interaction');
            };

            document.addEventListener('click', enableAudioOnInteraction);
            document.addEventListener('keydown', enableAudioOnInteraction);

            console.log('✅ Queue Display System initialized');
        });

        // ========================================
        // TESTING AND DEBUGGING
        // ========================================
        document.addEventListener('keydown', function(event) {
            if (event.ctrlKey && event.shiftKey) {
                switch(event.key) {
                    case 'T':
                        event.preventDefault();
                        console.log('🧪 Testing audio system...');
                        playAudioAndSpeak('TEST', 'Test User', '1', '');
                        break;
                    case 'V':
                        event.preventDefault();
                        getPlayerInfo();
                        break;
                    case 'S':
                        event.preventDefault();
                        console.log('🧪 Setting volume to 25%...');
                        setVideoVolume(25);
                        break;
                    case 'M':
                        event.preventDefault();
                        console.log('🧪 Testing mute/unmute...');
                        muteVideo();
                        setTimeout(() => {
                            unmuteVideo();
                        }, 3000);
                        break;
                    case 'Y':
                        event.preventDefault();
                        console.log('🧪 Testing YouTube player...');
                        initializeYouTubePlayer('tXWuQbGTfxM');
                        break;
                    case 'H':
                        event.preventDefault();
                        console.log('🧪 Testing HTML5 player...');
                        initializeHtmlVideoPlayer('https://sample-videos.com/zip/10/mp4/SampleVideo_1280x720_1mb.mp4');
                        break;
                    case 'Q':
                        event.preventDefault();
                        console.log('🧪 Testing queue announcement...');
                        playAudioAndSpeak('TEST', 'Test User', '1', '');
                        break;
                }
            }
        });

        // Global functions for testing
        window.testQueue = () => playAudioAndSpeak('TEST', 'Test User', '1', '');
        window.getCurrentVolume = () => {
            console.log('Current video type:', currentVideoType);
            console.log('Current video volume:', currentVideoVolume + '%');
            console.log('Player ready:', playerReady);

            if (currentVideoType === 'youtube' && youtubePlayer && playerReady) {
                try {
                    console.log('YouTube Player volume:', youtubePlayer.getVolume() + '%');
                    console.log('YouTube Player muted:', youtubePlayer.isMuted());
                    console.log('YouTube Player state:', youtubePlayer.getPlayerState());
                } catch (error) {
                    console.log('YouTube Player error:', error);
                }
            }

            if (currentVideoType === 'upload' && htmlVideoPlayer) {
                try {
                    console.log('HTML5 Video volume:', Math.round(htmlVideoPlayer.volume * 100) + '%');
                    console.log('HTML5 Video muted:', htmlVideoPlayer.muted);
                    console.log('HTML5 Video duration:', htmlVideoPlayer.duration);
                    console.log('HTML5 Video current time:', htmlVideoPlayer.currentTime);
                    console.log('HTML5 Video ready state:', htmlVideoPlayer.readyState);
                    console.log('HTML5 Video network state:', htmlVideoPlayer.networkState);
                } catch (error) {
                    console.log('HTML5 Video error:', error);
                }
            }

            return currentVideoVolume;
        };

        window.setVolume = (volume) => {
            console.log('Setting volume to:', volume + '%');
            setVideoVolume(volume);
        };

        window.testMute = () => {
            console.log('Testing mute...');
            muteVideo();
            setTimeout(() => {
                console.log('Testing unmute...');
                unmuteVideo();
            }, 3000);
        };

        window.forceUpdateVideo = () => {
            console.log('Force updating video...');
            updateVideoSource();
        };

        window.getPlayerInfo = () => {
            console.log('=== VIDEO PLAYER INFO ===');
            console.log('Current Type:', currentVideoType);
            console.log('Current Volume:', currentVideoVolume + '%');
            console.log('Player Ready:', playerReady);
            console.log('Current Video URL:', currentVideo);

            if (currentVideoType === 'youtube' && youtubePlayer) {
                try {
                    console.log('YouTube Player exists:', !!youtubePlayer);
                    if (playerReady) {
                        console.log('YouTube Volume:', youtubePlayer.getVolume() + '%');
                        console.log('YouTube Muted:', youtubePlayer.isMuted());
                        console.log('YouTube State:', youtubePlayer.getPlayerState());
                        console.log('YouTube Video Data:', youtubePlayer.getVideoData());
                    }
                } catch (error) {
                    console.log('YouTube Player error:', error);
                }
            }

            if (currentVideoType === 'upload' && htmlVideoPlayer) {
                try {
                    console.log('HTML5 Video exists:', !!htmlVideoPlayer);
                    console.log('HTML5 Video src:', htmlVideoPlayer.src);
                    console.log('HTML5 Volume:', Math.round(htmlVideoPlayer.volume * 100) + '%');
                    console.log('HTML5 Muted:', htmlVideoPlayer.muted);
                    console.log('HTML5 Duration:', htmlVideoPlayer.duration);
                    console.log('HTML5 Current Time:', htmlVideoPlayer.currentTime);
                    console.log('HTML5 Ready State:', htmlVideoPlayer.readyState);
                    console.log('HTML5 Network State:', htmlVideoPlayer.networkState);
                    console.log('HTML5 Error:', htmlVideoPlayer.error);
                } catch (error) {
                    console.log('HTML5 Video error:', error);
                }
            }

            console.log('=== END PLAYER INFO ===');
        };

        window.testYouTube = (videoId = 'tXWuQbGTfxM') => {
            console.log('Testing YouTube player with video ID:', videoId);
            initializeYouTubePlayer(videoId);
        };

        window.testHtml5Video = (url = 'https://sample-videos.com/zip/10/mp4/SampleVideo_1280x720_1mb.mp4') => {
            console.log('Testing HTML5 video player with URL:', url);
            initializeHtmlVideoPlayer(url);
        };

        window.switchToYouTube = () => {
            console.log('Switching to YouTube player...');
            initializeYouTubePlayer('tXWuQbGTfxM');
        };

        window.switchToUpload = () => {
            console.log('Switching to HTML5 player...');
            initializeHtmlVideoPlayer('https://sample-videos.com/zip/10/mp4/SampleVideo_1280x720_1mb.mp4');
        };

        window.debugVideoContainer = () => {
            const container = document.getElementById('video-container');
            console.log('Video container exists:', !!container);
            if (container) {
                console.log('Container innerHTML length:', container.innerHTML.length);
                console.log('Container children:', container.children.length);
                console.log('Container content preview:', container.innerHTML.substring(0, 200));
            }
        };

        // ========================================
        // VOLUME MONITORING AND ERROR RECOVERY
        // ========================================
        setInterval(() => {
            if (playerReady && Config.audio.debugMode) {
                let actualVolume = 0;
                let needsCorrection = false;

                try {
                    if (currentVideoType === 'youtube' && youtubePlayer) {
                        actualVolume = youtubePlayer.getVolume();
                        needsCorrection = Math.abs(actualVolume - currentVideoVolume) > 1;
                    } else if (currentVideoType === 'upload' && htmlVideoPlayer) {
                        actualVolume = Math.round(htmlVideoPlayer.volume * 100);
                        needsCorrection = Math.abs(actualVolume - currentVideoVolume) > 1;
                    }

                    if (needsCorrection) {
                        console.log('⚠️ Volume mismatch detected!');
                        console.log('Expected:', currentVideoVolume + '%');
                        console.log('Actual:', actualVolume + '%');
                        console.log('Correcting volume...');
                        setVideoVolume(currentVideoVolume);
                    }
                } catch (error) {
                    console.warn('Volume monitoring error:', error);
                }
            }
        }, 10000); // Check every 10 seconds

        // Error recovery for failed players
        setInterval(() => {
            if (!playerReady && currentVideo) {
                console.log('🔄 Player not ready but should be, attempting recovery...');

                if (currentVideoType === 'youtube' && !youtubePlayer) {
                    console.log('🔄 Recovering YouTube player...');
                    const videoId = extractVideoId(currentVideo);
                    initializeYouTubePlayer(videoId);
                } else if (currentVideoType === 'upload' && !htmlVideoPlayer) {
                    console.log('🔄 Recovering HTML5 player...');
                    initializeHtmlVideoPlayer(currentVideo);
                }
            }
        }, 30000); // Check every 30 seconds

        /*
        ===========================================
        TESTING COMMANDS FOR BROWSER CONSOLE:
        ===========================================

        Volume Control:
        - getCurrentVolume()         // Check current volume and player status
        - setVolume(50)             // Set volume to 50%
        - testMute()                // Test mute/unmute sequence

        Player Testing:
        - getPlayerInfo()           // Detailed player information
        - testYouTube('VIDEO_ID')   // Test YouTube player with specific video
        - testHtml5Video('URL')     // Test HTML5 player with specific URL
        - switchToYouTube()         // Switch to YouTube player
        - switchToUpload()          // Switch to HTML5 player

        Queue Testing:
        - testQueue()               // Test queue announcement
        - forceUpdateVideo()        // Force video update from database

        Debugging:
        - debugVideoContainer()     // Check video container status

        KEYBOARD SHORTCUTS:
        - Ctrl+Shift+T             // Test audio system
        - Ctrl+Shift+V             // Get player info
        - Ctrl+Shift+S             // Set volume to 25%
        - Ctrl+Shift+M             // Test mute/unmute sequence
        - Ctrl+Shift+Y             // Test YouTube player
        - Ctrl+Shift+H             // Test HTML5 player

        ===========================================
        */
    </script>
</body>
</html>
