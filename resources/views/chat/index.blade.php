<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --bg-gradient: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            --container-bg: #2d3748;
            --header-bg: linear-gradient(135deg, #007cba, #00a8cc);
            --messages-bg: #1a202c;
            --user-msg-bg: #007cba;
            --ai-msg-bg: #4a5568;
            --ai-msg-border: #718096;
            --input-bg: #2d3748;
            --input-border: #4a5568;
            --text-color: #e2e8f0;
            --text-secondary: #a0aec0;
            --btn-bg: rgba(255,255,255,0.1);
            --btn-hover: rgba(255,255,255,0.2);
            --empty-text: #718096;
        }

        [data-theme="light"] {
            --bg-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --container-bg: white;
            --header-bg: linear-gradient(135deg, #007cba, #00a8cc);
            --messages-bg: #f8f9fa;
            --user-msg-bg: #007cba;
            --ai-msg-bg: white;
            --ai-msg-border: #e0e0e0;
            --input-bg: white;
            --input-border: #e0e0e0;
            --text-color: #333;
            --text-secondary: #666;
            --btn-bg: rgba(255,255,255,0.2);
            --btn-hover: rgba(255,255,255,0.3);
            --empty-text: #6c757d;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--bg-gradient);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: var(--text-color);
            transition: all 0.3s ease;
        }

        .chat-container {
            width: 90%;
            max-width: 800px;
            height: 90vh;
            background: var(--container-bg);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .chat-header {
            background: var(--header-bg);
            color: white;
            padding: 20px;
            text-align: center;
            position: relative;
        }

        .chat-header h1 {
            font-size: 1.5em;
            margin-bottom: 5px;
        }

        .chat-header .subtitle {
            opacity: 0.9;
            font-size: 0.9em;
        }

        .clear-btn {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            background: var(--btn-bg);
            border: none;
            color: white;
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.8em;
            transition: all 0.3s ease;
        }

        .clear-btn:hover {
            background: var(--btn-hover);
        }

        .header-controls {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            gap: 10px;
        }

        .control-btn {
            background: var(--btn-bg);
            border: none;
            color: white;
            padding: 8px;
            border-radius: 8px;
            cursor: pointer;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2em;
            transition: all 0.3s ease;
        }

        .control-btn:hover {
            background: var(--btn-hover);
            transform: scale(1.1);
        }

        .voice-btn.active {
            background: #ff4757;
            animation: pulse 1s infinite;
        }

        .voice-btn.speaking {
            background: #2ed573;
            animation: pulse 1s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            background: var(--messages-bg);
            transition: all 0.3s ease;
        }

        .message {
            margin-bottom: 15px;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .message-user {
            display: flex;
            justify-content: flex-start;
        }

        .message-ai {
            display: flex;
            justify-content: flex-end;
        }

        [dir="rtl"] .message-user {
            justify-content: flex-end;
        }

        [dir="rtl"] .message-ai {
            justify-content: flex-start;
        }

        .message-content {
            max-width: 70%;
            padding: 12px 16px;
            border-radius: 18px;
            word-wrap: break-word;
            line-height: 1.4;
            position: relative;
            color: var(--text-color);
            transition: all 0.3s ease;
        }

        .message-user .message-content {
            background: var(--user-msg-bg);
            color: white;
            border-bottom-left-radius: 4px;
        }

        [dir="rtl"] .message-user .message-content {
            border-bottom-left-radius: 18px;
            border-bottom-right-radius: 4px;
        }

        .message-ai .message-content {
            background: var(--ai-msg-bg);
            color: var(--text-color);
            border: 1px solid var(--ai-msg-border);
            border-bottom-right-radius: 4px;
        }

        [dir="rtl"] .message-ai .message-content {
            border-bottom-right-radius: 18px;
            border-bottom-left-radius: 4px;
        }

        .voice-indicator {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #2ed573;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7em;
        }

        .message-ai .voice-indicator {
            left: -5px;
            right: auto;
        }

        [dir="rtl"] .voice-indicator {
            left: -5px;
            right: auto;
        }

        [dir="rtl"] .message-ai .voice-indicator {
            right: -5px;
            left: auto;
        }

        .play-btn {
            background: none;
            border: none;
            color: #007cba;
            cursor: pointer;
            margin-left: 8px;
            font-size: 1.1em;
            padding: 2px;
            border-radius: 3px;
            transition: all 0.2s;
        }

        [dir="rtl"] .play-btn {
            margin-left: 0;
            margin-right: 8px;
        }

        .play-btn:hover {
            background: rgba(0, 124, 186, 0.1);
        }

        .message-status {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 15px;
        }

        [dir="rtl"] .message-status {
            justify-content: flex-start;
        }

        .status-indicator {
            background: #4a5568;
            color: var(--text-secondary);
            padding: 8px 12px;
            border-radius: 12px;
            font-size: 0.8em;
            display: flex;
            align-items: center;
            gap: 5px;
            border: 1px solid var(--ai-msg-border);
        }

        .status-processing {
            background: #2b6cb0;
            color: #bee3f8;
            border-color: #3182ce;
        }

        .status-failed {
            background: #c53030;
            color: #fed7d7;
            border-color: #e53e3e;
        }

        .status-listening {
            background: #d69e2e;
            color: #faf089;
            border-color: #ecc94b;
        }

        .spinner {
            width: 12px;
            height: 12px;
            border: 2px solid transparent;
            border-top: 2px solid currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .chat-input {
            padding: 20px;
            background: var(--input-bg);
            border-top: 1px solid var(--input-border);
            transition: all 0.3s ease;
        }

        .input-container {
            display: flex;
            gap: 10px;
            align-items: flex-end;
        }

        .message-input {
            flex: 1;
            border: 2px solid var(--input-border);
            border-radius: 20px;
            padding: 12px 16px;
            font-size: 16px;
            font-family: inherit;
            resize: none;
            max-height: 100px;
            min-height: 44px;
            background: var(--input-bg);
            color: var(--text-color);
            transition: all 0.3s ease;
        }

        .message-input:focus {
            outline: none;
            border-color: #007cba;
        }

        .message-input::placeholder {
            color: var(--text-secondary);
        }

        .voice-input-btn {
            background: #ff4757;
            color: white;
            border: none;
            border-radius: 50%;
            width: 44px;
            height: 44px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            transition: all 0.2s;
            margin-right: 5px;
        }

        [dir="rtl"] .voice-input-btn {
            margin-right: 0;
            margin-left: 5px;
        }

        .voice-input-btn:hover {
            background: #ff3742;
            transform: scale(1.05);
        }

        .voice-input-btn.listening {
            background: #ff4757;
            animation: pulse 1s infinite;
        }

        .send-btn {
            background: #007cba;
            color: white;
            border: none;
            border-radius: 50%;
            width: 44px;
            height: 44px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            transition: all 0.2s;
        }

        .send-btn:hover {
            background: #005a87;
            transform: scale(1.05);
        }

        .send-btn:disabled {
            background: #4a5568;
            cursor: not-allowed;
            transform: none;
        }

        .empty-state {
            text-align: center;
            color: var(--empty-text);
            padding: 40px 20px;
        }

        .empty-state .icon {
            font-size: 3em;
            margin-bottom: 15px;
        }

        .typing-indicator {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 15px;
        }

        [dir="rtl"] .typing-indicator {
            justify-content: flex-start;
        }

        .typing-dots {
            background: var(--ai-msg-bg);
            border: 1px solid var(--ai-msg-border);
            border-radius: 18px;
            border-bottom-right-radius: 4px;
            padding: 12px 16px;
            display: flex;
            gap: 4px;
        }

        [dir="rtl"] .typing-dots {
            border-bottom-right-radius: 18px;
            border-bottom-left-radius: 4px;
        }

        .typing-dot {
            width: 8px;
            height: 8px;
            background: var(--text-secondary);
            border-radius: 50%;
            animation: typing 1.4s infinite;
        }

        .typing-dot:nth-child(2) { animation-delay: 0.2s; }
        .typing-dot:nth-child(3) { animation-delay: 0.4s; }

        @keyframes typing {
            0%, 60%, 100% { transform: translateY(0); }
            30% { transform: translateY(-10px); }
        }

        .voice-wave {
            display: flex;
            align-items: center;
            gap: 2px;
            margin-left: 10px;
        }

        [dir="rtl"] .voice-wave {
            margin-left: 0;
            margin-right: 10px;
        }

        .voice-wave span {
            width: 3px;
            height: 10px;
            background: currentColor;
            border-radius: 1px;
            animation: wave 1s infinite;
        }

        .voice-wave span:nth-child(2) { animation-delay: 0.1s; }
        .voice-wave span:nth-child(3) { animation-delay: 0.2s; }
        .voice-wave span:nth-child(4) { animation-delay: 0.3s; }

        @keyframes wave {
            0%, 100% { height: 10px; }
            50% { height: 20px; }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .chat-container {
                width: 95%;
                height: 95vh;
                border-radius: 15px;
            }
            
            .message-content {
                max-width: 85%;
            }
            
            .chat-header h1 {
                font-size: 1.2em;
            }

            .header-controls {
                right: 10px;
            }

            .clear-btn {
                left: 10px;
            }
        }
    </style>
</head>
<body data-theme="dark">
<div class="chat-container">
    <!-- Header -->
    <div class="chat-header">
        <button class="clear-btn" onclick="clearChat()">
            <span class="clear-text">🗑️ Clear</span>
        </button>
        <h1>
            <span class="title-text">🎤 Smart Voice Assistant</span>
        </h1>
        <div class="subtitle">
            <span class="subtitle-text">Talk to me or type your message!</span>
        </div>
        <div class="header-controls">
            <button class="control-btn" id="langToggleBtn" onclick="toggleLanguage()" title="Toggle Language">
                🌐
            </button>
            <button class="control-btn" id="themeToggleBtn" onclick="toggleTheme()" title="Toggle Theme">
                🌙
            </button>
            <button class="control-btn voice-btn" id="voiceToggleBtn" onclick="toggleVoiceMode()" title="Toggle Voice">
                🔊
            </button>
        </div>
    </div>

    <!-- Messages Area -->
    <div class="chat-messages" id="messagesContainer">
        <div class="empty-state" id="emptyState">
            <div class="icon">🎤</div>
            <h3 class="welcome-title">Welcome!</h3>
            <p class="welcome-text">You can talk to me or type your message<br>Press the microphone to start</p>
        </div>
    </div>

    <!-- Input Area -->
    <div class="chat-input">
        <div class="input-container">
            <button class="voice-input-btn" id="voiceInputBtn" onclick="toggleVoiceInput()" title="Speak">
                🎤
            </button>
            <textarea
                id="messageInput"
                class="message-input"
                placeholder="Type your message here or use the microphone..."
                rows="1"
            ></textarea>
            <button class="send-btn" id="sendBtn" onclick="sendMessage()">
                ➤
            </button>
        </div>
    </div>
</div>

<script>
    // Language translations
    const translations = {
        en: {
            title: "🎤 Smart Voice Assistant",
            subtitle: "Talk to me or type your message!",
            clear: "🗑️ Clear",
            welcomeTitle: "Welcome!",
            welcomeText: "You can talk to me or type your message<br>Press the microphone to start",
            placeholder: "Type your message here or use the microphone...",
            listening: "🎤 I'm listening to you..",
            speechError: "Speech recognition error. Make sure microphone access is allowed.",
            noSpeechSupport: "Your browser does not support speech recognition",
            sendError: "An error occurred while sending the message",
            responseError: "Failed to get a response",
            timeout: "Request timed out",
            clearConfirm: "Do you want to clear the conversation?",
            loadError: "Error loading messages",
            clearError: "Error clearing the conversation",
            playTitle: "Play message",
            turnOffVoice: "Turn off voice",
            turnOnVoice: "Turn on voice",
            speak: "Speak",
            toggleLang: "Toggle Language",
            toggleTheme: "Toggle Theme",
            toggleVoice: "Toggle Voice"
        },
        ar: {
            title: "🎤 المساعد الصوتي الذكي",
            subtitle: "تحدث معي أو اكتب رسالتك!",
            clear: "🗑️ مسح",
            welcomeTitle: "مرحباً!",
            welcomeText: "يمكنك التحدث معي باستخدام صوتك أو كتابة رسالتك<br>اضغط على الميكروفون للبدء",
            placeholder: "اكتب رسالتك هنا أو استخدم الميكروفون...",
            listening: "🎤 أنا أستمع إليك..",
            speechError: "خطأ في التعرف على الكلام. تأكد من السماح بالوصول للميكروفون.",
            noSpeechSupport: "متصفحك لا يدعم التعرف على الكلام",
            sendError: "حدث خطأ أثناء إرسال الرسالة",
            responseError: "فشل في الحصول على رد",
            timeout: "انتهت مهلة الطلب",
            clearConfirm: "هل تريد مسح المحادثة؟",
            loadError: "خطأ في تحميل الرسائل",
            clearError: "خطأ في مسح المحادثة",
            playTitle: "تشغيل الرسالة",
            turnOffVoice: "إيقاف الصوت",
            turnOnVoice: "تشغيل الصوت",
            speak: "تحدث",
            toggleLang: "تبديل اللغة",
            toggleTheme: "تبديل المظهر",
            toggleVoice: "تبديل الصوت"
        }
    };

    // إعداد CSRF Token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // متغيرات عامة
    let pendingMessages = new Set();
    let isLoading = false;
    let isListening = false;
    let voiceEnabled = true;
    let currentSpeech = null;
    let recognition = null;
    let currentLang = 'en';
    let currentTheme = 'dark';

    // عناصر DOM
    const messagesContainer = document.getElementById('messagesContainer');
    const messageInput = document.getElementById('messageInput');
    const sendBtn = document.getElementById('sendBtn');
    const emptyState = document.getElementById('emptyState');
    const voiceInputBtn = document.getElementById('voiceInputBtn');
    const voiceToggleBtn = document.getElementById('voiceToggleBtn');
    const langToggleBtn = document.getElementById('langToggleBtn');
    const themeToggleBtn = document.getElementById('themeToggleBtn');

    // تبديل اللغة
    function toggleLanguage() {
        currentLang = currentLang === 'en' ? 'ar' : 'en';
        const html = document.documentElement;
        
        if (currentLang === 'ar') {
            html.setAttribute('lang', 'ar');
            html.setAttribute('dir', 'rtl');
            if (recognition) {
                recognition.lang = 'ar-SA';
            }
        } else {
            html.setAttribute('lang', 'en');
            html.setAttribute('dir', 'ltr');
            if (recognition) {
                recognition.lang = 'en-US';
            }
        }
        
        updateTexts();
        localStorage.setItem('chatLang', currentLang);
    }

    // تبديل المظهر
    function toggleTheme() {
        currentTheme = currentTheme === 'dark' ? 'light' : 'dark';
        document.body.setAttribute('data-theme', currentTheme);
        themeToggleBtn.innerHTML = currentTheme === 'dark' ? '🌙' : '☀️';
        localStorage.setItem('chatTheme', currentTheme);
    }

    // تحديث النصوص
    function updateTexts() {
        const t = translations[currentLang];
        
        document.querySelector('.title-text').textContent = t.title;
        document.querySelector('.subtitle-text').textContent = t.subtitle;
        document.querySelector('.clear-text').textContent = t.clear;
        document.querySelector('.welcome-title').textContent = t.welcomeTitle;
        document.querySelector('.welcome-text').innerHTML = t.welcomeText;
        
        messageInput.placeholder = t.placeholder;
        
        // تحديث العناوين
        document.querySelector('.clear-btn').title = t.clear;
        voiceInputBtn.title = t.speak;
        langToggleBtn.title = t.toggleLang;
        themeToggleBtn.title = t.toggleTheme;
        voiceToggleBtn.title = voiceEnabled ? t.turnOffVoice : t.turnOnVoice;
    }

    // إعداد التعرف على الكلام
    function initSpeechRecognition() {
        if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            recognition = new SpeechRecognition();
            
            recognition.continuous = false;
            recognition.interimResults = false;
            recognition.lang = currentLang === 'ar' ? 'ar-SA' : 'en-US';
            
            recognition.onstart = function() {
                isListening = true;
                voiceInputBtn.classList.add('listening');
                addStatusMessage(translations[currentLang].listening, 'status-listening');
            };
            
            recognition.onresult = function(event) {
                const transcript = event.results[0][0].transcript;
                messageInput.value = transcript;
                sendMessage();
            };
            
            recognition.onerror = function(event) {
                console.error('Speech recognition error:', event.error);
                addErrorMessage(translations[currentLang].speechError);
            };
            
            recognition.onend = function() {
                isListening = false;
                voiceInputBtn.classList.remove('listening');
                removeStatusMessage('status-listening');
            };
        } else {
            console.warn(translations[currentLang].noSpeechSupport);
            voiceInputBtn.style.display = 'none';
        }
    }

    // تشغيل/إيقاف الإدخال الصوتي
    function toggleVoiceInput() {
        if (!recognition) {
            alert(translations[currentLang].noSpeechSupport);
            return;
        }

        if (isListening) {
            recognition.stop();
        } else {
            if (currentSpeech) {
                currentSpeech.cancel();
            }
            recognition.start();
        }
    }

    // تشغيل/إيقاف الصوت
    function toggleVoiceMode() {
        voiceEnabled = !voiceEnabled;
        voiceToggleBtn.innerHTML = voiceEnabled ? '🔊' : '🔇';
        voiceToggleBtn.title = voiceEnabled ? translations[currentLang].turnOffVoice : translations[currentLang].turnOnVoice;
        
        if (!voiceEnabled && currentSpeech) {
            currentSpeech.cancel();
        }
    }

    // تحويل النص إلى كلام
    function speakText(text) {
        if (!voiceEnabled || !('speechSynthesis' in window)) return;

        // إيقاف أي كلام حالي
        if (currentSpeech) {
            speechSynthesis.cancel();
        }

        currentSpeech = new SpeechSynthesisUtterance(text);
        
        // البحث عن صوت مناسب
        const voices = speechSynthesis.getVoices();
        let selectedVoice;
        
        if (currentLang === 'ar') {
            selectedVoice = voices.find(voice => 
                voice.lang.startsWith('ar') && 
                (voice.name.includes('Male') || voice.name.includes('مذكر') || voice.gender === 'male')
            ) || voices.find(voice => voice.lang.startsWith('ar'));
            currentSpeech.lang = 'ar-SA';
        } else {
            selectedVoice = voices.find(voice => 
                voice.lang.startsWith('en') && 
                (voice.name.includes('Male') || voice.gender === 'male')
            ) || voices.find(voice => voice.lang.startsWith('en'));
            currentSpeech.lang = 'en-US';
        }

        if (selectedVoice) {
            currentSpeech.voice = selectedVoice;
        }
        
        currentSpeech.rate = 0.9;
        currentSpeech.pitch = 0.8;
        currentSpeech.volume = 1;

        currentSpeech.onstart = function() {
            voiceToggleBtn.classList.add('speaking');
        };

        currentSpeech.onend = function() {
            voiceToggleBtn.classList.remove('speaking');
            currentSpeech = null;
        };

        currentSpeech.onerror = function() {
            voiceToggleBtn.classList.remove('speaking');
            currentSpeech = null;
        };

        speechSynthesis.speak(currentSpeech);
    }

    // تشغيل رسالة محددة
    function playMessage(text, button) {
        if (currentSpeech) {
            speechSynthesis.cancel();
            return;
        }

        button.innerHTML = '⏹️';
        speakText(text);

        currentSpeech.onend = function() {
            button.innerHTML = '▶️';
            currentSpeech = null;
        };

        currentSpeech.onerror = function() {
            button.innerHTML = '▶️';
            currentSpeech = null;
        };
    }

    // تحميل الإعدادات المحفوظة
    function loadSettings() {
        const savedLang = localStorage.getItem('chatLang') || 'en';
        const savedTheme = localStorage.getItem('chatTheme') || 'dark';
        
        if (savedLang !== currentLang) {
            currentLang = savedLang;
            toggleLanguage();
        }
        
        if (savedTheme !== currentTheme) {
            currentTheme = savedTheme;
            toggleTheme();
        }
    }

    // تحميل الرسائل عند بدء الصفحة
    document.addEventListener('DOMContentLoaded', function() {
        loadSettings();
        initSpeechRecognition();
        loadMessages();
        
        // تحميل الأصوات
        if ('speechSynthesis' in window) {
            speechSynthesis.getVoices();
            speechSynthesis.onvoiceschanged = function() {
                speechSynthesis.getVoices();
            };
        }
        
        // إعداد إرسال بالضغط على Enter
        messageInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });

            // تعديل حجم textarea تلقائياً
            messageInput.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = Math.min(this.scrollHeight, 100) + 'px';
            });
        });

        // إرسال رسالة جديدة
        async function sendMessage() {
            const message = messageInput.value.trim();
            if (!message || isLoading) return;

            isLoading = true;
            sendBtn.disabled = true;
            
            // إيقاف أي كلام حالي
            if (currentSpeech) {
                speechSynthesis.cancel();
            }
            
            // إضافة رسالة المستخدم فوراً
            addUserMessage(message);
            messageInput.value = '';
            messageInput.style.height = 'auto';
            
            // إخفاء الحالة الفارغة
            if (emptyState) {
                emptyState.style.display = 'none';
            }

            try {
                const response = await fetch('/chat/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ message: message })
                });

                const data = await response.json();
                
                if (data.success) {
                    // إضافة مؤشر الكتابة
                    addTypingIndicator(data.message_id);
                    
                    // إضافة المعرف إلى قائمة الرسائل المعلقة
                    pendingMessages.add(data.message_id);
                    
                    // بدء التحقق من حالة الرسالة
                    checkMessageStatus(data.message_id);
                }
            } catch (error) {
console.error('Error sending message:', error);
addErrorMessage('An error occurred while sending the message');

            }

            isLoading = false;
            sendBtn.disabled = false;
        }

        // إضافة رسالة المستخدم
        function addUserMessage(message) {
            const messageDiv = document.createElement('div');
            messageDiv.className = 'message message-user';
            messageDiv.innerHTML = `
                <div class="message-content">
                    ${escapeHtml(message)}
                    <div class="voice-indicator">🎤</div>
                </div>
            `;
            messagesContainer.appendChild(messageDiv);
            scrollToBottom();
        }

        // إضافة مؤشر الكتابة
        function addTypingIndicator(messageId) {
            const typingDiv = document.createElement('div');
            typingDiv.className = 'typing-indicator';
            typingDiv.id = `typing-${messageId}`;
            typingDiv.innerHTML = `
                <div class="typing-dots">
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                </div>
            `;
            messagesContainer.appendChild(typingDiv);
            scrollToBottom();
        }

        // إضافة رد الذكاء الاصطناعي
        function addAiMessage(message, messageId) {
            // إزالة مؤشر الكتابة
            const typingIndicator = document.getElementById(`typing-${messageId}`);
            if (typingIndicator) {
                typingIndicator.remove();
            }

            const messageDiv = document.createElement('div');
            messageDiv.className = 'message message-ai';
            messageDiv.innerHTML = `
                <div class="message-content">
                    ${escapeHtml(message).replace(/\n/g, '<br>')}
                    <button class="play-btn" onclick="playMessage('${message.replace(/'/g, "\\'")}', this)" title=" play message">
                        ▶️
                    </button>
                    <div class="voice-indicator">🔊</div>
                </div>
            `;
            messagesContainer.appendChild(messageDiv);
            scrollToBottom();

            // تشغيل الرد صوتياً تلقائياً
            if (voiceEnabled) {
                setTimeout(() => speakText(message), 500);
            }
        }

        // إضافة رسالة حالة
        function addStatusMessage(message, className = 'status-processing') {
            const existingStatus = document.querySelector(`.${className}`);
            if (existingStatus) return;

            const messageDiv = document.createElement('div');
            messageDiv.className = 'message-status';
            messageDiv.innerHTML = `
                <div class="status-indicator ${className}">
                    ${className === 'status-listening' ? '' : '<div class="spinner"></div>'}
                    ${message}
                    <div class="voice-wave">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            `;
            messagesContainer.appendChild(messageDiv);
            scrollToBottom();
        }

        // إزالة رسالة حالة
        function removeStatusMessage(className) {
            const statusElement = document.querySelector(`.${className}`);
            if (statusElement) {
                statusElement.closest('.message-status').remove();
            }
        }

        // إضافة رسالة خطأ
        function addErrorMessage(message) {
            const messageDiv = document.createElement('div');
            messageDiv.className = 'message-status';
            messageDiv.innerHTML = `
                <div class="status-indicator status-failed">
                    ❌ ${message}
                </div>
            `;
            messagesContainer.appendChild(messageDiv);
            scrollToBottom();
        }

        // التحقق من حالة الرسالة
        async function checkMessageStatus(messageId) {
            const maxAttempts = 60; // 60 محاولة = 3 دقائق
            let attempts = 0;

            const checkInterval = setInterval(async () => {
                attempts++;
                
                try {
                    const response = await fetch(`/chat/check/${messageId}`);
                    const data = await response.json();

                    if (data.status === 'completed') {
                        clearInterval(checkInterval);
                        pendingMessages.delete(messageId);
                        addAiMessage(data.data.ai_response, messageId);
                    } else if (data.status === 'failed') {
                        clearInterval(checkInterval);
                        pendingMessages.delete(messageId);
                        
                        // إزالة مؤشر الكتابة
                        const typingIndicator = document.getElementById(`typing-${messageId}`);
                        if (typingIndicator) {
                            typingIndicator.remove();
                        }
                        
addErrorMessage('Failed to get a response');
}
} catch (error) {
    console.error('Error checking message status:', error);
}
                // إيقاف التحقق بعد انتهاء المحاولات
                if (attempts >= maxAttempts) {
                    clearInterval(checkInterval);
                    pendingMessages.delete(messageId);
                    
                    const typingIndicator = document.getElementById(`typing-${messageId}`);
                    if (typingIndicator) {
                        typingIndicator.remove();
                    }
                    
addErrorMessage('Request timed out');
                }
            }, 3000); // كل 3 ثوان
        }

        // تحميل الرسائل السابقة
        async function loadMessages() {
            try {
                const response = await fetch('/chat/messages');
                const data = await response.json();
                
                if (data.messages && data.messages.length > 0) {
                    emptyState.style.display = 'none';
                    
                    data.messages.forEach(message => {
                        addUserMessage(message.user_message);
                        if (message.ai_response) {
                            const messageDiv = document.createElement('div');
                            messageDiv.className = 'message message-ai';
                            messageDiv.innerHTML = `
                                <div class="message-content">
                                    ${escapeHtml(message.ai_response).replace(/\n/g, '<br>')}
                                    <button class="play-btn" onclick="playMessage('${message.ai_response.replace(/'/g, "\\'")}', this)" title=" play message">
                                        ▶️
                                    </button>
                                    <div class="voice-indicator">🔊</div>
                                </div>
                            `;
                            messagesContainer.appendChild(messageDiv);
                        }
                    });
                    scrollToBottom();
                }
            } catch (error) {
console.error('Error loading messages:', error);
            }
        }

        // مسح المحادثة
        async function clearChat() {
if (!confirm('Do you want to clear the conversation?')) return;

            // إيقاف أي كلام حالي
            if (currentSpeech) {
                speechSynthesis.cancel();
            }

            try {
                const response = await fetch('/chat/clear', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                if (response.ok) {
                    messagesContainer.innerHTML = `
<div class="empty-state" id="emptyState">
    <div class="icon">🎤</div>
    <h3>Welcome!</h3>
    <p>You can talk to me using your voice or type your message<br>Click the microphone to start</p>
</div>

                    `;
                    pendingMessages.clear();
                }
            } catch (error) {
console.error('Error clearing the conversation:', error);
            }
        }

        //    التمرير إلى الأسفل
        function scrollToBottom() {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        // تشفير HTML
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>
</body>
</html>