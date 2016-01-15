<?php
class ModelShippingFlat extends Model {
	function getQuote($address) {
		$this->language->load('shipping/flat');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('flat_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if (!$this->config->get('flat_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

	  $r = $this->cart->getProducts();
$this->log->write(print_r($r,true));
		// special condition, if there is only one A* product, return true,
		// in this situation, we only charge user $50 shipping, however if they
		// have any other product ordered, the shipping is free no matter what
		//
		$bOnlyOneA = false;

		// only one product
		if( count($r) == 1 )
		{

			foreach( $r as $k => $v )
			{
			  if( isset( $v['model'] ) && substr( $v['model'],0,1 ) == 'A' )
			  {
			    $bOnlyOneA = true;
			  }
			}
		}

		if ($status) {
			$quote_data = array();

			$cost = ( $bOnlyOneA ? $this->config->get('flat_cost')/2
			          : $this->config->get('flat_cost') ) ;

			$quote_data['flat'] = array(
				'code'         => 'flat.flat',
				'title'        => $this->language->get('text_description'),
				'cost'         => ( $bOnlyOneA ? $cost/2 : $cost ),
				'tax_class_id' => $this->config->get('flat_tax_class_id'),
				'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('flat_tax_class_id'), $this->config->get('config_tax')))
			);

			$method_data = array(
				'code'       => 'flat',
				'title'      => $this->language->get('text_title'),
				'quote'      => $quote_data,
				'sort_order' => $this->config->get('flat_sort_order'),
				'error'      => false
			);
		}
$this->log->write(print_r($method_data,true));
		return $method_data;
	}
}
?>