import static org.junit.Assert.*;

import org.junit.Test;

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

public class PredictionTests {
	
	@Test
	public void check_db_connectivity() throws SQLException, ClassNotFoundException
	{
		//Here we will check if the data is being written to Database and if we are getting data from database.
		//
		
		Class.forName("com.mysql.jdbc.Driver");							//Step 1: Load JDBC Driver for MySQL Database
		String url = "jdbc:mysql://localhost:3306/trafficprediction?";	//Step 2: Establish Connection
		String username = "kush";	
		String password = "password";
		String dbname = "livecongetion";
		
		int k= 9000;
		double lat= 0;
		double lon= 0;
		String c[]= new String[k];
		
		Connection conn = DriverManager.getConnection(url,username,password);
		
		Statement statement = conn.createStatement();			// create our java jdbc statement
		
		String Stname= "Ave D";
		String sql = "SELECT `Start Latitude`,`Start Longitude`  FROM `livecongestion` WHERE `Street`= \"" + Stname + "\"";
	    ResultSet rt = statement.executeQuery(sql);
	    
	    int i=0;
	    while(rt.next()){
	    	lat=rt.getDouble("Start Latitude");
	    	lon=rt.getDouble("Start Longitude");
	    }
	    if ((lat+lon) < 1)
		{
			fail("Data not taken from database");
		}
	}
}

