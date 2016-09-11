<?php namespace Propaganistas\LaravelIntl\Tests;

use Orchestra\Testbench\TestCase;
use Propaganistas\LaravelIntl\Facades\Country;
use Propaganistas\LaravelIntl\IntlServiceProvider;

class TestCountry extends TestCase
{
    public function getPackageProviders($app)
    {
        return [IntlServiceProvider::class];
    }

    public function setUp()
    {
        require_once __DIR__ . '/../src/helpers.php';

        parent::setUp();
    }

    public function testHelper()
    {
        $this->assertEquals('Belgium', country('BE'));
        $this->assertEquals('Propaganistas\LaravelIntl\Country', get_class(country()));
    }

    public function testLocalesCanBeChanged()
    {
        $country = Country::setLocale('nl');
        $this->assertEquals('België', $country->name('BE'));

        $country = Country::setLocale('foo');
        $country->setFallbackLocale('fr');
        $this->assertEquals('Belgique', $country->name('BE'));

        $this->app->setLocale('nl');
        $this->assertEquals('België', country('BE'));

        $this->app->setLocale('en');
        $this->assertEquals('Belgium', Country::name('BE'));
    }

    public function testGet()
    {
        $country = Country::get('BE');
        $this->assertEquals('CommerceGuys\Intl\Country\Country', get_class($country));
        $this->assertEquals('BE', $country->getCountryCode());
    }

    public function testAll()
    {
        $countries = Country::all();
        $this->assertArraySubset(['BE' => 'Belgium', 'FR' => 'France'], $countries);

        $countries = Country::setLocale('nl')->all();
        $this->assertArraySubset(['BE' => 'België', 'FR' => 'Frankrijk'], $countries);
    }

    public function testName()
    {
        $country = Country::name('BE');
        $this->assertEquals('Belgium', $country);
    }

    public function testCurrency()
    {
        $currency = Country::currency('BE');
        $this->assertEquals('EUR', $currency);
    }
}