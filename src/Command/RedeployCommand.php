<?php

namespace App\Command;

use App\Exception\RedeployFailureException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RedeployCommand extends Command
{
    protected static $defaultName = 'rancher-redeploy';

    protected function configure()
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the deployment to redeploy.')
            ->addOption('namespace', 's', InputOption::VALUE_OPTIONAL, 'The Kubernetes namespace the deployment is in.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $formatter = $this->getHelper('formatter');
        $output->setDecorated(true);

        if (empty(shell_exec('which rancher'))) {

            $formattedBlock = $formatter->formatBlock([
                'You need to install the Rancher CLI before using this tool.',
                'https://rancher.com/docs/rancher/v2.x/en/cli/'
            ], 'error', true);

            $output->writeln($formattedBlock);
            die();
        }

        if (empty(shell_exec('which kubectl'))) {

            $formattedBlock = $formatter->formatBlock([
                'You need to install kubectl before using this tool.',
                'https://kubernetes.io/docs/tasks/tools/install-kubectl/'
            ], 'error', true);

            $output->writeln($formattedBlock);
            die();
        }

        if (!file_exists($_SERVER['HOME'] . '/.rancher/cli2.json')) {

            $formattedBlock = $formatter->formatBlock([
                'You need to run `rancher login` before using this tool.',
                'https://rancher.com/docs/rancher/v2.x/en/cli/#cli-authentication'
            ], 'error', true);

            $output->writeln($formattedBlock);
            die();

        }

        /** @var string $name */
        $name = $input->getArgument('name');

        /** @var string $namespace */
        $namespace = $input->getOption('namespace');

        try {

            $this->redeploy(
                $name,
                $namespace
            );

        } catch (\Exception $e) {

            $output->writeln('<error>' . $e->getMessage() . '</error>');
            die();

        }

        $formattedBlock = $formatter->formatBlock([
            'Redeploy successful.'
        ], 'info', true);

        $output->writeln($formattedBlock);
        die();

    }

    /**
     * @param string $name
     * @param string|null $namespace
     *
     * @throws RedeployFailureException
     */
    private function redeploy(string $name, ?string $namespace = null)
    {
        if ($namespace !== null) {
            exec('rancher kubectl patch deployment ' . $name . ' --namespace ' . $namespace . ' -p "{\"spec\": {\"template\": {\"metadata\": { \"labels\": {  \"redeploy\": \"'.time().'\"}}}}}" 2>&1', $output, $return);

            if ($return !== 0) {
                throw new RedeployFailureException(implode("\n", $output));
            }

            return;
        }

        exec('rancher kubectl patch deployment ' . $name . ' -p "{\"spec\": {\"template\": {\"metadata\": { \"labels\": {  \"redeploy\": \"'.time().'\"}}}}}" 2>&1', $output, $return);

        if ($return !== 0) {
            throw new RedeployFailureException(implode("\n", $output));
        }
    }
}