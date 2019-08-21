# Skrz\Meta

[![Build Status](https://travis-ci.org/skrz/meta.svg?branch=master)](https://travis-ci.org/skrz/meta)
[![Downloads this Month](https://img.shields.io/packagist/dm/skrz/meta.svg)](https://packagist.org/packages/skrz/meta)
[![Latest stable](https://img.shields.io/packagist/v/skrz/meta.svg)](https://packagist.org/packages/skrz/meta)

> Different wire formats, different data sources, single object model

## Requirements

`Skrz\Meta` requires PHP `>= 7.1` and Symfony `>= 2.7.0`.

## Installation

Add as [Composer](https://getcomposer.org/) dependency:

```sh
$ composer require skrz/meta
```

## Why?

At [Skrz.cz](http://skrz.cz/), we work heavily with many different input/output formats and data sources (databases).
E.g. data from partners come in as __XML feeds__; internally our __micro-service architecture__ encodes data into __JSON__ as wire
format; data can come from __MySQL, Redis, and Elasticsearch__ databases, and also has to be put in there.

However, in our PHP code base we want single object model that we could also share between projects. This need came mainly
from __micro services' protocols__ that got quite wild - nobody really knew what services sent to each other.

Serialization/deserialization had to be __fast__, therefore we created concept of so-called _meta classes_. A meta class is an
object's companion class that handles object's serialization/deserialization from/into many different formats. Every class
has exactly one meta class, in which methods from different _modules_ are combined - _modules_ can use each others methods
(e.g. `JsonModule` uses methods generated by `PhpModule`).


## Usage

Have simple value object:

```php
namespace Skrz\API;

class Category
{
    /** @var string */
    public $name;

    /** @var string */
    public $slug;

    /** @var Category */
    public $parentCategory;

}
```

You would like to serialize object into JSON. What you might do is to create method `toJson`:

```php
public function toJson()
{
    return json_encode(array(
        "name" => $this->name,
        "slug" => $this->slug,
        "parentCategory" => $this->parentCategory ? $this->parentCategory->toJson() : null
    ));
}
```

Creating such method for every value object that gets sent over wire is tedious and error-prone. So you generate
meta class that implements such methods.

Meta classes are generated according to _meta spec_. A meta spec is a class extending `Skrz\Meta|AbstractMetaSpec`:

```php
namespace Skrz;

use Skrz\Meta\AbstractMetaSpec;
use Skrz\Meta\JSON\JsonModule;
use Skrz\Meta\PHP\PhpModule;

class ApiMetaSpec extends AbstractMetaSpec
{

    protected function configure()
    {
        $this->match("Skrz\\API\\*")
            ->addModule(new PhpModule())
            ->addModule(new JsonModule());
    }

}
```

Method `configure()` initializes spec with _matchers_ and _modules_. A matcher is a set of classes that satisfy certain
criteria (e.g. namespace, class name). A module is generator that takes class matched by the matcher and generates
module-specific methods in the meta class. `ApiMetaSpec` creates meta classes for every class directly in `Skrz\API` namespace
(it does not include classes in sub-namespaces, e.g. `Skrz\API\Meta`). The meta classes are generated from PHP and JSON modules
(`Skrz\Meta\BaseModule` providing basic functionality of a meta class is added automatically).

To actually generate classes, you have supply some files to spec to process:

```php
use Symfony\Component\Finder\Finder;

$files = array_map(function (\SplFileInfo $file) {
    return $file->getPathname();
}, iterator_to_array(
    (new Finder())
        ->in(__DIR__ . "/API")
        ->name("*.php")
        ->notName("*Meta*")
        ->files()
));

$spec = new ApiMetaSpec();
$spec->processFiles($files);
```

Similar code should be part of your build process (or in development part of Grunt watch task etc.).

By default, spec generates meta class in `Meta` sub-namespace with `Meta` suffix (e.g. `Skrz\API\Category` -> `Skrz\API\Meta\CategoryMeta`)
and stores it inside `Meta` sub-directory of original class's directory.

After the meta classes has been generated, usage is quite simple:

```php
use Skrz\API\Category;
use Skrz\API\Meta\CategoryMeta;

$parentCategory = new Category();
$parentCategory->name = "The parent category";
$parentCategory->slug = "parent-category";

$childCategory = new Category();
$childCategory->name = "The child category";
$childCategory->slug = "child-category";
$childCategory->parentCategory = $parentCategory;


var_export(CategoryMeta::toArray($childCategory));
// array(
//     "name" => "The child category",
//     "slug" => "child-category",
//     "parentCategory" => array(
//         "name" => "The parent category",
//         "slug" => "parent-category",
//         "parentCategory" => null,
//     ),
// )


echo CategoryMeta::toJson($childCategory);
// {"name":"The child category","slug":"child-category","parentCategory":{"name":"The parent category","slug":"parent-category","parentCategory":null}}


$someCategory = CategoryMeta::fromJson(array(
    "name" => "Some category",
    "ufo" => 42, // unknown fields are ignored
));

var_export($someCategory instanceof Category);
// TRUE

var_export($someCategory->name === "Some category");
// TRUE
```

### Fields

- Fields represent set of symbolic field paths.
- They are composite (fields can have sub-fields).
- Fields can be supplied as `$filter` parameters in `to*()` methods.

```php
use Skrz\API\Category;
use Skrz\API\Meta\CategoryMeta;
use Skrz\Meta\Fields\Fields;

$parentCategory = new Category();
$parentCategory->name = "The parent category";
$parentCategory->slug = "parent-category";

$childCategory = new Category();
$childCategory->name = "The child category";
$childCategory->slug = "child-category";
$childCategory->parentCategory = $parentCategory;


var_export(CategoryMeta::toArray($childCategory, null, Fields::fromString("name,parentCategory{name}")));
// array(
//     "name" => "The child category",
//     "parentCategory" => array(
//         "name" => "The parent category",
//     ),
// )
```

Fields are inspired by:

- [Facebook Graph API's `?fields=...` query parameter](https://developers.facebook.com/docs/graph-api/using-graph-api#fields)
- [Google Protocol Buffers' `FieldMask`](https://github.com/google/protobuf/blob/master/src/google/protobuf/field_mask.proto) (and its [JSON serialization](https://developers.google.com/protocol-buffers/docs/proto3#json))

### Annotations

`Skrz\Meta` uses [Doctrine annotation parser](https://github.com/doctrine/annotations). Annotations can change mappings.
Also `Skrz\Meta` offers so called _groups_ - different sources can offer different field names, however, they map onto same object.


### `@PhpArrayOffset`

`@PhpArrayOffset` annotation can be used to change name of outputted keys in arrays generated by `toArray` and inputs to `fromArray`:

```php
namespace Skrz\API;

use Skrz\Meta\PHP\PhpArrayOffset;

class Category
{
    /**
     * @var string
     *
     * @PhpArrayOffset("THE_NAME")
     * @PhpArrayOffset("name", group="javascript")
     */
    protected $name;

    /**
     * @var string
     *
     * @PhpArrayOffset("THE_SLUG")
     * @PhpArrayOffset("slug", group="javascript")
     */
    protected $slug;

    public function getName() { return $this->name; }

    public function getSlug() { return $this->slug; }

}

// ...

use Skrz\API\Meta\CategoryMeta;

$category = CategoryMeta::fromArray(array(
    "THE_NAME" => "My category name",
    "THE_SLUG" => "category",
    "name" => "Different name" // name is not an unknown field, so it is ignored
));

var_export($category->getName());
// "My category name"

var_export($category->getSlug());
// "category"

var_export(CategoryMeta::toArray($category, "javascript"));
// array(
//     "name" => "My category name",
//     "slug" => "category",
// )
```


### `@JsonProperty`

`@JsonProperty` marks names of JSON properties. (Internally every group created by `@JsonProperty` creates PHP group
prefixed by `json:` - PHP object is first mapped to array using `json:` group, then the array is serialized using
`json_encode()`.)

```php
namespace Skrz\API;

use Skrz\Meta\PHP\PhpArrayOffset;
use Skrz\Meta\JSON\JsonProperty;

class Category
{
    /**
     * @var string
     *
     * @PhpArrayOffset("THE_NAME")
     * @JsonProperty("NAME")
     */
    protected $name;

    /**
     * @var string
     *
     * @PhpArrayOffset("THE_SLUG")
     * @JsonProperty("sLuG")
     */
    protected $slug;

    public function getName() { return $this->name; }

    public function getSlug() { return $this->slug; }

}

// ...

use Skrz\API\Meta\CategoryMeta;

$category = CategoryMeta::fromArray(array(
    "THE_NAME" => "My category name",
    "THE_SLUG" => "category",
));

var_export(CategoryMeta::toJson($category));
// {"NAME":"My category name","sLuG":"category"}
```

### `@XmlElement` & `@XmlElementWrapper` & `@XmlAttribute` & `@XmlValue`

- Modelled after [javax.xml.bind.annotation](http://docs.oracle.com/javaee/7/api/javax/xml/bind/annotation/package-summary.html).
- Works with [`XMLWriter`](php.net/xmlwriter) or [`DOMDocument`](php.net/domdocument) (for streaming or DOM-based XML APIs).



```php
// example: serialize object to XMLWriter

/**
 * @XmlElement(name="SHOPITEM")
 */
class Product
{
    /**
     * @var string
     *
     * @XmlElement(name="ITEM_ID")
     */
    public $itemId;
    
    /**
     * @var string[]
     *
     * @XmlElement(name="CATEGORYTEXT")
     */
    public $categoryTexts;
}

$product = new Product();
$product->itemId = "SKU123";
$product->categoryTexts = array("Home Appliances", "Dishwashers");

$xml = new \XMLWriter();
$xml->openMemory();
$xml->setIndent(true);
$xml->startDocument();
$meta->toXml($product, null, $xml);
$xml->endDocument();

echo $xml->outputMemory();
// <?xml version="1.0"?>
// <SHOPITEM>
//   <ITEM_ID>SKU123</ITEM_ID>
//   <CATEGORYTEXT>Home Appliances</CATEGORYTEXT>
//   <CATEGORYTEXT>Dishwashers</CATEGORYTEXT>
// </SHOPITEM>
```

For more examples see classes in `test/Skrz/Meta/Fixtures/XML` and `test/Skrz/Meta/XmlModuleTest.php`.

### `@PhpDiscriminatorMap` & `@JsonDiscriminatorMap`

`@PhpDiscriminatorMap` and `@JsonDiscriminatorMap` encapsulate inheritance.

```php
namespace Animals;

use Skrz\Meta\PHP\PhpArrayOffset;

/**
 * @PhpDiscriminatorMap({
 *     "cat" => "Animals\Cat", // specify subclass
 *     "dog" => "Animals\Dog"
 * })
 */
class Animal
{

    /**
     * @var string
     */
    protected $name;
    
}

class Cat extends Animal 
{
    public function meow() { echo "{$this->name}: meow"; }
}

class Dog extends Animal
{
    public function bark() { echo "{$this->name}: woof"; }
}

// ...

use Animals\Meta\AnimalMeta;

$cat = AnimalMeta::fromArray(["cat" => ["name" => "Oreo"]]);
$cat->meow();
// prints "Oreo: meow"

$dog = AnimalMeta::fromArray(["dog" => ["name" => "Mutt"]]);
$dog->bark();
// prints "Mutt: woof"
```

### `@PhpDiscriminatorOffset` & `@JsonDiscriminatorProperty`

`@PhpDiscriminatorOffset` and `@JsonDiscriminatorProperty` make subclasses differentiated using offset/property.

```php
namespace Animals;

use Skrz\Meta\PHP\PhpArrayOffset;

/**
 * @PhpDiscriminatorOffset("type")
 * @PhpDiscriminatorMap({
 *     "cat" => "Animals\Cat", // specify subclass
 *     "dog" => "Animals\Dog"
 * })
 */
class Animal
{

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $name;
    
}

class Cat extends Animal 
{
    public function meow() { echo "{$this->name}: meow"; }
}

class Dog extends Animal
{
    public function bark() { echo "{$this->name}: woof"; }
}

// ...

use Animals\Meta\AnimalMeta;

$cat = AnimalMeta::fromArray(["type" => "cat", "name" => "Oreo"]);
$cat->meow();
// prints "Oreo: meow"

$dog = AnimalMeta::fromArray(["type" => "dog", "name" => "Mutt"]);
$dog->bark();
// prints "Mutt: woof"
```

## Known limitations

- There can be at most 31/63 groups in one meta class. Group name is encoded using bit in integer type. PHP integer
is platform dependent and always signed, therefore there can be at most 31/63 groups depending on platform the PHP's running on.


## TODO

- YAML - just like JSON
- `@XmlElementRef`

## License

The MIT license. See `LICENSE` file.
