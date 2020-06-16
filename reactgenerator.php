<?php
class ReactGenerator {
	private $dom;
	private $comment;
	private $videoNode;
	
	private $filename;
	private $className;
	public function __construct($dom, $commentNode, $videoNode = NULL) {
		$this->dom = $dom;
		$this->videoNode = $videoNode;
		$this->doc = new DOMDocument("1.0");
		$this->doc->preserveWhiteSpace = false;
		$this->doc->formatOutput = true;
			
		$this->comment = $commentNode;
		$this->generateFileName();
		$this->prepareTag();
		$this->replaceTags();
		$this->createReact();
	}
	public function prepareTag() {
		$node =  $this->dom;
		
		if($node->nodeName=="section") {
			$reactfragment = $this->doc->createElement("React.Fragment", "");
			$section = $this->doc->createElement("Section", "");
			$reactfragment->appendChild($section);


			$this->doc->appendChild($reactfragment);
			//var_dump($imported->childNodes[1]->nodeName);
			$imported = $this->doc->importNode($node, true);
			
			//Append childs
			for($i=1; $i<count($imported->childNodes[1]->childNodes); $i++) {

				
				$section->appendChild($imported->childNodes[1]->childNodes[$i]);
			}



			$section->setAttribute("slide", "{this.props.slide}");

			if($this->videoNode) {
				$imported = $this->doc->importNode($this->videoNode, true);
				$reactfragment->appendChild($imported);
			}
	    }
	    
	    
        foreach($node->attributes as $attribute) {
	        $name = $attribute->nodeName;
			$value = $attribute->nodeValue;
	        
	        if($name=="class") {
		        //$name="className";
		        //$node->removeAttribute("class");
	        }
			
	        $node->setAttribute($name, $value);
        }

			
	}
	
	public function createReact() {

		
		$myfile = fopen($this->filename, "w") or die("Unable to open file!");
		
		fwrite($myfile, "import React from 'react';\n");
		fwrite($myfile, "import Section from './Section';\n");
		foreach($this->imports as $class => $location) {
		fwrite($myfile, "import ".$class." from '".$location."';\n");
			
		}
		fwrite($myfile, "class ". $this->className ." extends React.Component {\n\n");
		fwrite($myfile, "\trender() {\n");
		fwrite($myfile, "\t\treturn (\n");
		

		$lines = explode("\n", $this->doc->saveXML(null, LIBXML_NOXMLDECL), 2);
		if(!preg_match('/^\<\?xml/', $lines[0]))
			$content = $lines[0];
		$content .= $lines[1];
		

		$content = str_replace("\"{", "{", $content);
		$content = str_replace("}\"", "}", $content);

		$content = str_replace('xmlns:xlink="http://www.w3.org/1999/xlink"', 'xmlnsXlink="http://www.w3.org/1999/xlink"', $content);
		//$content = preg_replace('~(?:="|"{\s+(?:\w+="|\/>))(?!)}|"~', '', $content);

		fwrite($myfile, $content);
		fwrite($myfile, "\n\t\t);\n");
		fwrite($myfile, "\t}\n");
		fwrite($myfile, "}\n");
		fwrite($myfile, "export default ".$this->className. ";");
		
		fclose($myfile);
		
		chmod($this->filename, 0777);

	}
	public function generateFileName() {
	    $comment = $this->comment->nodeValue;
	    if(!strlen($comment)) {
		    throw new Exception("Invalid Comment");
	    }
	    $matches = null;
		preg_match('/((#[0-9]*))/', $comment, $matches);
	    $matches[1]= substr($matches[1], 1);
	    //var_dump($matches);
		$this->className = "Slide".$matches[1];
	    $this->filename = "../kirby_react/src/slide/Slide".$matches[1].".js";
		
	}
	public function getSection() {
		
	}
	
	public function generateComponent() {
		
	}
	
