<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Testing\TestResponse;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function assertJsonWithoutFields(TestResponse $response, array $expectedJson, array $ignoreFields)
    {
        $actualJson = $response->json();

        $filterFields = function (&$array, $fields) use (&$filterFields) {
            foreach ($array as $key => &$value) {
                if (is_array($value)) {
                    $filterFields($value, $fields);
                } else {
                    if (in_array($key, $fields)) {
                        unset($array[$key]);
                    }
                }
            }
        };

        $filterFields($actualJson, $ignoreFields);

        $this->assertEquals($expectedJson, $actualJson);
    }
}
