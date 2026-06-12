-- ================================================
-- DATABASE: gravity_falls
-- ================================================

CREATE DATABASE IF NOT EXISTS gravity_falls;
USE gravity_falls;

-- ================================================
-- TABEL USERS
-- ================================================

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'viewer', 'contributor') DEFAULT 'viewer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- ================================================
-- TABEL CATEGORIES
-- ================================================

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ================================================
-- TABEL CHARACTERS
-- ================================================

CREATE TABLE characters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    alias VARCHAR(100),
    description TEXT,
    category_id INT,
    image_url VARCHAR(255),
    first_appearance VARCHAR(100),
    status ENUM('alive', 'deceased', 'unknown') DEFAULT 'unknown',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- ================================================
-- TABEL JOURNAL
-- ================================================

CREATE TABLE journal (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    content TEXT,
    character_id INT,
    entry_number INT,
    journal_number INT DEFAULT 3,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (character_id) REFERENCES characters(id) ON DELETE SET NULL
);

-- ================================================
-- TABEL LOCATIONS
-- ================================================

CREATE TABLE locations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    location_type ENUM('building', 'nature', 'dimension', 'secret') DEFAULT 'building',
    is_dangerous ENUM('yes', 'no', 'unknown') DEFAULT 'unknown',
    first_appearance VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ================================================
-- RELASI KARAKTER & LOKASI (many to many)
-- karena 1 karakter bisa di banyak lokasi
-- dan 1 lokasi bisa ditempati banyak karakter
-- ================================================

CREATE TABLE character_locations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    character_id INT NOT NULL,
    location_id INT NOT NULL,
    role_at_location VARCHAR(100),
    FOREIGN KEY (character_id) REFERENCES characters(id) ON DELETE CASCADE,
    FOREIGN KEY (location_id) REFERENCES locations(id) ON DELETE CASCADE
);

-- ================================================
-- DUMMY DATA USERS
-- Password : password
-- ================================================

INSERT INTO users (username, email, password, role) VALUES
('Admin', 'admin@gravityfalls.com', '$2y$10$yG3imIAlUP9U696KeWquF.HuRXi.ag9i4QPQpW0kWZNaajc/PlIQa', 'admin');

-- ================================================
-- DUMMY DATA CATEGORIES
-- ================================================

INSERT INTO categories (name, description) VALUES
('Human', 'Karakter manusia biasa di Gravity Falls'),
('Monster', 'Makhluk monster atau entitas jahat'),
('Supernatural', 'Entitas supernatural atau dimensi lain'),
('Ghost', 'Hantu atau roh yang bergentayangan'),
('Cryptid', 'Makhluk misterius yang belum teridentifikasi');

-- ================================================
-- DUMMY DATA CHARACTERS
-- ================================================

INSERT INTO characters (name, alias, description, category_id, first_appearance, status) VALUES
('Dipper Pines', 'Pine Tree', 'Dipper Pines adalah remaja 12 tahun yang cerdas dan penuh rasa ingin tahu. Ia menghabiskan musim panas di Gravity Falls bersama saudara kembarnya Mabel.', 1, 'Tourist Trapped', 'alive'),
('Mabel Pines', 'Shooting Star', 'Mabel adalah saudara kembar Dipper yang ceria dan optimis. Ia sangat menyukai sweater rajutan dan hal-hal yang lucu.', 1, 'Tourist Trapped', 'alive'),
('Stanley Pines', 'Grunkle Stan', 'Paman buyut Dipper dan Mabel yang mengelola Mystery Shack, sebuah tempat wisata penuh tipuan di Gravity Falls.', 1, 'Tourist Trapped', 'alive'),
('Stanford Pines', 'Sixer', 'Ilmuwan dan penulis Journal 1, Journal 2, dan Journal 3 yang meneliti fenomena supernatural di Gravity Falls.', 1, 'Not What He Seems', 'alive'),
('Soos Ramirez', 'Soos', 'Teknisi Mystery Shack yang setia membantu keluarga Pines dalam berbagai petualangan.', 1, 'Tourist Trapped', 'alive'),
('Wendy Corduroy', 'Wendy', 'Kasir di Mystery Shack berusia 15 tahun. Dikenal santai dan pemberani, menjadi teman dekat Dipper dan Mabel.', 1, 'Tourist Trapped', 'alive'),
('Gideon Gleeful', 'Lil Gideon', 'Anak ajaib yang memiliki sebagian informasi dari Journal 2 dan berusaha menguasai Mystery Shack.', 1, 'The Hand That Rocks the Mabel', 'alive'),
('Bill Cipher', 'Dream Demon', 'Bill Cipher adalah iblis segitiga dari dimensi lain dan merupakan antagonis utama serial Gravity Falls.', 3, 'Dreamscaperers', 'unknown');

