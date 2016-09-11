package com.trafficprediction.aaditya.trafficpredictionproject;

import android.content.Context;
import android.content.Intent;
import android.content.res.AssetManager;
import android.location.Address;
import android.location.Geocoder;
import android.os.AsyncTask;
import android.support.v4.app.FragmentActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.AutoCompleteTextView;
import android.widget.Button;
import android.widget.EditText;

import com.google.android.gms.common.GooglePlayServicesNotAvailableException;
import com.google.android.gms.common.GooglePlayServicesRepairableException;
import com.google.android.gms.common.api.GoogleApiClient;
import com.google.android.gms.location.places.ui.PlacePicker;
import com.google.android.gms.maps.CameraUpdateFactory;
import com.google.android.gms.maps.GoogleMap;
import com.google.android.gms.maps.OnMapReadyCallback;
import com.google.android.gms.maps.SupportMapFragment;
import com.google.android.gms.maps.UiSettings;
import com.google.android.gms.maps.model.BitmapDescriptor;
import com.google.android.gms.maps.model.BitmapDescriptorFactory;
import com.google.android.gms.maps.model.LatLng;
import com.google.android.gms.maps.model.Marker;
import com.google.android.gms.maps.model.MarkerOptions;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.ArrayList;
import java.util.List;

public class MapsActivity extends FragmentActivity  implements OnMapReadyCallback {

    private GoogleMap mMap;                     //create  google map object
    String loca;                                // declare String for user entered location
    ArrayList<Data> da = new ArrayList<Data>();    // ArrayList<Data> to get the data of Parking
    double la;                                  // Latitude value
    double lo;                                  // Longitude value
    LatLng ln;                                   // LatLng variable
    String de;                                   // String description
    Data daNew;                                 // Data Object;


    Button enter;                   // button name for enter
    Button clean;                   // button name for clean
    Button events;                  // button name for special events
    Button constructions;           // button name for constructions
    Button incident;                // button name for incidents
    Button restaurant;              // button name for restaurant
    Button police;                  // button name for parking
    Button alert;                   // button name for alert
    Button parking;                  // button name for parking


    @Override
    protected void onCreate(Bundle savedInstanceState)  {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_maps);

        // Buttons
        enter           =            (Button) findViewById(R.id.enter);
        clean           =            (Button) findViewById(R.id.clean);
        events          =            (Button) findViewById(R.id.events);
        constructions   =            (Button) findViewById(R.id.constructions);
        incident        =            (Button) findViewById(R.id.incident);
        restaurant      =            (Button) findViewById(R.id.restaurant);
        police          =            (Button) findViewById(R.id.police);
        alert           =             (Button) findViewById(R.id.alert);
        parking         =             (Button) findViewById(R.id.parking);


        enter.setOnClickListener(new View.OnClickListener() {

            public void onClick(View view) {
                // Get a handle to user entered zip code
                AutoCompleteTextView add = (AutoCompleteTextView) findViewById(R.id.address);
                String location = add.getText().toString();
                setStringLocation(location);
                try {
                    geoLocate(location);
                } catch (IOException e) {
                    e.printStackTrace();
                }
            }
        });

