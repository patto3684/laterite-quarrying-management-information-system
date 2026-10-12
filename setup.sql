CREATE DATABASE IF NOT EXISTS lqsmis CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE lqsmis;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role VARCHAR(50) NOT NULL DEFAULT 'quarry_officer',
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS quarries (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  district VARCHAR(100) NOT NULL,
  sector VARCHAR(100) NOT NULL,
  status VARCHAR(30) NOT NULL DEFAULT 'active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS productions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  quarry_id INT NOT NULL DEFAULT 1,
  quantity_tonnes DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  recorded_on DATE NOT NULL,
  note VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS stock_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  item_name VARCHAR(150) NOT NULL,
  quantity_available INT NOT NULL DEFAULT 0,
  unit VARCHAR(50) NOT NULL DEFAULT 'tons'
);

CREATE TABLE IF NOT EXISTS equipment (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  status VARCHAR(30) NOT NULL DEFAULT 'available',
  last_maintenance DATE DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS customers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  contact_person VARCHAR(150) DEFAULT NULL,
  phone VARCHAR(50) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS financial_transactions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  type VARCHAR(20) NOT NULL,
  description VARCHAR(255) NOT NULL,
  amount DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  transaction_date DATE NOT NULL,
  customer_id INT DEFAULT NULL,
  payment_status VARCHAR(20) NOT NULL DEFAULT 'paid',
  payment_method VARCHAR(50) NOT NULL DEFAULT 'cash',
  FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS inspections (
  id INT AUTO_INCREMENT PRIMARY KEY,
  quarry_id INT NOT NULL DEFAULT 1,
  title VARCHAR(150) NOT NULL,
  scheduled_date DATE NOT NULL,
  status VARCHAR(30) NOT NULL DEFAULT 'pending'
);

CREATE TABLE IF NOT EXISTS activities (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  detail VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS system_settings (
  `k` VARCHAR(100) PRIMARY KEY,
  `v` TEXT DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS audit_logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT DEFAULT NULL,
  action VARCHAR(100) NOT NULL,
  target_table VARCHAR(100) DEFAULT NULL,
  details TEXT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS api_keys (
  id INT AUTO_INCREMENT PRIMARY KEY,
  service VARCHAR(150) NOT NULL,
  endpoint VARCHAR(255) DEFAULT NULL,
  api_key TEXT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS roles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL UNIQUE,
  description VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS permissions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,
  description VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS role_permissions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  role_id INT NOT NULL,
  permission_id INT NOT NULL,
  FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
  FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE,
  UNIQUE KEY unique_role_permission (role_id, permission_id)
);

-- Insert Roles
INSERT INTO roles (name, description) 
SELECT 'administrator', 'Full system access'
WHERE NOT EXISTS (SELECT 1 FROM roles WHERE name = 'administrator');

INSERT INTO roles (name, description) 
SELECT 'quarry_officer', 'Manages quarry operations, production, and equipment'
WHERE NOT EXISTS (SELECT 1 FROM roles WHERE name = 'quarry_officer');

INSERT INTO roles (name, description) 
SELECT 'inspector_officer', 'Handles compliance, safety, and quarry inspections'
WHERE NOT EXISTS (SELECT 1 FROM roles WHERE name = 'inspector_officer');

INSERT INTO roles (name, description) 
SELECT 'finance_officer', 'Manages financial transactions, customers, and revenue'
WHERE NOT EXISTS (SELECT 1 FROM roles WHERE name = 'finance_officer');

INSERT INTO roles (name, description) 
SELECT 'leader', 'Receives daily submissions and oversees operational performance'
WHERE NOT EXISTS (SELECT 1 FROM roles WHERE name = 'leader');

-- Insert Permissions
INSERT INTO permissions (name, description) 
SELECT 'dashboard', 'Access to dashboard'
WHERE NOT EXISTS (SELECT 1 FROM permissions WHERE name = 'dashboard');

INSERT INTO permissions (name, description) 
SELECT 'quarries', 'Manage quarries'
WHERE NOT EXISTS (SELECT 1 FROM permissions WHERE name = 'quarries');

INSERT INTO permissions (name, description) 
SELECT 'productions', 'Manage production records'
WHERE NOT EXISTS (SELECT 1 FROM permissions WHERE name = 'productions');

INSERT INTO permissions (name, description) 
SELECT 'stocks', 'Manage stock items'
WHERE NOT EXISTS (SELECT 1 FROM permissions WHERE name = 'stocks');

INSERT INTO permissions (name, description) 
SELECT 'equipment', 'Manage equipment'
WHERE NOT EXISTS (SELECT 1 FROM permissions WHERE name = 'equipment');

INSERT INTO permissions (name, description) 
SELECT 'finance', 'Manage financial transactions'
WHERE NOT EXISTS (SELECT 1 FROM permissions WHERE name = 'finance');

INSERT INTO permissions (name, description) 
SELECT 'inspections', 'Manage inspections'
WHERE NOT EXISTS (SELECT 1 FROM permissions WHERE name = 'inspections');

INSERT INTO permissions (name, description) 
SELECT 'customers', 'Manage customers'
WHERE NOT EXISTS (SELECT 1 FROM permissions WHERE name = 'customers');

INSERT INTO permissions (name, description) 
SELECT 'submissions', 'View daily operational submissions'
WHERE NOT EXISTS (SELECT 1 FROM permissions WHERE name = 'submissions');

INSERT INTO permissions (name, description) 
SELECT 'reports', 'View and generate reports'
WHERE NOT EXISTS (SELECT 1 FROM permissions WHERE name = 'reports');

INSERT INTO permissions (name, description) 
SELECT 'users', 'Manage system users'
WHERE NOT EXISTS (SELECT 1 FROM permissions WHERE name = 'users');

INSERT INTO permissions (name, description) 
SELECT 'settings', 'Manage system settings'
WHERE NOT EXISTS (SELECT 1 FROM permissions WHERE name = 'settings');

-- Assign Permissions to Administrator (all permissions)
INSERT INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id FROM roles r, permissions p
WHERE r.name = 'administrator' 
AND NOT EXISTS (SELECT 1 FROM role_permissions WHERE role_id = r.id AND permission_id = p.id);

-- Assign Permissions to Quarry Officer
INSERT INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id FROM roles r, permissions p
WHERE r.name = 'quarry_officer' AND p.name IN ('dashboard', 'quarries', 'productions', 'stocks', 'equipment', 'inspections', 'reports')
AND NOT EXISTS (SELECT 1 FROM role_permissions WHERE role_id = r.id AND permission_id = p.id);

-- Assign Permissions to Inspector Officer
INSERT INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id FROM roles r, permissions p
WHERE r.name = 'inspector_officer' AND p.name IN ('dashboard', 'inspections', 'quarries', 'reports')
AND NOT EXISTS (SELECT 1 FROM role_permissions WHERE role_id = r.id AND permission_id = p.id);

-- Assign Permissions to Finance Officer
INSERT INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id FROM roles r, permissions p
WHERE r.name = 'finance_officer' AND p.name IN ('dashboard', 'finance', 'customers', 'reports')
AND NOT EXISTS (SELECT 1 FROM role_permissions WHERE role_id = r.id AND permission_id = p.id);

-- Assign Permissions to Leader
INSERT INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id FROM roles r, permissions p
WHERE r.name = 'leader' AND p.name IN ('dashboard', 'submissions', 'reports')
AND NOT EXISTS (SELECT 1 FROM role_permissions WHERE role_id = r.id AND permission_id = p.id);

INSERT INTO users (full_name, email, password_hash, role)
SELECT 'System Administrator', 'admin@lqsmis.com', '$2y$10$BOudh4n5AZFEHWfppnyxNuuUPfQL4DKjPPAADwl6YS7lIopWsvebK', 'administrator'
WHERE NOT EXISTS (SELECT 1 FROM users WHERE email = 'admin@lqsmis.com');

INSERT INTO users (full_name, email, password_hash, role)
SELECT 'Quarry Officer', 'officer@lqsmis.com', '$2y$10$BOudh4n5AZFEHWfppnyxNuuUPfQL4DKjPPAADwl6YS7lIopWsvebK', 'quarry_officer'
WHERE NOT EXISTS (SELECT 1 FROM users WHERE email = 'officer@lqsmis.com');

INSERT INTO users (full_name, email, password_hash, role)
SELECT 'Inspector Officer', 'inspector@lqsmis.com', '$2y$10$BOudh4n5AZFEHWfppnyxNuuUPfQL4DKjPPAADwl6YS7lIopWsvebK', 'inspector_officer'
WHERE NOT EXISTS (SELECT 1 FROM users WHERE email = 'inspector@lqsmis.com');

INSERT INTO users (full_name, email, password_hash, role)
SELECT 'Finance Officer', 'finance@lqsmis.com', '$2y$10$BOudh4n5AZFEHWfppnyxNuuUPfQL4DKjPPAADwl6YS7lIopWsvebK', 'finance_officer'
WHERE NOT EXISTS (SELECT 1 FROM users WHERE email = 'finance@lqsmis.com');

INSERT INTO users (full_name, email, password_hash, role)
SELECT 'Leader Supervisor', 'leader@lqsmis.com', '$2y$10$BOudh4n5AZFEHWfppnyxNuuUPfQL4DKjPPAADwl6YS7lIopWsvebK', 'leader'
WHERE NOT EXISTS (SELECT 1 FROM users WHERE email = 'leader@lqsmis.com');

INSERT INTO quarries (name, district, sector, status)
SELECT 'Kigali Quarry', 'Gasabo', 'Jabana', 'active'
WHERE NOT EXISTS (SELECT 1 FROM quarries WHERE name = 'Kigali Quarry');

INSERT INTO quarries (name, district, sector, status)
SELECT 'Musanze Laterite Site', 'Gasabo', 'Kinyinya', 'active'
WHERE NOT EXISTS (SELECT 1 FROM quarries WHERE name = 'Musanze Laterite Site');

INSERT INTO productions (quarry_id, quantity_tonnes, recorded_on, note)
SELECT 1, 124.50, '2026-06-27', 'Daily extraction'
WHERE NOT EXISTS (SELECT 1 FROM productions WHERE recorded_on = '2026-06-27' AND quantity_tonnes = 124.50);

INSERT INTO productions (quarry_id, quantity_tonnes, recorded_on, note)
SELECT 1, 136.20, '2026-06-26', 'Morning shift'
WHERE NOT EXISTS (SELECT 1 FROM productions WHERE recorded_on = '2026-06-26' AND quantity_tonnes = 136.20);

INSERT INTO productions (quarry_id, quantity_tonnes, recorded_on, note)
SELECT 2, 90.80, '2026-06-25', 'Weekend output'
WHERE NOT EXISTS (SELECT 1 FROM productions WHERE recorded_on = '2026-06-25' AND quantity_tonnes = 90.80);

INSERT INTO stock_items (item_name, quantity_available, unit)
SELECT 'Crusher Parts', 18, 'units'
WHERE NOT EXISTS (SELECT 1 FROM stock_items WHERE item_name = 'Crusher Parts');

INSERT INTO stock_items (item_name, quantity_available, unit)
SELECT 'Fuel', 120, 'liters'
WHERE NOT EXISTS (SELECT 1 FROM stock_items WHERE item_name = 'Fuel');

INSERT INTO stock_items (item_name, quantity_available, unit)
SELECT 'Safety Helmets', 35, 'units'
WHERE NOT EXISTS (SELECT 1 FROM stock_items WHERE item_name = 'Safety Helmets');

INSERT INTO equipment (name, status, last_maintenance)
SELECT 'Excavator 1', 'available', '2026-06-01'
WHERE NOT EXISTS (SELECT 1 FROM equipment WHERE name = 'Excavator 1');

INSERT INTO equipment (name, status, last_maintenance)
SELECT 'Tipper Truck 2', 'maintenance', '2026-05-20'
WHERE NOT EXISTS (SELECT 1 FROM equipment WHERE name = 'Tipper Truck 2');

INSERT INTO equipment (name, status, last_maintenance)
SELECT 'Crusher Unit', 'available', '2026-06-10'
WHERE NOT EXISTS (SELECT 1 FROM equipment WHERE name = 'Crusher Unit');

INSERT INTO financial_transactions (type, description, amount, transaction_date)
SELECT 'income', 'Customer delivery payment', 5400000.00, '2026-06-27'
WHERE NOT EXISTS (SELECT 1 FROM financial_transactions WHERE description = 'Customer delivery payment');

INSERT INTO financial_transactions (type, description, amount, transaction_date)
SELECT 'expense', 'Fuel purchase', 840000.00, '2026-06-26'
WHERE NOT EXISTS (SELECT 1 FROM financial_transactions WHERE description = 'Fuel purchase');

INSERT INTO financial_transactions (type, description, amount, transaction_date)
SELECT 'income', 'Material sales', 3200000.00, '2026-06-25'
WHERE NOT EXISTS (SELECT 1 FROM financial_transactions WHERE description = 'Material sales');

INSERT INTO inspections (quarry_id, title, scheduled_date, status)
SELECT 1, 'Safety compliance review', '2026-06-29', 'pending'
WHERE NOT EXISTS (SELECT 1 FROM inspections WHERE title = 'Safety compliance review');

INSERT INTO inspections (quarry_id, title, scheduled_date, status)
SELECT 2, 'Environmental monitoring', '2026-06-28', 'completed'
WHERE NOT EXISTS (SELECT 1 FROM inspections WHERE title = 'Environmental monitoring');

INSERT INTO customers (name, contact_person, phone)
SELECT 'Rwanda Build Contractors', 'Jean Paul', '0788001122'
WHERE NOT EXISTS (SELECT 1 FROM customers WHERE name = 'Rwanda Build Contractors');

INSERT INTO customers (name, contact_person, phone)
SELECT 'Green Roads Ltd', 'Aline Uwase', '0788123456'
WHERE NOT EXISTS (SELECT 1 FROM customers WHERE name = 'Green Roads Ltd');

INSERT INTO activities (title, detail)
SELECT 'Login', 'Administrator signed in'
WHERE NOT EXISTS (SELECT 1 FROM activities WHERE title = 'Login');

INSERT INTO activities (title, detail)
SELECT 'Production recorded', '124.50 tonnes recorded for Kigali Quarry'
WHERE NOT EXISTS (SELECT 1 FROM activities WHERE title = 'Production recorded');

INSERT INTO activities (title, detail)
SELECT 'Inspection scheduled', 'Safety compliance review added'
WHERE NOT EXISTS (SELECT 1 FROM activities WHERE title = 'Inspection scheduled');
