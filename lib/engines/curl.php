<?PHP

namespace lib\engines; 
use lib as Library; 
class Curl {


	public function post($url,$data = array(),&$curlinfo=false){
		$curl =curl_init();	
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		curl_setopt($curl,CURLOPT_POST, count($data));
		curl_setopt($curl,CURLOPT_POSTFIELDS, http_build_query($data));
		$data=curl_exec($curl); 

		if (!empty($curlinfo)) $curlinfo = curl_getinfo($curl);
		
		curl_close($curl);
		return $data; 
	}

	public function put($url,$data = array(),&$curlinfo=false){
		
		$curl =curl_init();	
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('X-HTTP-Method-Override: PUT'));
		curl_setopt($curl,CURLOPT_POSTFIELDS, http_build_query($data));
		$data=curl_exec($curl); 

		if (!empty($curlinfo)) $curlinfo = curl_getinfo($curl);
		
		curl_close($curl);
		return $data; 
	}

	public function delete($url,$data = array(),&$curlinfo=false){
		if (empty($curl)) $curl =curl_init();	
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('X-HTTP-Method-Override: DELETE'));
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		curl_setopt($curl,CURLOPT_POSTFIELDS, http_build_query($data));
		$data=curl_exec($curl); 
		
		if (!empty($curlinfo)) $curlinfo = curl_getinfo($curl);

		curl_close($curl);
		return $data; 
	}

	public function get($url,$data = array(),&$curlinfo=false){	
		
		if (empty($curl)) $curl =curl_init();	
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, 20);
		$data=curl_exec($curl); 

		if (!empty($curlinfo)) $curlinfo = curl_getinfo($curl);
		
		curl_close($curl);
		return $data; 
		
	}

	public function status($method,$url,$data){
		if (method_exists($this, $method)){
			$curlinfo=true; 
			$data = $this->{$method}($url,$data,$curlinfo); 

			return array("status"=>$curlinfo,"data"=>$data);
		}
		else return false;
	}

}