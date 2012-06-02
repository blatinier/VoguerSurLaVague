-- --------------------------------------------------------

-- 
-- Structure de la table `allmystats_bad_user_agent`
-- 

CREATE TABLE `allmystats_bad_user_agent` (
  `id` int(5) NOT NULL auto_increment,
  `user_agent` varchar(255) NOT NULL default '',
  `info` varchar(255) NOT NULL default '',
  `type` char(1) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=50 ;

-- 
-- Contenu de la table `allmystats_bad_user_agent`
-- 

INSERT INTO `allmystats_bad_user_agent` (`id`, `user_agent`, `info`, `type`) VALUES 
(1, 'Mozilla/4.0 (compatible; MSIE 5.0; Windows NT; DigExt; DTS Agent', 'Beijing Express Email Address Extractor via DHCP Data Transport Services (DTS)', 'S'),
(3, 'Mozilla/3.0 (compatible)', 'Unknown', 'I'),
(13, 'Mozilla/4.0', 'Unknown', 'I'),
(6, 'Mozilla/4.0 (compatible; Advanced Email Extractor v2.xx)', 'Advanced Email Extractor e-mail collector (spam bot)', 'S'),
(7, 'Mozilla/4.0 (compatible; Iplexx Spider/1.0 http://www.iplexx.at)', 'Iplexx Austria (webhosting company) logfile spamming bot', 'S'),
(8, 'Mozilla/4.0 (compatible;)', 'Unknown', 'I'),
(16, '8484 Boston Project v 1.0', 'Unknown guestbook spamming or harvesting tool from diff. IPs', 'S'),
(17, 'atSpider/1.0', 'atSpider (ceased) email harvester / spambot ', 'S'),
(18, 'autoemailspider', 'Auto Email Pro Email harvester', 'S'),
(19, 'bwh3_user_agent', 'Basic Web Hacking 3 fake user-agent from Hellbound Hackers challenges', 'S'),
(20, 'ContactBot/0.2', 'Probably E-Mail harvesting robot - same as LMQueueBot', 'S'),
(21, 'ContentSmartz', 'ContentSmartz e-mail harvesting tools', 'S'),
(22, 'DataCha0s/2.0', 'Unknown bot from Kornet Korea (218.149.129.xxx) scans for Perl Awstats', 'S'),
(23, 'DBrowse 1.4d', 'Some site scanning tool via diff. IPs i.e.: - pacbell.net (67.112.xxx.xxx)', 'S'),
(24, 'Demo Bot DOT 16b', 'Some site scanning tool from 217.34.59.xxx (btopenworld.com)', 'S'),
(25, 'Demo Bot Z 16b', 'Some site scanning tool from 68.154.96.xx (bellsouth.net)', 'S'),
(26, 'DSurf15a 01', 'Some site scanning tool via diff. IPs i.e.: - cox.net (68.5.xxx.xxx) - pacbell.net (64.16x.xxx.xxx)', 'S'),
(27, 'DSurf15a 71', 'Some site scanning tool via diff. IPs i.e.: - cox.net (68.4.xxx.xxx)', 'S'),
(28, 'DSurf15a 81', 'Some site scanning tool via diff. IPs i.e.: - verizon.net (4.47.xxx.xxx)', 'S'),
(29, 'DSurf15a VA', 'Some site scanning tool via diff. IPs i.e.: - eastlink.ca (24.222.xxx.xxx) - cogeco.net (216.221.8x.xxx)', 'S'),
(30, 'EBrowse 1.4b', 'Some site scanning tool via diff. IPs i.e.: - swbell.net (65.66.xxx.xxx)', 'S'),
(31, 'Educate Search VxB', 'Some site scanning tool via diff. IPs i.e.: - cox.net (68.4.xxx.xxx)', 'S'),
(32, 'EmailSiphon', 'Sonic E-mail collector', 'S'),
(33, 'EmailSpider', 'EmailSpider E-mail harvesting software', 'S'),
(34, 'EmailWolf 1.00', 'Trellian EMailWolf E-mail collector', 'S'),
(35, 'ESurf15a 15', 'Some site scanning tool via diff. IPs ', 'S'),
(36, 'ExtractorPro', 'Extractor Pro e-mail collector', 'S'),
(37, 'Franklin Locator 1.8', 'Some spam bot', 'S'),
(38, 'FSurf15a 01', 'Some site scanning tool via diff. IPs', 'S'),
(39, 'Full Web Bot 0416B', 'Some site scanning tool from diff. IPs i.e.: - 66.28.240.xx (cogentco.com) - 68.5.174.xx (cox.net)', 'S'),
(40, 'Full Web Bot 0516B', 'Some site scanning tool i.e. from - 68.154.96.xx (bellsouth.net)', 'S'),
(41, 'Full Web Bot 2816B', 'Some site scanning tool from 66.255.6.xxx (uslec.com)', 'S'),
(42, 'China Local Browse 2.6', 'Unknown spam bot from telekom.com.my (218.111.83.xxx)', 'S'),
(43, 'Industry Program 1.0.x', 'Spam bot from diff. IPs', 'S'),
(44, 'IUPUI Research Bot v 1.9a', 'Some spam bot from 66.139.78.xx(x)', 'S'),
(45, 'Lincoln State Web Browser', 'Some spam bot', 'S'),
(46, 'LWP::Simple/5.803', 'ThePlanet/jaja-jak-globusy.com Google Adsense refferer spam bot from 70.85.116.* / 70.84.128.xxx / 70.85.193.xxx', 'S'),
(47, 'Mac Finder 1.0.xx', 'Some spam bot', 'S'),
(48, 'Missauga Locate 1.0.0', 'Some spam bot', 'S');
