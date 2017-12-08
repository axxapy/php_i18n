<?php namespace axxapy\i18n\Storages;


class PHPStorage implements IStorage {
	private $data;

	public function __construct(string $base_dir, string $lang, string $dialect, bool $ignore_lang = false) {
		if ($ignore_lang) {
			$filename   = $base_dir . '/plain.php';
		} else {
			if ($dialect) {
				$filename = $base_dir . '/' . $lang . '_' . $dialect . '.json';
			}

			if (!$dialect || !file_exists($filename)) {
				$filename = $base_dir . '/langs/' . $dialect . '.json';
			}
		}

		//@todo: error handling
		$this->data = require($filename);
	}

	public function getValue(string $key, $default = null) {
		if (!array_key_exists($key, $this->data)) return $default;
		return $this->data[$key];
	}

	public function dump() : array {
		return $this->data;
	}

	/*public function encode(array $data): string {
		return '<?php return ' . var_export($data, true) . ";\n";
	}*/
}
