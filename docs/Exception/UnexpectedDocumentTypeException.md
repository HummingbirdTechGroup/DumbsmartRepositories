# UnexpectedDocumentTypeException

**Document found in the given relation is not `null` or `object`.**

If the relation is one-to-one, only `null` and `object` are allowed to be stored.

If the relation is one-to-many, only `array` with `object`s and `null`s is allowed.

## What triggers it?

#### Tried to save or load an object into the Persister

**Trigger example:**

```php
class MyClass
{
    private $relation;
    public function setRelation($value) { $this->relation = $value; }
}

$metadataManager = new MetadataManager();
$repositoryManager = new RepositoryManager();

$transactionFactory = new TransactionFactory($metadataManager, $repositoryManager);
$persister = new Persister($repositoryManager, $transactionFactory);

$metadata = new Metadata(/* ObjectIdentifier */);
$metadata->setRelation(new OneToOneRelation('relation'));
$metadataManager->addMetadata(MyClass::class, $metadata);

$object = new MyClass();
$object->setRelation(4);

// The following line will trigger the exception because the `relation` property
// of the `MyClass` class contains an unallowed value.
$persister->save($object);
```

**Solution:**

Make sure all the object's relations contains `object`s, `null`s or arrays
composed by `obejct`s or `null`s.
