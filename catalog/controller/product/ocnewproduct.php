<?php
class  ControllerProductOcnewproduct extends Controller
{
    public function index() {
        $this->load->language('product/ocnewproduct');

        $this->load->model('tool/image');

        $this->load->model('catalog/ocnewproduct');

        $newproductpage_status = $this->config->get('ocnewproductpage_status');
        $newproductpage_limit = (int) $this->config->get('ocnewproductpage_limit');
        $newproductpage_width = $this->config->get('ocnewproductpage_width');
        $newproductpage_height = $this->config->get('ocnewproductpage_height');

        /* Begin Load language */
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_empty'] = $this->language->get('text_empty');
        $data['text_quantity'] = $this->language->get('text_quantity');
        $data['text_manufacturer'] = $this->language->get('text_manufacturer');
        $data['text_model'] = $this->language->get('text_model');
        $data['text_price'] = $this->language->get('text_price');
        $data['text_tax'] = $this->language->get('text_tax');
        $data['text_points'] = $this->language->get('text_points');
        $data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
        $data['text_sort'] = $this->language->get('text_sort');
        $data['text_limit'] = $this->language->get('text_limit');
        $data['text_new'] = $this->language->get('text_new');
        $data['text_sale'] = $this->language->get('text_sale');

        $data['button_cart'] = $this->language->get('button_cart');
        $data['button_wishlist'] = $this->language->get('button_wishlist');
        $data['button_compare'] = $this->language->get('button_compare');
        $data['button_list'] = $this->language->get('button_list');
        $data['button_grid'] = $this->language->get('button_grid');
        $data['button_continue'] = $this->language->get('button_continue');
        $data['tooltip_addcart'] = $this->language->get('tooltip_addcart');
        $data['tooltip_wishlist'] = $this->language->get('tooltip_wishlist');
        $data['tooltip_compare'] = $this->language->get('tooltip_compare');
        $data['tooltip_quickview'] = $this->language->get('tooltip_quickview');

        $data['compare'] = $this->url->link('product/compare');
        /* End */

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'p.sort_order';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        if (isset($this->request->get['limit'])) {
            $limit = $this->request->get['limit'];
        } else {
            $limit = $this->config->get($this->config->get('config_theme') . '_product_limit');
        }

        /* Begin Title & Breadcrumb */
        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        if (isset($this->request->get['limit'])) {
            $url .= '&limit=' . $this->request->get['limit'];
        }

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('product/ocnewproduct', $url)
        );
        /* End */

        /* Begin get product data */
        $data['products'] = array();

        $product_total = $this->model_catalog_ocnewproduct->getTotalNewProductsPage($newproductpage_limit);
        if($product_total > $newproductpage_limit) {
            $product_total = $newproductpage_limit;
        }

        if(($page * $limit) > $product_total) {
            $countLimit = $product_total - ($page - 1) * $limit;
        } else {
            $countLimit = $limit;
        }

        $filter_data = array(
            'sort'  => $sort,
            'order' => $order,
            'start' => ($page - 1) * $limit,
            'limit' => $countLimit
        );

        $results = $this->model_catalog_ocnewproduct->getNewProductsInPage($filter_data);

        foreach ($results as $result) {
            if ($result['image']) {
                $image = $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
            } else {
                $image = $this->model_tool_image->resize('placeholder.png', $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
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
                $rating = (int)$result['rating'];
            } else {
                $rating = false;
            }
            // Product Rotator /
            $product_rotator_status = (int) $this->config->get('ocproductrotator_status');
            if($product_rotator_status == 1) {
                $this->load->model('catalog/ocproductrotator');
                $this->load->model('tool/image');

                $product_id = $result['product_id'];
                $product_rotator_image = $this->model_catalog_ocproductrotator->getProductRotatorImage($product_id);

                if($product_rotator_image) {
                    $rotator_image = $this->model_tool_image->resize($product_rotator_image, $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height')); 
                } else {
                    $rotator_image = false;
                } 
            } else {
                $rotator_image = false;    
            }
            // End Product Rotator /
            // Get new product /
            $this->load->model('catalog/product');

            $data['new_products'] = array();

            $filter_data = array(
                'sort'  => 'p.date_added',
                'order' => 'DESC',
                'start' => 0,
                'limit' => 10
            );

            $new_results = $this->model_catalog_product->getProducts($filter_data);
            // End /
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
            $data['text_sale'] = $this->language->get('text_sale');
            $data['products'][] = array(
                'product_id'  => $result['product_id'],
				'is_new' => true,
                'thumb'       => $image,
                'name'        => $result['name'],
                'quantity' => $result['quantity'],
                'stock' => $result['stock'],
                'price2'     => $price2,
                'special2'     => $special2,
                'stock_color' => $result['stock_color'],
                'rotator_image' => $rotator_image,
                'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
                'price'       => $price,
                'special'     => $special,
                'tax'         => $tax,
                'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
                'rating'      => $result['rating'],
                'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'] . $url)
            );
        }
        /* End */

