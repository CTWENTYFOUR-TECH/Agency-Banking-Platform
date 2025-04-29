<?php

	function resizeImage($resourceType, $image_width, $image_height){
		$resizeWidth = 300;
		$resizeHeight = 300;

		$imageLayer = imagecreatetruecolor($resizeWidth, $resizeHeight);
		imagecopyresampled($imageLayer, $resourceType, 0,0,0,0, $resizeWidth, $resizeHeight, $image_width, $image_height);
		return $imageLayer;
	}

	
?>