<?PHP

namespace lib\engines; 
use lib as Library; 
class Solr {

	public $handle; 
	private $settings;
	private $path;
	public function __construct(){

		$settings=Library\Bootstrap::database("solr"); 

		if (!empty($settings)){

			$this->settings=$settings; 
		}

	}


	public function connect($path){
		
		$this->path=$path;
		if (empty($this->handle) && !empty($this->settings[$path])){

			$this->handle = new \SolrClient($this->settings[$path]);

			if (!$this->handle) {
				throw new \Exception("Could not connect to database");
			}
		}

		return $this;

	}

	public function query($params=array()){

		extract($params); 

		if (!empty($select)) {
			$query = new \SolrQuery($select);
		}
		else {
			$query = new \SolrQuery("*:*");
		}



		if (!empty($filters)){
			foreach($filters as $filter){
	            $query->addFilterQuery($filter);
	        }
		}



		if (!empty($facets)){
			$query->setFacet(true);
			foreach($facets as $facet){
	            $query->addFacetField($facet);
	        }
		}



		if (!empty($fields)){
			foreach($fields as $field){
	            $query->addField($field);
	        }
		}

		if (!empty($boosts)){
			foreach($boosts as $bf){
	            $query->addParam('bf',$bf);
	        }
    	}


		if (!empty($params)){
			foreach($params as $param){
	            $query->addParam($param['name'],$param['value']);
	        }
		}


		if (!empty($rows)){
			 $query->setRows($rows);
		}

		if (!empty($start)){
			 $query->setStart($start);
		}
		$result = $this->handle->query($query); 
		return $result->getResponse();

	}

	public function add(Array $arr){ 
		
		$document = new \SolrInputDocument();	

		foreach($arr as $title => $data) {
			if (is_array($data)) {
				foreach($data as $value) {
					$document->addField($title,$value);
				}
			}
			else $document->addField($title,$data);
		}
		
		$this->handle->addDocument($document);
	}

	public function commit(){ 
		extract($this->settings[$this->path]);
		exec("curl -s http://$hostname:$port/$path/update?commit=true -H 'Content-Type: text/xml' --data-binary '<commit/>'");
	}

	public function optimize(){ 
		extract($this->settings[$this->path]);
		exec("curl -s http://$hostname:$port/$path/update?commit=true -H 'Content-Type: text/xml' --data-binary '<optimize/>'");
	}

	public function deleteByQuery($query){
		$this->handle->deleteByQuery($query);
	}

}