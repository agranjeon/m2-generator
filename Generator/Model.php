<?php

declare(strict_types=1);

namespace Agranjeon\Generator\Generator;

/**
 * @author Alexandre Granjeon <alexandre.granjeon@gmail.com>
 */
class Model extends AbstractGenerator
{
    /**
     * Description $modelTemplateBody field
     *
     * @var string $modelTemplateBody
     */
    protected $modelTemplateBody = '/**
 * Description KEY constant
 *
 * @var string KEY
 */
const KEY = \'<snakeEntityName>\';
/**
 * @var string Name of object id field
 */
protected $_idFieldName = self::<idFieldName>;
/**
 * Prefix of model events names
 *
 * @var string $_eventPrefix
 */
protected $_eventPrefix = \'<eventPrefix>\';

/**
 * Construct
 *
 * @return void
 */
protected function _construct()
{
    $this->_init(<entityName>Resource::class);
}

<stubMethods>';
    /**
     * Description $getMethodTemplate field
     *
     * @var string $getMethodTemplate
     */
    protected static $getMethodTemplate = '/**
 * Description <methodName> function
 *
 * @return <variableType>
 */
public function <methodName>(): <variableType>
{
    return $this->getData(self::<constName>);
}
';
    /**
     * Description $setMethodTemplate field
     *
     * @var string $setMethodTemplate
     */
    protected static $setMethodTemplate = '/**
 * Description <methodName> function
 *
 * @param <variableType> $<variableName>
 *
 * @return <interfaceName>
 */
public function <methodName>(<variableType> $<variableName>): <interfaceName>
{
    return $this->setData(self::<constName>, $<variableName>);
}
';
    /**
     * Description $resourceModelBodyTemplate field
     *
     * @var string $resourceModelBodyTemplate
     */
    protected $resourceModelBodyTemplate = '/**
 * Initialize resource model
 *
 * @return void
 */
protected function _construct()
{
    $this->_init(\'<tableName>\', <entityName>Interface::<idFieldName>);
}';
    /**
     * Description $collectionBodyTemplate field
     *
     * @var string $collectionBodyTemplate
     */
    protected $collectionBodyTemplate = '/**
 * Id Field Name
 *
 * @var string $_idFieldName
 */
protected $_idFieldName = \'<idFieldName>\';
/**
 * Event prefix
 *
 * @var string $_eventPrefix
 */
protected $_eventPrefix = \'<eventPrefix>_collection\';

/**
 * Define resource model
 *
 * @return void
 */
protected function _construct()
{
    $this->_init(<entityName>Model::class, Resource<entityName>::class);
}';
    /**
     * Description $repositoryBodyTemplate field
     *
     * @var string $repositoryBodyTemplate
     */
    protected $repositoryBodyTemplate = '/**
 * Resource <entityName>
 *
 * @var <entityName>Resource $resource
 */
protected $resource;
/**
 * <entityName> Factory
 *
 * @var <entityName>Factory $<variableName>Factory
 */
protected $<variableName>Factory;
/**
 * <entityName> Collection Factory
 *
 * @var <entityName>CollectionFactory $<variableName>CollectionFactory
 */
protected $<variableName>CollectionFactory;
/**
 * Search Results Interface
 *
 * @var SearchResultsInterfaceFactory $searchResultsFactory
 */
protected $searchResultsFactory;

/**
 * <entityName>Repository Constructor.
 *
 * @param <entityName>Resource                 $resource
 * @param <entityName>Factory                  $<variableName>Factory
 * @param <entityName>CollectionFactory        $<variableName>CollectionFactory
 * @param SearchResultsInterfaceFactory $searchResultsFactory
 */
public function __construct(
    <entityName>Resource $resource,
    <entityName>Factory $<variableName>Factory,
    <entityName>CollectionFactory $<variableName>CollectionFactory,
    SearchResultsInterfaceFactory $searchResultsFactory
) {
    $this->resource               = $resource;
    $this-><variableName>Factory           = $<variableName>Factory;
    $this-><variableName>CollectionFactory = $<variableName>CollectionFactory;
    $this->searchResultsFactory   = $searchResultsFactory;
}

/**
 * {@inheritdoc}
 */
public function save(<entityName>Interface $<variableName>): <entityName>Interface
{
    try {
        $this->resource->save($<variableName>);
    } catch (\Exception $exception) {
        throw new CouldNotSaveException(__($exception->getMessage()));
    }

    return $<variableName>;
}

/**
 * {@inheritdoc}
 */
public function getById(int $<variableName>Id): <entityName>Interface
{
    /** @var <entityName> $<variableName> */
    $<variableName> = $this-><variableName>Factory->create();

    $this->resource->load($<variableName>, $<variableName>Id);

    if (!$<variableName>->getId()) {
        throw new NoSuchEntityException(__(\'The <entityName> with the "%1" ID doesn\\\'t exist.\', $<variableName>Id));
    }

    return $<variableName>;
}

/**
 * {@inheritdoc}
 */
public function getList(SearchCriteriaInterface $criteria): SearchResultsInterface
{
    /** @var SearchResultsInterface $searchResults */
    $searchResults = $this->searchResultsFactory->create();
    $searchResults->setSearchCriteria($criteria);
    /** @var Collection $collection */
    $collection = $this-><variableName>CollectionFactory->create();
    /** @var FilterGroup $filterGroup */
    foreach ($criteria->getFilterGroups() as $filterGroup) {
        /** @var Filter $filter */
        foreach ($filterGroup->getFilters() as $filter) {
            /** @var string $condition */
            $condition = $filter->getConditionType() ?: \'eq\';
            $collection->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
        }
    }
    $searchResults->setTotalCount($collection->getSize());
    /** @var array $sortOrders */
    $sortOrders = $criteria->getSortOrders();
    if ($sortOrders) {
        /** @var SortOrder $sortOrder */
        foreach ($sortOrders as $sortOrder) {
            $collection->addOrder(
                $sortOrder->getField(),
                ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? \'ASC\' : \'DESC\'
            );
        }
    }
    $collection->setCurPage($criteria->getCurrentPage());
    $collection->setPageSize($criteria->getPageSize());

    $searchResults->setItems($collection->getItems());
}

/**
 * {@inheritdoc}
 */
public function delete(<entityName>Interface $<variableName>): bool
{
    try {
        $this->resource->delete($<variableName>);
    } catch (\Exception $exception) {
        throw new CouldNotDeleteException(__($exception->getMessage()));
    }

    return true;
}

/**
 * {@inheritdoc}
 */
public function deleteById(int $<variableName>Id): bool
{
    return $this->delete($this->getById($<variableName>Id));
}';

    /**
     * Description generate function
     *
     * @param string   $moduleName
     * @param string   $entityName
     * @param string[] $fields
     * @param bool     $generateBackend
     * @param string   $tableName
     *
     * @return void
     */
    public function generate(
        string $moduleName,
        string $entityName,
        array $fields,
        bool $generateBackend,
        string $tableName = ''
    ): void {
        $this->generateEntityModel($moduleName, $entityName, $fields);
        $this->generateEntityResourceModel($moduleName, $entityName, $tableName);
        $this->generateCollection($moduleName, $entityName);
        $this->generateRepository($moduleName, $entityName);
    }

    /**
     * Description generateEntityModel function
     *
     * @param string   $moduleName
     * @param string   $entityName
     * @param string[] $fields
     *
     * @return void
     */
    protected function generateEntityModel(string $moduleName, string $entityName, array $fields)
    {
        /** @var string[] $placeHolders */
        $placeHolders = [
            '<namespace>',
            '<useStatement>',
            '<interfaceAnnotation>',
            '<entityClassName>',
            '<interfaceBody>',
        ];

        /** @var string $interfaceName */
        $interfaceName = $entityName . 'Interface';
        /** @var string $moduleNamespace */
        $moduleNamespace = str_replace('_', '\\', $moduleName);
        /** @var string $namespace */
        $namespace = $moduleNamespace . '\Model;';
        /** @var string[] $replacements */
        $replacements = [
            $namespace,
            '
use ' . $moduleNamespace . '\Api\Data\\' . $interfaceName . ';
use ' . $moduleNamespace . '\Model\ResourceModel\\' . $entityName . ' as ' . $entityName . 'Resource;
use Magento\Framework\Model\AbstractModel;
',
            $this->generateClassPhpDoc($namespace, $entityName, 'Class'),
            'class ' . $entityName . ' extends AbstractModel implements ' . $interfaceName,
            $this->prefixCodeWithSpaces(
                str_replace(
                    ['<snakeEntityName>', '<entityName>', '<eventPrefix>', '<idFieldName>', '<stubMethods>'],
                    [
                        $this->camelToSnake($entityName),
                        $entityName,
                        strtolower($entityName),
                        strtoupper($this->idFieldName),
                        $this->generateStubMethods($interfaceName, $fields),
                    ],
                    $this->modelTemplateBody
                )
            ),
        ];

        /** @var string $code */
        $code = str_replace($placeHolders, $replacements, $this->classTemplate) . "\n";
        $this->writeFile($moduleName, $namespace, $entityName, 'php', $code);
    }

    /**
     * Description generateEntityResourceModel function
     *
     * @param string $moduleName
     * @param string $entityName
     * @param string $tableName
     *
     * @return void
     */
    protected function generateEntityResourceModel(string $moduleName, string $entityName, string $tableName)
    {
        /** @var string[] $placeHolders */
        $placeHolders = [
            '<namespace>',
            '<useStatement>',
            '<interfaceAnnotation>',
            '<entityClassName>',
            '<interfaceBody>',
        ];

        /** @var string $moduleNamespace */
        $moduleNamespace = str_replace('_', '\\', $moduleName);
        /** @var string $namespace */
        $namespace = $moduleNamespace . '\Model\ResourceModel;';
        /** @var string[] $replacements */
        $replacements = [
            $namespace,
            '
use ' . $moduleNamespace . '\Api\Data\\' . $entityName . 'Interface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
',
            $this->generateClassPhpDoc($namespace, $entityName, 'Class'),
            'class ' . $entityName . ' extends AbstractDb',
            $this->prefixCodeWithSpaces(
                str_replace(
                    ['<entityName>', '<idFieldName>', '<tableName>'],
                    [
                        $entityName,
                        strtoupper($this->idFieldName),
                        $tableName,
                    ],
                    $this->resourceModelBodyTemplate
                )
            ),
        ];

        /** @var string $code */
        $code = str_replace($placeHolders, $replacements, $this->classTemplate) . "\n";
        $this->writeFile($moduleName, $namespace, $entityName, 'php', $code);
    }

    /**
     * Description generateEntityResourceModel function
     *
     * @param string $moduleName
     * @param string $entityName
     *
     * @return void
     */
    protected function generateCollection(string $moduleName, string $entityName)
    {
        /** @var string[] $placeHolders */
        $placeHolders = [
            '<namespace>',
            '<useStatement>',
            '<interfaceAnnotation>',
            '<entityClassName>',
            '<interfaceBody>',
        ];

        /** @var string $moduleNamespace */
        $moduleNamespace = str_replace('_', '\\', $moduleName);
        /** @var string $namespace */
        $namespace = $moduleNamespace . '\Model\ResourceModel\\' . $entityName . ';';
        /** @var string[] $replacements */
        $replacements = [
            $namespace,
            '
use ' . $moduleNamespace . '\Model\\' . $entityName . ' as ' . $entityName . 'Model;
use ' . $moduleNamespace . '\Model\ResourceModel\\' . $entityName . ' as Resource' . $entityName . ';
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
',
            $this->generateClassPhpDoc($namespace, $entityName, 'Class'),
            'class Collection extends AbstractCollection',
            $this->prefixCodeWithSpaces(
                str_replace(
                    ['<entityName>', '<idFieldName>', '<eventPrefix>'],
                    [
                        $entityName,
                        $this->idFieldName,
                        strtolower($entityName),
                    ],
                    $this->collectionBodyTemplate
                )
            ),
        ];

        /** @var string $code */
        $code = str_replace($placeHolders, $replacements, $this->classTemplate) . "\n";
        $this->writeFile($moduleName, $namespace, 'Collection', 'php', $code);
    }

    /**
     * Description generateEntityResourceModel function
     *
     * @param string $moduleName
     * @param string $entityName
     *
     * @return void
     */
    protected function generateRepository(string $moduleName, string $entityName)
    {
        /** @var string[] $placeHolders */
        $placeHolders = [
            '<namespace>',
            '<useStatement>',
            '<interfaceAnnotation>',
            '<entityClassName>',
            '<interfaceBody>',
        ];

        /** @var string $moduleNamespace */
        $moduleNamespace = str_replace('_', '\\', $moduleName);
        /** @var string $namespace */
        $namespace = $moduleNamespace . '\Model;';
        /** @var string $className */
        $className = $entityName . 'Repository';
        /** @var string[] $replacements */
        $replacements = [
            $namespace,
            '
use ' . $moduleNamespace . '\Api\Data\\' . $entityName . 'Interface;
use ' . $moduleNamespace . '\Api\\' . $entityName . 'RepositoryInterface;
use ' . $moduleNamespace . '\Model\ResourceModel\\' . $entityName . ' as ' . $entityName . 'Resource;
use ' . $moduleNamespace . '\Model\ResourceModel\\' . $entityName . '\Collection;
use ' . $moduleNamespace . '\Model\ResourceModel\\' . $entityName . '\CollectionFactory as ' . $entityName . 'CollectionFactory;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\Filter;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
',
            $this->generateClassPhpDoc($namespace, $entityName, 'Class'),
            'class ' . $className . ' implements ' . $entityName . 'RepositoryInterface',
            $this->prefixCodeWithSpaces(
                str_replace(
                    ['<entityName>', '<variableName>'],
                    [
                        $entityName,
                        lcfirst($entityName),
                    ],
                    $this->repositoryBodyTemplate
                )
            ),
        ];

        /** @var string $code */
        $code = str_replace($placeHolders, $replacements, $this->classTemplate) . "\n";
        $this->writeFile($moduleName, $namespace, $className, 'php', $code);
    }

    /**
     * Description generateStubMethods function
     *
     * @param string $entityName
     * @param array  $fields
     *
     * @return string
     */
    protected function generateStubMethods(string $entityName, array $fields): string
    {
        $methods = [];

        /**
         * @var string $column
         * @var string $type
         */
        foreach ($fields as $column => $type) {
            if ($column === 'entity_id') {
                continue;
            }
            $methods[] = $this->generateStubMethod(
                $entityName,
                'set',
                $column,
                $type
            );
            $methods[] = $this->generateStubMethod(
                $entityName,
                'get',
                $column,
                $type
            );
        }

        return rtrim(implode("\n", $methods), "\n");
    }

    /**
     * Description generateStubMethod function
     *
     * @param string $entityName
     * @param string $type
     * @param string $fieldName
     * @param string $typeHint
     *
     * @return string
     */
    protected function generateStubMethod(string $entityName, string $type, string $fieldName, string $typeHint): string
    {
        /** @var string $methodName */
        $methodName = $type . $this->camelize($fieldName);
        /** @var string $variableName */
        $variableName = lcfirst($this->camelize($fieldName));

        /** @var string $var */
        $var = sprintf('%sMethodTemplate', $type);
        /** @var string $template */
        $template = static::$$var;

        /** @var string|null $variableType */
        $variableType = static::$typeMapping[$typeHint] ?? null;
        /** @var string[] $replacements */
        $replacements = [
            '<variableType>'  => $variableType,
            '<variableName>'  => $variableName,
            '<methodName>'    => $methodName,
            '<interfaceName>' => $entityName,
            '<constName>'     => strtoupper($fieldName),
        ];

        /** @var string $method */
        $method = str_replace(
            array_keys($replacements),
            array_values($replacements),
            $template
        );

        return $method;
    }
}
