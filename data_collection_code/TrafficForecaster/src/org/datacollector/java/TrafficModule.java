package org.datacollector.java;
import java.io.BufferedReader;
import java.io.File;
import java.io.FileOutputStream;
import java.io.FileReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.ProtocolException;
import java.net.URL;
import java.net.URLConnection;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.sql.Statement;
import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;
import java.util.Iterator;
import java.util.TimeZone;

import org.apache.commons.codec.binary.Base64;

import com.mashape.unirest.http.HttpResponse;
import com.mashape.unirest.http.JsonNode;
import com.mashape.unirest.http.Unirest;
import com.mashape.unirest.http.exceptions.UnirestException;

import org.json.simple.JSONArray;
import org.json.simple.JSONObject;
import org.json.simple.parser.JSONParser;
import org.json.simple.parser.ParseException;


public class TrafficModule 
{
	private final String wazekey = "BUd2ludbanmshrv7YdPvcWIGf8DYp1y6YTAjsnFi9PIGCJB4sH";
	
	public TrafficModule()
	{
		
	}
	
	public String getwazekey()
	{
		return this.wazekey;
	}
	
	public void getTrafficData(double LatBottom, double LatTop, double LonLeft, double LonRight) throws IOException
	{
		String path = "C:\\Users\\pateku1\\workspace\\TrafficForecaster\\" + "jsondata.json";
		
		String strLatBottom = Double.toString(LatBottom);
		String strLatTop = Double.toString(LatTop);
		String strLonLeft = Double.toString(LonLeft);
		String strLonRight = Double.toString(LonRight);
		
		try {
			
			HttpResponse<JsonNode> response = Unirest.get("https://bestapi-waze-unoffical-free-v1.p.mashape.com/traffic-notifications?latBottom=" + strLatBottom + "&latTop=" + strLatTop +"&lonLeft=" + strLonLeft + "&lonRight=" + strLonRight)
					.header("X-Mashape-Key", wazekey)
					.header("Accept", "application/json")
					.asJson();
			
			BufferedReader buff = new BufferedReader(new InputStreamReader(response.getRawBody()));
			
			String inputline;
			StringBuffer jsonresponse = new StringBuffer();
			
			// Parse through the buffered reader and write all data to buffer
			
			while((inputline = buff.readLine()) != null)
			{
				jsonresponse.append(inputline);		
			}
			
			String output = jsonresponse.toString();
			buff.close();
			//System.out.println(output);
			
			File newfile = new File(path);
			
			// Write data to the XML file
			
			FileOutputStream fo = new FileOutputStream(newfile);
			fo.write(output.getBytes());
			fo.close();
			
		} catch (UnirestException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		/*
		
		try 
		{
			parsejson(path);
		} catch (SQLException e) 
		{
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		*/
		
	}
	
	public void parsejson(String path) throws SQLException
	{
		// Here we define the parameters that will be extracted from the JSON file
		int severity = 0;
		String congestiontype = null;
		String country = null;
		String endstreet = null;
		String startstreet = null;
		String street = null;
		double startlat = 0.0;
		double endlat = 0.0;
		double startlong = 0.0;
		double endlong = 0.0;
		double delay = 0.0;
		
		//Below for Waze Type Alerts
		int numofthumbs = 0;
		String incidenttype = null;
		String incidentsubtype = null;
		String placenearby = null;
		Double wazelat = 0.0;
		Double wazelong = 0.0;
		String zipcode = null;
		
		// this is to store whether it is weekday or weekend
		String dayofweek = getdayofweek();
		
		//String date to store today's date to store in the incident
		String incidentdate = getcurrectdate();
		String incidenttime = getcurrecttime();
		
		try
		{
			Class.forName("com.mysql.jdbc.Driver");							//Step 1: Load JDBC Driver for MySQL Database
			String url = "jdbc:mysql://localhost:3306/trafficprediction?";	//Step 2: Establish Connection
			String username = "kush";	
			String password = "password";
			String dbname = "livecongetion";
			
			Connection conn = DriverManager.getConnection(url,username,password);
			
			// Below Code is the format of the INSERT Command
			//INSERT INTO `livecongestion`(`Severity`, `CongestionType`, `EndStreet`, `StartStreet`, `Street`, `StartLatitude`, `StartLongitude`, `EndLatitude`, `EndLongitude`, `Delay`) VALUES (3,"Jams","Madison Ave","null","Ave D",40.726238,-73.974138,40.726735,-73.974138,400)
			
			Statement statement = conn.createStatement();			// create our java jdbc statement
			Statement statement2 = conn.createStatement();
			Statement statement3 = conn.createStatement();
			String insertcommand = null;
			String wazeinsertcommand = null;
			String historicwazeinsertcommand = null;
			
			conn.setAutoCommit(false);
			
			FileReader reader = new FileReader(path);
			JSONParser jsonParser = new JSONParser();
			JSONObject jsonObject = (JSONObject) jsonParser.parse(reader);
			
			 // There are 2 arrays Alerts and Jams. We will Store them in the JSON Array Object and than parse them individually
            JSONArray alerts = (JSONArray) jsonObject.get("alerts");
            JSONArray jams = (JSONArray) jsonObject.get("jams");
            
            Iterator j = jams.iterator();
            
            while (j.hasNext())
            {
            	JSONObject jamobjects= (JSONObject) j.next();
            	
            	severity = ((Long) jamobjects.get("severity")).intValue();
        		congestiontype = (String) jamobjects.get("type");
        		country = (String) jamobjects.get("country");
        		endstreet = (String) jamobjects.get("end");;
        		startstreet = (String) jamobjects.get("start");
        		street = (String) jamobjects.get("street");
        		startlat = Double.parseDouble((String) jamobjects.get("startLatitude"));
        		startlong = Double.parseDouble((String) jamobjects.get("startLongitude"));
        		endlat = Double.parseDouble((String) jamobjects.get("endLatitude"));
        		endlong = Double.parseDouble((String) jamobjects.get("startLongitude"));
        		delay = ((Long) jamobjects.get("delayInSec")).intValue();
        		
        		dayofweek = getdayofweek();
        		zipcode = getzipcode(startlat,startlong);
        		
        		
        		//System.out.println("Severity: " +severity + "\n" + "End Street: " + endstreet + "\n" + "Start Street: " + startstreet + "\n" + "Street: " +street + "\n" + "Start Latitude: " + startlat + "\n" + "Start Longitude : " +startlong + "\n" + "End Latitude: " + endlat + "\n" + "End Longitude: " + endlong );
            	
        		insertcommand = "INSERT INTO `livecongestion`(`ZipCode`,`Day`,`Date`,`Time`,`Severity`, `CongestionType`, `EndStreet`, `StartStreet`, `Street`, `StartLatitude`, `StartLongitude`, `EndLatitude`, `EndLongitude`, `Delay`) VALUES (\"" + zipcode + "\",\"" +dayofweek + "\",\"" +incidentdate+ "\",\"" + incidenttime + "\","+ severity + ", \"Jams\" ,\"" + endstreet + "\",\"" + startstreet + "\",\"" + street + "\"," + startlat + "," +startlong + "," + endlat + "," + endlong + "," + delay + ")";
        		
        		statement.addBatch(insertcommand);
            }
            
            
           statement.executeBatch();
            
            Iterator i = alerts.iterator();
            
            while (i.hasNext())
            {
            	JSONObject alertobjets = (JSONObject) i.next();
            	
            	country = (String) alertobjets.get("country");
            	numofthumbs = ((Long) alertobjets.get("numOfThumbsUp")).intValue();
            	incidenttype = (String) alertobjets.get("type");
            	incidentsubtype = (String) alertobjets.get("subType");
            	placenearby = (String) alertobjets.get("placeNearBy");
            	wazelat = Double.parseDouble((String) alertobjets.get("latitude"));
            	wazelong = Double.parseDouble((String) alertobjets.get("longitude"));
            	zipcode = getzipcode(wazelat,wazelong);
            	
            	wazeinsertcommand = "INSERT INTO `livewazealerts`(`ZipCode`,`numofThumbs`, `incidenttype`, `incidentsubtype`, `placenearby`, `latitude`, `longitude`, `Day`,`Date`, `Time`) VALUES (\"" + zipcode + "\"," + numofthumbs +  ",\"" + incidenttype + "\",\"" + "incidentsubtype" + "\",\"" + placenearby + "\"," + wazelat + "," + wazelong  + ",\"" + dayofweek + "\",\"" +incidentdate+ "\",\"" + incidenttime + "\")" ;
            	historicwazeinsertcommand = "INSERT INTO `historicwazealerts`(`ZipCode`,`numofThumbs`, `incidenttype`, `incidentsubtype`, `placenearby`, `latitude`, `longitude`, `Day`,`Date`, `Time`) VALUES (\"" + zipcode + "\"," + numofthumbs +  ",\"" + incidenttype + "\",\"" + "incidentsubtype" + "\",\"" + placenearby + "\"," + wazelat + "," + wazelong  + ",\"" + dayofweek + "\",\"" +incidentdate+ "\",\"" + incidenttime + "\")" ;
            	
            	statement2.addBatch(wazeinsertcommand);
            	statement3.addBatch(historicwazeinsertcommand);
            }
            
            statement2.executeBatch();
            statement3.executeBatch();
            conn.commit();
            conn.close();
		
		}
		catch (Exception e){
			System.err.println("Got an Exception while parsing JSON Data!!");
			System.err.println(e.getMessage());
			e.printStackTrace();
		}


	}
	
	public void cleanlivewazealerts () throws SQLException
	{
		try 
		{
			Class.forName("com.mysql.jdbc.Driver");
			String url = "jdbc:mysql://localhost:3306/trafficprediction?";	//Step 2: Establish Connection
			String username = "kush";	
			String password = "password";
			String dbname = "livewazealerts";
			
			Connection conn = DriverManager.getConnection(url,username,password);
			
			// Below Code is the format of the INSERT Command
			//INSERT INTO `livecongestion`(`Severity`, `CongestionType`, `EndStreet`, `StartStreet`, `Street`, `StartLatitude`, `StartLongitude`, `EndLatitude`, `EndLongitude`, `Delay`) VALUES (3,"Jams","Madison Ave","null","Ave D",40.726238,-73.974138,40.726735,-73.974138,400)
			
			Statement statement = conn.createStatement();			// create our java jdbc statement
			String sqlquery = "DELETE FROM `livewazealerts` WHERE 1";
			statement.executeUpdate(sqlquery);
			conn.close();
			
		} 
		catch (ClassNotFoundException e) 
		{
			System.out.println("Error while deleting live waze alerts data!!");
			e.printStackTrace();
		}							//Step 1: Load JDBC Driver for MySQL Database
		
		
	}
	
	public String getdayofweek()
	{
		Calendar calendar = Calendar.getInstance(TimeZone.getDefault());
		Date date = calendar.getTime();
		int dayOfWeek = calendar.get(Calendar.DAY_OF_WEEK);
		
		if((dayOfWeek == 1) || (dayOfWeek == 7))
		{
			return "Weekend";
		}
		else if (dayOfWeek == 2)
		{
			return "Monday";
		}
		else if (dayOfWeek == 3)
		{
			return "Tuesday";
		}
		else if (dayOfWeek == 4)
		{
			return "Wednesday";
		}
		else if (dayOfWeek == 5)
		{
			return "Thursday";
		}
		else
		{
			return "Friday";
		}
	}
	
	public String getcurrectdate()
	{
		Calendar localCalendar = Calendar.getInstance(TimeZone.getDefault());
		
		int currentDay = localCalendar.get(Calendar.DATE);
		int currentMonth = localCalendar.get(Calendar.MONTH) + 1;
		int currentYear = localCalendar.get(Calendar.YEAR);
		
		String output = currentYear + "-" + currentMonth + "-" + currentDay;
		
		return output;
		
	}

	
	public String getcurrecttime()
	{
		Calendar localCalendar = Calendar.getInstance(TimeZone.getDefault());
		Date currentTime = localCalendar.getTime();
		
		DateFormat outputdate = new SimpleDateFormat("HH:" + "00:00");
		
		String output = outputdate.format(currentTime);
		
		//output = "17:00:00";
		
		return output;
		
	}
	
	public String getzipcode (Double Latitude, Double longitude) throws Exception 
	{
		String url = "http://api.geonames.org/findNearbyPostalCodesJSON?lat=" + Latitude + "&lng=" + longitude + "&country=US&radius=1&username=kushpatel";			
		String path = "C:\\Users\\pateku1\\workspace\\TrafficForecaster\\" + "zipcodelookup.json";
		String temp = null;
		
		URL url1;

			url1 = new URL(url);
			HttpURLConnection link = (HttpURLConnection)url1.openConnection();
			link.setRequestMethod("GET");
			BufferedReader buff = new BufferedReader(new InputStreamReader(link.getInputStream()));
			String inputline;
			StringBuffer response = new StringBuffer();
			
			// Parse through the buffered reader and write all data to buffer
			
			while((inputline = buff.readLine()) != null)
			{
				response.append(inputline);		
			}
			
			String output = response.toString();
			buff.close();
			
			//System.out.println(output);
			
			File newfile = new File(path);
			
			// Write data to the JSON file
			
			FileOutputStream fo = new FileOutputStream(newfile);
			fo.write(output.getBytes());
			fo.close();
		

		FileReader reader = new FileReader(path);
		JSONParser jsonParser = new JSONParser();
		JSONObject jsonObject = (JSONObject) jsonParser.parse(reader);
		
		 // There are 2 arrays Alerts and Jams. We will Store them in the JSON Array Object and than parse them individually
        JSONArray alerts = (JSONArray) jsonObject.get("postalCodes");
        
        Iterator i = alerts.iterator();
        
        while (i.hasNext())
        {
        	JSONObject alertobjets = (JSONObject) i.next();
        	temp = (String)alertobjets.get("postalCode");
        }
        
        return temp;
		
		
	}
	
	
}


