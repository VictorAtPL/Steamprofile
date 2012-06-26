<?php
/**
 *	Written by Nico Bergemann <barracuda415@yahoo.de>
 *	Copyright 2011 Nico Bergemann
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

class HttpProfileLoader extends HttpLoader {
	private $bTrimExtra = false;
	private $bFilterCtlChars = false;

	public function __construct($sUrl, $sApp, $sExtra) {
		parent::__construct($sUrl, $sApp, $sExtra);
		$this->setReturnTransfer(true);
	}

	public function setTrimExtra($bTrimExtra) {
		$this->bTrimExtra = $bTrimExtra;
	}
	
	public function isTrimExtra() {
		return $this->bTrimExtra;
	}
	
	public function setFilterCtlChars($bFilterCtlChars) {
		$this->bFilterCtlChars = $bFilterCtlChars;
	}
	
	public function isFilterCtlChars() {
		return $this->bFilterCtlChars;
	}
	
	public function start() {
		$content = parent::start();
		
		// check if the we actually downloaded anything
		if(strlen($content) == 0) {
			throw new Exception('Empty profile data');
		}
		
		// trim extra profile data (groups, friends, most played games)
		if($this->bTrimExtra) {
			$sEndToken = '</summary>';
			$iEndPos = strpos($content, $sEndToken);

			if($iEndPos !== false) {
				$content = substr($content, 0, $iEndPos + strlen($sEndToken));
				$content.= "\n</profile>";
			}
		}
		
		// remove certain control characters that are misleadingly send by the API,
		// which are invalid in XML 1.0
		if($this->bFilterCtlChars) {
			$aCtlChr = array();

			for($i = 0; $i < 32; $i++) {
				// tab, lf and cr are allowed
				if($i == 9 || $i == 10 || $i == 13) continue;
				$aCtlChr[] = chr($i);
			}

			$content = str_replace($aCtlChr, '', $content);
		}
		
		return $content;
	}
}
?>