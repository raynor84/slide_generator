<?php
class DynamicClassGenerator {
	private $dom;
	private $comment;
	
	private $filename;
	private $className;
	public function __construct($dom, $commentNode) {
		$this->dom = $dom;
		$this->doc = new DOMDocument("1.0");
		$this->doc->preserveWhiteSpace = false;
		$this->doc->formatOutput = true;
        $this->filename = "../kirby_react/src/class/DynamicClass.js";
        
		$this->comment = $commentNode;
        $this->generateClassName();
        $this->updateDynamicClass();
	}

	

	public function generateClassName() {
	    $comment = $this->comment->nodeValue;
	    if(!strlen($comment)) {
		    throw new Exception("Invalid Comment");
	    }
	    $matches = null;
		preg_match('/((#[0-9]*))/', $comment, $matches);
	    $matches[1]= substr($matches[1], 1);
		$this->className = "Slide".$matches[1];
		
	}
    public function updateDynamicClass() {

		
        $text = file_get_contents($this->filename);
        //echo $text;
        if(!strpos($text, $this->className)) {
			$text = "import $this->className from '../slide/$this->className'".";\n".$text;
            $text = str_replace("};", "\t".$this->className.",\n};", $text);
        }
        //echo "\n";
        //echo $text;
        file_put_contents($this->filename, $text);
	}
	
}