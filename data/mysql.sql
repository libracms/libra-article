-- phpMyAdmin SQL Dump
-- version 3.3.9.2
-- http://www.phpmyadmin.net
--
-- Host: pma
-- Generation Time: Sep 15, 2012 at 01:03 PM
-- Server version: 5.1.41
-- PHP Version: 5.3.5-0.dotdeb.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `libra-cms`
--

-- --------------------------------------------------------

--
-- Table structure for table `article`
--

CREATE TABLE IF NOT EXISTS `article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `locale` varchar(255) NOT NULL,
  `heading` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `uid` varchar(64) NOT NULL,
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  `modified` datetime NOT NULL,
  `modifiedBy` int(11) NOT NULL,
  `ordering` int(11) NOT NULL,
  `state` varchar(255) NOT NULL,
  `rev` int(11) NOT NULL COMMENT 'latest revision #',
  `content` longtext NOT NULL,
  `params` longtext NOT NULL COMMENT '(DC2Type:array)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`locale`,`alias`),
  UNIQUE KEY `uid` (`uid`,`locale`),
  KEY `state` (`state`),
  KEY `locale` (`locale`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=79 ;

--
-- Dumping data for table `article`
--

INSERT INTO `article` (`id`, `locale`, `heading`, `alias`, `uid`, `created`, `createdBy`, `modified`, `modifiedBy`, `ordering`, `state`, `rev`, `content`, `params`) VALUES
(5, 'ru-RU', 'Главная', 'home', '1', '2012-03-20 02:21:51', 0, '2012-04-21 11:29:14', 0, -1, 'published', 0, '<p>\r\n	Добро пожаловать на сайт Либра ЦМС.</p>\r\n<p>\r\n	<a href="/en/index/download">Здесь</a> можно скачать исходный код</p>\r\n', 'C:22:"Zend\\Stdlib\\Parameters":65:{x:i:2;a:2:{s:8:"metaKeys";s:0:"";s:8:"metaDesc";s:0:"";};m:a:0:{}}'),
(16, '', 'Home', 'home', '1', '2012-04-02 16:42:40', 0, '2012-09-15 08:57:32', 0, -1, 'published', 1, '<h2>\r\n	Desctiption</h2>\r\n<p>\r\n	<strong>Libra CMS</strong> -&nbsp; is simple Content Management System (CMS) written at <strong>Zend Framework 2</strong> and existing modules from <a href="http://modules.zendframework.com/">http://modules.zendframework.com/</a> .</p>\r\n<p>\r\n	Base concepts:</p>\r\n<p>\r\n	It show functionality of new ZF2 framework and I hope It will be a good guid for novices to utilize feateures of ZF 2.</p>\r\n<p>\r\n	Features:</p>\r\n<ul>\r\n	<li>\r\n		It has SEO freindly URL and meta kewrods/description fields.</li>\r\n	<li>\r\n		It has <a href="http://demo.libra-cms.ejoom.com/admin/">administration</a> and article managment. (login admin, password: demo12 ). By stability reasons of this site It was closed. Demo accessible at <a href="http://demo.libra-cms.ejoom.com" target="_blank">demo.libra-cms.ejoom.com</a>, as well as you can view <a href="/en/images">images</a> of administration.</li>\r\n	<li>\r\n		It created with supporting multilanguage. Now site done for 2 languages Russian and Eanglish.</li>\r\n	<li>\r\n		It has login and registraion of new users system by ZfcUsers module and custom redirect page after sign in.</li>\r\n	<li>\r\n		It has user managment.</li>\r\n	<li>\r\n		It has autogenerating menu.</li>\r\n	<li>\r\n		It has simple article editing by FCKEditor,</li>\r\n	<li>\r\n		It use twitter bootstrap for form and design. So you can serfe site at mobile.</li>\r\n	<li>\r\n		It will be based at jQuery.</li>\r\n</ul>\r\n<p>\r\n	You can download code <a href="/en/index/download">this</a>&nbsp;&nbsp;&nbsp; (for administration: user: admin, password: libra-cms).</p>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	With any questions, suggestions or&nbsp; comments write me at <a href="mailto:duke@ejoom.com">duke@ejoom.com</a></p>\r\n<p>\r\n	I&#39;m also available via&nbsp; <a href="irc://irc.freenode.net/zftalk">#zftalk on Freenode</a>. Use free to ask.</p>\r\n', 'C:22:"Zend\\Stdlib\\Parameters":101:{x:i:2;a:3:{s:9:"headTitle";s:0:"";s:12:"metaKeywords";s:0:"";s:15:"metaDescription";s:0:"";};m:a:0:{}}'),
(17, '', 'Privacy, Disclaimer, Terms & Conditions, and Copyright Info', 'disclaimer', '4f79d91fdef8c', '2012-04-02 19:51:43', 0, '2012-04-21 10:11:38', 0, -10, 'unpublished', 0, '<p>\r\n	Copyright (c) 2008-2012 eJoom Software<br />\r\n	All rights reserved.<br />\r\n	<br />\r\n	Redistribution and use in source and binary forms, with or without<br />\r\n	modification, are permitted provided that the following conditions are met:<br />\r\n	<br />\r\n	1. Redistributions of source code must retain the above copyright<br />\r\n	&nbsp;&nbsp; notice, this list of conditions and the following disclaimer.<br />\r\n	<br />\r\n	2. Redistributions in binary form must reproduce the above copyright<br />\r\n	&nbsp;&nbsp; notice, this list of conditions and the following disclaimer in the<br />\r\n	&nbsp;&nbsp; documentation and/or other materials provided with the distribution.<br />\r\n	<br />\r\n	3. Neither the name of the eJoom Software nor the names of its contributors<br />\r\n	&nbsp;&nbsp; may be used to endorse or promote products derived from this software<br />\r\n	&nbsp;&nbsp; without specific prior written permission.<br />\r\n	<br />\r\n	THIS SOFTWARE IS PROVIDED BY EJOOM SOFTWARE ``AS IS`` AND ANY<br />\r\n	EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED<br />\r\n	WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE<br />\r\n	DISCLAIMED. IN NO EVENT SHALL &lt;COPYRIGHT HOLDER&gt; BE LIABLE FOR ANY<br />\r\n	DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES<br />\r\n	(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;<br />\r\n	LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND<br />\r\n	ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT<br />\r\n	(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS<br />\r\n	SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.<br />\r\n	<br />\r\n	&nbsp;</p>\r\n', 'C:22:"Zend\\Stdlib\\Parameters":65:{x:i:2;a:2:{s:8:"metaKeys";s:0:"";s:8:"metaDesc";s:0:"";};m:a:0:{}}'),
(39, 'en-US', 'English menu item', 'en-page', '4f8c3c0aeb35d', '2012-04-16 18:34:34', 0, '2012-04-18 10:42:54', 0, 0, 'published', 0, '<p>\r\n	This mneu item will not be visible at another language.</p>\r\n', 'C:22:"Zend\\Stdlib\\Parameters":65:{x:i:2;a:2:{s:8:"metaKeys";s:0:"";s:8:"metaDesc";s:0:"";};m:a:0:{}}'),
(40, 'ru-RU', 'Русский пункт меню', 'ru-page', '4f8c3c4339394', '2012-04-16 18:35:31', 0, '2012-04-18 10:42:24', 0, 0, 'published', 0, '<p>\r\n	Пункт меню в русской локали. Этот пункт меню не будет отображаться на других языках.</p>\r\n', 'C:22:"Zend\\Stdlib\\Parameters":65:{x:i:2;a:2:{s:8:"metaKeys";s:0:"";s:8:"metaDesc";s:0:"";};m:a:0:{}}'),
(42, '', 'Multilanguage page', 'multilanguage', '4f8c4324f2cc4', '2012-04-16 19:04:52', 0, '2012-04-16 19:04:52', 0, 0, 'unpublished', 0, '<p>\r\n	This article at all languages</p>\r\n', 'C:22:"Zend\\Stdlib\\Parameters":65:{x:i:2;a:2:{s:8:"metaKeys";s:0:"";s:8:"metaDesc";s:0:"";};m:a:0:{}}'),
(43, 'en-US', 'Multilanguage page', 'multilanguage', '4f8c3cd321141', '2012-04-16 19:05:48', 0, '2012-04-18 11:06:55', 0, 0, 'published', 0, '<p>\r\n	Page at Eanglish.</p>\r\n<p>\r\n	Та же страница но на английском</p>\r\n', 'C:22:"Zend\\Stdlib\\Parameters":65:{x:i:2;a:2:{s:8:"metaKeys";s:0:"";s:8:"metaDesc";s:0:"";};m:a:0:{}}'),
(44, 'ru-RU', 'Мултиязыковая страница', 'multilanguage', '4f8c3cd321141', '2012-04-16 19:06:29', 0, '2012-04-18 11:06:40', 0, 0, 'published', 0, '<p>\r\n	Страница на русском.</p>\r\n<p>\r\n	Same english page in russian locale.</p>\r\n', 'C:22:"Zend\\Stdlib\\Parameters":65:{x:i:2;a:2:{s:8:"metaKeys";s:0:"";s:8:"metaDesc";s:0:"";};m:a:0:{}}'),
(45, '', 'FAQ2', 'faq', '4f8d427a14891', '2012-04-17 13:14:18', 0, '2012-08-28 11:00:37', 0, 5, 'published', 0, '<p>\r\n	Why this source not in github or bitbucket: 2</p>\r\n<p>\r\n	Because I did&#39;n wish put in repo hevy sized vendors like FCKEditor. When I separate it and prepare all configs I&#39;ll put it to public repo.</p>\r\n', 'C:22:"Zend\\Stdlib\\Parameters":90:{x:i:2;a:2:{s:8:"metaKeys";s:4:"meta";s:15:"metaDescription";s:12:"description2";};m:a:0:{}}'),
(46, '', 'Images', 'images', '4f8e6e6a9dd05', '2012-04-18 10:34:02', 0, '2012-04-18 11:04:23', 0, 3, 'published', 0, '<p>\r\n	<a href="/images/stories/images/Selection_010.jpg"><img alt="" src="/images/stories/images/Selection_010.jpg" style="width: 200px; height: 128px;" /></a>&nbsp; &nbsp; <a href="/images/stories/images/Selection_007.jpg"><img alt="" src="/images/stories/images/Selection_007.jpg" style="width: 199px; height: 129px;" /></a><a href="/images/stories/images/Selection_007.jpg"> </a></p>\r\n<p>\r\n	<a href="/images/stories/images/Selection_008.jpg"><img alt="" src="/images/stories/images/Selection_008.jpg" style="width: 200px; height: 131px;" /></a> &nbsp; &nbsp;&nbsp; <a href="/images/stories/images/Selection_009.jpg"><img alt="" src="/images/stories/images/Selection_009.jpg" style="width: 200px; height: 131px;" /></a></p>\r\n', 'C:22:"Zend\\Stdlib\\Parameters":65:{x:i:2;a:2:{s:8:"metaKeys";s:0:"";s:8:"metaDesc";s:0:"";};m:a:0:{}}'),
(47, '', 'Authors', 'authors', '4f8e7560f31c3', '2012-04-18 11:03:44', 0, '2012-04-21 14:55:47', 0, 7, 'published', 0, '<p>\r\n	This CMS was created by duke. Inspired by Zend Framework and Joomla CMS.</p>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	<img alt="" src="/images/stories/images/me.jpg" style="width: 100px; height: 133px;" /></p>\r\n<p>\r\n	Vitalii</p>\r\n', 'C:22:"Zend\\Stdlib\\Parameters":65:{x:i:2;a:2:{s:8:"metaKeys";s:0:"";s:8:"metaDesc";s:0:"";};m:a:0:{}}'),
(48, '', 'Roadmap', 'roadmap', '4fb633ba93df5', '2012-05-18 14:34:18', 0, '2012-05-18 14:38:36', 0, 2, 'published', 0, '<p>\r\n	The developing of&nbsp; Libra CMS postponed on the time of release zf2 beta4.&nbsp; When will be published convinient routing, view paths, and new Zend\\Form concepts.</p>\r\n', 'C:22:"Zend\\Stdlib\\Parameters":85:{x:i:2;a:2:{s:8:"metaKeys";s:19:"zf2, libra cms, cms";s:8:"metaDesc";s:0:"";};m:a:0:{}}'),
(52, '', 'new test', 'testa', '50435ef447499', '2012-09-02 13:28:20', 0, '2012-09-12 19:14:57', 0, 0, 'published', 18, '<p>\r\n	setete</p>\r\n', 'C:22:"Zend\\Stdlib\\Parameters":137:{x:i:2;a:3:{s:9:"headTitle";s:0:"";s:12:"metaKeywords";s:18:"some meta keywords";s:15:"metaDescription";s:16:"meta description";};m:a:0:{}}'),
(78, 'en-US', 'new', 'new-all', '50435ef447499', '2012-09-08 09:20:30', 0, '2012-09-12 19:12:04', 0, 0, 'published', 14, '<p>\r\n	sdfdsf</p>\r\n', 'C:22:"Zend\\Stdlib\\Parameters":101:{x:i:2;a:3:{s:9:"headTitle";s:0:"";s:12:"metaKeywords";s:0:"";s:15:"metaDescription";s:0:"";};m:a:0:{}}');
