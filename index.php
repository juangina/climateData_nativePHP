
<?php
/*
        Email:	ericrenee21@gmail.com
        Token:	gLATlIEFJwUbrGLdaDBOuncROrqnyeEj 
        
        
        STATION DETAILS
        Name	ATLANTA DEKALB PEACHTREE AIRPORT, GA US
        Network:ID	GHCND:USW00053863
        Latitude/Longitude	33.875°, -84.30222°
        Elevation	305.3 m       

         STATION DETAILS
        Name	ATLANTA HARTSFIELD INTERNATIONAL AIRPORT, GA US
        Network:ID	GHCND:USW00013874
        Latitude/Longitude	33.6301°, -84.4418°
        Elevation	307.8 m
        
        STATION DETAILS
        Name	DECATUR 1.2 NE, GA US
        Network:ID	GHCND:US1GADK0033
        Latitude/Longitude	33.7825736999512°, -84.2811660766602°
        Elevation	305.1 m

        STATION DETAILS
        Name	CLARKSTON 0.9 NE, GA US
        Network:ID	GHCND:US1GADK0008
        Latitude/Longitude	33.81834°, -84.227948°
        Elevation	311.2 m

        STATION DETAILS
        Name	TUCKER 1.3 ENE, GA US
        Network:ID	GHCND:US1GADK0001
        Latitude/Longitude	33.859122°, -84.200954°
        Elevation	325.2 m
        
        STATION DETAILS
        Name	TUCKER 0.6 NNW, GA US
        Network:ID	GHCND:US1GADK0051
        Latitude/Longitude	33.863057°, -84.225368°
        Elevation	324 m        
*/
if ($_SERVER["REQUEST_METHOD"] === "POST") {
        //Extract Date from Post
        $theYear = $_POST['annual_year_selection'];
        if($_POST['station_code_selection'] != '') {
                $station = $_POST['station_code_selection'];
        } else {
                $station = $_POST['station_code'];
        }

        if($_POST['measurement_code_selection'] != '') {
                $measurement = $_POST['measurement_code_selection'];
        } else {
                $measurement = $_POST['measurement_code'];
        }

        //Setup URL to Query Database
        $url = 'https://www.ncei.noaa.gov/access/services/data/v1';
        $query_fields = [
                'dataset' => 'global-summary-of-the-month',
                'dataTypes' => 'DP01,DP10,DSNW,EMSN,EMXP,PRCP,SNOW,EMXT,TAVG',
                'stations' => $station,
                'startDate' => $theYear.'-01-01',
                'endDate' => $theYear.'-12-31',
                'includeStationName' => 'true',
                'includeStationLocation' => 'true',
                'units' => 'metric',
                'includeAttributes' => 'true',
                'format' => 'json'
        ];
        //Send API POST request to NCEI Database
        $curl = curl_init($url . '?' . http_build_query($query_fields));     
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $rawJSONResponse = curl_exec($curl);        
        $response = json_decode($rawJSONResponse, true);        
        curl_close($curl);
}
?>

<!DOCTYPE html>
<html lang="en">
        <head>
                <style>
                        label, .currentDate {color: purple;}
                </style>
                <title>Climate Data</title>
        </head>
        <body>
                <h2 style="text-align: center;">Climate Data - NCEI</h2>
                <section class="currentDate">
                        <?php echo date("l jS \of F Y") . "<br>"; ?>
                </section>
                <form action="" method="post">
                 
                        <section class="enterStation">
                                <label for="station_code">Default Station Code: ATLANTA HARTSFIELD INTERNATIONAL AIRPORT, GA US</label>
                                <input id="station_code" type="text" name="station_code" value="USW00013874" />
                                <br />
                        </section>                 
                        <section class="enterMeasurement">
                                <label for="measurement_code">Default Measurement Code: Average Monthly Participation</label>
                                <input id="measurement_code" type="text" name="measurement_code" value="PRCP" />
                                <br />
                        </section>
                        <section class="selectYear">
                                <label for="annual_year_selection">Select Annual Year</label>
                                <select id="annual_year_selection" name="annual_year_selection">
                                        <option value="2020">2020</option>
                                        <option value="2019">2019</option>
                                        <option value="2018">2018</option>
                                        <option value="2017">2017</option>
                                        <option value="2016">2016</option>
                                        <option value="2015">2015</option>
                                        <option value="2014">2014</option>
                                        <option value="2013">2013</option>
                                        <option value="2012">2012</option>
                                        <option value="2011">2011</option>
                                        <option value="2010">2010</option>                                
                                </select>
                        </section>                        
                       <section class="selectStation">
                                <label for="station_code_selection">Select Station Location</label>
                                <select id="station_code_selection" name="station_code_selection">
                                        <option value="">No Selection</option>
                                        <option value="USW00053863">ATLANTA DEKALB PEACHTREE AIRPORT, GA US</option>
                                        <option value="USW00013874">ATLANTA HARTSFIELD INTERNATIONAL AIRPORT, GA US</option>
                                        <option value="US1GADK0033">DECATUR 1.2 NE, GA US</option>
                                        <option value="US1GADK0008">CLARKSTON 0.9 NE, GA US</option>
                                        <option value="US1GADK0001">TUCKER 1.3 ENE, GA US</option>
                                        <option value="US1GADK0051">TUCKER 0.6 NNW, GA US</option>
                                </select>
                        </section>
                        <section class="selectMeasurement">
                                <label for="measurement_code_selection">Select Station Location</label>
                                <select id="measurement_code_selection" name="measurement_code_selection">
                                        <option value="">No Selection</option>
                                        <option value="PRCP">Parcipitation</option>
                                        <option value="EMXP">Maximum Daily Parcipitation</option>
                                        <option value="TAVG">Average Temperature</option>
                                        <option value="EMXT">Maximum Daily Temperature</option>
                                </select>
                        </section>
                                                
                        <input type="submit" value="Retrieve Data">
                </form>
                <a href="index.php">Reset</a>
        <br />
        <?php
                if (!empty($response)) {
                        //var_dump($response);
                        //echo '<div>'.$response[0][$measurement].'</div>';
                        //var_dump($theYear);
                        echo '<pre>';
                        foreach($response as $level1) {
                                echo "<div>Year-Month = " . $level1['DATE'] . "     Key = ". $measurement."        Value = " . $level1[$measurement] . "</div>";
                              }
                        echo '</pre>';                        
                }
        ?>
        </body>
</html>
<?php
/*


*/
?>