import pandas as pd  # Import pandas for CSV reading
from transformers import AutoModelForSequenceClassification, AutoTokenizer
import torch
import json


model_name = "cardiffnlp/twitter-roberta-base-sentiment-latest"
tokenizer = AutoTokenizer.from_pretrained(model_name)
model = AutoModelForSequenceClassification.from_pretrained(model_name)
model.eval()

# Check the model card to confirm these labels; they might be different!
labels = ["Negative", "Neutral", "Positive"]

def analyze_tweet(text, negative_threshold=0.8, positive_threshold=0.85):
    inputs = tokenizer(text, return_tensors="pt", padding=True, truncation=True, max_length=512)
    with torch.no_grad():
        outputs = model(**inputs)
        logits = outputs.logits.detach().cpu().numpy()
        probabilities = torch.nn.functional.softmax(torch.from_numpy(logits), dim=-1).numpy()[0]

        # Custom Threshold Logic
        if probabilities[0] >= negative_threshold:
            predicted_label = "Negative"
        elif probabilities[2] >= positive_threshold:
            predicted_label = "Positive"
        else:
            predicted_label = "Neutral"

        results = {
            "text": text,
            "Negative": float(probabilities[0]),
            "Neutral": float(probabilities[1]),
            "Positive": float(probabilities[2]),
            "predicted_label": predicted_label,
        }
        return results

if __name__ == "__main__":
    try:
        df = pd.read_csv("D:/Documents/Git/elective4_project/writable/data.csv")
        tweets = df["text"].tolist()

        # Now pass thresholds to analyze_tweet
        results = [analyze_tweet(tweet, negative_threshold=0.8, positive_threshold=0.8) for tweet in tweets]

        print(json.dumps({"results": results}, indent=2))

    except FileNotFoundError:
        print(json.dumps({"error": "Error: data.csv not found."}))

    except KeyError:
        print("Error: 'tweet' column not found in data.csv")
    except pd.errors.EmptyDataError:
        print("Error: data.csv is empty.")
    except Exception as e:
        print(f"An unexpected error occurred: {e}")
