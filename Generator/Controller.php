<?php

declare(strict_types=1);

namespace Agranjeon\Generator\Generator;

/**
 * @author Alexandre Granjeon <alexandre.granjeon@gmail.com>
 */
class Controller extends AbstractGenerator
{
    /**
     * Description $indexBodyTemplate field
     *
     * @var string $indexBodyTemplate
     */
    protected $indexBodyTemplate = '/**
 * Authorization level of a basic admin session
 *
 * @var string ADMIN_RESOURCE
 */
const ADMIN_RESOURCE = \'<moduleName>::<entityName>\';

/**
 * Description $resultPageFactory field
 *
 * @var PageFactory $resultPageFactory
 */
protected $resultPageFactory;

/**
 * Index Constructor.
 *
 * @param Context     $context
 * @param PageFactory $resultPageFactory
 */
public function __construct(
    Context $context,
    PageFactory $resultPageFactory
) {
    parent::__construct($context);

    $this->resultPageFactory = $resultPageFactory;
}

/**
 * Execute
 *
 * @return Page
 */
public function execute(): Page
{
    /** @var Page $resultPage */
    $resultPage = $this->resultPageFactory->create();

    $resultPage->getConfig()->getTitle()->prepend(__(\'<entityName>s\'));

    return $resultPage;
}';
    /**
     * Description $newActionBodyTemplate field
     *
     * @var string $newActionBodyTemplate
     */
    protected $newActionBodyTemplate = '/**
 * Authorization level of a basic admin session
 *
 * @var string ADMIN_RESOURCE
 */
const ADMIN_RESOURCE = \'<moduleName>::<entityName>\';

/**
 * Forward Factory
 *
 * @var ForwardFactory $resultForwardFactory
 */
protected $resultForwardFactory;

/**
 * NewAction Constructor.
 *
 * @param Context        $context
 * @param ForwardFactory $resultForwardFactory
 */
public function __construct(
    Context $context,
    ForwardFactory $resultForwardFactory
) {
    parent::__construct($context);

    $this->resultForwardFactory = $resultForwardFactory;
}

/**
 * Forward to edit
 *
 * @return Forward
 */
public function execute(): Forward
{
    /** @var Forward $resultForward */
    $resultForward = $this->resultForwardFactory->create();

    return $resultForward->forward(\'edit\');
}';
    /**
     * Description $saveBodyTemplate field
     *
     * @var string $saveBodyTemplate
     */
    protected $saveBodyTemplate = '/**
 * Authorization level of a basic admin session
 *
 * @var string ADMIN_RESOURCE
 */
const ADMIN_RESOURCE = \'<moduleName>::<entityName>\';

/**
 * <entityName> Repository Interface
 *
 * @var <entityName>RepositoryInterface $<variableName>Repository
 */
protected $<variableName>Repository;
/**
 * <entityName> Factory
 *
 * @var <entityName>Factory $<variableName>Factory
 */
protected $<variableName>Factory;

/**
 * Save Constructor.
 *
 * @param Action\Context           $context
 * @param <entityName>RepositoryInterface $<variableName>Repository
 * @param <entityName>Factory             $<variableName>Factory
 */
public function __construct(
    Action\Context $context,
    <entityName>RepositoryInterface $<variableName>Repository,
    <entityName>Factory $<variableName>Factory
) {
    parent::__construct($context);

    $this-><variableName>Repository = $<variableName>Repository;
    $this-><variableName>Factory    = $<variableName>Factory;
}

/**
 * Save action
 *
 * @return Redirect
 * @throws LocalizedException
 * @throws Exception
 */
public function execute(): Redirect
{
    /** @var string[] $data */
    $data = $this->getRequest()->getPostValue();

    /** @var Redirect $resultRedirect */
    $resultRedirect = $this->resultRedirectFactory->create();

    if (!$data) {
        return $resultRedirect->setPath(\'*/*/\');
    }

    /** @var <entityName> $<variableName> */
    $<variableName> = $this-><variableName>Factory->create();

    /** @var int $<variableName>Id */
    $<variableName>Id = $this->getRequest()->getParam(\'id\');
    if ($<variableName>Id) {
        $<variableName> = $this-><variableName>Repository->getById((int)$<variableName>Id);
    }

    $<variableName>->setData($data);
    try {
        $this-><variableName>Repository->save($<variableName>);
        $this->messageManager->addSuccessMessage(__(\'You saved the <entityName>.\'));
    } catch (Exception $exception) {
        $this->messageManager->addExceptionMessage(
            $exception,
            __(\'Something went wrong while saving the <entityName>.\')
        );
    }

    return $resultRedirect->setPath(\'*/*/\');
}';
    /**
     * Description $editBodyTemplate field
     *
     * @var string $editBodyTemplate
     */
    protected $editBodyTemplate = '/**
 * Authorization level of a basic admin session
 *
 * @var string ADMIN_RESOURCE
 */
const ADMIN_RESOURCE = \'<moduleName>::<entityName>\';

/**
 * Core Registry
 *
 * @var Registry $coreRegistry
 */
protected $coreRegistry;
/**
 * Page Factory
 *
 * @var PageFactory $resultPageFactory
 */
protected $resultPageFactory;
/**
 * <entityName> Repository Interface
 *
 * @var <entityName>RepositoryInterface $<variableName>Repository
 */
protected $<variableName>Repository;
/**
 * <entityName> Factory
 *
 * @var <entityName>Factory $<variableName>Factory
 */
protected $<variableName>Factory;

/**
 * Edit Constructor.
 *
 * @param Action\Context           $context
 * @param PageFactory              $resultPageFactory
 * @param Registry                 $registry
 * @param <entityName>RepositoryInterface $<variableName>Repository
 * @param <entityName>Factory             $<variableName>Factory
 */
public function __construct(
    Action\Context $context,
    PageFactory $resultPageFactory,
    Registry $registry,
    <entityName>RepositoryInterface $<variableName>Repository,
    <entityName>Factory $<variableName>Factory
) {
    parent::__construct($context);

    $this->resultPageFactory = $resultPageFactory;
    $this->coreRegistry      = $registry;
    $this-><variableName>Repository   = $<variableName>Repository;
    $this-><variableName>Factory      = $<variableName>Factory;
}

/**
 * Edit Entityname
 *
 * @return Page|Redirect
 */
public function execute(): ResultInterface
{
    /** @var int $<variableName>Id */
    $<variableName>Id = $this->getRequest()->getParam(\'id\');

    try {
        /** @var <entityName> $<variableName> */
        $<variableName> = $this-><variableName>Factory->create();

        if ($<variableName>Id) {
            $<variableName> = $this-><variableName>Repository->getById((int)$<variableName>Id);
        }

        $this->coreRegistry->register(<entityName>::KEY, $<variableName>);

        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();

        $resultPage->getConfig()->getTitle()->prepend(__(\'<entityName>\'));

        return $resultPage;
    } catch (Exception $exception) {
        $this->messageManager->addErrorMessage(
            __(\'Entityname error: %1\', $exception->getMessage())
        );
    }

    /** @var Redirect $resultRedirect */
    $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

    return $resultRedirect->setPath(\'*/*/\');
}';
    /**
     * Description $deleteBodyTemplate field
     *
     * @var string $deleteBodyTemplate
     */
    protected $deleteBodyTemplate = '/**
 * Authorization level of a basic admin session
 *
 * @var string ADMIN_RESOURCE
 */
const ADMIN_RESOURCE = \'<moduleName>::<entityName>\';

/**
 * <entityName> Repository
 *
 * @var <entityName>RepositoryInterface $<variableName>Repository
 */
protected $<variableName>Repository;

/**
 * Delete Constructor.
 *
 * @param Action\Context           $context
 * @param <entityName>RepositoryInterface $<variableName>Repository
 */
public function __construct(
    Action\Context $context,
    <entityName>RepositoryInterface $<variableName>Repository
) {
    parent::__construct($context);

    $this-><variableName>Repository = $<variableName>Repository;
}

/**
 * Delete action
 *
 * @return Redirect
 */
public function execute(): Redirect
{
    /** @var Redirect $resultRedirect */
    $resultRedirect = $this->resultRedirectFactory->create();

    try {
        /** @var int $<variableName>Id */
        $<variableName>Id = $this->getRequest()->getParam(\'id\');
        if ($<variableName>Id) {
            $this-><variableName>Repository->deleteById((int)$<variableName>Id);
        }
        $this->messageManager->addSuccessMessage(__(\'You deleted the <entityName>.\'));
    } catch (Exception $exception) {
        $this->messageManager->addErrorMessage(
            __(\'<entityName> error: %1\', $exception->getMessage())
        );
    }

    return $resultRedirect->setPath(\'*/*/\');
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

        $this->generateIndex($moduleName, $entityName);
        $this->generateNewAction($moduleName, $entityName);
        $this->generateSave($moduleName, $entityName);
        $this->generateEdit($moduleName, $entityName);
        $this->generateDelete($moduleName, $entityName);
    }

    /**
     * Description generateIndex function
     *
     * @param string $moduleName
     * @param string $entityName
     *
     * @return void
     */
    protected function generateIndex(string $moduleName, string $entityName)
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
        $className = 'Index';
        /** @var string $namespace */
        $namespace = str_replace('_', '\\', $moduleName) . '\Controller\AdminHtml\\' . $entityName . ';';
        /** @var string[] $replacements */
        $replacements = [
            $namespace,
            '
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
',
            $this->generateClassPhpDoc($namespace, $className, 'Class'),
            'class ' . $className . ' extends Action',
            $this->prefixCodeWithSpaces(
                str_replace(
                    ['<moduleName>', '<entityName>', '<variableName>'],
                    [$moduleName, $entityName, lcfirst($entityName)],
                    $this->indexBodyTemplate
                )
            ),
        ];

        /** @var string $code */
        $code = str_replace($placeHolders, $replacements, $this->classTemplate) . "\n";
        $this->writeFile($moduleName, $namespace, $className, 'php', $code);
    }

    /**
     * Description generateNewAction function
     *
     * @param string $moduleName
     * @param string $entityName
     *
     * @return void
     */
    protected function generateNewAction(string $moduleName, string $entityName)
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
        $className = 'NewAction';
        /** @var string $namespace */
        $namespace = str_replace('_', '\\', $moduleName) . '\Controller\AdminHtml\\' . $entityName . ';';
        /** @var string[] $replacements */
        $replacements = [
            $namespace,
            '
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Backend\Model\View\Result\Forward;
',
            $this->generateClassPhpDoc($namespace, $className, 'Class'),
            'class ' . $className . ' extends Action',
            $this->prefixCodeWithSpaces(
                str_replace(
                    ['<moduleName>', '<entityName>', '<variableName>'],
                    [$moduleName, $entityName, lcfirst($entityName)],
                    $this->newActionBodyTemplate
                )
            ),
        ];

        /** @var string $code */
        $code = str_replace($placeHolders, $replacements, $this->classTemplate) . "\n";
        $this->writeFile($moduleName, $namespace, $className, 'php', $code);
    }

    /**
     * Description generateSave function
     *
     * @param string $moduleName
     * @param string $entityName
     *
     * @return void
     */
    protected function generateSave(string $moduleName, string $entityName)
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
        $className = 'Save';
        /** @var string $moduleNamespace */
        $moduleNamespace = str_replace('_', '\\', $moduleName);
        /** @var string $namespace */
        $namespace = $moduleNamespace . '\Controller\AdminHtml\\' . $entityName . ';';
        /** @var string[] $replacements */
        $replacements = [
            $namespace,
            '
use ' . $moduleNamespace . '\Api\\' . $entityName . 'RepositoryInterface;
use ' . $moduleNamespace . '\Model\\' . $entityName . ';
use ' . $moduleNamespace . '\Model\\' . $entityName . 'Factory;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Exception\LocalizedException;
use Exception;
',
            $this->generateClassPhpDoc($namespace, $className, 'Class'),
            'class ' . $className . ' extends Action',
            $this->prefixCodeWithSpaces(
                str_replace(
                    ['<moduleName>', '<entityName>', '<variableName>'],
                    [$moduleName, $entityName, lcfirst($entityName)],
                    $this->saveBodyTemplate
                )
            ),
        ];

        /** @var string $code */
        $code = str_replace($placeHolders, $replacements, $this->classTemplate) . "\n";
        $this->writeFile($moduleName, $namespace, $className, 'php', $code);
    }

    /**
     * Description generateEdit function
     *
     * @param string $moduleName
     * @param string $entityName
     *
     * @return void
     */
    protected function generateEdit(string $moduleName, string $entityName)
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
        $className = 'Edit';
        /** @var string $moduleNamespace */
        $moduleNamespace = str_replace('_', '\\', $moduleName);
        /** @var string $namespace */
        $namespace = $moduleNamespace . '\Controller\AdminHtml\\' . $entityName . ';';
        /** @var string[] $replacements */
        $replacements = [
            $namespace,
            '
use ' . $moduleNamespace . '\Api\\' . $entityName . 'RepositoryInterface;
use ' . $moduleNamespace . '\Model\\' . $entityName . ';
use ' . $moduleNamespace . '\Model\\' . $entityName . 'Factory;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\View\Result\Page;
use Magento\Framework\Controller\ResultFactory;
use Exception;
',
            $this->generateClassPhpDoc($namespace, $className, 'Class'),
            'class ' . $className . ' extends Action',
            $this->prefixCodeWithSpaces(
                str_replace(
                    ['<moduleName>', '<entityName>', '<variableName>'],
                    [$moduleName, $entityName, lcfirst($entityName)],
                    $this->editBodyTemplate
                )
            ),
        ];

        /** @var string $code */
        $code = str_replace($placeHolders, $replacements, $this->classTemplate) . "\n";
        $this->writeFile($moduleName, $namespace, $className, 'php', $code);
    }

    /**
     * Description generateDelete function
     *
     * @param string $moduleName
     * @param string $entityName
     *
     * @return void
     */
    protected function generateDelete(string $moduleName, string $entityName)
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
        $className = 'Delete';
        /** @var string $moduleNamespace */
        $moduleNamespace = str_replace('_', '\\', $moduleName);
        /** @var string $namespace */
        $namespace = $moduleNamespace . '\Controller\AdminHtml\\' . $entityName . ';';
        /** @var string[] $replacements */
        $replacements = [
            $namespace,
            '
use ' . $moduleNamespace . '\Api\\' . $entityName . 'RepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;
use Exception;
',
            $this->generateClassPhpDoc($namespace, $className, 'Class'),
            'class ' . $className . ' extends Action',
            $this->prefixCodeWithSpaces(
                str_replace(
                    ['<moduleName>', '<entityName>', '<variableName>'],
                    [$moduleName, $entityName, lcfirst($entityName)],
                    $this->deleteBodyTemplate
                )
            ),
        ];

        /** @var string $code */
        $code = str_replace($placeHolders, $replacements, $this->classTemplate) . "\n";
        $this->writeFile($moduleName, $namespace, $className, 'php', $code);
    }
}