-- ================================================
-- DUMMY DATA JOURNAL 
-- ================================================

INSERT INTO journal (title, content, character_id, entry_number, journal_number) VALUES
('Bill Cipher - Entitas Berbahaya', 'PERINGATAN: Jangan pernah bernegosiasi dengan makhluk ini. Bill Cipher adalah iblis segitiga dari Nightmare Realm. Ia dapat memasuki mimpi dan memanipulasi pikiran manusia.', 8, 1, 3),
('Gideon dan Journal Rahasia', 'Gideon Gleeful memperoleh sebagian informasi dari Journal 2 dan menggunakannya untuk mendapatkan pengaruh di Gravity Falls.', 7, 2, 2),
('Dipper Pines - Peneliti Muda', 'Anak ini memiliki kemampuan analisis yang luar biasa untuk usianya. Ia menemukan Journal 3 dan terus menyelidiki berbagai misteri yang ada di Gravity Falls.', 1, 3, 3),
('Gnomes', 'Makhluk kecil penghuni hutan Gravity Falls yang dapat bergabung menjadi satu tubuh raksasa.', NULL, 4, 3),
('Gobblewonker', 'Monster danau yang ternyata merupakan robot raksasa hasil ciptaan Fiddleford McGucket.', NULL, 5, 3),
('Shapeshifter', 'Makhluk misterius yang ditemukan di bunker rahasia dan mampu meniru bentuk siapa pun.', NULL, 6, 3),
('Portal Antar Dimensi', 'Penelitian Stanford Pines mengenai portal yang mampu menghubungkan berbagai dimensi berbeda.', 4, 7, 1),
('Time Traveler', 'Catatan mengenai anomali waktu dan kemungkinan perjalanan lintas waktu yang ditemukan di Gravity Falls.', 4, 8, 2);

-- ================================================
-- DUMMY DATA LOCATIONS
-- ================================================

INSERT INTO locations (name, description, location_type, is_dangerous, first_appearance) VALUES
('Mystery Shack', 'Toko wisata milik Stanley Pines yang penuh dengan artefak palsu dan pameran tipuan. Menjadi markas utama keluarga Pines selama musim panas.', 'building', 'no', 'Tourist Trapped'),
('Gravity Falls Forest', 'Hutan lebat di sekitar kota Gravity Falls yang menyimpan banyak makhluk supernatural dan misteri tersembunyi.', 'nature', 'yes', 'Tourist Trapped'),
('Gravity Falls Bunker', 'Bunker rahasia milik Stanford Pines yang tersembunyi di dalam hutan. Tempat ditemukannya Shapeshifter misterius.', 'secret', 'yes', 'Into the Bunker'),
('Nightmare Realm', 'Dimensi tempat asal Bill Cipher. Dunia yang kacau dan tidak memiliki aturan fisika normal.', 'dimension', 'yes', 'Dreamscaperers'),
('Gravity Falls Lake', 'Danau besar di pinggiran kota tempat kemunculan monster Gobblewonker. Sering dijadikan tempat memancing warga lokal.', 'nature', 'unknown', 'The Legend of the Gobblewonker'),
('Town of Gravity Falls', 'Kota kecil misterius di Oregon yang menjadi latar utama serial. Penuh dengan fenomena supernatural yang tidak dapat dijelaskan secara ilmiah.', 'building', 'unknown', 'Tourist Trapped'),
('Northwest Mansion', 'Rumah mewah milik keluarga Northwest yang menyimpan banyak rahasia kelam.', 'building', 'yes', 'Northwest Mansion Mystery'),
('Underground Jail', 'Penjara bawah tanah milik Gideon yang digunakan untuk menahan orang-orang yang menghalangi rencananya.', 'secret', 'yes', 'Gideon Rises'),
('Mindscape', 'Dunia dalam pikiran manusia yang bisa dimasuki melalui kemampuan supernatural. Bill Cipher sering beroperasi di sini.', 'dimension', 'yes', 'Dreamscaperers'),
('Gravity Falls Cemetery', 'Pemakaman kota yang sering dikaitkan dengan aktivitas supernatural terutama di malam hari.', 'building', 'yes', 'Summerween');

