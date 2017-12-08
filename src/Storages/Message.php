<?php namespace axxapy\i18n\Storages;

class Message {
	public $value;
	public $value_org;
	public $lang_org;
	public $modified;

	public function __construct(array $data) {
		$data      += [
			'value'     => '',
			'value_org' => '',
			'lang_org'  => '',
			'modified'  => '',
		];

		$this->value = $data['value'];
		$this->value_org = $data['value_org'];
		$this->lang_org = $data['lang_org'];
		$this->modified = $data['modified'];
	}
}
