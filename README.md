# Plato
Plato Project : Interface

## Installation of the interface

Clone the repository to the local destination you want. 

###### Required :
* HTTP Server (Apache, Nginx, or others)
* PHP 5+
* a MySQL database server : MySQL or MariaDB for example 

## Installation of drivers for communication between Windows Server/MSSQL and Unix/MySQL

You don't need this step if you start your database from scratch with no existing data.

It's a bit more complicated :

UnixODBC and FreeTDS driver compialtion and configuration has been taken from the http://it.toolbox.com/wiki/index.php/Ubuntu_Debian_FreeTDS_ODBC and from http://pzuk.wordpress.com/2012/02/03/how-to-make-freetds-unixodbc-and-qt-working-together/

Here are instructions for getting tsql and isql to play nice together.
Apt-getting doesn’t always get you all the tools you need to develop against SQLserver and Sybase.
Gather some important packages:

	
apt-get install libtool bison autotools-dev g++ build-essential tcsh unixodbc-dev tdsodbc


Create unixODBC repository
<pre>
cd /usr/local/
mkdir unixODBC
</pre>

Download, extract and compile unixODBC driver manager:
<pre>
wget http://www.unixodbc.org/unixODBC-2.2.14.tar.gz
tar xvf unixODBC-2.2.14.tar
cd unixODBC-2.2.14
./configure --prefix /usr/local/unixODBC --enable-gui=no 
make
make install
</pre>

Default installation dir is: /usr/local/bin/. Now, follow the above instructions replacing the configure line for freetds with this:

Download, extract and compile FreeTDS driver:

<pre>
wget ftp://ftp.ibiblio.org/pub/Linux/ALPHA/freetds/stable/freetds-stable.tgz
tar xzvf freetds-stable.tgz
cd freetds-Version/
./configure --prefix=/usr/local --sysconfdir=/usr/local/etc --with-unixodbc=/usr/local/unixODBC --with-tdsver=8.0
 make && make install clean
</pre>

Create a file named freetds.conf in /usr/local/etc 

<pre>
cd /usr/local/etc
touch freetds.conf
</pre>

with the following contents:
<pre>
[plato]
host = 147.100.103.188
port = 1433
client charset = ISO-8859-1
tds version = 8.0
dump file = /var/log/freetds.log
</pre>

