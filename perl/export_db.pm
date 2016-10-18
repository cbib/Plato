package export_db;
#############################################################################################
#
# Script name : export_db.pm // functions of export_db.pl
# -----------
# Dev environment : - Ubuntu 14.04 x64
# ---------------   - perl, v5.18.2 built for x86_64-linux-gnu-thread-multi
#
#############################################################################################

use strict;
use warnings;
use Data::Dumper;

#################################################
#			DB SELECT FROM Remote Plato				#
#################################################
#--------------------------------------------------------------- get Unit from remote
sub get_unit_remote {
	my ($rconn, $unitHRef)= @_;
	my $query = "SELECT * FROM Unit;";
	my $result = $rconn->selectall_hashref($query, "UId") or die "\nERR=Sysdate\n $! \n $@\nDBI:errstr";
	foreach my $key (keys %{$result}){
		Encode::from_to($result->{$key}{"Name"}, "iso-8859-1", "utf8");
		$unitHRef->{$key}=$result->{$key}{"Name"};
	}
}

#--------------------------------------------------------------- get activity from remote
sub get_activity_remote {
	my ($rconn, $activityHRef) = @_;
	my $query = "SELECT * FROM Activity;";
	my @result = @{ $rconn->selectall_arrayref($query, { Slice => {} }) or die "\nERR=Sysdate\n $! \n $@\nDBI:errstr" };
	@{$activityHRef}=@result;
}

#--------------------------------------------------------------- get materials from remote
sub get_materials_remote {
	my ($rconn, $materialsHRef) = @_;
	my $query = "SELECT * FROM Material;";
	my $result = $rconn->selectall_hashref($query, "UId") or die "\nERR=Sysdate\n $! \n $@\nDBI:errstr";
	%$materialsHRef = %$result;
}

#--------------------------------------------------------------- get enzymecode from remote
sub get_enzymecode_remote {
	my ($rconn, $enzymecodeHRef) = @_;
	my $query = "SELECT * FROM EnzymeCode;";
	my $result = $rconn->selectall_hashref($query, "UId") or die "\nERR=Sysdate\n $! \n $@\nDBI:errstr";	
	%$enzymecodeHRef = %$result;
}

#--------------------------------------------------------------- get linked activity
sub get_linked_activity {
	my ($rconn, $linkedActivityHRef) = @_;
	my $query = "SELECT 
		Material.UId, Material.Name, 
		Activity.EnzymeCode_FK, Activity.Material_FK, Activity.Protocol_FK, Activity.Value, Activity.Unit_FK
		FROM
		Material, Activity
		WHERE
		Activity.Material_FK = Material.UId";
	   my @result = @{ $rconn->selectall_arrayref($query, { Slice => {} }) or die "\nERR=Sysdate\n $! \n $@\nDBI:errstr" };
		@{$linkedActivityHRef}=@result;
}

#--------------------------------------------------------------- get Experiments from PlatoDB
sub get_experiments_remote {
	my ($rconn, $experimentsHRef) = @_;
	my $query = "SELECT * FROM Experiments;";
	my $result = $rconn->selectall_hashref($query, "UId") or die "\nERR=Sysdate\n $! \n $@\nDBI:errstr";	
	%$experimentsHRef = %$result;
}

#--------------------------------------------------------------- get Experiments from PlatoDB
sub get_freshweight_remote {
	my ($rconn, $freshweightHRef) = @_;
	my $query = "SELECT * FROM FreshWeights;";
	my $result = $rconn->selectall_hashref($query, "UId") or die "\nERR=Sysdate\n $! \n $@\nDBI:errstr";	
	%$freshweightHRef = %$result;
}

#--------------------------------------------------------------- get link between freshweight and experiment, because the link is not on primary key in platoDB...
sub get_link_freshweight_experiment {
	my ($rconn, $linkFreshweightExperimentHRef) = @_;
	my $query = "
		SELECT FreshWeights.UId AS FWUID, Experiments.UId AS EXPUID
		FROM FreshWeights INNER JOIN Experiments
		ON FreshWeights.Experiment_FK = Experiments.Id";
	my $result = $rconn->selectall_hashref($query, "FWUID") or die "\nERR=Sysdate\n $! \n $@\nDBI:errstr";	
	%$linkFreshweightExperimentHRef = %$result;
}

