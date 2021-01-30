<?php 
declare(strict_types=1);
require_once('./configuration/Configuration.php');
require_once('./configuration/DataPoints.php');
use PHPUnit\Framework\TestCase;

final class DataPointsTest extends TestCase
{
    private $dataPoints;
    

    public function testCompanyExists(): void
    {
        $dataPoints = new DataPoints();
        $this->assertEquals(2, $dataPoints->companyExists(2));
    }

    public function testProcessWebhook(): void
    {
        $dataPoints = new DataPoints();

        $sensorMessages = json_decode('[
                            {
                                "sensorID":"528876","sensorName":"2 | Test Company | Thermocouple | 528876","applicationID":"86","networkID":"3","dataMessageGUID":"9b65ec35-d972-4827-8c3d-fb3a1e04ce3f","state":"0","messageDate":"2020-11-20 14:30:54","rawData":"23.6","dataType":"TemperatureData","dataValue":"23.6","plotValues":"74.48","plotLabels":"Fahrenheit","batteryLevel":"97","signalStrength":"100","pendingChange":"True","voltage":"2.49"
                            }
                            ,{
                                "sensorID":"528879" , "sensorName":"2 | Test Company | 0-20mA Current | 528879", "applicationID":"22", "networkID":"3", "dataMessageGUID":"72feb5dc-08a9-4b65-8beb-278311e0499b", "state": "2", "messageDate": "2020-09-06 03:37:14", "rawData":"0", "dataType": "MilliAmps", "dataValue": "0", "plotValues": "0", "plotLabels": "mA", "batteryLevel": "0", "signalStrength": "0", "pendingChange": "False", "voltage": "2.15"
                            }
                            ,{
                                "sensorID":"528889" , "sensorName":"2 | Test Company | Current Meter 20 Amp | 528889", "applicationID":"93", "networkID":"3", "dataMessageGUID":"fa8bebb7-6e48-4a08-afb8-9846b926585d", "state": "0", "messageDate": "2020-10-14 22:18:03", "rawData":"0.05%2c0.16%2c0.17%2c0.16", "dataType": "AmpHours|Amps|Amps|Amps", "dataValue": "0.05|0.16|0.17|0.16", "plotValues": "0.05|0.16|0.17|0.16", "plotLabels": "Amp Hours|AvgCurrent|MaxCurrent|MinCurrent", "batteryLevel": "100", "signalStrength": "0", "pendingChange": "False", "voltage": "2.94"
                            }
                            ,{
                                "sensorID":"528911","sensorName":"2 - Test Company - Temperature - 528911","applicationID":"2","networkID":"3","dataMessageGUID":"51d54ec3-c19e-4e06-803c-9f4776948efd","state":"17","messageDate":"2020-11-19 20:50:43","rawData":"24.7","dataType":"TemperatureData","dataValue":"24.7","plotValues":"76.46","plotLabels":"Fahrenheit","batteryLevel":"100","signalStrength":"94","pendingChange":"True","voltage":"3.15"
                            }
                            ,{
                                "sensorID":"528888","sensorName":"13 | Test Company | Current Meter 20 Amp | 528888","applicationID":"93","networkID":"3","dataMessageGUID":"8b7fc2d6-2364-48d1-97c4-d7ab801c3baf","state": "1","messageDate": "2020-12-02 16:10:25","rawData":"0%2c0%2c0%2c0","dataType": "AmpHours|Amps|Amps|Amps","dataValue": "0|0|0|0","plotValues": "0.3|0.4|0.2|0.1","plotLabels": "Amp Hours|AvgCurrent|MaxCurrent|MinCurrent","batteryLevel": "100","signalStrength": "96","pendingChange": "True","voltage": "3.03"
                            }
                        ]', true);

        $this->assertEquals(true, $dataPoints->processWebhook($sensorMessages));
    }

    public function testInsertDataPoint(): void
    {
        $dataPoints = new DataPoints();
        
        $sensor = json_decode('{
                "sensorID":"111111","sensorName":"2 | Test Company | Temperature | 111111","applicationID":"2","networkID":"3","dataMessageGUID":"51d54ec3-c19e-4e06-803c-9f4776948efd","state":"17","messageDate":"2020-11-19 20:50:43","rawData":"24.7","dataType":"TemperatureData","dataValue":"24.7","plotValues":"76.46","plotLabels":"Fahrenheit","batteryLevel":"100","signalStrength":"94","pendingChange":"True","voltage":"3.15"
        }', true);

        $this->assertEquals(
            true,
            $dataPoints->insertDataPoint(2, $sensor)
        );
        
        $sensor = json_decode('{
                "sensorID":"222222" , "sensorName":"2 | Test Company | Current Meter 20 Amp | 222222", "applicationID":"93", "networkID":"3", "dataMessageGUID":"fa8bebb7-6e48-4a08-afb8-9846b926585d", "state": "0", "messageDate": "2020-10-14 22:18:03", "rawData":"0.05%2c0.16%2c0.17%2c0.16", "dataType": "AmpHours|Amps|Amps|Amps", "dataValue": "0.05|0.16|0.17|0.16", "plotValues": "0.05|0.16|0.17|0.16", "plotLabels": "Amp Hours|AvgCurrent|MaxCurrent|MinCurrent", "batteryLevel": "100", "signalStrength": "0", "pendingChange": "False", "voltage": "2.94"
            }', true);

        $this->assertEquals(
            true,
            $dataPoints->insertDataPoint(2, $sensor)
        );
        
    }

    
    
    /*
    public function testCannotBeCreatedFromInvalidEmailAddress(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Email::fromString('invalid');
    }

    public function testCanBeUsedAsString(): void
    {
        $this->assertEquals(
            'user@example.com',
            Email::fromString('user@example.com')
        );
    }
    */
}
?>