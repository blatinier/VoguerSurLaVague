#!/usr/bin/env python

import ConfigParser
import MySQLdb
import os
import xml.etree.ElementTree as ET
import bs4

from datetime import datetime
from MySQLdb.cursors import DictCursor

# Read configuration
config = ConfigParser.ConfigParser()
config.read(os.path.join(os.path.abspath(os.path.dirname(__file__)),
                                  '..', 'config', 'config.ini'))

host = config.get('prod', 'host')
dbname = config.get('prod', 'dbname')
username = config.get('prod', 'username')
password = config.get('prod', 'password')

# Connect to DB
connection = MySQLdb.connect(host=host, user=username,
                             passwd=password, db=dbname,
                             cursorclass=DictCursor)
cursor = connection.cursor()

# Init RSS
rss = ET.Element('rss')
rss.set('version', '2.0')
channel = ET.SubElement(rss, 'channel')
title = ET.SubElement(channel, 'title')
title.text = 'Melmelboo'
link = ET.SubElement(channel, 'link')
link.text = 'https://www.melmelboo.fr'
description = ET.SubElement(channel, 'description')
description.text = 'Le blog de Melmelboo !'
language = ET.SubElement(channel, 'language')
language.text = 'fr'
lastBuildDate = ET.SubElement(channel, 'lastBuildDate')
lastBuildDate.text = datetime.today().strftime("%a, %d %b %Y %H:%M:%S GMT")
# List last articles to regenerate RSS
cursor.execute("""SELECT a.id AS aid,
                         a.titre AS atitle,
                         a.texte AS atext,
                         a.auteur AS author,
                         a.url AS url,
                         a.pubdate AS pubdate,
                         c.titre AS category
               FROM articles a
               LEFT JOIN category c ON c.id = a.cat
               WHERE pubdate < NOW() AND pubdate != "0000-00-00 00:00:00"
               ORDER BY a.pubdate DESC LIMIT 50""")
my_articles = cursor.fetchall()
for art in my_articles:
    item = ET.SubElement(channel, 'item')
    atitle = ET.SubElement(item, 'title')
    atitle.text = art['atitle'].decode("latin1")
    alink = ET.SubElement(item, 'link')
    alink.text = "https://www.melmelboo.fr/art-%s-%d" % (art['url'], art['aid'])
    adescription = ET.SubElement(item, 'description')
    clean = bs4.BeautifulSoup(art["atext"].decode("latin1")).get_text()[:150].strip()
    if clean:
        adescription.text = clean + "..."
    else:
        adescription.text = art['atitle'].decode("latin1")
    acontent = ET.SubElement(item, 'content')
    acontent.set("type", "html")
    acontent.text = art['atext'].decode("latin1")
    aauthor = ET.SubElement(item, 'author')
    aauthor.text = art['author']
    acategory = ET.SubElement(item, 'category')
    acategory.text = art['category'].decode("latin1")
    acomments = ET.SubElement(item, 'comments')
    acomments.text = "https://www.melmelboo.fr/art-%s-%d#firstcom" % (art['url'], art['aid'])
    aguid = ET.SubElement(item, 'guid')
    aguid.text = "%d" % (art['aid'])
    apubDate = ET.SubElement(item, 'pubDate')
    apubDate.text = art['pubdate'].strftime("%a, %d %b %Y %H:%M:%S GMT")
    asource = ET.SubElement(item, 'source')
    asource.set('url', 'https://www.melmelboo.fr/RSS/articles.rss')
    asource.text = "Les news de Melmelboo"

    tree = ET.ElementTree(rss)
    tree.write('articles.xml', encoding="utf8")
