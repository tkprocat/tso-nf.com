<?php namespace LootTracker\Exceptions;

use App;
use Exception;
use GrahamCampbell\Exceptions\ExceptionHandler as ExceptionHandler;
use GrahamCampbell\Exceptions\ExceptionIdentifier;

class Handler extends ExceptionHandler
{

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        'Symfony\Component\HttpKernel\Exception\HttpException'
    ];


    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $e
     *
     * @return void
     */
    public function report(Exception $e)
    {
        if ($this->shouldReport($e)) {
            $request = App::make(\Illuminate\Http\Request::class);
            $level = $this->getLevel($e);
            $id = $this->container->make(ExceptionIdentifier::class)->identify($e);
            $this->log->{$level}($e, ['url' => $request->url(), 'identification' => ['id' => $id]]);
        }
    }


    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception               $e
     *
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        return parent::render($request, $e);
    }
}
