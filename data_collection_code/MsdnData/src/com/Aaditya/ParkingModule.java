package com.Aaditya;

import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.File;
import java.io.FileOutputStream;
import java.io.FileReader;
import java.io.FileWriter;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.Iterator;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import org.json.simple.parser.JSONParser;
import org.json.simple.parser.ParseException;

public class ParkingModule {
	
		//Store the parkingLot name
		static ArrayList<String> parkingName = new ArrayList<String>();
		static ArrayList<String> parkingAddress = new ArrayList<String>();
		
		// current UNIX Time Stamp
		long startTimeUnix = System.currentTimeMillis();
		long endTimeUnix = startTimeUnix+1000;
		
		private double la;
		private double lo;
		
		private final String parkwizkey= "62d882d8cfe5680004fa849286b6ce20";
		
		public ParkingModule()
		{
			// Latitude and Longitude to New Brunswick
			this.lo = -74.445433;
			this.la= 40.485875;
		}
		
		public String getparkwizkey()
		{
			return this.parkwizkey;
		}
		
		public void getParkingData() throws IOException, ClassNotFoundException, SQLException, ParseException
		{
			
			// Here we define the parameters that will be extracted from the JSON file
			String name = null;
			double latitude = 0.0;
			double longitude = 0.0;
			String address = null;
			String city = null;
			String parkstate = null;
			String zipcode = null;
			double [] latlong = new double[2];
			
			// Database Parameters
			Class.forName("com.mysql.jdbc.Driver");							//Step 1: Load JDBC Driver for MySQL Database
			String dburl = "jdbc:mysql://localhost:3306/trafficprediction?";	//Step 2: Establish Connection
			String username = "kush";	
			String password = "password";
			String dbname = "parkinglots";
			
			Connection conn = DriverManager.getConnection(dburl,username,password);
			
			Statement statement = conn.createStatement();			// create our java jdbc statement
			String insertcommand = null;
			
			conn.setAutoCommit(false);

			// Real time
			// 1st Http Request for parking data
			String url ="http://api.parkwhiz.com/venue/search/?lat="+la+"&lng="+lo+"&key=62d882d8cfe5680004fa849286b6ce20";
	        URL obj = new URL(url);

	        HttpURLConnection con = (HttpURLConnection) obj.openConnection();
	        con.setRequestMethod("GET");

	        BufferedReader in = new BufferedReader(new InputStreamReader(con.getInputStream()));

	        String inputLine;
	        StringBuffer response = new StringBuffer();


	        while ((inputLine = in.readLine()) != null) {
	            response.append(inputLine);
	        }
	        in.close();        
	       
	        // Write to file
	       // String content = "This is the content to write into file";
	        String path = "C:\\Users\\pateku1\\workspace\\TrafficForecaster\\parkingdata.json";
	        File file = new File(path);
	        FileWriter fw = new FileWriter(file.getAbsoluteFile());
	        BufferedWriter bw = new BufferedWriter(fw);
	        bw.write(response.toString());
	        bw.close();// be sure to close BufferedWriter
	        
	        //---------Parse the response----------------------------------------
	        String jsonString = response.toString();
	        
	        try
	        {
	        	JSONArray jsonArray = new JSONArray(jsonString);
	        	JSONObject object;

	         	for (int i =0 ; i <jsonArray.length(); i++)
	        	{
	        		object = (JSONObject) jsonArray.get(i);
	        		name = object.getString("name");
	        		address = object.getString("address");
	        		city = object.getString("city");
	        		parkstate = object.getString("state");
	        		zipcode = object.getString("zip");

	        		///insertcommand = "INSERT INTO `parkinglots`(`Latitude`, `Longitude`, `Name`) VALUES (" + [value-1],[value-2],[value-3])"
	        		
	        		//System.out.println(name);
	        		//System.out.println(address);
	        		//System.out.println(city);
	        		//System.out.println(parkstate);
	        		
	        		insertcommand = "INSERT INTO `parkinglotstemp`(`Name`, `Address`) VALUES (\"" + name + "\",\"" + address + "," + city + "," + parkstate + "\")";
	        		statement.addBatch(insertcommand);
	        		
	        	}
	         	statement.executeBatch();
	         	conn.commit();
	            conn.close();
	        		       	
	        } catch (JSONException e)
	        {
	        	e.printStackTrace();
	        }      
	        
		}
		

		
		
		
		
		

}


