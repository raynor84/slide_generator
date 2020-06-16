<?php

class SlideBlueprint {
	public function createArray() {
		$heading = array(
			"fade" => array(
				"extends" => "blocks/fade",
			),
			"hintergrund" => array(
				"extends" => "blocks/hintergrund"
			),
			"whiteslide" => array(
				"extends" => "blocks/whiteslide"
			),
			"hoehe" => array(
				"extends" => "blocks/hoehe",
			),
			"abstand" => array(
				"extends" => "blocks/abstand",
			),
			"hintergrundbild" => array(
				"extends" => "blocks/hintergrundbild",
			),
		);
		return $heading;
	}
}