# 1. IMPORT REQUIRED LIBRARIES
import pandas as pd
import numpy as np
from sqlalchemy import create_engine
from datetime import datetime, timedelta
import os
import sys

# 2. SETUP DATABASE CONNECTION
try:
    # Use raw string (r'') for Windows paths
    db_path = r'R:\Desktop\LOGISTIC2\database\database.sqlite'
    engine = create_engine(f'sqlite:///{db_path}')
    
    # 3. GET DATA FROM DATABASE
    query = """
    SELECT id, user_id, event, ip_address, created_at, updated_at
    FROM activity_logs 
    ORDER BY created_at DESC 
    LIMIT 10000;
    """
    df = pd.read_sql(query, engine)
    
    # 4. APPLY YOUR FRAUD DETECTION RULES (UNCHANGED)
    df['is_fraud'] = 0
    
    fraud_patterns = [
        'Unauthorized access attempt',
        'Tried to view Unowned',
        'Tried to view Unassigned',
        'Two-factor authentication Code has been verified',
        'Two-factor authentication has been disabled'
    ]
    
    for idx, row in df.iterrows():
        if any(pattern in row['event'] for pattern in fraud_patterns):
            df.at[idx, 'is_fraud'] = 1
    
        if idx > 0 and ('Submitted' in row['event'] or 'Approved' in row['event']):
            prev_time = pd.to_datetime(df.at[idx-1, 'created_at'])
            curr_time = pd.to_datetime(row['created_at'])
            if (curr_time - prev_time) < timedelta(minutes=1):
                df.at[idx, 'is_fraud'] = 1
    
        if row['ip_address'] == '127.0.0.1' and any(action in row['event'] for action in ['Submitted', 'Approved', 'Updated']):
            df.at[idx, 'is_fraud'] = 1
    
    # 5. ADD FRAUD ANALYSIS COLUMNS (UNCHANGED)
    df['fraud_likelihood'] = df['is_fraud'] * 100.0
    df['fraud_likelihood'] += np.random.uniform(-5, 5, df.shape[0])
    df['fraud_likelihood'] = df['fraud_likelihood'].clip(0, 100)
    
    df['hour_of_day'] = pd.to_datetime(df['created_at']).dt.hour
    df['day_of_week'] = pd.to_datetime(df['created_at']).dt.dayofweek
    
    def determine_decision(row):
        if row['is_fraud']:
            return 'Immediate action required'
        if row['fraud_likelihood'] > 75:
            return 'Manual review needed'
        if row['fraud_likelihood'] > 25:
            return 'Monitor activity'
        return 'No action'
    
    df['final_decision'] = df.apply(determine_decision, axis=1)
    
    # 6. SAVE RESULTS TO CSV
    output_path = r'R:\Desktop\LOGISTIC2\python\fraud_data_from_activity_logs.csv'
    df.to_csv(output_path, index=False)
    print(f"SUCCESS: Saved fraud data to {output_path}")
    
except Exception as e:
    # 7. HANDLE ERRORS
    print(f"ERROR: {str(e)}", file=sys.stderr)
    sys.exit(1)