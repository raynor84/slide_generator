<?php
	require_once("vendor/autoload.php");
	require_once("./reactgenerator.php");
	require_once("./blueprintgenerator.php");
	require_once("./dynamicclassgenerator.php");
	ini_set('display_errors', 'On');
	error_reporting(E_ERROR);
	

/* Use internal libxml errors -- turn on in production, off for debugging */
libxml_use_internal_errors(false);
/* Createa a new DomDocument object */
$dom = new DomDocument;
/* Load the HTML */
$newContent = new DOMDocument('1.0');

$dom->loadHTMLFile("../slide5/index.html");
/* Create a new XPath object */
$xpath = new DomXPath($dom);
/* Query all <td> nodes containing specified class name */
$nodes = $xpath->query("//body");
/* Set HTTP response header to plain text for debugging output */
header("Content-type: text/plain");
/* Traverse the DOMNodeList object to output each DomNode's nodeValue */
//var_dump($dom);
//var_dump($xpath);

	
	foreach($nodes as $node)
    {
	    foreach($node->childNodes as $child) {
			if(($child->nodeName!="svg")&&($child->nodeName!="script")&&($child->nodeName!="#text")&&($child->nodeName!="nav")&&($child->nodeName!="#comment")) {
				//var_dump($children->parentNode);
				    // clone the parent
			    
				
				$newNode = $child->cloneNode(true);
				$b_comment_found = false;
				$comment = $child;
				while(!$b_comment_found) {
					$comment = $comment->previousSibling;
					//echo $child->nodeName;
					if($comment->nodeName=="#comment") {
						$b_comment_found=true;
					}
				}
			   
				$break = false;
				$videoNode = $child;

				while(!$break) {
					$videoNode = $videoNode->nextSibling;
					if(!$videoNode) {
						$break = true;
						$videoNode = NULL;
					}
					if($videoNode->nodeName=="div") {
						$break=true;
						
					}
					if($videoNode->nodeName=="section") {
						$break=true;
						$videoNode = NULL;	
					}

				}


				$reactGenerator = new ReactGenerator($newNode, $comment, $videoNode);
			    //$reactGenerator->generateFiles();
				
				$dynamicClassGenerator = new DynamicClassGenerator($newNode, $comment);

			    $blueprintGenerator = new BlueprintGenerator($newNode, $comment);
			    //$blueprintGenerator->generateFiles();
			    

		        
		        
		    }
		    
	    }
	    
    }    





echo "\n\nwriting finished";