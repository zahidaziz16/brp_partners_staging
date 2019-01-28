<?php
class ModelToolImage extends Model {
	public function resize($filename, $width, $height) {
		if (!is_file(DIR_IMAGE . $filename) || substr(str_replace('\\', '/', realpath(DIR_IMAGE . $filename)), 0, strlen(DIR_IMAGE)) != DIR_IMAGE) {
			return;
		}

		$extension = pathinfo($filename, PATHINFO_EXTENSION);

		$image_old = $filename;
		$image_new = 'cache/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-' . (int)$width . 'x' . (int)$height . '.' . $extension;

		if (!is_file(DIR_IMAGE . $image_new) || (filectime(DIR_IMAGE . $image_old) > filectime(DIR_IMAGE . $image_new))) {
			list($width_orig, $height_orig, $image_type) = getimagesize(DIR_IMAGE . $image_old);
				 
			if (!in_array($image_type, array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF))) { 
				return DIR_IMAGE . $image_old;
			}
						
			$path = '';

			$directories = explode('/', dirname($image_new));

			foreach ($directories as $directory) {
				$path = $path . '/' . $directory;

				if (!is_dir(DIR_IMAGE . $path)) {
					@mkdir(DIR_IMAGE . $path, 0777);
				}
			}

			if ($width_orig != $width || $height_orig != $height) {
				$image = new Image(DIR_IMAGE . $image_old);
				$image->resize($width, $height);
				$image->save(DIR_IMAGE . $image_new);
			} else {
				copy(DIR_IMAGE . $image_old, DIR_IMAGE . $image_new);
			}
		}
		
		$image_new = str_replace(' ', '%20', $image_new);  // fix bug when attach image on email (gmail.com). it is automatic changing space " " to +
		
		if ($this->request->server['HTTPS']) {
			return $this->config->get('config_ssl') . 'image/' . $image_new;
		} else {
			return $this->config->get('config_url') . 'image/' . $image_new;
		}
	}
        
        public function resizeBRP($filename, $width, $height) {
            
            
		
		$extension = pathinfo(parse_url($filename,PHP_URL_PATH), PATHINFO_EXTENSION);

		$image_old = $filename;
                
		$image_new = 'cache/' . utf8_substr($filename, 42, utf8_strrpos(utf8_substr($filename, 42), '.')) . '-' . (int)$width . 'x' . (int)$height . '.' . $extension;       
//print_r(get_headers(str_replace(' ', '%20', $filename)));exit;
                
                
//                $h = get_headers(str_replace(' ', '%20', $filename), 1);
//                $dt = NULL;
//                if (!(!$h || strstr($h[0], '200') === FALSE)) {
//                    if(isset($h['Last-Modified'])) {
//                        $dt = new \DateTime($h['Last-Modified']);//php 5.3
//                    }else {
//                        return;
//                    }
//                }
//                
//                $dtL = new DateTime("NOW");
//                if(is_file(DIR_IMAGE . $image_new)) {
//                    $dtL->setTimestamp(filectime(DIR_IMAGE . $image_new));
//                }
                
                
                    
		if (!is_file(DIR_IMAGE . $image_new)){// || ($dt > $dtL)) {
			list($width_orig, $height_orig, $image_type) = getimagesize(str_replace(' ', '%20', $filename));
				 
                        
			
						
			$path = '';

			$directories = explode('/', dirname($image_new));

			foreach ($directories as $directory) {
				$path = $path . '/' . $directory;

				if (!is_dir(DIR_IMAGE . $path)) {
					@mkdir(DIR_IMAGE . $path, 0777);
				}
			}
                        

			if ($width_orig != $width || $height_orig != $height) {
                            
				$image = new Image(str_replace(' ', '%20', $filename), true);
				$image->resize($width, $height);
				$image->save(DIR_IMAGE . $image_new);
			} else {
				copy(str_replace(' ', '%20', $filename), DIR_IMAGE . $image_new);
			}
		}
		
		$image_new = str_replace(' ', '%20', $image_new);  // fix bug when attach image on email (gmail.com). it is automatic changing space " " to +
		
		if ($this->request->server['HTTPS']) {
			return $this->config->get('config_ssl') . 'image/' . $image_new;
		} else {
			return $this->config->get('config_url') . 'image/' . $image_new;
		}
	}
        
}
