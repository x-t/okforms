# okforms

Create simple forms and polls anonymously.

## I don't want to set it up
There's a public instance on [f00f.xyz](https://okforms.f00f.xyz)

## Requirements

| PHP modules |
| ----------- |
| php-json    |
| php-mysqli  |

| SQL server |
| ---------- |
| MariaDB    |
| *or*       |
| MySQL      |

## SQL configuration
The tables are given in [SQL.md](SQL.md)  

## Setup
* Meet the requirements (see above)
* Set up the SQL server (see above)
* Change the values [settings.php](settings.php) to your liking
* Shove it all in your webroot

### Pro-tip
Disable PHP notices with `display_errors=0` in your php.ini config.

## Other software used in this project
| Name | Where | Link |
| ---- | ----- | ---- |
| FontAwesome | everywhere | https://fontawesome.com/ |

## Javascript/CSS
This page uses minified Javascript and CSS. The minified versions are already included, however for any changes make sure to create a minified version. I recommend [this](https://marketplace.visualstudio.com/items?itemName=olback.es6-css-minify) VSCode extension or [this](https://packagecontrol.io/packages/Minifier) Sublime Text extension.

## License
This project is licensed under the MIT (Expat) license, for more info see the [LICENSE](LICENSE.md) file.
