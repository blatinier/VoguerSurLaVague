
-- --------------------------------------------------------

CREATE TABLE allmystats_archive (
  annee int(11) NOT NULL default '0',
  mois int(11) NOT NULL default '0',
  visiteur int(11) NOT NULL default '0',
  visite int(11) NOT NULL default '0',
  visites_hors_bot int(11) NOT NULL default '0',
  pages_hors_bot varchar(11) NOT NULL default '0',
  visites_robot varchar(11) NOT NULL default '0',
  pages_robots varchar(11) NOT NULL default '0',
  PRIMARY KEY  (annee,mois)
) TYPE=MyISAM;


-- --------------------------------------------------------

-- 
-- Structure de la table allmystats_page
-- 

CREATE TABLE allmystats_page (
  code bigint(20) NOT NULL default '0',
  page varchar(200) NOT NULL default '',
  nb_visite int(11) NOT NULL default '0',
  heure varchar(5) NOT NULL default '',
  PRIMARY KEY  (code,page)
) TYPE=MyISAM;


-- --------------------------------------------------------

-- 
-- Structure de la table allmystats_visiteur
-- 

CREATE TABLE allmystats_visiteur (
  agent varchar(255) default NULL,
  referer varchar(255) default NULL,
  ip varchar(50) NOT NULL default '',
  date varchar(10) default NULL,
  host varchar(100) default NULL,
  code bigint(20) NOT NULL auto_increment,
  domaine varchar(50) default NULL,
  nb_visite int(11) NOT NULL default '0',
  PRIMARY KEY  (code),
  KEY ADDR (ip)
) TYPE=MyISAM;


-- --------------------------------------------------------
