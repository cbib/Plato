#!/usr/bin/perl

#############################################################################################
#
# Script name : reset database // reset the db, has to be used each time you set up the db
# -----------
# Dev environment : - Ubuntu 14.04 x64
# ---------------   - perl, v5.18.2 built for x86_64-linux-gnu-thread-multi
#
#############################################################################################

## Dependancies
use strict;
use warnings;
use Data::Dumper;
use DBI;    
use DBD::ODBC;
use Encode qw(encode_utf8);
use utf8;
use Carp;
use export_db;
use connections;

####### Global vars #######
our $dbh = export_db::local_db_connector();


my @querys =(
"DELETE FROM standard_enzyme WHERE 1;",
"DELETE FROM enzyme_protocol WHERE 1;",
"DELETE FROM experiment_standard WHERE 1;",
"DELETE FROM sample_aliquot WHERE 1;",
"DELETE FROM batch_data WHERE 1;",
"DELETE FROM experiment_freshweight WHERE 1;",
"DELETE FROM freshweight_sample WHERE 1;",
"DELETE FROM unit WHERE 1;",
"DELETE FROM rawdata WHERE 1;",
"DELETE FROM enzyme WHERE 1;",
"DELETE FROM protocol WHERE 1;",
"DELETE FROM standard WHERE 1;",
"DELETE FROM equation WHERE 1;",
"DELETE FROM rawdata WHERE 1;",
"DELETE FROM batch WHERE 1;",
"DELETE FROM sample WHERE 1;",
"DELETE FROM aliquot WHERE 1;",
"DELETE FROM location WHERE 1;",
"DELETE FROM experiment WHERE 1;",
"DELETE FROM freshweight WHERE 1;",
"DELETE FROM processdata WHERE 1;",
"DELETE FROM raw_equ_proc WHERE 1;",
"ALTER TABLE `standard_enzyme` AUTO_INCREMENT = 1;",
"ALTER TABLE `enzyme_protocol` AUTO_INCREMENT = 1;",
"ALTER TABLE `experiment_standard` AUTO_INCREMENT = 1;",
"ALTER TABLE `experiment_freshweight` AUTO_INCREMENT = 1;",
"ALTER TABLE `freshweight_sample` AUTO_INCREMENT = 1;",
"ALTER TABLE `sample_aliquot` AUTO_INCREMENT = 1;",
"ALTER TABLE `raw_equ_proc` AUTO_INCREMENT = 1;",
"ALTER TABLE `batch_data` AUTO_INCREMENT = 1;",
"ALTER TABLE `unit` AUTO_INCREMENT = 1;",
"ALTER TABLE `rawdata` AUTO_INCREMENT = 1;",
"ALTER TABLE `enzyme` AUTO_INCREMENT = 1;",
"ALTER TABLE `protocol` AUTO_INCREMENT = 1;",
"ALTER TABLE `standard` AUTO_INCREMENT = 1;",
"ALTER TABLE `equation` AUTO_INCREMENT = 1;",
"ALTER TABLE `batch` AUTO_INCREMENT = 1;",
"ALTER TABLE `sample` AUTO_INCREMENT = 1;",
"ALTER TABLE `experiment` AUTO_INCREMENT = 1;",
"ALTER TABLE `freshweight` AUTO_INCREMENT = 1;",
"ALTER TABLE `location` AUTO_INCREMENT = 1;",
"ALTER TABLE `aliquot` AUTO_INCREMENT = 1;",
"ALTER TABLE `processdata` AUTO_INCREMENT = 1;",
"INSERT INTO unit VALUES ('','undefinedUnit');",
"INSERT INTO standard VALUES ('','undefinedStandard','','','','','','');",
"INSERT INTO experiment VALUES ('','undefinedExperiment');",
"INSERT INTO location VALUES ('','undefLocation','','','');",
"INSERT INTO sample VALUES ('','1','1');",
"INSERT INTO aliquot VALUES ('','','','','1');",
"INSERT INTO sample_aliquot VALUES ('','1','1','free');",
"INSERT INTO equation VALUES ('','undefinedEquation');");


my $i=0;
foreach my $line (@querys){
	my $sth = $dbh->prepare($line);
	$sth->execute or die "Can't Add record : $dbh->errstr";
	$sth->finish;
	$i++;
	print STDERR "$i/".$#querys."\r";
}
$dbh->disconnect();

print STDERR "Reset complete.\n";






























