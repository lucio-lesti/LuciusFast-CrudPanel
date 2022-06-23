<?php

interface IGenerator {
    public static function output($param_gen);        
}


class AbstractGenerator implements IGenerator
{
    public static function output($param_gen){}
}
