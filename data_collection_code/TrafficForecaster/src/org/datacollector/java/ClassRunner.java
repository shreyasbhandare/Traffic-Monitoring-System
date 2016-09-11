package org.datacollector.java;

import com.aaditya.*;
import com.Aaditya.*;

import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;
import java.util.Locale;

public class ClassRunner 
{
	public ClassRunner()
	{
		Date currtime;
		Date open;				//Opening Time for Stocks
		Date close;				//Closing Time for Stocks
		
		// Make sure Entered times below are 24 Hour Format - Military Time
		open = parseDate("08:00");
		close= parseDate("23:00");
		
		currtime = getcurrenttime();
		
		System.out.println("Current Time: " +currtime);
		System.out.println("Open Time: " +open);
		System.out.println("Close Time: " +close);
		
		String path = "C:\\Users\\pateku1\\workspace\\TrafficForecaster\\" + "jsondata.json";
		
		try
		{
			currtime = getcurrenttime();
			//Open.before(currtime) – Tests if open is before currtime
			//Close.after(currtime) – Tests if close is after currtime
			// For continuos query, change if to while & uncomment the thread.sleep
			
			while(open.before(currtime) && close.after(currtime))
			{
				
				TrafficModule mod1 = new TrafficModule();
				
				//First clean out the livewazealerts table.
				//mod1.cleanlivewazealerts();
				
				// ---------------------- Below for Traffic Data Call ---------------------------------------
				mod1.getTrafficData(40.3854996541, 40.8145859205, -74.5465144794, -74.1812077294);
				mod1.parsejson(path);
				System.out.println("Area 1 Complete!");
				mod1.getTrafficData(40.6119, 41.0416, -74.1807, -73.9527);
				mod1.parsejson(path);
				System.out.println("Area 2 Complete!");
				mod1.getTrafficData(40.625165, 41.054754, -73.9223497, -73.694351);
				mod1.parsejson(path);
				System.out.println("Area 3 Complete!");
				
				// --------------------- Below for Restaurants Data Call ------------------------------------
				YelpAPI getdata1 = new YelpAPI();
				getdata1.getYelpData();
				System.out.println("Restaurant Data Complete!!");
				
				ParkingModule module1 = new ParkingModule();
				module1.getParkingData();
				System.out.println("Parking Data Complete!!");
				
				
				// -------------------- Prediction -------------------------------------------------------------
				prediction trafficprediction = new prediction();
				trafficprediction.predict();
				System.out.println("Prediction Complete");
				
				// -------------------- Now go to sleep-----------------------------------------------------------
				

				Thread.sleep(3600*1000);
				currtime = getcurrenttime();
				System.out.println("Current Time: " +currtime);
				System.out.println("Open Time: " +open);
				System.out.println("Close Time: " +close);
			}
			
			System.out.println("Daily Market Closed.Exit!!");
		}
		catch(Exception e)
		{
			System.out.println("Error calling Thread Sleeps");
		}
		
		
	}
	
	public static void main(String args[])
	{

		ClassRunner test  = new ClassRunner();
		
    }
	
	public Date getcurrenttime()
	{
		Calendar now = Calendar.getInstance();
		
		// Calendar.HOUR will give Hour in 12 Hour Format
		// Calendar.HOUR_OF_DAY will give Hour in 24 Hour Format
		int hour = now.get(Calendar.HOUR_OF_DAY);
		int minute = now.get(Calendar.MINUTE);
		
		//System.out.println("Hour: " +hour);
		//System.out.println("Minute: " +minute);
		
		Date currdate = null;
		
		currdate = parseDate(hour + ":" + minute);
	
		return currdate;
	}
	
	private Date parseDate(String date) {

		SimpleDateFormat dateparser = new SimpleDateFormat("HH:mm", Locale.US);
	    try 
	    {
	        return dateparser.parse(date);
	    } 
	    catch (java.text.ParseException e) 
	    {
	    	System.out.println("Error Calling Current Date Method!!!");
	        return new Date(0);
	    }
	}


}
