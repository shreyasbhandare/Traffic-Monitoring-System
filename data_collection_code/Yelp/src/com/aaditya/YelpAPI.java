package com.aaditya;

import java.io.BufferedReader;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.PrintWriter;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.Iterator;

import org.json.JSONException;
import org.json.JSONObject;
//import org.json.simple.JSONArray;
import org.json.JSONArray;
import org.json.simple.parser.JSONParser;
import org.json.simple.parser.ParseException;
import org.scribe.builder.ServiceBuilder;
import org.scribe.model.OAuthRequest;
import org.scribe.model.Response;
import org.scribe.model.Token;
import org.scribe.model.Verb;
import org.scribe.oauth.OAuthService;

import com.beust.jcommander.JCommander;
import com.beust.jcommander.Parameter;

/**
 * Code sample for accessing the Yelp API V2.
 * 
 * This program demonstrates the capability of the Yelp API version 2.0 by using the Search API to
 * query for businesses by a search term and location, and the Business API to query additional
 * information about the top result from the search query.
 * 
 * <p>
 * See <a href="http://www.yelp.com/developers/documentation">Yelp Documentation</a> for more info.
 * 
 */
public class YelpAPI {

  private static final String API_HOST = "api.yelp.com";
  private static final String DEFAULT_TERM = "dinner";
  private static final String DEFAULT_LOCATION = "San Francisco, CA";
  private static final int SEARCH_LIMIT = 20;
  private static final String SEARCH_PATH = "/v2/search";
  private static final String BUSINESS_PATH = "/v2/business";

  /*
   * Update OAuth credentials below from the Yelp Developers API site:
   * http://www.yelp.com/developers/getting_started/api_access
   */
  private static final String CONSUMER_KEY = "nqsaiwugmUZ52lB6PdTLJA";
  private static final String CONSUMER_SECRET = "ZSHFdnV157-pyepdStLFx0mHtUU";
  private static final String TOKEN = "6y3tN4pmrGJlbZJl06sbi91WYwUKSpkL";
  private static final String TOKEN_SECRET = "XNbTAB_VFpnt0ZLhaaCBOzyUPBc";

  OAuthService service;
  Token accessToken;

  /**
   * Setup the Yelp API OAuth credentials.
   * 
   * @param consumerKey Consumer key
   * @param consumerSecret Consumer secret
   * @param token Token
   * @param tokenSecret Token secret
   */
  public YelpAPI() {
    
	this.service = new ServiceBuilder().provider(TwoStepOAuth.class).apiKey(this.CONSUMER_KEY).apiSecret(this.CONSUMER_SECRET).build();
    this.accessToken = new Token(this.TOKEN, this.TOKEN_SECRET);
    
  }

  /**
   * Creates and sends a request to the Search API by term and location.
   * <p>
   * See <a href="http://www.yelp.com/developers/documentation/v2/search_api">Yelp Search API V2</a>
   * for more info.
   * 
   * @param term <tt>String</tt> of the search term to be queried
   * @param location <tt>String</tt> of the location
   * @return <tt>String</tt> JSON Response
   */
  public String searchForBusinessesByLocation(String term, String location) {
    OAuthRequest request = createOAuthRequest(SEARCH_PATH);
    request.addQuerystringParameter("term", term);
    request.addQuerystringParameter("location", location);
    request.addQuerystringParameter("limit", String.valueOf(SEARCH_LIMIT));
    return sendRequestAndGetResponse(request);
  }

  /**
   * Creates and sends a request to the Business API by business ID.
   * <p>
   * See <a href="http://www.yelp.com/developers/documentation/v2/business">Yelp Business API V2</a>
   * for more info.
   * 
   * @param businessID <tt>String</tt> business ID of the requested business
   * @return <tt>String</tt> JSON Response
   */
  public String searchByBusinessId(String businessID) {
    OAuthRequest request = createOAuthRequest(BUSINESS_PATH + "/" + businessID);
    return sendRequestAndGetResponse(request);
  }

  /**
   * Creates and returns an {@link OAuthRequest} based on the API endpoint specified.
   * 
   * @param path API endpoint to be queried
   * @return <tt>OAuthRequest</tt>
   */
  private OAuthRequest createOAuthRequest(String path) {
    OAuthRequest request = new OAuthRequest(Verb.GET, "https://" + API_HOST + path);
    return request;
  }

  /**
   * Sends an {@link OAuthRequest} and returns the {@link Response} body.
   * 
   * @param request {@link OAuthRequest} corresponding to the API request
   * @return <tt>String</tt> body of API response
   */
  private String sendRequestAndGetResponse(OAuthRequest request) {
    System.out.println("Querying " + request.getCompleteUrl() + " ...");
    this.service.signRequest(this.accessToken, request);
    Response response = request.send();
    return response.getBody();
  }
  
