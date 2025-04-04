# Adminer-HideTables

[![Adminer](https://img.shields.io/badge/adminer-%3E%3D5.0-blue)](https://www.adminer.org)

Plugin to Adminer for hiding tables in the left panel

- How to install plugins to Adminer: http://www.adminer.org/plugins/
- About plugin on http://www.kutac.cz/blog/weby-a-vse-okolo/adminer-skryvani-tabulek/  (🇨🇿 - Czech only)
- `tables-hide.min.php` contains minifed JS and CSS using https://www.minifier.org

## Usage

More about usage plugins in Adminer is on http://www.adminer.org/plugins/

```php
$plugins = [
    new AdminerTablesHide(),
];
```

### Changelog
**v2.0 - 11.3.2025**
- Support Adminer 5

**v1.1 - 9.3.2018**
- Fix JS according to CSP (Content Security Policy). Added in Adminer [4.4.0 (released 2018-01-17)](https://github.com/vrana/adminer/blob/master/changes.txt)
- Save tables to LocalStorage not cookies
- Improve filtering - was slow with too many tables
