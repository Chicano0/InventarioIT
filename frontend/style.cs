* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 20px;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
}

header {
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    text-align: center;
    margin-bottom: 30px;
}

header h1 {
    color: #667eea;
    font-size: 2.5em;
    margin-bottom: 10px;
}

header p {
    color: #666;
    font-size: 1.1em;
}

.form-container {
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
}

.form-container h2 {
    color: #333;
    margin-bottom: 20px;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 15px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    color: #555;
    font-weight: 600;
    margin-bottom: 8px;
    font-size: 0.9em;
}

.form-group input,
.form-group textarea,
.form-group select {
    padding: 12px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 1em;
    transition: all 0.3s;
}

.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.btn {
    padding: 14px 30px;
    border: none;
    border-radius: 8px;
    font-size: 1em;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    width: 100%;
    margin-top: 10px;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
}

.btn-primary:active {
    transform: translateY(0);
}

.inventory-section {
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    flex-wrap: wrap;
    gap: 15px;
}

.section-header h2 {
    color: #333;
}

.stats {
    display: flex;
    gap: 20px;
}

.stat {
    color: #666;
    font-size: 0.95em;
}

.stat strong {
    color: #667eea;
    font-size: 1.2em;
}

.loading {
    text-align: center;
    padding: 40px;
    color: #999;
    font-size: 1.1em;
}

.error-message {
    background: #fee;
    color: #c33;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    display: none;
}

.error-message.show {
    display: block;
}

.items-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}

.item-card {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    border-radius: 12px;
    padding: 20px;
    transition: all 0.3s;
    border: 2px solid transparent;
}

.item-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    border-color: #667eea;
}

.item-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 15px;
}

.item-name {
    font-size: 1.3em;
    font-weight: 700;
    color: #333;
    margin-bottom: 5px;
}

.item-category {
    display: inline-block;
    background: #667eea;
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.8em;
    font-weight: 600;
}

.item-body {
    margin-bottom: 15px;
}

.item-description {
    color: #555;
    font-size: 0.95em;
    margin-bottom: 12px;
    line-height: 1.5;
}

.item-details {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
    margin-bottom: 15px;
}

.item-detail {
    font-size: 0.9em;
}

.item-detail-label {
    color: #666;
    font-weight: 600;
}

.item-detail-value {
    color: #333;
    font-weight: 500;
}

.item-status {
    display: inline-block;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.85em;
    font-weight: 600;
}

.status-activo {
    background: #d4edda;
    color: #155724;
}

.status-uso {
    background: #d1ecf1;
    color: #0c5460;
}

.status-mantenimiento {
    background: #fff3cd;
    color: #856404;
}

.status-inactivo {
    background: #f8d7da;
    color: #721c24;
}

.item-footer {
    display: flex;
    gap: 10px;
    margin-top: 15px;
}

.btn-small {
    padding: 8px 16px;
    font-size: 0.9em;
    border-radius: 6px;
    cursor: pointer;
    border: none;
    font-weight: 600;
    transition: all 0.3s;
    flex: 1;
}

.btn-edit {
    background: #ffc107;
    color: #333;
}

.btn-edit:hover {
    background: #ffb300;
    transform: translateY(-2px);
}

.btn-delete {
    background: #dc3545;
    color: white;
}

.btn-delete:hover {
    background: #c82333;
    transform: translateY(-2px);
}

.item-date {
    font-size: 0.85em;
    color: #888;
    margin-top: 10px;
    text-align: right;
}

@media (max-width: 768px) {
    header h1 {
        font-size: 2em;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .items-grid {
        grid-template-columns: 1fr;
    }
    
    .section-header {
        flex-direction: column;
        align-items: flex-start;
    }
}