#--------------------------------------------------------------- get batches
sub get_batches {
	my ($rconn, $batchesHRef) = @_;
	my $query = "SELECT * FROM Batches;";
	my $result = $rconn->selectall_hashref($query, "UId") or die "\nERR=Sysdate\n $! \n $@\nDBI:errstr";	
	%$batchesHRef = %$result;
}

#--------------------------------------------------------------- get batchCompilation
sub get_batchCompilationsForOneBatch {
	my ($rconn, $batchName_FK, $batchNumber_FK, $batchDate_FK) = @_;
	#-------------------- Get BatchCompilation data for each batch
	my $query = "
		SELECT * from BatchCompilation 
		where
		BatchCompilation.BatchName_Fk = '".$batchName_FK."' AND
		BatchCompilation.BatchNumber_Fk = ".$batchNumber_FK." AND
		BatchCompilation.BatchDate_Fk = '".$batchDate_FK."'";
	   my @result = @{ $rconn->selectall_arrayref($query, { Slice => {} }) or die "\nERR=Sysdate\n $! \n $@\nDBI:errstr" };
		return \@result;
}


#--------------------------------------------------------------- get rawData
sub get_freshweightForOneBatchComp {
	my ($rconn, $experiment_FK, $sample, $aliquot) = @_;
	my $query = "
		SELECT UId from FreshWeights
		where
		FreshWeights.Experiment_FK = '".$experiment_FK."' AND
		FreshWeights.Sample = ".$sample." AND
		FreshWeights.Aliquot = ".$aliquot.";";
	my $sth = $rconn->prepare($query);
	$sth->execute() or die "\nERR=Sysdate\n $! \n $@\nDBI:errstr";;
	my ($result) = $sth->fetchrow_array()
   and $sth->finish();
	return $result;
}

#--------------------------------------------------------------- get raw data
sub get_rawDataForOneBatchComp {
	my ($rconn, $batchNameFK, $batchNumberFK, $batchDateFK, $rowFK, $colFK) = @_;
	#--------------------Get rawdata for each batchCompilation (the date has to come batches otherwise it will not work)
	my $query = "
		SELECT * from RawData
		where
		RawData.BatchName_Fk = '".$batchNameFK."' AND
		RawData.BatchNumber_Fk = ".$batchNumberFK." AND
		RawData.BatchDate_Fk = '".$batchDateFK."' AND
		RawData.Row_Fk = ".$rowFK." AND
		RawData.Column_Fk = ".$colFK.";";
   my @result = @{ $rconn->selectall_arrayref($query, { Slice => {} }) or die "\nERR=Sysdate\n $! \n $@\nDBI:errstr" };
	return \@result;
}

#--------------------------------------------------------------- get processed data corresponding to a single raw data
sub get_processedDataForOneRawData {
	my ($rconn, $batchNameFK, $batchNumberFK, $batchDateFK, $enzymeFK, $rowFK, $colFK) = @_;
	my $query = "
		SELECT ProcessedData.Value from ProcessedData
		where
		ProcessedData.BatchName_Fk = '".$batchNameFK."' AND
		ProcessedData.BatchNumber_Fk = ".$batchNumberFK." AND
		ProcessedData.BatchDate_Fk = '".$batchDateFK."' AND
		ProcessedData.Enzyme_Fk = '".$enzymeFK."' AND
		ProcessedData.Row_Fk = ".$rowFK." AND
		ProcessedData.Column_Fk = ".$colFK.";";
   my @result = @{ $rconn->selectall_arrayref($query, { Slice => {} }) or die "\nERR=Sysdate\n $! \n $@\nDBI:errstr" };
	return \@result;
}

#--------------------------------------------------------------- get processed data corresponding to a single raw data
sub get_experiment_UId {
	my ($rconn, $experimentFK) = @_;
	my $query = "
		SELECT Experiments.UId from Experiments
		where
		Experiments.Id = '".$experimentFK."';";
   my @result = @{ $rconn->selectall_arrayref($query, { Slice => {} }) or die "\nERR=Sysdate\n $! \n $@\nDBI:errstr" };
	return \@result;
}


