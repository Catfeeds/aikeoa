<form method="post" action="<?php echo url(); ?>" id="myform" name="myform">

<div ng-app="condition">

    <?php
    if (sizeof($steps)):
    foreach ($steps as $step):
    ?>
    <div class="panel">
        <table ng-controller="Condition<?php echo $step->id; ?>Ctrl" class="table table-form table-condensed table-hover">
            <thead>
            <tr>
                <th colspan="7" align="left">转入: <?php echo $step->name; ?></th>
            </tr>
            <tr>
                <th align="center">左括号</th>
                <th align="center">字段</th>
                <th align="center">条件</th>
                <th>值</th>
                <th align="center">右括号</th>
                <th align="center">逻辑</th>
                <th align="center">
                    <a ng-click="create();" class="btn btn-xs btn-default"><i class="icon icon-plus"></i></a>
                </th>
            </tr>
            </thead>
            <tbody>
                <tr ng-repeat="item in items">
                    <td>
                        <select name="condition[<?php echo $step->id; ?>][{{$index}}][l]" ng-model="item.l" class="input-sm form-control">
                            <option value=""></option>
                            <option value="(">(</option>
                        </select>
                    </td>
                    <td>
                        <select name="condition[<?php echo $step->id; ?>][{{$index}}][f]" ng-model="item.f" class="input-sm form-control">
                            <option value=""></option>
                            <?php 
                                foreach ($columns as $table => $column):
                                foreach ($column['data'] as $field):
                            ?>
                            <?php if ($field['auto'] == 1): ?>
                                <option value="<?php echo $field['field']; ?>"><?php echo $field['name']; ?></option>
                            <?php else: ?>
                                <option value="<?php echo $table; ?>.<?php echo $field['field']; ?>">
                                    <?php if ($column['master'] == 0): ?>[子表]<?php endif; ?>
                                    <?php echo $field['name']; ?>
                                </option>
                            <?php endif; ?>
                            <?php 
                                endforeach;
                                endforeach;
                            ?>
                        </select>
                    </td>
                    <td>
                        <select name="condition[<?php echo $step->id; ?>][{{$index}}][c]" ng-model="item.c" class="input-sm form-control">
                            <option value=""></option>
                            <option value="==">等于</option>
                            <option value="<>">不等于</option>
                            <option value=">">大于</option>
                            <option value="<">小于</option>
                            <option value=">=">大于等于</option>
                            <option value="<=">小于等于</option>
                            <!--
                            <option value="like">包含</option>
                            <option value="not like">不包含</option>
                            -->
                        </select>
                    </td>
                    <td>
                        <input name="condition[<?php echo $step->id; ?>][{{$index}}][v]" ng-model="item.v" class="input-sm form-control">
                    </td>
                    <td>
                        <select name="condition[<?php echo $step->id; ?>][{{$index}}][r]" ng-model="item.r" class="input-sm form-control">
                            <option value=""></option>
                            <option value=")">)</option>
                        </select>
                    </td>
                    <td>
                        <select name="condition[<?php echo $step->id; ?>][{{$index}}][i]" ng-model="item.i" class="input-sm form-control">
                            <option value=""></option>
                            <option value="and">and</option>
                            <option value="or">or</option>
                        </select>
                    </td>
                    <td align="center">
                        <a ng-click="remove($index);" data-condition="delete" class="btn btn-xs btn-default"><i class="icon icon-trash"></i></a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php
    endforeach;
    endif;
    ?>
</div>

<script type="text/javascript">
angular.module("condition", [])

<?php foreach ($steps as $step): ?>
.controller('Condition<?php echo $step->id; ?>Ctrl', function($scope) {
    $scope.items = <?php echo json_encode((array)old('condition.'.$step->id, $condition[$step->id])); ?>;
    $scope.create = function() {
        var item = {};
        $scope.items.push(item);
    };
    $scope.remove = function(index) {
        $scope.items.splice(index, 1);
    };
})
<?php endforeach; ?>

$(function() {
    angular.bootstrap($('#condition-app'),['condition']);
});
</script>

<div class="panel">
<table class="table table-form m-b-none">
    <tr>
        <td>
            <input type="hidden" name="id" value="<?php echo $row->id; ?>">
            <input type="hidden" name="model_id" value="<?php echo $model->id; ?>">
            <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> 保存</button>
            <button type="button" onclick="history.back();" class="btn btn-default">返回</button>
        </td>
    </tr>
</table>
</div>

</form>

</div>