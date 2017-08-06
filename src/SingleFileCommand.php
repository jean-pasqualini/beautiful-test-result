<?php

declare(strict_types=1);

namespace App;

use App\Helper\QuestionHelper;
use LitEmoji\LitEmoji;
use PhpSchool\PSX\Factory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

class SingleFileCommand extends Command
{
    /** @var array */
    private $testStyle = [
        'error'         => 'bomb',
        'failure'       => 'poop',
        'incomplete'    => 'construction',
        'risky'         => 'game_die',
        'skipped'       => 'see_no_evil',
        'pass'          => 'elephant',
    ];

    public function configure()
    {
        $this->setName('generate');
    }

    public function interact(InputInterface $input, OutputInterface $output)
    {
        $emojiAvailables = require __DIR__.'/Resources/emojis.php';
        foreach ($emojiAvailables as $key => $emojiAvailable) {
            $emojiAvailables[$key.' '.LitEmoji::encodeUnicode(':'.$key.':')] = $key;
            unset($emojiAvailables[$key]);
        }

        $outputStyle = new SymfonyStyle($input, $output);
        $output->writeln('<bg=yellow> List of emoticons availabe </>');
        $outputStyle->table(
            ['1', '2', '3'],
            array_chunk(array_keys($emojiAvailables), 3)
        );

        $questionHelper = new QuestionHelper();

        foreach ($this->testStyle as $testType => $emoji) {
            $this->testStyle[$testType] = $emojiAvailables[$questionHelper->ask(
                $input,
                $output,
                new ChoiceQuestion(
                    'choice the style for result type <options=bold>'.$testType.'</> (default: '.LitEmoji::encodeUnicode(':'.$emoji.':').') : ',
                    $emojiAvailables,
                    $emoji
                )
            )];
        }
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $outputStyle = new SymfonyStyle($input, $output);
        $highlighter = (new Factory())();

        $defaultKeyMap = [
            'error'         => 'E',
            'failure'       => 'F',
            'incomplete'    => 'I',
            'risky'         => 'R',
            'skipped'       => 'S',
            'pass'          => '.',
        ];
        $config = [
            'emojis' => $this->testStyle,
            'keymap' => $defaultKeyMap,
        ];
        foreach($config['emojis'] as $key => $emojisItem) {
            $config['emojis'][$config['keymap'][$key]] = LitEmoji::encodeUnicode(":$emojisItem:");
            unset($config['emojis'][$key]);
        }

        $outputStyle->section('Follow the instruct for install emoticon on phpunit');

        $output->writeln(sprintf(
            '<bg=magenta;options=bold,underscore>%s</>%s',
            '1. Write the content in the one php file',
            PHP_EOL));

        $content = (function ($config) {
            ob_start();
            include(__DIR__.'/Resources/single_file_template.php');
            return ob_get_clean();
        })($config);

        $output->writeln([$highlighter->highlight($content), '']);

        $output->writeln(sprintf(
            '<bg=magenta;options=bold,underscore>%s</>%s',
            '2. Add the line on phpunit node in your phpunit.xml.dist',
            PHP_EOL));

        $output->writeln([
            'printerFile="[filepath_contains_content_of_previous_step]"',
            'printerClass="App\EmojiPrinter"',
        ]);
    }
}

