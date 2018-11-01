
<?php
/* 
Description: this php script contains an array of campus ftp server, user, and file data, which it loops thorough to retreive the ftp files from the central ftp server and put them into a local file by campus name. 
Automation of this program depends on the os of the application server it is running on, but likey crontab or similar.

execute by running 'php job_listings.php'. Output should show file download status, and sample data being cleaned from local static file 'sample_date.csv'

Questions:
Is title code totally numeric like 2.444 or only int of 4 or less or only 4 length int?
*/

// TODO: add this to launchd or as a crontab job depending on os: * * * * *  php /Users/max/Sites/ucop/retreive_ftp.php

include_once('./db.php');

class job_listings extends db {

	private $debug=true;
	
	//these now found in parent db class.
	//private $db_host = 'db hostname';
	//private $db_username = 'db user';
	//private $db_password = 'db pass';
	//private $db_name = 'name of database';
	private $table_name = 'table_name';
	
	//////////////////
	// Deliverable 1: 
	// this function is the code and pseudocode for an automated daily file retrieval from the central FTP server deliverable
	// This functionality can be added to crontab or similar depending on server os like so for weekdays at 0700.
	// '0 7 * * 1-5  /usr/bin/php /Users/max/Sites/ucop/include/job_listings.php'
	//////////////////
	
	public function getDataFileFromServer() { 
		
		$listingFiles = array(
		// this is an actual test ftp site to make sure all this works. we dont do anything with it, just testing. actual data is static file called test_data.csv
		array('name'=>'campus_0','server'=>'speedtest.tele2.net', 'username'=>'anonymous', 'password'=>'anonymous', 'server_file' => '1KB.zip', 'file_ext' => '.zip', 'ftp_data_type'=>FTP_BINARY),
		// pseudocode one entry for each campus.
		//array('name'=>'campus_1','server'=>'ftp.campus1.edu', 'username'=>'anonymous', 'password'=>'anonymous', 'server_file' => 'campus_1.zip', 'file_ext' => '.zip', 'ftp_data_type'=>FTP_BINARY),
		//array('name'=>'campus_2','server'=>'ftp.campus2.edu', 'username'=>'anonymous', 'password'=>'anonymous', 'server_file' => 'campus_2.zip', 'file_ext' => '.zip', 'ftp_data_type'=>FTP_BINARY),
		//array('name'=>'campus_3','server'=>'ftp.campus3.edu', 'username'=>'anonymous', 'password'=>'anonymous', 'server_file' => 'campus_3.zip', 'file_ext' => '.zip', 'ftp_data_type'=>FTP_BINARY),
		//array('name'=>'campus_4','server'=>'ftp.campus4.edu', 'username'=>'anonymous', 'password'=>'anonymous', 'server_file' => 'campus_4.zip', 'file_ext' => '.zip', 'ftp_data_type'=>FTP_BINARY)
		);
		
		//loop through each campus in the array and retreive the data file. is the name of the data file the same each day?
		foreach( $listingFiles as $key=>$value){
			
			if ($this->debug) {
				echo "name is: " . $value['name'] . ", server is: " . $value['server'] . ", username is: " . $value['username'] . ", password is: " . $value['password'] . ", server_file is: " . $value['server_file'] . ", file_ext is: " . $value['file_ext'] . ", ftp_data_type is: " . $value['ftp_data_type'];
			}
			
			// name of the file on the server
			$server_file = $value['server_file'];
			// local name to store file
			$local_file = $value['name'] . $value['file_ext'];
			
			//login creds. prob unnessesary to assign the var names but make it easier to read in rest of the script. same is true for the file names, I suppose.
			$ftp_user_name = $value['username'];
			$ftp_user_pass = $value['password'];
			$ftp_server = $value['server'];
			$ftp_data_type = $value['ftp_data_type'];
			
			$file_retrieved_status = false;
			
			// make connection to ftp servers
			$conn_id = ftp_connect($ftp_server);
				
			// login passive
			$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
			ftp_pasv($conn_id, true);
			
			// try to download $server_file and save to $local_file
			if (ftp_get($conn_id, $local_file, $server_file, $ftp_data_type)) { // set to FTP_ASCII if uncompressed csv files are being moved.
				echo "Successfully written to " . $local_file . "\n";
				$file_retrieved_status = true;
				echo "file_retrieved_status is: " . $file_retrieved_status;
				
				// maybe return cleaned data and write to file here, so we can loop through the files.
				$this->validateListingsInFile($local_file);
			} else {
				echo "There was a problem\n";
			}
			
			// close the connection
			ftp_close($conn_id);
			return $file_retrieved_status;
		}
	}
	
