<?php

namespace Oro\Bundle\MigrationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

//use Oro\Component\Log\OutputLogger;
use Psr\Log\AbstractLogger as OutputLogger;

use Oro\Bundle\MigrationBundle\Migration\Loader\MigrationsLoader;
use Oro\Bundle\MigrationBundle\Migration\MigrationExecutor;

class LoadMigrationsCommand extends ContainerAwareCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->setName('oro:migration:load')
            ->setDescription('Execute migration scripts.')
            ->addOption(
                'force',
                null,
                InputOption::VALUE_NONE,
                'Causes the generated by migrations SQL statements to be physically executed against your database.'
            )
            ->addOption(
                'dry-run',
                null,
                InputOption::VALUE_NONE,
                'Outputs list of migrations without apply them.'
            )
            ->addOption(
                'show-queries',
                null,
                InputOption::VALUE_NONE,
                'Outputs list of database queries for each migration file.'
            )
            ->addOption(
                'bundles',
                null,
                InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL,
                'A list of bundles to load data from. If option is not set, migrations will be taken from all bundles.'
            )
            ->addOption(
                'exclude',
                null,
                InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL,
                'A list of bundle names which migrations should be skipped.'
            );
    }

    /**
     * @inheritdoc
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $force = $input->getOption('force');
        $dryRun = $input->getOption('dry-run');

        if ($force || $dryRun) {
            $output->writeln($dryRun ? 'List of migrations:' : 'Process migrations...');

            $migrationLoader = $this->getMigrationLoader($input);
            $migrations      = $migrationLoader->getMigrations();
            if (!empty($migrations)) {
                if ($input->getOption('dry-run') && !$input->getOption('show-queries')) {
                    foreach ($migrations as $migration) {
                        $output->writeln(sprintf('  <comment>> %s</comment>', get_class($migration)));
                    }
                } else {
                    $logger      = new OutputLogger($output, true, null, '  ');
                    $queryLogger = new OutputLogger(
                        $output,
                        true,
                        $input->getOption('show-queries') ? null : OutputInterface::VERBOSITY_QUIET,
                        '    '
                    );
                    $executor    = $this->getMigrationExecutor($input);
                    //$executor->setLogger($logger);
                    $executor->getQueryExecutor()->setLogger($queryLogger);
                    $executor->executeUp($migrations, $input->getOption('dry-run'));
                }
            }
        } else {
            $output->writeln(
                '<comment>ATTENTION</comment>: Database backup is highly recommended before executing this command.'
            );
            $output->writeln('');
            $output->writeln('To force execution run command with <info>--force</info> option:');
            $output->writeln(sprintf('    <info>%s --force</info>', $this->getName()));
        }
    }

    /**
     * @param InputInterface $input
     * @return MigrationsLoader
     */
    protected function getMigrationLoader(InputInterface $input)
    {
        $migrationLoader = $this->getContainer()->get('oro_migration.migrations.loader');
        $bundles         = $input->getOption('bundles');
        if (!empty($bundles)) {
            $migrationLoader->setBundles($bundles);
        }
        $excludeBundles = $input->getOption('exclude');
        if (!empty($excludeBundles)) {
            $migrationLoader->setExcludeBundles($excludeBundles);
        }

        return $migrationLoader;
    }

    /**
     * @param InputInterface $input
     * @return MigrationExecutor
     */
    protected function getMigrationExecutor(InputInterface $input)
    {
        return $this->getContainer()->get('oro_migration.migrations.executor');
    }
}
