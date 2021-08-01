## Installation, Update, De-Installation

### Requirements

- PHP 7.4.3 or later
- MediaWiki 1.35 or later

### Installation

There are two methods for installing Darkages: with or without [Composer][composer].

If you install Darkages with Composer, further required software packages will be installed
automatically. In this case, it is *not* necessary to install any dependencies. Composer will
take care of that.

If you install Darkages without Composer, you will need to install and enable the
[Bootstrap extension][bootstrap] before you install and enable Darkages.

If unsure try the detailed installation instructions for
[Windows](installation-windows.md) or [Linux](installation-linux.md).

Here is the short version:

#### Installation with Composer

On a command line go to your MediaWiki installation directory and run these two commands

```
COMPOSER=composer.local.json composer require --no-update mediawiki/darkages-skin:~3.0

composer update mediawiki/darkages-skin --no-dev -o
```

Then, open `LocalSettings.php` in an editor, and add the following lines:


```php
wfLoadExtension( 'Bootstrap' );
wfLoadSkin( 'darkages' );
```

Optional: to set Darkages as the default skin, find `$wgDefaultSkin` and amend it:

```php
$wgDefaultSkin='darkages';
```

Save the file. To verifying Darkages was installed correctly, navigate to _Special:Version_ on your wiki.

If you run into problems, try the
[troubleshooting](installation-troubleshooting.md).

#### Installation without Composer

1. Install and enable the [Bootstrap][bootstrap] extension.

2. [Download][download] Darkages and place the file(s) in a directory called **c**hameleon in your
    skins/ folder. 

3. Add the following code at the bottom of your LocalSettings.php:

   ```php
   wfLoadSkin( 'darkages' );
	```

   To set Darkages as the default skin, find `$wgDefaultSkin` and amend it:
   ```php
   $wgDefaultSkin='darkages';
   ```

4. __Done:__ Navigate to _Special:Version_ on your wiki to verify that the skin
   is successfully installed.

### Update with Composer

From your MediaWiki installation directory run `composer update "mediawiki/darkages-skin" --no-dev -o`

If you want to upgrade from Darkages 2.x to 3.x, first edit `composer.local.json`. Change `"mediawiki/darkages-skin": "~2.0"` to `"mediawiki/darkages-skin": "~3.0"`.

### De-installation with Composer

Before de-installation make sure you secure (move, backup) any custom files you
might want to retain.

Remove the Darkages skin from the `composer.local.json` file. Then run
`composer update "mediawiki/darkages-skin"` from the MediaWiki installation
directory.

[composer]: https://getcomposer.org/
[bootstrap]: https://www.mediawiki.org/wiki/Extension:Bootstrap
[download]: https://github.com/ProfessionalWiki/darkages/archive/master.zip
