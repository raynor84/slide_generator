<?php

class HeadingWithPopupBlueprint {
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
			"title_left" => array(
				"extends" => "blocks/titel",
				"label" => "Titel Links",
			),
			"title_right" => array(
				"extends" => "blocks/titel",
				"label" => "Titel Rechts",
			),
			"youtube_url" => array(
				"extends" => "blocks/youtube_url",

			),
			"line-a" => array(
				"type"=>"line",
			)

		);
		return $heading;
	}
}