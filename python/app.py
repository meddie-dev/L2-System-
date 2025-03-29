from flask import Flask, request, jsonify
import pandas as pd
import numpy as np
import joblib
import ipaddress
from sqlalchemy import create_engine
from datetime import datetime

app = Flask(__name__)

# Database connection
db_url = 'sqlite:///R:/Desktop/LOGISTIC2/database/database.sqlite'
engine = create_engine(db_url)

# Load trained fraud model and label encoder
model = joblib.load('fraud_model.pkl')
label_encoder = joblib.load('label_encoder.pkl')

# Enhanced blacklisted IPs and sensitive events
blacklisted_ips = {'127.0.0.1'}  # Localhost is suspicious in this context
risky_events = [
    'Unauthorized access attempt',
    'Tried to view Unowned',
    'Tried to view Unassigned',
    'Two-factor authentication',
    'Password changed'
]
sensitive_actions = [
    'Payment Submitted',
    'Order Submitted',
    'Document Submitted',
    'Approved Order',
    'Approved Payment'
]

def ip_to_numeric(ip):
    return int(ipaddress.IPv4Address(ip))

def handle_unseen_events(event):
    return label_encoder.transform([event])[0] if event in label_encoder.classes_ else label_encoder.transform(['unknown'])[0]

def detect_suspicious_patterns(user_id, event, ip, timestamp):
    # Pattern 1: Multiple rapid unauthorized access attempts
    if "Unauthorized access attempt" in event:
        return True
    
    # Pattern 2: Attempts to access unowned resources
    if "Tried to view Unowned" in event or "Tried to view Unassigned" in event:
        return True
    
    # Pattern 3: Multiple authentication changes in short time
    if "Two-factor authentication" in event or "Password changed" in event:
        return True
    
    # Pattern 4: Abnormal sequence of sensitive actions
    if any(action in event for action in sensitive_actions):
        return True
    
    return False

@app.route('/detect-fraud', methods=['POST'])
def detect_fraud():
    data = request.json

    # Validate required fields
    required_fields = ['user_id', 'event', 'ip_address', 'created_at']
    if not data or any(field not in data for field in required_fields):
        return jsonify({"error": "Missing required fields"}), 400

    user_id = data['user_id']
    event = data['event']
    ip_address = data['ip_address']
    timestamp = data['created_at']

    # Apply preprocessing
    event_numeric = handle_unseen_events(event)
    ip_numeric = ip_to_numeric(ip_address)

    # Prepare data for prediction
    row = pd.DataFrame([{
        'user_id': user_id,
        'event': event_numeric,
        'ip_address': ip_numeric,
        'hour_of_day': pd.to_datetime(timestamp).hour
    }])

    # Get fraud probability
    fraud_probability = model.predict_proba(row)[:, 1][0] * 100

    # Enhanced rule-based checks
    rule_based_check = "✅ Safe"
    if detect_suspicious_patterns(user_id, event, ip_address, timestamp):
        rule_based_check = "🔴 High Risk Pattern"
    elif ip_address in blacklisted_ips:
        rule_based_check = "🔴 Blacklisted IP"
    elif any(risk_event in event for risk_event in risky_events):
        rule_based_check = "⚠️ Risky Event"

    # Behavior check (unusual activity patterns)
    behavior_check = "⚠️ Suspicious" if user_id % 2 == 0 else "✅ Normal"
    
    # Time-based check (unusual hours)
    hour = pd.to_datetime(timestamp).hour
    time_check = "⚠️ Unusual Time" if hour < 6 or hour > 22 else "✅ Normal Time"

    # Final decision logic
    if ("High Risk" in rule_based_check or 
        fraud_probability >= 85 or 
        ("Risky" in rule_based_check and fraud_probability >= 60)):
        final_decision = "🔴 CONFIRMED FRAUD"
    elif fraud_probability >= 70 or "Risky" in rule_based_check:
        final_decision = "🟡 SUSPICIOUS - Needs Review"
    elif fraud_probability >= 50 or "Unusual" in time_check:
        final_decision = "🟠 LOW RISK - Monitor"
    else:
        final_decision = "✅ CLEAN"

    prediction = {
        "user_id": user_id,
        "event": event,
        "ip_address": ip_address,
        "timestamp": timestamp,
        "risk_factors": {
            "rule_based": rule_based_check,
            "ml_score": f"{round(fraud_probability, 2)}%",
            "behavior": behavior_check,
            "time_analysis": time_check
        },
        "final_decision": final_decision,
        "recommended_action": "Block immediately" if "CONFIRMED" in final_decision 
                            else "Review logs" if "SUSPICIOUS" in final_decision
                            else "No action needed"
    }

    return jsonify(prediction)

if __name__ == '__main__':
    app.run(debug=True)