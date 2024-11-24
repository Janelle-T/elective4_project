import sys
import json
import pandas as pd
from transformers import AutoModelForSequenceClassification, AutoTokenizer
import torch

model_name = "cardiffnlp/twitter-roberta-base-sentiment-latest"
tokenizer = AutoTokenizer.from_pretrained(model_name)
model = AutoModelForSequenceClassification.from_pretrained(model_name)
model.eval()


def analyze_tweet(text, negative_threshold=0.8, positive_threshold=0.8):
    inputs = tokenizer(text, return_tensors="pt", padding=True, truncation=True, max_length=512)
    with torch.no_grad():
        outputs = model(**inputs)
        logits = outputs.logits.detach().cpu().numpy()
        probabilities = torch.nn.functional.softmax(torch.from_numpy(logits), dim=-1).numpy()[0]

        if probabilities[0] >= negative_threshold:
            predicted_label = "Negative"
        elif probabilities[2] >= positive_threshold:
            predicted_label = "Positive"
        else:
            predicted_label = "Neutral"

        results = {
            "text": text,
            "Negative": round(float(probabilities[0]), 7),
            "Neutral": round(float(probabilities[1]), 7),
            "Positive": round(float(probabilities[2]), 7),
            "predicted_label": predicted_label,
        }
        return results

if __name__ == "__main__":
    input_file = sys.argv[1]
    output_file = sys.argv[2]

    with open(input_file, 'r') as f:
        data = json.load(f)
        tweets = data['texts']

    results = [analyze_tweet(tweet) for tweet in tweets]

    with open(output_file, 'w') as f:
        json.dump({"results": results}, f, indent=2)