import static org.junit.Assert.*;

import org.junit.Test;
import org.datacollector.java.*;

import java.io.File;
import java.io.IOException;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;

public class TrafficModuleTests {

	@Test
	public void checkTrafficobjectbeingcreated()
	{
		TrafficModule testmodule = new TrafficModule();
		assertEquals("Object not created with proper Waze key", "BUd2ludbanmshrv7YdPvcWIGf8DYp1y6YTAjsnFi9PIGCJB4sH", testmodule.getwazekey());
	}
	
	@Test
	public void check_if_trafficdata_getting_downloaded() throws IOException
	{
		// Below path is where the traffic data is locally stored on the computer locally
		String path = "C:\\Users\\pateku1\\workspace\\TrafficForecaster\\" + "jsondata.json";
		
		// So first we delete the file to ensure that on old file is not being checked.
		try{
    		
    		File file = new File(path);
        	
    		if(file.delete()){
    			System.out.println(file.getName() + " is deleted!");
    		}else{
    			System.out.println("file is not on local drive. Already deleted");
    		}
    	   
    	}catch(Exception e){
    		
    		e.printStackTrace();
    	}
		
		// Now we call the method on the TrafficModule.gettrafficadata() and check if the new file is being stored on the local drive
		TrafficModule testmodule = new TrafficModule();
		testmodule.getTrafficData(40.3854996541, 40.8145859205, -74.5465144794, -74.1812077294);
		
		File file = new File(path);
		
		if(file.exists()){
			System.out.println(file.getName() + " exists on local machine");
		}
		else{
			fail("Error");
		}
		
	}
	
	@Test
	public void check_data_written_to_database() throws IOException, SQLException, ClassNotFoundException
	{
		// Here we will check the parsejson() Method that parses and writes an entry to the database.
		// First we need to call the Traffic.gettrafficdata() and than call the parsejson()
		
		// Now we call the method on the TrafficModule.gettrafficadata() and store into the database
		TrafficModule testmodule = new TrafficModule();
		testmodule.getTrafficData(40.3854996541, 40.8145859205, -74.5465144794, -74.1812077294);
		
		// Below path is where the traffic data is locally stored on the computer locally
		String path = "C:\\Users\\pateku1\\workspace\\TrafficForecaster\\" + "jsondata.json";
		
		testmodule.parsejson(path);
		
		// Now we will connect to our database and check an entry has been made for today's date and today's hour
		
		Class.forName("com.mysql.jdbc.Driver");							//Step 1: Load JDBC Driver for MySQL Database
		String url = "jdbc:mysql://localhost:3306/trafficprediction?";	//Step 2: Establish Connection
		String username = "kush";	
		String password = "password";
		String dbname = "livecongetion";
		
		Connection conn = DriverManager.getConnection(url,username,password);
		
		Statement statement = conn.createStatement();			// create our java jdbc statement
		
		String currtime = testmodule.getcurrecttime();
		String currdate = testmodule.getcurrectdate();
		String countquery = "SELECT COUNT(ID) FROM `livecongestion` WHERE `Date`= \"" + currdate + "\" AND `Time` = \"" + currtime + "\"";
		ResultSet rs = statement.executeQuery(countquery);
		
		int count = 0;
		
		while (rs.next())
		{
			count = rs.getInt("COUNT(ID)");
		}
		 
		if (count < 1)
		{
			fail("Data not written to database");
		}
		
				
	}

}
