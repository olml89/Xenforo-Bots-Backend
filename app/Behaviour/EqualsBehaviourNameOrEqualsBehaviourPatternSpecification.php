<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Behaviour;

use olml89\XenforoBotsBackend\Behaviour\Domain\Behaviour;
use olml89\XenforoBotsBackend\Behaviour\Domain\BehaviourName;
use olml89\XenforoBotsBackend\Behaviour\Domain\BehaviourPattern;
use olml89\XenforoBotsBackend\Common\Domain\Criteria\CompositeExpressions\OrExpression;
use olml89\XenforoBotsBackend\Common\Domain\Criteria\Criteria;
use olml89\XenforoBotsBackend\Common\Domain\Criteria\Expression\EqualTo;
use olml89\XenforoBotsBackend\Common\Domain\Criteria\Expression\Field;
use olml89\XenforoBotsBackend\Common\Domain\Criteria\Specification;

final readonly class EqualsBehaviourNameOrEqualsBehaviourPatternSpecification implements Specification
{
    public function __construct(
        private BehaviourName $behaviourName,
        private BehaviourPattern $behaviourPattern,
    ) {}

    public function isSatisfiedBy(Behaviour $behaviour): bool
    {
        return $behaviour->behaviourName()->equals($this->behaviourName)
            && $behaviour->behaviourPattern()->equals($this->behaviourPattern);
    }

    public function criteria(): Criteria
    {
        return new Criteria(
            expression: new OrExpression(
                new EqualTo(
                    field: new Field('behaviourName'),
                    value: $this->behaviourName
                ),
                new EqualTo(
                    field: new Field('behaviourPattern'),
                    value: $this->behaviourPattern
                )
            )
        );
    }
}
