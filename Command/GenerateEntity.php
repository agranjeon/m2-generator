<?php

declare(strict_types=1);

namespace Agranjeon\Generator\Command;

use Agranjeon\Generator\Generator\AbstractGenerator;
use Agranjeon\Generator\Generator\Context;
use Magento\Backend\App\Area\FrontNameResolver;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\App\State;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Module\ModuleList;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Container;

/**
 * @author Alexandre Granjeon <alexandre.granjeon@gmail.com>
 */
class GenerateEntity extends Command
{
    /**
     * @var string NAME
     */
    const NAME = 'agranjeon:generate:entity';
    /**
     * @var string ENTITY_NAME
     */
    const ENTITY_NAME = 'entity_name';
    /**
     * @var string MODULE_NAME
     */
    const MODULE_NAME = 'module_name';
    /**
     * @var string ENTITY_TABLE
     */
    const ENTITY_TABLE = 'entity_table';
    /**
     * @var string GENERATE_BACKEND
     */
    const GENERATE_BACKEND = 'generate_backend';
    /**
     * @var string FIELDS
     */
    const FIELDS = 'fields';
    /**
     * @var AdapterInterface $connection
     */
    protected $connection;
    /**
     * @var Context $generatorContext
     */
    protected $generatorContext;
    /**
     * @var SymfonyStyle $io
     */
    protected $io;
    /**
     * @var ModuleList $moduleList
     */
    protected $moduleList;
    /**
     * @var State $appState
     */
    private $appState;

    /**
     * @param State              $appState
     * @param ResourceConnection $resourceConnection
     * @param Context            $generatorContext
     * @param ModuleList         $moduleList
     */
    public function __construct(
        State $appState,
        ResourceConnection $resourceConnection,
        Context $generatorContext,
        ModuleList $moduleList
    ) {
        parent::__construct();

        $this->appState         = $appState;
        $this->connection       = $resourceConnection->getConnection();
        $this->generatorContext = $generatorContext;
        $this->moduleList       = $moduleList;
    }

    /**
     * {@inheritDoc}
     */
    protected function configure(): void
    {
        $this->setName(self::NAME)->setDescription('Generate classes and files for a given entity')->setDefinition(
            $this->getOptionsList()
        );
    }

    /**
     * Get list of arguments for the command
     *
     * @return InputArgument[]
     */
    public function getOptionsList(): array
    {
        return [
            new InputOption(
                self::MODULE_NAME
            ),
            new InputOption(self::ENTITY_NAME),
            new InputOption(self::ENTITY_TABLE),
            new InputOption(
                self::GENERATE_BACKEND
            ),
            new InputOption(
                self::FIELDS
            ),
        ];
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|void|null
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->appState->setAreaCode(FrontNameResolver::AREA_CODE);
        } catch (\Exception $exception) {
        }

        $moduleName      = $input->getOption(self::MODULE_NAME);
        $entityName      = $input->getOption(self::ENTITY_NAME);
        $entityTable     = $input->getOption(self::ENTITY_TABLE);
        $generateBackend = (bool)filter_var(
            $input->getOption(self::GENERATE_BACKEND),
            FILTER_VALIDATE_BOOLEAN,
            FILTER_NULL_ON_FAILURE
        );
        $fields          = $input->getOption(self::FIELDS);

        $this->generatorContext->generate($moduleName, $entityName, $entityTable, $generateBackend, $fields);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->io->section('Welcome to the magento2 entity generator');

        // namespace
        $this->io->text(
            [
                'This command helps you generate magento2 entities.',
                '',
                'First, you need to give us some information about the entity you want to generate.',
                '',
            ]
        );

        $validator = function (string $value) {
            if (!$value) {
                throw new \InvalidArgumentException('This field is required');
            }

            return $value;
        };

        $moduleName      = $this->io->choice('The module name', $this->moduleList->getNames());
        $entityName      = $this->io->ask('The entity name', $input->getOption(self::ENTITY_NAME), $validator);
        $entityTable     = $this->io->ask('The table name', $input->getOption(self::ENTITY_TABLE), $validator);
        $generateBackend = (string)$this->io->confirm(
            'Should we generate back-end files ?',
            $input->getOption(self::GENERATE_BACKEND)
        );

