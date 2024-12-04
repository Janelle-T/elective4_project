import nltk
import sys
import json
from nltk.tokenize import word_tokenize
from nltk.corpus import stopwords

# Ensure necessary resources are downloaded
try:
    nltk.download('punkt', quiet=True)
    nltk.download('stopwords', quiet=True)
except Exception as e:
    print(f"Error downloading NLTK resources: {e}")
    sys.exit(1)

# Expanded positive, negative, and neutral word lists
positive_words = [
    'good', 'great', 'awesome', 'fantastic', 'amazing', 'excellent', 'positive', 'happy',
    'joyful', 'love', 'best', 'wonderful', 'satisfied', 'incredible', 'delightful', 'superb',
    'beautiful', 'pleasant', 'brilliant', 'inspiring', 'remarkable', 'outstanding', 'excellent',
    'awesome', 'perfect', 'grateful', 'blessed', 'radiant', 'cheerful', 'affectionate', 'wonderful'
]

negative_words = [
    'bad', 'terrible', 'awful', 'poor', 'disappointing', 'horrible', 'angry', 'sad', 'frustrated',
    'hate', 'worst', 'unpleasant', 'upset', 'unhappy', 'miserable', 'depressed', 'distressing', 
    'tragic', 'heartbroken', 'dreadful', 'annoying', 'painful', 'boring', 'hard', 'disappointing', 
    'regret', 'lost', 'tired', 'failing', 'unhelpful', 'strict', 'failure', 'hopeless'
]

neutral_words = [
    'okay', 'fine', 'average', 'normal', 'neutral', 'so-so', 'indifferent', 'typical', 'usual',
    'mediocre', 'fair', 'predictable', 'standard', 'ordinary', 'meh', 'acceptable', 'alright', 'unremarkable'
]

# Include stopwords from NLTK
stop_words = set(stopwords.words('english'))

def analyze_sentiment(comment):
    if not comment.strip():
        return "Error: No comment provided"
    
    try:
        # Tokenize the comment into words
        tokens = word_tokenize(comment.lower())
        
        # Filter out stopwords
        tokens = [word for word in tokens if word not in stop_words]

        # Count positive, negative, and neutral words
        positive_count = sum(1 for word in tokens if word in positive_words)
        negative_count = sum(1 for word in tokens if word in negative_words)
        neutral_count = sum(1 for word in tokens if word in neutral_words)

        # Determine sentiment based on word counts
        if positive_count > negative_count:
            return 'Positive'
        elif negative_count > positive_count:
            return 'Negative'
        else:
            return 'Neutral'
    except Exception as e:
        return f"Error: Sentiment analysis failed: {str(e)}"

def tokenize_comment(comment):
    try:
        # Tokenize the comment into words
        tokens = word_tokenize(comment.lower())
        # Filter out stopwords
        tokens = [word for word in tokens if word not in stop_words]
        return json.dumps(tokens)  # Return tokens as JSON string
    except Exception as e:
        return json.dumps({"error": f"Error in tokenization: {str(e)}"})

if __name__ == "__main__":
    # Fetch comment passed via standard input
    comment = sys.stdin.read().strip()

    if comment:
        # Call functions and output the result
        tokens = tokenize_comment(comment)
        sentiment = analyze_sentiment(comment)
        print(f"Tokens: {tokens}")
        print(f"Sentiment: {sentiment}")
    else:
        print("Error: No comment provided.")
