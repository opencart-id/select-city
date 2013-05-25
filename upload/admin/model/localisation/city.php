<?php
class ModelLocalisationCity extends Model {
	public function addCity($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "city SET name = '" . $this->db->escape($data['name']) . "', zone_id = '" . (int)$data['zone_id'] . "', status = '" . (int)$data['status'] . "'");

		$this->cache->delete('city');
	}

	public function editCity($city_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "city SET name = '" . $this->db->escape($data['name']) . "', zone_id = '" . (int)$data['zone_id'] . "', status = '" . (int)$data['status'] . "' WHERE city_id = '" . (int)$city_id . "'");

		$this->cache->delete('city');
	}

	public function deleteCity($city_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "city WHERE city_id = '" . (int)$city_id . "'");

		$this->cache->delete('city');
	}

	public function getCity($city_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "city WHERE city_id = '" . (int)$city_id . "'");

		return $query->row;
	}

	public function getCities($data = array()) {
		$city_data = '';

		if ($data) {
			$sql = "SELECT c.city_id AS city_id, c.zone_id AS zone_id, c.name AS name, c.status AS status, (SELECT name FROM " . DB_PREFIX . "zone z WHERE z.zone_id = c.zone_id AND z.status = '1') AS zone FROM " . DB_PREFIX . "city c";

			$sort_data = array(
				'name',
				'zone'
			);

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY zone";
			}

			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}

			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}

				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}

			$query = $this->db->query($sql);

			return $query->rows;
		} else {
			$city_data = $this->cache->get('city');

			if (!$city_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "city ORDER BY name ASC");

				$city_data = $query->rows;

				$this->cache->set('city', $city_data);
			}

			return $city_data;
		}
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

	public function getTotalCities() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "city");

		return $query->row['total'];
	}

	public function checkDatabase() {
		// Cities
		$query = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "city'");

		if (!$query->num_rows) {
			$this->db->query("CREATE TABLE `" . DB_PREFIX . "city` (`city_id` int(11) NOT NULL AUTO_INCREMENT, `zone_id` int(11) NOT NULL, `name` varchar(128) NOT NULL, `status` tinyint(1) NOT NULL DEFAULT '1', `sort_order` int(3) NOT NULL DEFAULT '0', PRIMARY KEY (`city_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");

			$this->db->query(
				"INSERT INTO `" . DB_PREFIX . "city` (`zone_id`, `name`, `status`) VALUES
				(1507, 'Kab. Aceh Barat', 1),
				(1507, 'Kab. Aceh Barat Daya', 1),
				(1507, 'Kab. Aceh Besar', 1),
				(1507, 'Kab. Aceh Jaya', 1),
				(1507, 'Kab. Aceh Selatan', 1),
				(1507, 'Kab. Aceh Singkil', 1),
				(1507, 'Kab. Aceh Tamiang', 1),
				(1507, 'Kab. Aceh Tengah', 1),
				(1507, 'Kab. Aceh Tenggara', 1),
				(1507, 'Kab. Aceh Timur', 1),
				(1507, 'Kab. Aceh Utara', 1),
				(1507, 'Kab. Bener Meriah', 1),
				(1507, 'Kab. Bireun', 1),
				(1507, 'Kab. Gayo Lues', 1),
				(1507, 'Kab. Nagan Raya', 1),
				(1507, 'Kab. Pidie', 1),
				(1507, 'Kab. Pidie Jaya', 1),
				(1507, 'Kab. Simeulue', 1),
				(1507, 'Banda Aceh', 1),
				(1507, 'Langsa', 1),
				(1507, 'Lhokseumawe', 1),
				(1507, 'Sabang', 1),
				(1507, 'Subulussalam', 1),
				(1508, 'Kab. Badung', 1),
				(1508, 'Kab. Bangli', 1),
				(1508, 'Kab. Buleleng', 1),
				(1508, 'Kab. Gianyar', 1),
				(1508, 'Kab. Jembrana', 1),
				(1508, 'Kab. Karangasem', 1),
				(1508, 'Kab. Klungkung', 1),
				(1508, 'Kab. Tabanan', 1),
				(1508, 'Denpasar', 1),
				(1509, 'Kab. Lebak', 1),
				(1509, 'Kab. Pandeglang', 1),
				(1509, 'Kab. Serang', 1),
				(1509, 'Kab. Tangerang', 1),
				(1509, 'Cilegon', 1),
				(1509, 'Serang', 1),
				(1509, 'Tangerang', 1),
				(1509, 'Tangerang Selatan', 1),
				(1510, 'Kab. Bengkulu Selatan', 1),
				(1510, 'Kab. Bengkulu Tengah', 1),
				(1510, 'Kab. Bengkulu Utara', 1),
				(1510, 'Kab. Kaur', 1),
				(1510, 'Kab. Kepahiang', 1),
				(1510, 'Kab. Lebong', 1),
				(1510, 'Kab. Muko-Muko', 1),
				(1510, 'Kab. Rejang Lebong', 1),
				(1510, 'Kab. Seluma', 1),
				(1510, 'Bengkulu', 1),
				(1512, 'Kab. Boalemo', 1),
				(1512, 'Kab. Bone Bolango', 1),
				(1512, 'Kab. Gorontalo', 1),
				(1512, 'Kab. Gorontalo Utara', 1),
				(1512, 'Kab. Pohuwato', 1),
				(1512, 'Gorontalo', 1),
				(1513, 'Kab. Kepulauan Seribu', 1),
				(1513, 'Jakarta Barat', 1),
				(1513, 'Jakarta Selatan', 1),
				(1513, 'Jakarta Pusat', 1),
				(1513, 'Jakarta Utara', 1),
				(1513, 'Jakarta Timur', 1),
				(1514, 'Kab. Batanghari', 1),
				(1514, 'Kab. Bungo', 1),
				(1514, 'Kab. Kerinci', 1),
				(1514, 'Kab. Merangin', 1),
				(1514, 'Kab. Muaro Jambi', 1),
				(1514, 'Kab. Sarolangun', 1),
				(1514, 'Kab. Tanjung Jabung Timur', 1),
				(1514, 'Kab. Tanjung Jabung Barat', 1),
				(1514, 'Kab. Tebo', 1),
				(1514, 'Jambi', 1),
				(1514, 'Sungai Penuh', 1),
				(1515, 'Kab. Bandung', 1),
				(1515, 'Kab. Bandung Barat', 1),
				(1515, 'Kab. Bekasi', 1),
				(1515, 'Kab. Bogor', 1),
				(1515, 'Kab. Ciamis', 1),
				(1515, 'Kab. Cianjur', 1),
				(1515, 'Kab. Cirebon', 1),
				(1515, 'Kab. Garut', 1),
				(1515, 'Kab. Indramayu', 1),
				(1515, 'Kab. Karawang', 1),
				(1515, 'Kab. Kuningan', 1),
				(1515, 'Kab. Majalengka', 1),
				(1515, 'Kab. Pangandaran', 1),
				(1515, 'Kab. Purwakarta', 1),
				(1515, 'Kab. Subang', 1),
				(1515, 'Kab. Sukabumi', 1),
				(1515, 'Kab. Sumedang', 1),
				(1515, 'Kab. Tasikmalaya', 1),
				(1515, 'Bandung', 1),
				(1515, 'Banjar', 1),
				(1515, 'Bekasi', 1),
				(1515, 'Bogor', 1),
				(1515, 'Cimahi', 1),
				(1515, 'Cirebon', 1),
				(1515, 'Depok', 1),
				(1515, 'Sukabumi', 1),
				(1515, 'Tasikmalaya', 1),
				(1516, 'Kab. Banjarnegara', 1),
				(1516, 'Kab. Banyumas', 1),
				(1516, 'Kab. Batang', 1),
				(1516, 'Kab. Blora', 1),
				(1516, 'Kab. Boyolali', 1),
				(1516, 'Kab. Brebes', 1),
				(1516, 'Kab. Cilacap', 1),
				(1516, 'Kab. Demak', 1),
				(1516, 'Kab. Grobogan', 1),
				(1516, 'Kab. Jepara', 1),
				(1516, 'Kab. Karanganyar', 1),
				(1516, 'Kab. Kebumen', 1),
				(1516, 'Kab. Kendal', 1),
				(1516, 'Kab. Klaten', 1),
				(1516, 'Kab. Kudus', 1),
				(1516, 'Kab. Magelang', 1),
				(1516, 'Kab. Pati', 1),
				(1516, 'Kab. Pekalongan', 1),
				(1516, 'Kab. Pemalang', 1),
				(1516, 'Kab. Purbalingga', 1),
				(1516, 'Kab. Purworejo', 1),
				(1516, 'Kab. Rembang', 1),
				(1516, 'Kab. Semarang', 1),
				(1516, 'Kab. Sragen', 1),
				(1516, 'Kab. Sukoharjo', 1),
				(1516, 'Kab. Tegal', 1),
				(1516, 'Kab. Temanggung', 1),
				(1516, 'Kab. Wonogiri', 1),
				(1516, 'Kab. Wonosobo', 1),
				(1516, 'Magelang', 1),
				(1516, 'Surakarta', 1),
				(1516, 'Salatiga', 1),
				(1516, 'Semarang', 1),
				(1516, 'Pekalongan', 1),
				(1516, 'Tegal', 1),
				(1517, 'Kab. Bangkalan', 1),
				(1517, 'Kab. Banyuwangi', 1),
				(1517, 'Kab. Blitar', 1),
				(1517, 'Kab. Bojonegoro', 1),
				(1517, 'Kab. Bondowoso', 1),
				(1517, 'Kab. Gresik', 1),
				(1517, 'Kab. Jember', 1),
				(1517, 'Kab. Jombang', 1),
				(1517, 'Kab. Kediri', 1),
				(1517, 'Kab. Lamongan', 1),
				(1517, 'Kab. Lumajang', 1),
				(1517, 'Kab. Madiun', 1),
				(1517, 'Kab. Magetan', 1),
				(1517, 'Kab. Malang', 1),
				(1517, 'Kab. Mojokerto', 1),
				(1517, 'Kab. Nganjuk', 1),
				(1517, 'Kab. Ngawi', 1),
				(1517, 'Kab. Pacitan', 1),
				(1517, 'Kab. Pamekasan', 1),
				(1517, 'Kab. Pasuruan', 1),
				(1517, 'Kab. Ponorogo', 1),
				(1517, 'Kab. Probolinggo', 1),
				(1517, 'Kab. Sampang', 1),
				(1517, 'Kab. Sidoarjo', 1),
				(1517, 'Kab. Situbondo', 1),
				(1517, 'Kab. Sumenep', 1),
				(1517, 'Kab. Trenggalek', 1),
				(1517, 'Kab. Tuban', 1),
				(1517, 'Kab. Tulungagung', 1),
				(1517, 'Batu', 1),
				(1517, 'Blitar', 1),
				(1517, 'Kediri', 1),
				(1517, 'Madiun', 1),
				(1517, 'Malang', 1),
				(1517, 'Mojokerto', 1),
				(1517, 'Pasuruan', 1),
				(1517, 'Probolinggo', 1),
				(1517, 'Surabaya', 1),
				(1518, 'Kab. Bengkayang', 1),
				(1518, 'Kab. Kapuas Hulu', 1),
				(1518, 'Kab. Ketapang', 1),
				(1518, 'Kab. Kayong Utara', 1),
				(1518, 'Kab. Landak', 1),
				(1518, 'Kab. Melawi', 1),
				(1518, 'Kab. Pontianak', 1),
				(1518, 'Kab. Sambas', 1),
				(1518, 'Kab. Sanggau', 1),
				(1518, 'Kab. Sekadau', 1),
				(1518, 'Kab. Sintang', 1),
				(1518, 'Kab. Kubu Raya', 1),
				(1518, 'Pontianak', 1),
				(1518, 'Singkawang', 1),
				(1519, 'Kab. Balangan', 1),
				(1519, 'Kab. Banjar', 1),
				(1519, 'Kab. Barito Kuala', 1),
				(1519, 'Kab. Hulu Sungai Selatan', 1),
				(1519, 'Kab. Hulu Sungai Tengah', 1),
				(1519, 'Kab. Hulu Sungai Utara', 1),
				(1519, 'Kab. Kotabaru', 1),
				(1519, 'Kab. Tanah Laut', 1),
				(1519, 'Kab. Tabalong', 1),
				(1519, 'Kab. Tanah Bumbu', 1),
				(1519, 'Kab. Tapin', 1),
				(1519, 'Banjar Baru', 1),
				(1519, 'Banjarmasin', 1),
				(1520, 'Kab. Barito Selatan', 1),
				(1520, 'Kab. Barito Timur', 1),
				(1520, 'Kab. Barito Utara', 1),
				(1520, 'Kab. Gunung Mas', 1),
				(1520, 'Kab. Kapuas', 1),
				(1520, 'Kab. Katingan', 1),
				(1520, 'Kab. Kotawaringin Barat', 1),
				(1520, 'Kab. Kotawaringin Timur', 1),
				(1520, 'Kab. Lamandau', 1),
				(1520, 'Kab. Murung Raya', 1),
				(1520, 'Kab. Pulang Pisau', 1),
				(1520, 'Kab. Sukamara', 1),
				(1520, 'Kab. Seruyan', 1),
				(1520, 'Palangkaraya', 1),
				(1521, 'Kab. Berau', 1),
				(1521, 'Kab. Kutai Barat', 1),
				(1521, 'Kab. Kutai Kertanegara', 1),
				(1521, 'Kab. Kutai Timur', 1),
				(1521, 'Kab. Mahakam Ulu', 1),
				(1521, 'Kab. Pasir', 1),
				(1521, 'Kab. Penajam Paser Utara', 1),
				(1521, 'Balikpapan', 1),
				(1521, 'Bontang', 1),
				(1521, 'Samarinda', 1),
				(1522, 'Kab. Bangka', 1),
				(1522, 'Kab. Bangka Barat', 1),
				(1522, 'Kab. Bangka Tengah', 1),
				(1522, 'Kab. Bangka Selatan', 1),
				(1522, 'Kab. Belitung', 1),
				(1522, 'Kab. Belitung Timur', 1),
				(1522, 'Pangkalpinang', 1),
				(1523, 'Kab. Lampung Barat', 1),
				(1523, 'Kab. Lampung Selatan', 1),
				(1523, 'Kab. Lampung Tengah', 1),
				(1523, 'Kab. Lampung Timur', 1),
				(1523, 'Kab. Lampung Utara', 1),
				(1523, 'Kab. Way Kanan', 1),
				(1523, 'Kab. Tanggamus', 1),
				(1523, 'Kab. Tulang Bawang', 1),
				(1523, 'Kab. Pesawaran', 1),
				(1523, 'Kab. Pesisir Barat', 1),
				(1523, 'Kab. Pringsewu', 1),
				(1523, 'Kab. Mesuji', 1),
				(1523, 'Kab. Tulang Bawang Barat', 1),
				(1523, 'Bandarlampung', 1),
				(1523, 'Metro', 1),
				(1524, 'Kab. Buru', 1),
				(1524, 'Kab. Kepulauan Aru', 1),
				(1524, 'Kab. Maluku Tengah', 1),
				(1524, 'Kab. Maluku Tenggara', 1),
				(1524, 'Kab. Maluku Tenggara Barat', 1),
				(1524, 'Kab. Maluku Barat Daya', 1),
				(1524, 'Kab. Buru Selatan', 1),
				(1524, 'Kab. Seram Bagian Barat', 1),
				(1524, 'Kab. Seram Bagian Timur', 1),
				(1524, 'Ambon', 1),
				(1524, 'Tual', 1),
				(1525, 'Kab. Halmahera Barat', 1),
				(1525, 'Kab. Halmahera Selatan', 1),
				(1525, 'Kab. Halmahera Tengah', 1),
				(1525, 'Kab. Halmahera Timur', 1),
				(1525, 'Kab. Halmahera Utara', 1),
				(1525, 'Kab. Kepulauan Sula', 1),
				(1525, 'Kab. Morotai', 1),
				(1525, 'Kab. Pulau Taliabu', 1),
				(1525, 'Ternate', 1),
				(1525, 'Tidore Kepulauan', 1),
				(1526, 'Kab. Bima', 1),
				(1526, 'Kab. Dompu', 1),
				(1526, 'Kab. Lombok Barat', 1),
				(1526, 'Kab. Lombok Tengah', 1),
				(1526, 'Kab. Lombok Timur', 1),
				(1526, 'Kab. Lombok Utara', 1),
				(1526, 'Kab. Sumbawa', 1),
				(1526, 'Kab. Sumbawa Barat', 1),
				(1526, 'Mataram', 1),
				(1526, 'Bima', 1),
				(1527, 'Kab. Alor', 1),
				(1527, 'Kab. Belu', 1),
				(1527, 'Kab. Ende', 1),
				(1527, 'Kab. Flores Timur', 1),
				(1527, 'Kab. Kupang', 1),
				(1527, 'Kab. Lembata', 1),
				(1527, 'Kab. Malaka', 1),
				(1527, 'Kab. Manggarai', 1),
				(1527, 'Kab. Manggarai Barat', 1),
				(1527, 'Kab. Manggarai Timur', 1),
				(1527, 'Kab. Nagekeo', 1),
				(1527, 'Kab. Ngada', 1),
				(1527, 'Kab. Rote Ndao', 1),
				(1527, 'Kab. Sikka', 1),
				(1527, 'Kab. Sumba Barat', 1),
				(1527, 'Kab. Sumba Barat Daya', 1),
				(1527, 'Kab. Sumba Tengah', 1),
				(1527, 'Kab. Sumba Timur', 1),
				(1527, 'Kab. Timor Tengah Selatan', 1),
				(1527, 'Kab. Timor Tengah Utara', 1),
				(1527, 'Kab. Sabu Raijua', 0),
				(1527, 'Kupang', 1),
				(1528, 'Kab. Asmat', 1),
				(1528, 'Kab. Biak Numfor', 1),
				(1528, 'Kab. Boven Digoel', 1),
				(1528, 'Kab. Jayapura', 1),
				(1528, 'Kab. Jayawijaya', 1),
				(1528, 'Kab. Keerom', 1),
				(1528, 'Kab. Mappi', 1),
				(1528, 'Kab. Merauke', 1),
				(1528, 'Kab. Mimika', 1),
				(1528, 'Kab. Nabire', 1),
				(1528, 'Kab. Paniai', 1),
				(1528, 'Kab. Pegunungan Bintang', 1),
				(1528, 'Kab. Puncak Jaya', 1),
				(1528, 'Kab. Sarmi', 1),
				(1528, 'Kab. Supiori', 1),
				(1528, 'Kab. Tolikara', 1),
				(1528, 'Kab. Waropen', 1),
				(1528, 'Kab. Yahukimo', 1),
				(1528, 'Kab. Yapen Waropen', 1),
				(1528, 'Kab. Memberamo Raya', 1),
				(1528, 'Kab. Memberamo Tengah', 1),
				(1528, 'Kab. Yalimo', 1),
				(1528, 'Kab. Lanny Jaya', 1),
				(1528, 'Kab. Nduga', 1),
				(1528, 'Kab. Puncak', 1),
				(1528, 'Kab. Dogiyai', 1),
				(1528, 'Kab. Deiyai', 0),
				(1528, 'Kab. Intan Jaya', 0),
				(1528, 'Jayapura', 1),
				(1529, 'Kab. Bengkalis', 1),
				(1529, 'Kab. Indragiri Hilir', 1),
				(1529, 'Kab. Indragiri Hulu', 1),
				(1529, 'Kab. Kampar', 1),
				(1529, 'Kab. Kepulauan Meranti', 0),
				(1529, 'Kab. Kuantan Singingi', 1),
				(1529, 'Kab. Pelalawan', 1),
				(1529, 'Kab. Rokan Hulu', 1),
				(1529, 'Kab. Rokan Hilir', 1),
				(1529, 'Kab. Siak', 1),
				(1529, 'Dumai', 1),
				(1529, 'Pekanbaru', 1),
				(1530, 'Kab. Bantaeng', 1),
				(1530, 'Kab. Barru', 1),
				(1530, 'Kab. Bone', 1),
				(1530, 'Kab. Bulukumba', 1),
				(1530, 'Kab. Enrekang', 1),
				(1530, 'Kab. Gowa', 1),
				(1530, 'Kab. Jeneponto', 1),
				(1530, 'Kab. Luwu', 1),
				(1530, 'Kab. Luwu Timur', 1),
				(1530, 'Kab. Luwu Utara', 1),
				(1530, 'Kab. Maros', 1),
				(1530, 'Kab. Pangkajene Kepulauan (Pangkep)', 1),
				(1530, 'Kab. Penukai Abab Lematang Ilir', 1),
				(1530, 'Kab. Pinrang', 1),
				(1530, 'Kab. Selayar', 1),
				(1530, 'Kab. Sinjai', 1),
				(1530, 'Kab. Sidenreng Rappang', 1),
				(1530, 'Kab. Soppeng', 1),
				(1530, 'Kab. Takalar', 1),
				(1530, 'Kab. Tanatoraja', 1),
				(1530, 'Kab. Toraja Utara', 1),
				(1530, 'Kab. Wajo', 1),
				(1530, 'Makassar', 1),
				(1530, 'Palopo', 1),
				(1530, 'Pare-Pare', 1),
				(1531, 'Kab. Banggai', 1),
				(1531, 'Kab. Banggai Kepulauan', 1),
				(1531, 'Kab. Banggai Laut', 1),
				(1531, 'Kab. Buol', 1),
				(1531, 'Kab. Donggala', 1),
				(1531, 'Kab. Morowali', 1),
				(1531, 'Kab. Parigi Mountong', 1),
				(1531, 'Kab. Poso', 1),
				(1531, 'Kab. Tojo Una-Una', 1),
				(1531, 'Kab. Toli-Toli', 1),
				(1531, 'Kab. Sigi', 1),
				(1531, 'Palu', 1),
				(1532, 'Kab. Bombana', 1),
				(1532, 'Kab. Buton', 1),
				(1532, 'Kab. Buton Utara', 1),
				(1532, 'Kab. Kolaka', 1),
				(1532, 'Kab. Kolaka Utara', 1),
				(1532, 'Kab. Kolaka Timur', 1),
				(1532, 'Kab. Kendari (Kab. Konawe)', 1),
				(1532, 'Kab. Konawe Utara', 1),
				(1532, 'Kab. Konawe Selatan', 1),
				(1532, 'Kab. Muna', 1),
				(1532, 'Kab. Wakatobi', 1),
				(1532, 'Bau-Bau', 1),
				(1532, 'Kendari', 1),
				(1533, 'Kab. Bolaangmongondow', 1),
				(1533, 'Kab. Bolaangmongondow Utara', 1),
				(1533, 'Kab. Bolaangmongondow Timur', 1),
				(1533, 'Kab. Bolaangmongondow Selatan', 1),
				(1533, 'Kab. Sangihe Talaud', 1),
				(1533, 'Kab. Kepulauan Talaud', 1),
				(1533, 'Kab. Kepulauan Sitaro', 1),
				(1533, 'Kab. Minahasa', 1),
				(1533, 'Kab. Minahasa Utara', 1),
				(1533, 'Kab. Minahasa Selatan', 1),
				(1533, 'Kab. Mitra (Minahasa Tenggara)', 1),
				(1533, 'Bitung', 1),
				(1533, 'Manado', 1),
				(1533, 'Tomohon', 1),
				(1533, 'Kotamobagu', 1),
				(1534, 'Kab. Agam', 1),
				(1534, 'Kab. Dharmasraya', 1),
				(1534, 'Kab. Limapuluhkota', 1),
				(1534, 'Kab. Kepulauan Mentawai', 1),
				(1534, 'Kab. Padang Pariaman', 1),
				(1534, 'Kab. Pasaman', 1),
				(1534, 'Kab. Pasaman Barat', 1),
				(1534, 'Kab. Pesisir Selatan', 1),
				(1534, 'Kab. Sawahlunto Sijunjung', 1),
				(1534, 'Kab. Solok', 1),
				(1534, 'Kab. Solok Selatan', 1),
				(1534, 'Kab. Tanah Datar', 1),
				(1534, 'Bukit Tinggi', 1),
				(1534, 'Padang', 1),
				(1534, 'Padang Panjang', 1),
				(1534, 'Pariaman', 1),
				(1534, 'Payakumbuh', 1),
				(1534, 'Sawahlunto', 1),
				(1534, 'Solok', 1),
				(1535, 'Kab. Banyuasin', 1),
				(1535, 'Kab. Empat Lawang', 1),
				(1535, 'Kab. Lahat', 1),
				(1535, 'Kab. Muara Enim', 1),
				(1535, 'Kab. Musi Banyuasin', 1),
				(1535, 'Kab. Musi Rawas', 1),
				(1535, 'Kab. Ogan Ilir', 1),
				(1535, 'Kab. Ogan Komering Ilir', 1),
				(1535, 'Kab. Ogan Komering Ulu', 1),
				(1535, 'Kab. Ogan Komering Ulu Timur', 1),
				(1535, 'Kab. Ogan Komering Ulu Selatan', 1),
				(1535, 'Lubuk Linggau', 1),
				(1535, 'Pagar Alam', 1),
				(1535, 'Palembang', 1),
				(1535, 'Prabumulih', 1),
				(1536, 'Kab. Asahan', 1),
				(1536, 'Kab. Batubara', 1),
				(1536, 'Kab. Dairi', 1),
				(1536, 'Kab. Deliserdang', 1),
				(1536, 'Kab. Humbang Hasudutan', 1),
				(1536, 'Kab. Karo', 1),
				(1536, 'Kab. Labuhanbatu', 1),
				(1536, 'Kab. Labuhanbatu Utara', 1),
				(1536, 'Kab. Labuhanbatu Selatan', 1),
				(1536, 'Kab. Langkat', 1),
				(1536, 'Kab. Mandailing Natal', 1),
				(1536, 'Kab. Nias', 1),
				(1536, 'Kab. Nias Selatan', 1),
				(1536, 'Kab. Nias Barat', 0),
				(1536, 'Kab. Nias Utara', 0),
				(1536, 'Kab. Gunung Sitoli', 0),
				(1536, 'Kab. Padang Lawas', 1),
				(1536, 'Kab. Padang Lawas Utara', 1),
				(1536, 'Kab. Pakpak Bharat', 1),
				(1536, 'Kab. Samosir', 1),
				(1536, 'Kab. Serdang Bedagai', 1),
				(1536, 'Kab. Simalungun', 1),
				(1536, 'Kab. Tapanuli Selatan', 1),
				(1536, 'Kab. Tapanuli Tengah', 1),
				(1536, 'Kab. Tapanuli Utara', 1),
				(1536, 'Kab. Toba Samosir', 1),
				(1536, 'Binjai', 1),
				(1536, 'Medan', 1),
				(1536, 'Padang Sidempuan', 1),
				(1536, 'Pematang Siantar', 1),
				(1536, 'Sibolga', 1),
				(1536, 'Tanjung Balai', 1),
				(1536, 'Tebing Tinggi', 1),
				(1537, 'Kab. Bantul', 1),
				(1537, 'Kab. Gunung Kidul', 1),
				(1537, 'Kab. Kulon Progo', 1),
				(1537, 'Kab. Sleman', 1),
				(1537, 'Yogyakarta', 1);"
			);
		}

		// Kepulauan Riau zone
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE name = 'Kepulauan Riau'");

		if ($query->num_rows) {
			$kep_riau_id = $query->row['zone_id'];
		} else {
			$this->db->query("INSERT INTO " . DB_PREFIX . "zone SET status = '1', name = 'Kepulauan Riau', code = 'KR', country_id = '100'");

			$kep_riau_id = $this->db->getLastId();
		}

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "city` WHERE zone_id = '" . (int)$kep_riau_id . "'");

		if (!$query->num_rows) {
			$this->db->query(
				"INSERT INTO `" . DB_PREFIX . "city` (`zone_id`, `name`, `status`) VALUES
				(" . $kep_riau_id . ", 'Kab. Karimun', 1),
				(" . $kep_riau_id . ", 'Kab. Bintan (Kep. Riau)', 1),
				(" . $kep_riau_id . ", 'Kab. Lingga', 1),
				(" . $kep_riau_id . ", 'Kab. Natuna', 1),
				(" . $kep_riau_id . ", 'Kab. Kepulauan Anambas', 1),
				(" . $kep_riau_id . ", 'Tanjungpinang', 1),
				(" . $kep_riau_id . ", 'Batam', 1);"
			);
		}

		// Papua Barat zone
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE name = 'Papua Barat'");

		if ($query->num_rows) {
			$papua_barat_id = $query->row['zone_id'];
		} else {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "zone` SET status = '1', name = 'Papua Barat', code = 'PB', country_id = '100'");

			$papua_barat_id = $this->db->getLastId();
		}

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "city` WHERE zone_id = '" . (int)$papua_barat_id . "'");

		if (!$query->num_rows) {
			$this->db->query(
				"INSERT INTO `" . DB_PREFIX . "city` (`zone_id`, `name`, `status`) VALUES
				(" . $papua_barat_id . ", 'Kab. Fak-Fak', 1),
				(" . $papua_barat_id . ", 'Kab. Kaimana', 1),
				(" . $papua_barat_id . ", 'Kab. Kepulauan Raja Ampat', 1),
				(" . $papua_barat_id . ", 'Kab. Manokwari', 1),
				(" . $papua_barat_id . ", 'Kab. Manokwari Selatan', 1),
				(" . $papua_barat_id . ", 'Kab. Pegunungan Arfak', 1),
				(" . $papua_barat_id . ", 'Kab. Sorong Selatan', 1),
				(" . $papua_barat_id . ", 'Kab. Teluk Bintuni', 1),
				(" . $papua_barat_id . ", 'Kab. Sorong', 1),
				(" . $papua_barat_id . ", 'Kab. Teluk Wondama', 1),
				(" . $papua_barat_id . ", 'Kab. Tambrauw', 0),
				(" . $papua_barat_id . ", 'Kab. Maibrat', 0),
				(" . $papua_barat_id . ", 'Sorong', 1);"
			);
		}

		// Kalimantan Utara zone
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE name = 'Kalimantan Utara'");

		if ($query->num_rows) {
			$kal_utara_id = $query->row['zone_id'];
		} else {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "zone` SET status = '1', name = 'Kalimantan Utara', code = 'PB', country_id = '100'");

			$kal_utara_id = $this->db->getLastId();
		}

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "city` WHERE zone_id = '" . (int)$kal_utara_id . "'");

		if (!$query->num_rows) {
			$this->db->query(
				"INSERT INTO `" . DB_PREFIX . "city` (`zone_id`, `name`, `status`) VALUES
				(" . $kal_utara_id . ", 'Kab. Bulungan', 1),
				(" . $kal_utara_id . ", 'Kab. Malinau', 1),
				(" . $kal_utara_id . ", 'Kab. Nunukan', 1),
				(" . $kal_utara_id . ", 'Kab. Tanah Tidung', 1),
				(" . $kal_utara_id . ", 'Tarakan', 1);"
			);
		}

		// Sulawesi Barat zone
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE name = 'Sulawesi Barat'");

		if ($query->num_rows) {
			$sul_barat_id = $query->row['zone_id'];
		} else {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "zone` SET status = '1', name = 'Sulawesi Barat', code = 'SR', country_id = '100'");

			$sul_barat_id = $this->db->getLastId();
		}

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "city` WHERE zone_id = '" . (int)$sul_barat_id . "'");

		if (!$query->num_rows) {
			$this->db->query(
				"INSERT INTO `" . DB_PREFIX . "city` (`zone_id`, `name`, `status`) VALUES
				(" . $sul_barat_id . ", 'Kab. Mamaju', 1),
				(" . $sul_barat_id . ", 'Kab. Majene', 1),
				(" . $sul_barat_id . ", 'Kab. Mamuju Utara', 1),
				(" . $sul_barat_id . ", 'Kab. Mamasa', 1),
				(" . $sul_barat_id . ", 'Kab. Polewali Mandar', 1);"
			);
		}
	}
}
?>