package export_db;

use strict;
use warnings;
use Data::Dumper;

#################################################
#						DB connection						#
#################################################

#-------------------------------------------------------------remote connection (to plato)
sub remote_db_connector{
	# Paramètres de connection à la base de données
	my $bd		= 'dbname';
	my $serveur	= 'ip';	  # Il est possible de mettre une adresse IP
	my $identifiant = 'id';	  # identifiant 
	my $motdepasse	= 'pwd';
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
	# Paramètres de connection à la base de données
	my $bd		= 'plato_export_21082016';
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


#################################################
#			DB SELECT FROM Remote Plato			#
#################################################
#--------------------------------------------------------------- get Unit from remote
sub get_unit_remote {
	my ($rconn, $unitHRef)= @_;

	my $query = "SELECT * FROM Unit;";
	#print $query."\n";

	my $result = $rconn->selectall_hashref($query, "UId") or die "\nERR=Sysdate\n $! \n $@\nDBI:errstr";

	foreach my $key (keys %{$result}){
		Encode::from_to($result->{$key}{"Name"}, "iso-8859-1", "utf8");
		$unitHRef->{$key}=$result->{$key}{"Name"};
		#print $key."  ".$result->{$key}{"Name"}."\n";
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


##--------------------------------------------------------------- get batchCompilation
#sub get_batchCompilation {
#	my ($rconn, $batchCompilationARef) = @_;

#	my $query = "SELECT 
#		TOP 100 *
#		FROM
#		BatchCompilation";
#	   my @result = @{ $rconn->selectall_arrayref($query, { Slice => {} }) or die "\nERR=Sysdate\n $! \n $@\nDBI:errstr" };
#		@{$batchCompilationARef}=@result;
#}


##--------------------------------------------------------------- get rawData
#sub get_rawData {
#	my ($rconn, $rawDataARef) = @_;

#	my $query = "SELECT 
#		TOP 100 *
#		FROM
#		RawData";
#	   my @result = @{ $rconn->selectall_arrayref($query, { Slice => {} }) or die "\nERR=Sysdate\n $! \n $@\nDBI:errstr" };
#		@{$rawDataARef}=@result;
#}





#--------------------------------------------------------------- get batchCompilation
sub get_batchCompilationsForOneBatch {
	my ($rconn, $batchName_FK, $batchNumber_FK, $batchDate_FK) = @_;
#--------------------Recupere chaque batchCompilation issue d'un batch
#Select * from PlatoDB.dbo.BatchCompilation 
#where
#PlatoDB.dbo.BatchCompilation.BatchName_Fk = 'CAQ40Peche_Rack6' AND
#PlatoDB.dbo.BatchCompilation.BatchNumber_Fk = 1 AND
#PlatoDB.dbo.BatchCompilation.BatchDate_Fk = '12/04/2013 00:00:00'
	my $query = "
		Select * from PlatoDB.dbo.BatchCompilation 
		where
		PlatoDB.dbo.BatchCompilation.BatchName_Fk = '".$batchName_FK."' AND
		PlatoDB.dbo.BatchCompilation.BatchNumber_Fk = ".$batchNumber_FK." AND
		PlatoDB.dbo.BatchCompilation.BatchDate_Fk = '".$batchDate_FK."'";
	   my @result = @{ $rconn->selectall_arrayref($query, { Slice => {} }) or die "\nERR=Sysdate\n $! \n $@\nDBI:errstr" };
		return \@result;
}


#--------------------------------------------------------------- get rawData
sub get_freshweightForOneBatchComp {
	my ($rconn, $experiment_FK, $sample, $aliquot) = @_;
#--------------------Recupere le freshWeight d'un batchCompilation
#Select * from PlatoDB.dbo.FreshWeights
#where
#PlatoDB.dbo.FreshWeights.Experiment_FK = 'CAQ40Peche_Redfruit' AND
#PlatoDB.dbo.FreshWeights.Sample = 44532 AND
#PlatoDB.dbo.FreshWeights.Aliquot = 1
#Avec l'UId du freshWeight on peut retrouver le liens vers le sample_aliquot

	my $query = "
		Select UId from PlatoDB.dbo.FreshWeights
		where
		PlatoDB.dbo.FreshWeights.Experiment_FK = '".$experiment_FK."' AND
		PlatoDB.dbo.FreshWeights.Sample = ".$sample." AND
		PlatoDB.dbo.FreshWeights.Aliquot = ".$aliquot.";";

#	my ($result) = $rconn−>selectrow_array($query) or die "\nERR=Sysdate\n $! \n $@\nDBI:errstr";
##	my @result = @{ $rconn->selectall_arrayref($query, { Slice => {} }) or die "\nERR=Sysdate\n $! \n $@\nDBI:errstr" };
#	return $result;
	#print $query."\n";

	my $sth = $rconn->prepare($query);
	$sth->execute() or die "\nERR=Sysdate\n $! \n $@\nDBI:errstr";;
	my ($result) = $sth->fetchrow_array()
   and $sth->finish();
	return $result;
}

#--------------------------------------------------------------- get raw data
sub get_rawDataForOneBatchComp {
	my ($rconn, $batchNameFK, $batchNumberFK, $batchDateFK, $rowFK, $colFK) = @_;
#--------------------Recuperation des rawData correspondant a un batchCompilation (sauf que la date vient de batches sinon ça ne marche pas...)
#Select * from PlatoDB.dbo.RawData
#where
#PlatoDB.dbo.RawData.BatchName_Fk = 'CAQ40Peche_Rack6' AND
#PlatoDB.dbo.RawData.BatchNumber_Fk = 1 AND
#PlatoDB.dbo.RawData.BatchDate_Fk = '12/04/2013 00:00:00' AND
#PlatoDB.dbo.RawData.Row_Fk = 0 AND
#PlatoDB.dbo.RawData.Column_Fk = 1
	my $query = "
		Select * from PlatoDB.dbo.RawData
		where
		PlatoDB.dbo.RawData.BatchName_Fk = '".$batchNameFK."' AND
		PlatoDB.dbo.RawData.BatchNumber_Fk = ".$batchNumberFK." AND
		PlatoDB.dbo.RawData.BatchDate_Fk = '".$batchDateFK."' AND
		PlatoDB.dbo.RawData.Row_Fk = ".$rowFK." AND
		PlatoDB.dbo.RawData.Column_Fk = ".$colFK.";";
   my @result = @{ $rconn->selectall_arrayref($query, { Slice => {} }) or die "\nERR=Sysdate\n $! \n $@\nDBI:errstr" };
	return \@result;
}


#--------------------------------------------------------------- get processed data corresponding to a single raw data
sub get_processedDataForOneRawData {
	my ($rconn, $batchNameFK, $batchNumberFK, $batchDateFK, $enzymeFK, $rowFK, $colFK) = @_;
#--------------------Recuperation des processedData correspondantes, rajouter la FK enzyme pour l'unicité'
#Select * from PlatoDB.dbo.ProcessedData
#where
#PlatoDB.dbo.ProcessedData.BatchName_Fk = 'CAQ40Peche_Rack6' AND
#PlatoDB.dbo.ProcessedData.BatchNumber_Fk = 1 AND
#PlatoDB.dbo.ProcessedData.BatchDate_Fk = '12/04/2013 00:00:00' AND
#PlatoDB.dbo.ProcessedData.Row_Fk = 0 AND
#PlatoDB.dbo.ProcessedData.Column_Fk = 1
	my $query = "
		Select PlatoDB.dbo.ProcessedData.Value from PlatoDB.dbo.ProcessedData
		where
		PlatoDB.dbo.ProcessedData.BatchName_Fk = '".$batchNameFK."' AND
		PlatoDB.dbo.ProcessedData.BatchNumber_Fk = ".$batchNumberFK." AND
		PlatoDB.dbo.ProcessedData.BatchDate_Fk = '".$batchDateFK."' AND
		PlatoDB.dbo.ProcessedData.Enzyme_Fk = '".$enzymeFK."' AND
		PlatoDB.dbo.ProcessedData.Row_Fk = ".$rowFK." AND
		PlatoDB.dbo.ProcessedData.Column_Fk = ".$colFK.";";
   my @result = @{ $rconn->selectall_arrayref($query, { Slice => {} }) or die "\nERR=Sysdate\n $! \n $@\nDBI:errstr" };
	return \@result;
}


#--------------------------------------------------------------- get processed data corresponding to a single raw data
sub get_experiment_UId {
	my ($rconn, $experimentFK) = @_;
	my $query = "
		Select PlatoDB.dbo.Experiments.UId from PlatoDB.dbo.Experiments
		where
		PlatoDB.dbo.Experiments.Id = '".$experimentFK."';";
	#print $query."\n";
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
		#----------------------------------------------------------- Insertion des données batch
		my $query = "
			INSERT INTO
				batch
			VALUES ('', '".$batchesHRef->{$key}{'Name'}."', '".$batchesHRef->{$key}{'Number'}."', '".$batchesHRef->{$key}{'Date'}."', '".$batchesHRef->{$key}{'LayoutType'}."');";
		my $sth = $lconn->prepare($query);
			$sth->execute or die "Can't Add record : $lconn->errstr";
			my $last_batch_ID= $lconn->{'mysql_insertid'};
			$sth->finish;

		#Pour chaque batch il faut recuperer les batchcompilation correspondantes
		my @batchCompsForOneBatch = get_batchCompilationsForOneBatch($rconn, $batchesHRef->{$key}{'Name'}, $batchesHRef->{$key}{'Number'}, $batchesHRef->{$key}{'Date'});
		#pour chaque batchcompilation d'un batch on cherche le lien entre le freshweight et le sample aliquot
		foreach my $batchComp (@batchCompsForOneBatch) {
#			print STDERR "loop\n";
			foreach my $rowComp (@{$batchComp}){
#				print STDERR "loop2\n";
				my $experimentUId = get_experiment_UId($rconn, $rowComp->{'Experiment_Fk'});
				#--------------------------------------------------------------- get FreshWeights UId
				my $fw_UId = get_freshweightForOneBatchComp($rconn, $rowComp->{'Experiment_Fk'}, $rowComp->{'Sample_Fk'}, $rowComp->{'Aliquot_Fk'});
				#--------------------------------------------------------------- get rawData for each batchcompilation
				my $rawData = get_rawDataForOneBatchComp($rconn, $rowComp->{'BatchName_Fk'}, $rowComp->{'BatchNumber_Fk'}, $batchesHRef->{$key}{'Date'}, $rowComp->{'Row'}, $rowComp->{'Column'});
				my @rawData = @{$rawData};

				#--------------------------------------------------------------- Insert batch_data
				my $last_insert_batch_data = insert_batch_data($lconn, $last_batch_ID, $linkExperimentHRef->{@{$experimentUId}[0]->{'UId'}}, $linkSampleAliquotFWHRef->{$fw_UId}, $rowComp->{'Row'}, $rowComp->{'Column'});
			
				foreach my $keyRawData (@rawData) {
#					print STDERR "loop3\n";
					#--------------------------------------------------------------- Insert Rawdata for each enzyme
					my $last_rawdata_id = insert_rawData($lconn, $last_insert_batch_data, $keyRawData->{'RawValue'}, $keyRawData->{'excluded'}, $keyRawData->{'Velocity'}, $keyRawData->{'proved'}, $linkEnzymeHRef->{$keyRawData->{'Enzyme_Fk'}});
					
					my $dataPosition = 1+(($rowComp->{'Row'}*8)+$rowComp->{'Column'});
					#--------------------------------------------------------------- Insert batch_data
					
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

#--------------------------------------------------------------------
sub insert_batch_data {
	my ($lconn, $batchID, $experimentFK, $sampleAliquotID, $row, $col) = @_;
#	my $query = "
#		INSERT INTO
#			batch_data
#		VALUES ('', '".$batchID."', '".$rawdataID."', '".$sampleAliquotID."', '".$position."', '".$row."', '".$col."');";

#	my $query = "
#		INSERT INTO
#			batch_data
#		VALUES ('', '".$batchID."', '".$rawdataID."', '".$experimentFK."', '1', '".$row."', '".$col."');";

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


#--------------------------------------------------------------------
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

#--------------------------------------------------------------------
sub insert_rawData {
	my ($lconn, $last_batch_data_id, $rawvalue, $excluded, $velocity, $proved, $enzymeFK ) = @_;
	my $query = "
		INSERT INTO
			rawdata
		VALUES ('', '".$rawvalue."', '".$excluded."', '".$velocity."', '".$proved."', '".$enzymeFK."', '".$last_batch_data_id."');";

#		print $query."\n";

	my $sth = $lconn->prepare($query);
		$sth->execute or die "Can't Add record : $lconn->errstr";
		my $last_rawdata_id = $lconn->{'mysql_insertid'};
		$sth->finish;
		return $last_rawdata_id;
}

#--------------------------------------------------------------------
sub insert_processedData {
	my ($lconn, $value) = @_;

	if (defined ($value)){
	my $query = "
		INSERT INTO
			processdata
		VALUES ('', '".$value."');";

	#print $query."\n";
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

#---------------------------------------------------------------
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



sub insert_unit_sparql {
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

#---------------------------------------------------------------
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

#--------------------------------------------------------------------
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

#---------------------------------------------------------------
sub insert_standard_enzyme {
	my ($lconn, $unitHRef, $enzymecodeHRef, $materialsHRef, $activityHRef) = @_;
	my $query="INSERT INTO
				standard_enzyme
				VALUES ";
	#print "###########".scalar(@$activityHRef)."#################\n\n\n";
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
			#print $query."\n";
		} 
		else {
			$query .= "
			('', '".$unit_fk."', '".$standard_fk."','".$value."','".$enzyme_fk."',''),";
			
		}
	}
	chop $query;
	$query.=";";
	#print $query;
	my $sth = $lconn->prepare($query);
		$sth->execute or die "Can't Add record : $lconn->errstr";
		$sth->finish;
}

#--------------------------------------------------------------------
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

#--------------------------------------------------------------------
sub insert_experiment_standard {
	my ($lconn, $experimentHRef, $linkExperimentHRef, $standardHRef, $linkStandardHRef) = @_;
	
	my $query = "INSERT INTO experiment_standard VALUES";
	foreach my $key (keys %{$experimentHRef}) {
		if ($experimentHRef->{$key}{"Material_Fk"} ne "NULL") {
			$query.="\n('','".$linkStandardHRef->{$experimentHRef->{$key}{"Material_Fk"}}."', '".$linkExperimentHRef->{$key}."'),";
		}
		else{#($experimentHRef->{$key}{"Material_Fk"} eq "NULL") {
			$query.="\n('','"."1"."', '".$linkExperimentHRef->{$key}."'),";
		}
	}
	chop $query;
	$query.=";";
	#print $query;
	my $sth = $lconn->prepare($query);
		$sth->execute or die "Can't Add record : $lconn->errstr";
		$sth->finish;
}

#--------------------------------------------------------------------
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
#			print $queryBis."\n";
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
			#my $last_sample_id = $lconn->{'mysql_insertid'};
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

		#ajouts ici /!\
		}
		else {
			$query3 =qq(
				INSERT INTO
					location
				VALUES ('', 'undefLocation', '', '', ''););
		}
		# fin ajouts
		
			my $sth3 = $lconn->prepare($query3);
				#print STDERR $query3."\n";
				$sth3->execute or die "Can't Add record : $lconn->errstr";
				$last_loc_id = $lconn->{'mysql_insertid'};
				$sth3->finish;
		# }

		#------------------------------------------------------------------ Insert aliquot
		# my $query4="";
		# if ($last_loc_id == 1) {
		# 	$query4 = "
		# 		INSERT INTO
		# 			aliquot
		# 		VALUES ('',  '".$freshweightHRef->{$key}{'Aliquot'}."',  '".$freshweightHRef->{$key}{'Value'}."', '', '1');";
		# }
		# else {
		my $query4 = "
				INSERT INTO
					aliquot
				VALUES ('',  '".$freshweightHRef->{$key}{'Aliquot'}."',  '".$freshweightHRef->{$key}{'Value'}."', '', '".$last_loc_id."');";
		# }
#		print $query4."\n";
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

##--------------------------------------------------------------------
#sub insert_batches{
#	my ($lconn, $batchesHRef, $linkBatchesHRef) = @_;

#	foreach my $key (keys %{$batchesHRef}) {
#		my $query = "
#			INSERT INTO
#				batch
#			VALUES ('', '".$batchesHRef->{$key}{'Name'}."', '".$batchesHRef->{$key}{'Number'}."', '".$batchesHRef->{$key}{'Date'}."', '".$batchesHRef->{$key}{'LayoutType'}."');";
#		#print $query."\n";
#		my $sth = $lconn->prepare($query);
#			$sth->execute or die "Can't Add record : $lconn->errstr";
#			$linkBatchesHRef->{$key} = $lconn->{'mysql_insertid'};
#			$sth->finish;
#	}
#}




sub convertUTF8{
	my $dbh = @_;
	ALTER TABLE batch CONVERT TO CHARACTER SET utf8;


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
	}
	$dbh->disconnect();

}











1;
