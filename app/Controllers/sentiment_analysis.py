import transformers
import sys
import json
import os

# Load pre-trained model
model_name = "distilbert-base-uncased-finetuned-sst-2-english"  # Or a more suitable model
tokenizer = transformers.AutoTokenizer.from_pretrained(model_name)
model = transformers.AutoModelForSequenceClassification.from_pretrained(model_name)
device = "cpu"  # Change to "cuda" if you have a CUDA-enabled GPU
model.to(device)

# Add logging for starting the analysis
print(json.dumps({"log": "Starting analysis"}))  # This logs that the sentiment analysis has started

def analyze_sentiment(text):
    try:
        # Log the incoming text for analysis
        print(json.dumps({"log": f"Analyzing text: {text[:30]}..."}) )  # Logs the first 30 characters of the text
        inputs = tokenizer(text, return_tensors="pt", truncation=True, padding=True, max_length=512)
        inputs = {k: v.to(device) for k, v in inputs.items()}
        outputs = model(**inputs)
        prediction = outputs.logits.argmax().item()
        sentiment = "positive" if prediction == 1 else "negative"
        return {"sentiment": sentiment}
    except FileNotFoundError:
        print(json.dumps({"log": "Model file not found"}))
        return {"error": "Model file not found"}
    except transformers.modeling_utils.TruncationException as e:
        print(json.dumps({"log": f"Text too long: {e}"}))
        return {"error": f"Input text too long: {e}"}
    except Exception as e:
        print(json.dumps({"log": f"Error occurred: {str(e)}"}))
        return {"error": f"An unexpected error occurred: {e}"}

if __name__ == "__main__":
    if len(sys.argv) != 3:
        print(json.dumps({"error": "Incorrect number of arguments"}))
        sys.exit(1)

    input_file = sys.argv[1]
    output_file = sys.argv[2]

    if not os.path.exists(input_file):
        print(json.dumps({"error": f"Input file not found: {input_file}"}))
        sys.exit(1)

    try:
        # Read input file
        with open(input_file, 'r', encoding='utf-8') as f:
            text = f.read()
            result = analyze_sentiment(text)
        
        # Write output to JSON file
        with open(output_file, 'w', encoding='utf-8') as outfile:
            json.dump(result, outfile)
        
        # Print result to the console for debugging
        print(json.dumps(result))  # This logs the result to the console as well
    except Exception as e:
        print(json.dumps({"error": str(e)}))
