-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- 主机： 127.0.0.1
-- 生成日期： 2019-02-03 12:26:57
-- 服务器版本： 10.1.36-MariaDB
-- PHP 版本： 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `ptest`
--

-- --------------------------------------------------------

--
-- 表的结构 `share`
--

CREATE TABLE `share` (
  `id` int(11) NOT NULL,
  `uid` int(10) NOT NULL,
  `username` varchar(128) NOT NULL,
  `uuid` varchar(10) NOT NULL,
  `successn` int(10) NOT NULL,
  `mtime` datetime NOT NULL,
  `log` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `share`
--

INSERT INTO `share` (`id`, `uid`, `username`, `uuid`, `successn`, `mtime`, `log`) VALUES
(1, 3, 'Erohal', '6052', 0, '2019-02-03 13:57:57', 'Urim|');

--
-- 转储表的索引
--

--
-- 表的索引 `share`
--
ALTER TABLE `share`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `share`
--
ALTER TABLE `share`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
