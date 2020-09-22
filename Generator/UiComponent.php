<?php

declare(strict_types=1);

namespace Agranjeon\Generator\Generator;

/**
 * @author Alexandre Granjeon <alexandre.granjeon@gmail.com>
 */
class UiComponent extends AbstractGenerator
{
    /**
     * Description $actionsBodyTemplate field
     *
     * @var string $actionsBodyTemplate
     */
    protected $actionsBodyTemplate = '/**
 * <entityName> URL Path Edit
 *
 * @var string <constPrefix>_URL_PATH_EDIT
 */
const <constPrefix>_URL_PATH_EDIT = \'<moduleName>/<lowerEntityName>/edit\';
/**
 * <entityName> URL Path Delete
 *
 * @var string <constPrefix>_URL_PATH_DELETE
 */
const <constPrefix>_URL_PATH_DELETE = \'<moduleName>/<lowerEntityName>/delete\';
/**
 * URL Builder
 *
 * @var UrlInterface $urlBuilder
 */
protected $urlBuilder;

/**
 * <entityName>Actions constructor
 *
 * @param ContextInterface   $context
 * @param UiComponentFactory $uiComponentFactory
 * @param UrlInterface       $urlBuilder
 * @param array              $components
 * @param array              $data
 */
public function __construct(
    ContextInterface $context,
    UiComponentFactory $uiComponentFactory,
    UrlInterface $urlBuilder,
    array $components = [],
    array $data = []
) {
    parent::__construct($context, $uiComponentFactory, $components, $data);

    $this->urlBuilder = $urlBuilder;
}

/**
 * Prepare Data Source
 *
 * @param array[] $dataSource
 *
 * @return array[]
 */
public function prepareDataSource(array $dataSource): array
{
    if (!isset($dataSource[\'data\'][\'items\'])) {
        return $dataSource;
    }

    /**
     * @var array[] $item
     */
    foreach ($dataSource[\'data\'][\'items\'] as &$item) {
        if (!isset($item[\'entity_id\'])) {
            continue;
        }

        $item[$this->getData(\'name\')] = [
            \'edit\' => [
                \'href\' => $this->urlBuilder->getUrl(
                    static::<constPrefix>_URL_PATH_EDIT,
                    [
                        \'id\' => $item[\'entity_id\']
                    ]
                ),
                \'label\' => __(\'Edit\')
            ],
            \'delete\' => [
                \'href\' => $this->urlBuilder->getUrl(
                    static::<constPrefix>_URL_PATH_DELETE,
                    [
                        \'id\' => $item[\'entity_id\']
                    ]
                ),
                \'label\' => __(\'Delete\'),
                \'confirm\' => [
                    \'title\' => __(\'Delete\'),
                    \'message\' => __(\'Are you sure you want to delete item?\')
                ]
            ]
        ];
    }

    return $dataSource;
}';
    /**
     * Description $dataProviderBodyTemplate field
     *
     * @var string $dataProviderBodyTemplate
     */
    protected $dataProviderBodyTemplate = '/**
 * <entityName> Constructor.
 *
 * @param string            $name
 * @param string            $primaryFieldName
 * @param string            $requestFieldName
 * @param CollectionFactory $<variableName>CollectionFactory
 * @param array             $meta
 * @param array             $data
 */
public function __construct(
    $name,
    $primaryFieldName,
    $requestFieldName,
    CollectionFactory $<variableName>CollectionFactory,
    array $meta = [],
    array $data = []
) {
    parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);

    $this->collection = $<variableName>CollectionFactory->create();
}';
    /**
     * Description $formDataProviderBodyTemplate field
     *
     * @var string $formDataProviderBodyTemplate
     */
    protected $formDataProviderBodyTemplate = '/**
 * <entityName> Constructor.
 *
 * @param string            $name
 * @param string            $primaryFieldName
 * @param string            $requestFieldName
 * @param CollectionFactory $<variableName>CollectionFactory
 * @param array             $meta
 * @param array             $data
 */
public function __construct(
    $name,
    $primaryFieldName,
    $requestFieldName,
    CollectionFactory $<variableName>CollectionFactory,
    array $meta = [],
    array $data = []
) {
    parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);

