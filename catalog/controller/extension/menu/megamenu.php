<?php
class ControllerExtensionMenuMegamenu extends Controller {
	public function index() {
		$this->load->language('extension/menu/megamenu');
		$results = $this->model_setting_module->getModule(45);
		$base64 = base64_decode($results['tree']);
		$json = json_decode($base64, true);
		$data['all_category_width'] = 260;
		$data['tree'] = $this->buildMenu($json);
		$route = isset($this->request->get['route'])? $this->request->get['route']: 'common/home';
		$data['show_default'] = $route == 'common/home' ? 'show-default' : '';
		//echo "this is megamenu";die;
		return $this->load->view('extension/menu/megamenu', $data);
	}

	private function buildMenu($menu) {
		$html = '<div class="container"> <div class="menu-container"><ul class="level_0">';
		foreach ($menu as $key => $value) {
			$html .= '<li class="'. $value['css'] .'">';
			$html .= '<a href="'. $value['href'] .'">'. $value['text'] .'</a>';
			if (isset($value['nodes'])) {
				$html .= $this->buildSubMenu($value['nodes'], 1);
			}
			
			$html .= '</li>';
		}
		$html .= '</ul></div></div>';
		return $html;
	}

	private function buildSubMenu($menu, $level) {
		$html = '<ul class="level_'. $level .'">';
		
		foreach ($menu as $key => $value) {
			
			$html .= '<li class="'. $value['css'] .'">';
			$html .= '<a href="'. $value['href'] .'">'. $value['text'] .'</a>';
			if (isset($value['nodes'])) {
				$html .= $this->buildSubMenu($value['nodes'], $level+1);
			}
			$html .= '</li>';
		}
		$html .= '</ul>';

		return $html;
	}
}