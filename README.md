# Plato
Plato Project : Web HCI designed for 96 well plates data storage and processing. Users can store analytes, freshweights, batches, rawdata and processed data. The processing is done automatically and can be exported as an excell or csv format.
Some features are for the administrator only, like suppression insertion of users, rawdata, analytes, experiments...


## Installation of the Web interface

Clone the repository to the local destination you want (/var/www/html/ for example). 

###### Required :
* HTTP Server (Apache, Nginx, or others)
* PHP 5+, php_mysql (PDO)
* MySQL database server : MySQL or MariaDB for example 

## Installation of drivers for communication between Windows Server/MSSQL and Unix/MySQL
**/!\ You don't need this step if you start your database from scratch with no existing data./!\**

See MSSQL_connection.md

## MySQL database setup

In this step you will use command line for MySQL and perl scripts

#### Scripts preparation

Go to perl/

<pre>
cd perl/
</pre>

Choose a name for your database and replace it in plato_sql_structure.sql :

<pre>
CREATE DATABASE IF NOT EXISTS `dababase_name` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `dababase_name`;
</pre>

Do some changes in connections.pm, for local and remote connection fill with the right param :

<pre>
	# db connection param
	my $bd		= 'dbname';
	my $serveur	= '100.100.100.100';	  	# IP adress
	my $identifiant = 'login';	  	# id 
	my $motdepasse	= 'passwd';			# Pwd
</pre>

### Script launch 

####Import into mysql :

<pre>
mysql -u username -ppasswd < plato_sql_structure.sql
</pre>

####Launch the scripts

<pre>
* ./reset_database.pl  #reset and format the database
</pre>
If you have to import data from a MSSQL database follow these steps : 
<pre>
* ./export_db.pl # export data from the MSSQL database
* ./free_inuse.pl # fix some lacking informations
* ./convert_utf8.pl
</pre>

export_db.pl may take a while depending how much datas you have. Better launch it on friday if you have more than a thousand batches...

After that you website is on.
To connect to different pages you have to add a first user (this has to be an admin)
change the sha256 string with one that you have generated on internet here  (http://www.xorbin.com/tools/sha256-hash-calculator) for exemple.

<pre>
    mysql -u username -ppasswd < user.sql
</pre>

Now enjoy !