#################################################
#				DB INSERT IN New PlatoDB				#
#################################################

#-------------------------------------------------------------------- INSERT DATAs
sub insert_data{
	my ($lconn, $rconn, $batchesHRef, $linkBatchesHRef, $linkSampleAliquotFWHRef, $linkEnzymeHRef, $linkExperimentHRef) = @_;

	my $size = scalar keys %{$batchesHRef};
	my $i =0;
	foreach my $key (keys %{$batchesHRef}) {
		#----------------------------------------------------------- Insertion of Batch data
		my $query = "
			INSERT INTO
				batch
			VALUES ('', '".$batchesHRef->{$key}{'Name'}."', '".$batchesHRef->{$key}{'Number'}."', '".$batchesHRef->{$key}{'Date'}."', '".$batchesHRef->{$key}{'LayoutType'}."');";
		my $sth = $lconn->prepare($query);
			$sth->execute or die "Can't Add record : $lconn->errstr";
			my $last_batch_ID= $lconn->{'mysql_insertid'};
			$sth->finish;

		# For each batch we get the batchcompilation
		my @batchCompsForOneBatch = get_batchCompilationsForOneBatch($rconn, $batchesHRef->{$key}{'Name'}, $batchesHRef->{$key}{'Number'}, $batchesHRef->{$key}{'Date'});
		# For each batchCompilation of a batch we seek for the leek between freshweight, sample and aliquot
		foreach my $batchComp (@batchCompsForOneBatch) {
			foreach my $rowComp (@{$batchComp}){
				my $experimentUId = get_experiment_UId($rconn, $rowComp->{'Experiment_Fk'});
				#--------------------------------------------------------------- get FreshWeights UId
				my $fw_UId = get_freshweightForOneBatchComp($rconn, $rowComp->{'Experiment_Fk'}, $rowComp->{'Sample_Fk'}, $rowComp->{'Aliquot_Fk'});
				#--------------------------------------------------------------- get rawData for each batchcompilation
				my $rawData = get_rawDataForOneBatchComp($rconn, $rowComp->{'BatchName_Fk'}, $rowComp->{'BatchNumber_Fk'}, $batchesHRef->{$key}{'Date'}, $rowComp->{'Row'}, $rowComp->{'Column'});
				my @rawData = @{$rawData};
				#--------------------------------------------------------------- Insert batch_data
				my $last_insert_batch_data = insert_batch_data($lconn, $last_batch_ID, $linkExperimentHRef->{@{$experimentUId}[0]->{'UId'}}, $linkSampleAliquotFWHRef->{$fw_UId}, $rowComp->{'Row'}, $rowComp->{'Column'});
				foreach my $keyRawData (@rawData) {
					#--------------------------------------------------------------- Insert Rawdata for each enzyme
					my $last_rawdata_id = insert_rawData($lconn, $last_insert_batch_data, $keyRawData->{'RawValue'}, $keyRawData->{'excluded'}, $keyRawData->{'Velocity'}, $keyRawData->{'proved'}, $linkEnzymeHRef->{$keyRawData->{'Enzyme_Fk'}});
					my $dataPosition = 1+(($rowComp->{'Row'}*8)+$rowComp->{'Column'});
					#--------------------------------------------------------------- processed data for eaxh enzyme
					my $processedDatas = get_processedDataForOneRawData($rconn, $rowComp->{'BatchName_Fk'}, $rowComp->{'BatchNumber_Fk'}, $batchesHRef->{$key}{'Date'}, $keyRawData->{'Enzyme_Fk'}, $rowComp->{'Row'}, $rowComp->{'Column'});
					#--------------------------------------------------------------- Insert processed Data		
					if(defined(@{$processedDatas}[0])){			
						my $last_processedData_id = insert_processedData($lconn, @{$processedDatas}[0]->{'Value'});
						if ($last_processedData_id != -1){
							#--------------------------------------------------------------- Insert praw_equ_proc
							insert_raw_equ_proc($lconn, $last_rawdata_id, $last_processedData_id);
						}
					}
				}
			}
		}
		$i++;
		print STDERR "$i/".$size."\r"  if (($i % 100) == 0);
	}
}

