import java.io.*;
import java.net.HttpURLConnection;
import java.net.URI;
import java.net.URL;
import java.net.http.HttpClient;
import java.net.http.HttpRequest;
import java.net.http.HttpResponse;
import java.time.Instant;
import java.util.Properties;


public class Greenhouse {

    public static IndoorMeasurement indoor;
    public static OutdoorMeasurement outdoor;
    public static String cSecretKey = "";
    public static String cApiPath = "";
    public static int cDebugMode = 0;

    public static void main(String[] args){

        try (InputStream input = new FileInputStream("config.properties")) {

            Properties prop = new Properties();

            prop.load(input);


           cSecretKey = prop.getProperty("secretKey");
           cApiPath = prop.getProperty("api.path");
           cDebugMode = Integer.parseInt(prop.getProperty("debug.mode"));

        } catch (IOException ex) {
            ex.printStackTrace();
        }

        System.out.println("Project Greenhouse 2024" + ((cDebugMode == 1 ? " [DEBUG]" : "" )));


        try {
            indoor = MeasurementCollection.GetInhouseValues();
            outdoor = MeasurementCollection.GetOutdoorValues();
        } catch (IOException e) {
            e.printStackTrace();
        } catch (InterruptedException e) {
            e.printStackTrace();
        }
        try
        {
             sendMeasurement(cSecretKey, Instant.now().getEpochSecond(), indoor.getTemperature(), indoor.getHumidity(), outdoor.getTemperature(), outdoor.getPressure());
        }
        catch(Exception e)
        {
            System.out.println("Something went wrong. Are you running that on Raspberry Pi?");
        }
    }
    public static void sendMeasurement(String secretKey, long mTimestamp, double mTemperatureInhouse, double mHumidityInhouse, double mTemperatureOutdoor, double mPressureOutdoor) {

        String payload = "{\"secretKey\": \"" + secretKey + "\", \"timestamp\": \"" + mTimestamp + "\", \"temperature_inhouse\": \"" + mTemperatureInhouse + "\", \"humidity_inhouse\": \"" + mHumidityInhouse + "\", \"temperature_outdoor\": \"" + mTemperatureOutdoor + "\", \"pressure_outdoor\": \"" + mPressureOutdoor + "\"}";
        HttpClient client = HttpClient.newHttpClient();

        HttpRequest request = HttpRequest.newBuilder(URI.create(cApiPath))
                .header("content-type", "application/json")
                .POST(HttpRequest.BodyPublishers.ofString(payload))
                .build();

        try {
            HttpResponse<String> response = client.send(request, HttpResponse.BodyHandlers.ofString());
            System.out.println(response.body());
        } catch (IOException e) {
            e.printStackTrace();
        } catch (InterruptedException e) {
            e.printStackTrace();
        }

    }
  /*  public static void sendMeasurement(String secretKey, long mTimestamp, double mTemperatureInhouse, int mHumidityInhouse, double mTemperatureOutdoor, int mPressureOutdoor){
        try{
            URL url = new URL("http://localhost/project_greenhouse/upload_measurement.php");
            HttpURLConnection connection = (HttpURLConnection) url.openConnection();
            connection.setRequestMethod("POST");
            connection.setDoOutput(true);
            connection.setRequestProperty("Content-Type","application/json");
            connection.setRequestProperty("Accept", "application/json");
            String payload = "{\"secretKey\":\"" + secretKey + "\",\"temperatureInhouse\": \"" + mTemperatureInhouse + "\", \"humidityInhouse\": \"" + mHumidityInhouse + "\", \"temperatureOutdoor\": \"" + mTemperatureOutdoor + "\", \"pressureOutdoor\": \"" + mPressureOutdoor + "\"}";
            byte[] out = payload.getBytes(StandardCharsets.UTF_8);
            OutputStream stream = connection.getOutputStream();
            InputStream iStream = connection.getInputStream();
            stream.write(out);
         //   System.out.println(connection.getResponseCode() + " " + connection.getInputStream().toString()); // THis is optional
        if (connection.getResponseCode() == 200)
        {
            BufferedReader in = new BufferedReader(
                    new InputStreamReader(
                            iStream));

            StringBuilder response = new StringBuilder();
            String currentLine;

            while ((currentLine = in.readLine()) != null)
                response.append(currentLine);

            in.close();

            System.out.println(response.toString());
            System.out.println("Tiedot lisättiin järjestelmään onnistuneesti.");
        }
        else
        {
            System.out.println("Jokin meni pieleen.");
        }
            connection.disconnect();
        }catch (Exception e){
            System.out.println(e);
            System.out.println("Failed successfully");
        }
    }'*/
}
