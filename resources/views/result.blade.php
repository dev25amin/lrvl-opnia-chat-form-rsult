{{-- =================================================================== --}}
{{-- resources/views/result.blade.php --}}
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Text Processing Results</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        h2 {
            color: #007cba;
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 10px;
            margin-top: 30px;
        }
        .content-box {
            background: #d0d0d0;
            padding: 20px;
            border-radius: 8px;
            margin: 15px 0;
            border-left: 4px solid #007cba;
            line-height: 1.6;
        }
        .status-pending {
            background: #ffcdcd;
            color: #856404;
            border-left-color: #ffc107;
        }
        .status-processing {
            background: #0e66c4;
            color: #004085;
            border-left-color: #007bff;
        }
        .status-completed {
            background: #1b7430;
            color: #155724;
            border-left-color: #28a745;
        }
        .status-failed {
            background: #531319;
            color: #721c24;
            border-left-color: #dc3545;
        }
        .word-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }
        .word-tag {
            background: #007cba;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 14px;
            display: inline-block;
        }
        .loading {
            text-align: center;
            padding: 20px;
        }
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #007cba;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 2s linear infinite;
            margin: 0 auto 15px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .back-btn {
            background: #6c757d;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
        }
        .back-btn:hover {
            background: #545b62;
        }
        .refresh-btn {
            background: #17a2b8;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
        }
    </style>
    
    <style>
        body {
            background-color: #f0f0f0;
            color: #111;
            transition: background-color 0.3s, color 0.3s;
        }

        h1, h2, h3, h4, h5, h6 {
            color: inherit;
        }

        .card, .section, .box, .container {
            background-color: #ffffff;
            border: 1px solid #ccc;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 1rem;
            margin-bottom: 1rem;
            transition: background-color 0.3s, color 0.3s, border-color 0.3s, box-shadow 0.3s;
        }

        body.dark-mode {
            background-color: #1e1e1e;
            color: #f0f0f0;
        }

        body.dark-mode .card,
        body.dark-mode .section,
        body.dark-mode .box,
        body.dark-mode .container {
            background-color: #2c2c2c;
            border-color: #444;
            box-shadow: 0 2px 6px rgba(255,255,255,0.05);
        }

        body.dark-mode .content-box {
            background-color: #151515;
            border-color: #444;
            box-shadow: 0 2px 6px rgba(255,255,255,0.05);
        }

        .toggle-buttons {
            position: fixed;
            top: 10px;
            right: 10px;
            display: flex;
            gap: 10px;
            z-index: 999;
        }

        .toggle-buttons button {
            padding: 6px 10px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            background-color: #6e6e6e;
        }

        body.dark-mode .toggle-buttons button {
            background-color: #444;
            color: #f0f0f0;
        }
    </style>
