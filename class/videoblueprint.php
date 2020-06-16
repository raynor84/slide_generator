<?php

class VideoBlueprint {
	public function createArray() {
		$heading = array(
			"line-e" => array(
				"type"=>"line",
			),
			"youtube_url" => array(
				"extends" => "blocks/youtube_url",
			),
			"videovorschau" => array(
				"extends" => "blocks/videovorschau"
			),
			"alternativtext" => array(
				"extends" => "blocks/alternativtext_videovorschau",
			),
			"line-f" => array(
				"type"=>"line",
			)

		);
		return $heading;
	}
}