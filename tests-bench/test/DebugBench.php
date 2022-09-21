<?php

class DebugBench
{

    public function benchDebugBacktrace()
    {
        $caller = debug_backtrace()[1]['function'];
    }

    public function benchExceptionBacktrace()
    {
        $e = new Exception();
        $caller = $e->getTrace()[1]['function'];
    }

}