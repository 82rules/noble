<?PHP


namespace lib; 

class Controllers {

	public function rest(\lib\Responder $responder){
		
		if (method_exists($this, $responder->context->_rest_)){
			$responder=$this->{$responder->context->_rest_}($responder);
		}

		return $responder; 
	}


	public function put(\lib\Responder $responder){

		return $responder;
	}

	public function post(\lib\Responder $responder){

		return $responder;
	}

	public function get(\lib\Responder $responder){

		return $responder;
	}

	public function delete(\lib\Responder $responder){

		return $responder;
	}

	public function section(\lib\Responder $responder){

		if (!empty($responder->context->section) && !empty($responder->context->subsection)) {
			if (method_exists($this, sprintf('section_%s_%s',$responder->context->section, $responder->context->subsection))) {
				$responder = $this->{sprintf('section_%s_%s',$responder->context->section, $responder->context->subsection)}($responder); 
			}
			
		}

		else if (!empty($responder->context->section) && method_exists($this,"section_".$responder->context->section)){
			$responder = $this->{"section_".$responder->context->section}($responder); 
		}

		return $responder; 
	}
}