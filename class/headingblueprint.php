<?php

class HeadingBlueprint {
	public function createArray() {
		$heading = array(
			"groesse" => array(
				"extends" => "blocks/groesze",
			),
			"modifizierer" => array(
				"extends" => "blocks/modifizierer"
			),
			"schnitt" => array(
				"extends" => "blocks/schnitt",
			),
			"ordnung" => array(
				"extends" => "blocks/ordnung",
			),
			"title" => array(
				"extends" => "blocks/titel",
			),
			"line-a" => array(
				"type"=>"line",
			)

		);
		return $heading;
	}
}