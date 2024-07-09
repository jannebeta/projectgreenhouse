public class IndoorMeasurement {
    private double Temperature;
    private double Humidity;

    public IndoorMeasurement(double mTemperature, double mHumidity) {
        this.Temperature = mTemperature;
        this.Humidity = mHumidity;
    }

    public double getTemperature() {
        return Temperature;
    }

    public double getHumidity() {
        return Humidity;
    }
}
