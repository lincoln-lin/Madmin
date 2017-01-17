<?php
/**
 * Created by IntelliJ IDEA.
 * User: liujing <liujing@meizu.com>
 * Date: 5/26/16
 * Time: 6:38 PM
 */

namespace backend\models;

trait TimeRangeTrait
{
    public function filterTime($query, $column)
    {
        $start = "{$column}_start";
        $end = "{$column}_end";
        if ($this->$start && ($t = strtotime($this->$start)) > 0) {
            $query->andFilterWhere(['>=', $column, $t]);
        }
        if ($this->$end && ($t = strtotime($this->$end)) > 0) {
            $query->andFilterWhere(['<=', $column, $t]);
        }
    }
}