</head>
<body class="dark-mode" dir="ltr">

    {{-- Toggle Buttons --}}
    <div class="toggle-buttons">
        <button onclick="toggleLanguage()">🌐</button>
        <button onclick="toggleDarkMode()">🌓</button>
    </div>



    <div class="container">
        <h1>📊 Text Processing Results</h1>

        <div id="status-section">
            @if($text->status === 'pending')
                <div class="content-box status-pending">
                    <div class="loading">
                        <div class="spinner"></div>
                        <strong>⏳ Waiting for processing...</strong>
                        <br><small>The text is in the queue</small>
                    </div>
                </div>
            @elseif($text->status === 'processing')
                <div class="content-box status-processing">
                    <div class="loading">
                        <div class="spinner"></div>
                        <strong>⚙️ Processing...</strong>
                        <br><small>The text is currently being analyzed</small>
                    </div>
                </div>
            @elseif($text->status === 'failed')
                <div class="content-box status-failed">
                    <strong>❌ Processing failed</strong>
                    <br><small>An error occurred while processing the text</small>
                    <button class="refresh-btn" onclick="location.reload()">🔄 Retry</button>
                </div>
            @endif
        </div>

        <div id="results-section" style="{{ $text->status !== 'completed' ? 'display:none' : '' }}">
            <h2>📝 Original Text</h2>
            <div class="content-box">
                {{ $text->original_text }}
            </div>

            <h2>🌍 Arabic Translation</h2>
            <div class="content-box">
                {{ $text->translated_text ?: 'Translation not available yet...' }}
            </div>

            <h2>✏️ Rewritten Text</h2>
            <div class="content-box">
                {{ $text->rewritten_text ?: 'Rewriting not completed yet...' }}
            </div>

            <h2>📚 Extracted Nouns</h2>
            <div class="content-box">
                @if($text->nouns && count($text->nouns) > 0)
                    <div class="word-list">
                        @foreach($text->nouns as $noun)
                            <span class="word-tag">{{ $noun }}</span>
                        @endforeach
                    </div>
                @else
                    <em>No nouns extracted yet...</em>
                @endif
            </div>

            <h2>🎯 Extracted Verbs</h2>
            <div class="content-box">
                @if($text->verbs && count($text->verbs) > 0)
                    <div class="word-list">
                        @foreach($text->verbs as $verb)
                            <span class="word-tag">{{ $verb }}</span>
                        @endforeach
                    </div>
                @else
                    <em>No verbs extracted yet...</em>
                @endif
            </div>
        </div>

        <a href="{{ route('text.create') }}" class="back-btn">⬅️ Process New Text</a>

        @if($text->status !== 'completed' && $text->status !== 'failed')
        <script>
            // Check status every 3 seconds
            function checkStatus() {
                fetch('/api/status/{{ $text->id }}')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'completed' || data.status === 'failed') {
                        location.reload();
                    }
                })
                .catch(error => console.log('Error checking status:', error));
            }

            setInterval(checkStatus, 3000);
            setTimeout(checkStatus, 2000);
        </script>
        @endif
    </div>
    {{-- JavaScript --}}
    <script>

        // Dark Mode toggle
        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
            localStorage.setItem('darkMode', document.body.classList.contains('dark-mode'));
        }

    const translations = {
        en: {
            title: "📊 Text Processing Results",
            waiting: "⏳ Waiting for processing...",
            queue: "The text is in the queue",
            processing: "⚙️ Processing...",
            analyzing: "The text is currently being analyzed",
            failed: "❌ Processing failed",
            error: "An error occurred while processing the text",
            retry: "🔄 Retry",
            original: "📝 Original Text",
            translation: "🌍 Arabic Translation",
            rewrite: "✏️ Rewritten Text",
            nouns: "📚 Extracted Nouns",
            verbs: "🎯 Extracted Verbs",
            no_nouns: "No nouns extracted yet...",
            no_verbs: "No verbs extracted yet...",
            new_text: "⬅️ Process New Text"
        },
        ar: {
            title: "📊 نتائج معالجة النص",
            waiting: "⏳ في انتظار المعالجة...",
            queue: "النص في قائمة الانتظار",
            processing: "⚙️ جاري المعالجة...",
            analyzing: "يتم تحليل النص الآن",
            failed: "❌ فشلت المعالجة",
            error: "حدث خطأ أثناء معالجة النص",
            retry: "🔄 إعادة المحاولة",
            original: "📝 النص الأصلي",
            translation: "🌍 الترجمة للعربية",
            rewrite: "✏️ النص المعاد صياغته",
            nouns: "📚 الأسماء المستخرجة",
            verbs: "🎯 الأفعال المستخرجة",
            no_nouns: "لم يتم استخراج أسماء بعد...",
            no_verbs: "لم يتم استخراج أفعال بعد...",
            new_text: "⬅️ معالجة نص جديد"
        }
    };

    function applyTranslations(lang) {
        const t = translations[lang];

        document.querySelector("h1").textContent = t.title;
        document.querySelectorAll("h2")[0].textContent = t.original;
        document.querySelectorAll("h2")[1].textContent = t.translation;
        document.querySelectorAll("h2")[2].textContent = t.rewrite;
        document.querySelectorAll("h2")[3].textContent = t.nouns;
        document.querySelectorAll("h2")[4].textContent = t.verbs;

        const backBtn = document.querySelector(".back-btn");
        if (backBtn) backBtn.textContent = t.new_text;

        const statusBox = document.getElementById("status-section");
        if (statusBox) {
            if (lang === 'ar') {
                statusBox.innerHTML = statusBox.innerHTML
                    .replace("Waiting for processing...", t.waiting)
                    .replace("The text is in the queue", t.queue)
                    .replace("Processing...", t.processing)
                    .replace("The text is currently being analyzed", t.analyzing)
                    .replace("Processing failed", t.failed)
                    .replace("An error occurred while processing the text", t.error)
                    .replace("Retry", t.retry);
            } else {
                statusBox.innerHTML = statusBox.innerHTML
                    .replace(t.waiting, "Waiting for processing...")
                    .replace(t.queue, "The text is in the queue")
                    .replace(t.processing, "Processing...")
                    .replace(t.analyzing, "The text is currently being analyzed")
                    .replace(t.failed, "Processing failed")
                    .replace(t.error, "An error occurred while processing the text")
                    .replace(t.retry, "Retry");
            }
        }
    }

    function toggleLanguage() {
        const html = document.documentElement;
        const body = document.body;
        const currentLang = html.lang;

        const newLang = currentLang === 'en' ? 'ar' : 'en';
        html.lang = newLang;
        body.dir = newLang === 'ar' ? 'rtl' : 'ltr';
        localStorage.setItem('lang', newLang);

        applyTranslations(newLang);
    }

    document.addEventListener('DOMContentLoaded', () => {
        const savedLang = localStorage.getItem('lang') || 'en';
        const savedDarkMode = localStorage.getItem('darkMode');

        document.documentElement.lang = savedLang;
        document.body.dir = savedLang === 'ar' ? 'rtl' : 'ltr';

        if (savedDarkMode === 'true') {
            document.body.classList.add('dark-mode');
        }

        applyTranslations(savedLang);
    });


    </script>

</body>
</html>
