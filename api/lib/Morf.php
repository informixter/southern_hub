<?php

class Morf //класс для лексической обработки данных
{

	public function getForms ($words)
	{
		include_once('./lib/morphy/src/common.php');
		$dir = './lib/morphy/libs';
		$lang = 'ru_RU';
		$opts = array('storage' => 'mem');
		$r = new phpMorphy($dir, $lang, $opts);
		return $r -> getAllForms($words);
	}

	/**
	 * нормализация с использованием yandex mystem вместе с граммемами
	 * @param array $words - массив слов
	 * @return array
	 */
	public function normalizeMyStem2 ($words)
	{
		$translates = [
			'A' => 'прилагательное',
			'ADV' => 'наречие',
			'ADVPRO' => 'местоименное наречие',
			'ANUM' => 'числительное-прилагательное',
			'APRO' => 'местоимение-прилагательное',
			'COM' => 'часть композита - сложного слова',
			'CONJ' => 'союз',
			'INTJ' => 'междометие',
			'NUM' => 'числительное',
			'PART' => 'частица',
			'PR' => 'предлог',
			'S' => 'существительное',
			'SPRO' => 'местоимение-существительное',
			'V' => 'глагол'
		];

		$words = array_values(array_unique($words));
		$file_name = './lib/morph/' . time() . rand(0, 1000);
		$file = fopen($file_name, 'wt+');
		fwrite($file, implode("\n", $words));
		fclose($file);
		$result = `lib/mystem -n -i $file_name`;
		$result = mb_strtoupper($result, 'utf-8');
		unlink($file_name);
		$result = explode("\n", $result);
		$lemms = [];
		for ($i = 0, $max = sizeof($result); $i < $max; $i++)
		{
			$result[$i] = preg_replace("/\?/", '', $result[$i]);
			$result[$i] = explode('|', $result[$i])[0];
			$result[$i] = preg_match("/^([^{]*){([^=]*)=([^,=]*)/", $result[$i], $matches);

			if ($matches)
			{
				$lemms[$matches[1]] = ['lemm' => $matches[2], 'part' => $translates[$matches[3]]];
			}
		}

		return $lemms;
	}


	/**
	 * нормализация с использованием yandex mystem
	 * @param array $words - массив слов
	 * @return array
	 */
	public function normalizeMyStem ($words)
	{
		$words = array_values(array_unique($words));
		$file_name = './lib/morph/' . time() . rand(0, 1000);
		$file = fopen($file_name, 'wt+');
		fwrite($file, implode("\n", $words));
		fclose($file);
		$result = `lib/mystem -n $file_name`;
		$result = mb_strtoupper($result, 'utf-8');
		unlink($file_name);
		$result = explode("\n", $result);
		$lemms = [];
		for ($i = 0, $max = sizeof($result); $i < $max; $i++)
		{
			$result[$i] = preg_replace("/\?/", '', $result[$i]);
			$result[$i] = preg_match("/([^{]*){([^}]*)}/", $result[$i], $matches);
			if ($matches)
			{
				$lemms[$matches[1]] = explode('|', $matches[2]);
			}
		}
		return $lemms;
	}

	/**
	 * нормализация слова
	 * @param string $word
	 * @param array $lemms
	 * @return mixed
	 */
	public	function normalizWord (&$word, &$lemms)
	{
		$normWord = $word;
		if (isset($lemms[$word]) and $lemms[$word]) //если есть нормальная форма для слова (без спец. символов)
		{
			$normWord = $lemms[$word][0];
		}
		$word = mb_strtolower($normWord, 'utf-8');
		return true;
	}

	/**
	 * нормализация слова
	 * @param string $word
	 * @param array $lemms
	 * @return mixed
	 */
	public	function normalizWord2 (&$word, &$lemms)
	{
		$normWord = ['lemm' => mb_strtolower($word, 'utf-8'), 'part' => '*'];
		if (isset($lemms[$word]) and $lemms[$word]) //если есть нормальная форма для слова (без спец. символов)
		{
			$normWord = $lemms[$word];
			$normWord['lemm'] = preg_replace("/[)(\.,\?\!]/", '', mb_strtolower($normWord['lemm'], 'utf-8'));
		}
		$word = $normWord;
		return true;
	}


	/**
	 * нормализация каждого слова в тексте
	 * @param array $phrases
	 * @param $replaceExactChar
	 * @return array
	 */
	public function morfPhrases ($phrases, $replaceExactChar = true)
	{
		$allWords = array_values(array_unique(explode(" ", preg_replace("/[\[\]\"\+]/", '', implode(" ", $phrases)))));
		$lemms = $this -> normalizeMystem($allWords);

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
					if ($replaceExactChar)
					{
						$words[$j] = mb_substr($words[$j], 1);
					}
					continue;
				}

				$words[$j] = mb_strtoupper($words[$j], 'utf-8');
				$this -> normalizWord($words[$j], $lemms);
			}

			$phrases[$i] = implode(' ', $words);
		}

		return $phrases;
	}

	/**
	 * нормализация каждого слова в тексте С ЧАСТЯМИ РЕЧИ
	 * @param array $phrases
	 * @param $replaceExactChar
	 * @return array
	 */
	public function morfPhrases2 ($phrases, $replaceExactChar = true)
	{
		$allWords = array_values(array_unique(explode(" ", preg_replace("/[\[\]\"\+]/", '', implode(" ", $phrases)))));
		$lemms = $this -> normalizeMystem2($allWords);

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
					if ($replaceExactChar)
					{
						$words[$j] = mb_substr($words[$j], 1);
					}
					continue;
				}

				$words[$j] = preg_replace("/[)(\.,\?\!]/", '', mb_strtoupper($words[$j], 'utf-8'));
				$this -> normalizWord2($words[$j], $lemms);
			}

			$phrases[$i] = $words;
		}

		return $phrases;
	}

}