#--------------------------------------------------------------------Insert data in batch_data (local db)
sub insert_batch_data {
	my ($lconn, $batchID, $experimentFK, $sampleAliquotID, $row, $col) = @_;
	my $query = "
		INSERT INTO
			batch_data
		VALUES ('', '".$batchID."', '".$experimentFK."', '".$sampleAliquotID."', '".$row."', '".$col."');";
	my $sth = $lconn->prepare($query);
		$sth->execute or die "Can't Add record : $lconn->errstr";
		my $last_batch_data_id = $lconn->{'mysql_insertid'};
		$sth->finish;
		return $last_batch_data_id;
}

#--------------------------------------------------------------------Insert data in raw_equ_proc (local db)
sub insert_raw_equ_proc {
	my ($lconn, $rawdataID, $processdataID) = @_;
	my $query = "
		INSERT INTO
			raw_equ_proc
		VALUES ('', '".$rawdataID."', '1', '".$processdataID."');";
	my $sth = $lconn->prepare($query);
		$sth->execute or die "Can't Add record : $lconn->errstr";
		$sth->finish;
}

#--------------------------------------------------------------------Insert data in rawdata (local db)
sub insert_rawData {
	my ($lconn, $last_batch_data_id, $rawvalue, $excluded, $velocity, $proved, $enzymeFK ) = @_;
	my $query = "
		INSERT INTO
			rawdata
		VALUES ('', '".$rawvalue."', '".$excluded."', '".$velocity."', '".$proved."', '".$enzymeFK."', '".$last_batch_data_id."');";
	my $sth = $lconn->prepare($query);
		$sth->execute or die "Can't Add record : $lconn->errstr";
		my $last_rawdata_id = $lconn->{'mysql_insertid'};
		$sth->finish;
		return $last_rawdata_id;
}

#--------------------------------------------------------------------Insert data in processdata (local db)
sub insert_processedData {
	my ($lconn, $value) = @_;

	if (defined ($value)){
	my $query = "
		INSERT INTO
			processdata
		VALUES ('', '".$value."');";
	my $sth = $lconn->prepare($query);
		$sth->execute or die "Can't Add record : $lconn->errstr";
		my $last_processedData_id = $lconn->{'mysql_insertid'};
		$sth->finish;
		return $last_processedData_id;
	}
	else {
		return -1;	
	}
}

#---------------------------------------------------------------Insert data in unit (local db)
sub insert_unit {
	my ($lconn, $unitHRef, $linkUnitHRef) = @_;
	foreach my $key (keys %{$unitHRef}){
		my $query = "INSERT INTO unit VALUES ('', '".$unitHRef->{$key}."');";
		#print  $query."\n";
		my $sth = $lconn->prepare($query);
			$sth->execute or die "Can't Add record : $lconn->errstr";
			$linkUnitHRef->{$key} = $lconn->{'mysql_insertid'};
			$sth->finish;
	}
}

#---------------------------------------------------------------Insert data in enzyme (local db)
sub insert_enzyme {
	my ($lconn, $enzymecodeHRef, $linkEnzymeHRef) = @_;
	foreach my $key (keys %{$enzymecodeHRef}) {
		my $enzymeType=-1;	
		if ($enzymecodeHRef->{$key}{'Type'} eq "Enzyme" ) {
			$enzymeType = 1;
		}
		elsif ($enzymecodeHRef->{$key}{'Type'} eq "Metabolite"){
			$enzymeType = 0;
		}
		else {
			print "ERROR !!!!!!!!!! THIS IS A Un-identified Object !!! \n"
		}
		my $query = "INSERT INTO enzyme VALUES 
			('', '".$enzymecodeHRef->{$key}{'Analyte'}."','".$enzymecodeHRef->{$key}{'SlopeRatio'}."','".$enzymecodeHRef->{$key}{'Code'}."','".$enzymeType."');";
		#print  $query."\n";
		my $sth = $lconn->prepare($query);
			$sth->execute or die "Can't Add record : $lconn->errstr";
			$linkEnzymeHRef->{$key} = $lconn->{'mysql_insertid'};
			$sth->finish;
	}
}

