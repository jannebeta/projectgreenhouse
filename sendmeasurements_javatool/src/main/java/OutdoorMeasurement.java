public class OutdoorMeasurement {
    private double Temperature;
    private double Pressure;

    public OutdoorMeasurement(double mTemperature, double mPressure) {
        this.Temperature = mTemperature;
        this.Pressure = mPressure;
    }

    public double getTemperature() {
        return Temperature;
    }

    public double getPressure() {
        return Pressure;
    }
}
