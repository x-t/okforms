# okforms

Do you want to be that annoying f\*ck that keeps giving form links to 2 people instead of just asking them questions? Do you wish that you could create forms on a free/libre platform that no one will answer because it looks so bad? Say no more!  

Oh, also, it has polls.

## I don't want to set it up!
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
The tables are given in [sql.md](sql.md)  

## Setup
* Meet the requirements (see above)
* Set up the SQL server (see above)
* Change the values [vars.php](vars.php) to your liking
* Shove it all in your webroot

## Javascript
This page uses minified Javascript. The minified versions are already included, however for any changes make sure to create a minified version. I recommend [this](https://marketplace.visualstudio.com/items?itemName=HookyQR.minify) VSCode extension.

## License
This project is licensed under the MIT (Expat) license, for more info see the [LICENSE](LICENSE.md) file.

## LibreJS notice
Due to the lack of documentation on fixing up your JS files to meet the freedom standards, I chose not to support LibreJS. If anyone wants to make this LibreJS-compliant, please do so.