<?php

declare(strict_types=1);

namespace Agranjeon\Generator\Generator;

use Agranjeon\Generator\Api\GeneratorInterface;
use Magento\Framework\Module\Dir;
use Magento\Framework\Module\Dir\Reader;

/**
/**
 * @author Alexandre Granjeon <alexandre.granjeon@gmail.com>
 */
abstract class AbstractGenerator implements GeneratorInterface
{
    /**
     * Description $classTemplate field
     *
     * @var string $classTemplate
     */
    protected $classTemplate = '<?php

declare(strict_types=1);

namespace <namespace>
<useStatement>
<interfaceAnnotation>
<entityClassName>
{
<interfaceBody>
}';
    /**
     * Description $classPhpDoc field
     *
     * @var string $classPhpDoc
     */
    protected $classPhpDoc = '/**
 * @author Alexandre Granjeon <alexandre.granjeon@gmail.com>
 */';
    /**
     * Description $constTemplate field
     *
     * @var string $constTemplate
     */
    protected $constTemplate = '/**
 * Description <constName> constant
 *
 * @var <type> <constName>
 */
const <constName> = <fieldName>;';
    /**
     * Description $classPhpDoc field
     *
     * @var string $classPhpDoc
     */
    protected $xmlDoc = '<!--
/**
 * @author Alexandre Granjeon <alexandre.granjeon@gmail.com>
 */
-->';
    /**
     * Description $xmlFileTemplate field
     *
     * @var string $xmlFileTemplate
     */
    protected $xmlFileTemplate = '<?xml version="1.0"?>

<xmlDoc>
<<type> xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:<configXsd>">
<xmlBody>
</<type>>';
    /**
     * Description $typeMapping field
     *
     * @var string[] $typeMapping
     */
    public static $typeMapping = [
        'blob'      => 'string',
        'boolean'   => 'bool',
        'date'      => 'string',
        'datetime'  => 'string',
        'int'       => 'int',
        'smallint'  => 'int',
        'bigint'    => 'int',
        'tinyint'   => 'int',
        'decimal'   => 'string',
        'float'     => 'float',
        'text'      => 'string',
        'timestamp' => 'string',
        'varchar'   => 'string',
    ];
    /**
     * The actual spaces to use for indention.
     *
     * @var string
     */
    protected $spaces = '    ';
    /**
     * Description $reader field
     *
     * @var Reader $reader
     */
    protected $reader;
    /**
     * Description $idFieldName field
     *
     * @var string $idFieldName
     */
    protected $idFieldName;
    /**
     * Description $modulePath field
     *
     * @var string[] $modulePath
     */
    protected $modulePath = [];

    /**
     * AbstractGenerator constructor
     *
     * @param Reader $reader
     */
    public function __construct(
        Reader $reader
    ) {
        $this->reader = $reader;
    }

    /**
     * Description setIdFieldName function
     *
     * @param string $idFieldName
     *
     * @return GeneratorInterface
     */
    public function setIdFieldName(string $idFieldName): GeneratorInterface
    {
        $this->idFieldName = $idFieldName;

        return $this;
    }

    /**
     * Description getIdFieldName function
     *
     * @return string
     */
    public function getIdFieldName(): string
    {
        return $this->idFieldName;
    }

    /**
     * Description getFilePath function
     *
     * @param string $moduleName
     * @param string $namespace
     * @param string $className
     * @param string $extension
     *
     * @return string
     */
    protected function getFilePath(string $moduleName, string $namespace, string $className, string $extension): string
    {
        if (!isset($this->modulePath[$moduleName])) {
            /** @var string $moduleDir */
            $moduleDir = $this->reader->getModuleDir(Dir::MODULE_ETC_DIR, $moduleName);

            $this->modulePath[$moduleName] = str_replace('/' . Dir::MODULE_ETC_DIR, '', $moduleDir);
        }

        /** @var string $namespaceWithoutModule */
        $namespaceWithoutModule = str_replace([str_replace('_', '\\', $moduleName), ';'], '', $namespace);
        /** @var string $path */
        $path = $this->modulePath[$moduleName] . str_replace(
                '\\',
                DIRECTORY_SEPARATOR,
                $namespaceWithoutModule
            ) . DIRECTORY_SEPARATOR . $className . '.' . $extension;

        return $path;
    }

