CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  phone VARCHAR(50) NULL,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('client','contractor','admin') NOT NULL,
  status ENUM('pending','active','blocked') NOT NULL DEFAULT 'pending',
  email_verified_at DATETIME NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL
);

CREATE TABLE IF NOT EXISTS contractor_profiles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NULL,
  company_name VARCHAR(190) NULL,
  company_code VARCHAR(100) NULL,
  vat_code VARCHAR(100) NULL,
  contact_person VARCHAR(190) NULL,
  email VARCHAR(190) NULL,
  phone VARCHAR(50) NULL,
  website VARCHAR(255) NULL,
  city VARCHAR(120) NULL,
  region VARCHAR(120) NULL,
  address VARCHAR(255) NULL,
  description TEXT NULL,
  categories TEXT NULL,
  service_radius_km INT DEFAULT 30,
  consent_to_contact TINYINT(1) NOT NULL DEFAULT 0,
  source ENUM('self_registered','admin_import','manual','scraped') NOT NULL DEFAULT 'manual',
  rating_avg DECIMAL(3,2) DEFAULT 0,
  rating_count INT DEFAULT 0,
  status ENUM('pending','approved','rejected','blocked') NOT NULL DEFAULT 'pending',
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  INDEX (user_id), INDEX (email), INDEX (phone)
);

CREATE TABLE IF NOT EXISTS project_categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  slug VARCHAR(120) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS projects (
  id INT AUTO_INCREMENT PRIMARY KEY,
  client_id INT NOT NULL,
  title VARCHAR(190) NOT NULL,
  category_id INT NULL,
  city VARCHAR(120) NOT NULL,
  region VARCHAR(120) NOT NULL,
  address_optional VARCHAR(255) NULL,
  budget_min DECIMAL(12,2) NULL,
  budget_max DECIMAL(12,2) NULL,
  desired_start_date DATE NULL,
  description TEXT NOT NULL,
  status ENUM('draft','open','collecting_bids','bids_received','contractor_selected','closed','cancelled') NOT NULL DEFAULT 'open',
  ai_summary TEXT NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL
);

CREATE TABLE IF NOT EXISTS project_files (
  id INT AUTO_INCREMENT PRIMARY KEY,
  project_id INT NOT NULL,
  original_name VARCHAR(255) NOT NULL,
  file_path VARCHAR(255) NOT NULL,
  mime_type VARCHAR(120) NOT NULL,
  size INT NOT NULL,
  created_at DATETIME NOT NULL
);

CREATE TABLE IF NOT EXISTS project_invites (
  id INT AUTO_INCREMENT PRIMARY KEY,
  project_id INT NOT NULL,
  contractor_profile_id INT NOT NULL,
  status ENUM('queued','sent','opened','accepted','declined','bounced') NOT NULL DEFAULT 'queued',
  invite_token VARCHAR(80) NOT NULL,
  sent_at DATETIME NULL,
  created_at DATETIME NOT NULL
);

CREATE TABLE IF NOT EXISTS bids (
  id INT AUTO_INCREMENT PRIMARY KEY,
  project_id INT NOT NULL,
  contractor_profile_id INT NOT NULL,
  price DECIMAL(12,2) NOT NULL,
  price_type ENUM('fixed','estimate','hourly') NOT NULL DEFAULT 'estimate',
  duration_days INT NULL,
  message TEXT NULL,
  includes_materials TINYINT(1) NOT NULL DEFAULT 0,
  warranty_months INT NULL,
  status ENUM('submitted','shortlisted','rejected','selected','withdrawn') NOT NULL DEFAULT 'submitted',
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL
);

CREATE TABLE IF NOT EXISTS messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  project_id INT NOT NULL,
  sender_user_id INT NULL,
  contractor_profile_id INT NULL,
  message TEXT NOT NULL,
  created_at DATETIME NOT NULL
);

CREATE TABLE IF NOT EXISTS email_logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  recipient_email VARCHAR(190) NOT NULL,
  subject VARCHAR(255) NOT NULL,
  body_preview VARCHAR(255) NULL,
  status ENUM('queued','sent','failed') NOT NULL,
  error_message TEXT NULL,
  related_project_id INT NULL,
  created_at DATETIME NOT NULL
);

CREATE TABLE IF NOT EXISTS ai_analyses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  project_id INT NOT NULL,
  analysis_type ENUM('project_summary','bid_comparison','negotiation_text','risk_analysis') NOT NULL,
  input_json LONGTEXT NULL,
  output_text LONGTEXT NULL,
  created_at DATETIME NOT NULL
);

CREATE TABLE IF NOT EXISTS payments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  project_id INT NULL,
  type ENUM('project_analysis','premium_project','lead_fee','subscription','success_fee') NOT NULL,
  amount DECIMAL(12,2) NOT NULL,
  currency VARCHAR(10) NOT NULL DEFAULT 'EUR',
  status ENUM('pending','paid','failed','refunded') NOT NULL DEFAULT 'pending',
  provider VARCHAR(80) NULL,
  provider_ref VARCHAR(190) NULL,
  created_at DATETIME NOT NULL
);

CREATE TABLE IF NOT EXISTS settings (
  `key` VARCHAR(120) PRIMARY KEY,
  `value` LONGTEXT NULL
);
