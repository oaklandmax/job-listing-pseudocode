<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
    </head>
    <body>
		<?php
		include_once('./db.php');
		
		class search extends db {

			private $arg_count = 0;
			
			////////////////////////////////////////
			// Deliverable 4: Describe and pseudocode the handling of search parameters from the search form to convert them into a SQL
			// query for the case where a user submits values for all search fields. Show a search query. 
			////////////////////////////////////////
			

			private function addSqlConjuction(){
				$return_string = '';
				if ($this->arg_count) {
					$return_string = ' AND';
				}
				return $return_string;
			}

			private function buildSQL(){
				
				// TODO: validate listings. maybe here, maybe in a separate function.
				
				$debug = false;
				
				if ($debug) {
					print_r($_POST);
				}
				
				// clean up keyword string of commas and spaces, and make array 
				$keywords = trim($_POST['keywords']);
				$keywords = str_replace(" ",",", $keywords);
				$keywords = preg_replace('/,,+/',',', $keywords);
				$keywords = explode(',',$keywords);
				
				// make dynamic keyword query
                if (count($keywords) && $keywords[0] != ''){
                    $keyword_query = 'WHERE ';
                    for( $i=0; $i < count($keywords); $i++){
						$keyword_query .= "job_desc LIKE '" . $keywords[$i] . "'";
						if ($i < count($keywords) -1){
							$keyword_query .= " OR WHERE ";
						}
                    }				
                }
				
				// set dynamic query for checked location
				if (count($_POST['campus'])) {
                    $locations = 'WHERE ';
                    for( $i=0; $i < count($_POST['campus']); $i++){
                        $locations .= "location = '" . $_POST['campus'][$i] . "'";
                        if ($i < count($_POST['campus']) -1){
                            $locations .= " OR WHERE ";
                        }
                    }
                }
				
				// set date since posting query. date is stored as unix timestamp.
				// psudocode this using 86400 seconds per day, multiplied by the 
				// number of days old the post is, subtracted from current datestamp.
				// for choices other than "all". prob should do this from midnight of the local day instead of now.
				$posting_age_in_seconds = null;
				if ($_POST['posted'] == 'yesterday') {
					$posting_age_in_seconds = strtotime("now") - 86400;
				} else if ($_POST['posted'] == 'three_days') {
					$posting_age_in_seconds = strtotime("now") - (86400 * 3);
				} else if ($_POST['posted'] == 'seven_days') {
					$posting_age_in_seconds = strtotime("now") - (86400 * 7);
				}
								
				// Build SQL query from above vars. 
				$sql = "SELECT * FROM " . TABLE;
                
                // add keyword query if any are specified
                if ($keyword_query){
					$sql .= $this->addSqlConjuction();
					$sql .= " (" . $keyword_query . ")";
					$this->arg_count++;
                }

                // add locations if any specified
                if ($locations){
					$sql .= $this->addSqlConjuction();
					$sql .= " (" . $locations. ")";
					$this->arg_count++;
                }

				
				// add posting age if user chooses something other than all
				if ($_POST['posted'] == 'yesterday' || $_POST['posted'] == 'three_days' || $_POST['posted'] == 'seven_days') {
					$sql .= $this->addSqlConjuction();
					$sql .= " WHERE posted_date > " . $posting_age_in_seconds;
					$this->arg_count++;
				}
				
				// set sql params for job_type other than all.
				if ($_POST['job_type'] == 'full_time' || $_POST['job_type'] == 'part_time' ) {
					$sql .= $this->addSqlConjuction();
					$sql .= " WHERE job_type = '" . $_POST['job_type'] . "'";
					$this->arg_count++;
				}
				
				return $sql . ';';
			}
			
			
			public function get_listings() {
				
				$debug = false;
				
				$sql = $this->buildSQL();
				echo $sql;
				
				// not actually using database in this demo so return.
				return 0;
				
				// Open database connection
				$conn  = $this->open_db();
						
				// call the db with the query
				$result = $conn->query($sql);
				if ($result->num_rows > 0) {
					// put the rows into an array to be returned - may not be nesc. casting around here.
					while($row = $result->fetch_assoc()) {
						$arr[] = $row;
					}
					// this returns rows
					return $arr;
					if(!$arr) exit('No rows'); // not nesc?
				} else {
					if ($debug) {
						return "<br>0 results";
					}
				}
				$conn->close();
			}
			

			//////////////////
			// Deliverable 3:
			// The report will be similar to the sample data file, but properly formatted into a table, 
			// and scrubbed of invalid records and fields per the validation function.
			///////////////////
			public function display_report() {
				return 0;
			}
		
			
		}
		
		date_default_timezone_set('America/Los_Angeles'); // pacific time used because California
		$my_search = new search();
		$my_search->get_listings();
		$my_search->display_report();
		
		?>

    </body>
</html>
