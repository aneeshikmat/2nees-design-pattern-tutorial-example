<?php
/**
 * This example just to simulate how Composite can be work
 * 2nees.com
 */

interface WebsiteComponent {
    public function getDetails(): array;
}

/**
 * Class BasicWebsite - Concrete Component
 */
class BasicWebsite implements WebsiteComponent {
    private float $price;

    /**
     * BasicWebsite constructor.
     * @param float $price
     */
    public function __construct(float $price)
    {
        $this->price = $price;
    }

    public function getDetails(): array
    {
        return [
            "Price" => $this->price,
            "Type" => "Basic",
            "Options" => ["HomePage", "EnLanguage", "ContactUs-EN", "AboutUS-EN"],
            "Description" => "Your looking to Basic Website and this is price is: {$this->price}$"
        ];
    }
}

/**
 * Class WebsiteBaseDecorator - Base Decorator
 */
class WebsiteBaseDecorator implements WebsiteComponent {
    protected WebsiteComponent $websiteComponent;

    /**
     * WebsiteBaseDecorator constructor.
     * @param WebsiteComponent $websiteComponent
     */
    public function __construct(WebsiteComponent $websiteComponent)
    {
        $this->websiteComponent = $websiteComponent;
    }

    public function getDetails(): array
    {
        return $this->websiteComponent->getDetails();
    }
}

/**
 * Class MultiLanguageWebsite - Concrete Decorators
 */
class MultiLanguageWebsite extends WebsiteBaseDecorator {
    public function getDetails(): array
    {
        $base = parent::getDetails();
        $price = 600 + $base["Price"];

        return [
            "Price" => $price,
            "Type" => "{$base['Type']} + MultiLanguage",
            "Options" => array_merge($base['Options'], ["HomePage-AR","ContactUs-AR", "AboutUS-AR", "ArLanguage"]),
            "Description" => "Your looking to Basic MultiLanguage Website and this is price is: {$price}$"
        ];
    }
}

/**
 * Class ReportsDashboardWebsite - Another Concrete Decorators
 */
class ReportsDashboardWebsite extends WebsiteBaseDecorator {
    public function getDetails(): array
    {
        $base = parent::getDetails();
        $price = 444 + $base["Price"];

        $options = $base['Options'];
        array_push($options, "DashBoard-En");
        if(in_array("ArLanguage", $options)){
            array_push($options, "DashBoard-AR");
        }

        return [
            "Price" => $price,
            "Type" => "{$base['Type']} + AddReportsDashboard",
            "Options" => $options,
            "Description" => "Your looking to Basic Report Website and this is price is: {$price}$"
        ];
    }
}

/**
 * Class DrupalWebsite - Another Concrete Decorators
 */
class DrupalWebsite extends WebsiteBaseDecorator {
    public function getDetails(): array
    {
        $base = parent::getDetails();
        $price = 200 + $base["Price"];

        return [
            "Price" => $price,
            "Type" => "{$base['Type']} + Drupal",
            "Options" => array_merge($base['Options'], ["user1"]),
            "Description" => "Your looking to Drupal CMS Website and this is price is: {$price}$"
        ];
    }
}

/**
 * Class YiiWebsite - Another Concrete Decorators
 */
class YiiWebsite extends WebsiteBaseDecorator {
    public function getDetails(): array
    {
        $base = parent::getDetails();
        $price = 1200 + $base["Price"];

        return [
            "Price" => $price,
            "Type" => "{$base['Type']} + Yii",
            "Options" => array_merge($base['Options'], ["AdminPage", "useDesignPattern", "WorkWithMySql", "WorkWithRedis"]),
            "Description" => "Your looking to Yii Website and this is price is: {$price}$"
        ];
    }
}

// Fake Checkbox/Radio from UI
$miltiLangauge          = true;
$linkWithReportWebsite  = true;
$websiteType = 3;// 1 or 2, 3

// Call Basic or Default Implementation
$website = new BasicWebsite(500);
// Add Multi MultiLanguage
if($miltiLangauge) {
    $website = new MultiLanguageWebsite($website);
}
// Link website with report website dashboard
if($linkWithReportWebsite) {
    $website = new ReportsDashboardWebsite($website);
}
// Create Professional Website Drupal or Yii
if($websiteType === 2) {
    $website = new DrupalWebsite($website);
}elseif ($websiteType === 3) {
    $website = new YiiWebsite($website);
}

// Print final result (Note: You can print result in any place above and you can use result as you want)
print_r($website->getDetails());
