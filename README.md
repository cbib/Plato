# Plato
Plato Project : Interface

## Installation of the interface

Clone the repository to the local destination you want. 

###### Required :
* HTTP Server (Apache, Nginx, or others)
* PHP 5+
* a MySQL database server : MySQL or MariaDB for example 

## Installation of drivers for communication between Windows Server/MSSQL and Unix/MySQL

**/!\ You don't need this step if you start your database from scratch with no existing data.**

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


You need to create a odbc.ini files or use an existing one. No need to use the odbcinst.ini files , just copy its content in odbc.ini file.


usr/local/etc/odbc.ini
<pre>
[plato]
Driver = /usr/local/lib/libtdsodbc.so
Description = plato database
Trace = Yes
Servername=plato
Port = 1433
Database = PlatoDB
UID = labdesigner
PASSWORD = glucose
</pre>


usr/local/etc/freetds.conf
<pre>
[plato]
host = 147.100.103.188
port = 1433
client charset = ISO-8859-1
tds version = 8.0
dump file = /var/log/freetds.log

</pre>

How to test unixODBC:
<pre>
odbcinst -s -q 	show available ODBC sources
odbcinst -d -q 	show available ODBC drivers
odbcinst -j 	show config
isql -v datasourcename login password 	conenct using data source name
</pre>

How to test freetds:
<pre>
tsql -H 127.0.0.1 -p 1433 -U sa -P password 	conenct using host name
tsql -S datasourcename -U sa -P password 	conenct using data source name from the freetds.conf file
</pre>

Note that application will use files:
<pre>
/usr/local/etc/odbcinst.ini
/usr/local/etc/odbc.ini
</pre>

but isql uses:

<pre>
/etc/odbcinst.ini
/etc/odbc.ini
</pre>

If you encounter problems for connection, create a ".odbc.ini" file in your home directory. this file must contain the same as "odbc.ini" file

#####On linux , just do the same steps as for mac os for install unixODBC and freetds. but instead of creating freetds.conf odbc.ini, just create a file .odbcinst.ini in your home directory. add this lines :

[FreeTDS]
Description = v0.91 with protocol v8.0
Driver = /usr/local/lib/libtdsodbc.so
Setup = /usr/local/lib/libtdsodbc.so
FileUsage = 1

You may encounter some problems...

## Use of Perl scripts
To use the perl scripts in perl/ you will need to complete the step above.

Command line :
<pre>
./data_transfert.pl database_name
</pre>

This will take a while depending how much datas you have. Better launch it on friday...
