<?php
use Symfony\Component\Yaml\Yaml;

spl_autoload_register(function ($class_name) {
    require_once("./class/" .strtolower($class_name) . '.php');
});
class BlueprintGenerator {
	private $dom;
	private $comment;
	
	private $filename;
	private $className;

	private $hasHeading, $hasVideo, $hasHeadingWithPopup, $hasLogo, $hasPopupbutton, $hasImage;
	private $hasText;
	private $buttons = array();
	private $fields;
	public function __construct($dom, $commentNode) {
		$this->dom = $dom;

		$this->doc = new DOMDocument("1.0");
		$this->doc->loadXML("<root></root>");

		$node = $this->doc->importNode($this->dom, true);
		$this->doc->documentElement->appendChild($node);
		$this->doc->preserveWhiteSpace = false;
		$this->doc->formatOutput = true;
			
		$this->comment = $commentNode;
		
		$this->generateFileName();
		$this->fetchInformation();
		$this->setFields();
		$this->createBlueprint();
		$this->updateDefaultYML();
	}
	public function fetchInformation() {
		$finder = new DomXPath($this->doc);
		
		//Check if Heading with Popup or Header
		$headingWithPopup = $finder->query('//section//h1/span[contains(@class, "popupTrigger")]');
		$headings = $this->doc->getElementsByTagName('h1');
		if($headingWithPopup[0]) {
			$this->hasHeadingWithPopup = true;
		} else if($headings[0]) {
			$this->hasHeading = true;
		}
		$video = $finder->query("//div[contains(@class, 'videoThumbnail')]");
		if($video[0]) {
			$this->hasVideo = true;
		}
		
		$texts = $this->doc->getElementsByTagName('p');
		if($texts[0]) {
			$this->hasText = true;
		}
		//var_dump($this->dom);
		
		$query = '//section//svg';

		$entries = $finder->query($query);
		if($entries[0]) {
			$this->hasLogo = true;
		}

		$query = '//section//div[contains(@class, "popupTrigger")][contains(@class, "button")]';

		$entries = $finder->query($query);
		if($entries[0]) {
			$this->hasPopupbutton = true;
		}
		$buttons = $finder->query("//a[contains(@class, 'button')]");

		$letters ="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		if(count($buttons)) {

			for($i=0; $i<count($buttons); $i++) {
				
				array_push($this->buttons, "Button".$letters[$i]);
			}
		}

		$query = '//section//img';

		$entries = $finder->query($query);
		
		foreach($entries as $entry) {
			
			array_push($this->hasImage, true);
		}

	}
	public function createBlueprint() {
		//echo "creating Blueprint";
		$yaml = Yaml::dump($this->fields);
		//echo $yaml;
		file_put_contents($this->filename, $yaml);
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
	    $this->filename = "../kirby/site/blueprints/slide/Slide".$matches[1].".yml";	
	}

	public function setFields() {

		$this->fields = array(
			"name" => $this->className,
			"label" => $this->className.": {{title}}",
			"preview" => array(
				"snippet" => "blocks/quote",
				"css" => "/assets/css/blocks/quote.css",
			),
			"defaultView" => "preview",
			"type" => "fields",
			"fields" => array(

			),
		);

		if($this->hasHeadingWithPopup) {
			$blueprintHeadingWithPopup = new HeadingWithPopupBlueprint();
			$headingWithPopup = $blueprintHeadingWithPopup->createArray();

			$this->fields["fields"] = array_merge($this->fields["fields"], $headingWithPopup);
		}
		if($this->hasHeading) {
			//echo "\nTrue";
			$blueprintHeading = new HeadingBlueprint();
			$headings = $blueprintHeading->createArray();
			
			$this->fields["fields"] = array_merge($this->fields["fields"], $headings);
			//var_dump($this->fields);
		}

		if($this->hasText) {
			$text = array(
				"text" => array(
					"extends" => "blocks/text",
				),
				"line-b" => array(
					"type"=>"line",
				)

			);
			$this->fields["fields"] = array_merge($this->fields["fields"], $text);
		}

		if($this->hasLogo) {
			$blueprintLogo = new LogoBlueprint();
			$logo = $blueprintLogo->createArray();

			$this->fields["fields"] = array_merge($this->fields["fields"], $logo);
		}

		if($this->hasPopupbutton) {
			$blueprintpopupbutton = new PopupButtonBlueprint();
			$array = $blueprintpopupbutton->createArray();

			$this->fields["fields"] = array_merge($this->fields["fields"], $array);
		}
		//var_dump($this->buttons);
		foreach($this->buttons as $button) {
			$button = strtolower($button);
			
			$buttonBlueprint= new ButtonBlueprint();
			$button_yml = $buttonBlueprint->createArray($button);

			$this->fields["fields"] = array_merge($this->fields["fields"], $button_yml);

		}
		if($this->hasHeading) {
			
			$blueprintHeading = new HeadingBlueprint();
			$headings = $blueprintHeading->createArray();
			
			$this->fields["fields"] = array_merge($this->fields["fields"], $headings);
			
		}

		if($this->hasVideo) {
			
			$blueprintVideo = new VideoBlueprint();
			$headings = $blueprintVideo->createArray();
			
			$this->fields["fields"] = array_merge($this->fields["fields"], $headings);
			
		}

		foreach($this->hasImage as $image) {
			$blueprintImage = new ImageBlueprint();
			$array = $blueprintImage->createArray();
			
			$this->fields["fields"] = array_merge($this->fields["fields"], $array);

		}


		$slideBlueprint = new SlideBlueprint();
		$slide_yml = $slideBlueprint->createArray();


		$this->fields["fields"] =  array_merge($this->fields["fields"], $slide_yml);
		//var_dump($this->fields);
	}

	public function updateDefaultYML() {
		$update = array(
			strtolower($this->className) => array(
				"extends" => strtolower("slide/".$this->className),
			)
		);
		
		$yml = Yaml::parseFile('../kirby/site/blueprints/pages/default.yml');
		//var_dump($yml["fields"]["slides"]["fieldsets"]);
		$yml["fields"]["slides"]["fieldsets"] = array_merge($yml["fields"]["slides"]["fieldsets"], $update);
		$file = Yaml::dump($yml, 10);
		
		file_put_contents('../kirby/site/blueprints/pages/default.yml', $file);
	}

}