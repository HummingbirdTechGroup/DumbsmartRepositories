# MetadataNotFoundException

**The metadata manager has no metadata registered for the given object.**

The object's metadata contains all the information to know how to persist and
load the object. For example, it contains the information to compute the object's
ID and the properties that contain relations.

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
// has not being registered into the `MetadataManager`.
$persister->save(new MyClass());
```

**Solution:**

Make sure the object's class has an entry into the MetadataManager with
all the needed data to process it.

For example:

```php
class MyClass {}

$metadataManager = new MetadataManager();
$repositoryManager = new RepositoryManager();

$transactionFactory = new TransactionFactory($metadataManager, $repositoryManager);
$persister = new Persister($repositoryManager, $transactionFactory);

// The following line will prevent the error
$metadataManager->addMetadata(MyClass::class, new Metadata(/* ObjectIdentifier */));

$persister->save(new MyClass());
```

#### Tried to save or load an object into the Persister with metadata for its class:

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

$metadata = new Metadata(/* ObjectIdentifier */);
$metadata->setRelation(new OneToManyRelation('relations'));
$metadataManager->addMetadata(MyClass::class, $metadata);

$object = new MyClass();
$object->addRelation(new MyRelatedClass());

// The following line will trigger the exception because the `MyRelatedClass` class
// has not being registered into the `MetadataManager`.
$persister->save($object);
```

**Solution:**

Make sure any class saved or loaded through a relation of the given object
has also a registry into the MetadataManager (it is only needed for those
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

$metadata = new Metadata(/* ObjectIdentifier */);
$metadata->setRelation(new OneToManyRelation('relations'));
$metadataManager->addMetadata(MyClass::class, $metadata);

// The following line will prevent the error
$metadataManager->addMetadata(MyRelatedClass::class, new Metadata(/* ObjectIdentifier */));

$object = new MyClass();
$object->addRelation(new MyRelatedClass());
$persister->save($object);
```
