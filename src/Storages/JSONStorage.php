<?php namespace axxapy\i18n\Storages;

class JSONStorage implements IStorage {
	/** @var \axxapy\i18n\Storages\Message[] */
	private $data = [];
	private $file;

	public function __construct(string $base_dir, string $lang, string $dialect) {
		if ($dialect) {
			$filename = $base_dir . '/' . $lang . '_' . $dialect . '.json';
		}

		if (!$dialect || !file_exists($filename)) {
			$filename = $base_dir . '/langs/' . $lang . '.json';
		}

		$this->file = $filename;

		if (!file_exists($filename)) return;

		//@todo: error handling
		$data = json_decode(file_get_contents($filename), true);
		foreach ($data as $key => $value) {
			$this->data[$key] = new Message($value);
		}
	}

	public function getMessage(string $key): ?Message {
		if (!array_key_exists($key, $this->data)) return null;
		return $this->data[$key];
	}

	public function putMessage(string $key, Message $Message) {
		$this->data[$key] = $Message;
	}

	public function getValue(string $key, $default = null) {
		$Message = $this->getMessage($key);
		if ($Message == null) return $default;
		return $Message->value ? $Message->value : $Message->value_org;
	}

	public function save() {
		file_put_contents($this->file, preg_replace_callback("#$\G(\t*)\s{4}#", function ($matches) {
			$len = floor(strlen($matches[0]) / 4);
			return "\n" . str_repeat("\t", $len) . str_repeat(' ', strlen($matches[0]) - 1 - $len * 4);
		}, json_encode($this->data, JSON_PRETTY_PRINT)));
	}

	public function cleanup() {
		$this->data = [];
	}
}
