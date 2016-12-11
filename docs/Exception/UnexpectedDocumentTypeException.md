# UnexpectedDocumentTypeException

**Document found in the given relation is not `null` or `object`.**

Only `null` and `object`s are allowed to be stored through a relation.
If the relation is tagged as one-to-many, then only `array` composed by
`object`s and `null`s is allowed.

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
