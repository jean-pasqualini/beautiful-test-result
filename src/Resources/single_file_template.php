<?php echo '<?php' ?>

declare(strict_types = 1);

namespace App;

use PHPUnit\Framework\TestSuite;
use PHPUnit\TextUI\ResultPrinter;

// Backward compatibility with phpunit 5
if (!class_exists('PHPUnit\TextUI\ResultPrinter')) {
    class_alias(\PHPUnit_TextUI_ResultPrinter::class, 'PHPUnit\TextUI\ResultPrinter');
    class_alias(\PHPUnit_Framework_TestSuite::class, 'PHPUnit\Framework\TestSuite');
}

final class EmojiPrinter extends ResultPrinter
{
    const SPACER = ' ';

    public function startTestSuite(TestSuite $suite)
    {
        if ($this->numTests == -1) {
            $this->numTests = count($suite);
            $this->numTestsWidth = strlen((string) $this->numTests);
            $this->maxColumn = 40 - (2 * $this->numTestsWidth);
        }

        parent::startTestSuite($suite);
    }

    protected function writeProgress($progress)
    {
        return parent::writeProgress($this->emojify($progress) . self::SPACER);
    }

    protected function writeProgressWithColor($color, $progress)
    {
        return $this->writeProgress($progress);
    }

    private function emojify(string $result): string
    {
        <?php echo '$emojify = '.var_export($config['emojis'], true).';'; ?>

        return $emojify[$result] ?? $result;
    }
}
