--TEST--
Testing boolean hint
--FILE--
<?php

require_once '../../../../../main/php/com/maxhorvath/phptypesafe/PHPTypeSafe.php';

function initiateTypeHintError(string $triggerError)
{
    return true;
}

com::maxhorvath::phptypesafe::PHPTypeSafe::setUp();

initiateTypeHintError('test');
print "Ok\n";

initiateTypeHintError(false);
print "Ok\n";

?>
--EXPECTF--
Ok

Fatal error: Uncaught exception 'ErrorException' with message 'Argument 1 passed to ::initiateTypeHintError must be of type string, boolean given, called in %s on line %d' in %s:%d
Stack trace:
#0 %s(%d): com::maxhorvath::phptypesafe::ErrorHandler::analyzeError(4096, 'Argument 1 pass...', '%s', %d, Array)
#1 %s(%d): initiateTypeHintError(false)
#2 {main}
  thrown in %s on line %d