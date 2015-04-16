<?php

namespace Pagekit;

use Pagekit\Application\Traits\EventTrait;
use Pagekit\Application\Traits\RouterTrait;
use Pagekit\Application\Traits\StaticTrait;
use Pagekit\Event\EventDispatcher;
use Pagekit\Module\ModuleManager;
use Symfony\Component\HttpFoundation\Request;

class Application extends Container
{
    use StaticTrait, EventTrait, RouterTrait;

    /**
     * Constructor.
     *
     * @param array $values
     */
    public function __construct(array $values = [])
    {
        parent::__construct($values);

        $this['events'] = function() {
            return new EventDispatcher();
        };

        $this['module'] = function() {
            return new ModuleManager($this);
        };
    }

    /**
     * Handles the request and delivers the response.
     *
     * @param Request $request
     */
    public function run(Request $request = null)
    {
        if ($request === null) {
            $request = Request::createFromGlobals();
        }

        $response = $this['kernel']->handle($request);
        $response->send();

        $this['kernel']->terminate($request, $response);
    }

    /**
     * Determine if we are running in the console.
     *
     * @return bool
     */
    public function inConsole()
    {
        return PHP_SAPI == 'cli';
    }
}
