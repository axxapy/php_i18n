<?php namespace axxapy\i18n\Storages;

abstract class Storage {
	const FORMAT_NAME = "";

	private $data = [];
	private $filename;

	public function __construct(string $base_dir, string $lang, string $dialect) {
		$filename = $base_dir . "/" . static::FORMAT_NAME . "/" . $lang;
		if ($dialect) $filename .= "_{$dialect}";
		$filename .= "." . static::FORMAT_NAME;
		if (!file_exists($filename)) {
			throw new \InvalidArgumentException("File '{$filename}' not found");
		}
		$this->filename = $filename;
		$this->data     = $this->decode($filename);
	}

	public function get(string $key, $default = null) {
		return array_key_exists($key, $this->data) ? $this->data[$key] : $default;
	}

	public function set(string $key, $value) {
		$this->data[$key] = $value;
	}

	public function export(string $file = null) {
		if (!$file) $file = $this->filename;
		file_put_contents($file, $this->encode($this->data));
	}

	abstract protected function decode(string $filename): array;

	abstract protected function encode(array $data): string;
}