	public function replaceTags() {

		$xpath = new DOMXPath($this->doc);
		//Heading with popup
		
		// We start from the root element
		$query = '//comment()';

		$entries = $xpath->query($query);
		foreach($entries as $entry) {
			$entry->parentNode->removeChild($entry);
		}

		$query = '//Section//h1/span[contains(@class, "popupTrigger")]';

		$entries = $xpath->query($query);
		if($entries[0]) {
			$react_element = $entries[0]->parentNode->ownerDocument->createElement("HeadingWithPopup");
			$react_element->setAttribute("fields", "{this.props.slide}");
	
			$entries[0]->parentNode->parentNode->replaceChild($react_element, $entries[0]->parentNode);
			$this->imports["HeadingWithPopup"]="./fields/HeadingWithPopup";

		}

		//Headings
		$headings = $this->doc->getElementsByTagName('h1');
				
		if($headings[0]) {
			
			$react_heading = $headings[0]->ownerDocument->createElement("Heading");
			$react_heading->setAttribute("fields", "{this.props.slide}");
			$class=$headings[0]->getAttribute("class");
			$class = strstr($class, "margin");

			$react_heading->setAttribute("addClass", $class);

			$headings[0]->parentNode->replaceChild($react_heading, $headings[0]);
			$this->imports["Heading"]="./fields/Heading";
		}
		
		//Texts
		$texts = $this->doc->getElementsByTagName('p');
		if($texts[0]) {
			$react_text = $texts[0]->ownerDocument->createElement("Text");
			$react_text->setAttribute("fields", "{this.props.slide}");
			$class = $texts[0]->getAttribute("class");
			$react_text->setAttribute("addClass", $class);
			
			$texts[0]->parentNode->replaceChild($react_text, $texts[0]);
			$this->imports["Text"]="./fields/Text";			
		}
		
		//Logo

		// We start from the root element
		$query = '//Section//svg';

		$entries = $xpath->query($query);
		if($entries[0]) {
			$react_element = $entries[0]->ownerDocument->createElement("Logo");
			$react_element->setAttribute("fields", "{this.props.slide}");
	
			$entries[0]->parentNode->replaceChild($react_element, $entries[0]);
			$this->imports["Logo"]="./fields/Logo";			
		}
		
		//PopupButton
		
		// We start from the root element
		$query = '//Section//div[contains(@class, "popupTrigger")][contains(@class, "button")]';

		$entries = $xpath->query($query);
		if($entries[0]) {
			$react_element = $entries[0]->ownerDocument->createElement("PopupButton");
			$react_element->setAttribute("fields", "{this.props.slide}");
			$react_element->setAttribute("popupid", $entries[0]->getAttribute("data-popup-id"));

			$entries[0]->parentNode->replaceChild($react_element, $entries[0]);
			$this->imports["PopupButton"]="./fields/PopupButton";			
		}

		//Buttons
		$finder = new DomXPath($this->doc);
		$buttons = $finder->query("//a[contains(@class, 'button')]");
		$letters ="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		if(count($buttons)) {

			
			$s=0;
			for($i=0; $i<count($buttons); $i++) {

				$react_button = $buttons[$i]->ownerDocument->createElement("Button".$letters[$s]);
				$react_button->setAttribute("fields", "{this.props.slide}");
				
				$buttons[$i]->parentNode->replaceChild($react_button, $buttons[$i]);
				$this->imports["Button".$letters[$i]]="./fields/Button".$letters[$i];

				$s++;
			}
			
			
		}

		//Images
		

		$query = '//Section//img';

		$entries = $xpath->query($query);

		foreach($entries as $entry) {
			$class = $entry->getAttribute("class");
			$data_action = $entry->getAttribute("data-action");
			
			$react_element = $entry->ownerDocument->createElement("Img");
			$react_element->setAttribute("fields", "{this.props.slide}");
			$react_element->setAttribute("addClass", $class);
			$react_element->setAttribute("dataAction", $data_action);
			
	
			$entry->parentNode->replaceChild($react_element, $entry);
			$this->imports["Img"]="./fields/Img";

		}


		$use = $finder->query("//use");
		if(count($use)) {
			$xhref = $use[0]->getAttribute("xlink:href");
			$use[0]->removeAttribute("xlink:href");
			$use[0]->setAttribute("xlinkHref", $xhref);
		}
		
		$iframe = $finder->query("//iframe");
		$imgVorschau = $finder->query("//div[contains(@class, 'videoThumbnail')]");

		if(count($iframe)) {

			$iframe[0]->setAttribute("data-src", "{this.props.slide.youtube_url}");
			$iframe[0]->setAttribute("title", "{this.props.slide._key}");
			
			$frameborder = $iframe[0]->getAttribute("frameborder");
			$iframe[0]->setAttribute("frameBorder", $frameborder);
			$iframe[0]->removeAttribute("frameborder");

			$allowfullscreen = $iframe[0]->getAttribute("allowfullscreen");
			$iframe[0]->setAttribute("allowFullScreen", $allowfullscreen);
			$iframe[0]->removeAttribute("allowfullscreen");


		}
		if(count($iframe) && count($imgVorschau)) {
			$react = $imgVorschau[0]->ownerDocument->createElement("VideoThumbnail");
			$react->setAttribute("fields", "{this.props.slide}");
			$react->setAttribute("popupid", $imgVorschau[0]->getAttribute("data-popup-id"));
			
			$this->imports["VideoThumbnail"]="./fields/VideoThumbnail";		

			$imgVorschau[0]->parentNode->replaceChild($react, $imgVorschau[0]);
		}

		$iframe = $finder->query("//iframe");
		$buttonVorschau = $finder->query("//PopupButton");

	}
	
	public function generateFiles() {

	}
	
}