	/////////////////////////
	// Deliverable 2:
	// This function and the following submitListingToDB() are the code and pseudocode for a data import and validation process. 
	// Clearly step through the critical areas. Less-critical areas do not need a lot of detail
	/////////////////////////
	
	public function validateListingsInFile($data_file){
		
		// validate data in file
		// cycle through all the file, right now using sample_data.csv

		$valid_locations = array('BK', 'DV', 'DVMC', 'IR', 'IRMC', 'LA', 'LAMC', 'MR', 'RV', 'SD', 'SDMC', 'SF', 'SFMC', 'SB', 'SC', 'OP');
		$valid_position_type = array('SMG', 'MSP', 'PSS');
		
		$file_received = fopen('./sample_data.csv', "r"); // will be path/to/$datafile;
		
		while (($data = fgetcsv($file_received)) !== FALSE) {
			echo "\n";
			$validation_errors = '';
			$q = array();
			$valid = true;
			
			// validate location code			
			if (in_array($data[0], $valid_locations)){
				$q['loc'] = $data[0];
				echo "loc is: " . $q['loc'];
			} else {
				$valid = false;
				$validation_errors .= "\nRequired Location " . $data[0] . " not valid, skipping record";
				echo $validation_errors;
				continue;
			}
		
			// validate position type or set to blank
			if (in_array($data[1], $valid_position_type)){
				$q['position_type'] = $data[1];
				echo ", position_type is: " . $q['position_type'];
			} else {
				$q['position_type'] = '';
				$validation_errors .=  "\nOptional Position type: " . $data[1] . " not valid. set to '" . $q['position_type'] . "' adding record anyway.";
			}
			
			// validate title code for 4 digit or less integer. May need to change if t his isnt correct.
			$num_length = strlen((string)$data[2]);
			if (is_numeric($data[2]) && $num_length<5 && $data[2] >= 0){
				$q['title_code'] = $data[2];
				echo ", title_code is: " . $q['title_code']; 
			} else {
				$q['title_code'] = null;
				$validation_errors .=  "\nOptional Title Code: " . $data[2] . " not valid. set to '" . $q['title_code'] . "' adding record anyway.";
			}
			
			// this can be interesting. converting string to unix date. Required field, so if not good, skip record.
			// converting to unix date will allow us to use query form to get postings in prev 3 days, etc.
			if ($q['posting_date'] = strtotime($data[3])) {
				echo ", date is: " . $q['posting_date'];
			} else {
				$valid = false;
				$validation_errors .= "\nRequired Posting Date " . $data[3] . " not valid, skipping record";
				echo $validation_errors;
				continue;
			}
		
			// ... it will go on like this for each field. pseudocode!
			
			$q['closing_date'] = $data[4];
			$q['job_title'] = $data[5];
			$q['position_summary'] = $data[6];
			$q['salary_range'] = $data[7];
			$q['url'] = $data[8];
			$q['internal_job_num'] = $data[9];
			$q['fulltime_parttime'] = $data[10];
			
			echo $validation_errors;

			// submit record to database if we got this far without a continue
			// we may have to put the cleaned listing file into a new array and overwrite the prev days file,
			// or allow the prev days file to stay if there is no new file for today
			$submitted = $this->submitListingToDB($q);
		}
		echo "\n";
		fclose($file_received);
	}
	
	public function submitListingToDB($in) {
		//var_dump($in);
		// dump existing campus file if new campus file has 1 or more records.
		
		return 0;
		
		// use parent db class open_db()
		
		// sql would look something like this for each record in the cleaned file
		//mysql_query("INSERT INTO $table_name (location, position_type, title_code, posting_date, closing_date, job_title, position_summary, salary_range, url, internal_job_num, job_type)
		//VALUES ("$q['loc']", "$q['position_type']",  "$q['title_code']", $q['posting_date'], $q['closing_date'], $q['job_title'], $q['position_summary'], $q['salary_range'], $q['url'], $q['internal_job_num'], $q['job_type']) ")
		//or die(mysql_error());
	}
	
}
date_default_timezone_set('America/Los_Angeles'); // pacific time used because California
$my_listing = new job_listings();
$file_status = $my_listing->getDataFileFromServer();

?>
