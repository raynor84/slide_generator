<?php

class LogoBlueprint {
	public function createArray() {
		$array = array(
			"line-g" => array(
				"type"=>"line",
			),
			"logo" => array(
				"extends" => "blocks/logo",
			),
			"width" => array(
				"extends" => "blocks/width"
			),
			"height" => array(
				"extends" => "blocks/height"
			),
			"alternativtext" => array(
				"extends" => "blocks/alternativtext",
			),
			"line-h" => array(
				"type"=>"line",
			)

		);
		return $array;
	}
}