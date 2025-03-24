-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 22, 2025 at 04:01 PM
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
-- Database: `pos_barcode_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_category`
--

CREATE TABLE `tbl_category` (
  `catid` int(11) NOT NULL,
  `category` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_category`
--

INSERT INTO `tbl_category` (`catid`, `category`) VALUES
(1, 'Soap'),
(2, 'Mobile'),
(3, 'Watches'),
(4, 'Health Care'),
(9, 'Grocery'),
(10, 'Fashion'),
(11, 'Electronics'),
(12, 'Baby & Kids'),
(13, 'Beverages'),
(14, 'Cosmetics'),
(15, 'Hardware'),
(16, 'Laptop'),
(17, 'Veg'),
(18, 'Non Veg'),
(19, 'Books'),
(20, 'Vegetables'),
(21, 'Spices'),
(22, 'Body Lotions'),
(23, 'Medicines');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_customer`
--

CREATE TABLE `tbl_customer` (
  `customer_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `dob` date NOT NULL,
  `age` int(11) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `order_date` date NOT NULL,
  `city` varchar(100) NOT NULL,
  `zipcode` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_customer`
--

INSERT INTO `tbl_customer` (`customer_id`, `name`, `gender`, `dob`, `age`, `phone`, `order_date`, `city`, `zipcode`) VALUES
(1, 'John Doe', 'Male', '1980-01-01', 45, '1234567890', '2025-03-22', 'New York', '10001'),
(2, 'Jane Smith', 'Female', '1985-02-02', 40, '0987654321', '2025-03-22', 'Los Angeles', '90001'),
(3, 'Alice Johnson', 'Female', '1990-03-03', 35, '1231231234', '2025-03-22', 'Chicago', '60601'),
(4, 'Bob Brown', 'Male', '1995-04-04', 30, '3213214321', '2025-03-22', 'Houston', '77001'),
(5, 'Charlie Davis', 'Male', '2000-05-05', 25, '4564564567', '2025-03-22', 'Phoenix', '85001'),
(6, 'Diana Evans', 'Female', '1975-06-06', 50, '6546546543', '2025-03-22', 'Philadelphia', '19101'),
(7, 'Eve Foster', 'Female', '1982-07-07', 43, '7897897890', '2025-03-22', 'San Antonio', '78201'),
(8, 'Frank Green', 'Male', '1988-08-08', 37, '9879879876', '2025-03-22', 'San Diego', '92101'),
(9, 'Grace Harris', 'Female', '1993-09-09', 32, '1112223334', '2025-03-22', 'Dallas', '75201'),
(10, 'Henry Jackson', 'Male', '1998-10-10', 27, '4445556667', '2025-03-22', 'San Jose', '95101'),
(11, 'Ivy King', 'Female', '1970-11-11', 55, '7778889990', '2025-03-22', 'Austin', '73301'),
(12, 'Jack Lee', 'Male', '1983-12-12', 42, '0001112223', '2025-03-22', 'Jacksonville', '32099'),
(13, 'Karen Miller', 'Female', '1977-01-13', 48, '3334445556', '2025-03-22', 'Fort Worth', '76101'),
(14, 'Larry Nelson', 'Male', '1981-02-14', 44, '6667778889', '2025-03-22', 'Columbus', '43085'),
(15, 'Mona Owens', 'Female', '1992-03-15', 33, '9990001112', '2025-03-22', 'Charlotte', '28201'),
(16, 'Nina Parker', 'Female', '1984-04-16', 41, '2223334445', '2025-03-22', 'San Francisco', '94101'),
(17, 'Oscar Quinn', 'Male', '1991-05-17', 34, '5556667778', '2025-03-22', 'Indianapolis', '46201'),
(18, 'Paul Roberts', 'Male', '1987-06-18', 38, '8889990001', '2025-03-22', 'Seattle', '98101'),
(19, 'Quincy Scott', 'Male', '1996-07-19', 29, '1234567891', '2025-03-22', 'Denver', '80201'),
(20, 'Rachel Taylor', 'Female', '1994-08-20', 31, '2345678902', '2025-03-22', 'Washington', '20001');

--
-- Table structure for table `tbl_invoice`
--

CREATE TABLE `tbl_invoice` (
  `invoice_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `order_date` date NOT NULL,
  `subtotal` double NOT NULL,
  `discount` double NOT NULL,
  `sgst` float NOT NULL,
  `cgst` float NOT NULL,
  `total` double NOT NULL,
  `payment_type` tinytext NOT NULL,
  `due` double NOT NULL,
  `paid` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_invoice`
--

INSERT INTO `tbl_invoice` (`invoice_id`, `order_date`, `customer_id`, `subtotal`, `discount`, `sgst`, `cgst`, `total`, `payment_type`, `due`, `paid`) VALUES
(1, '2025-03-22', 1, 100.0, 5.0, 2.5, 2.5, 95.0, 'Cash', 0.0, 95.0),
(2, '2025-03-22', 2, 200.0, 10.0, 5.0, 5.0, 190.0, 'Card', 0.0, 190.0),
(3, '2025-03-22', 3, 300.0, 15.0, 7.5, 7.5, 285.0, 'Cash', 0.0, 285.0),
(4, '2025-03-22', 4, 400.0, 20.0, 10.0, 10.0, 380.0, 'Card', 0.0, 380.0),
(5, '2025-03-22', 5, 500.0, 25.0, 12.5, 12.5, 475.0, 'Cash', 0.0, 475.0),
(6, '2025-03-22', 6, 600.0, 30.0, 15.0, 15.0, 570.0, 'Card', 0.0, 570.0),
(7, '2025-03-22', 7, 700.0, 35.0, 17.5, 17.5, 665.0, 'Cash', 0.0, 665.0),
(8, '2025-03-22', 8, 800.0, 40.0, 20.0, 20.0, 760.0, 'Card', 0.0, 760.0),
(9, '2025-03-22', 9, 900.0, 45.0, 22.5, 22.5, 855.0, 'Cash', 0.0, 855.0),
(10, '2025-03-22', 10, 1000.0, 50.0, 25.0, 25.0, 950.0, 'Card', 0.0, 950.0),
(11, '2025-03-22', 11, 1100.0, 55.0, 27.5, 27.5, 1045.0, 'Cash', 0.0, 1045.0),
(12, '2025-03-22', 12, 1200.0, 60.0, 30.0, 30.0, 1140.0, 'Card', 0.0, 1140.0),
(13, '2025-03-22', 13, 1300.0, 65.0, 32.5, 32.5, 1235.0, 'Cash', 0.0, 1235.0),
(14, '2025-03-22', 14, 1400.0, 70.0, 35.0, 35.0, 1330.0, 'Card', 0.0, 1330.0),
(15, '2025-03-22', 15, 1500.0, 75.0, 37.5, 37.5, 1425.0, 'Cash', 0.0, 1425.0),
(16, '2025-03-22', 16, 1600.0, 80.0, 40.0, 40.0, 1520.0, 'Card', 0.0, 1520.0),
(17, '2025-03-22', 17, 1700.0, 85.0, 42.5, 42.5, 1615.0, 'Cash', 0.0, 1615.0),
(18, '2025-03-22', 18, 1800.0, 90.0, 45.0, 45.0, 1710.0, 'Card', 0.0, 1710.0),
(19, '2025-03-22', 19, 1900.0, 95.0, 47.5, 47.5, 1805.0, 'Cash', 0.0, 1805.0),
(20, '2025-03-22', 20, 2000.0, 100.0, 50.0, 50.0, 1900.0, 'Card', 0.0, 1900.0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_invoice_details`
--

CREATE TABLE `tbl_invoice_details` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `barcode` varchar(200) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(200) NOT NULL,
  `qty` int(11) NOT NULL,
  `rate` double NOT NULL,
  `saleprice` double NOT NULL,
  `order_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_invoice_details`
--

INSERT INTO `tbl_invoice_details` (`id`, `invoice_id`, `customer_id`, `barcode`, `product_id`, `product_name`, `qty`, `rate`, `saleprice`, `order_date`) VALUES
(1, 1, 1, '8901057510028', 1, 'Kangaro Stapler Pins', 10, 30.0, 50.0, '2025-03-22'),
(2, 2, 2, '8901057310062', 2, 'Kangaro Stapler', 5, 150.0, 200.0, '2025-03-22'),
(3, 3, 3, '8901030865237', 3, 'kissan tomato katchup', 15, 110.0, 160.0, '2025-03-22'),
(4, 4, 4, '7121434', 4, 'lenovo charger', 8, 450.0, 800.0, '2025-03-22'),
(5, 5, 5, '5120819', 5, 'Veg Burger', 20, 50.0, 80.0, '2025-03-22'),
(6, 6, 6, '6121422', 6, 'Suger Packet 5 KG', 12, 120.0, 200.0, '2025-03-22'),
(7, 7, 7, '8904000952210', 7, 'Dyna Soap', 25, 15.0, 25.0, '2025-03-22'),
(8, 8, 8, '8901030843891', 8, 'Pepsodent Toothpaste', 30, 80.0, 140.0, '2025-03-22'),
(9, 9, 9, '8809461562230', 9, 'Modelling Comb', 18, 130.0, 250.0, '2025-03-22'),
(10, 10, 10, '8906105612730', 10, 'Wow Omega 3 Capsules', 22, 380.0, 500.0, '2025-03-22'),
(11, 11, 11, '6971914086630', 11, 'Real me XT', 5, 18000.0, 21500.0, '2025-03-22'),
(12, 12, 12, '12114804', 12, 'Mix Spices 500gm', 10, 180.0, 240.0, '2025-03-22'),
(13, 13, 13, '22072445', 22, 'Tshirt', 7, 500.0, 750.0, '2025-03-22'),
(14, 14, 14, '8901057510028', 1, 'Kangaro Stapler Pins', 10, 30.0, 50.0, '2025-03-22'),
(15, 15, 15, '8901057310062', 2, 'Kangaro Stapler', 5, 150.0, 200.0, '2025-03-22'),
(16, 16, 16, '8901030865237', 3, 'kissan tomato katchup', 15, 110.0, 160.0, '2025-03-22'),
(17, 17, 17, '7121434', 4, 'lenovo charger', 8, 450.0, 800.0, '2025-03-22'),
(18, 18, 18, '5120819', 5, 'Veg Burger', 20, 50.0, 80.0, '2025-03-22'),
(19, 19, 19, '6121422', 6, 'Suger Packet 5 KG', 12, 120.0, 200.0, '2025-03-22'),
(20, 20, 20, '8904000952210', 7, 'Dyna Soap', 25, 15.0, 25.0, '2025-03-22');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product`
--

CREATE TABLE `tbl_product` (
  `pid` int(11) NOT NULL,
  `barcode` varchar(1000) NOT NULL,
  `product` varchar(200) NOT NULL,
  `category` varchar(200) NOT NULL,
  `description` varchar(200) NOT NULL,
  `stock` int(11) NOT NULL,
  `purchaseprice` float NOT NULL,
  `saleprice` float NOT NULL,
  `image` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_product`
--

INSERT INTO `tbl_product` (`pid`, `barcode`, `product`, `category`, `description`, `stock`, `purchaseprice`, `saleprice`, `image`) VALUES
(1, '8901057510028', 'Kangaro Stapler Pins', 'Hardware', 'no 10 mm', 456, 30, 50, '639a07f9bd5d4.jpg'),
(2, '8901057310062', 'Kangaro Stapler', 'Hardware', 'no 10', 0, 150, 200, '639a088ba5aa8.jpg'),
(3, '8901030865237', 'kissan tomato katchup', 'Grocery', '1.2kg', 164, 110, 160, '639a0eea4ae36.jpg'),
(4, '7121434', 'lenovo charger', 'Laptop', '12v', 93, 450, 800, '639a0f563d3b6.jpg'),
(5, '5120819', 'Veg Burger', 'Veg', 'small p', 859, 50, 80, '63b95327e78f0.png'),
(6, '6121422', 'Suger Packet 5 KG', 'Grocery', '5 KG Suger Packet ', 493, 120, 200, '639a19867b32a.jpg'),
(7, '8904000952210', 'Dyna Soap', 'Soap', 'Dyna Premium Beauty Sandal and Saffron Soap', 0, 15, 25, '63a54421a2087.jpg'),
(8, '8901030843891', 'Pepsodent Toothpaste', 'Grocery', 'Pepsodent GERMI CHECK JUMBO\r\nADVANCED GERMI CHECK FORMULA  ', 334, 80, 140, '63b9a7bc9175e.jpg'),
(9, '8809461562230', 'Modelling Comb', 'Electronics', 'Modelling Comb Electronic Comb for men .', 114, 130, 250, '63a5461349496.jpg'),
(10, '8906105612730', 'Wow Omega 3 Capsules', 'Medicines', 'Wow Omega 3 60N softgel Capsules  ', 106, 380, 500, '63a546b584e3b.jpg'),
(11, '6971914086630', 'Real me XT', 'Mobile', 'Model : RMX1921', 145, 18000, 21500, '63a547ca0e89a.jpg'),
(12, '12114804', 'Mix Spices 500gm', 'Grocery', 'Mix Spices Pack 500gm', 296, 180, 240, '63a5481c962ff.jpg'),
(22, '22072445', 'Tshirt', 'Fashion', 'Black Tshirt', 100, 500, 750, '662efde515daa.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_taxdis`
--

CREATE TABLE `tbl_taxdis` (
  `taxdis_id` int(11) NOT NULL,
  `sgst` float NOT NULL,
  `cgst` float NOT NULL,
  `discount` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_taxdis`
--

INSERT INTO `tbl_taxdis` (`taxdis_id`, `sgst`, `cgst`, `discount`) VALUES
(1, 2.5, 2.5, 2),
(2, 5, 5, 5);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `userid` int(11) NOT NULL,
  `username` varchar(200) NOT NULL,
  `useremail` varchar(200) NOT NULL,
  `userpassword` varchar(200) NOT NULL,
  `role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`userid`, `username`, `useremail`, `userpassword`, `role`) VALUES
(2, 'user', 'user@gmail.com', '123', 'User'),
(15, 'Admin_2', 'admin2@gmail.com', '12345', 'Admin'),
(16, 'Admin', 'admin@gmail.com', '12345', 'Admin'),
(17, 'Sarath', 'sarath@gmail.com', '12345', 'User'),
(19, 'Barath', 'b@gmail.com', '12345', 'Admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_category`
--
ALTER TABLE `tbl_category`
  ADD PRIMARY KEY (`catid`);

--
-- Indexes for table `tbl_customer`
--
ALTER TABLE `tbl_customer`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `tbl_invoice`
--
ALTER TABLE `tbl_invoice`
  ADD PRIMARY KEY (`invoice_id`);

--
-- Indexes for table `tbl_invoice_details`
--
ALTER TABLE `tbl_invoice_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_product`
--
ALTER TABLE `tbl_product`
  ADD PRIMARY KEY (`pid`);

--
-- Indexes for table `tbl_taxdis`
--
ALTER TABLE `tbl_taxdis`
  ADD PRIMARY KEY (`taxdis_id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_category`
--
ALTER TABLE `tbl_category`
  MODIFY `catid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `tbl_customer`
--
ALTER TABLE `tbl_customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tbl_invoice`
--
ALTER TABLE `tbl_invoice`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `tbl_invoice_details`
--
ALTER TABLE `tbl_invoice_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=230;

--
-- AUTO_INCREMENT for table `tbl_product`
--
ALTER TABLE `tbl_product`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `tbl_taxdis`
--
ALTER TABLE `tbl_taxdis`
  MODIFY `taxdis_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