    $this->collection = $<variableName>CollectionFactory->create();
}

/**
 * Get data
 *
 * @return array
 */
public function getData(): array
{
    /** @var mixed[] $data */
    $data = [];
    /** @var <entityName>Model[] $items */
    $items = $this->collection->getItems();
    /** @var <entityName>Model $item */
    foreach ($items as $item) {
        $data[$item->getEntityId()] = $item->getData();
    }

    return $data;
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
        if (!$generateBackend) {
            return;
        }
        $this->generateEntityActions($moduleName, $entityName);
        $this->generateDataProvider($moduleName, $entityName);
        $this->generateFormDataProvider($moduleName, $entityName);
    }

    /**
     * Description generateEntityActions function
     *
     * @param string $moduleName
     * @param string $entityName
     *
     * @return void
     */
    protected function generateEntityActions(string $moduleName, string $entityName)
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
        $namespace = $moduleNamespace . '\Ui\Component\Listing\Column;';
        /** @var string $className */
        $className = $entityName . 'Actions';
        /** @var string[] $replacements */
        $replacements = [
            $namespace,
            '
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
',
            $this->generateClassPhpDoc($namespace, $entityName, 'Class'),
            'class ' . $className . ' extends Column',
            $this->prefixCodeWithSpaces(
                str_replace(
                    ['<entityName>', '<moduleName>', '<constPrefix>', '<lowerEntityName>'],
                    [
                        $entityName,
                        strtolower($moduleName),
                        strtoupper($this->camelToSnake($entityName)),
                        strtolower($entityName),
                    ],
                    $this->actionsBodyTemplate
                )
            ),
        ];

        /** @var string $code */
        $code = str_replace($placeHolders, $replacements, $this->classTemplate) . "\n";
        $this->writeFile($moduleName, $namespace, $className, 'php', $code);
    }

    /**
     * Description generateDataProvider function
     *
     * @param string $moduleName
     * @param string $entityName
     *
     * @return void
     */
    protected function generateDataProvider(string $moduleName, string $entityName)
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
        $namespace = $moduleNamespace . '\Ui\Component\DataProvider;';
        /** @var string[] $replacements */
        $replacements = [
            $namespace,
            '
use ' . $moduleNamespace . '\Model\ResourceModel\\' . $entityName . '\CollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;
',
            $this->generateClassPhpDoc($namespace, $entityName, 'Class'),
            'class ' . $entityName . ' extends AbstractDataProvider',
            $this->prefixCodeWithSpaces(
                str_replace(
                    ['<entityName>', '<variableName>'],
                    [
                        $entityName,
                        lcfirst($entityName),
                    ],
                    $this->dataProviderBodyTemplate
                )
            ),
        ];

        /** @var string $code */
        $code = str_replace($placeHolders, $replacements, $this->classTemplate) . "\n";
        $this->writeFile($moduleName, $namespace, $entityName, 'php', $code);
    }

    /**
     * Description generateFormDataProvider function
     *
     * @param string $moduleName
     * @param string $entityName
     *
     * @return void
     */
    protected function generateFormDataProvider(string $moduleName, string $entityName)
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
        $namespace = $moduleNamespace . '\Ui\Component\DataProvider\Form;';
        /** @var string[] $replacements */
        $replacements = [
            $namespace,
            '
use ' . $moduleNamespace . '\Model\ResourceModel\\' . $entityName . '\CollectionFactory;
use ' . $moduleNamespace . '\Model\\' . $entityName . ' as ' . $entityName . 'Model;
use Magento\Ui\DataProvider\AbstractDataProvider;
',
            $this->generateClassPhpDoc($namespace, $entityName, 'Class'),
            'class ' . $entityName . ' extends AbstractDataProvider',
            $this->prefixCodeWithSpaces(
                str_replace(
                    ['<entityName>', '<variableName>'],
                    [
                        $entityName,
                        lcfirst($entityName),
                    ],
                    $this->formDataProviderBodyTemplate
                )
            ),
        ];

        /** @var string $code */
        $code = str_replace($placeHolders, $replacements, $this->classTemplate) . "\n";
        $this->writeFile($moduleName, $namespace, $entityName, 'php', $code);
    }
}
