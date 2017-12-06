<?php namespace axxapy\i18n\Storages;

class PHPStorage extends Storage {
	public function decode(string $filename): array {
		return require $filename;
	}

	public function encode(array $data): string {
		return '<?php return ' . var_export($data, true) . ";\n";
	}
}
