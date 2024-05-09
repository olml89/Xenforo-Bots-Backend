<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\Doctrine;

use Doctrine\Common\Collections\Criteria as DoctrineCriteria;
use Doctrine\Common\Collections\Expr\Comparison as DoctrineComparison;
use Doctrine\Common\Collections\Expr\CompositeExpression as DoctrineCompositeExpression;
use Doctrine\Common\Collections\Expr\Expression as DoctrineExpression;
use olml89\XenforoBotsBackend\Common\Domain\Criteria\CompositeExpressions\AndExpression;
use olml89\XenforoBotsBackend\Common\Domain\Criteria\CompositeExpressions\CompositeExpression;
use olml89\XenforoBotsBackend\Common\Domain\Criteria\CompositeExpressions\NotExpression;
use olml89\XenforoBotsBackend\Common\Domain\Criteria\CompositeExpressions\OrExpression;
use olml89\XenforoBotsBackend\Common\Domain\Criteria\CompositeExpressions\Type;
use olml89\XenforoBotsBackend\Common\Domain\Criteria\Criteria;
use olml89\XenforoBotsBackend\Common\Domain\Criteria\Expression;
use olml89\XenforoBotsBackend\Common\Domain\Criteria\Expression\Filter;
use olml89\XenforoBotsBackend\Common\Domain\Criteria\Expression\Operator;
use olml89\XenforoBotsBackend\Common\Domain\Criteria\Order\Order;

final class DoctrineCriteriaConverter
{
    public function convert(Criteria $criteria, array $criteriaToDoctrineFields = []): DoctrineCriteria
    {
        return new DoctrineCriteria(
            expression: $this->convertExpression($criteria->expression),
            orderings: $this->convertOrder($criteria->order, $criteriaToDoctrineFields),
            firstResult: $criteria->offset,
            maxResults: $criteria->limit
        );
    }

    private function convertExpression(Expression $expression): DoctrineExpression
    {
        if ($expression instanceof CompositeExpression) {
            return new DoctrineCompositeExpression(
                type: $this->convertCompositeExpressionType($expression),
                expressions: array_map(
                    fn(Expression $expression): DoctrineExpression => $this->convertExpression($expression),
                    $this->getCompositeExpressionClauses($expression),
                ),
            );
        }

        /** @var Filter $expression */
        return new DoctrineComparison(
            field: $expression->field->value(),
            op: $this->convertExpressionOperator($expression->operator),
            value: $expression->value,
        );
    }

    private function convertExpressionOperator(Operator $operator): string
    {
        return match ($operator) {
            Operator::EQUAL => DoctrineComparison::EQ,
            Operator::NOT_EQUAL => DoctrineComparison::NEQ,
            Operator::GT => DoctrineComparison::GT,
            Operator::GTE => DoctrineComparison::GTE,
            Operator::LT => DoctrineComparison::LT,
            Operator::LTE => DoctrineComparison::LTE,
            Operator::IN => DoctrineComparison::IN,
            Operator::NOT_IN => DoctrineComparison::NIN,
        };
    }

    private function convertCompositeExpressionType(CompositeExpression $expression): string
    {
        return match ($expression->type) {
            Type::AND => DoctrineCompositeExpression::TYPE_AND,
            Type::OR => DoctrineCompositeExpression::TYPE_OR,
            Type::NOT => DoctrineCompositeExpression::TYPE_NOT,
        };
    }

    /**
     * @return Expression[]
     */
    private function getCompositeExpressionClauses(CompositeExpression $expression): array
    {
        if ($expression instanceof AndExpression || $expression instanceof OrExpression) {
            return $expression->clauses;
        }

        /** @var NotExpression $expression */
        return [$expression->clause];
    }

    private function convertOrder(?Order $order, array $criteriaToDoctrineFields): ?array
    {
        if (is_null($order)) {
            return null;
        }

        $orderByFieldName = $order->orderBy->value();

        if (array_key_exists($orderByFieldName, $criteriaToDoctrineFields)) {
            $orderByFieldName = $criteriaToDoctrineFields[$orderByFieldName];
        }

        return [
            $orderByFieldName => $order->orderType->value,
        ];
    }
}