    /**
     * Description writeFile function
     *
     * @param string $moduleName
     * @param string $namespace
     * @param string $className
     * @param string $extension
     * @param string $content
     *
     * @return void
     */
    protected function writeFile(
        string $moduleName,
        string $namespace,
        string $className,
        string $extension,
        string $content
    ): void {
        /** @var string $filePath */
        $filePath = $this->getFilePath($moduleName, $namespace, $className, $extension);

        $dir = dirname($filePath);
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        file_put_contents($filePath, $content);
    }

    /**
     * Camelizes a string.
     *
     * @param string $string The string to camelize.
     *
     * @return string The camelized string.
     */
    protected function camelize(string $string): string
    {
        return str_replace(" ", "", ucwords(strtr($string, "_-", "  ")));
    }

    /**
     * Description camelToSnake function
     *
     * @param string $string
     *
     * @return string
     */
    protected function camelToSnake(string $string): string
    {
        $string = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $string));

        return str_replace('__', '_', $string);
    }

    /**
     * @param string $code
     * @param int    $num
     *
     * @return string
     */
    protected function prefixCodeWithSpaces($code, $num = 1)
    {
        $lines = explode("\n", $code);

        foreach ($lines as $key => $value) {
            if (!empty($value)) {
                $lines[$key] = rtrim(str_repeat($this->spaces, $num) . $lines[$key]);
            }
        }

        return implode("\n", $lines);
    }

    /**
     * Description generateClassPhpDoc function
     *
     * @param string $namespace
     * @param string $entityName
     * @param string $type
     *
     * @return string
     */
    protected function generateClassPhpDoc(string $namespace, string $entityName, string $type): string
    {
        /** @var string $year */
        $year      = (new \DateTime())->format('Y');
        $namespace = str_replace(';', '', $namespace);
        /** @var string[] $replacements */
        $replacements = [
            '<namespace>'  => $namespace,
            '<entityName>' => $entityName,
            '<year>'       => $year,
            '<type>'       => $type,
        ];
        /** @var string $phpDoc */
        $phpDoc = str_replace(
            array_keys($replacements),
            array_values($replacements),
            $this->classPhpDoc
        );

        return $phpDoc;
    }

    /**
     * Description generateXmlDoc function
     *
     * @param string $namespace
     *
     * @return string
     */
    protected function generateXmlDoc(string $namespace): string
    {
        /** @var string $year */
        $year = (new \DateTime())->format('Y');
        /** @var string[] $replacements */
        $replacements = [
            '<namespace>' => $namespace,
            '<year>'      => $year,
        ];
        /** @var string $xmlDoc */
        $xmlDoc = str_replace(
            array_keys($replacements),
            array_values($replacements),
            $this->xmlDoc
        );

        return $xmlDoc;
    }

    /**
     * Description generateEntityConst function
     *
     * @param string $fieldName
     * @param string $value
     * @param string $type
     *
     * @return string
     */
    protected function generateEntityConst(string $fieldName, string $value, string $type)
    {
        /** @var string $constName */
        $constName = strtoupper($fieldName);
        /** @var string[] $replacements */
        $replacements = [
            '<constName>' => $constName,
            '<fieldName>' => '\'' . $value . '\'',
            '<type>'      => $type,
        ];
        /** @var string $method */
        $method = str_replace(
            array_keys($replacements),
            array_values($replacements),
            $this->constTemplate
        );

        return $this->prefixCodeWithSpaces($method);
    }

    /**
     * Description getInterfaceName function
     *
     * @param string $entityName
     *
     * @return string
     */
    protected function getInterfaceName(string $entityName): string
    {
        return $entityName . 'Interface';
    }
}
