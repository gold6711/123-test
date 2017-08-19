<?php
class ControllerExtensionModuleOcspecialproductslider extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/ocspecialproductslider');

		$this->load->model('catalog/product');
		$this->load->model('catalog/ocproductrotator');
		$this->load->model('tool/image');

		$data = array();

		$data['heading_title'] = $this->language->get('heading_title');

		$lang_code = $this->session->data['language'];

		if(isset($setting['title']) && $setting['title']) {
			$data['title'] = $setting['title'][$lang_code]['title'];
		} else {
			$data['title'] = $this->language->get('heading_title');
		}
		
		$data['text_tax'] = $this->language->get('text_tax');
		$data['text_empty'] = $this->language->get('text_empty');
		$data['text_sale'] = $this->language->get('text_sale');
		$data['text_new'] = $this->language->get('text_new');

		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');
		$data['button_upload'] = $this->language->get('button_upload');
		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_quickview'] = $this->language->get('button_quickview');
		$data['tooltip_cart'] = $this->language->get('tooltip_cart');
		$data['tooltip_wishlist'] = $this->language->get('tooltip_wishlist');
		$data['tooltip_compare'] = $this->language->get('tooltip_compare');
		$data['tooltip_quickview'] = $this->language->get('tooltip_quickview');
		$data['data_btn_cart'] = $this->language->get('data_btn_cart');
		$data['data_btn_wishlist'] = $this->language->get('data_btn_wishlist');
		$data['data_btn_compare'] = $this->language->get('data_btn_compare');
		$data['data_btn_quickview'] = $this->language->get('data_btn_quickview');
		$data['product_btn_wishlist'] = $this->language->get('product_btn_wishlist');
		$data['product_btn_compare'] = $this->language->get('product_btn_compare');

		$data['products'] = array();

		if (empty($setting['limit'])) {
			$setting['limit'] = 10;
		}

		if(isset($setting['rotator']) && $setting['rotator']) {
			$product_rotator_status = (int) $this->config->get('ocproductrotator_status');
		} else {
			$product_rotator_status = 0;
		}

		$filter_data = array(
			'sort'  => 'pd.name',
			'order' => 'ASC',
			'start' => 0,
			'limit' => $setting['limit']
		);

		$results = $this->model_catalog_product->getProductSpecials($filter_data);

		if ($results) {
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
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

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = $result['rating'];
				} else {
					$rating = false;
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

				//adding text stock
					$this->load->language('product/product');

					if ($result['quantity'] <= 0) {
		                $result['stock'] = $result['stock_status'];
		                if($result['stock'] == 'Out Of Stock') {
		                    $result['stock_color'] = "#8b0707";
		                } elseif ($result['stock'] == 'Pre-Order') {
		                    $result['stock_color'] = "#ff9900";
		                } elseif ($result['stock'] == '2-3 Days') {
		                    $result['stock_color'] = "#e89f23";
		                } elseif ($result['stock'] == 'Available Soon') {
		                    $result['stock_color'] = "#fb5323";
		                } elseif ($result['stock'] == 'In Stock') {
		                    $result['stock_color'] = "#c5cc1d";
		                } else {
		                    $result['stock_color'] = "#ff0000";
		                }
		 
		            } elseif ($this->config->get('config_stock_display')) {
		                $result['stock'] = $result['quantity'];
		                $result['stock_color'] = "#66aa00";
		            } else {
		                $result['stock'] = $this->language->get('text_instock');

		                $result['stock_color'] = "#66aa00";
		            }
	           
		        //end adding text stock
		            
				$data['products'][] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'quantity' => $result['quantity'],
                'stock' => $result['stock'],
                'stock_color' => $result['stock_color'],
				'rotator_image' => $rotator_image,
					'name'        => $result['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'rating'      => $rating,
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
				);
			}
		}

		$data['config_slide'] = array(
			'items' => $setting['item'],
			'autoplay' => $setting['autoplay'],
			'f_show_nextback' => $setting['shownextback'],
			'f_show_ctr' => $setting['shownav'],
			'f_show_label' => $setting['showlabel'],
			'f_speed' => $setting['speed'],
			'f_show_price' => $setting['showprice'],
			'f_show_des' => $setting['showdes'],
			'f_show_addtocart' => $setting['showaddtocart'],
			'f_rows' => $setting['rows']
		);

		return $this->load->view('extension/module/ocspecialproductslider', $data);
	}
}