<?php 

									$signatureProcess = 0;
									if(is_array($_FILES)){
										$fileSignName = $_FILES['upload_sign']['tmp_name'];
										$sourceProperties = getimagesize($fileSignName);
										$resizeFileSignName = time().rand();
										$uploadPath = "./../OpenAccountBVN/signature_uploads/";
										$fileSignExt = pathinfo($_FILES['upload_sign']['name'], PATHINFO_EXTENSION);
										$uploadSignType = $sourceProperties[2];
										$sourceImageWidth = $sourceProperties[0];
										$sourceImageHeight =  $sourceProperties[1];

										switch ($uploadSignType) {
											case IMAGETYPE_JPEG:
												$resourceType = imagecreatefromjpeg($fileSignName);
												$imageLayer= resizeImage($resourceType, $sourceImageWidth,$sourceImageHeight);
												imagejpeg($imageLayer,$uploadPath."signature_".$resizeFileSignName.'.'.$fileSignExt);
												break;

											case IMAGETYPE_GIF:
												$resourceType = imagecreatefromgif($fileSignName);
												$imageLayer= resizeImage($resourceType, $sourceImageWidth,$sourceImageHeight);
												imagegif($imageLayer,$uploadPath."signature_".$resizeFileSignName.'.'.$fileSignExt);
												break;

											case IMAGETYPE_PNG:
												$resourceType = imagecreatefrompng($fileSignName);
												$imageLayer= resizeImage($resourceType, $sourceImageWidth,$sourceImageHeight);
												imagepng($imageLayer,$uploadPath."signature_".$resizeFileSignName.'.'.$fileSignExt);
												break;

											default:
												$signatureProcess = 0;
												break;
										}
										move_uploaded_file(@$file, $uploadPath. $resizeFileSignName. ".". $fileSignExt);
										
										$out_sign = file_get_contents($uploadPath."signature_".$resizeFileSignName.'.'.$fileSignExt);
                                        
                                        $out_signConvert = base64_encode($out_sign);
                                        
										//echo $out_signConvert;

										$signatureProcess = 1;
									}
                                    