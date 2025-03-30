import pandas as pd
import numpy as np
from sklearn.model_selection import train_test_split
from sklearn.ensemble import RandomForestClassifier
import joblib
import ipaddress
import os
import sys
from datetime import datetime

def main():
    try:
        # 1. SETUP PATHS
        script_dir = os.path.dirname(os.path.abspath(__file__))
        csv_path = os.path.join(script_dir, "fraud_data_from_activity_logs.csv")
        model_path = os.path.join(script_dir, "fraud_model.pkl")
        encoder_path = os.path.join(script_dir, "label_encoder.pkl")

        # 2. LOAD DATA
        if not os.path.exists(csv_path):
            raise FileNotFoundError(f"Data file not found at {csv_path}")
            
        df = pd.read_csv(csv_path)

        # 3. FEATURE ENGINEERING
        event_label_mapping = {event: idx for idx, event in enumerate(df['event'].unique())}
        df['event_numeric'] = df['event'].map(event_label_mapping)
        
        def ip_to_numeric(ip):
            try:
                return int(ipaddress.IPv4Address(ip))
            except:
                return 0
        df['ip_address_numeric'] = df['ip_address'].apply(ip_to_numeric)

        # 4. PREPARE DATA
        X = df[['user_id', 'event_numeric', 'ip_address_numeric', 'hour_of_day', 'day_of_week']]
        y = df['is_fraud']
        X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

        # 5. TRAIN MODEL WITH IMPROVED PARAMETERS
        model = RandomForestClassifier(
            n_estimators=200,  # Increased from 100
            random_state=42,
            max_depth=15,      # Added depth limit
            min_samples_split=5,
            class_weight='balanced'  # Handles imbalanced data
        )
        model.fit(X_train, y_train)

        # 6. EVALUATE MODEL
        accuracy = model.score(X_test, y_test)
        
        # 7. SAVE MODEL
        joblib.dump(model, model_path)
        joblib.dump(event_label_mapping, encoder_path)

        # 8. GENERATE PREDICTIONS
        predictions = model.predict(X_test)
        pred_df = pd.DataFrame({
            'ID': X_test.index,
            'User': X_test['user_id'],
            'Event': X_test['event_numeric'],
            'IP Address': X_test['ip_address_numeric'],
            'Behavior': np.where(predictions == 1, 'SUSPICIOUS', 'Normal', 'Anomaly'),
            'Risk Level': np.where(predictions == 1, 'High', 'Low', 'Medium'),
            'Confidence': np.where(predictions == 1, '85%', '15%', '50%', '25%'),
            'Decision': np.where(predictions == 1, 'CONFIRMED FRAUD', 'CLEAN', 'ANOMALY'),
            'Action': np.where(predictions == 1, 'Block user', 'No action', 'Need manual review'),
        })

        # 9. OUTPUT RESULTS
        if accuracy >= 0.9:
            print(f"SUCCESS: Model updated with accuracy {accuracy:.0%}")
        else:
            print(f"WARNING: Model accuracy {accuracy:.0%} is below 90% target")
        
        print("\nPrediction Results:")
        print(pred_df.to_string(index=False))
        
        return 0
        
    except Exception as e:
        print(f"ERROR: {str(e)}", file=sys.stderr)
        return 1

if __name__ == "__main__":
    sys.exit(main())