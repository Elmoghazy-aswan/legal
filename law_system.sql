-- 1. إنشاء قاعدة البيانات (إذا لم تكن موجودة)
CREATE DATABASE IF NOT EXISTS law_system 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE law_system;

-- 2. إنشاء جدول المستخدمين
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user'
) ENGINE=InnoDB;

-- إدراج حساب المدير (كلمة السر الافتراضية: 123)
INSERT INTO users (username, password, role) 
VALUES ('admin', '123', 'admin')
ON DUPLICATE KEY UPDATE username=username;

-- 3. إنشاء جدول القضايا بالمسميات الجديدة
CREATE TABLE IF NOT EXISTS lawsuits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    case_number VARCHAR(100) NOT NULL,
    plaintiff_name VARCHAR(255) NOT NULL,
    case_type VARCHAR(100),
    department VARCHAR(100),
    court_type VARCHAR(100),
    -- استخدام VARCHAR لمرونة أكبر في المسميات الطويلة
    case_status VARCHAR(100) DEFAULT 'متداولة', 
    attachment_path VARCHAR(255),
    created_at DATE DEFAULT (CURRENT_DATE)
) ENGINE=InnoDB;

-- 4. تنظيف ودمج البيانات (في حال وجود بيانات قديمة بأسماء مختلفة)
-- دمج حالات المنتهية (لصالحنا وضدنا) في "صدر الحكم"
UPDATE lawsuits 
SET case_status = 'صدر الحكم' 
WHERE case_status LIKE 'منتهية%';

-- تحويل "مؤجلة" إلى "وردت المطالبات القضائية"
UPDATE lawsuits 
SET case_status = 'وردت المطالبات القضائية' 
WHERE case_status = 'مؤجلة';

-- 5. تحديث العمود ليكون محدوداً بالخيارات الثلاثة فقط (اختياري لضبط الدقة)
ALTER TABLE lawsuits 
MODIFY COLUMN case_status ENUM('متداولة', 'صدر الحكم', 'وردت المطالبات القضائية') 
DEFAULT 'متداولة';