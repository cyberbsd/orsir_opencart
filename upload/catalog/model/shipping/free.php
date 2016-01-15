<?php
class ModelShippingFree extends Model {
	function getQuote($address) {
		$this->language->load('shipping/free');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('free_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if (!$this->config->get('free_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$r = $this->cart->getProducts();

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

		if ($this->cart->getSubTotal() < $this->config->get('free_total')
		    || $bOnlyOneA === true ) {

			$status = false;
		}

		$method_data = array();

		if ($status) {
			$quote_data = array();

			$quote_data['free'] = array(
				'code'         => 'free.free',
				'title'        => $this->language->get('text_description'),
				'cost'         => 0.00,
				'tax_class_id' => 0,
				'text'         => $this->currency->format(0.00)
			);

			$method_data = array(
				'code'       => 'free',
				'title'      => $this->language->get('text_title'),
				'quote'      => $quote_data,
				'sort_order' => $this->config->get('free_sort_order'),
				'error'      => false
			);
		}

		return $method_data;
	}
}
?>