        $input->setOption(self::MODULE_NAME, $moduleName);
        $input->setOption(self::ENTITY_NAME, $entityName);
        $input->setOption(self::ENTITY_TABLE, $entityTable);
        $input->setOption(self::GENERATE_BACKEND, $generateBackend);
        $input->setOption(self::FIELDS, $this->addFields($input, $output));
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return array
     */
    private function addFields(InputInterface $input, OutputInterface $output): array
    {
        $fields = [];
        $this->io->text(
            [
                '',
                'Instead of starting with a blank entity, you can add some fields now.',
                'Note that the primary key will be added automatically (named <comment>entity_id</comment>).',
                '',
            ]
        );

        $types = array_keys(AbstractGenerator::$typeMapping);
        $this->io->note('Available types: ' . join(', ', $types));

        $lengthValidator = function ($length) {
            if (!$length) {
                return $length;
            }

            $result = filter_var(
                $length,
                FILTER_VALIDATE_INT,
                [
                    'options' => ['min_range' => 1],
                ]
            );

            if (false === $result) {
                throw new \InvalidArgumentException(sprintf('Invalid length "%s".', $length));
            }

            return $length;
        };

        $precisionValidator = function ($precision) {
            if (!$precision) {
                return $precision;
            }

            $result = filter_var(
                $precision,
                FILTER_VALIDATE_INT,
                [
                    'options' => ['min_range' => 1, 'max_range' => 65],
                ]
            );

            if (false === $result) {
                throw new \InvalidArgumentException(sprintf('Invalid precision "%s".', $precision));
            }

            return $precision;
        };

        $scaleValidator = function ($scale) {
            if (!$scale) {
                return $scale;
            }

            $result = filter_var(
                $scale,
                FILTER_VALIDATE_INT,
                [
                    'options' => ['min_range' => 0, 'max_range' => 30],
                ]
            );

            if (false === $result) {
                throw new \InvalidArgumentException(sprintf('Invalid scale "%s".', $scale));
            }

            return $scale;
        };

        $paddingValidator = function ($padding) {
            if (!$padding) {
                return $padding;
            }

            $result = filter_var(
                $padding,
                FILTER_VALIDATE_INT,
                [
                    'options' => ['min_range' => 0, 'max_range' => 20],
                ]
            );

            if (false === $result) {
                throw new \InvalidArgumentException(sprintf('Invalid padding "%s".', $padding));
            }

            return $padding;
        };

        while (true) {
            $output->writeln('');
            $columnName = $this->io->ask(
                'New field name (press <return> to stop adding fields)',
                null,
                function ($name) use ($fields) {
                    if (isset($fields[$name]) || 'entity_id' === $name) {
                        throw new \InvalidArgumentException(sprintf('Field "%s" is already defined.', $name));
                    }

                    // check for valid PHP variable name
                    if (!is_null($name) && !$this->isValidPhpVariableName($name)) {
                        throw new \InvalidArgumentException(sprintf('"%s" is not a valid PHP variable name.', $name));
                    }

                    return $name;
                }
            );

            if (!$columnName) {
                break;
            }

            $defaultType = 'varchar';

            // try to guess the type by the column name prefix/suffix
            if (substr($columnName, -3) === '_at') {
                $defaultType = 'timestamp';
            } elseif (substr($columnName, -3) === '_id') {
                $defaultType = 'int';
            } elseif (substr($columnName, 0, 3) === 'is_') {
                $defaultType = 'boolean';
            } elseif (substr($columnName, 0, 4) === 'has_') {
                $defaultType = 'boolean';
            }

            $type = $this->io->choice('Field type', $types, $defaultType);

            $data = [
                'columnName' => $columnName,
                'fieldName'  => lcfirst(Container::camelize($columnName)),
                'type'       => $type,
            ];

            if ($type == 'varchar') {
                $data['length'] = $this->io->ask('Field length', 255, $lengthValidator);
            } elseif (in_array($type, ['decimal', 'float'])) {
                $data['precision'] = $this->io->ask('Precision', 12, $precisionValidator);
                $data['scale']     = $this->io->ask('Scale', 4, $scaleValidator);
            } elseif (in_array($type, ['int', 'smallint', 'bigint', 'tinyint'])) {
                $data['padding'] = $this->io->ask('Padding', 10, $paddingValidator);
            }

            if ($nullable = $this->io->confirm('Is nullable', false)) {
                $data['nullable'] = $nullable;
            }

            if ($unique = $this->io->confirm('Unique', false)) {
                $data['unique'] = $unique;
            }

            $fields[$columnName] = $data;
        }

        return $fields;
    }

    /**
     * Checks if the given name is a valid PHP variable name
     *
     * @see http://php.net/manual/en/language.variables.basics.php
     *
     * @param string $name
     *
     * @return bool
     */
    public function isValidPhpVariableName(string $name): bool
    {
        return (bool)preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $name, $matches);
    }
}
