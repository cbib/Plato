#!/usr/bin/perl

#############################################################################################
#
# Script name : convert utf8 // Script to be sure that the database has not latin1 anywhere
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
		"ALTER TABLE aliquot CONVERT TO CHARACTER SET utf8 COLLATE utf8_bin;",
		"ALTER TABLE batch CONVERT TO CHARACTER SET utf8 COLLATE utf8_bin;",
		"ALTER TABLE batch_data CONVERT TO CHARACTER SET utf8 COLLATE utf8_bin;",
		"ALTER TABLE enzyme CONVERT TO CHARACTER SET utf8 COLLATE utf8_bin;",
		"ALTER TABLE enzyme_protocol CONVERT TO CHARACTER SET utf8 COLLATE utf8_bin;",
		"ALTER TABLE equation CONVERT TO CHARACTER SET utf8 COLLATE utf8_bin;",
		"ALTER TABLE experiment CONVERT TO CHARACTER SET utf8 COLLATE utf8_bin;",
		"ALTER TABLE experiment_freshweight CONVERT TO CHARACTER SET utf8 COLLATE utf8_bin;",
		"ALTER TABLE experiment_standard CONVERT TO CHARACTER SET utf8 COLLATE utf8_bin;",
		"ALTER TABLE freshweight CONVERT TO CHARACTER SET utf8 COLLATE utf8_bin;",
		"ALTER TABLE freshweight_sample CONVERT TO CHARACTER SET utf8 COLLATE utf8_bin;",
		"ALTER TABLE location CONVERT TO CHARACTER SET utf8 COLLATE utf8_bin;",
		"ALTER TABLE processdata CONVERT TO CHARACTER SET utf8 COLLATE utf8_bin;",
		"ALTER TABLE protocol CONVERT TO CHARACTER SET utf8 COLLATE utf8_bin;",
		"ALTER TABLE raw_equ_proc CONVERT TO CHARACTER SET utf8 COLLATE utf8_bin;",
		"ALTER TABLE rawdata CONVERT TO CHARACTER SET utf8 COLLATE utf8_bin;",
		"ALTER TABLE sample CONVERT TO CHARACTER SET utf8 COLLATE utf8_bin;",
		"ALTER TABLE sample_aliquot CONVERT TO CHARACTER SET utf8 COLLATE utf8_bin;",
		"ALTER TABLE standard CONVERT TO CHARACTER SET utf8 COLLATE utf8_bin;",
		"ALTER TABLE standard_enzyme CONVERT TO CHARACTER SET utf8 COLLATE utf8_bin;",
		"ALTER TABLE unit CONVERT TO CHARACTER SET utf8 COLLATE utf8_bin;",
		"ALTER TABLE user CONVERT TO CHARACTER SET utf8 COLLATE utf8_bin;");


my $i=0;
foreach my $line (@querys){
	my $sth = $dbh->prepare($line);
	$sth->execute or die "Can't Add record : $dbh->errstr";
	$sth->finish;
	$i++;
	print STDERR "$i/".$#querys."\r";
}
$dbh->disconnect();

print STDERR "Conversion complete.\n";



