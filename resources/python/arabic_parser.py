import sys
import json

try:
    input_text = sys.argv[1]
except IndexError:
    print(json.dumps({"error": "No input text provided"}))
    sys.exit(1)

# مثال بسيط جدا
words = input_text.strip().split()

result = []
for word in words:
    result.append({
        "word": word,
        "type": "اسم" if word.startswith("ال") else "فعل" if word.endswith("َ") else "حرف",
        "role": "فاعل" if word.endswith("ُ") else "مفعول به" if word.endswith("َ") else "-"
    })

print(json.dumps(result, ensure_ascii=False))
