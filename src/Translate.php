<?php namespace axxapy\i18n;

use axxapy\i18n\Storages\JSONStorage;
use axxapy\i18n\Storages\PHPStorage;
use axxapy\i18n\Storages\IStorage;

class Translate {
	const STORAGE_JSON = JSONStorage::class;
	const STORAGE_PHP  = PHPStorage::class;

	private $dir;
	private $lang;
	private $dialect;

	/** @var \axxapy\i18n\Storages\IStorage */
	private $Storage;

	private $storage_type = self::STORAGE_PHP;
	private $storage_plain = false;

	public function __construct(string $base_dir, string $language_code, string $dialect_code) {
		$language_code = strtolower($language_code);
		$dialect_code  = strtolower($dialect_code);
		if (!Language::isValidLanguage($language_code)) {
			throw new \InvalidArgumentException("Language with code '{$language_code}' has not been found.");
		}
		$this->dir     = $base_dir;
		$this->lang    = $language_code;
		$this->dialect = $dialect_code;
	}

	protected function getStorage(): IStorage {
		if (!$this->Storage) {
			$this->Storage = new $this->storage_type($this->dir, $this->lang, $this->dialect, $this->storage_plain);
		}
		return $this->Storage;
	}

	public function setStorage(string $type, bool $plain) : Translate {
		if (!in_array($type, [self::STORAGE_JSON, self::STORAGE_PHP])) {
			throw new \InvalidArgumentException("Unknown storage '{$type}'. Possible values are: Translate::STORAGE_JSON, Translate::STORAGE_PHP");
		}
		$this->storage_type = $type;
		$this->storage_plain = $plain;
		return $this;
	}

	public function getString(string $code, array $vars = []): string {
		$str = $this->getStorage()->getValue($code, $code);
		if ($vars) {
			$vars = array_map(function ($key) { return '{' . $key . '}'; }, array_flip($vars));
			return str_replace(array_values($vars), array_keys($vars), $str);
		}
		return $str;
	}

	public function getStringF(string $code, ...$replaces): string {
		return sprintf($this->getStorage()->getValue($code, $code), ...$replaces);
	}

	public function getArray(string $code): array {
		return $this->getStorage()->getValue($code, [$code]);
	}

	public function getNumDependentString(string $code, float $number): string {
		$data = $this->getStorage()->getValue($code . '#', $code);
		if ($number == 1) return $data['1'];
		if ($number > 1 && $number < 5) return isset($data['2-4']) ? $data['2-4'] : $data['5'];
		if ($number == 0 || $number > 4) return $data['5+'];
	}

	public function formatNumber(float $number): string {
		return (string)$number;
	}
}
