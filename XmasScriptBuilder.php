<?php
/*
 * Copyright 2018 Serge Cornelissen
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/*
 * XMAS Script
 */

class XmasScriptBuilder {
	private $data;

	function __construct() {
		$this->data = [];
	}

	public function getData() {
		return $this->data;
	}

	public function setAudio($s) {
		$this->data[] = ['A', $s];
	}

	public function setMessage($s) {
		$this->data[] = ['M', $s];
	}

	public function setSSML($s) {
		$this->data[] = ['S', $s];
	}

	public function setVariable($k,$v) {
		$this->data[] = ['!', "$k=$v"];
	}

	public function __toString() {
		$parts = [];
		foreach ($this->data as $row) {
			$parts[] = '#' . $row[0] . ':' . $row[1] . "#E:";
		}
		return implode("", $parts);
	}
}
