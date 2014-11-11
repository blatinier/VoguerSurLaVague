#!/usr/bin/env python

import ConfigParser
import MySQLdb
import os
import xml.etree.ElementTree as ET

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

# Get last update from home page
cursor.execute("SELECT MAX(pubdate) AS last_pub FROM articles WHERE pubdate < NOW()")
last_pubdate = cursor.fetchone()

# Create sitemap xml root element
sitemap = ET.Element('urlset')
sitemap.set('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9')

# Home page is frequently changed
url_element = ET.SubElement(sitemap, 'url')
loc = ET.SubElement(url_element, 'loc')
loc.text = "http://www.melmelboo.fr"
lastmod = ET.SubElement(url_element, 'lastmod')
lastmod.text = last_pubdate["last_pub"].strftime("%Y-%m-%d")
changefreq = ET.SubElement(url_element, 'changefreq')
changefreq.text = 'weekly'
priority = ET.SubElement(url_element, 'priority')
priority.text = "1.0"

# List all articles and add url to sitemap
cursor.execute("SELECT id, url, pubdate FROM articles WHERE pubdate < NOW() ORDER BY id DESC")
my_articles = cursor.fetchall()
for art in my_articles:
    url_element = ET.SubElement(sitemap, 'url')
    loc = ET.SubElement(url_element, 'loc')
    loc.text = "http://www.melmelboo.fr/art-%s-%d" % (art['url'], art['id'])
    lastmod = ET.SubElement(url_element, 'lastmod')
    try:
        lastmod.text = art['pubdate'].strftime("%Y-%m-%d")
    except:
        continue
    changefreq = ET.SubElement(url_element, 'changefreq')
    changefreq.text = 'never'
    priority = ET.SubElement(url_element, 'priority')
    priority.text = "0.8"

# List all categories and add url to sitemap
cursor.execute("SELECT id, slug FROM category WHERE type=0 ORDER BY id DESC")
my_categories = cursor.fetchall()
for cat in my_categories:
    cursor.execute("SELECT MAX(pubdate) AS pubdate FROM articles WHERE cat = %s AND pubdate < NOW()", cat['id'])
    art = cursor.fetchone()
    if art['pubdate'] is None:
        continue
    url_element = ET.SubElement(sitemap, 'url')
    loc = ET.SubElement(url_element, 'loc')
    loc.text = "http://www.melmelboo.fr/cat-%s-%d" % (cat['slug'], cat['id'])
    lastmod = ET.SubElement(url_element, 'lastmod')
    lastmod.text = art['pubdate'].strftime("%Y-%m-%d")
    changefreq = ET.SubElement(url_element, 'changefreq')
    changefreq.text = 'monthly'
    priority = ET.SubElement(url_element, 'priority')
    priority.text = "0.5"

tree = ET.ElementTree(sitemap)
tree.write('sitemap.xml', encoding="UTF-8")
