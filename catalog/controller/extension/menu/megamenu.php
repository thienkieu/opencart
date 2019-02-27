<?php
class ControllerExtensionMenuMegamenu extends Controller {
	public function index() {
		$this->load->language('extension/menu/megamenu');
		$results = $this->model_setting_module->getModule(45);
		$base64 = base64_decode($results['tree']);
		$json = json_decode($base64, true);
		$data['all_category_width'] = 260;
		$data['tree'] = $this->buildMobileMenu($json);
		$route = isset($this->request->get['route'])? $this->request->get['route']: 'common/home';
		$data['show_default'] = $route == 'common/home' ? 'show-default' : '';
		//echo "this is megamenu";die;
		return $this->load->view('extension/menu/megamenu', $data);
	}

	private function buildMenu($menu) {
		$html = '<div class="megamenu-background"><div class="container"> <div class="menu-container"><ul class="level_0">';
		foreach ($menu as $key => $value) {
			$html .= '<li class="'. $value['css'] .'">';
			$html .= '<a href="'. $value['href'] .'">'. $value['text'] .'</a>';
			if (isset($value['nodes'])) {
				$html .= $this->buildSubMenu(null, $value['nodes'], 1);
			}
			
			$html .= '</li>';
		}
		$html .= '</ul></div></div></div>';
		return $html;
	}

	private function buildSubMenu($parent, $menu, $level) {
		$class = '';
		if ($parent != null && isset($parent['displayCarousel']) && $parent['displayCarousel'] == true) {
			$class = ' owl-carousel';
		}

		$html = '<ul class="level_'. $level.$class.'">';
		
		foreach ($menu as $key => $value) {
			$html .= '<li class="'. $value['css'] .'">';
			
			$html .= '<a href="'. $value['href'] .'">';
			if (isset($value['icon']) && !empty($value['icon'])) {
				$html .='<img class="icon" src="/image/'.$value['icon'].'"/>';
			}
			$html .= $value['text'];
			if (isset($value['image']) && !empty($value['image'])) {
				$html .='<img src="/image/'.$value['image'].'"/>';
			}
			
			if (isset($value['description']) && !empty($value['description'])) {
				$html .= $value['description'];
			}
			$html .= '</a>';
			if (isset($value['price']) && !empty($value['price'])) {
				$html .= '<span>'. $value['price'].'</span>';
			}

			if (isset($value['nodes'])) {
				$html .= $this->buildSubMenu($value, $value['nodes'], $level+1);
			}
			$html .= '</li>';
		}
		$html .= '</ul>';

		return $html;
	}


	private function buildMobileMenu($menu) {
		$html = '<div class="megamenu-mobile"> <div class="container"> <ul class="level_0">';
		foreach ($menu as $key => $value) {
			$css = isset($value['cssMobile']) ? $value['cssMobile']: '';
			$html .= '<li class="'. $css .'">';
			$style = '';
			if (isset($value['styleMobile']) && !empty($value['styleMobile'])){
				$style = $value['styleMobile'];
			}
			$html .= '<a href="'. $value['href'] .'" style="'.$style.'">'. $value['text'] .'</a>';
			if (isset($value['nodes'])) {
				$html .= '<a class="expand-button" style="background:none;"></a>';
				$html .= $this->buildMobileSubMenu(null, $value['nodes'], 1);
			}
			
			$html .= '</li>';
		}
		$html .= '</ul></div></div>';
		return $html;
	}

	private function buildMobileSubMenu($parent, $menu, $level) {
		$class = '';
		if ($parent != null && isset($parent['displayCarousel']) && $parent['displayCarousel'] == true) {
			$class = ' owl-carousel';
		}

		$html = '<ul class="level_'. $level.$class.'">';
		
		foreach ($menu as $key => $value) {
			$css = isset($value['cssMobile']) ? $value['cssMobile']: '';
			$html .= '<li class="'. $css .'">';
			$style = '';
			if (isset($value['styleMobile']) && !empty($value['styleMobile'])){
				$style = $value['styleMobile'];
			}
			$html .= '<a href="'. $value['href'] .'" style="'.$style.'">';
			if (isset($value['icon']) && !empty($value['icon'])) {
				$html .='<img class="icon" src="/image/'.$value['icon'].'"/>';
			}
			$html .= $value['text'];
			if (isset($value['image']) && !empty($value['image'])) {
				$html .='<img src="/image/'.$value['image'].'"/>';
			}
			
			if (isset($value['description']) && !empty($value['description'])) {
				$html .= $value['description'];
			}
			$html .= '</a>';
			if (isset($value['price']) && !empty($value['price'])) {
				$html .= '<span>'. $value['price'].'</span>';
			}

			if (isset($value['nodes'])) {
				$html .= '<a class="expand-button" style="background:none;"></a>';
				$html .= $this->buildMobileSubMenu($value, $value['nodes'], $level+1);
			}
			$html .= '</li>';
		}
		$html .= '</ul>';

		return $html;
	}
}