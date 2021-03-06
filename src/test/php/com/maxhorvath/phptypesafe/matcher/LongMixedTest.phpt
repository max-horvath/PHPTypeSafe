--TEST--
Testing boolean hint mixed with non-strict checks
--FILE--
<?php

require_once '../../../../../main/php/com/maxhorvath/phptypesafe/PHPTypeSafe.php';

function initiateNonStrictTypeHintError($triggerError)
{
    return true;
}

function initiateMixedTypeHintError(long $triggerError, $nonStrictValue)
{
    return true;
}

com\maxhorvath\phptypesafe\PHPTypeSafe::setUp();

initiateNonStrictTypeHintError(123);
print "Ok\n";

initiateNonStrictTypeHintError('test');
print "Ok\n";

initiateMixedTypeHintError(123, 123);
print "Ok\n";

initiateMixedTypeHintError('test', 'test');
print "Ok\n";

?>
--EXPECTF--
Ok
Ok
Ok

Fatal error: Uncaught exception 'ErrorException' with message 'Argument 1 passed to initiateMixedTypeHintError() must be of type long, string given, called in %s on line %d' in %s:%d
Stack trace:
#0 %s(%d): com\maxhorvath\phptypesafe\ErrorHandler::analyzeError(4096, 'Argument 1 pass...', '%s', %d, Array)
#1 %s(%d): initiateMixedTypeHintError('test', 'test')
#2 {main}
  thrown in %s on line %d