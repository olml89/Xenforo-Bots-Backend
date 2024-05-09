<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Content\Domain;

use olml89\XenforoBotsBackend\Common\Domain\Criteria\CompositeExpressions\AndExpression;
use olml89\XenforoBotsBackend\Common\Domain\Criteria\Criteria;
use olml89\XenforoBotsBackend\Common\Domain\Criteria\Expression\EqualTo;
use olml89\XenforoBotsBackend\Common\Domain\Criteria\Expression\Field;

final readonly class EqualsExternalContentIdAndEqualsScopeSpecification implements ContentSpecification
{
    public function __construct(
        private AutoId $externalContentId,
        private ContentScope $scope,
    ) {}

    public function isSatisfiedBy(Content $content): bool
    {
        return $content->externalContentId()->equals($this->externalContentId)
            && $content->scope() === $this->scope;
    }

    public function criteria(): Criteria
    {
        return new Criteria(
            expression: new AndExpression(
                new EqualTo(
                    field: new Field('externalContentId'),
                    value: $this->externalContentId
                ),
                new EqualTo(
                    field: new Field('scope'),
                    value: $this->scope
                )
            )
        );
    }
}
