<?php

class Sinonims
{
	private $yandexApiKey = 'dict.1.1.20151229T121447Z.48cacd615c2030e4.6a7df1425a6c2f0ded275578598c8a1d30170ab0';

	public function parseSinonims ($words)
	{
		$sinonims = [];
		array_walk($words, function ($word) use (&$sinonims) {$sinonims[$word] = ['base1' => [], 'base2' => [], 'base3' => [],
			'base4' => [], 'base5' => [], 'base6' => [], 'base7' => []];});

		//$this -> getSinonimsFromBase1($sinonims, $words);

		// этот вообще фиговый
		//$this -> getSinonimsFromBase2($sinonims, $words);

		//$this -> getSinonimsFromBase3($sinonims, $words);
		//$this -> getSinonimsFromBase4($sinonims, $words);
		$this -> getSinonimsFromBase5($sinonims, $words);
		//$this -> getSinonimsFromBase6($sinonims, $words);
		//$this -> getSinonimsFromBase7($sinonims, $words);

		foreach ($sinonims as $word => &$bases)
		{
			$bases = array_values(array_unique(array_merge($bases['base1'], $bases['base2'], $bases['base3'], $bases['base4'], $bases['base5'], $bases['base6'], $bases['base7'])));
			$finalWords = [];
			foreach ($bases as $word)
			{
				if (sizeof(explode(" ", $word)) === 1)
				{
					$finalWords[] = $word;
				}
			}
			$bases = $finalWords;
		}

		return $sinonims;
	}


	/**
	 * нормализация каждого слова в тексте
	 * @param array $phrases
	 * @return array
	 */
	private function morfPhrases ($phrases)
	{
		$allWords = array_values(array_unique(explode(" ", preg_replace("/[\[\]\"\+]/", '', implode(" ", $phrases)))));
		$lemms = $this -> morf -> normalizeMystem($allWords);

		for ($i = 0, $max = sizeof($phrases); $i < $max; $i++)
		{
			$phrases[$i] = trim($phrases[$i]);
			if ($phrases[$i] === '')
			{
				continue;
			}

			$words = explode(" ", preg_replace("/[\[\]\"\+]/", '', $phrases[$i]));
			for ($j = 0, $max2 = sizeof($words); $j < $max2; $j++) //обходим каждое слово в строке
			{
				if (mb_substr($words[$j], 0, 1) === '!')
				{
					continue;
				}

				$words[$j] = mb_strtoupper($words[$j], 'utf-8');
				$this -> morf -> normalizWord($words[$j], $lemms);
			}

			$phrases[$i] = implode(' ', $words);
		}

		return $phrases;
	}


	/**
	 * получение синонимов с базы 1 (translate.yandex)
	 * @param array $result
	 * @param array $words
	 */
	private function getSinonimsFromBase1 (&$result, $words)
	{
		$curl = $this -> getCurl(false, false);
		$url = 'https://dictionary.yandex.net/api/v1/dicservice.json/lookup?lang=ru-ru&key=' . $this -> yandexApiKey;

		for ($i = 0, $max = sizeof($words); $i < $max; $i++)
		{
			curl_setopt($curl, CURLOPT_URL, $url . "&text=" . $words[$i]);
			$data = json_decode(curl_exec($curl), true);

			// словарные статьи
			$temp = isset($data['def']) ? $data['def'] : [];

			for ($j = 0, $max2 = sizeof($temp); $j < $max2; $j++)
			{
				// варианты перевода из словарной статьи
				$info = $temp[$j]['tr'];

				for ($k = 0, $max3 = sizeof($info); $k < $max3; $k++)
				{
					if (!in_array($info[$k]['text'], $result[$words[$i]]['base1']))
					{
						$result[$words[$i]]['base1'][] = $info[$k]['text'];
					}

					// обрабокта синонимов для варианта перевода
					if (isset($info[$k]['syn']))
					{
						$sinonims = $info[$k]['syn'];
						for ($z = 0, $max4 = sizeof($sinonims); $z < $max4; $z++)
						{
							if (!in_array($sinonims[$z]['text'], $result[$words[$i]]['base1']))
							{
								$result[$words[$i]]['base1'][] = $sinonims[$z]['text'];
							}
						}
					}
				}
			}
		}
		curl_close($curl);
	}


