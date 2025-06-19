{{-- resources/views/form.blade.php --}}
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Text Processor</title>
    <style>
        :root {
            --bg-color: #1a1a1a;
            --container-bg: #2d2d2d;
            --text-color: #ffffff;
            --border-color: #444;
            --primary-color: #007cba;
            --primary-hover: #005a87;
            --note-bg: #1e3a5f;
            --note-border: #2196f3;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: var(--bg-color);
            color: var(--text-color);
            transition: all 0.3s ease;
        }

        .header-controls {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-bottom: 20px;
        }

        .control-btn {
            background: var(--container-bg);
            border: 1px solid var(--border-color);
            color: var(--text-color);
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: all 0.3s ease;
        }

        .control-btn:hover {
            background: var(--primary-color);
        }

        .container {
            background: var(--container-bg);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 20px rgba(0,0,0,0.3);
        }

        h1 {
            color: var(--text-color);
            text-align: center;
            margin-bottom: 30px;
        }

        textarea {
            width: 100%;
            padding: 15px;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-size: 16px;
            font-family: inherit;
            resize: vertical;
            box-sizing: border-box;
            background: var(--container-bg);
            color: var(--text-color);
        }

        textarea:focus {
            border-color: var(--primary-color);
            outline: none;
        }

        button {
            background: var(--primary-color);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 15px;
            width: 100%;
        }

        button:hover {
            background: var(--primary-hover);
        }

        .note {
            background: var(--note-bg);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid var(--note-border);
        }

        label {
            color: var(--text-color);
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
<body>
    {{-- Toggle Buttons --}}
    <div class="toggle-buttons">
        <button onclick="toggleLanguage()">🌐</button>
        <button onclick="toggleDarkMode()">🌓</button>
    </div>



    <div class="container">
        <h1 id="main-title">🤖 Smart Text Processor</h1>
       
        <div class="note">
            <strong id="note-title">💡 What the tool does:</strong>
            <ul id="feature-list">
                <li id="feature-1">Translates text to Arabic</li>
                <li id="feature-2">Paraphrases the text with similar meaning</li>
                <li id="feature-3">Extracts nouns and verbs</li>
            </ul>
        </div>
        
        <form action="{{ route('text.store') }}" method="POST">
            @csrf
            <label for="original_text" id="input-label"><strong>Enter the text to process:</strong></label>
            <textarea
                name="original_text"
                id="original_text"
                rows="8"
                placeholder="Type or paste your text here..."
                required
            >{{ old('original_text') }}</textarea>
           
            @error('original_text')
                <div style="color: red; margin-top: 5px;">{{ $message }}</div>
            @enderror
           
            <button type="submit" id="submit-btn">🚀 Start Processing</button>
        </form>
    </div>

    <script>
        let currentLang = 'en';
        
        const translations = {
            en: {
                title: '🤖 Smart Text Processor',
                noteTitle: '💡 What the tool does:',
                feature1: 'Translates text to Arabic',
                feature2: 'Paraphrases the text with similar meaning',
                feature3: 'Extracts nouns and verbs',
                inputLabel: 'Enter the text to process:',
                placeholder: 'Type or paste your text here...',
                submitBtn: '🚀 Start Processing',
                langText: 'العربية',
                themeText: 'Light'
            },
            ar: {
                title: '🤖 معالج النصوص الذكي',
                noteTitle: '💡 ما تقوم به الأداة:',
                feature1: 'ترجمة النص إلى العربية',
                feature2: 'إعادة صياغة النص بمعنى مشابه',
                feature3: 'استخراج الأسماء والأفعال',
                inputLabel: 'أدخل النص المراد معالجته:',
                placeholder: 'اكتب أو الصق النص هنا...',
                submitBtn: '🚀 ابدأ المعالجة',
                langText: 'English',
                themeText: 'فاتح'
            }
        };

        function toggleLanguage() {
            currentLang = currentLang === 'en' ? 'ar' : 'en';
            const isArabic = currentLang === 'ar';
            
            // Update HTML attributes
            document.documentElement.lang = currentLang;
            document.documentElement.dir = isArabic ? 'rtl' : 'ltr';
            
            // Update text content
            const trans = translations[currentLang];
            document.getElementById('main-title').textContent = trans.title;
            document.getElementById('note-title').textContent = trans.noteTitle;
            document.getElementById('feature-1').textContent = trans.feature1;
            document.getElementById('feature-2').textContent = trans.feature2;
            document.getElementById('feature-3').textContent = trans.feature3;
            document.getElementById('input-label').innerHTML = '<strong>' + trans.inputLabel + '</strong>';
            document.getElementById('original_text').placeholder = trans.placeholder;
            document.getElementById('submit-btn').textContent = trans.submitBtn;
            document.getElementById('lang-text').textContent = trans.langText;
            document.getElementById('theme-text').textContent = trans.themeText;
        }

        function toggleDarkMode() {
            const root = document.documentElement;
            const themeIcon = document.getElementById('theme-icon');
            const themeText = document.getElementById('theme-text');
            
            const isDark = root.style.getPropertyValue('--bg-color') === '#f5f5f5';
            
            if (isDark) {
                // Switch to dark mode
                root.style.setProperty('--bg-color', '#1a1a1a');
                root.style.setProperty('--container-bg', '#2d2d2d');
                root.style.setProperty('--text-color', '#ffffff');
                root.style.setProperty('--border-color', '#444');
                root.style.setProperty('--note-bg', '#1e3a5f');
                themeIcon.textContent = '☀️';
                themeText.textContent = currentLang === 'ar' ? 'فاتح' : 'Light';
            } else {
                // Switch to light mode
                root.style.setProperty('--bg-color', '#f5f5f5');
                root.style.setProperty('--container-bg', '#ffffff');
                root.style.setProperty('--text-color', '#333333');
                root.style.setProperty('--border-color', '#ddd');
                root.style.setProperty('--note-bg', '#e3f2fd');
                themeIcon.textContent = '🌙';
                themeText.textContent = currentLang === 'ar' ? 'داكن' : 'Dark';
            }
        }
    </script>
</body>
</html>