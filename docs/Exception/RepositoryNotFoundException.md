# RepositoryNotFoundException

**The repository manager has no repository registered for the given object.**

The object's repository is the actual service that stores the collection of objects.
They are meant to contain a single type of object.

## What triggers it?

#### Tried to save or load an object into the Persister

**Trigger example:**

```php
class MyClass {}

$metadataManager = new MetadataManager();
$repositoryManager = new RepositoryManager();

$transactionFactory = new TransactionFactory($metadataManager, $repositoryManager);
$persister = new Persister($repositoryManager, $transactionFactory);

// The following line will trigger the exception because the `MyClass` class
// has not being registered into the `RepositoryManager`.
$persister->save(new MyClass());
```

**Solution:**

Make sure the object's class has an entry into the RepositoryManager.

For example:

```php
class MyClass {}

$metadataManager = new MetadataManager();
$repositoryManager = new RepositoryManager();

$transactionFactory = new TransactionFactory($metadataManager, $repositoryManager);
$persister = new Persister($repositoryManager, $transactionFactory);

// The following line will prevent the error
$repositoryManager->addRepository(MyClass::class, /* Repository */);

$persister->save(new MyClass());
```

#### Tried to save or load an object into the Persister with a repository for its class:

**Trigger example:**

```php
class MyRelatedClass {}

class MyClass
{
    private $relations = [];
    public function addRelation($related) { $this->relations[] = $related; }
}

$metadataManager = new MetadataManager();
$repositoryManager = new RepositoryManager();

$transactionFactory = new TransactionFactory($metadataManager, $repositoryManager);
$persister = new Persister($repositoryManager, $transactionFactory);

$repositoryManager->addRepository(MyClass::class, /* Repository */);

$object = new MyClass();
$object->addRelation(new MyRelatedClass());

// The following line will trigger the exception because the `MyRelatedClass` class
// has not being registered into the `RepositoryManager`.
$persister->save($object);
```

**Solution:**

Make sure any class saved or loaded through a relation of the given object
has also a registry into the RepositoryManager (it is only needed for those
classes found through a registered relation).

For example:

```php
class MyRelatedClass {}

class MyClass
{
    private $relations = [];
    public function addRelation($related) { $this->relations[] = $related; }
}

$metadataManager = new MetadataManager();
$repositoryManager = new RepositoryManager();

$transactionFactory = new TransactionFactory($metadataManager, $repositoryManager);
$persister = new Persister($repositoryManager, $transactionFactory);

$repositoryManager->addRepository(MyClass::class, /* Repository */);

// The following line will prevent the error
$repositoryManager->addRepository(MyRelatedClass::class, /* Repository */);

$object = new MyClass();
$object->addRelation(new MyRelatedClass());
$persister->save($object);
```
