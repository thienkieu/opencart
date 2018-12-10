<?php
class ControllerExtensionMenuMegamenu extends Controller {
	public function index() {
		$this->load->language('extension/menu/megamenu');
		$data = $this->document->getStyles();
		
		//echo "this is megamenu";die;
		return $this->load->view('extension/menu/megamenu');
	}
}