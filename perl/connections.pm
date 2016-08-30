package export_db;
#############################################################################################
#
# Script name : connections.pm // library of local and remote connection (allow a gitignore)
# -----------
# Dev environment : - Ubuntu 14.04 x64
# ---------------   - perl, v5.18.2 built for x86_64-linux-gnu-thread-multi
#
#############################################################################################
use strict;
use warnings;
use Data::Dumper;

#################################################
#						DB connection						#
#################################################

#-------------------------------------------------------------remote connection (to plato)
sub remote_db_connector{
	# db connection param
	my $bd		= 'dbname';
	my $serveur	= '100.100.100.100';	  	# IP adress
	my $identifiant = 'login';	  	# id 
	my $motdepasse	= 'passwd';			# Pwd
	my $port	= '1433';
	my $dsn="dbi:ODBC:DSN=plato";
	print "Connexion à la base de données $bd\n";
	my $rconn = DBI->connect($dsn, $identifiant, $motdepasse);
	if (! defined($rconn) ) {
		 print "***Error connecting to DSN\n";
		 print "***Error was:\n";
		 print "***$DBI::errstr\n";         # $DBI::errstr is the error
	}
	return $rconn;
}

#--------------------------------------------------------------- local connection (to new db)
sub local_db_connector{
	# db connection param
	my $bd		= 'platolocalbdname';
	my $serveur	= '127.0.0.1';	  # IP adress
	my $identifiant = 'id';	  # id 
	my $motdepasse	= 'pwd';	  # Pwd
	my $port	= '';
	# Connection à la base de données mysql
	print "Connexion à la base de données $bd\n";
	my $lconn = DBI->connect( "DBI:mysql:database=$bd;host=$serveur;port=$port", 
		$identifiant, $motdepasse, { 
			RaiseError => 1,
		}
	) or die "Local Database connection is not possible $bd !\n $! \n $@\n$DBI::errstr";
	return $lconn;
}




1;
