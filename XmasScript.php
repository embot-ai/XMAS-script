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
 *
 * M: Messages
 * A: Audio
 * S: SSML
 *
 * X: XML
 * E: Exit (alias to M)
 * !: Commands, variables
 */

class XmasScript {
	const XMAS_SEPARATOR = " ";

	private $data;

	public function getData() {
		return $this->data;
	}

	public function displayText() : string {
		return $this->filterData('M', self::XMAS_SEPARATOR);
	}

	public function displayHTML() : string {
		return $this->filterData('MH', self::XMAS_SEPARATOR);
	}

	public function SSML() : string {
		return $this->filterData('S', '');
	}

	public function variables() : array {
		$variables = [];
		foreach ($this->data as $row) {
			if ($row[0] == '!') {
				$arr = preg_split('/=/u', $row[1], 2, PREG_SPLIT_NO_EMPTY); //multibyte safe split
				$variables[$arr[0]] = $arr[1];
			}
		}
		return $variables;
	}

	/*
	 * $mask: String of allowed keys, for example 'MS' (messages and ssml)
	 * $separator: String between multiple keys
	 */

	public function filterData(string $mask, string $separator) : string {
		$set = [];
		foreach ($this->data as $row) {
			if (strpos($mask, $row[0]) !== FALSE) {
				$set[] = $row[1];
			}
		}
		return implode($separator, $set);
	}

	/* public function buildSSML($public_audio_uri) {
		$ssml = ["<speak>"];
		foreach ($this->data as $row) {
			if ($row[0] == 'M') {
				$ssml[] = $row[1];
			}
			if ($row[0] == 'A') {
				$ssml[] = sprintf('<audio src="%s%s"></audio>', $public_audio_uri, $row[1]);
			}
		}
		$ssml[] = "</speak>";
		return implode("", $ssml);
	} */

	private function appendData(string $mode, string $term) {
		$term = trim($term);

		if (mb_strlen($term)>0) {
			$this->data[] = [$mode, $term];
		}
	}

	public function parse(string $xmasStr) {
		$this->data = [];
		$mode = 'M';
		$lexer = new \XmasScript\TripletParser($xmasStr);
		while($lexer->valid()) {
			$triplet = $lexer->current();
			//echo $triplet, "\$\n";

			//$arr = str_split($triplet);
			$arr = preg_split('//u', $triplet, -1, PREG_SPLIT_NO_EMPTY); //multibyte safe split
			//print_r($arr);

			if (count($arr) == 3) {
				if ($arr[0] == '#' && $arr[2] == ':') {

					$term = $lexer->fetch(0);

					//var_dump($term);

					$this->appendData($mode, $term);

					if ($arr[1] == 'E') {
						$arr[1] = 'M';
					}

					$mode = $arr[1];

					$lexer->skip(3);
					continue;
				}
			} else {
				break;
			}
			$lexer->next();
		}

		$term = $lexer->fetch(0);
		//var_dump($term);
		$this->appendData($mode, $term);
	}
}
