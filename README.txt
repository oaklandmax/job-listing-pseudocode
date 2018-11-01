Hi Patrick,

Well there is a lot of stuff here. It mostly looks like code, but there is a fair amount of pesudocode in the comments describing the parts
that were clearly similar to the working code, and describing what things might look like.

"sudo php job_listings.php":
Deliverables 1 and 2 are in the include/job_listings.php file which would be triggered by a cron job, or launchctl, or whatever the server OS
uses to schedule scripts. There are notes in the file indicating which object functions are designed to fulfill each deliverable.
This can be tested locally using "sudo php job_listings.php" to pull the file "campus_0.zip" from a speedtest ftp server which is the stand in for 
a campus job listing ftp server to be specified later. It is set up in an array to accomodate multiple servers, but as it stands is an array of one.

Upon pulling the dummmy file, the sample data csv file is then read in and validated, displaying clean data to the screeen.

Deliverable 3 and 4 are in include/search.php which is called by the form in index.php, and are noted in the comments in the code. The report description 
depends on looking at the sample data, and envisioning it as a formatted table, which would work with technically savvy people, but hopefully is clear
enough for folks who are looking at it from a business perpective.

This has been a fun project, and given more time it could be even better with more validations, better SQL security, etc. 

I look forward to continuing in the interview process, and hope this is close to what you were hoping to receive. 

-Max Perez
