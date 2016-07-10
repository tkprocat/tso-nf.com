<?php namespace LootTracker\Exceptions;

use App;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Http\Exception\HttpResponseException;
use GrahamCampbell\Exceptions\ExceptionIdentifier;
use GrahamCampbell\Exceptions\ExceptionHandler as ExceptionHandler;

class Handler extends ExceptionHandler
{

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
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
        /**
         * Copied from GrahamCampbell\Exceptions\ExceptionHandlerTrait and modified cause of a bug with testing validation error handling.
         */
        if ($e instanceof HttpResponseException) {
            return $e->getResponse();
        }

        $transformed = $this->getTransformed($e);

        $response = method_exists($e, 'getResponse') ? $e->getResponse() : null;

        if (!$response instanceof Response) {
            $response = $this->getResponse($request, $e, $transformed);
        }

        return $this->toIlluminateResponse($response, $transformed);
    }
}
