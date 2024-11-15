CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,  -- Auto-incremented primary key
    full_name VARCHAR(255) NOT NULL,    -- The full name of the admin
    email VARCHAR(255) NOT NULL UNIQUE, -- The email address (unique constraint to avoid duplicates)
    passwordHash VARCHAR(255) NOT NULL, -- The hashed password
    verification_token VARCHAR(255) DEFAULT NULL, -- Token for email verification (nullable)
    email_verified BOOLEAN DEFAULT FALSE, -- Whether the email is verified (default is false)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Timestamp for when the record was created
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP -- Timestamp for when the record was last updated
);

CREATE TABLE college (
    college_id INT AUTO_INCREMENT PRIMARY KEY,  -- Unique ID for each college
    college_name VARCHAR(255) NOT NULL,  -- College name (required field)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Timestamp when the college is created
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,  -- Timestamp when the college is last updated
    UNIQUE (college_name)  -- Ensures college names are unique
);

CREATE TABLE department (
    department_id INT AUTO_INCREMENT PRIMARY KEY,  -- Unique ID for each department
    department_name VARCHAR(255) NOT NULL,  -- Department name
    college_id INT,  -- Foreign key to the college
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Timestamp when the department is created
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,  -- Timestamp when the department is last updated
    FOREIGN KEY (college_id) REFERENCES college(college_id) ON DELETE CASCADE,  -- Cascade delete for related departments when a college is deleted
    UNIQUE (department_name)
);

CREATE TABLE faculty_list (
    id INT AUTO_INCREMENT PRIMARY KEY,
    faculty_id VARCHAR(20) NOT NULL UNIQUE,
    full_name VARCHAR(255) NOT NULL,
    phoneNumber VARCHAR(15),
    gender ENUM('Female', 'Male') NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    passwordHash VARCHAR(255) NOT NULL,
    college INT NULL,  -- Reference to college, can be NULL
    department INT NULL,  -- Reference to department, can be NULL
    is_active TINYINT(1) DEFAULT 0,
    reset_token VARCHAR(255),
    token_created_at TIMESTAMP NULL DEFAULT NULL,
    email_verified TINYINT(1) DEFAULT 0,
    verification_token VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (college) REFERENCES college(college_id) ON DELETE SET NULL,  -- Set college to NULL if college is deleted
    FOREIGN KEY (department) REFERENCES department(department_id) ON DELETE SET NULL  -- Set department to NULL if department is deleted
);

CREATE TABLE student_list (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(20) NOT NULL UNIQUE,
    full_name VARCHAR(255) NOT NULL,
    phoneNumber VARCHAR(15),
    gender ENUM('Female', 'Male') NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    passwordHash VARCHAR(255) NOT NULL,
    reset_token VARCHAR(255),
    token_created_at TIMESTAMP NULL DEFAULT NULL,
    email_verified TINYINT(1) DEFAULT 0,
    verification_token VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE academic (
    id INT AUTO_INCREMENT PRIMARY KEY,
    school_year VARCHAR(9) NOT NULL,    -- e.g., 2024-2025
    semester INT NOT NULL,
    status INT NOT NULL COMMENT '1=Start, 2=Closed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


CREATE TABLE rating (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rate TINYINT NOT NULL CHECK (rate BETWEEN 1 AND 5),
    descriptive_rating VARCHAR(50) NOT NULL,
    qualitative_description TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE (rate)
);

CREATE TABLE criteria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE evaluation_question (
    id INT AUTO_INCREMENT PRIMARY KEY,
    criteria_id INT NOT NULL,
    question_text TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (criteria_id) REFERENCES criteria(id) ON DELETE CASCADE
);

CREATE TABLE evaluation (
    id INT(20) AUTO_INCREMENT PRIMARY KEY,
    student_id INT(20) NOT NULL,
    faculty_id INT(20) NOT NULL,
    academic_id INT(20) NOT NULL,
    comment TEXT NOT NULL, -- This is the overall comment from the student
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES student_list(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (faculty_id) REFERENCES faculty_list(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (academic_id) REFERENCES academic(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE evaluation_answer (
    evaluation_id INT(20) NOT NULL,
    evaluation_question_id INT(20) NOT NULL,
    rating_id INT(20) NOT NULL,
    PRIMARY KEY (evaluation_id, evaluation_question_id),
    FOREIGN KEY (evaluation_id) REFERENCES evaluation(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (evaluation_question_id) REFERENCES evaluation_question(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (rating_id) REFERENCES rating(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;


CREATE TABLE evaluation_dates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    open_datetime DATETIME NOT NULL,
    close_datetime DATETIME NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
