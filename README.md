## Dumbsmart Repositories

This project provides a layer that sits in between your project and a collection of fake repositories
so that each document is stored into its own repository regardless of whether it was persisted as a relation of
another object.
This layer is designed to be used on testing environments or on low data-level-access applications. Using this
project in production is discouraged.

[![License](https://poser.pugx.org/carlosv2/dumbsmart-repositories/license)](https://packagist.org/packages/carlosv2/dumbsmart-repositories)
[![Build Status](https://travis-ci.org/carlosV2/DumbsmartRepositories.svg?branch=master)](https://travis-ci.org/carlosV2/DumbsmartRepositories)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/500011c2-4635-4827-b00e-c253b3502171/mini.png)](https://insight.sensiolabs.com/projects/500011c2-4635-4827-b00e-c253b3502171)

## Why?

When using fake repositories is common to use functions like `serialize` or `json_encode` in order to make
data easily persistable. A side effects of using these methods is that related documents are stored along with
the parent document. As a result, querying this document from its own repository produces no results, and modifying
its data means loading any potential document related with the one that must be modified.
This is usually not a problem for small projects, but with large projects where data is complex, unforseen consequences can ensue.

### Installation

Install with:
```sh
$ composer require --dev carlosv2/dumbsmart-repositories
```

### Usage

Imagine you have the following entities:
```php
class User
{
    private $id;
    private $posts;
    
    public function __construct($id) { $this->id = $id; }
    public function getId() { return $this->id; }
    public function setPosts(array $posts) { $this->posts = $posts; }
}

class Post
{
    private $id;
    
    public function __construct($id) { $this->id = $id; }
    public function getId() { return $this->id; }
}
```

In order to use them, you first need to configure this layer:
```php
// Configure the metadata
$metadataManager = new MetadataManager();

$userMetadata = new Metadata(new AccessorObjectIdentifier('getId'));
$userMetadata->setRelation(new OneToManyRelation('posts'));
$metadataManager->addMetadata(User::class, $userMetadata);

$postMetadata = new Metadata(new AccessorObjectIdentifier('getId'));
$metadataManager->addMetadata(Post::class, $postMetadata);


// Configure the repositories
$repositoryManager = new RepositoryManager();
$repositoryManager->addRepository(User::class, new InMemoryRepository(new AccessorObjectIdentifier('getId')));
$repositoryManager->addRepository(Post::class, new InMemoryRepository(new AccessorObjectIdentifier('getId')));


// Create the persister object
$transactionFactory = new TransactionFactory($metadataManager, $repositoryManager);
$persister = new Persister($repositoryManager, $transactionFactory);
```

Once you have configured the layer, you can start using it like this:
```php
$post1 = new Post(1);
$post2 = new Post(2);

$user = new User(1);
$user->setPosts([$post1, $post2]);

$persister->save($user);

// This returns an object with same properties as $post2. However it does
// not return same object because it has been serialized and unserialized
$persister->findById(Post::class, 2);
```

If you were using [everzet/persisted-objects](https://github.com/everzet/persisted-objects) previously, you don't
even need to modify your code, only the way you build your repositories:
```php
// $persister is an instance of carlosV2\DumbsmartRepositories\Persister
$persister = ... ;

// FrontRepository implements Everzet\PersistedObjects\Repository
$frontRepository = new FrontRepository($persister, YourVeryOwnClass::class);
$repository = new YourVeryOwnClassRepository($frontRepository);
```
