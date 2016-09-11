package org.datacollector.java;

import java.io.FileReader;
import java.io.IOException;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.sql.Time;
import java.util.List;


import Jama.Matrix;


public class prediction 
{
	static double alph=0.01;
	static double beta=11.1;
	static final String JDBC_DRIVER = "com.mysql.jdbc.Driver";  
	static final String DB_URL = "jdbc:mysql://localhost:3306/trafficprediction?";

	  //  Database credentials
	static final String USER = "kush";
	static final String PASS = "password";
	
	public void predict(){
		int k = 5000;
		double x[]= new double[k];
		double t[]= new double[k];
		double lat[]= new double[k];
		double lon[]= new double[k];
		Time time[]= new Time[k];
		int tp[] = new int[k];
		String c[]= new String[k];
		String day[]= new String[k];
		int a=0;
		
		
		/*********************************************/
				
		int i=0,j=0,m=0,n=0;
		Connection conn = null;
		Statement stmt = null;
		try{
		      //STEP 2: Register JDBC driver
		      Class.forName("com.mysql.jdbc.Driver");

		      //STEP 3: Open a connection
		      System.out.println("Connecting to database...");
		      conn = DriverManager.getConnection(DB_URL, USER, PASS);

		      //STEP 4: Execute a query
		      System.out.println("Connecting to database...");
		      stmt = conn.createStatement();
		      
		      //Specifying the rows taken from db table
		      
		      /////////////***********************//////////////
		      String day1="Thursday";
		      String sql = "SELECT `Street`,`StartLatitude`,`StartLongitude`,`Delay`,`Time`  FROM `livecongestion` WHERE `Day`= \"" + day1 + "\"";
		      
		      ResultSet rs = stmt.executeQuery(sql);
		      while(rs.next()){
		    	  time[n]=(rs.getTime("Time"));
		    	  tp[n]= time[n].getHours();
		    	  n++;
		      }
		      //Time time=0;
		      ResultSet rt = stmt.executeQuery(sql);
		      
		      while(rt.next()){
		    	  c[i]=rt.getString("Street");
		    	  lat[i]=rt.getDouble("StartLatitude");
		    	  lon[i]=rt.getDouble("StartLongitude");
		    	  i++;
		      }
		      
		      ResultSet rr = stmt.executeQuery(sql);
		      a=i;
		      while(j<i){
		       
		       if((tp[j]>11) && (tp[j]<13)){
			   String S1=c[j];
		       //write to db
		       String sql1="INSERT INTO predicted (`Street`, `Start Latitude`, `Start Longitude`) " + "VALUES ('"+c[j]+"','"+lat[j]+"','"+lon[j]+"')";
			   stmt.executeUpdate(sql1);
		       while(rr.next()){
		    	  String S2= c[m];
		    	  if(S1==S2){
		          //Retrieve by column name
		          x[m]  = rs.getDouble("Delay");
		          t[m] = m;
		          m++;
		        }}
		        rs.close();
		       	Matrix PX= PXT(x,k);
				double ex[][]= new double[4][1];
				ex[0][0]=a;
				ex[1][0]=Math.pow(a,2);
				ex[2][0]=Math.pow(a,3);
				ex[3][0]=Math.pow(a,4);
				ex[4][0]=Math.pow(a,5);
				Matrix PI=new Matrix(ex).transpose();
				//System.out.println(ex[0]);
				//System.out.print(t);
				double estX=PI.times(Sval(PX).times(summ(t, PX))).get(0, 0);
				System.out.println(estX);
				//write into dB
				String sql2="INSERT INTO predicted (`Delay`) " + "VALUES ('"+estX+"')";
				stmt.executeUpdate(sql2);
			       
				j++;
		      }
		     }
		      
		/**********************************************************************/
				
		}catch(SQLException se){
		      //Handle errors for JDBC
		      se.printStackTrace();
		}catch(Exception e){
		      //Handle errors for Class.forName
		      e.printStackTrace();
		}finally{
		      //finally block used to close resources
		      try{
		         if(stmt!=null)
		            stmt.close();
		      }catch(SQLException se2){
		      }// nothing we can do
		      try{
		         if(conn!=null)
		            conn.close();
		      }catch(SQLException se){
		         se.printStackTrace();
		      }//end finally try
		   }//end try
		System.out.println("Goodbye!");
		
		/***********************************************/
		
	}
	
	public static Matrix PXT(double x[], int k){
		//Matrix P=new Matrix(3,k);
		double l[][]=new double[4][k];
		for(int i=0;i<k; i++){
			l[0][i]=x[i];
			l[1][i]= (double) Math.pow(x[i], 2);
			l[2][i]= (double) Math.pow(x[i], 3);
			l[3][i]= (double) Math.pow(x[i], 4);
		}
		Matrix P=new Matrix(l);
		return P;
		
		
	}
	public static Matrix Sval(Matrix PX){
		Matrix I =Matrix.identity(4,4);
		I=I.times(alph);
		Matrix temp=new Matrix(4,4);
		for(int i=0;i<PX.getColumnDimension();i++){
			Matrix temp1=getCol(PX,i);
			temp=temp.plus(temp1.times(temp1.transpose()));
		}
		temp=temp.times(beta);
		return I.plus(temp).inverse();
		
	}
	public static Matrix getCol(Matrix X,int i){
		return X.getMatrix(0, X.getRowDimension()-1,i,i);
	}
	public static Matrix summ(double t[], Matrix PX){
		Matrix temp2=new Matrix(4,1);
		for(int i=0;i<PX.getColumnDimension();i++){
			Matrix temp3=getCol(PX,i);
			temp2=temp2.plus(temp3.times(t[i]));
			
		}
		return temp2.times(beta);
		
		
	}
	  
}

