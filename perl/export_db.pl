#!/usr/bin/perl

#############################################################################################
#
# Script name : export_db.pl // Main export program
# -----------
# Dev environment : - Ubuntu 14.04 x64
# ---------------   - perl, v5.18.2 built for x86_64-linux-gnu-thread-multi
#
#############################################################################################
#
# Warning : 
# 
# Use : ./export_db.pl	
#
#############################################################################################
#
# History : 2015/04/01 				V0.0 Emmanuel Bouilhol
# History : 2016/02/20 				V1.0 Emmanuel Bouilhol
#
#############################################################################################
#
# Dependencies : - See called packages
#
#############################################################################################
#
# Improvements TBD : 
# - 
#
##############################################################################################

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
our $lconn;
our $rconn;

our %unit;
our @activity;
our %standard;
our %enzymecode;
our %experiment;
our %freshweight;
our %batches;
our @batchCompilation;
our @rawData;

######## LINKED HASH  ##########
our %linkUnit;
our %linkEnzyme;
our %linkStandard;
our %linkExperiment;
our %linkFreshweight;
our %linkFreshweightExperimentHRef;
our %linkBatches;
our %linkSampleAliquotFW;


################################################################
#										MAIN										#
################################################################

#------------------------------------------------------------------ Connection to the databases
$lconn = export_db::local_db_connector();
$rconn = export_db::remote_db_connector(); 

#################################################
#
#							SELECT
#
#################################################

print STDERR`date`;
#------------------------------------------------------------------ Export unit from PlatoDB
carp "Get units...";
export_db::get_unit_remote($rconn, \%unit);

#------------------------------------------------------------------ Export activity from PlatoDB
carp "Get activity...";
export_db::get_activity_remote($rconn, \@activity);

#------------------------------------------------------------------ Replace undef values by NULL
carp "Replacing undef values...";
foreach my $line (@activity) {
	foreach my $value (values %{$line}){
		$value = (!defined($value) or $value eq '' or $value eq '~') ? "NULL" : $value;
	}
}

#------------------------------------------------------------------ Export materials from PlatoDB
carp "Get materials...";
export_db::get_materials_remote($rconn, \%standard);

#------------------------------------------------------------------ Export Experiments from PlatoDB
carp "Get experiments...";
export_db::get_experiments_remote($rconn, \%experiment);
foreach my $key (keys %experiment){
	$experiment{$key}{"Material_Fk"} = (!defined($experiment{$key}{"Material_Fk"}) or $experiment{$key}{"Material_Fk"} eq '' or $experiment{$key}{"Material_Fk"} eq '~') ? "NULL" : $experiment{$key}{"Material_Fk"};
}

#------------------------------------------------------------------ Export enzymes from PlatoDB
carp "Get enzymeCode...";
export_db::get_enzymecode_remote($rconn, \%enzymecode);

#------------------------------------------------------------------ Export enzymes from PlatoDB
carp "Get freshweight...";
export_db::get_freshweight_remote($rconn, \%freshweight);

#print Dumper %freshweight;

#------------------------------------------------------------------ Link UIds of freshweight and experiment ('cause bad link in platoDB')
carp "Get link freshweight-experiment...";
export_db::get_link_freshweight_experiment($rconn, \%linkFreshweightExperimentHRef);

#------------------------------------------------------------------ Export Batches
carp "Get batches...";
export_db::get_batches($rconn, \%batches);

#################################################
#
#							INSERT
#
#################################################

#------------------------------------------------------------------ Insert unit in mysql
# Fill a hash with Uid as key and corresponding mysql id as value
carp "Insert unit...";
export_db::insert_unit($lconn, \%unit, \%linkUnit);

#------------------------------------------------------------------ Insert unit in mysql
# Fill a hash with Uid as key and corresponding mysql id as value
carp "Insert enzymes...";
export_db::insert_enzyme($lconn, \%enzymecode, \%linkEnzyme);

#------------------------------------------------------------------ Insert unit in mysql
# Fill a hash with Uid as key and corresponding mysql id as value
carp "Insert standard...";
export_db::insert_standard($lconn, \%standard, \%linkStandard, \@activity);

#------------------------------------------------------------------ Insert relation table standard_enzyme
carp "Fill relation table standard_enzyme...";
export_db::insert_standard_enzyme($lconn, \%linkUnit, \%linkEnzyme, \%linkStandard, \@activity);

#------------------------------------------------------------------ Insert relation table experiment
carp "Fill table experiment...";
export_db::insert_experiment($lconn, \%experiment, \%linkExperiment);

#------------------------------------------------------------------ Insert relation table experiment_standard
carp "Fill relation table experiment_standard...";
export_db::insert_experiment_standard($lconn, \%experiment, \%linkExperiment, \%standard,  \%linkStandard);

#------------------------------------------------------------------ Insert relation table experiment_standard
carp "Fill table freshweight...";
export_db::insert_freshweight($lconn, \%freshweight, \%linkFreshweight, \%linkFreshweightExperimentHRef, \%linkExperiment, \%linkSampleAliquotFW);
print STDERR "after freshWeight";
print STDERR`date`;
#------------------------------------------------------------------ Insert relation table experiment_standard
carp "Fill table Data...";
export_db::insert_data($lconn, $rconn, \%batches, \%linkBatches, \%linkSampleAliquotFW, \%linkEnzyme, \%linkExperiment);

print STDERR "after data";
print STDERR`date`;
print STDERR "End of full process";



















































