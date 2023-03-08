<?php declare(strict_types=1);

namespace MLL\LaravelUtils\ModelStates;

use Illuminate\Foundation\Application;
use MLL\LaravelUtils\ModelStates\Exceptions\TransitionNotAllowed;
use MLL\LaravelUtils\ModelStates\Exceptions\TransitionNotFound;

final class StateMachine
{
    private readonly StateConfig $stateConfig;

    private readonly HasStateManagerInterface $model;

    private readonly Application $app;

    public function __construct(HasStateManagerInterface $model)
    {
        $this->stateConfig = $model->stateClass()::config();
        $this->model = $model;

        $this->app = Application::getInstance();
    }

    /**
     * @param State|class-string<State> $newState
     */
    public function transitionTo(State|string $newState): HasStateManagerInterface
    {
        $from = $this->model->state::class;
        $to = $newState::class;

        $transition = $this->instantiateTransitionClass($from, $to);

        if (! $transition->canTransition()) {
            throw new TransitionNotAllowed($this->model, $transition);
        }

        if (method_exists($transition, 'handle')) {
            // Allows dependency injection
            $call = $this->app->call([$transition, 'handle']);
            assert($call instanceof HasStateManagerInterface);

            return $call;
        }

        return $this->model;
    }

    public function instantiateTransitionClass(string $from, string $to): Transition
    {
        $transitionClass = $this->stateConfig->transition($from, $to)
            ?? throw new TransitionNotFound($from, $to, $this->model);

        return new $transitionClass($this->model, $from, $to);
    }
}