	/**
	 * получение синонимов с базы 2
	 * @param array $result
	 * @param array $words
	 */
	private function getSinonimsFromBase2 (&$result, $words)
	{
		$curl = $this -> getCurl(false, false);

		for ($i = 0, $max = sizeof($words); $i < $max; $i++)
		{
			$word = $words[$i];

			if ($curl === null)
			{
				$data = file_get_contents("http://sinonimus.ru/synonyms/all/sinonim_slova_$word");
			}
			else
			{
				curl_setopt($curl, CURLOPT_URL, "https://sinonimus.ru/synonyms/all/sinonim_slova_$word");
				$data = curl_exec($curl);
			}


			$data = iconv('windows-1251', 'utf-8', $data);
			preg_match_all("/<section itemprop=\"articleBody\">.*<\/section>/", $data, $match);

			if (!isset($match[0][0]) or !isset($match[0][0]))
			{
				continue;
			}

			$tempWords = preg_split("/ \| /", preg_replace('/ \|$/', '', strip_tags($match[0][0])));
			$tempWords = array_values(array_diff($tempWords, ['', ' ']));

			for ($j = 0, $max2 = sizeof($tempWords); $j < $max2; $j++)
			{
				$tempWords[$j] = trim($tempWords[$j]);
				$wordsCount = sizeof(explode(" ", $tempWords[$j]));
				if ($tempWords[$j] !== '' and $wordsCount === 1 and !in_array($tempWords[$j], $result[$words[$i]]['base2']))
				{
					$result[$words[$i]]['base2'][] = $tempWords[$j];
				}
			}
		}
	}

	/**
	 * @param $result
	 * @param array $words
	 *
	 */
	private function getSinonimsFromBase3 (&$result, $words)
	{
		$curl = $this -> getCurl(false, false);

		for ($i = 0, $max = sizeof($words); $i < $max; $i++)
		{
			$word = $words[$i];
			curl_setopt($curl, CURLOPT_URL, "https://synonyms.su/" . urlencode("С") . "/". urlencode($word));
			$data = curl_exec($curl);
			preg_match("/<table class=\"synonyms-table\">.*<\/table>/", $data, $matches);
			if (!is_array($matches) or !isset($matches[0]))
			{
				continue;
			}

			$DOM = new DOMDocument;
			$DOM -> loadHTML('<?xml encoding="UTF-8">' . $matches[0]);
			$items = $DOM -> getElementsByTagName('tr');

			$rowsCount = 0;

			foreach ($items as $node) {
				if ($rowsCount === 0)
				{
					$rowsCount++;
					continue;
				}

				$sinonim = $node->childNodes[1] -> nodeValue;
				if (!in_array($sinonim, $result[$word]['base3']))
				{
					$result[$word]['base3'][] = mb_strtolower($sinonim, 'utf-8');
				}
				//$sinonims[] = ['word' => $node->childNodes[1] -> nodeValue, 'frequency' => $node->childNodes[2] -> nodeValue];
				$rowsCount++;
			}
			//var_dump(curl_error($curl));
			//var_dump($data);
		}
	}

	/**
	 * @param $result
	 * @param array $words
	 */
	private function getSinonimsFromBase4 (&$result, $words)
	{
		$curl = $this -> getCurl(false, false);

		for ($i = 0, $max = sizeof($words); $i < $max; $i++)
		{
			$word = $words[$i];
			curl_setopt($curl, CURLOPT_URL, "https://wordsynonym.ru/". urlencode($word));
			$data = curl_exec($curl);

			preg_match_all("/<li><span>(.*)<\/span><\/li>/", $data, $matches);
			if (!is_array($matches) or !isset($matches[0]) or sizeof($matches[0]) === 0)
			{
				continue;
			}

			for ($j = 0, $max2 = sizeof($matches[1]); $j < $max2; $j++)
			{
				$sinonim = $matches[1][$j];
				if (!in_array($sinonim, $result[$word]['base4']))
				{
					$result[$word]['base4'][] = mb_strtolower($sinonim, 'utf-8');
				}
			}
		}
	}

