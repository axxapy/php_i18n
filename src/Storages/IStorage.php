<?php namespace axxapy\i18n\Storages;

interface IStorage {
	public function getValue(string $key, $default = null);
}
