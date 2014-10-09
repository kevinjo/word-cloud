WordCloud
=========

[![Build Status (Travis)](https://img.shields.io/travis/Jleagle/word-cloud/master.svg)](https://travis-ci.org/Jleagle/word-cloud/builds)
[![Code Quality (scrutinizer)](https://scrutinizer-ci.com/g/Jleagle/word-cloud/badges/quality-score.png)](https://scrutinizer-ci.com/g/Jleagle/word-cloud)
[![Dependency Status (versioneye.com)](https://www.versioneye.com/php/Jleagle:word-cloud/badge.png)](https://www.versioneye.com/php/Jleagle:word-cloud)
[![Downloads Total](https://poser.pugx.org/Jleagle/word-cloud/downloads.svg)](https://packagist.org/packages/Jleagle/word-cloud)

This class makes it easy to generate word clouds, calculating the size of each word for you.

### Add a word

Add one word at a time:

```php
$wc = new WordCloud();
$wc->addWord('lorem');
$wc->addWord('ipsum');
```

Add an array of words:

```php
$wc->addWordsByArray(['lorem', 'ipsum']);
```

Add words from a delimited string:

```php
$wc->addWordsByString('lorem ipsum');
$wc->addWordsByString('lorem.ipsum', '.');
```

### Retrieve words

This will return an array of words, with counts and font sizes.

```php
$wc->getWords()
```

To return the words in size order or randomly:

```php
$wc->getWords('size', 'asc');
$wc->getWords('size', 'desc');
$wc->getWords('rand');
```

By default, words are returned alphabetically.

You can also set the font size range:

```php
$wc->getWords('size', 'asc', 20, 40);
```
