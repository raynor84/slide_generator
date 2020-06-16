<?php

class ButtonBlueprint {
	public function createArray($button) {
		$heading = array(
			$button."_text" => array(
				"extends" => "blocks/button_text"
			),
			$button."_url" => array(
				"extends" => "blocks/button_url"
			),
			$button."_fenster" => array(
				"extends" => "blocks/button_fenster"
			),
			$button."_nachverfolgung" => array(
				"extends" => "blocks/button_nachverfolgung"
			),	
			$button."_stil" => array(
				"extends" => "blocks/button_stil"
			),	
			$button."_groesse" => array(
				"extends" => "blocks/button_groesse"
			),	
			$button."_material_icon" => array(
				"extends" => "blocks/button_material_icons"
			),				
			$button."_position" => array(
				"extends" => "blocks/button_position"
			),
			$button."_farbe" => array(
				"extends" => "blocks/button_farbe"
			),
			"line-a" => array(
				"type"=>"line",
			)

		);
		return $heading;
	}
}