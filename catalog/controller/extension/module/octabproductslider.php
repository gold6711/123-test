<?php
class ControllerExtensionModuleOctabproductslider extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/octabproductslider');

		$this->load->model('catalog/product');
		$this->load->model('catalog/ocproductrotator');
		$this->load->model('extension/module/mostviewed');
		$this->load->model('extension/module/randomproduct');
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

		
		if (file_exists('catalog/view/theme/' . $this->config->get($this->config->get('config_theme') . '_directory'). '/stylesheet/opentheme/producttab.css')) {
			$this->document->addStyle('catalog/view/theme/' . $this->config->get($this->config->get('config_theme') . '_directory'). '/stylesheet/opentheme/producttab.css');
		} else {
			$this->document->addStyle('catalog/view/theme/default/stylesheet/opentheme/producttab.css');
		}

		if (empty($setting['limit'])) {
			$setting['limit'] = 10;
		}

        $new_filter_data = array(
            'sort'  => 'p.date_added',
            'order' => 'DESC',
            'start' => 0,
            'limit' => 10
        );

        $new_results = $this->model_catalog_product->getProducts($new_filter_data);

		if(isset($setting['rotator']) && $setting['rotator']) {
			$product_rotator_status = (int) $this->config->get('ocproductrotator_status');
		} else {
			$product_rotator_status = 0;
		}
		
		$cover_image =  $this->model_tool_image->resize($setting['image'], 270, 805);

		$productTabslider = array();

		if($setting['types']) {
			foreach($setting['types'] as $type) {
				if($type == "bestseller") {
					$product_bestseller = array();

					$bestseller_products = $this->model_catalog_product->getBestSellerProducts($setting['limit']);

					if ($bestseller_products) {
						foreach ($bestseller_products as $result) {
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

                            $is_new = false;
                            if ($new_results) {
                                foreach($new_results as $new_r) {
                                    if($result['product_id'] == $new_r['product_id']) {
                                        $is_new = true;
                                    }
                                }
                            }
                            if ((float)$result['special']) {
								$price2 = $this->tax->calculate($result['price'],$result['tax_class_id'], $this->config->get('config_tax'));
								$special2 = $this->tax->calculate($result['special'],$result['tax_class_id'], $this->config->get('config_tax'));
							} else {
								$price2 = false;
								$special2 = false;
							}  
                            //adding text stock
							$results = $this->model_catalog_product->getProducts($data);
							$product_list = array();
							foreach ($results as $result) {
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
				            }
					        //end adding text stock
							$product_bestseller[] = array(
								'product_id'  => $result['product_id'],
                                'is_new' => $is_new,
								'thumb'       => $image,
								'rotator_image' => $rotator_image,
								'quantity' => $result['quantity'],
				                'stock' => $result['stock'],
				                'stock_color' => $result['stock_color'],
								'name'        => $result['name'],
								'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
								'price'       => $price,
								'special'     => $special,
								'price2'     => $price2,
                				'special2'     => $special2,
								'tax'         => $tax,
								'rating'      => $rating,
								'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id']),
							);
						}
					}

					$productTabslider[] = array(
						'id' => 'bestseller_product',
						'name' => $this->language->get('text_bestseller'),
						'productInfo' => $product_bestseller,
                        'text_empty' => $this->language->get('text_empty_bestseller')
					);
				}

				if($type == "mostviewed") {
					$product_mostviewed = array();

					$mostviewed_products = $this->model_extension_module_mostviewed->getMostViewedProducts($setting['limit']);

					if ($mostviewed_products) {
						foreach ($mostviewed_products as $result) {
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

                            $is_new = false;
                            if ($new_results) {
                                foreach($new_results as $new_r) {
                                    if($result['product_id'] == $new_r['product_id']) {
                                        $is_new = true;
                                    }
                                }
                            }
                            if ((float)$result['special']) {
								$price2 = $this->tax->calculate($result['price'],$result['tax_class_id'], $this->config->get('config_tax'));
								$special2 = $this->tax->calculate($result['special'],$result['tax_class_id'], $this->config->get('config_tax'));
							} else {
								$price2 = false;
								$special2 = false;
							}  
                            //adding text stock
							$results = $this->model_catalog_product->getProducts($data);
							$product_list = array();
							foreach ($results as $result) {
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
				            }
					        //end adding text stock
							$product_mostviewed[] = array(
								'product_id'  => $result['product_id'],
                                'is_new' => $is_new,
								'rotator_image' => $rotator_image,
								'quantity' => $result['quantity'],
				                'stock' => $result['stock'],
				                'stock_color' => $result['stock_color'],
								'thumb'       => $image,
								'name'        => $result['name'],
								'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
								'price'       => $price,
								'special'     => $special,
								'tax'         => $tax,
								'price2'     => $price2,
                				'special2'     => $special2,
								'rating'      => $rating,
								'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id']),
							);
						}
					}

					$productTabslider[] = array(
						'id' => 'mostviewed_product',
						'name' => $this->language->get('text_mostviewed'),
						'productInfo' => $product_mostviewed,
                        'text_empty' => $this->language->get('text_empty_mostviewed')
					);
				}

				if($type == "random") {
					$product_random = array();

					$products = $this->model_extension_module_randomproduct->getRandomProducts($setting['limit']);

					if($products) {
						foreach ($products as $product) {
							$product_info = $this->model_catalog_product->getProduct($product['product_id']);

							if ($product_info) {
								if ($product_info['image']) {
									$image = $this->model_tool_image->resize($product_info['image'], $setting['width'], $setting['height']);
								} else {
									$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
								}

								if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
									$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
									$price2 = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
								} else {
									$price = false;
									$price2 = false;
								}

								if ((float)$product_info['special']) {
									$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
									$special2 = $this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax'));
								} else {
									$special = false;
									$special2 = false;
								}

								if ($this->config->get('config_tax')) {
									$tax = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price'], $this->session->data['currency']);
								} else {
									$tax = false;
								}

								if ($this->config->get('config_review_status')) {
									$rating = $product_info['rating'];
								} else {
									$rating = false;
								}

								if($product_rotator_status == 1) {
									$product_id = $product_info['product_id'];
									$product_rotator_image = $this->model_catalog_ocproductrotator->getProductRotatorImage($product_id);

									if($product_rotator_image) {
										$rotator_image = $this->model_tool_image->resize($product_rotator_image, $setting['width'], $setting['height']);
									} else {
										$rotator_image = false;
									}
								} else {
									$rotator_image = false;
								}

                                $is_new = false;
                                if ($new_results) {
                                    foreach($new_results as $new_r) {
                                        if($product_info['product_id'] == $new_r['product_id']) {
                                            $is_new = true;
                                        }
                                    }
                                }

								$product_random[] = array(
									'product_id'  => $product_info['product_id'],
                                    'is_new' => $is_new,
									'thumb'       => $image,
									'rotator_image' => $rotator_image,
									'name'        => $product_info['name'],
									'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
									'price'       => $price,
									'special'     => $special,
									'price2'     => $price2,
                					'special2'     => $special2,
									'tax'         => $tax,
									'rating'      => $rating,
									'href'        => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
								);
							}
						}
					}

					$productTabslider[] = array(
						'id' => 'random_product',
						'name' => $this->language->get('text_random'),
						'productInfo' => $product_random,
                        'text_empty' => $this->language->get('text_empty_random')
					);
				}

				if($type == "special") {
					$product_special = array();

					$special_filter_data = array(
						'sort'  => 'pd.name',
						'order' => 'ASC',
						'start' => 0,
						'limit' => $setting['limit']
					);

					$results = $this->model_catalog_product->getProductSpecials($special_filter_data);

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
							if ((float)$result['special']) {
								$price2 = $this->tax->calculate($result['price'],$result['tax_class_id'], $this->config->get('config_tax'));
								$special2 = $this->tax->calculate($result['special'],$result['tax_class_id'], $this->config->get('config_tax'));
							} else {
								$price2 = false;
								$special2 = false;
							}  
							//adding text stock
							$results = $this->model_catalog_product->getProducts($data);
							$product_list = array();
							foreach ($results as $result) {
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
				            }
					        //end adding text stock
							$product_special[] = array(
								'product_id'  => $result['product_id'],
								'thumb'       => $image,
								'quantity' => $result['quantity'],
				                'stock' => $result['stock'],
				                'stock_color' => $result['stock_color'],
				                'is_new'      => $is_new,
								'rotator_image' => $rotator_image,
								'price2'     => $price2,
                				'special2'     => $special2,
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

					$productTabslider[] = array(
						'id' => 'special_product',
						'name' => $this->language->get('text_special'),
						'productInfo' => $product_special,
                        'text_empty' => $this->language->get('text_empty_special')
					);
				}

				if($type == "latest") {
					$product_latest = array();

					$latest_filter_data = array(
						'sort'  => 'p.date_added',
						'order' => 'DESC',
						'start' => 0,
						'limit' => $setting['limit']
					);

					$results = $this->model_catalog_product->getProducts($latest_filter_data);

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

							if ((float)$result['special']) {
								$price2 = $this->tax->calculate($result['price'],$result['tax_class_id'], $this->config->get('config_tax'));
								$special2 = $this->tax->calculate($result['special'],$result['tax_class_id'], $this->config->get('config_tax'));
							} else {
								$price2 = false;
								$special2 = false;
							}  
							//adding text stock
							$results = $this->model_catalog_product->getProducts($data);
							$product_list = array();
							foreach ($results as $result) {
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
				            }
					        //end adding text stock
							$product_latest[] = array(
								'product_id'  => $result['product_id'],
								'thumb'       => $image,
								'rotator_image' => $rotator_image,
								'quantity' => $result['quantity'],
				                'stock' => $result['stock'],
				                'stock_color' => $result['stock_color'],
				                'is_new'      => $is_new,
				                'price2'     => $price2,
                				'special2'     => $special2,
								'name'        => $result['name'],
								'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
								'price'       => $price,
								'special'     => $special,
								'tax'         => $tax,
								'rating'      => $rating,
								'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id']),
								'reviews' => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
							);
						}
					}

					$productTabslider[] = array(
						'id' => 'latest_product',
						'name' => $this->language->get('text_latest'),
						'productInfo' => $product_latest,
                        'text_empty' => $this->language->get('text_empty_latest')
					);
				}
			}
		}
			
		$data['config_slide'] = array(
			'items' => $setting['item'],
			'image' => $cover_image,
			'autoplay' => $setting['autoplay'],
			'f_show_nextback' => $setting['shownextback'],
			'f_show_ctr' => $setting['shownav'],
			'f_speed' => $setting['speed'],
            'f_show_label' => $setting['showlabel'],
			'f_show_price' => $setting['showprice'],
			'f_show_des' => $setting['showdes'],
			'f_show_addtocart' => $setting['showaddtocart'],
			'f_rows' => $setting['rows']
		);
			
		$data['productTabslider'] = $productTabslider;

		if ($data['productTabslider']) {
			return $this->load->view('extension/module/octabproductslider', $data);
		}
	}
		
	
}