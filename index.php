<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <link rel='stylesheet' href='include/main.css'>
        <? //require 'include/survey_lib.php';?>
        <title>Max Perez</title>
        <style>
        .note {
        	font-style:italic;
        	clear:both;
        }
        .input {
			clear:both;
			margin-top:20px;
		}
		.col {
			float:left; 
			width: 150px;
		}
        </style>
    </head>
    <body>
        
        <div id="contents">
            
            <form method="post" id="searchform" action="./include/search.php">
                <fieldset>
                    <h1>Search UC Jobs</h1>
                    
                    <div class="note">Indicates required field</div>
                    <div class="input">
                        <label for="keywords"><strong>Keyword(s):</strong></label>
                        <input type="text" name="keywords" maxlength="128" size="55" placeholder=" What position do you seek?">
                    </div>

                    <div class="input">
                        <label for="location"><strong>Location(s)</strong> Choose one or more locations to search:</label><br>
                        <div class="col">
							<input type="checkbox" name="campus[]" value="berkeley"> Berkeley<br>
							<input type="checkbox" name="campus[]" value="ucla"> UCLA<br>
							<input type="checkbox" name="campus[]" value="san_diego"> San Diego<br>
						</div>
						<div class="col">
							<input type="checkbox" name="campus[]" value="davis"> Davis<br>
							<input type="checkbox" name="campus[]" value="merced"> Merced<br>
						</div>
						<div class="col">
							<input type="checkbox" name="campus[]" value="irvine"> Irvine<br>
							<input type="checkbox" name="campus[]" value="santa_cruz"> Santa Cruz<br>
							<p>...and on and on to match the form.</p>
                        </div>
                    </div>
                        
                    <div class="input"><br>
						<label for="posted"><strong>Jobs Posted:</strong></label><br>
						<input type="radio" name="posted" id="yesterday" value="yesterday">
						<label for="yesterday">since yesterday</label>
						<input type="radio" name="posted" id="three_days" value="three_days">
						<label for="three_days">in the last three days</label>
						<input type="radio" name="posted" id="seven_days" value="seven_days">
						<label for="seven_days">in the last seven days</label>
						<input type="radio" name="posted" id="all" value="all">
						<label for="all">show all</label>
                    </div>

                    <div class="input">
						<label for="job_type"><strong>Job Type:</strong></label><br>
						<input type="radio" name="job_type" id="full_time" value="full_time">
						<label for="full_time">Full time</label>
						<input type="radio" name="job_type" id="part_time" value="part_time">
						<label for="part_time">Part time</label>
						<input type="radio" name="job_type" id="all" value="all">
						<label for="all">Show all</label>
                    </div>

                    <div class="input">
                        <input type="submit" value="Search"> <input type="reset" value="Reset"> 
                    </div>

                </filedset>
            </form>

        </div>
    </body>
</html>