        clean.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                // Get a handle to clean button
                    mMap.clear();
            }
        });

        events.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                try{
                    events();
                }catch (IOException e)
                {
                    e.printStackTrace();
                }

            }
        });

        constructions.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                try {
                    construction();
                } catch (IOException e) {
                    e.printStackTrace();
                }
            }
        });

        incident.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                try {
                    incident();
                } catch (IOException e) {
                    e.printStackTrace();
                }

            }
        });

        restaurant.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                // do something for restaurant
                try {
                    restaurant();
                } catch (IOException e) {
                    e.printStackTrace();
                }
            }
        });

        parking.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                // do something for restaurant
                try {
                    parking();
                } catch (IOException e) {
                    e.printStackTrace();
                }
            }
        });

        police.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                try {
                    police();
                } catch (IOException e) {
                    e.printStackTrace();
                }
            }
        });

        alert.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                try{
                    alert();
                }catch (IOException e)
                {
                    e.printStackTrace();
                }
            }
        });

        // Obtain the SupportMapFragment and get notified when the map is ready to be used.
        SupportMapFragment mapFragment = (SupportMapFragment) getSupportFragmentManager()
                .findFragmentById(R.id.map);
        mapFragment.getMapAsync(this);
    }   // end of onCreate


    @Override
    public void onMapReady (GoogleMap googleMap)
        {
            mMap = googleMap;
            mMap.getUiSettings().setZoomControlsEnabled(true);
            mMap.getUiSettings().setMapToolbarEnabled(true);
            mMap.getUiSettings().setMyLocationButtonEnabled(true);

            // Add a Default marker to New brunswick and move the camera

            LatLng val = new LatLng(40.485875, -74.445433);
            mMap.addMarker(new MarkerOptions().position(val).title("Default"));
            mMap.moveCamera(CameraUpdateFactory.newLatLngZoom(val, 11.5f));
        }

    // Getter and Setters for location entered by user
    public void setStringLocation (String l)
    {
        loca = l;
    }

    public String getStringLocation()
    {
        return loca;
    }


    // Method to get Lat and long values from string address
    public LatLng geoLocate (String loc) throws IOException{
        Geocoder gc = new Geocoder(this);
        List <Address> list = gc.getFromLocationName(loc, 1);
        Address add1 = list.get(0);

         la = add1.getLatitude();
         lo = add1.getLongitude();

        LatLng val = new LatLng(la, lo);
        plotMarker(val);
        return val;
    }

    // plot the data using marker
    public void plotMarker(LatLng value)
    {
        mMap.addMarker(new MarkerOptions().position(value).title("Default"));
        mMap.moveCamera(CameraUpdateFactory.newLatLngZoom(value, 12.5f));
    }

    // plot construction on maps
    public void  construction() throws IOException{
        // Get input stream and Buffered Reader for data file

        try
        {
            InputStream is = getAssets().open("liveconstruction.csv");
            BufferedReader reader = new BufferedReader( new InputStreamReader(is));
            String line;

            // Read each line
            while ((line = reader.readLine()) != null){
                // split to separate  the latitude, longitude and description values
                String[] rowData = line.split(",");

                // create a Data object for this row's data
                Data data = new Data();
                String l1 = rowData[5];
                String l2 = l1.replace("\"","");
                double l3 = Double.parseDouble(l2);
                data.setLat( l3);

                l1 = rowData[6];
                l2 = l1.replace("\"","");
                l3 = Double.parseDouble(l2);
                data.setLon( l3);

                l1 = rowData[7];
                l2 = l1.replace("\"", "");
                data.setDescription( l2);

                LatLng val = new LatLng(data.getLat(), data.getLon());
                // change marker image
                MarkerOptions options = new MarkerOptions().position(val).icon(BitmapDescriptorFactory.fromResource(R.drawable.construction));
                Marker marker= mMap.addMarker(options);
                marker.setTitle(data.getDescription());
               // mMap.addMarker(new MarkerOptions().position(val).title("Default"));
            }
        }catch (IOException e)
        {
            e.printStackTrace();
        }
    }           // end of method

    // plot alerts on maps
    public void  alert() throws IOException{
        // Get input stream and Buffered Reader for data file

        try
        {
            InputStream is = getAssets().open("livewazealertstemp2.csv");
            BufferedReader reader = new BufferedReader( new InputStreamReader(is));
            String line;

            // Read each line
            while ((line = reader.readLine()) != null){
                // split to separate  the latitude, longitude and description values
                String[] rowData = line.split(";");

                // create a Data object for this row's data
                Data data = new Data();
                String l1 = rowData[6];
                String l2 = l1.replace("\"","");
                double l3 = Double.parseDouble(l2);
                data.setLat( l3);

                l1 = rowData[7];
                l2 = l1.replace("\"","");
                l3 = Double.parseDouble(l2);
                data.setLon( l3);

                l1 = rowData[3];
                l2 = l1.replace("\"", "");
                data.setDescription( l2);

                LatLng val = new LatLng(data.getLat(), data.getLon());
                // change marker image
                MarkerOptions options = new MarkerOptions().position(val).icon(BitmapDescriptorFactory.fromResource(R.drawable.binoculars));
                Marker marker= mMap.addMarker(options);
                marker.setTitle(data.getDescription());
                // mMap.addMarker(new MarkerOptions().position(val).title("Default"));
            }
        }catch (IOException e)
        {
            e.printStackTrace();
        }
    }           // end of method

    // plot the parking
    // plot incident on maps
    public void  parking() throws IOException{
        // Get input stream and Buffered Reader for data file

        try
        {
            InputStream is = getAssets().open("parkinglots.csv");
            BufferedReader reader = new BufferedReader( new InputStreamReader(is));
            String line;

            // Read each line
            while ((line = reader.readLine()) != null){
                // split to separate  the latitude, longitude and description values
                String[] rowData = line.split(";");

                // create a Data object for this row's data
                Data data = new Data();
                String l1 = rowData[0];
                String l2 = l1.replace("\"","");
                double l3 = Double.parseDouble(l2);
                data.setLat( l3);

                l1 = rowData[1];
                l2 = l1.replace("\"","");
                l3 = Double.parseDouble(l2);
                data.setLon( l3);

                l1 = rowData[2];
                l2 = l1.replace("\"", "");
                data.setDescription( l2);

                LatLng val = new LatLng(data.getLat(), data.getLon());
                // change marker image
                MarkerOptions options = new MarkerOptions().position(val).icon(BitmapDescriptorFactory.fromResource(R.drawable.parkinggarage));
                Marker marker= mMap.addMarker(options);
                marker.setTitle(data.getDescription());
            }
        }catch (IOException e)
        {
            e.printStackTrace();
        }
    }           // end of method

    // plot incident on maps
    public void  incident() throws IOException{
        // Get input stream and Buffered Reader for data file

        try
        {
            InputStream is = getAssets().open("liveaccidents.csv");
            BufferedReader reader = new BufferedReader( new InputStreamReader(is));
            String line;

            // Read each line
            while ((line = reader.readLine()) != null){
                // split to separate  the latitude, longitude and description values
                String[] rowData = line.split(",\"");

                // create a Data object for this row's data
                Data data = new Data();
                String l1 = rowData[5];
                String l2 = l1.replace("\"","");
                double l3 = Double.parseDouble(l2);
                data.setLat( l3);

                l1 = rowData[6];
                l2 = l1.replace("\"","");
                l3 = Double.parseDouble(l2);
                data.setLon( l3);

                l1 = rowData[7];
                l2 = l1.replace("\"", "");
                data.setDescription( l2);

                LatLng val = new LatLng(data.getLat(), data.getLon());
                // change marker image
                MarkerOptions options = new MarkerOptions().position(val).icon(BitmapDescriptorFactory.fromResource(R.drawable.caution));
                Marker marker= mMap.addMarker(options);
                marker.setTitle(data.getDescription());
            }
        }catch (IOException e)
        {
            e.printStackTrace();
        }
    }           // end of method

    // plot special events on maps
    public void  events() throws IOException{
        // Get input stream and Buffered Reader for data file

        try
        {
            InputStream is = getAssets().open("liveevents.csv");
            BufferedReader reader = new BufferedReader( new InputStreamReader(is));
            String line;

            // Read each line
            while ((line = reader.readLine()) != null){
                // split to separate  the latitude, longitude and description values
                String[] rowData = line.split(",");

                // create a Data object for this row's data
                Data data = new Data();
                String l1 = rowData[5];
                String l2 = l1.replace("\"","");
                double l3 = Double.parseDouble(l2);
                data.setLat( l3);

                l1 = rowData[6];
                l2 = l1.replace("\"", "");
                l3 = Double.parseDouble(l2);
                data.setLon( l3);

                l1 = rowData[7];
                l2 = l1.replace("\"", "");
                data.setDescription(l2);

                LatLng val = new LatLng(data.getLat(), data.getLon());
                // change marker image
                MarkerOptions options = new MarkerOptions().position(val).icon(BitmapDescriptorFactory.fromResource(R.drawable.specialevents));
                Marker marker= mMap.addMarker(options);
                marker.setTitle(data.getDescription());

            }
        }catch (IOException e)
        {
            e.printStackTrace();
        }
    }           // end of method

    // plot restaurant
    public void  restaurant() throws IOException{
        // Get input stream and Buffered Reader for data file

        try
        {
            InputStream is = getAssets().open("restaurants.csv");
            BufferedReader reader = new BufferedReader( new InputStreamReader(is));
            String line;

            // Read each line
            while ((line = reader.readLine()) != null){
                // split to separate  the latitude, longitude and description values
                String[] rowData = line.split(";");

                // create a Data object for this row's data
                Data data = new Data();
                String l1 = rowData[0];
                String l2 = l1.replace("\"","");
                double l3 = Double.parseDouble(l2);
                data.setLat( l3);

                l1 = rowData[1];
                l2 = l1.replace("\"", "");
                l3 = Double.parseDouble(l2);
                data.setLon( l3);

                l1 = rowData[2];
                l2 = l1.replace("\"", "");
                data.setDescription(l2);

                LatLng val = new LatLng(data.getLat(), data.getLon());
                // change marker image
                MarkerOptions options = new MarkerOptions().position(val).icon(BitmapDescriptorFactory.fromResource(R.drawable.restaurant));
                Marker marker= mMap.addMarker(options);
                marker.setTitle(data.getDescription());

            }
        }catch (IOException e)
        {
            e.printStackTrace();
        }
    }           // end of method

    //plot police
    public void  police() throws IOException{
        // Get input stream and Buffered Reader for data file

        try
        {
            InputStream is = getAssets().open("livewazealertstemp.csv");
            BufferedReader reader = new BufferedReader( new InputStreamReader(is));
            String line;

            // Read each line
            while ((line = reader.readLine()) != null){
                // split to separate  the latitude, longitude and description values
                String[] rowData = line.split(";");

                // create a Data object for this row's data
                Data data = new Data();
                String l1 = rowData[6];
                String l2 = l1.replace("\"","");
                double l3 = Double.parseDouble(l2);
                data.setLat( l3);

                l1 = rowData[7];
                l2 = l1.replace("\"", "");
                l3 = Double.parseDouble(l2);
                data.setLon( l3);

                //l1 = rowData[2];
                //l2 = l1.replace("\"", "");
                data.setDescription("Police");

                LatLng val = new LatLng(data.getLat(), data.getLon());
                // change marker image
                MarkerOptions options = new MarkerOptions().position(val).icon(BitmapDescriptorFactory.fromResource(R.drawable.police));
                Marker marker= mMap.addMarker(options);
                marker.setTitle(data.getDescription());

            }
        }catch (IOException e)
        {
            e.printStackTrace();
        }
    }           // end of method


    public void  plotParking() throws IOException, JSONException {


        // get the lat and long values
        LatLng l = geoLocate(getStringLocation());

        // Call process doInBackground and it will return a list of parking name and LatLng values

        PlotParking pk = new PlotParking(this);
        pk.execute(l);
        da = pk.plotData;

        // iterate over the list and give the LatLng values with description to google maps marker
        for (int i = 0; i < da.size(); i++)
        {
            daNew = da.get(i);
            la = daNew.getLat();
            lo = daNew.getLon();
            de = daNew.getDescription();
            // create la lo values and pass it to marker
            ln = new LatLng(la, lo);
            mMap.addMarker(new MarkerOptions().position(ln).title(de));
        }
    }

    }       // end of class


