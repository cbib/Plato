#!/usr/bin/perl
## Dependancies
use strict;
use warnings;
use Data::Dumper;
use DBI;    
use DBD::ODBC;
use Term::UI;
use Term::ReadLine;
use Encode qw(encode_utf8);
use utf8;
use export_db;
use Carp;

####### Global vars #######
our $dbh = local_db_connector();

my $sth = $dbh->prepare("SELECT DISTINCT bat_data_sample_aliquot_FK FROM batch_data");
$sth->execute();

my $row;
while ($row = $sth->fetchrow_arrayref()) {
    print "@$row\n";
    my $statement = "UPDATE sample_aliquot SET spl_alq_state = 'in use' WHERE spl_alq_id = @$row[0]";
    my $sth5 = $dbh->prepare($statement);
	$sth5->execute or die "Can't Add record : $dbh->errstr";
	$sth5->finish();
}

$sth->finish();
$dbh->disconnect();




sub local_db_connector{
	# Paramètres de connection à la base de données
	my $bd		= 'plato_export_14052016';
	my $serveur	= '127.0.0.1';	  # Il est possible de mettre une adresse IP
	my $identifiant = 'root';	  # identifiant 
	my $motdepasse	= 'r00t';
	my $port	= '';
	# Connection à la base de données mysql
	print "Connexion à la base de données $bd\n";
	my $lconn = DBI->connect( "DBI:mysql:database=$bd;host=$serveur;port=$port", 
		$identifiant, $motdepasse, { 
			RaiseError => 1,
		}
	) or die "Connection impossible à la base de données $bd !\n $! \n $@\n$DBI::errstr";
	return $lconn;
}



