-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 23, 2025 at 11:23 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `photography_collective`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `a_id` int(11) NOT NULL,
  `a_name` varchar(255) NOT NULL,
  `a_email` varchar(40) NOT NULL,
  `username` varchar(40) NOT NULL,
  `password` varchar(255) NOT NULL,
  `a_phoneNum` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`a_id`, `a_name`, `a_email`, `username`, `password`, `a_phoneNum`) VALUES
(1, 'Neharhika Ramnatsing', 'neharhikaram@gmail.com', 'neha_2003', '*F64EEFEAC129778BC4217CDF4A651CEFC5846638', 59798862),
(2, 'Farzeen Dincha', 'dinchafarzeen@gmail.com', 'dindin', '*8C98582739F4FC30D421680678DA9AE426A3CC6C', 57538597);

-- --------------------------------------------------------

--
-- Table structure for table `booked_photographers`
--

CREATE TABLE `booked_photographers` (
  `booking_id` int(11) NOT NULL,
  `photographer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booked_photographers`
--

INSERT INTO `booked_photographers` (`booking_id`, `photographer_id`) VALUES
(1, 1),
(2, 2),
(2, 6),
(3, 4);

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `booking_id` int(11) NOT NULL,
  `u_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `payment_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`booking_id`, `u_id`, `description`, `location`, `price`, `payment_date`) VALUES
(1, 1, 'Graduation pictures to be taken by Alex Johnson.\r\nBasic editing requested by our customer.', 'University Of Mauritius, Reduit.', 3000.00, '2024-01-10'),
(2, 3, 'Wedding event to be handled by Sam Lee and Sophie brown.\r\n1-day event\r\n', 'Surinam', 7000.00, '2024-09-30'),
(3, 2, 'Birthday party\r\n18.00 till 22.00', '10B, morc St Andre, Vacoas.', 2000.00, NULL),
(7, 6, 'birthday party', 'UOM', 1000.00, '2025-01-17'),
(8, 6, 'School Photoshoot', 'University of Mauritius', 1000.00, '2025-01-15');

-- --------------------------------------------------------

--
-- Table structure for table `booking_dates`
--

