<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Question\Question;
use App\Service\CategoryService;

class CreateCategoryCommand extends Command
{
    private $categoryService;
    /**
     * @param CategoryService $categoryService
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;

        parent::__construct();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function interact(\Symfony\Component\Console\Input\InputInterface $input, \Symfony\Component\Console\Output\OutputInterface $output)
    {
        if (!$input->getArgument('name')) {
            $question = new Question('Please choose a name: ');
            $question->setValidator(function ($name) {
                if (empty($name)) {
                    throw new \Exception('Name can not be empty');
                }

                return $name;
            });

            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument('name', $answer);
        }
    }

    protected function configure()
    {
        $this
            ->setName('app:create-category')
            ->setDescription('Creates a new category')
            ->setHelp('This command allows you to add new category')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the category.');;
    }

    protected function execute(\Symfony\Component\Console\Input\InputInterface $input, \Symfony\Component\Console\Output\OutputInterface $output)
    {
        $output->writeln(['Category Creator', "==================", '']);
        // outputs a message followed by a "\n"
          // retrieve the argument value using getArgument()
          $output->writeln(sprintf('Name: %s', $input->getArgument('name')));
          $this->categoryService->create($input->getArgument('name'));
          $output->writeln('<fg=green>Category successfully created!</>');

    }

}