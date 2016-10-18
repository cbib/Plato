#!/usr/bin/perl

#############################################################################################
#
# Script name : free_inuse.pl // Check if a freshweight is free or in use
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
use Term::UI;
use Term::ReadLine;
use Encode qw(encode_utf8);
use utf8;
use export_db;
use connections;
use Carp;

####### Global vars #######
our $dbh = export_db::local_db_connector();

my $sth = $dbh->prepare("SELECT DISTINCT bat_data_sample_aliquot_FK FROM batch_data");
$sth->execute();

my $row;
while ($row = $sth->fetchrow_arrayref()) {
    #print "@$row\n";
    my $statement = "UPDATE sample_aliquot SET spl_alq_state = 'in use' WHERE spl_alq_id = @$row[0]";
    my $sth5 = $dbh->prepare($statement);
	$sth5->execute or die "Can't Add record : $dbh->errstr";
	$sth5->finish();
}

$sth->finish();
$dbh->disconnect();
print "Finished !";

