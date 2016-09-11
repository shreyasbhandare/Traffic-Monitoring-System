package com.trafficprediction.aaditya.trafficpredictionproject;

/**
 * Created by aadit on 11/29/2015.
 */
public class Data {
    private double lat;
    private double lon;
    private String  description;

    // Getters
    public double getLat()
    {
        return  lat;
    }
    public double getLon()
    {
        return  lon;
    }
    public String getDescription()
    {
        return  description;
    }

    // Setters
    public void setLat(double la)
    {
        this.lat = la;
    }
    public void setLon(double lo)
    {
        this.lon = lo;
    }
    public void setDescription(String desc) { this.description = desc;  }

}
