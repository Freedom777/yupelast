-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 04, 2014 at 04:54 PM
-- Server version: 5.5.38
-- PHP Version: 5.4.31-1+deb.sury.org~precise+1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `last`
--

-- --------------------------------------------------------

--
-- Table structure for table `yupe_category_category`
--

CREATE TABLE IF NOT EXISTS `yupe_category_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `alias` varchar(150) NOT NULL,
  `lang` char(2) DEFAULT NULL,
  `name` varchar(250) NOT NULL,
  `image` varchar(250) DEFAULT NULL,
  `short_description` text,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ux_yupe_category_category_alias_lang` (`alias`,`lang`),
  KEY `ix_yupe_category_category_parent_id` (`parent_id`),
  KEY `ix_yupe_category_category_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `yupe_contact_provider`
--

CREATE TABLE IF NOT EXISTS `yupe_contact_provider` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `template` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `fa_css_class` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ux_yupe_contact_provider_code` (`code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `yupe_contact_provider`
--

INSERT INTO `yupe_contact_provider` (`id`, `code`, `name`, `template`, `icon`, `fa_css_class`) VALUES
(1, 'phone', 'Телефон', '', '', 'fa-phone'),
(2, 'skype', 'Скайп', 'skype:{%uid%}?chat', '', 'fa-skype'),
(3, 'vk', 'Вконтакте', 'http://vk.com/{%uid%}', '', 'fa-vk'),
(4, 'od', 'Одноклассники', 'http://ok.ru/profile/{%uid%}', '', 'fa-angellist'),
(5, 'address', 'Адрес', '', '', 'fa-home'),
(6, 'love.mail.ru', 'Лаврамблер', 'http://love.mail.ru/mb{%uid%}', '', 'fa-heart'),
(7, 'loveplanet.ru', 'Лавпленет', 'http://loveplanet.ru/page/{%uid%}', '', 'fa-heart'),
(8, 'email', 'Email', 'mailto:{%uid%}', '', 'fa-envelope');

-- --------------------------------------------------------

--
-- Table structure for table `yupe_contact_user`
--

CREATE TABLE IF NOT EXISTS `yupe_contact_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `provider_id` int(11) NOT NULL,
  `uid` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_yupe_contact_user_user_id` (`user_id`),
  KEY `fk_yupe_contact_user_provider_id` (`provider_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `yupe_contentblock_content_block`
--

CREATE TABLE IF NOT EXISTS `yupe_contentblock_content_block` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `code` varchar(100) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '1',
  `content` text NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ux_yupe_contentblock_content_block_code` (`code`),
  KEY `ix_yupe_contentblock_content_block_type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `yupe_gallery_gallery`
--

CREATE TABLE IF NOT EXISTS `yupe_gallery_gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `description` text,
  `status` int(11) NOT NULL DEFAULT '1',
  `owner` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ix_yupe_gallery_gallery_status` (`status`),
  KEY `ix_yupe_gallery_gallery_owner` (`owner`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `yupe_gallery_image_to_gallery`
--

CREATE TABLE IF NOT EXISTS `yupe_gallery_image_to_gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image_id` int(11) NOT NULL,
  `gallery_id` int(11) NOT NULL,
  `creation_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ux_yupe_gallery_image_to_gallery_gallery_to_image` (`image_id`,`gallery_id`),
  KEY `ix_yupe_gallery_image_to_gallery_gallery_to_image_image` (`image_id`),
  KEY `ix_yupe_gallery_image_to_gallery_gallery_to_image_gallery` (`gallery_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `yupe_image_image`
--

CREATE TABLE IF NOT EXISTS `yupe_image_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `name` varchar(250) NOT NULL,
  `description` text,
  `file` varchar(250) NOT NULL,
  `creation_date` datetime NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `alt` varchar(250) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `ix_yupe_image_image_status` (`status`),
  KEY `ix_yupe_image_image_user` (`user_id`),
  KEY `ix_yupe_image_image_type` (`type`),
  KEY `ix_yupe_image_image_category_id` (`category_id`),
  KEY `fk_yupe_image_image_parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `yupe_mail_mail_event`
--

CREATE TABLE IF NOT EXISTS `yupe_mail_mail_event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(150) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ux_yupe_mail_mail_event_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `yupe_mail_mail_template`
--

CREATE TABLE IF NOT EXISTS `yupe_mail_mail_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(150) NOT NULL,
  `event_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text,
  `from` varchar(150) NOT NULL,
  `to` varchar(150) NOT NULL,
  `theme` text NOT NULL,
  `body` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ux_yupe_mail_mail_template_code` (`code`),
  KEY `ix_yupe_mail_mail_template_status` (`status`),
  KEY `ix_yupe_mail_mail_template_event_id` (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `yupe_menu_menu`
--

CREATE TABLE IF NOT EXISTS `yupe_menu_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ux_yupe_menu_menu_code` (`code`),
  KEY `ix_yupe_menu_menu_status` (`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `yupe_menu_menu`
--

INSERT INTO `yupe_menu_menu` (`id`, `name`, `code`, `description`, `status`) VALUES
(1, 'Верхнее меню', 'top-menu', 'Основное меню сайта, расположенное сверху в блоке mainmenu.', 1);

-- --------------------------------------------------------

--
-- Table structure for table `yupe_menu_menu_item`
--

CREATE TABLE IF NOT EXISTS `yupe_menu_menu_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `regular_link` tinyint(1) NOT NULL DEFAULT '0',
  `title` varchar(150) NOT NULL,
  `href` varchar(150) NOT NULL,
  `class` varchar(150) NOT NULL,
  `title_attr` varchar(150) NOT NULL,
  `before_link` varchar(150) NOT NULL,
  `after_link` varchar(150) NOT NULL,
  `target` varchar(150) NOT NULL,
  `rel` varchar(150) NOT NULL,
  `condition_name` varchar(150) DEFAULT '0',
  `condition_denial` int(11) DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '1',
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `ix_yupe_menu_menu_item_menu_id` (`menu_id`),
  KEY `ix_yupe_menu_menu_item_sort` (`sort`),
  KEY `ix_yupe_menu_menu_item_status` (`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `yupe_menu_menu_item`
--

INSERT INTO `yupe_menu_menu_item` (`id`, `parent_id`, `menu_id`, `regular_link`, `title`, `href`, `class`, `title_attr`, `before_link`, `after_link`, `target`, `rel`, `condition_name`, `condition_denial`, `sort`, `status`) VALUES
(1, 0, 1, 1, 'Главная', '/', '', 'Главная страница сайта', '', '', '', '', '', 0, 1, 1),
(2, 0, 1, 0, 'Блоги', '/blog/blog/index', '', 'Блоги', '', '', '', '', '', 0, 2, 1),
(3, 2, 1, 0, 'Пользователи', '/user/people/index', '', 'Пользователи', '', '', '', '', '', 0, 3, 1),
(4, 0, 1, 0, 'Wiki', '/wiki/default/index', '', 'Wiki', '', '', '', '', '', 0, 9, 0),
(5, 0, 1, 0, 'Войти', '/login', 'login-text', 'Войти на сайт', '', '', '', '', 'isAuthenticated', 1, 11, 1),
(6, 0, 1, 0, 'Выйти', '/logout', 'login-text', 'Выйти', '', '', '', '', 'isAuthenticated', 0, 12, 1),
(7, 0, 1, 0, 'Регистрация', '/registration', 'login-text', 'Регистрация на сайте', '', '', '', '', 'isAuthenticated', 1, 10, 1),
(8, 0, 1, 0, 'Панель управления', '/yupe/backend/index', 'login-text', 'Панель управления сайтом', '', '', '', '', 'isSuperUser', 0, 13, 1),
(9, 0, 1, 0, 'FAQ', '/faq', '', 'FAQ', '', '', '', '', '', 0, 7, 1),
(10, 0, 1, 0, 'Контакты', '/contacts', '', 'Контакты', '', '', '', '', '', 0, 7, 1);

-- --------------------------------------------------------

--
-- Table structure for table `yupe_migrations`
--

CREATE TABLE IF NOT EXISTS `yupe_migrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(255) NOT NULL,
  `version` varchar(255) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_migrations_module` (`module`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

--
-- Dumping data for table `yupe_migrations`
--

INSERT INTO `yupe_migrations` (`id`, `module`, `version`, `apply_time`) VALUES
(1, 'user', 'm000000_000000_user_base', 1416570124),
(2, 'user', 'm131019_212911_user_tokens', 1416570124),
(3, 'user', 'm131025_152911_clean_user_table', 1416570126),
(4, 'user', 'm131026_002234_prepare_hash_user_password', 1416570127),
(5, 'user', 'm131106_111552_user_restore_fields', 1416570127),
(6, 'user', 'm131121_190850_modify_tokes_table', 1416570128),
(7, 'user', 'm140812_100348_add_expire_to_token_table', 1416570128),
(8, 'yupe', 'm000000_000000_yupe_base', 1416570129),
(9, 'yupe', 'm130527_154455_yupe_change_unique_index', 1416570129),
(10, 'category', 'm000000_000000_category_base', 1416570130),
(11, 'image', 'm000000_000000_image_base', 1416570131),
(12, 'mail', 'm000000_000000_mail_base', 1416570132),
(13, 'page', 'm000000_000000_page_base', 1416570135),
(14, 'page', 'm130115_155600_columns_rename', 1416570135),
(15, 'page', 'm140115_083618_add_layout', 1416570136),
(16, 'page', 'm140620_072543_add_view', 1416570136),
(17, 'menu', 'm000000_000000_menu_base', 1416570137),
(18, 'menu', 'm121220_001126_menu_test_data', 1416570137),
(19, 'contentblock', 'm000000_000000_contentblock_base', 1416570138),
(20, 'contentblock', 'm140715_130737_add_category_id', 1416570138),
(21, 'gallery', 'm000000_000000_gallery_base', 1416570140),
(22, 'gallery', 'm130427_120500_gallery_creation_user', 1416570141),
(23, 'queue', 'm000000_000000_queue_base', 1416570141),
(24, 'queue', 'm131007_031000_queue_fix_index', 1416570142),
(25, 'social', 'm000000_000000_social_profile', 1417432138);

-- --------------------------------------------------------

--
-- Table structure for table `yupe_page_page`
--

CREATE TABLE IF NOT EXISTS `yupe_page_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT NULL,
  `lang` char(2) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `creation_date` datetime NOT NULL,
  `change_date` datetime NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `change_user_id` int(11) DEFAULT NULL,
  `title_short` varchar(150) NOT NULL,
  `title` varchar(250) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `body` text NOT NULL,
  `keywords` varchar(250) NOT NULL,
  `description` varchar(250) NOT NULL,
  `status` int(11) NOT NULL,
  `is_protected` tinyint(1) NOT NULL DEFAULT '0',
  `order` int(11) NOT NULL DEFAULT '0',
  `layout` varchar(250) DEFAULT NULL,
  `view` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ux_yupe_page_page_slug_lang` (`slug`,`lang`),
  KEY `ix_yupe_page_page_status` (`status`),
  KEY `ix_yupe_page_page_is_protected` (`is_protected`),
  KEY `ix_yupe_page_page_user_id` (`user_id`),
  KEY `ix_yupe_page_page_change_user_id` (`change_user_id`),
  KEY `ix_yupe_page_page_menu_order` (`order`),
  KEY `ix_yupe_page_page_category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `yupe_queue_queue`
--

CREATE TABLE IF NOT EXISTS `yupe_queue_queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `worker` int(11) NOT NULL,
  `create_time` datetime NOT NULL,
  `task` text NOT NULL,
  `start_time` datetime DEFAULT NULL,
  `complete_time` datetime DEFAULT NULL,
  `priority` int(11) NOT NULL DEFAULT '1',
  `status` int(11) NOT NULL DEFAULT '0',
  `notice` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ux_yupe_queue_queue_worker` (`worker`),
  KEY `ux_yupe_queue_queue_priority` (`priority`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `yupe_social_user`
--

CREATE TABLE IF NOT EXISTS `yupe_social_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `provider` varchar(250) NOT NULL,
  `uid` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ux_yupe_social_user_uid` (`uid`),
  KEY `ix_yupe_social_user_provider` (`provider`),
  KEY `fk_yupe_social_user_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `yupe_user_tokens`
--

CREATE TABLE IF NOT EXISTS `yupe_user_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `type` int(11) NOT NULL,
  `status` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `expire` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ix_yupe_user_tokens_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `yupe_user_user`
--

CREATE TABLE IF NOT EXISTS `yupe_user_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `change_date` datetime NOT NULL,
  `first_name` varchar(250) DEFAULT NULL,
  `middle_name` varchar(250) DEFAULT NULL,
  `last_name` varchar(250) DEFAULT NULL,
  `nick_name` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `gender` tinyint(1) NOT NULL DEFAULT '0',
  `birth_date` date DEFAULT NULL,
  `site` varchar(250) NOT NULL DEFAULT '',
  `about` varchar(250) NOT NULL DEFAULT '',
  `location` varchar(250) NOT NULL DEFAULT '',
  `status` int(11) NOT NULL DEFAULT '2',
  `access_level` int(11) NOT NULL DEFAULT '0',
  `last_visit` datetime DEFAULT NULL,
  `registration_date` datetime NOT NULL,
  `avatar` varchar(150) DEFAULT NULL,
  `hash` varchar(255) NOT NULL DEFAULT 'a7cbdcf6b19a5e5e7c0e79e89a4661ce0.72337600 1416570126',
  `email_confirm` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ux_yupe_user_user_nick_name` (`nick_name`),
  UNIQUE KEY `ux_yupe_user_user_email` (`email`),
  KEY `ix_yupe_user_user_status` (`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `yupe_user_user`
--

INSERT INTO `yupe_user_user` (`id`, `change_date`, `first_name`, `middle_name`, `last_name`, `nick_name`, `email`, `gender`, `birth_date`, `site`, `about`, `location`, `status`, `access_level`, `last_visit`, `registration_date`, `avatar`, `hash`, `email_confirm`) VALUES
(1, '0000-00-00 00:00:00', '', '', '', 'admin', 'oleg_freedom@rambler.ru', 0, NULL, '', '', '', 1, 1, '2014-12-03 17:40:12', '2014-11-21 13:44:42', NULL, '$2a$13$DfAATqCoNdngvr8CDRVuO.H/zNwugr0wU3kwgLeh9Uin1lEk1.ta6', 1);

-- --------------------------------------------------------

--
-- Table structure for table `yupe_yupe_settings`
--

CREATE TABLE IF NOT EXISTS `yupe_yupe_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_id` varchar(100) NOT NULL,
  `param_name` varchar(100) NOT NULL,
  `param_value` varchar(255) NOT NULL,
  `creation_date` datetime NOT NULL,
  `change_date` datetime NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `type` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ux_yupe_yupe_settings_module_id_param_name_user_id` (`module_id`,`param_name`,`user_id`),
  KEY `ix_yupe_yupe_settings_module_id` (`module_id`),
  KEY `ix_yupe_yupe_settings_param_name` (`param_name`),
  KEY `fk_yupe_yupe_settings_user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `yupe_yupe_settings`
--

INSERT INTO `yupe_yupe_settings` (`id`, `module_id`, `param_name`, `param_value`, `creation_date`, `change_date`, `user_id`, `type`) VALUES
(1, 'yupe', 'siteDescription', 'Каталог пользователей', '2014-11-21 13:45:23', '2014-11-21 13:45:23', 1, 1),
(2, 'yupe', 'siteName', 'Каталог пользователей', '2014-11-21 13:45:23', '2014-11-21 13:45:23', 1, 1),
(3, 'yupe', 'siteKeyWords', 'Каталог, Пользователи', '2014-11-21 13:45:23', '2014-11-21 13:45:23', 1, 1),
(4, 'yupe', 'email', 'oleg_freedom@rambler.ru', '2014-11-21 13:45:23', '2014-11-21 13:45:23', 1, 1),
(5, 'yupe', 'theme', 'default', '2014-11-21 13:45:23', '2014-11-21 13:45:23', 1, 1),
(6, 'yupe', 'backendTheme', '', '2014-11-21 13:45:23', '2014-11-21 13:45:23', 1, 1);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `yupe_category_category`
--
ALTER TABLE `yupe_category_category`
  ADD CONSTRAINT `fk_yupe_category_category_parent_id` FOREIGN KEY (`parent_id`) REFERENCES `yupe_category_category` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION;

--
-- Constraints for table `yupe_contact_user`
--
ALTER TABLE `yupe_contact_user`
  ADD CONSTRAINT `yupe_contact_user_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `yupe_user_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `yupe_contact_user_ibfk_2` FOREIGN KEY (`provider_id`) REFERENCES `yupe_contact_provider` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `yupe_gallery_gallery`
--
ALTER TABLE `yupe_gallery_gallery`
  ADD CONSTRAINT `fk_yupe_gallery_gallery_owner` FOREIGN KEY (`owner`) REFERENCES `yupe_user_user` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION;

--
-- Constraints for table `yupe_gallery_image_to_gallery`
--
ALTER TABLE `yupe_gallery_image_to_gallery`
  ADD CONSTRAINT `fk_yupe_gallery_image_to_gallery_gallery_to_image_gallery` FOREIGN KEY (`gallery_id`) REFERENCES `yupe_gallery_gallery` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_yupe_gallery_image_to_gallery_gallery_to_image_image` FOREIGN KEY (`image_id`) REFERENCES `yupe_image_image` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `yupe_image_image`
--
ALTER TABLE `yupe_image_image`
  ADD CONSTRAINT `fk_yupe_image_image_category_id` FOREIGN KEY (`category_id`) REFERENCES `yupe_category_category` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_yupe_image_image_parent_id` FOREIGN KEY (`parent_id`) REFERENCES `yupe_image_image` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_yupe_image_image_user_id` FOREIGN KEY (`user_id`) REFERENCES `yupe_user_user` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION;

--
-- Constraints for table `yupe_mail_mail_template`
--
ALTER TABLE `yupe_mail_mail_template`
  ADD CONSTRAINT `fk_yupe_mail_mail_template_event_id` FOREIGN KEY (`event_id`) REFERENCES `yupe_mail_mail_event` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `yupe_menu_menu_item`
--
ALTER TABLE `yupe_menu_menu_item`
  ADD CONSTRAINT `fk_yupe_menu_menu_item_menu_id` FOREIGN KEY (`menu_id`) REFERENCES `yupe_menu_menu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `yupe_page_page`
--
ALTER TABLE `yupe_page_page`
  ADD CONSTRAINT `fk_yupe_page_page_category_id` FOREIGN KEY (`category_id`) REFERENCES `yupe_category_category` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_yupe_page_page_change_user_id` FOREIGN KEY (`change_user_id`) REFERENCES `yupe_user_user` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_yupe_page_page_user_id` FOREIGN KEY (`user_id`) REFERENCES `yupe_user_user` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION;

--
-- Constraints for table `yupe_social_user`
--
ALTER TABLE `yupe_social_user`
  ADD CONSTRAINT `fk_yupe_social_user_user_id` FOREIGN KEY (`user_id`) REFERENCES `yupe_user_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `yupe_user_tokens`
--
ALTER TABLE `yupe_user_tokens`
  ADD CONSTRAINT `fk_yupe_user_tokens_user_id` FOREIGN KEY (`user_id`) REFERENCES `yupe_user_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `yupe_yupe_settings`
--
ALTER TABLE `yupe_yupe_settings`
  ADD CONSTRAINT `fk_yupe_yupe_settings_user_id` FOREIGN KEY (`user_id`) REFERENCES `yupe_user_user` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;