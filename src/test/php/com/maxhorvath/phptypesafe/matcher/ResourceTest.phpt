--TEST--
Testing boolean hint
--FILE--
<?php

require_once '../../../../../main/php/com/maxhorvath/phptypesafe/PHPTypeSafe.php';

function initiateTypeHintError(resource $triggerError)
{
    return true;
}

com::maxhorvath::phptypesafe::PHPTypeSafe::setUp();

$resource = fopen(__FILE__, 'r');
initiateTypeHintError($resource);
fclose($resource);
print "Ok\n";

initiateTypeHintError('test');
print "Ok\n";

?>
--EXPECTF--
Ok

Fatal error: Uncaught exception 'ErrorException' with message 'Argument 1 passed to ::initiateTypeHintError must be of type resource, string given, called in %s on line %d' in %s:%d
Stack trace:
#0 %s(%d): com::maxhorvath::phptypesafe::ErrorHandler::analyzeError(4096, 'Argument 1 pass...', '%s', %d, Array)
#1 %s(%d): initiateTypeHintError('test')
#2 {main}
  thrown in %s on line %d