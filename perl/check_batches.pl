#!/usr/bin/perl

#############################################################################################
#
# Script name : check_batches.pl // Check the number of batches in MSSQL db (one shot script)
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
my $rconn = export_db::remote_db_connector();


my $query = "SELECT * FROM Batches;";
my $result = $rconn->selectall_hashref($query, "UId") or die "\nERR=Sysdate\n $! \n $@\nDBI:errstr";	
my %batches = %$result;

print Dumper %batches;





