--
-- Table structure for table `#__t6_item`
-- Updated for MySQL strict mode (Joomla 6 compatibility)
--

CREATE TABLE `#__t6_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `asset_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Joomla Item reference',
  `asset_id` int(11) DEFAULT NULL,
  `tag_id` int(11) DEFAULT NULL,
  `content` mediumtext COLLATE utf8mb4_unicode_ci,
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update` timestamp NULL DEFAULT NULL,
  `rev` int(11) DEFAULT NULL,
  `working_content` mediumtext COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Table structure for table `#__t6_revision`
-- Updated for MySQL strict mode (Joomla 6 compatibility)
--

CREATE TABLE `#__t6_revision` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `itemid` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` mediumtext COLLATE utf8mb4_unicode_ci,
  `rev` int(11) DEFAULT NULL,
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `#__t6_tag`
--
