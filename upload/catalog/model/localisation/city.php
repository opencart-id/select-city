<?php
class ModelLocalisationCity extends Model {
    public function getCity($city_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "city WHERE city_id = '" . (int)$city_id . "'");

		return $query->row;
    }

    public function getCities($data = array()) {
        $city_data = $this->cache->get('city.status');

		if (!$city_data) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "city WHERE status = '1' ORDER BY name ASC");

			$city_data = $query->rows;

			$this->cache->set('city.status', $city_data);
		}

		return $city_data;
    }

    public function getCitiesByZoneId($zone_id) {
		$city_data = $this->cache->get('city.' . (int)$zone_id);

		if (!$city_data) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "city WHERE zone_id = '" . (int)$zone_id . "' AND status = '1' ORDER BY name");

			$city_data = $query->rows;

			$this->cache->set('city.' . (int)$zone_id, $city_data);
		}

		return $city_data;
	}
}
?>