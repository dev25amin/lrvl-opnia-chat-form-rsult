<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>محلل النصوص الذكي</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: slideUp 0.8s ease-out;
        }

        @keyframes slideUp {
            from { transform: translateY(50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header h1 {
            font-size: 2.5rem;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .header p {
            color: #666;
            font-size: 1.1rem;
        }

        .language-selector {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .lang-btn {
            padding: 12px 25px;
            border: none;
            border-radius: 50px;
            background: linear-gradient(135deg, #f093fb, #f5576c);
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: bold;
            box-shadow: 0 5px 15px rgba(240, 147, 251, 0.3);
        }

        .lang-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(240, 147, 251, 0.4);
        }

        .lang-btn.active {
            background: linear-gradient(135deg, #667eea, #764ba2);
            transform: scale(1.1);
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
            font-size: 1.1rem;
        }

        .textarea-container {
            position: relative;
            overflow: hidden;
            border-radius: 15px;
        }

        .text-input {
            width: 100%;
            min-height: 200px;
            padding: 20px;
            border: 2px solid #e0e0e0;
            border-radius: 15px;
            font-size: 1.1rem;
            resize: vertical;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
            font-family: inherit;
        }

        .text-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 20px rgba(102, 126, 234, 0.2);
        }

        .char-counter {
            position: absolute;
            bottom: 10px;
            right: 15px;
            color: #888;
            font-size: 0.9rem;
            background: rgba(255, 255, 255, 0.9);
            padding: 5px 10px;
            border-radius: 20px;
        }

        .options-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .option-card {
            background: linear-gradient(135deg, #ffecd2, #fcb69f);
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            border: 2px solid transparent;
        }

        .option-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(252, 182, 159, 0.3);
        }

        .option-card.selected {
            border-color: #667eea;
            background: linear-gradient(135deg, #e0c3fc, #9bb5ff);
        }

        .option-card i {
            font-size: 2rem;
            margin-bottom: 10px;
            color: #333;
        }

        .submit-btn {
            width: 100%;
            padding: 18px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 1.2rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        }

        .submit-btn:active {
            transform: translateY(-1px);
        }

        .submit-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }

        .submit-btn:hover::before {
            left: 100%;
        }

        .features-list {
            display: flex;
            justify-content: space-around;
            margin-top: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #667eea;
            font-weight: bold;
        }

        .feature-item i {
            color: #f5576c;
        }

        .loading {
            display: none;
            text-align: center;
            margin-top: 20px;
        }

        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 10px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media (max-width: 768px) {
            .container {
                padding: 25px;
                margin: 10px;
            }
            
            .header h1 {
                font-size: 2rem;
            }
            
            .options-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container" id="mainContainer">
        <div class="header">
            <h1><i class="fas fa-brain"></i> محلل النصوص الذكي</h1>
            <p>أداة متقدمة لتحليل وترجمة النصوص بالذكاء الاصطناعي</p>
        </div>

        <div class="language-selector">
            <button class="lang-btn active" data-lang="ar" onclick="switchLanguage('ar')">
                <i class="fas fa-globe"></i> العربية
            </button>
            <button class="lang-btn" data-lang="en" onclick="switchLanguage('en')">
                <i class="fas fa-globe"></i> English
            </button>
            <button class="lang-btn" data-lang="fr" onclick="switchLanguage('fr')">
                <i class="fas fa-globe"></i> Français
            </button>
        </div>

<form id="analysisForm" method="POST" action="/analyze">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <div class="form-group">
        <label class="form-label" id="textLabel">
            <i class="fas fa-edit"></i> أدخل النص للتحليل
        </label>
        <div class="textarea-container">
            <textarea 
                name="text" 
                class="text-input" 
                id="textInput"
                placeholder="اكتب أو الصق النص هنا... يمكنك كتابة حتى 5000 حرف"
                maxlength="5000"
                oninput="updateCharCounter()"
                required
            ></textarea>
            <div class="char-counter" id="charCounter">0 / 5000</div>
        </div>
    </div>



    <div class="form-group">
        <label class="form-label">
            <i class="fas fa-globe-americas"></i> اختر لغة الترجمة المطلوبة
        </label>
        <select name="target_language" class="text-input" style="min-height: 50px; padding: 15px;">
            <option value="ar">العربية</option>
            <option value="en" selected>الإنجليزية</option>
            <option value="fr">الفرنسية</option>
            <option value="es">الإسبانية</option>
            <option value="de">الألمانية</option>
            <option value="it">الإيطالية</option>
            <option value="ja">اليابانية</option>
            <option value="ko">الكورية</option>
            <option value="zh">الصينية</option>
            <option value="ru">الروسية</option>
        </select>
    </div>

    <button type="submit" class="submit-btn" id="submitBtn">
        <i class="fas fa-magic"></i> ابدأ التحليل الذكي
    </button>

    <div class="loading" id="loadingDiv">
        <div class="spinner"></div>
        <p>جاري تحليل النص... يرجى الانتظار</p>
    </div>
</form>


        <div class="features-list">
            <div class="feature-item">
                <i class="fas fa-shield-alt"></i>
                <span>آمن ومحمي</span>
            </div>
            <div class="feature-item">
                <i class="fas fa-bolt"></i>
                <span>سريع وفعال</span>
            </div>
            <div class="feature-item">
                <i class="fas fa-star"></i>
                <span>دقة عالية</span>
            </div>
            <div class="feature-item">
                <i class="fas fa-mobile-alt"></i>
                <span>متجاوب مع الأجهزة</span>
            </div>
        </div>
    </div>

    <script>
        // Language switching functionality
        const translations = {
            ar: {
                title: 'محلل النصوص الذكي',
                subtitle: 'أداة متقدمة لتحليل وترجمة النصوص بالذكاء الاصطناعي',
                textLabel: 'أدخل النص للتحليل',
                placeholder: 'اكتب أو الصق النص هنا... يمكنك كتابة حتى 5000 حرف',
                submitBtn: 'ابدأ التحليل الذكي',
                loadingText: 'جاري تحليل النص... يرجى الانتظار'
            },
            en: {
                title: 'Smart Text Analyzer',
                subtitle: 'Advanced AI-powered text analysis and translation tool',
                textLabel: 'Enter text for analysis',
                placeholder: 'Type or paste your text here... up to 5000 characters',
                submitBtn: 'Start Smart Analysis',
                loadingText: 'Analyzing text... please wait'
            },
            fr: {
                title: 'Analyseur de Texte Intelligent',
                subtitle: 'Outil avancé d\'analyse et de traduction de texte par IA',
                textLabel: 'Entrez le texte à analyser',
                placeholder: 'Tapez ou collez votre texte ici... jusqu\'à 5000 caractères',
                submitBtn: 'Commencer l\'Analyse Intelligente',
                loadingText: 'Analyse du texte en cours... veuillez patienter'
            }
        };

        function switchLanguage(lang) {
            // Update active button
            document.querySelectorAll('.lang-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            document.querySelector(`[data-lang="${lang}"]`).classList.add('active');

            // Update text content
            const t = translations[lang];
            document.querySelector('.header h1').innerHTML = `<i class="fas fa-brain"></i> ${t.title}`;
            document.querySelector('.header p').textContent = t.subtitle;
            document.getElementById('textLabel').innerHTML = `<i class="fas fa-edit"></i> ${t.textLabel}`;
            document.getElementById('textInput').placeholder = t.placeholder;
            document.getElementById('submitBtn').innerHTML = `<i class="fas fa-magic"></i> ${t.submitBtn}`;
            document.querySelector('#loadingDiv p').textContent = t.loadingText;

            // Update direction
            if (lang === 'ar') {
                document.documentElement.setAttribute('dir', 'rtl');
                document.documentElement.setAttribute('lang', 'ar');
            } else {
                document.documentElement.setAttribute('dir', 'ltr');
                document.documentElement.setAttribute('lang', lang);
            }
        }

        function updateCharCounter() {
            const textInput = document.getElementById('textInput');
            const charCounter = document.getElementById('charCounter');
            const currentLength = textInput.value.length;
            charCounter.textContent = `${currentLength} / 5000`;
            
            if (currentLength > 4500) {
                charCounter.style.color = '#ff6b6b';
            } else {
                charCounter.style.color = '#888';
            }
        }

        function toggleOption(card, optionValue) {
            const input = card.querySelector('input[type="hidden"]');
            
            if (card.classList.contains('selected')) {
                card.classList.remove('selected');
                input.disabled = true;
            } else {
                card.classList.add('selected');
                input.disabled = false;
            }
        }

        // Form submission with loading animation
        document.getElementById('analysisForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const loadingDiv = document.getElementById('loadingDiv');
            
            submitBtn.style.display = 'none';
            loadingDiv.style.display = 'block';
            
            // Add animation to container
            document.getElementById('mainContainer').style.transform = 'scale(0.95)';
            document.getElementById('mainContainer').style.opacity = '0.7';
        });

        // Add floating animation to option cards
        document.querySelectorAll('.option-card').forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
            card.style.animation = 'slideUp 0.6s ease-out forwards';
        });

        // Add typing effect to placeholder
        let placeholderText = 'اكتب أو الصق النص هنا... يمكنك كتابة حتى 5000 حرف';
        let index = 0;
        
        function typeEffect() {
            if (index < placeholderText.length) {
                document.getElementById('textInput').placeholder = placeholderText.substring(0, index + 1);
                index++;
                setTimeout(typeEffect, 100);
            }
        }
        
        setTimeout(typeEffect, 1000);
    </script>
</body>
</html>