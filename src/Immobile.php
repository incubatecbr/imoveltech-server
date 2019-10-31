<?php
use User;

class Immobile extends Main{

    public function details(){
        try {
            $id = $_POST['id'];
            $sql = "SELECT * FROM immobile WHERE id_ = $id";
            $res = $this->conn->query($sql);
            if($res->num_rows > 0 ){
                return $res->fetch_assoc();
            }else{
                return  false;
            }
            
        } catch (\Throwable $th) {
             return $th->getMessage();
        } 
    }

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
             return $th->getMessage();
        }        
    }
    /**
     * Function for return all immobiles user.
     */
    public function immobilesUser(){
        try {
            $id_ = $_POST['id'];
            $user = new User();
            $sql = "SELECT id_, title_ad from immobile WHERE id_user = $id_";
            $res = $this->conn->query($sql);
            $username = $user->getName();
            if($res->num_rows > 0){
                $all = [
                    'username' => $username['username'],
                    $res->fetch_all(MYSQLI_ASSOC),
                ];
                return $all;
            }else{
                $msg = [
                    'msg' => 'Usuário não possui nenhum imovel cadastrado',
                    'username' => $username['username'],
                ];
                return $msg;
            }
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * Function for add new.
     */
    public function add(){
        try {
            $data = $_POST['data'];
            $price = $this->priceSaleByWake($data['sizeHome'], $data['bedrooms'], $data['suites'], $data['wc'], $data['sizeRecreation'], $data['pool'], $data['garagem']);
            $priceF = $this->formatPriceMillion($price);
            $sql = "INSERT INTO immobile(title_ad, size_m2_home, bedrooms, suites, wc, pool_home, garage, size_m2_recreation, sale_price, id_user, address_home, img_base64 ) VALUES ('{$data['titleAd']}', {$data['sizeHome']}, {$data['bedrooms']}, {$data['suites']}, {$data['wc']},{$data['pool']}, {$data['garagem']}, {$data['sizeRecreation']},$priceF,{$data['idUser']},'{$data['address_h']}','{$data['base64Img']}')";
            $res = $this->conn->query($sql);
            if( $res == true){
                return $res;
            }else{
                return $this->conn->error;
            } 

        } catch (\Throwable $th) {
             return $th->getMessage();
        } 
    }

    public function delete(){
        try {
            $id = $_POST['id'];
            $sql = "DELETE FROM immobile WHERE id_ = $id";
            if ( $this->conn->query($sql) === TRUE ){
                return true;
            }else{
                return false;
            }
        } catch (\Throwable $th) {
            return $th->getMessage();
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
     *  
     *  Por que ?
     *  R: Com base na analise dos dados (data_base_10_instance.arff) foi gerado essa função onde podemos perceber que todos os atributos são 
     *  utilizados para a formulação dos pesos na multiplicação, com bases nessas 10 instancias o coeficiente de erro encontra-se em 0,1% sendo
     *  considerado assim como melhor opção para esse calculo.
     */
    public function priceSaleByWake($sizeHome, $bedrooms, $suites, $wc, $sizeRecreation, $pool, $garage){
        $calc = ( 231.7489 * $sizeHome ) + ( 198584.2266 * $bedrooms ) + ( -8011.9209 * $suites ) + ( 158646.3995 * $wc ) + ( 4846.0335 * $sizeRecreation ) + ( 146174.6425 * $pool ) + ( 68896.2327 * $garage ) -766580.1618;
        $round = number_format( round($calc), 3,'.','.'); 
        return str_replace('.000', '', $round);        
    }

    /**
     * Function format price, ex: 2.100.540 for 2100.540
     */
    public function formatPriceMillion($price){
        if(strlen($price) > 7){
            $str = substr_replace($price, '', 1, 1);
            return $str;
        }else{
            return $price;
        }
    }
   
    
}
