-- Nếu database đã tồn tại thì bỏ qua dòng này
CREATE DATABASE IF NOT EXISTS cnjava 
    CHARACTER SET utf8mb4 
    COLLATE utf8mb4_general_ci;

-- Chọn database để làm việc
USE cnjava;

-- Tạo bảng users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,   -- Khóa chính, tự tăng
    usercode VARCHAR(50) NOT NULL UNIQUE, -- Mã người dùng (duy nhất)
    username VARCHAR(100) NOT NULL,      -- Họ tên
    birthyear INT NOT NULL,              -- Năm sinh
    email VARCHAR(100) NOT NULL,         -- Email
    phone VARCHAR(20),                   -- Số điện thoại
    address VARCHAR(200)                 -- Địa chỉ
);
INSERT INTO users (usercode, username, birthyear, email, phone, address)
VALUES
('U001', 'Nguyen Van A', 2000, 'vana@example.com', '0901234567', 'Hà Nội'),
('U002', 'Tran Thi B', 1999, 'thib@example.com', '0987654321', 'TP.HCM'),
('U003', 'Le Van C', 2001, 'vanc@example.com', NULL, 'Đà Nẵng');
SELECT * FROM users;
