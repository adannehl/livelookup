<?php
//This is a sample file which could be used for a HelpSpot Live Lookup. This example uses HTTP GET
//Note that a Live Lookup file can be written in ANY language as long as it returns valid XML in the format expected
//It uses a comma separated file as a data source

//This file would be automatically called by HelpSpot with an HTTP GET string like this:
// http://www.mycompany.com/data/livelookup.php?customer_id=123456&first_name=Bob&last_name=Jones&email=bjones@mycompany.com&phone=2126387654

//Set CSV file location
$csv = 'C:\Users\MES Spark PA\OneDrive - PaILS Inc\Projects\Export all.csv';

//This example assumes a CSV file in this format (no headers)
// "customer ID", "customer first name", "customer last name", "customer email", "customer phone number" 

//Output the XML encoding
header('Content-type: text/xml');
echo '<?xml version="1.0" encoding="ISO-8859-1"'."?".">\n";

//Read in CSV File
$matches = array();
$handle = fopen($csv, "r");
while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
   $num = count($data);
	//Here we'll search each row as it comes through if we find a person matching the customer ID OR email that was passed
	//in then we'll have a match. In a production version you could do more here like serach for matches against last names
	//or phone numbers. 
	if(!empty($_GET['email']) && $data[1] == $_GET['email']){	//If email on this line matches one passed in then this line is a match
		$matches[] = $data;
	}
}
fclose($handle);
	
echo '<livelookup version="1.0" columns="customer_id,first_name,last_name">';
	//If we found some matches then output them	
	if(count($matches)){
		//Output each customer, these will be shown to the help desk user. The user can then pick the right one (if more than one returned). 
		//The data can also be automatically inserted.
		foreach($matches AS $person){
			echo '
			<customer>
				<customer_id>'.$person[0].'</customer_id>
				<email>'.$person[1].'</email>
				<phone>'.$person[3].'</phone>					
			</customer>';
		}
	}
echo '</livelookup>';		
	
?>