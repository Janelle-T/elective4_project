import sys
import json
import nltk
from nltk.sentiment.vader import SentimentIntensityAnalyzer

# Ensure required NLTK resources are downloaded
nltk.download('vader_lexicon')
nltk.download('punkt')

def analyze_sentiment(text):
    analyzer = SentimentIntensityAnalyzer()
    # Analyze sentiment
    sentiment_scores = analyzer.polarity_scores(text)

    # Tokenize the text
    tokens = nltk.word_tokenize(text)

    # Get sentiment label (Positive, Neutral, Negative)
    sentiment = "Neutral"
    if sentiment_scores['compound'] >= 0.05:
        sentiment = "Positive"
    elif sentiment_scores['compound'] <= -0.05:
        sentiment = "Negative"
    
    return {
        'tokens': tokens,
        'sentiment': sentiment,
        'scores': sentiment_scores
    }

if __name__ == "__main__":
    input_text = sys.argv[1]  # Get text from command-line argument
    result = analyze_sentiment(input_text)

    # Print JSON response
    print(json.dumps(result))
