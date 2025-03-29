import sys
import json
import pandas as pd
import joblib
import traceback

def load_model(model_path):
    """Load the trained model from a file."""
    try:
        model = joblib.load(model_path)
        return model
    except Exception as e:
        print(json.dumps({"error": f"Failed to load model: {str(e)}"}))
        sys.exit(1)

def preprocess_data(data, model):
    """Ensure the input data matches the trained model's feature set."""
    df = pd.DataFrame(data)

    # Drop columns that were not used during training
    columns_to_remove = ["id", "created_at", "updated_at", "is_fraud", "fraud_likelihood"]  
    df = df.drop(columns=[col for col in columns_to_remove if col in df], errors='ignore')

    # Convert categorical features into numeric
    if 'event' in df.columns:
        df['event_numeric'] = df['event'].astype('category').cat.codes
        df = df.drop(columns=['event'])
    
    if 'ip_address' in df.columns:
        df['ip_address_numeric'] = df['ip_address'].apply(lambda x: hash(x) % 100000)
        df = df.drop(columns=['ip_address'])

    # 🔥 **Align features dynamically with trained model**
    try:
        expected_features = model.feature_names_in_  # Get feature names from the trained model
        df = df.reindex(columns=expected_features, fill_value=0)
    except AttributeError:
        print(json.dumps({"error": "Model does not have feature names stored. Please retrain with feature names."}))
        sys.exit(1)

    return df

def predict_fraud(model, data):
    """Make fraud predictions on given data."""
    try:
        df = pd.DataFrame(data)

        # Keep original data for final output
        original_data = df[["id", "user_id", "event", "ip_address"]].copy()

        # Preprocess Data
        df = preprocess_data(data, model)  

        # Make Predictions
        predictions = model.predict(df)
        df['fraud_prediction'] = predictions.tolist()

        # Assign risk labels
        df['behavior'] = df['fraud_prediction'].apply(lambda x: "SUSPICIOUS" if x == 1 else "Normal")
        df['rule_based'] = df['fraud_prediction'].apply(lambda x: "High Risk" if x == 1 else "Normal")
        df['machine_learning'] = df['fraud_prediction'].apply(lambda x: "85%" if x == 1 else "0%")
        df['final_decision'] = df['fraud_prediction'].apply(lambda x: "CONFIRMED FRAUD" if x == 1 else "CLEAN")
        df['action'] = df['fraud_prediction'].apply(lambda x: "Block user" if x == 1 else "No action")

        # Merge original data back
        df = pd.concat([original_data, df[["behavior", "rule_based", "machine_learning", "final_decision", "action"]]], axis=1)

        return df.to_dict(orient='records')
    except Exception as e:
        print(json.dumps({"error": f"Prediction error: {str(e)}"}))
        sys.exit(1)


def main():
    if len(sys.argv) < 2:
        print(json.dumps({"error": "Missing input file argument."}))
        sys.exit(1)
    
    input_file = sys.argv[1]
    model_path = "R:/Desktop/LOGISTIC2/python/fraud_model.pkl"  # Adjust model path
    
    try:
        # Load data from JSON file
        with open(input_file, 'r') as f:
            fraud_data = json.load(f)
        
        # Load trained model
        model = load_model(model_path)
        
        # Make predictions
        results = predict_fraud(model, fraud_data)
        
        # Print predictions as JSON for PHP to read
        print(json.dumps(results))
        
    except Exception as e:
        print(json.dumps({"error": f"Unexpected error: {str(e)}"}))
        traceback.print_exc()
        sys.exit(1)

if __name__ == "__main__":
    main()

