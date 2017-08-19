<?php  
class ControllerExtensionModuleOcblog extends Controller
{
	public function index($setting) {
		$this->load->model('blog/article');
        $this->load->language('blog/blog');
		$data['text_post_by'] = $this->language->get('text_post_by');
		$data['text_module_des'] = $this->language->get('text_module_des');
		$data['text_module_des2'] = $this->language->get('text_module_des2');

		if (isset($setting['name'])) {
			$data['text_headingtitle'] = $setting['name'];
		} else {
			$data['text_headingtitle'] = $this->language->get('text_headingtitle');
		}

		$lang_code = $this->session->data['language'];

		if(isset($setting['title']) && $setting['title']) {
			$data['title'] = $setting['title'][$lang_code]['title'];
		} else {
			$data['title'] = $this->language->get('text_headingtitle');
		}

		if (isset($setting['limit'])) {
			$limit = $setting['limit'];
		} else {
			$limit = 10;
		}

		if (isset($setting['rows'])) {
			$rows = $setting['rows'];
		} else {
			$rows = 1;
		}

		if (isset($setting['items'])) {
			$items = $setting['items'];
		} else {
			$items = 4;
		}

		if (isset($setting['speed'])) {
			$speed = $setting['speed'];
		} else {
			$speed = 3000;
		}

		if (isset($setting['auto']) && $setting['auto']) {
			$auto = true;
		} else {
			$auto = false;
		}

		if (isset($setting['navigation']) && $setting['navigation']) {
			$navigation = true;
		} else {
			$navigation = false;
		}

		if (isset($setting['pagination']) && $setting['pagination']) {
			$pagination = true;
		} else {
			$pagination = false;
		}

		$data['articles'] = array();

		$filter_data = array(
			'start'              => 0,
			'limit'              => $limit
		);

		$results = $this->model_blog_article->getArticlesByList($filter_data, $setting['list']);

		$this->load->model('tool/image');
		
		$f_article = array();
		$f_count = 0;
		
		if ($results) {
			foreach ($results as $result) {
				if($f_count == 0) {
					$f_article['article_id'] = $result['article_id'];
					$f_article['name'] = $result['name'];
					$f_article['author'] = $result['author'];
					$f_article['image'] = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
					$f_article['date_added'] = date('d', strtotime($result['date_added']));
					$f_article['date_added2'] = date('M', strtotime($result['date_added']));
					$f_article['intro_text'] = html_entity_decode($result['intro_text'], ENT_QUOTES, 'UTF-8');
					$f_article['href'] = $this->url->link('blog/article', 'article_id=' . $result['article_id']);
				}
				$data['articles'][] = array(
					'article_id'  => $result['article_id'],
					'name'        => $result['name'],
					'author'	  => $result['author'],
					'image'		  => $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']),
					'date_added'  => date('d', strtotime($result['date_added'])),
					'date_added2'  => date('M', strtotime($result['date_added'])),
					'intro_text' => html_entity_decode($result['intro_text'], ENT_QUOTES, 'UTF-8'),
					'href'        => $this->url->link('blog/article', 'article_id=' . $result['article_id'])
				);
				$f_count++;
			}			
			
			$articlesExceptFirst = array();

			foreach ($results as $result) {
				if($result['article_id'] != $f_article['article_id']) {
					$articlesExceptFirst[] = $result;
				}
			}
			
			foreach ($articlesExceptFirst as $result) {
				$data['articles_excpt_first'][] = array(
						'article_id'  => $result['article_id'],
						'name'        => $result['name'],
						'author'	  => $result['author'],
						'image'		  => $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']),
						'date_added'  => date('d', strtotime($result['date_added'])),
						'date_added2'  => date('M', strtotime($result['date_added'])),
						'intro_text' => html_entity_decode($result['intro_text'], ENT_QUOTES, 'UTF-8'),
						'href'        => $this->url->link('blog/article', 'article_id=' . $result['article_id'])
				);
			}
			
			$data['farticle'] = $f_article;
		}		

		$data['slide'] = array(
			'auto' => $auto,
			'rows' => $rows,
			'navigation' => $navigation,
			'pagination' => $pagination,
			'speed' => $speed,
			'items' => $items
		);

		$data['button_read_more'] = $this->language->get('button_read_more');
		$data['text_empty'] = $this->language->get('text_empty');

		return $this->load->view('blog/blog_home.tpl', $data);
    }
}