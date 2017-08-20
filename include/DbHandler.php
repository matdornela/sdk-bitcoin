<?php
class DbHandler{
    private $conn;
    private $ch;

    function __construct() {
        require_once dirname(__FILE__) . '/DbConnect.php';
        // opening db connection
        $db = new DbConnect();
        $this->conn = $db->connect();
    }

    private function getTickerApi(){
    // init curl object    
        $ch = curl_init();

    // define options
        $optArray = array(
            CURLOPT_URL => 'https://www.mercadobitcoin.net/api/v2/ticker/',
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT => 60

            );
    // apply those options
        curl_setopt_array($ch, $optArray);

    // execute request and get response
        $response = curl_exec($ch);
        return json_decode($response, true);

    }

    public function insertTicker(){

        $jsonArray = $this->getTickerApi();

        var_dump($jsonArray);

        foreach ($jsonArray as $value) {
            $high = $value['high'];
            $low = $value['low'];
            $last = $value['last'];
            $buy = $value["buy"];
            $sell = $value['sell'];
            $date = $value['date'];
            $vol = $value['vol'];
        }
        $dateF = gmdate("Y-m-d H:i", $date);

        $sql = "INSERT INTO `ticker_history`
        (`ticker_history_high`, 
        `ticker_history_low`, 
        `ticker_history_vol`, 
        `ticker_history_last`, 
        `ticker_history_buy`, 
        `ticker_history_sell`,
        `ticker_history_date`)
        VALUES 
        (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("dddddss", $high,$low,$vol,$last,$buy,$sell,$dateF);
        $result = $stmt->execute();
        $stmt->close();

        if ($result) {
         echo "<br /> Ticker has been sucessfully inserted\n";

     } else{
         echo "<br /> Unable to insert this data\n";
     }
 }


 public function getTickerByDate($date){

    $query = "SELECT *, DATE_FORMAT(`ticker_history_date`, '%Y-%m-%d %H:%i') AS `ticker_history_date_formatted` FROM `ticker_history` WHERE `ticker_history_date` =?";

    $stmt = $this->conn->prepare($query);
    $dateTime = $date . ":00";

    /* bind parameters for markers */
    $stmt->bind_param("s", $dateTime);

    /* execute query */
    $stmt->execute();

    $tickerArray = array();
    /* bind result variables */
    $stmt->bind_result($ticker_history_id, $ticker_history_high, $ticker_history_low, $ticker_history_vol,
     $ticker_history_last, $ticker_history_buy, $ticker_history_sell,$ticker_history_date,$ticker_history_datetime);

    $i = 0;
    while ($stmt->fetch()) {       
        $tickerArray[$i]["ticker_history_id"] = $ticker_history_id;
        $tickerArray[$i]["ticker_history_high"] = $ticker_history_high;
        $tickerArray[$i]["ticker_history_low"] = $ticker_history_low;
        $tickerArray[$i]["ticker_history_vol"] = $ticker_history_vol;
        $tickerArray[$i]["ticker_history_last"] = $ticker_history_last;
        $tickerArray[$i]["ticker_history_buy"] = $ticker_history_buy;
        $tickerArray[$i]["ticker_history_sell"] = $ticker_history_sell;
        $tickerArray[$i]["ticker_history_date_formatted"] = $ticker_history_datetime;
        $i++;
    }
    $stmt->close();
    return json_encode($tickerArray);
}


public function getTickerByPeriodEveryHour($date1, $date2){
    $status = true;
    do{
        $ticker = $this->getTickerByPeriod($date1, $date2);
        print_r($ticker);
        sleep(3600);

    }while($status == true);
}

private function getTickerByPeriod($date1,$date2){

    $this->insertTicker();

    $query = "SELECT *,DATE_FORMAT(`ticker_history_date`, '%Y-%m-%d %H:%i') AS `ticker_history_date_formatted` FROM `ticker_history` 
    WHERE STR_TO_DATE(`ticker_history_date`,'%Y-%m-%d') >= ? 
    AND STR_TO_DATE(`ticker_history_date`,'%Y-%m-%d') <= ?";

    $stmt = $this->conn->prepare($query);

    /* bind parameters for markers */
    $stmt->bind_param("ss", $date1, $date2);
    /* execute query */
    $stmt->execute();

    $tickerArray = array();
    /* bind result variables */
    $stmt->bind_result($ticker_history_id, $ticker_history_high, $ticker_history_low, $ticker_history_vol,
     $ticker_history_last, $ticker_history_buy, $ticker_history_sell,$ticker_history_date, $ticker_history_date_formatted);

    $i = 0;
    while ($stmt->fetch()) {       
        $tickerArray[$i]["ticker_history_id"] = $ticker_history_id;
        $tickerArray[$i]["ticker_history_high"] = $ticker_history_high;
        $tickerArray[$i]["ticker_history_low"] = $ticker_history_low;
        $tickerArray[$i]["ticker_history_vol"] = $ticker_history_vol;
        $tickerArray[$i]["ticker_history_last"] = $ticker_history_last;
        $tickerArray[$i]["ticker_history_buy"] = $ticker_history_buy;
        $tickerArray[$i]["ticker_history_sell"] = $ticker_history_sell;
        $tickerArray[$i]["ticker_history_date_formatted"] = $ticker_history_date_formatted;
        $i++;
    }
    $stmt->close();

    return json_encode($tickerArray);


}
}

?>