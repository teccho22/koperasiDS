-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id_number` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_proffesion` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_phone` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_agent` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_collect` int(11) DEFAULT NULL,
  `customer_dpd` int(11) DEFAULT NULL,
  `customer_active_loan` int(11) DEFAULT NULL,
  `is_blacklist` int(11) DEFAULT NULL,
  `is_alert` int(11) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `create_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `ms_incomings`
--

CREATE TABLE `ms_incomings` (
  `incoming_id` int(10) UNSIGNED NOT NULL,
  `loan_id` int(10) DEFAULT NULL,
  `incoming_category` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `incoming_date` datetime DEFAULT NULL,
  `incoming_amount` double(16,2) DEFAULT '0.00',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `loan_due_date` datetime DEFAULT NULL,
  `loan_status` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL,
  `create_by` int(11) NOT NULL,
  `create_at` datetime NOT NULL,
  `update_by` int(11) NOT NULL,
  `update_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ms_loans`
--

CREATE TABLE `ms_loans` (
  `loan_id` int(10) UNSIGNED NOT NULL,
  `customer_id` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `loan_number` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `loan_amount` double(16,2) NOT NULL,
  `interest_rate` double(16,2) NOT NULL DEFAULT '2.50',
  `provision_fee` double(16,2) NOT NULL,
  `disbursement_amount` double(16,2) NOT NULL,
  `tenor` int(11) NOT NULL,
  `installment_amount` double(16,2) NOT NULL,
  `collateral_category` text COLLATE utf8mb4_unicode_ci,
  `collateral_file_name` text COLLATE utf8mb4_unicode_ci,
  `collateral_file_path` text COLLATE utf8mb4_unicode_ci,
  `collateral_description` text COLLATE utf8mb4_unicode_ci,
  `loan_collect` int(11) NOT NULL,
  `loan_dpd` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `create_by` int(11) NOT NULL,
  `create_at` datetime NOT NULL,
  `update_by` int(11) NOT NULL,
  `update_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `ms_outgoings`
--

CREATE TABLE `ms_outgoings` (
  `outgoing_id` int(10) UNSIGNED NOT NULL,
  `loan_id` int(10) DEFAULT NULL,
  `outgoing_category` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `outgoing_date` datetime NOT NULL,
  `outgoing_amount` double(16,2) NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `create_by` int(11) NOT NULL,
  `create_at` datetime NOT NULL,
  `update_by` int(11) NOT NULL,
  `update_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ms_reminders`
--

CREATE TABLE `ms_reminders` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_id` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reminder_file_name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reminder_generated_date` datetime NOT NULL,
  `reminder_file_path` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `create_by` int(11) NOT NULL,
  `create_at` datetime NOT NULL,
  `update_by` int(11) NOT NULL,
  `update_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trx_account_mgmt`
--

CREATE TABLE `trx_account_mgmt` (
  `id` int(10) UNSIGNED NOT NULL,
  `trx_category` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `trx_amount` double(16,2) NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `create_by` int(11) NOT NULL,
  `create_at` datetime NOT NULL,
  `update_by` int(11) NOT NULL,
  `update_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `username` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `create_by` int(11) DEFAULT NULL,
  `create_at` datetime DEFAULT NULL,
  `update_by` int(11) DEFAULT NULL,
  `update_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `is_active`, `create_by`, `create_at`, `update_by`, `update_at`) VALUES
(3, 'admin', '$2y$10$UMmpMKrWBK2ohpvB9yqu1ePx47I0haLLm2kAaiEZwuKjwpbQ3Jd5y', 1, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD UNIQUE KEY `ms_customers_customer_id_unique` (`customer_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ms_incomings`
--
ALTER TABLE `ms_incomings`
  ADD PRIMARY KEY (`incoming_id`);

--
-- Indexes for table `ms_loans`
--
ALTER TABLE `ms_loans`
  ADD PRIMARY KEY (`loan_id`),
  ADD KEY `ms_loans_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `ms_outgoings`
--
ALTER TABLE `ms_outgoings`
  ADD PRIMARY KEY (`outgoing_id`);

--
-- Indexes for table `ms_reminders`
--
ALTER TABLE `ms_reminders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ms_reminders_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `trx_account_mgmt`
--
ALTER TABLE `trx_account_mgmt`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `users_user_id_unique` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `ms_incomings`
--
ALTER TABLE `ms_incomings`
  MODIFY `incoming_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;
--
-- AUTO_INCREMENT for table `ms_loans`
--
ALTER TABLE `ms_loans`
  MODIFY `loan_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `ms_outgoings`
--
ALTER TABLE `ms_outgoings`
  MODIFY `outgoing_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ms_reminders`
--
ALTER TABLE `ms_reminders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `trx_account_mgmt`
--
ALTER TABLE `trx_account_mgmt`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `ms_loans`
--
ALTER TABLE `ms_loans`
  ADD CONSTRAINT `ms_loans_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`);

--
-- Constraints for table `ms_reminders`
--
ALTER TABLE `ms_reminders`
  ADD CONSTRAINT `ms_reminders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