#--------------------------------------------------------------------Insert data in standard (local db)
sub insert_standard {
	my ($lconn, $materialsHRef, $linkStandardHRef) = @_;
	foreach my $key (keys %{$materialsHRef}) {
		my $query = "
			INSERT INTO
				standard
			VALUES ('', '".$materialsHRef->{$key}{'Name'}."', '', '', '', '', '', '');";
		#print $query."\n";
		my $sth = $lconn->prepare($query);
			$sth->execute or die "Can't Add record : $lconn->errstr";
			$linkStandardHRef->{$key} = $lconn->{'mysql_insertid'};
			$sth->finish;
	}
}

#--------------------------------------------------------------------Insert data in standard_enzyme (local db)
sub insert_standard_enzyme {
	my ($lconn, $unitHRef, $enzymecodeHRef, $materialsHRef, $activityHRef) = @_;
	my $query="INSERT INTO
				standard_enzyme
				VALUES ";
	foreach my $line (@{$activityHRef}) {
			my ($unit_fk, $standard_fk, $value, $enzyme_fk) = ("NULL", "NULL", "NULL", "NULL");
		foreach my $key (keys %{$line}){
			if (defined($unitHRef->{$line->{"Unit_Fk"}}) ) {
				$unit_fk = $unitHRef->{$line->{"Unit_Fk"}}; 
			}
			if (defined($materialsHRef->{$line->{"Material_Fk"}}) ) {
				$standard_fk = $materialsHRef->{$line->{"Material_Fk"}};
			}
			if (defined($line->{"Value"}) ) {
				$value = $line->{"Value"}; 
			}
			if (defined($enzymecodeHRef->{$line->{"EnzymeCode_Fk"}}) ) {
				$enzyme_fk = $enzymecodeHRef->{$line->{"EnzymeCode_Fk"}};
			}
			$standard_fk = $materialsHRef->{$line->{"Material_Fk"}};
			$value = $line->{"Value"};
			$enzyme_fk = $enzymecodeHRef->{$line->{"EnzymeCode_Fk"}};
		}
		if ($unit_fk eq "NULL") {
			$query .= "
			('', '1', '".$standard_fk."','".$value."','".$enzyme_fk."',''),";
		} 
		else {
			$query .= "
			('', '".$unit_fk."', '".$standard_fk."','".$value."','".$enzyme_fk."',''),";
		}
	}
	chop $query;
	$query.=";";
	my $sth = $lconn->prepare($query);
		$sth->execute or die "Can't Add record : $lconn->errstr";
		$sth->finish;
}

#--------------------------------------------------------------------Insert data in experiment (local db)
sub insert_experiment {
	my ($lconn, $experimentHRef, $linkExperimentHRef) = @_;
	
	foreach my $key (keys %{$experimentHRef}) {
		my $query = "
			INSERT INTO
				experiment
			VALUES ('', '".$experimentHRef->{$key}{'Id'}."');";
		#print $query."\n";
		my $sth = $lconn->prepare($query);
			$sth->execute or die "Can't Add record : $lconn->errstr";
			$linkExperimentHRef->{$key} = $lconn->{'mysql_insertid'};
			$sth->finish;
	}
}

#--------------------------------------------------------------------Insert data in experiment_standard (local db)
sub insert_experiment_standard {
	my ($lconn, $experimentHRef, $linkExperimentHRef, $standardHRef, $linkStandardHRef) = @_;
	
	my $query = "INSERT INTO experiment_standard VALUES";
	foreach my $key (keys %{$experimentHRef}) {
		if ($experimentHRef->{$key}{"Material_Fk"} ne "NULL") {
			$query.="\n('','".$linkStandardHRef->{$experimentHRef->{$key}{"Material_Fk"}}."', '".$linkExperimentHRef->{$key}."'),";
		}
		else{
			$query.="\n('','"."1"."', '".$linkExperimentHRef->{$key}."'),";
		}
	}
	chop $query;
	$query.=";";
	my $sth = $lconn->prepare($query);
		$sth->execute or die "Can't Add record : $lconn->errstr";
		$sth->finish;
}

