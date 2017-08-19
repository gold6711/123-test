<?php
class ControllerExtensionModuleOctabcategoryslider extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/octabcategoryslider');

		$this->load->model('catalog/product');
		$this->load->model('catalog/category');
		$this->load->model('catalog/ocproductrotator');
		$this->load->model('tool/image');

		if (file_exists('catalog/view/theme/' . $this->config->get($this->config->get('config_theme') . '_directory') . '/stylesheet/opentheme/categorytabslider.css')) {
			$this->document->addStyle('catalog/view/theme/' . $this->config->get($this->config->get('config_theme') . '_directory') . '/stylesheet/opentheme/categorytabslider.css');
		} else {
			$this->document->addStyle('catalog/view/theme/default/stylesheet/opentheme/categorytabslider.css');
		}

		$data = array();

		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_tax'] = $this->language->get('text_tax');
		$cover_image =  $this->model_tool_image->resize($setting['image'], 270, 410);
		$cover_image2 =  $this->model_tool_image->resize($setting['image'], 270, 463);
		$cover_image3 =  $this->model_tool_image->resize($setting['image'], 270, 520);

		$lang_code = $this->session->data['language'];

		if(isset($setting['title']) && $setting['title']) {
			$data['title'] = $setting['title'][$lang_code]['title'];
		} else {
			$data['title'] = $this->language->get('heading_title');
		}
		
		$data['text_tax'] = $this->language->get('text_tax');

		if (empty($setting['limit'])) {
			$setting['limit'] = 10;
		}

		if(isset($setting['rotator']) && $setting['rotator']) {
			$product_rotator_status = (int) $this->config->get('ocproductrotator_status');
		} else {
			$product_rotator_status = 0;
		}

		if(isset($setting['thumbnail']) && $setting['thumbnail']) {
			$use_thumbnail = true;
		} else {
			$use_thumbnail = false;
		}

		$filter_data = array(
			'rotator_status' => $product_rotator_status,
			'sort'  => 'p.date_added',
			'order' => 'DESC',
			'start' => 0,
			'limit' => $setting['limit']
		);

		$arrayCates = $setting['product_category'];
		
		$arrayCateName = array();
		$arrayExisted = array();
		foreach ($arrayCates as $cate_id) {
			$categories_info = $this->model_catalog_category->getCategory($cate_id);
			$arrayExisted[] = $cate_id;
			if(isset($categories_info['name'])) {
				$category_title = $categories_info['name'];
				$arrayCateName[$cate_id]['name'] = $category_title;
			}
			if($use_thumbnail) {
				if(isset($categories_info['thumbnail_image'])) {
					$arrayCateName[$cate_id]['thumbnail_image'] = $this->model_tool_image->resize($categories_info['thumbnail_image'], 80, 80);
				} else {
					$arrayCateName[$cate_id]['thumbnail_image'] = false;
				}
			} else {
				$arrayCateName[$cate_id]['thumbnail_image'] = false;
			}
		}
		
		$productByCates = array();
		foreach($arrayCates as $cate_id) {
			if(in_array($cate_id,$arrayExisted)) {
				$filter_data['filter_category_id'] = $cate_id;
				$results = $this->getProductFromData($filter_data,$setting);
				$productByCates[$cate_id] = $results ;
			}			
		}

		$fproductByCates = array();
		foreach($arrayCates as $cate_id) {
			if(in_array($cate_id,$arrayExisted)) {
				$filter_data['filter_category_id'] = $cate_id;
				$f_product = $this->getFirstProductFromData($filter_data,$setting);
				$fproductByCates[$cate_id] = $f_product;
			}			
		}

		$productByCatesExceptFirst = array();
		foreach($arrayCates as $cate_id) {
			if(in_array($cate_id,$arrayExisted)) {
				$filter_data['filter_category_id'] = $cate_id;
				$results = $this->getProductExceptFirstFromData($filter_data,$setting);
				$productByCatesExceptFirst[$cate_id] = $results;
			}			
		}
		
		$productEFByCates = array();
		foreach($arrayCates as $cate_id) {
			if(in_array($cate_id,$arrayExisted)) {
				$filter_data['filter_category_id'] = $cate_id;
				$results = $this->getProductExceptFirstFromData($filter_data,$setting);
				$productEFByCates[$cate_id] = $results ;
			}			
		}

		$data['config_slide'] = array(
			'items' => $setting['item'],
			'image' => $cover_image,
			'image2' => $cover_image2,
			'image3' => $cover_image3,
			'tab_cate_autoplay' => $setting['autoplay'],
			'tab_cate_show_nextback' => $setting['shownextback'], 
			'tab_cate_show_ctr' => $setting['shownav'], 
			'tab_cate_speed_slide' => $setting['speed'],
			'tab_cate_show_price' => $setting['showprice'],
			'tab_cate_show_label' => $setting['showlabel'],
			'tab_cate_show_des' => $setting['showdes'],
			'tab_cate_show_addtocart' => $setting['showaddtocart'],
			'f_rows' => $setting['rows'],
            'tab_name' => $setting['name']
		);

		$alias = str_replace(' ','_',$setting['name']);
		$data['cateogry_alias'] = $alias;
		$data['array_cates'] = $arrayCateName; 
		$data['tab_effect'] = 'wiggle';
		$data['category_products'] = $productByCates;
		$data['category_e_f_products'] = $productEFByCates;
		$data['f_products'] = $fproductByCates;

		$data['text_empty'] = $this->language->get('text_empty');
		$data['text_sale'] = $this->language->get('text_sale');
		$data['text_new'] = $this->language->get('text_new');

		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');
		
		if ($data['category_products']) {
			return $this->load->view('extension/module/octabcategoryslider', $data);
		}
	}

	public function getProductFromData($data= array(), $setting = array()) {

		if($data['rotator_status']) {
			$product_rotator_status = (int) $this->config->get('ocproductrotator_status');
		} else {
			$product_rotator_status = 0;
		}

		/* Get new product */
		$filter_data = array(
				'sort'  => 'p.date_added',
				'order' => 'DESC',
				'start' => 0,
				'limit' => 10
		);

		$new_results = $this->model_catalog_product->getProducts($filter_data);
		/* End */

		$results = $this->model_catalog_product->getProducts($data);
		$product_list = array();
		foreach ($results as $result) {
			if ($result['image']) {
				$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
			} else {
				$image = false;
			}
						
			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
			} else {
				$price = false;
			}
					
			if ((float)$result['special']) {
				$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
			} else {
				$special = false;
			}
			
			if ($this->config->get('config_review_status')) {
				$rating = $result['rating'];
			} else {
				$rating = false;
			}
			
			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$price_num = $this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'));
			} else {
				$price_num = false;
			}

			if ((float)$result['special']) {
				$special_num = $this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax'));
			} else {
				$special_num = false;
			}

			$is_new = false;
			if ($new_results) {
				foreach($new_results as $new_r) {
					if($result['product_id'] == $new_r['product_id']) {
						$is_new = true;
					}
				}
			}

			if($product_rotator_status == 1) {
				$product_id = $result['product_id'];
				$product_rotator_image = $this->model_catalog_ocproductrotator->getProductRotatorImage($product_id);

				if($product_rotator_image) {
					$rotator_image = $this->model_tool_image->resize($product_rotator_image, $setting['width'], $setting['height']);
				} else {
					$rotator_image = false;
				}
			} else {
				$rotator_image = false;
			}
			$data['name2'] = strlen($result['name']) > 30 ? substr($result['name'],0,30)."..." : $result['name'];

			$product_list[] = array(
				'product_id' => $result['product_id'],
                'is_new'      => $is_new,
				'thumb'   	 => $image,
				'rotator_image' => $rotator_image,
				'name'    	 => $result['name'],
				'name2'    	 => $data['name2'],
				'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 80) . '..',
				'price'   	 => $price,
				'special' 	 => $special,
				'price_num'       => $price_num,
				'special_num'     => $special_num,
				'rating'     => $rating,
				'reviews'    => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
				'href'    	 => $this->url->link('product/product', 'product_id=' . $result['product_id']),
			);
		}
		
		return $product_list;
	}
	
	public function getFirstProductFromData($data= array(), $setting = array()) {

		if($data['rotator_status']) {
			$product_rotator_status = (int) $this->config->get('ocproductrotator_status');
		} else {
			$product_rotator_status = 0;
		}

		/* Get new product */
		$filter_data = array(
				'sort'  => 'p.date_added',
				'order' => 'DESC',
				'start' => 0,
				'limit' => 10
		);

		$new_results = $this->model_catalog_product->getProducts($filter_data);
		/* End */

		$results = $this->model_catalog_product->getProducts($data);
		
		$f_product = array();
		$count = 0;
		
		foreach ($results as $result) {
			if($count > 0) break;
			
			if ($result['image']) {
				$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
			} else {
				$image = false;
			}
						
			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
			} else {
				$price = false;
			}
					
			if ((float)$result['special']) {
				$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
			} else {
				$special = false;
			}
			
			if ($this->config->get('config_review_status')) {
				$rating = $result['rating'];
			} else {
				$rating = false;
			}
			
			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$price_num = $this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'));
			} else {
				$price_num = false;
			}

			if ((float)$result['special']) {
				$special_num = $this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax'));
			} else {
				$special_num = false;
			}

			$is_new = false;
			if ($new_results) {
				foreach($new_results as $new_r) {
					if($result['product_id'] == $new_r['product_id']) {
						$is_new = true;
					}
				}
			}

			if($product_rotator_status == 1) {
				$product_id = $result['product_id'];
				$product_rotator_image = $this->model_catalog_ocproductrotator->getProductRotatorImage($product_id);

				if($product_rotator_image) {
					$rotator_image = $this->model_tool_image->resize($product_rotator_image, $setting['width'], $setting['height']);
				} else {
					$rotator_image = false;
				}
			} else {
				$rotator_image = false;
			}

			if($count == 0) {
				$f_product['product_id'] = $result['product_id'];
                $f_product['is_new']      = $is_new;
				$f_product['thumb']   	 = $image;
				$f_product['rotator_image'] = $rotator_image;
				$f_product['name']    	 = $result['name'];
				$f_product['description'] = utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..';
				$f_product['price']   	 = $price;
				$f_product['special'] 	 = $special;
				$f_product['price_num']       = $price_num;
				$f_product['special_num']     = $special_num;
				$f_product['rating']     = $rating;
				$f_product['reviews']    = sprintf($this->language->get('text_reviews'), (int)$result['reviews']);
				$f_product['href']    	 = $this->url->link('product/product', 'product_id=' . $result['product_id']);
			}
			
			$count++;
		}
		
		return $f_product;
	}
	
	public function getProductExceptFirstFromData($data, $setting) {
		$productFromData = $this->getProductFromData($data, $setting);
		$firstProduct = $this->getFirstProductFromData($data, $setting);
		$productsExceptFList = array();
		
		foreach($productFromData as $product) {
			if($product['product_id'] == $firstProduct['product_id']) continue;
			
			$productsExceptFList[] = $product;
		}
		
		return $productsExceptFList;
	}
		
	
}