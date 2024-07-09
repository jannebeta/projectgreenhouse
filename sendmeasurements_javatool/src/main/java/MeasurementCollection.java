import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;

public class MeasurementCollection {

    public static IndoorMeasurement GetInhouseValues() throws IOException, InterruptedException {

        String line;
        String[] data;
        double humidity=0;
        double temperature=0;

        Runtime rt= Runtime.getRuntime();
        Process p=rt.exec("python3 /home/valvonta/DHT11.py");
        BufferedReader bri = new BufferedReader(new InputStreamReader(p.getInputStream()));
        if((line = bri.readLine()) != null){
            if(!(line.contains("SENSOR_ERROR"))){

                data=line.split(";");
                temperature=Double.parseDouble(data[0]);
                humidity=Double.parseDouble(data[1]);
                return new IndoorMeasurement(temperature, humidity);
            }
            else
        System.out.println("Data Error");
    }
        bri.close();
        p.waitFor();
        return null;
}
    public static OutdoorMeasurement GetOutdoorValues() throws IOException, InterruptedException {
        String line;
        String[] data;
        double temperature=0;
        double pressure=0;

        Runtime rt= Runtime.getRuntime();
        Process p=rt.exec("python3 /home/valvonta/Pimoroni/bmp280/Outdoor.py");
        BufferedReader bri = new BufferedReader(new InputStreamReader(p.getInputStream()));
        if((line = bri.readLine()) != null){


                data=line.split(";");
                temperature=Double.parseDouble(data[0]);
                pressure=Double.parseDouble(data[1]);
                return new OutdoorMeasurement(temperature, pressure);

        }
        bri.close();
        p.waitFor();
        return null;
    }
}
