<?php

declare(strict_types=1);

namespace Nikba\LaravelBussystemApi\Tests\Unit;

use Nikba\LaravelBussystemApi\Data\SearchCriteria;
use Nikba\LaravelBussystemApi\Tests\TestCase;

class SearchCriteriaTest extends TestCase
{
    public function test_can_create_basic_search_criteria(): void
    {
        $criteria = SearchCriteria::create()
            ->date('2024-12-31')
            ->from(3)
            ->to(7)
            ->bus()
            ->currency('EUR')
            ->language('en');

        $array = $criteria->toArray();

        $this->assertEquals('2024-12-31', $array['date']);
        $this->assertEquals(3, $array['id_from']);
        $this->assertEquals(7, $array['id_to']);
        $this->assertEquals('bus', $array['trans']);
        $this->assertEquals('EUR', $array['currency']);
        $this->assertEquals('en', $array['lang']);
    }

    public function test_can_set_train_stations(): void
    {
        $criteria = SearchCriteria::create()
            ->trainFrom(2200001)
            ->trainTo(5400076)
            ->train();

        $array = $criteria->toArray();

        $this->assertEquals(2200001, $array['point_train_from_id']);
        $this->assertEquals(5400076, $array['point_train_to_id']);
        $this->assertEquals('train', $array['trans']);
    }

    public function test_can_set_airports(): void
    {
        $criteria = SearchCriteria::create()
            ->airportFrom('PRG')
            ->airportTo('VIE')
            ->air();

        $array = $criteria->toArray();

        $this->assertEquals('PRG', $array['id_iata_from']);
        $this->assertEquals('VIE', $array['id_iata_to']);
        $this->assertEquals('air', $array['trans']);
    }

    public function test_can_set_air_passengers(): void
    {
        $criteria = SearchCriteria::create()
            ->air()
            ->airPassengers(2, 1, 1);

        $array = $criteria->toArray();

        $this->assertEquals(2, $array['adt']);
        $this->assertEquals(1, $array['chd']);
        $this->assertEquals(1, $array['inf']);
    }

    public function test_can_set_air_service_class(): void
    {
        $criteria = SearchCriteria::create()
            ->air()
            ->airServiceClass('B');

        $array = $criteria->toArray();

        $this->assertEquals('B', $array['service_class']);
    }

    public function test_can_set_air_direct_flights(): void
    {
        $criteria = SearchCriteria::create()
            ->air()
            ->airDirect(true);

        $array = $criteria->toArray();

        $this->assertEquals(1, $array['direct']);
    }

    public function test_can_set_sorting_options(): void
    {
        $criteria = SearchCriteria::create()
            ->sortByPrice();

        $array = $criteria->toArray();

        $this->assertEquals('price', $array['sort_type']);

        $criteria->sortByTime();
        $array = $criteria->toArray();

        $this->assertEquals('time', $array['sort_type']);
    }

    public function test_can_set_transfer_options(): void
    {
        $criteria = SearchCriteria::create()
            ->directOnly();

        $array = $criteria->toArray();

        $this->assertEquals('0', $array['change']);

        $criteria->allowTransfers('auto');
        $array = $criteria->toArray();

        $this->assertEquals('auto', $array['change']);
    }

    public function test_can_set_period(): void
    {
        $criteria = SearchCriteria::create()
            ->period(3);

        $array = $criteria->toArray();

        $this->assertEquals(3, $array['period']);

        // Test boundary limits
        $criteria->period(-5); // Should be limited to -3
        $array = $criteria->toArray();
        $this->assertEquals(-3, $array['period']);

        $criteria->period(20); // Should be limited to 14
        $array = $criteria->toArray();
        $this->assertEquals(14, $array['period']);
    }

    public function test_can_include_sold_out_routes(): void
    {
        $criteria = SearchCriteria::create()
            ->includeSoldOut(true);

        $array = $criteria->toArray();

        $this->assertEquals(1, $array['get_all_departure']);

        $criteria->includeSoldOut(false);
        $array = $criteria->toArray();

        $this->assertEquals(0, $array['get_all_departure']);
    }

    public function test_can_add_custom_parameters(): void
    {
        $criteria = SearchCriteria::create()
            ->addParam('custom_param', 'custom_value')
            ->addParam('another_param', 123);

        $array = $criteria->toArray();

        $this->assertEquals('custom_value', $array['custom_param']);
        $this->assertEquals(123, $array['another_param']);
    }

    public function test_uses_default_values(): void
    {
        $criteria = SearchCriteria::create();
        $array = $criteria->toArray();

        $this->assertEquals(date('Y-m-d'), $array['date']);
        $this->assertEquals('all', $array['trans']);
        $this->assertEquals('auto', $array['change']);
        $this->assertEquals(0, $array['period']);
        $this->assertEquals('time', $array['sort_type']);
        $this->assertEquals(0, $array['get_all_departure']);
    }

    public function test_method_chaining_works(): void
    {
        $criteria = SearchCriteria::create()
            ->date('2024-12-31')
            ->from(3)
            ->to(7)
            ->bus()
            ->currency('EUR')
            ->language('en')
            ->sortByPrice()
            ->period(3)
            ->includeSoldOut(true);

        $this->assertInstanceOf(SearchCriteria::class, $criteria);
        
        $array = $criteria->toArray();
        $this->assertEquals('2024-12-31', $array['date']);
        $this->assertEquals('price', $array['sort_type']);
        $this->assertEquals(3, $array['period']);
        $this->assertEquals(1, $array['get_all_departure']);
    }
}