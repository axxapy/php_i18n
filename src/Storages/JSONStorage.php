<?php namespace axxapy\i18n\Storages;

class JSONStorage extends Storage {
	const FORMAT_NAME = "json";

	protected function decode(string $filename): array {
		return json_decode(file_get_contents($filename), true);
	}

	protected function encode(array $data): string {
		return preg_replace_callback("#$\G(\t*)\s{4}#", function ($matches) {
			$len = floor(strlen($matches[0]) / 4);
			return "\n" . str_repeat("\t", $len) . str_repeat(' ', strlen($matches[0]) - 1 - $len * 4);
		}, json_encode($data, JSON_PRETTY_PRINT));
	}
}
