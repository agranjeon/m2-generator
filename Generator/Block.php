<?php

declare(strict_types=1);

namespace Agranjeon\Generator\Generator;

/**
 * @author Alexandre Granjeon <alexandre.granjeon@gmail.com>
 */
class Block extends AbstractGenerator
{
    /**
     * Description $genericButtonBody field
     *
     * @var string $genericButtonBody
     */
    protected $genericButtonBody = '/**
 * Context
 *
 * @var Context $context
 */
protected $context;
/**
 * <entityName> Repository Interface
 *
 * @var <entityName>RepositoryInterface $<variableName>Repository
 */
protected $<variableName>Repository;

/**
 * GenericButton Constructor.
 *
 * @param Context                  $context
 * @param <entityName>RepositoryInterface $<variableName>Repository
 */
public function __construct(
    Context $context,
    <entityName>RepositoryInterface $<variableName>Repository
) {
    $this->context         = $context;
    $this-><variableName>Repository = $<variableName>Repository;
}

/**
 * Return Current <entityName> Id
 *
 * @return int
 *
 * @throws LocalizedException
 */
public function getEntityId(): int
{
    /** @var int $<variableName>Id */
    $<variableName>Id = $this->context->getRequest()->getParam(\'id\');

    if ($<variableName>Id) {
        /** @var <entityName> $<variableName> */
        $<variableName> = $this-><variableName>Repository->getById((int)$<variableName>Id);

        return (int)$<variableName>->getId();
    }

    return 0;
}

/**
 * Generate url by route and parameters
 *
 * @param string   $route
 * @param string[] $params
 *
 * @return string
 */
public function getUrl(string $route = \'\', array $params = []): string
{
    return $this->context->getUrlBuilder()->getUrl($route, $params);
}';
    /**
     * Description $backButtonBody field
     *
     * @var string $backButtonBody
     */
    protected $backButtonBody = '/**
 * Retrieve button data
 *
 * @return string[]
 */
public function getButtonData(): array
{
    return [
        \'label\'      => __(\'Back\'),
        \'on_click\'   => sprintf("location.href = \'%s\';", $this->getBackUrl()),
        \'class\'      => \'back\',
        \'sort_order\' => 10
    ];
}

/**
 * Get URL for back (reset) button
 *
 * @return string
 */
public function getBackUrl(): string
{
    return $this->getUrl(\'*/*/\');
}';
    /**
     * Description $saveButtonBody field
     *
     * @var string $saveButtonBody
     */
    protected $saveButtonBody = '/**
 * Get Button Data
 *
 * @return string[]
 */
public function getButtonData(): array
{
    return [
        \'label\'          => __(\'Save\'),
        \'class\'          => \'save primary\',
        \'sort_order\'     => 10,
        \'data_attribute\' => [
            \'mage-init\' => [
                \'button\' => [\'event\' => \'save\']
            ],
            \'form-role\' => \'save\',
        ],
    ];
}';
    /**
     * Description $deleteButtonBody field
     *
     * @var string $deleteButtonBody
     */
    protected $deleteButtonBody = '/**
 * Get Button Data
 *
 * @return string[]
 * @throws LocalizedException
 */
public function getButtonData(): array
{
    if (!$this->getEntityId()) {
        return [];
    }

    return [
        \'label\'      => __(\'Delete\'),
        \'class\'      => \'delete\',
        \'sort_order\' => 20,
        \'on_click\'   => \'deleteConfirm("\' .
            __(\'Are you sure you want to do this?\') .
            \'", "\' .
            $this->getDeleteUrl() .
            \'", {"data": {}})\',
    ];
}

/**
 * URL to send delete requests to.
 *
 * @return string
 * @throws LocalizedException
 */
public function getDeleteUrl(): string
{
    return $this->getUrl(\'*/*/delete\', [\'id\' => $this->getEntityId()]);
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
    public function generate(string $moduleName, string $entityName, array $fields, bool $generateBackend, string $tableName = ''): void
    {
        if (!$generateBackend) {
            return;
        }

        $this->generateBackButton($moduleName, $entityName);
        $this->generateSaveButton($moduleName, $entityName);
        $this->generateDeleteButton($moduleName, $entityName);
        $this->generateGenericButton($moduleName, $entityName);
    }

    /**
     * Description generateBackButton function
     *
     * @param string $moduleName
     * @param string $entityName
     *
     * @return void
     */
    protected function generateBackButton(string $moduleName, string $entityName)
    {
        /** @var string[] $placeHolders */
        $placeHolders = [
            '<namespace>',
            '<useStatement>',
            '<interfaceAnnotation>',
            '<entityClassName>',
            '<interfaceBody>',
        ];
        /** @var string $className */
        $className = 'BackButton';
        /** @var string $namespace */
        $namespace = str_replace('_', '\\', $moduleName) . '\Block\AdminHtml\\' . $entityName . '\Edit;';
        /** @var string[] $replacements */
        $replacements = [
            $namespace,
            '
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
',
            $this->generateClassPhpDoc($namespace, $className, 'Class'),
            'class ' . $className . ' extends GenericButton implements ButtonProviderInterface',
            $this->prefixCodeWithSpaces($this->backButtonBody),
        ];

        /** @var string $code */
        $code = str_replace($placeHolders, $replacements, $this->classTemplate) . "\n";
        $this->writeFile($moduleName, $namespace, $className, 'php', $code);
    }

    /**
     * Description generateSaveButton function
     *
     * @param string $moduleName
     * @param string $entityName
     *
     * @return void
     */
    protected function generateSaveButton(string $moduleName, string $entityName)
    {
        /** @var string[] $placeHolders */
        $placeHolders = [
            '<namespace>',
            '<useStatement>',
            '<interfaceAnnotation>',
            '<entityClassName>',
            '<interfaceBody>',
        ];
        /** @var string $className */
        $className = 'SaveButton';
        /** @var string $namespace */
        $namespace = str_replace('_', '\\', $moduleName) . '\Block\AdminHtml\\' . $entityName . '\Edit;';
        /** @var string[] $replacements */
        $replacements = [
            $namespace,
            '
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
',
            $this->generateClassPhpDoc($namespace, $className, 'Class'),
            'class ' . $className . ' extends GenericButton implements ButtonProviderInterface',
            $this->prefixCodeWithSpaces($this->saveButtonBody),
        ];

        /** @var string $code */
        $code = str_replace($placeHolders, $replacements, $this->classTemplate) . "\n";
        $this->writeFile($moduleName, $namespace, $className, 'php', $code);
    }

    /**
     * Description generateDeleteButton function
     *
     * @param string $moduleName
     * @param string $entityName
     *
     * @return void
     */
    protected function generateDeleteButton(string $moduleName, string $entityName)
    {
        /** @var string[] $placeHolders */
        $placeHolders = [
            '<namespace>',
            '<useStatement>',
            '<interfaceAnnotation>',
            '<entityClassName>',
            '<interfaceBody>',
        ];
        /** @var string $className */
        $className = 'DeleteButton';
        /** @var string $namespace */
        $namespace = str_replace('_', '\\', $moduleName) . '\Block\AdminHtml\\' . $entityName . '\Edit;';
        /** @var string[] $replacements */
        $replacements = [
            $namespace,
            '
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
',
            $this->generateClassPhpDoc($namespace, $className, 'Class'),
            'class ' . $className . ' extends GenericButton implements ButtonProviderInterface',
            $this->prefixCodeWithSpaces($this->deleteButtonBody),
        ];

        /** @var string $code */
        $code = str_replace($placeHolders, $replacements, $this->classTemplate) . "\n";
        $this->writeFile($moduleName, $namespace, $className, 'php', $code);
    }

    /**
     * Description generateGenericButton function
     *
     * @param string $moduleName
     * @param string $entityName
     *
     * @return void
     */
    protected function generateGenericButton(string $moduleName, string $entityName)
    {
        /** @var string[] $placeHolders */
        $placeHolders = [
            '<namespace>',
            '<useStatement>',
            '<interfaceAnnotation>',
            '<entityClassName>',
            '<interfaceBody>',
        ];
        /** @var string $className */
        $className = 'GenericButton';
        /** @var string $moduleNamespace */
        $moduleNamespace = str_replace('_', '\\', $moduleName);
        /** @var string $namespace */
        $namespace = $moduleNamespace . '\Block\AdminHtml\\' . $entityName . '\Edit;';
        /** @var string[] $replacements */
        $replacements = [
            $namespace,
            '
use ' . $moduleNamespace . '\Api\\' . $entityName . 'RepositoryInterface;
use ' . $moduleNamespace . '\Model\\' . $entityName . ';
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Exception\LocalizedException;
',
            $this->generateClassPhpDoc($namespace, $className, 'Class'),
            'class ' . $className,
            $this->prefixCodeWithSpaces(
                str_replace(
                    ['<variableName>', '<entityName>'],
                    [lcfirst($entityName), $entityName],
                    $this->genericButtonBody
                )
            ),
        ];

        /** @var string $code */
        $code = str_replace($placeHolders, $replacements, $this->classTemplate) . "\n";
        $this->writeFile($moduleName, $namespace, $className, 'php', $code);
    }
}
