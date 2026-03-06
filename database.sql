-- Database Schema for Airbinclone
-- Database: rsk2_12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `profile_pic` TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`name`, `email`, `password`, `profile_pic`) VALUES
('Guest User', 'guest@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', ''); -- password is 'password'

-- --------------------------------------------------------

--
-- Table structure for table `properties`
--

CREATE TABLE IF NOT EXISTS `properties` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `price_per_night` DECIMAL(10,2) NOT NULL,
  `location` VARCHAR(255) NOT NULL,
  `type` VARCHAR(100),
  `category` VARCHAR(100) DEFAULT 'Trending',
  `amenities` TEXT,
  `image_url` VARCHAR(255),
  `rating` DECIMAL(3,2) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `properties`
--

INSERT INTO `properties` (`title`, `description`, `price_per_night`, `location`, `type`, `category`, `amenities`, `image_url`, `rating`) VALUES
('Sunset Villa', 'Beautiful villa with ocean view.', 150.00, 'Malibu, CA', 'Villa', 'Beachfront', 'Pool,WiFi,Kitchen', 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=800&q=80', 4.80),
('Cozy Mountain Cabin', 'A quiet getaway in the mountains.', 90.00, 'Aspen, CO', 'Cabin', 'Amazing views', 'Fireplace,Hiking,WiFi', 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=800&q=80', 4.90),
('Modern Downtown Apartment', 'In the heart of the city.', 120.00, 'New York, NY', 'Apartment', 'Trending', 'Gym,WiFi,Elevator', 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=800&q=80', 4.50),
('Seaside Cottage', 'Steps away from the beach.', 110.00, 'Miami, FL', 'Cottage', 'Beachfront', 'WiFi,Beach access', 'https://images.unsplash.com/photo-1499793983690-e29da59ef1c2?auto=format&fit=crop&w=800&q=80', 4.60),
('Luxury Infinity Pool Villa', 'Private pool with stunning views.', 350.00, 'Bali, Indonesia', 'Villa', 'Amazing pools', 'Pool,WiFi,Breakfast', 'https://images.unsplash.com/photo-1540541338287-41700207dee6?auto=format&fit=crop&w=800&q=80', 4.95),
('Arctic Glass Igloo', 'Sleep under the northern lights.', 500.00, 'Lappland, Finland', 'Unique', 'Arctic', 'Heated floors,View,WiFi', 'https://images.unsplash.com/photo-1516550893923-42d28e5677af?auto=format&fit=crop&w=800&q=80', 4.98),
('Safari Glamping Tent', 'Camping with full luxury.', 180.00, 'Maasai Mara, Kenya', 'Tent', 'Camping', 'Safari,Bed,WiFi', 'https://images.unsplash.com/photo-1496062031456-07b8f162a322?auto=format&fit=crop&w=800&q=80', 4.75),
('Architectural Wonder', 'Modern design house.', 280.00, 'Tokyo, Japan', 'House', 'Design', 'Kitchen,Gym,WiFi', 'https://images.unsplash.com/photo-1518780664697-55e3ad937233?auto=format&fit=crop&w=800&q=80', 4.85),
('Medieval Castle Stay', 'Live like royalty.', 600.00, 'Edinburgh, Scotland', 'Castle', 'Castles', 'Large garden,Historic,WiFi', 'https://images.unsplash.com/photo-1533154683836-84ea7a0bc310?auto=format&fit=crop&w=800&q=80', 4.92),
('Rustic Farm Stay', 'Fresh air and farm animals.', 75.00, 'Tuscany, Italy', 'Farm', 'Farms', 'Farm tours,Nature,Kitchen', 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=800&q=80', 4.70);

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE IF NOT EXISTS `bookings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `property_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `check_in_date` DATE NOT NULL,
  `check_out_date` DATE NOT NULL,
  `total_price` DECIMAL(10,2) NOT NULL,
  `status` VARCHAR(50) DEFAULT 'confirmed',
  FOREIGN KEY (`property_id`) REFERENCES `properties`(`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE IF NOT EXISTS `reviews` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `property_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `rating` INT NOT NULL,
  `comment` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`property_id`) REFERENCES `properties`(`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

COMMIT;
