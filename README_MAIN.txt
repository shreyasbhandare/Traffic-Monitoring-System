
Software Engineering
Group 7

The Project can be executed in the following steps. 

1) Download and Set Up Eclipse
		->   Download and Install Eclipse on your local computer.
		->	 Import the Project Folders that have been uploaded on the code Folder. The folder names are below.

				1) Yelp
				2) TrafficForecaster
				3) MsdnData

				First, you will need to import all these projects into your Eclipse Environment. After these projects have been imported, check for any errors. There should not be any errors
				because all the necessary JAR files are built with the folders and have been added to the build path. Sometimes, Eclipse will throw errors because the local directory path
				specified is different from my computer to the person runnin it. Please correct this in the build path folder. After that the errors will clear up.

2) Database Tables
		->	You will need to create 2 database with names below
				Database name: "tafficprediction"
				Database name: "customerdb"
				
		->	Below are the 5 different tables that you will need to create for "trafficprediction".
				Table name: "livewazealerts";
				Table name: "livecongestion";
				Table name: "historiclivewazealerts";
				Table name: "parkinglots";
				Table name: "restaurants";
			
			Note: Their structures for column names are in the JPG images as attached in the Project Folder. Please refer to them.
				
		->	Below are the 1 different tables that you will need to create for "customerdb".
				Table name: "logincredentials";
			
			Note: The column names and structure can be referred in the 'logincredentials_database_SCHEMA.JPG" image
				
				
3) Data Collection	
		 		Note: You might have to change the local path in some of the java classes. See below
				1) Line 53. (TrafficModule.java)
				    path = "C:\\Users\\pateku1\\workspace\\TrafficForecaster\\" + "jsondata.json";
				   
				2) Line 336. (TrafficModule.java)
					path = "C:\\Users\\pateku1\\workspace\\TrafficForecaster\\" + "zipcodelookup.json";
				
				3) Line 158. (YelpAPI.java)
					PrintWriter out = new PrintWriter("C:\\Users\\pateku1\\workspace\\TrafficForecaster\\YelpDataNJ.json");
					
				4) Line 101, (ParkingModule.java)
					String path = "C:\\Users\\pateku1\\workspace\\TrafficForecaster\\parkingdata.json";


				After the errors have been cleared, now the runner will need to setup the database. Create 2 databases, one names  and one named "trafficprediction"
			
			After the databases are created, we will run the main method that calls each of the above imported classes to collect data and write to the database.
			In TrafficForecaster, there is a class called "ClassRunner.java" which has the main method that will run the data collection for Traffic, 
			Parking Lots and Restaurants.

			Lines 47,50,53 define a bounding box within which all the alerts reported for that hour will be collected.  If the user wants anything outside this value, please enter your own bounding
			box co-ordinates. For the Yelp API and the Parking API, the input parameters are built in. At the moment, there is a restriction on the Yelp API because it restricts us to only 20 restaurants
			per call. For the parking lots, the limitation is only 10 calls per zip code. There is not any free API that will give more data calls than that. hence we are working with what is available.
			In the future, this can be modified to APIs that provide more detailed data.

			For the prediction part, the program takes, Street Name, Latitude, Longitude, Delay, Time from the data base where a particular day is specified.
			The program stores the data in a hash table of where it contains the street name, lat and longitude of the street and then the delay is stored according to the street name. 
			An if statement filters all the time between a specific time period, for example, 1P.M.-2P.M. for all the 4 weeks or all the available weeks for a particular day, eg. Thursday And estimates the value for the given street, on a specific date and time. IT writes the estimated delay back to the database and when the website calls prediction, the predicted value is being called.

			Run the classrunner.java and currently its set to run between 8:00 AM and 11:00PM. It will pause for an hour between calls. Which means we are collecting data once every hour. This can be modified
			as required. Once this data collection has been completed, the user can go to the webpage and navigate accordigly. 

4) Running Website
		->	Download a PHP Server onto local machine. Some popular one are "WAMP" that has PHP,MySQL and Apache. 
		->	Unzip "Crock.zip" on local drive.
		->	Navigate to the PHP "www" folder on the PHP Server Software Directory and copy the "Crock" folder
		->	Run the "Login.php" in your local browser. Typical URL is "localhost/Crock/Login.php"
		->	"Home3.php" will be the first page when user has logged in. User can do query for any particular zip code and get severity information traffic.
		->  "NNJ Severity.php". here user can enter day of the week, and hour of the week and hit run. Output will be severity information with predicted severity information. 
			user can also enter starting address and ending destination to obtain navigation on top of the severity information.
		->	"Alerts" here the user can obtain live information for police, accidents, road closures etc. 
		->	"Severity". Here user can do query for any zip code and obtain an average severity for that zip code for the last week.
		->	"Accident Charts". Here the user can do query for any zip code and obtain total number of police and accident reports reported for the past week.
		->	"Parking". Here the user can get the parking lots on the map as obtained from ParkWiz.API
		-	"Restaurants". Here the user can get the restaurants on the map as obtained from Yelp API

		


