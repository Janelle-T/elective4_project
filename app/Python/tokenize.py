import nltk
from nltk.tokenize import word_tokenize
from nltk.corpus import stopwords
import json
import sys

# Ensure necessary resources are downloaded
try:
    nltk.download('punkt', quiet=True)
    nltk.download('stopwords', quiet=True)
except Exception as e:
    print(f"Error downloading NLTK resources: {e}")
    sys.exit(1)

# Custom stopwords (you can add more words if necessary)
custom_stopwords = set([
    'i', 'me', 'my', 'myself', 'we', 'our', 'ours', 'ourselves', 'you', 'your', 'yours',
    'yourself', 'yourselves', 'he', 'him', 'his', 'himself', 'she', 'her', 'hers', 'herself',
    'it', 'its', 'itself', 'they', 'them', 'their', 'theirs', 'themselves', 'what', 'which',
    'who', 'whom', 'this', 'that', 'these', 'those', 'am', 'is', 'are', 'was', 'were', 'be', 
    'been', 'being', 'have', 'has', 'had', 'having', 'do', 'does', 'did', 'doing', 'a', 'an', 
    'the', 'and', 'but', 'if', 'or', 'because', 'as', 'until', 'while', 'of', 'at', 'by', 'for', 
    'with', 'about', 'against', 'between', 'into', 'through', 'during', 'before', 'after', 
    'above', 'below', 'to', 'from', 'up', 'down', 'in', 'out', 'on', 'off', 'over', 'under', 
    'again', 'further', 'then', 'once', 'here', 'there', 'when', 'where', 'why', 'how', 'all', 
    'any', 'both', 'each', 'few', 'more', 'most', 'other', 'some', 'such', 'no', 'nor', 'not', 
    'only', 'own', 'same', 'so', 'than', 'too', 'very', 's', 't', 'can', 'will', 'just', 'don', 
    'should', 'now', 'd', 'll', 'm', 'o', 're', 've', 'y', 'ain', 'aren', 'couldn', 'didn', 'doesn', 
    'hadn', 'hasn', 'haven', 'isn', 'ma', 'mightn', 'mustn', 'needn', 'shan', 'shouldn', 'wasn', 'weren', 'won', 'wouldn'
])

# Function to tokenize text and remove stopwords
def tokenize_text(comment):
    if not comment.strip():
        return []  # Return empty list if text is empty

    try:
        # Tokenize the input text
        tokens = word_tokenize(comment)
    
        # Get the default English stopwords from NLTK and combine with custom stopwords
        stop_words = set(stopwords.words('english')).union(custom_stopwords)
    
        # Filter out stopwords from the tokens
        filtered_tokens = [word for word in tokens if word.lower() not in stop_words]
    
        return filtered_tokens
    except Exception as e:
        return {"error": f"Tokenization failed: {str(e)}"}

# Main function to handle the comment input and call the tokenization function
if __name__ == "__main__":
    # Check if a comment is provided as a command-line argument
    if len(sys.argv) < 2:
        print("Error: No comment provided.")
        sys.exit(1)

    # Retrieve the comment from the command-line arguments
    comment = sys.argv[1]
    
    # Tokenize the text and get the filtered tokens
    tokens = tokenize_text(comment)

    # Prepare the result as a JSON object
    result = {
        "tokens": tokens
    }
    
    # Output the result as JSON
    print(json.dumps(result, indent=4))
