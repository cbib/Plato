# Plato
Plato Project : Web HCI designed for 96 well plates data storage and processing. Users can store analytes, freshweights, batches, rawdata and processed data. The processing is done automatically and can be exported as an excell or csv format.
Some features are for the administrator only, like suppression insertion of users, rawdata, analytes, experiments...


## Installation of the interface

Clone the repository to the local destination you want (/var/www/html/ for example). 

###### Required :
* HTTP Server (Apache, Nginx, or others)
* PHP 5+
* MySQL database server : MySQL or MariaDB for example 

## Installation of drivers for communication between Windows Server/MSSQL and Unix/MySQL

**/!\ You don't need this step if you start your database from scratch with no existing data.**
See readme in perl/ folder.

###### In plato_sql_structure.sql
Change the name of the database :
<pre>
CREATE DATABASE IF NOT EXISTS `dababase_name` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `dababase_name`;
</pre>

Then import into mysql :
<pre>
mysql -u username -ppasswd < plato_sql_structure.sql
</pre>

Change database name in connection.pl

Launch the scripts

<pre>
* ./reset.pl  #format the database
* ./export_db.pl # export data from the MSSQL database
* ./free_inuse.pl # fix some lacking informations
</pre>

export_db.pl may take a while depending how much datas you have. Better launch it on friday if you have more than a thousand batches...