-- ================================================
-- DUMMY DATA CHARACTER LOCATIONS
-- Disesuaikan dengan karakter ID 1-8
-- ID 1 = Dipper, 2 = Mabel, 3 = Stanley, 4 = Stanford
-- ID 5 = Soos, 6 = Wendy, 7 = Gideon, 8 = Bill Cipher
-- ================================================

INSERT INTO character_locations (character_id, location_id, role_at_location) VALUES
-- Mystery Shack (location_id = 1)
(1, 1, 'Tinggal sementara selama musim panas'),
(2, 1, 'Tinggal sementara selama musim panas'),
(3, 1, 'Pemilik Mystery Shack'),
(4, 1, 'Pernah tinggal sebelum masuk portal'),
(5, 1, 'Teknisi dan karyawan setia'),
(6, 1, 'Kasir dan karyawan'),

-- Gravity Falls Forest (location_id = 2)
(1, 2, 'Sering menjelajah mencari misteri'),
(2, 2, 'Sering menjelajah bersama Dipper'),
(7, 2, 'Pernah bersembunyi di hutan'),

-- Gravity Falls Bunker (location_id = 3)
(1, 3, 'Menemukan dan menjelajahi bunker'),
(2, 3, 'Menjelajahi bunker bersama Dipper'),
(4, 3, 'Pemilik dan pembuat bunker rahasia'),

-- Nightmare Realm (location_id = 4)
(8, 4, 'Tempat asal Bill Cipher'),

-- Gravity Falls Lake (location_id = 5)
(1, 5, 'Menyelidiki monster Gobblewonker'),
(2, 5, 'Menyelidiki monster Gobblewonker'),
(3, 5, 'Memancing di danau'),
(5, 5, 'Menemani keluarga Pines ke danau'),

-- Town of Gravity Falls (location_id = 6)
(1, 6, 'Pendatang musim panas'),
(2, 6, 'Pendatang musim panas'),
(3, 6, 'Penduduk tetap kota'),
(5, 6, 'Penduduk asli kota'),
(6, 6, 'Penduduk asli kota'),
(7, 6, 'Penduduk dan tokoh terkenal kota'),

-- Northwest Mansion (location_id = 7)
(1, 7, 'Diundang untuk mengusir hantu'),
(2, 7, 'Menghadiri pesta mansion'),

-- Underground Jail (location_id = 8)
(7, 8, 'Pemilik penjara bawah tanah'),
(3, 8, 'Pernah ditahan di sini'),
(1, 8, 'Pernah ditahan oleh Gideon'),

-- Mindscape (location_id = 9)
(8, 9, 'Penguasa Mindscape'),
(1, 9, 'Pernah masuk untuk menyelamatkan Grunkle Stan'),
(4, 9, 'Pernah terjebak di dalam Mindscape'),

-- Gravity Falls Cemetery (location_id = 10)
(1, 10, 'Menyelidiki aktivitas supernatural'),
(2, 10, 'Mengikuti Dipper menyelidiki pemakaman');