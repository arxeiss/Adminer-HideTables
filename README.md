# Adminer-HideTables
Plugin to Adminer for hiding tables in left list

tables-hide.min.php contains minifed JS and CSS using http://gpbmike.github.io/refresh-sf/

## Install

How to install plugins to Adminer: http://www.adminer.org/plugins/

About plugin on http://www.kutac.cz/blog/weby-a-vse-okolo/adminer-skryvani-tabulek/

### Changelog
**9.3.2018**
- Fix JS according to CSP (Content Security Policy). Added in Adminer [4.4.0 (released 2018-01-17)](https://github.com/vrana/adminer/blob/master/changes.txt)
- Save tables to LocalStorage not cookies
- Improve filtering - was slow with too many tables
