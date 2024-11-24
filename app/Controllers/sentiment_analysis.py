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

def analyze_tweet(text):
    inputs = tokenizer(text, return_tensors="pt", padding=True, truncation=True, max_length=512)
    with torch.no_grad():
        outputs = model(**inputs)
        logits = outputs.logits.detach().cpu().numpy()
        probabilities = torch.nn.functional.softmax(torch.from_numpy(logits), dim=-1).numpy()[0]

    results = {}
    results["text"] = text
    for label, prob in zip(labels, probabilities):
        results[label] = float(prob)  # Convert float32 to Python float
    results["predicted_label"] = labels[probabilities.argmax()]

    return results

if __name__ == "__main__":
    try:
        # Read tweets from CSV. Assume your CSV has a column named 'tweet'
        df = pd.read_csv("D:/Documents/Git/elective4_project/writable/data.csv")

        tweets = df["text"].tolist()  # Get tweets as a list

        results = []
        for text in tweets:
            analysis = analyze_tweet(text)
            results.append(analysis)

        # Ensure all data is JSON serializable
        print(json.dumps(results, indent=2))

    except FileNotFoundError:
        print("Error: data.csv not found.")
    except KeyError:
        print("Error: 'tweet' column not found in data.csv")
    except pd.errors.EmptyDataError:
        print("Error: data.csv is empty.")
    except Exception as e:
        print(f"An unexpected error occurred: {e}")
