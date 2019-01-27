<?php
class ControllerExtensionMenuMegamenu extends Controller {
	public function index() {
		$this->load->language('extension/menu/megamenu');
		$data['all_category_width'] = 260;
		$route = isset($this->request->get['route'])? $this->request->get['route']: 'common/home';
		$data['show_default'] = $route == 'common/home' ? 'show-default' : '';
		//echo "this is megamenu";die;
		return $this->load->view('extension/menu/megamenu', $data);
	}

}