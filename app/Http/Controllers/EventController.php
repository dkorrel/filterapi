<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Filter\Filter;

use Request;

class EventController extends BaseController {

	protected $keyOperatorMap = array(
		'location' => 'in',
		'category' => 'in',
		'venue' => 'in',
		'start_discount' => array('discount' => '>='),
		'end_discount' => array('discount' => '<='),
		'start_price' => array('price' => '>='),
		'end_price' => array('price' => '<='),
		'start_value' => array('value' => '>='),
		'end_value' => array('value' => '<=')
	);

	public function index(Request $request)
	{
		$data = $this->getData();

		$input = $request::Input();

		if( count($input) === 0 ) return $data;

		$filters = $this->parseFilters($input);

		if( count($filters) === 0 ) return $data;

		$filter = new Filter($filters, $data);

		return $filter->filterData();
	}

	protected function parseFilters(array $input)
	{
		$result = array();

		foreach( $input as $key => $value )
		{
			if(! isset($this->keyOperatorMap[$key]) ) continue;

			$operator = $this->keyOperatorMap[$key];

			$values = explode(',', $value);

			if( count($values) > 1 ) $value = $values;

			if( is_array($operator) )
			{
				$key = key($operator);

				$operator = $operator[$key];
			}

			$result[] = array(
				'key' => $key,
				'operator' => $operator,
				'value' => $value
			);
		}
		
		return $result;
	}

	protected function getData()
	{
		// Some quick mock data for testing
		return array(
			array(
				'title' => 'Melkweg',
				'description' => 'Artikel tekst',
				'location' => 'Amsterdam',
				'category' => 'Muziek',
				'venue' => 'Melkweg',
				'discount' => 25, // For example a percentage
				'price' => 24.50,
				'start_date' => 'timestamp',
				'end_date' => 'timestamp'
			),
			array(
				'title' => 'Paradiso',
				'description' => 'Artikel tekst',
				'location' => 'Amsterdam',
				'category' => 'Muziek',
				'venue' => 'Paradiso',
				'discount' => 50,
				'price' => 5.00,
				'start_date' => 'timestamp',
				'end_date' => 'timestamp'
			),
			array(
				'title' => 'Rijksmuseum',
				'description' => 'Artikel tekst',
				'location' => 'Amsterdam',
				'category' => 'Kunst',
				'venue' => 'Rijksmuseum',
				'discount' => 10,
				'price' => 13.50,
				'start_date' => 'timestamp',
				'end_date' => 'timestamp'
			),
			array(
				'title' => 'De Parade',
				'description' => 'Artikel tekst',
				'location' => 'Den Haag',
				'category' => 'Muziek',
				'venue' => 'Westbroekpark',
				'discount' => 40,
				'price' => 10,
				'start_date' => 'timestamp',
				'end_date' => 'timestamp'
			),
			array(
				'title' => 'Nederlands Fotomuseum',
				'description' => 'Artikel tekst',
				'location' => 'Rotterdam',
				'category' => 'Kunst',
				'venue' => 'Nederlands Fotomuseum',
				'discount' => 15,
				'price' => 30,
				'start_date' => 'timestamp',
				'end_date' => 'timestamp'
			),
		);
	}

}