<?php

declare(strict_types=1);

namespace Phorza\Infrastructure\Persistence\Phalcon;

use Phalcon\Mvc\Model as PhalconModel;
use Phorza\Domain\Criteria\Criteria;
use Phalcon\Mvc\Model\Criteria as PhalconCriteria;
use Phalcon\Mvc\Model\CriteriaInterface;

final class PhalconCriteriaConverter
{
    private PhalconModel $model;
    private Criteria $criteria;

    public function __construct(PhalconModel $model, Criteria $criteria)
    {
        $this->model                   = $model;
        $this->criteria                = $criteria;
    }

    public static function convert(
        PhalconModel $model,
        Criteria $criteria
    ): CriteriaInterface {
        $converter = new self($model, $criteria);

        return $converter->convertToPhalconCriteria();
    }

    public static function convertToCount(
        PhalconModel $model,
        Criteria $criteria
    ): CriteriaInterface {
        $converter = new self($model, $criteria);

        return $converter->convertToPhalconCriteriaToCount();
    }

    private function convertToPhalconCriteria(): CriteriaInterface
    {
        $query = $this->model->query();

        $query = $this->buildExpression($this->criteria, $query);

        if ($this->criteria->hasOrder()) {
            $query = $this->formatOrder($this->criteria, $query);
        }

        if (!empty($this->criteria->limit())) {
            if (!empty($this->criteria->offset())) {
                $query->limit($this->criteria->limit(), $this->criteria->offset());
            } else {
                $query->limit($this->criteria->limit());
            }
        }

        return $query;
    }

    private function convertToPhalconCriteriaToCount(): CriteriaInterface
    {
        $query = $this->model->query();

        $query = $this->buildExpression($this->criteria, $query);

        if ($this->criteria->hasOrder()) {
            $query = $this->formatOrder($this->criteria, $query);
        }

        return $query;
    }

    private function buildExpression(Criteria $criteria, CriteriaInterface $query): CriteriaInterface
    {
        if ($criteria->hasFilters()) {
            $bind = [];
            $filterCount = 0;
            foreach ($criteria->filters() as $filter) {
                /** @var \Phorza\Domain\Criteria\Filter $filter */
                $filterCount++;
                $bind[$filter->field()->value()] = $filter->value()->value();
                if ($filterCount == 1) {
                    $query->where(sprintf('%1$s %2$s :%1$s:', $filter->field()->value(), $filter->operator()->value()));
                } else {
                    $query->andWhere(sprintf('%1$s %2$s :%1$s:', $filter->field()->value(), $filter->operator()->value()));
                }
            }
            if (count($bind)) {
                $query->bind($bind);
            }
        }

        return $query;
    }

    private function formatOrder(Criteria $criteria, CriteriaInterface $query): CriteriaInterface
    {
        if (!$criteria->hasOrder()) {
            return $query;
        }

        $orderBy = [];
        foreach ($criteria->orders() as $order) {
            /** @var \Phorza\Domain\Criteria\Order $order */
            $orderBy[] = sprintf('%s %s', $order->orderBy()->value(), $order->orderType()->value());
        }
        $query->orderBy(implode(', ', $orderBy));

        return $query;
    }
}
