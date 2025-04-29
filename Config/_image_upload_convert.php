<?php
    include 'imageResize.php';
							
							
									$imageProcess = 0;
									if(is_array($_FILES)){
										$fileName = $_FILES['upload_image']['tmp_name'];
										$sourceProperties = getimagesize($fileName);
										$resizeFileName = time().rand();
										$uploadPath = "./../OpenAccountBVN/image_uploads/";
										$fileExt = pathinfo($_FILES['upload_image']['name'], PATHINFO_EXTENSION);
										$uploadImageType = $sourceProperties[2];
										$sourceImageWidth = $sourceProperties[0];
										$sourceImageHeight =  $sourceProperties[1];

										switch ($uploadImageType) {
											case IMAGETYPE_JPEG:
												$resourceType = imagecreatefromjpeg($fileName);
												$imageLayer= resizeImage($resourceType, $sourceImageWidth,$sourceImageHeight);
												imagejpeg($imageLayer,$uploadPath."thump_".$resizeFileName.'.'.$fileExt);
												//echo base64_encode($imagesave);
												
												break;

											case IMAGETYPE_GIF:
												$resourceType = imagecreatefromgif($fileName);
												$imageLayer= resizeImage($resourceType, $sourceImageWidth,$sourceImageHeight);
												imagegif($imageLayer,$uploadPath."thump_".$resizeFileName.'.'.$fileExt);
												//echo base64_encode($imagesave2);
												break;

												case IMAGETYPE_PNG:
												$resourceType = imagecreatefrompng($fileName);
												$imageLayer= resizeImage($resourceType, $sourceImageWidth,$sourceImageHeight);
												imagepng($imageLayer,$uploadPath."thump_".$resizeFileName.'.'.$fileExt);
												//echo base64_encode($imagesave3);
												break;

											default:
												$imageProcess = 0;
												break;
										}
										move_uploaded_file(@$file, $uploadPath. $resizeFileName. ".". $fileExt);

										$out_image = file_get_contents($uploadPath."thump_".$resizeFileName.'.'.$fileExt);

										$out_imageConvert = base64_encode($out_image);
										
										//echo $out_imageConvert; 
										
										$imageProcess = 1;
									}