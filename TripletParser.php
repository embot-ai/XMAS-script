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

namespace XmasScript;

class TripletParser implements \Iterator {
	private $p = 0; //pointer to char (char number)
	private $str;
	private $LENGTH;

	private $start = 0; //pointer to word

	function __construct($s) {
		//Append 3 extra spaces to ensure triplet
		$this->str = $s . "   ";

		$this->LENGTH = mb_strlen($this->str);
	}

	public function valid() {
		return ($this->p < $this->LENGTH);
	}

	public function key() {
		return $this->p;
	}

	public function fetch($offset) {
		$word = mb_substr($this->str, $this->start, ($this->p + $offset - $this->start));
		$this->start = $this->p + $offset + 1;
		return $word;
	}

	public function current() {
		return mb_substr($this->str, $this->p, 3);
	}

	public function skip($offset) {
		$this->p += $offset;
		$this->start = $this->p;
	}

	public function next() {
		$this->p++;

		//Speed up
		$look_ahead = mb_strpos($this->str, '#', $this->p);
		//var_dump($look_ahead);
		//var_dump(mb_substr($this->str, $look_ahead));

		if ($look_ahead === FALSE) {
			$this->p = $this->LENGTH;
		} else {
			$this->p = $look_ahead;
		}

		/* $look_ahead = mb_substr($this->str, $this->p, 8);
		//echo "LA{$this->p}:{$look_ahead}\$\n";
		if (mb_strpos($look_ahead, '#') === FALSE && mb_strlen($look_ahead) == 8) {
			//$jmp = mb_strlen($look_ahead);
			//echo "JMP:$jmp\$\n";
			$this->p += 8;
		} */
	}

	public function rewind() {
		$this->p = 0;
		$this->start = 0;
	}
}