CREATE TABLE `booking_dates` (
  `booking_id` int(11) NOT NULL,
  `booking_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_dates`
--

INSERT INTO `booking_dates` (`booking_id`, `booking_date`) VALUES
(1, '2024-01-25'),
(2, '2024-08-29'),
(2, '2024-08-30'),
(3, '2024-09-04'),
(7, '2025-03-24'),
(8, '2025-01-20');

-- --------------------------------------------------------

--
-- Table structure for table `photographer`
--

CREATE TABLE `photographer` (
  `photographer_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `bio` text NOT NULL,
  `p_phoneNum` int(11) NOT NULL,
  `profilePhoto` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `photographer`
--

INSERT INTO `photographer` (`photographer_id`, `name`, `bio`, `p_phoneNum`, `profilePhoto`) VALUES
(1, 'Alex Johnson', 'Hi, I’m Alex Johnson, a portrait photographer based in Mauritius. I love capturing the unique essence of people through my lens, whether it’s for professional headshots, family portraits, or creative projects. With over a decade of experience, I strive to make every photo session a memorable and enjoyable experience.', 52785236, 'C:\\Users\\Ramnatsing\\Downloads\\XAMPP\\htdocs\\photographers\\AlexJohnson\\Alex Johnson.jpg'),
(2, 'Sam Lee', 'I’m Sam Lee, an event photographer specializing in capturing the energy and excitement of events, from weddings and corporate gatherings to concerts and festivals. My goal is to create lasting memories through vibrant and dynamic photos that tell the story of each event.', 58456932, 'C:\\Users\\Ramnatsing\\Downloads\\XAMPP\\htdocs\\photographers\\Sam Lee\\Sam Lee.jpg'),
(3, 'Olivia Martinez', 'Hi, I’m Olivia Martinez, a fashion photographer. I thrive on capturing the latest trends and styles, working with models, designers, and brands to create stunning visual stories. My work is all about creativity, elegance, and bringing out the best in every subject.', 57452251, 'C:\\Users\\Ramnatsing\\Downloads\\XAMPP\\htdocs\\photographers\\Olivia Martinez\\Olivia Martinez.jpg'),
(4, 'Liam Smith', 'Hello, I’m Liam Smith, an event photographer who loves to capture the joy and excitement of celebrations. With a keen eye for detail and a knack for candid shots, I ensure that every important moment is beautifully documented. From the laughter at a birthday party to the tears of joy at a wedding, I aim to create a visual narrative that you can cherish forever.', 54511525, 'C:\\Users\\Ramnatsing\\Downloads\\XAMPP\\htdocs\\photographers\\Liam Smith\\Liam Smith.jpg'),
(5, 'Emma Davidson', 'Hi, I’m Emma Davidson, a passionate event photographer with over 10 years of experience capturing life’s most precious moments. On any special occasion, I strive to create timeless images that tell your unique story. My goal is to make you feel comfortable and natural in front of the camera, ensuring every shot is filled with genuine emotion.', 59798863, 'C:\\Users\\Ramnatsing\\Downloads\\XAMPP\\htdocs\\photographers\\Emma Davidson\\Emma Davidson.jpg'),
(6, 'Sophia Brown', 'I’m Sophia Brown, a dedicated photographer specializing in events like weddings and birthday parties. My approach is to blend into the background, capturing natural and spontaneous moments that truly reflect the essence of your event. I believe that the best photos are those that capture real emotions and connections, and I work tirelessly to deliver images that you’ll love.', 52314869, 'C:\\Users\\Ramnatsing\\Downloads\\XAMPP\\htdocs\\photographers\\Sophia Brown\\Sophia Brown.jpg'),
(7, 'Fransis Davis', 'I’m Fransis Davis, an event photographer who thrives on capturing the energy and emotion of celebrations. With a friendly and unobtrusive approach, I make sure to document every significant moment, from the grand to the intimate. My goal is to provide you with a stunning visual story that you can look back on with joy and pride.', 58785216, 'C:\\Users\\Ramnatsing\\Downloads\\XAMPP\\htdocs\\photographers\\Fransis Davis\\Fransis Davis.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `photographs`
--

CREATE TABLE `photographs` (
  `photographer_id` int(11) NOT NULL,
  `image_name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `photographs`
--

INSERT INTO `photographs` (`photographer_id`, `image_name`) VALUES
(1, 'Image1.jpg'),
(1, 'Image2.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `registered_user`
--

CREATE TABLE `registered_user` (
  `u_id` int(11) NOT NULL,
  `u_name` varchar(255) NOT NULL,
  `u_email` varchar(40) NOT NULL,
  `user_name` varchar(40) NOT NULL,
  `pass_word` varchar(255) NOT NULL,
  `u_phoneNum` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registered_user`
--

INSERT INTO `registered_user` (`u_id`, `u_name`, `u_email`, `user_name`, `pass_word`, `u_phoneNum`) VALUES
(1, 'Harry Banks', 'harryb@gmail.com', 'harryB', '*763B8823F0A748B2BFA22DD480E4A76E3AD2FE3C', 59874561),
(2, 'Zara Patel', 'patel@yahoo.com', 'zPatel23', '*DD01573941BF591C46C9220FD1C4351274499190', 58745612),
(3, 'Melissa Dufleur', 'mel2506@gmail.com', 'heyitsme', '*FF813EEB0174B4130D01093B3A26CF55D04746F0', 57456213),
(5, 'test', 'test@gmail.com', 'test', '12345678', 58265125),
(6, 'neha', 'neha@gmail.com', 'neha24', '$2y$10$x1bHsIfOsoZUtx.nrtdLF.rQ9SygbGm2XFg8nj70pIK/LaXq0Ofpm', 51234567),
(7, 'Jane Louis', 'jane@gmail.com', 'iamjanel', '$2y$10$.n1kwCk.bQbNVv9/PpDT1.TGPy5Qrvc7Qd0jHtxNCVgcM3p3ftLjW', 59792654),
(8, 'Lisa Robinson', 'lisa@gmail.com', 'lisaR', '$2y$10$wmuTgguCiicOahyz4phIfeI4XuxphsoVybNnLZl99wSbqpBo./pMC', 57894561);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `date` date NOT NULL,
  `comment` text NOT NULL,
  `u_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `rating`, `date`, `comment`, `u_id`, `booking_id`) VALUES
(1, 5, '2024-01-31', 'I really loved my grad pics!\r\nPhotographer was friendly and everything went on smoothly.', 1, 1),
(2, 4, '2024-08-31', 'Photographers were kind and supportive.\r\nEverything went smoothly and shots were great!\r\nTho, they came late on the first day of the ceremony.', 3, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`a_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `booked_photographers`
--
ALTER TABLE `booked_photographers`
  ADD PRIMARY KEY (`booking_id`,`photographer_id`),
  ADD KEY `booked_photographers_ibfk_2` (`photographer_id`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `u_id` (`u_id`);

--
-- Indexes for table `booking_dates`
--
ALTER TABLE `booking_dates`
  ADD PRIMARY KEY (`booking_id`,`booking_date`);

--
-- Indexes for table `photographer`
--
ALTER TABLE `photographer`
  ADD PRIMARY KEY (`photographer_id`);

--
-- Indexes for table `photographs`
--
ALTER TABLE `photographs`
  ADD PRIMARY KEY (`photographer_id`,`image_name`);

--
-- Indexes for table `registered_user`
--
ALTER TABLE `registered_user`
  ADD PRIMARY KEY (`u_id`),
  ADD UNIQUE KEY `user_name` (`user_name`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `u_id` (`u_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `a_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `photographer`
--
ALTER TABLE `photographer`
  MODIFY `photographer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `registered_user`
--
ALTER TABLE `registered_user`
  MODIFY `u_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booked_photographers`
--
ALTER TABLE `booked_photographers`
  ADD CONSTRAINT `booked_photographers_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `booked_photographers_ibfk_2` FOREIGN KEY (`photographer_id`) REFERENCES `photographer` (`photographer_id`) ON UPDATE CASCADE;

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`u_id`) REFERENCES `registered_user` (`u_id`);

--
-- Constraints for table `booking_dates`
--
ALTER TABLE `booking_dates`
  ADD CONSTRAINT `booking_dates_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `photographs`
--
ALTER TABLE `photographs`
  ADD CONSTRAINT `photographs_ibfk_1` FOREIGN KEY (`photographer_id`) REFERENCES `photographer` (`photographer_id`) ON UPDATE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`u_id`) REFERENCES `registered_user` (`u_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