        /* Begin Sort Attribute Filter */
        $url = '';

        if (isset($this->request->get['limit'])) {
            $url .= '&limit=' . $this->request->get['limit'];
        }

        $data['sorts'] = array();

        $data['sorts'][] = array(
            'text'  => $this->language->get('text_default'),
            'value' => 'p.sort_order-ASC',
            'href'  => $this->url->link('product/ocnewproduct', 'sort=p.sort_order&order=ASC' . $url)
        );

        $data['sorts'][] = array(
            'text'  => $this->language->get('text_name_asc'),
            'value' => 'pd.name-ASC',
            'href'  => $this->url->link('product/ocnewproduct', 'sort=pd.name&order=ASC' . $url)
        );

        $data['sorts'][] = array(
            'text'  => $this->language->get('text_name_desc'),
            'value' => 'pd.name-DESC',
            'href'  => $this->url->link('product/ocnewproduct', 'sort=pd.name&order=DESC' . $url)
        );

        $data['sorts'][] = array(
            'text'  => $this->language->get('text_price_asc'),
            'value' => 'p.price-ASC',
            'href'  => $this->url->link('product/ocnewproduct', 'sort=p.price&order=ASC' . $url)
        );

        $data['sorts'][] = array(
            'text'  => $this->language->get('text_price_desc'),
            'value' => 'p.price-DESC',
            'href'  => $this->url->link('product/ocnewproduct', 'sort=p.price&order=DESC' . $url)
        );

        if ($this->config->get('config_review_status')) {
            $data['sorts'][] = array(
                'text'  => $this->language->get('text_rating_desc'),
                'value' => 'rating-DESC',
                'href'  => $this->url->link('product/ocnewproduct', 'sort=rating&order=DESC' . $url)
            );

            $data['sorts'][] = array(
                'text'  => $this->language->get('text_rating_asc'),
                'value' => 'rating-ASC',
                'href'  => $this->url->link('product/ocnewproduct', 'sort=rating&order=ASC' . $url)
            );
        }

        $data['sorts'][] = array(
            'text'  => $this->language->get('text_model_asc'),
            'value' => 'p.model-ASC',
            'href'  => $this->url->link('product/ocnewproduct', 'sort=p.model&order=ASC' . $url)
        );

        $data['sorts'][] = array(
            'text'  => $this->language->get('text_model_desc'),
            'value' => 'p.model-DESC',
            'href'  => $this->url->link('product/ocnewproduct', 'sort=p.model&order=DESC' . $url)
        );
        /* End */

        /* Begin Limit Attribute Filter */
        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $data['limits'] = array();

        $limits = array_unique(array($this->config->get($this->config->get('config_theme') . '_product_limit'), 25, 50, 75, 100));

        sort($limits);

        foreach($limits as $value) {
            $data['limits'][] = array(
                'text'  => $value,
                'value' => $value,
                'href'  => $this->url->link('product/ocnewproduct', $url . '&limit=' . $value)
            );
        }
        /* End */

        /* Begin Pagination */
        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['limit'])) {
            $url .= '&limit=' . $this->request->get['limit'];
        }

        $pagination = new Pagination();
        $pagination->total = $product_total;
        $pagination->page = $page;
        $pagination->limit = $limit;
        $pagination->url = $this->url->link('product/ocnewproduct', $url . '&page={page}');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));
        /* End */

        /* Begin load data */
        $data['sort'] = $sort;
        $data['order'] = $order;
        $data['limit'] = $limit;

        $data['newproductpage_status'] = $newproductpage_status;
        $data['newproductpage_width'] = $newproductpage_width;
        $data['newproductpage_height'] = $newproductpage_height;

        $data['continue'] = $this->url->link('common/home');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');
        /* End */

        $this->response->setOutput($this->load->view('product/ocnewproduct', $data));
    }
}