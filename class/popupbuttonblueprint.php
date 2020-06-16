<?php

class PopupButtonBlueprint {
	public function createArray() {
		$array = array(
			"line-i" => array(
				"type"=>"line",
			),
			"buttonpopup_text" => array(
				"extends" => "blocks/button_text"
			),
			"youtube_url" => array(
				"extends" => "blocks/youtube_url"
			),
			"buttonpopup_stil" => array(
				"extends" => "blocks/button_stil"
			),	
			"buttonpopup_groesse" => array(
				"extends" => "blocks/button_groesse"
			),	
			"buttonpopup_material_icon" => array(
				"extends" => "blocks/button_material_icons"
			),				
			"buttonpopup_position" => array(
				"extends" => "blocks/button_position"
			),
			"buttonpopup_farbe" => array(
				"extends" => "blocks/button_farbe"
			),
			"line-j" => array(
				"type"=>"line",
			)

		);
		return $array;
	}
}