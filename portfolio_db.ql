-- Database schema for web portfolio system

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM ('client','admin') NOT NULL,
    password_changed BOOLEAN NOT NULL DEFAULT FALSE,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    );
    
-- Projects table
CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    status VARCHAR(150) NOT NULL DEFAULT 'Not Started',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    );
    
-- Testimonials
CREATE TABLE testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    content TEXT NOT NULL,
    is_featured BOOLEAN NOT NULL DEFAULT FALSE,
    author_name VARCHAR(150) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP

    );
    
-- Documents
CREATE TABLE documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    uploaded_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
    );
    
-- Portfolio entries 
CREATE TABLE portfolio_entries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    media_url VARCHAR(255),
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    );
    
-- Contact submissions
CREATE TABLE contact_submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    subject VARCHAR(150),
    message TEXT NOT NULL,
    submitted_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    );

-- Portfolio media (gallery images per portfolio entry, separate from the entries' single cover image)
CREATE TABLE portfolio_media (
    id INT AUTO_INCREMENT PRIMARY KEY,
    entry_id INT NOT NULL,
    media_url VARCHAR(255) NOT NULL,
    display_order INT DEFAULT 0,
    FOREIGN KEY (entry_id) REFERENCES portfolio_entries(id) ON DELETE CASCADE
    );

-- site content (2 col section, header bubble)
CREATE TABLE site_content (
    id INT NOT NULL DEFAULT 1 PRIMARY KEY,
    experience_text TEXT NOT NULL,
    skills_text TEXT NOT NULL,
    is_available BOOL NOT NULL DEFAULT TRUE,
    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP

);
--Add single entry used by system
INSERT INTO site_content (id, experience_text,skills_text, is_available)
VALUES(1, 'I have around 2.5 years of experience in WordPress web development, gained through self-directed projects built in my own time. Over this period I have worked on custom theme development, site builds from the ground up, and general WordPress configuration and maintenance, alongside broader full-stack development skills developed through academic study.', 'Custom WordPress theme development and customisation, PHP, MySQL/MariaDB database design, HTML5 and CSS3, responsive and accessible design, front-end/back-end integration, version control with Git, and general full-stack web development.', 1);
    
    


-- Auto contact submission clean up
-- Based off 
--Dhandala, N. (2026) How to Schedule Data Cleanup Jobs with MySQL Events. OneUptime Blog. 
--Available at: https://oneuptime.com/blog/post/2026-03-31-mysql-schedule-data-cleanup-events/view (Accessed: 9 August 2026)

DELIMITER $$

CREATE EVENT IF NOT EXISTS delete_old_contact_submissions
ON SCHEDULE EVERY 1 DAY
STARTS CURRENT_TIMESTAMP
DO
BEGIN

DELETE FROM contact_submissions
WHERE submitted_at < (NOW() - INTERVAL 90 DAY);
END $$

--Add new testimonial
INSERT INTO testimonials (content, author_name, is_featured)
VALUES ('RB Digital Solutions redesigned our site and our enquiries doubled within a month.', 'Willday Wealth Management', 1),
VALUES ('i like treats', 'Buffy', 0);



--Add new portfolio media
INSERT INTO portfolio_media (entry_id, media_url, display_order)
VALUES (1, 'uploads/portfolio/willdaywm-carousel.png', 1),
VALUES (1, 'uploads/portfolio/willdaywm-statement-archive.png', 2),
VALUES (1, 'uploads/portfolio/willdaywm-download-the-app.png', 3);


-- Creating admin account
-- DONT push to git with real details!
INSERT INTO users (full_name, email, password_hash, role, password_changed)
VALUES ('Rosie Admin', 'test@rbdigitalsolutions.co.uk', 'hashedpassword', 'admin', TRUE);



-- adding new portfolio entry, replace placeholders with real VALUES

INSERT INTO portfolio_entries (id, title, description, media_url)
VALUES (1, 'Willday Wealth Management', 'Willday Wealth Management is a WordPress website I've worked on 
for two and a half years, having joined initially as an intern before being offered a permanent role. 
What began as a completely self -taught web development position has since expanded to cover the company's full digital presence, 
and since January I've taken on responsibility for their marketing alongside ongoing site development, including content updates, design work, 
and day-to-day maintenance. This role has given me hands-on experience with Gravity Forms, Advanced Custom Fields (ACF), and the Divi page builder, 
along with building custom layouts tailored to the site's needs. My work on the site and its marketing has consistently been recognised positively by the team.', 
'/Web-Portfolio/public/images/willdaywm.png');

INSERT INTO portfolio_entries (title, description, media_url)
VALUES ('Basic Personal WordPress Portfolio', 'Ahead of my interview for Willday Wealth Management, I built a simple WordPress portfolio site to demonstrate my
 capabilities as a developer. I was the only candidate to take this approach, and the site was a significant factor in being offered the role. 
 The site features basic information about my skills and experience up to that point. A lot has changed since then!',
  '/Web-Portfolio/public/images/rstep67.jpg');


INSERT INTO portfolio_entries (title, description, media_url)
VALUES ('Automatic Hydroponic System - Arduino', 'An automatic hydroponic watering system built using an Arduino, 
created to explore the basics of electronics and physical computing. The system uses humidity sensors to detect 
when soil moisture drops below a set threshold, automatically dispensing water until the sensor reads sufficiently moist again, 
then stopping. It was a simple, self-directed project built out of personal interest, and one I genuinely enjoyed working on.', 
'/Web-Portfolio/public/images/autohydro.jpg');