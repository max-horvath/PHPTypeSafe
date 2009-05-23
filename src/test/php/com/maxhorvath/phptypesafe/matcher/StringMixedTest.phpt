--TEST--
Testing boolean hint mixed with non-strict checks
--FILE--
<?php

require_once '../../../../../main/php/com/maxhorvath/phptypesafe/PHPTypeSafe.php';

function initiateNonStrictTypeHintError($triggerError)
{
    return true;
}

function initiateMixedTypeHintError(string $triggerError, $nonStrictValue)
{
    return true;
}

com\maxhorvath\phptypesafe\PHPTypeSafe::setUp();

initiateNonStrictTypeHintError('test');
print "Ok\n";

initiateNonStrictTypeHintError(false);
print "Ok\n";

initiateMixedTypeHintError('test', 'test');
print "Ok\n";

initiateMixedTypeHintError(false, false);
print "Ok\n";

?>
--EXPECTF--
Ok
Ok
Ok

Fatal error: Uncaught exception 'ErrorException' with message 'Argument 1 passed to initiateMixedTypeHintError() must be of type string, boolean given, called in %s on line %d' in %s:%d
Stack trace:
#0 %s(%d): com\maxhorvath\phptypesafe\ErrorHandler::analyzeError(4096, 'Argument 1 pass...', '%s', %d, Array)
#1 %s(%d): initiateMixedTypeHintError(false, false)
#2 {main}
  thrown in %s on line %d