  public void getYelpData() throws IOException, ClassNotFoundException, SQLException
  {
	  YelpAPICLI yelpApiCli = new YelpAPICLI();
	    //new JCommander(yelpApiCli, args);
		// Here we define the parameters that will be extracted from the JSON file
		String name = null;
		double latitude = 0.0;
		double longitude = 0.0;
		
		// Database Parameters
		Class.forName("com.mysql.jdbc.Driver");							//Step 1: Load JDBC Driver for MySQL Database
		String dburl = "jdbc:mysql://localhost:3306/trafficprediction?";	//Step 2: Establish Connection
		String username = "kush";	
		String password = "password";
		String dbname = "restaurants";
					
		Connection conn = DriverManager.getConnection(dburl,username,password);
					
		Statement statement = conn.createStatement();			// create our java jdbc statement
		String insertcommand = null;
		
		conn.setAutoCommit(false);
				
	  	String response = this.searchForBusinessesByLocation("restaurant", "new york");
		
	    PrintWriter out = new PrintWriter("C:\\Users\\pateku1\\workspace\\TrafficForecaster\\YelpDataNJ.json");
		out.println(response);
		out.close();
	    System.out.println(response);

	    String jsonString = response.toString();
	    
	    	JSONObject jsonOject;
			try {
				jsonOject = new JSONObject(jsonString);
		    	JSONObject json2 = (JSONObject) jsonOject.get("region");
		    	JSONObject json3 = (JSONObject) json2.get("center");
		    	JSONArray json4 = (JSONArray) jsonOject.getJSONArray("businesses");
		    	
		    	for (int i = 0; i < 20; i++)
		    	{
		    	name = (String) json4.getJSONObject(i).getString("name");
		    	
		    	JSONObject arr = json4.getJSONObject(i).getJSONObject("location").getJSONObject("coordinate");
		    	
		    	latitude = arr.getDouble("latitude");
		    	longitude = arr.getDouble("longitude");
		    	
		    	insertcommand = "INSERT INTO `restaurants`(`Latitude`, `Longitude`, `Name`) VALUES (" + latitude + "," + longitude + ",\"" + name + "\")";
		    	
		    	//System.out.println(arr.getDouble("latitude"));
		    	//System.out.println(arr.getDouble("longitude"));
		    	//System.out.println(name);
		    	statement.addBatch(insertcommand);
		    	}
		    	
		    	statement.executeBatch();
		    	conn.commit();
	            conn.close();
		    	
			} catch (JSONException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}

  }

  /**
   * Queries the Search API based on the command line arguments and takes the first result to query
   * the Business API.
   * 
   * @param yelpApi <tt>YelpAPI</tt> service instance
   * @param yelpApiCli <tt>YelpAPICLI</tt> command line arguments
 * @throws JSONException 
   */
  private static void queryAPI(YelpAPI yelpApi, YelpAPICLI yelpApiCli) throws JSONException {
    String searchResponseJSON =
        yelpApi.searchForBusinessesByLocation(yelpApiCli.term, yelpApiCli.location);

    JSONParser parser = new JSONParser();
    JSONObject response = null;
    try {
      response = (JSONObject) parser.parse(searchResponseJSON);
    } catch (ParseException pe) {
      System.out.println("Error: could not parse JSON response:");
      System.out.println(searchResponseJSON);
      System.exit(1);
    }

    JSONArray businesses = (JSONArray) response.get("businesses");
    JSONObject firstBusiness = (JSONObject) businesses.get(0);
    String firstBusinessID = firstBusiness.get("id").toString();
   // System.out.println(String.format(
       // "%s businesses found, querying business info for the top result \"%s\" ...",
      //  businesses.size(), firstBusinessID));

    // Select the first business and display business details
    String businessResponseJSON = yelpApi.searchByBusinessId(firstBusinessID.toString());
    System.out.println(String.format("Result for business \"%s\" found:", firstBusinessID));
    System.out.println(businessResponseJSON);
  }

  /**
   * Command-line interface for the sample Yelp API runner.
   */
  private static class YelpAPICLI {
    @Parameter(names = {"-q", "--term"}, description = "Search Query Term")
    public String term = DEFAULT_TERM;

    @Parameter(names = {"-l", "--location"}, description = "Location to be Queried")
    public String location = DEFAULT_LOCATION;
  }

  /**
   * Main entry for sample Yelp API requests.
   * <p>
   * After entering your OAuth credentials, execute <tt><b>run.sh</b></tt> to run this example.
 * @throws FileNotFoundException 
 * @throws ParseException 
   */
  
}