	/**
	 * @param $result
	 * @param array $words
	 */
	private function getSinonimsFromBase5 (&$result, $words)
	{
		$curl = $this -> getCurl(false, false);

		for ($i = 0, $max = sizeof($words); $i < $max; $i++)
		{
			if (!isset($words[$i]))
			{
				continue;
			}
			$word = $words[$i];
			curl_setopt($curl, CURLOPT_URL, "http://www.synonymizer.ru/index.php?sword=". urlencode($word));
			$data = curl_exec($curl);
			$data = iconv("Windows-1251", "UTF-8", $data);
			preg_match_all("/\?sword=([^\"]*)\"/", $data, $matches);
			if (!is_array($matches) or !isset($matches[0]) or sizeof($matches[0]) === 0)
			{
				continue;
			}

			for ($j = 0, $max2 = sizeof($matches[1]); $j < $max2; $j++)
			{
				$sinonim = $matches[1][$j];
				if (in_array($sinonim, ['текст', 'синоним', 'написать', 'раскрутка', 'продвижение', 'статья', 'синонимайзер']))
				{
					continue;
				}

				if (!in_array($sinonim, $result[$word]['base5']))
				{
					$result[$word]['base5'][] = mb_strtolower($sinonim, 'utf-8');
				}
			}
		}
	}

	/**
	 * @param $result
	 * @param array $words
	 */
	private function getSinonimsFromBase6 (&$result, $words)
	{
		$curl = $this -> getCurl(false, false);

		for ($i = 0, $max = sizeof($words); $i < $max; $i++)
		{
			$word = $words[$i];
			curl_setopt($curl, CURLOPT_URL, "https://synonymonline.ru/" . urlencode("С") . "/" . urlencode($word));
			$data = curl_exec($curl);
			preg_match_all("/<li class=\"col-sm-4 col-xs-6\"><span>(.*)<\/span><\/li>/", $data, $matches);
			if (!is_array($matches) or !isset($matches[0]) or sizeof($matches[0]) === 0)
			{
				continue;
			}

			for ($j = 0, $max2 = sizeof($matches[1]); $j < $max2; $j++)
			{
				$sinonim = $matches[1][$j];

				if (!in_array($sinonim, $result[$word]['base6']))
				{
					$result[$word]['base6'][] = mb_strtolower($sinonim, 'utf-8');
				}
			}
		}
	}

	/**
	 * @param $result
	 * @param array $words
	 */
	private function getSinonimsFromBase7 (&$result, $words)
	{
		$curl = $this -> getCurl(false, false);

		for ($i = 0, $max = sizeof($words); $i < $max; $i++)
		{
			$word = $words[$i];
			curl_setopt($curl, CURLOPT_URL, "https://isynonyms.com/synonym/" . urlencode($word) . "/");
			$data = curl_exec($curl);
			preg_match_all("/\/synonym\/([^\"]*?)\'>/", $data, $matches);
			if (!is_array($matches) or !isset($matches[0]) or sizeof($matches[0]) === 0)
			{
				continue;
			}

			for ($j = 0, $max2 = sizeof($matches[1]); $j < $max2; $j++)
			{
				$sinonim = preg_replace("/\//", '', $matches[1][$j]);
				$sinonim = urldecode($sinonim);

				if (!in_array($sinonim, $result[$word]['base7']))
				{
					$result[$word]['base7'][] = mb_strtolower($sinonim, 'utf-8');
				}
			}
		}
	}

	private function getCurl ($addHeaders = false, $isPost = true, $timeout = 7200)
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, $addHeaders);
		curl_setopt($curl, CURLOPT_POST, $isPost);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);
		return $curl;
	}

}
