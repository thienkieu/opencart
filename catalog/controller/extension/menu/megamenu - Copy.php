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

	private function renderMenu($data){
		$html = '<ul class="un-style">';
			foreach($data as $value){
				$html += renderChildrenMenu($value);
			}
		$html += '</ul>';
	}

	private function renderChildrenMenu($data){
		$html = '<li class="all-category-item">' +
					'<a href="">' +
						'<span>'+
							'<img class="all-category-item-icon" src="https://serendipity.oc-templates.com/image/catalog/m10.png" alt="">'+
							$data['name']+
						'</span>'+
					'</a>';
					if (isset($data['children'])) 
					{					
						$html +='<div class="sub-menu full-width-sub-menu">'+
									'<div class="content">'+
										'<p class="arrow"></p>'+
										'<div class="row">'+
										foreach($data['children'] as $value){
											$html +='<div class="col-sm-3">';
											$html +=	'<ul class="un-style sub-menu-column-item">';
											$html += 	renderChildrenMenu($value);
											$html += 	'</ul>';
											$html += '</div>';
										}
						$html +=		'</div>'+
									'</div>'+
								'</div>';
					}
		$html += '</li>';
							
		return $html;
		
	}
}