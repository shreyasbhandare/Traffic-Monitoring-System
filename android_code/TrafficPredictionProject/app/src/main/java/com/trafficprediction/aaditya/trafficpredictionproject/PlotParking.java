package com.trafficprediction.aaditya.trafficpredictionproject;


import android.content.Context;
import android.location.Address;
import android.location.Geocoder;
import android.os.AsyncTask;
import com.google.android.gms.maps.model.LatLng;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URL;
import java.util.ArrayList;
import java.util.List;
import java.util.Locale;


import javax.xml.transform.Result;


public class PlotParking extends AsyncTask< LatLng, Void , ArrayList<Data> > {

    // Getting the Context of Maps Activity
    Geocoder has;
    Context ctx;


    public PlotParking ( Context context){
       ctx = context.getApplicationContext();
       has = new Geocoder(ctx);

    }


    //Store the parkingLot name
     ArrayList<String> parkingName = new ArrayList<String>();
     ArrayList<String> parkingAddress = new ArrayList<String>();
     ArrayList<Data> plotData = new ArrayList<Data>();

    double la;
    double lo;
    Data d = new Data();
    LatLng l;


    @Override
    protected ArrayList<Data> doInBackground(LatLng... params) {
        // 1st Http Request for parking data
        l = params[0];


        try {


            String url = "http://api.parkwhiz.com/venue/search/?lat=" + l.latitude + "&lng=" + l.longitude + "&key=62d882d8cfe5680004fa849286b6ce20";
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

            //---------Parse the response----------------------------------------
            String jsonString = response.toString();

            JSONArray jsonArray = new JSONArray(jsonString);
            JSONObject object;

            for (int i = 0; i < jsonArray.length(); i++) {
                object = (JSONObject) jsonArray.get(i);
                parkingName.add(object.getString("name"));
                parkingAddress.add(object.getString("address"));
                List <Address> list = has.getFromLocationName(parkingAddress.get(i), 1);
                Address add1 = list.get(0);

                la = add1.getLatitude();
                lo = add1.getLongitude();

                LatLng val = new LatLng(la, lo);

                d.setLat(la);
                d.setLon(lo);
                d.setDescription(parkingName.get(i));

                plotData.add(d);
            }

        } catch (IOException e){
            e.printStackTrace();
        } catch (JSONException e){
            e.printStackTrace();
        }
        return plotData;
    }

    @Override
    protected void onPostExecute(ArrayList<Data> plotData)
    {

    }


}