#--------------------------------------------------------------------Insert data in freshweight (local db)
sub insert_freshweight {
	my ($lconn, $freshweightHRef, $linkFreshweightHRef, $linkFwExpHRef, $linkExperimentHRef, $linkSampleAliquotFWHRef) = @_;
	my $i=0;
	my $size = scalar keys %$freshweightHRef;
	foreach my $key (keys %{$freshweightHRef}) {
		#------------------------------------------------------------------ Insert freshweight
		my $query = "
			INSERT INTO
				freshweight
			VALUES ('', '".$freshweightHRef->{$key}{'Experiment_FK'}."');";
		my $sth = $lconn->prepare($query);
			$sth->execute or die "Can't Add record : $lconn->errstr";
			my $last_fw_id = $lconn->{'mysql_insertid'};
			$linkFreshweightHRef->{$key}=$last_fw_id;
			#print $key." ====> ". $last_fw_id."\n";
			$sth->finish;
		#------------------------------------------------------------------ Insert experiment_freshweight
		
		my $queryBis = "
			INSERT INTO
				experiment_freshweight
			VALUES ('', '".$linkExperimentHRef->{$linkFwExpHRef->{$key}{"EXPUID"}}."', '".$last_fw_id."');";
		my $sthBis = $lconn->prepare($queryBis);
			$sthBis->execute or die "Can't Add record : $lconn->errstr";
			$sthBis->finish;

		#------------------------------------------------------------------ Insert sample
		my $query2 =  "
			INSERT INTO
				sample
			VALUES ('', '".$freshweightHRef->{$key}{'Sample'}."', '1');";
		my $sth2 = $lconn->prepare($query2);
			$sth2->execute or die "Can't Add record : $lconn->errstr";
			my $last_sample_id = $lconn->{'mysql_insertid'};
			$sth2->finish;

		#------------------------------------------------------------------ Insert freshweight_sample relation table
		my $query2b =  "
			INSERT INTO
				freshweight_sample
			VALUES ('', '".$last_fw_id."','".$last_sample_id."');";
		my $sth2b = $lconn->prepare($query2b);
			$sth2b->execute or die "Can't Add record : $lconn->errstr";
			$sth2b->finish;

		#------------------------------------------------------------------ Insert location
		my $last_loc_id=1;
		my $query3="";
		if (defined($freshweightHRef->{$key}{'Location'})) {
			my $loc = $freshweightHRef->{$key}{'Location'};
			$loc =~s/'//g;
			$query3 =qq(
				INSERT INTO
					location
				VALUES ('', '$loc', '', '', ''););
		}
		else {
			$query3 =qq(
				INSERT INTO
					location
				VALUES ('', 'undefLocation', '', '', ''););
		}
		my $sth3 = $lconn->prepare($query3);
			$sth3->execute or die "Can't Add record : $lconn->errstr";
			$last_loc_id = $lconn->{'mysql_insertid'};
			$sth3->finish;
		#------------------------------------------------------------------ Insert aliquot
		my $query4 = "
				INSERT INTO
					aliquot
				VALUES ('',  '".$freshweightHRef->{$key}{'Aliquot'}."',  '".$freshweightHRef->{$key}{'Value'}."', '', '".$last_loc_id."');";
		my $sth4 = $lconn->prepare($query4);
			$sth4->execute or die "Can't Add record : $lconn->errstr";
			my $last_aliquot_id = $lconn->{'mysql_insertid'};
			$sth4->finish;

		#------------------------------------------------------------------ Insert sample_aliquot
		my $query5 ="
			INSERT INTO
				sample_aliquot
			VALUES ('', '".$last_sample_id."','".$last_aliquot_id."', 'free');";
		my $sth5 = $lconn->prepare($query5);
			$sth5->execute or die "Can't Add record : $lconn->errstr";
			$linkSampleAliquotFWHRef->{$key} = $lconn->{'mysql_insertid'};
			$sth5->finish;

		$i++;
		print STDERR "$i/".$size."\r"  if (($i % 100) == 0);
	}
}





1;
