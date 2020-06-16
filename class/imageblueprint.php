<?php

class ImageBlueprint {
	public function createArray() {
		$heading = array(
			"image_line-a" => array(
				"type"=>"line",
			),
			"image" => array(
				"extends" => "blocks/image"
			),
			"alternativtext" => array(
				"extends" => "blocks/alternativtext",
			),
			"img_width" => array(
				"extends" => "blocks/width",
			),
			"img_height" => array(
				"extends" => "blocks/height",
			),
			"image_line-b" => array(
				"type"=>"line",
			)

		);
		return $heading;
	}
}