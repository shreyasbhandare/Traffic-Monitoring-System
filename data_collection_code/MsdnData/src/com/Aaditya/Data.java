package com.Aaditya;

import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.File;
import java.io.FileWriter;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.ArrayList;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

public class Data {
	
	//Store the parkingLot name
	static ArrayList<String> parkingName = new ArrayList<String>();
	static ArrayList<String> parkingAddress = new ArrayList<String>();
	
	// current UNIX Time Stamp
	long startTimeUnix = System.currentTimeMillis();
	long endTimeUnix = startTimeUnix+1000;

	public static void main(String[] args) throws IOException {
		// TODO Auto-generated method stub
		
		double la = 40.485875;
		double lo = -74.445433;

		// Real time
		// 1st Http Request for parking data
		String url ="http://api.parkwhiz.com/venue/search/?lat="+la+"&lng="+lo+"&key=62d882d8cfe5680004fa849286b6ce20";
        URL obj = new URL(url);

        HttpURLConnection con = (HttpURLConnection) obj.openConnection();
        con.setRequestMethod("GET");

        BufferedReader in = new BufferedReader(
                new InputStreamReader(con.getInputStream()));


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
        		parkingName.add(object.getString("name"));
        		parkingAddress.add(object.getString("address"));
        		System.out.println(parkingName.get(i));
        		System.out.println(parkingAddress.get(i));
        		
        	}
        		       	
        } catch (JSONException e)
        {
        	e.printStackTrace();
        }      


}			// end of main	
	
}			// end of class
