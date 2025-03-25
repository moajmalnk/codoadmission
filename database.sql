CREATE TABLE admissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    applicant_name VARCHAR(100) NOT NULL,
    batch_no VARCHAR(20),
    date_of_joining DATE,
    dob DATE NOT NULL,
    gender ENUM('male', 'female') NOT NULL,
    phone VARCHAR(15) NOT NULL,
    email VARCHAR(100) NOT NULL,
    education VARCHAR(200),
    father_name VARCHAR(100),
    father_occupation VARCHAR(100),
    mother_name VARCHAR(100),
    guardian_phone VARCHAR(15),
    address TEXT NOT NULL,
    technical_background ENUM('yes', 'no'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
); 

ALTER TABLE admissions ADD COLUMN signature_path VARCHAR(255);

ALTER TABLE admissions
ADD COLUMN application_id VARCHAR(20) UNIQUE NOT NULL;

-- Add index for faster lookups
CREATE INDEX idx_application_id ON admissions(application_id);