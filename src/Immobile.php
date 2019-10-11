<?php
class Immobile extends Main{


    /**
     * Function list all immobile
     */
    public function list(){
        try {
            $sql = "SELECT * FROM immobile";
            $res = $this->conn->query($sql);
            if($res->num_rows > 0 ){
                $all = $res->fetch_all(MYSQLI_ASSOC);
                return $all;
            }
        } catch (\Throwable $th) {
             return $e->getMessage();
        }        
    }

    /**
     * Function for add new.
     */
    public function add(){
        try {
            $data = $_POST['data'];
            $price = $this->priceSaleByWake($data['sizeHome'], $data['bedrooms'], $data['suites'], $data['wc'], $data['sizeRecreation'], $data['pool'], $data['garagem']);
            $sql = "INSERT INTO immobile(title_ad, size_m2_home, bedrooms, suites, wc, pool_home, garage, size_m2_recreation, sale_price, id_user, address_home, img_base64 ) VALUES ('{$data['titleAd']}', {$data['sizeHome']}, {$data['bedrooms']}, {$data['suites']}, {$data['wc']},{$data['pool']}, {$data['garagem']}, {$data['sizeRecreation']},$price,1,'{$data['address_h']}','{$data['base64Img']}')";
            $res = $this->conn->query($sql);
            if( $res == true){
                return $res;
            }else{
                return $this->conn->error;
            } 

        } catch (\Throwable $th) {
             return $e->getMessage();
        } 
    }
    /**
     *  Function return price for immobile with base Linear Regression - Weka.
     * 
     *  Classifier model (full training set)
     *  Linear Regression Model
     *
     *  precoVenda =
     *
     *   231.7489 * tamanhoCasa +
     *   198584.2266 * quartos +
     *   -8011.9209 * suites +
     *   158646.3995 * banheiros +
     *   4846.0335 * areaLazer +
     *   146174.6425 * piscina +
     *   68896.2327 * garagem +
     *   -766580.1618
     */
    public function priceSaleByWake($sizeHome, $bedrooms, $suites, $wc, $sizeRecreation, $pool, $garage){
        $calc = ( 231.7489 * $sizeHome ) + ( 198584.2266 * $bedrooms ) + ( -8011.9209 * $suites ) + ( 158646.3995 * $wc ) + ( 4846.0335 * $sizeRecreation ) + ( 146174.6425 * $pool ) + ( 68896.2327 * $garage ) -766580.1618;
        $round = number_format( round($calc), 3,'.','.'); 
        return str_replace('.000', '', $round);        
    }
   
    
}
