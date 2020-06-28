<?php


namespace App\Console\Commands;


use App\Console\Model;

class TurnOnOffCron implements Criteria, Executable
{
    protected const COMMAND = ['on', 'off'];
    protected $filename;
    protected $model;
    protected $param;

    public function __construct(Model $model)
    {
        $this->filename = $model->get('filename');
        $this->param = $model->get('param');
        $this->model = $model;
    }

    public function test(string $param): bool
    {
        return in_array( $param, self::COMMAND);
    }

    public function execute()
    {
        if ( !is_writable( $this->filename ) )
            exit();

        $crontab = file( $this->filename );

        $key = array_search( $this->model->get($this->param), $crontab );

        if ( $key === false )
            exit();

        $crontab[$key] = $this->model->get($this->param);
        sleep( 1 );
        print_r($crontab);
        file_put_contents( $this->filename, implode( '', $crontab ) );
    }
}
