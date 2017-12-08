<?php namespace axxapy\i18n\Cli;

use axxapy\Controllers\CliController;
use axxapy\i18n\Storages\JSONStorage;
use axxapy\i18n\Storages\Message;
use axxapy\i18n\Storages\PHPStorage;
use InvalidArgumentException;

class TranslationCli extends CliController {
	/**
	 * @cliCommand sync
	 */
	public function cmdSync(array $args = []) {
		if (count($args) < 2) {
			throw new InvalidArgumentException();
		}

		$lang_list = array_map(function ($v) { return strtolower(trim($v)); }, explode(',', $args[1]));

		$data = (new PHPStorage($args[0], '', '', true))->dump();
		foreach ($lang_list as $lang) {
			$Storage = new JSONStorage($args[0], $lang, '');
			$StorageNew = new JSONStorage($args[0], $lang, '');
			foreach ($data as $key => $value) {
				$Message = $Storage->getMessage($key);
				if ($Message == null) {
					$Message            = new Message([]);
					$Message->value_org = $value;
					$Message->lang_org  = '';//@todo
					$Message->modified  = date(DATE_ISO8601);
					$StorageNew->putMessage($key, $Message);
					continue;
				}

				if ($this->same($Message->value_org, $value)) {
					$Message->value     = null;
					$Message->value_org = $value;
					$Message->modified  = date(DATE_ISO8601);
				}

				$StorageNew->putMessage($key, $Message);
			}

			$StorageNew->save();
		}
	}

	private function same($first, $second) : bool {
		if (is_array($first) && is_array($second)) {
			foreach ($first as $key => $value) {
				if (!array_key_exists($key, $second)) return false;
				if ($value !== $second[$key]) return false;
			}
			return true;
		}
		if (gettype($first) === gettype($second)) return $first === $second;
		return false;
	}
}
