<div id="product-app" ng-controller="ItemsCtrl">
<table class="table">
    <thead>
        <tr class="x-line">
            <th align="center" width='300'>产品</th>
            <th align="center" width='100'>单价</th>
            <th align="center" width='100'>折扣率(%)</th>
            <th v-show="editor" align="center"></th>
        </tr>
    </thead>

    <tbody>
        <tr class="x-line" v-for="(item,index) in items">
            <td align="left">
                <input v-model="item.product_text" v-on:click="productDialog(index)" readonly="readonly" style="width:250px;" class="form-control input-sm input-inline" type="text">
                <input v-bind:name="'price_item['+index+'][product_id]'" type="hidden" v-model="item.product_id">
            </td>
            <td align="left">
                <input v-model="item.price" class="form-control input-sm input-inline" v-bind:name="'price_item['+index+'][price]'" type="text">
            </td>
            <td align="left">
                <input v-model="item.rate" class="form-control input-sm input-inline" v-bind:name="'price_item['+index+'][rate]'" type="text">
            </td>
            <td v-show="editor" align="right">
                <input v-model="item.id" type="hidden">
                <a class="option" v-show="option.remove" href='javascript:;' v-on:click='remove(index)'><i class="icon icon-trash"></i> 删除</a>
            </td>
        </tr>
    </tbody>

    <tfoot>
        <tr class="x-line">
            <th align="left">合计</th>
            <th></th>
            <th></th>
            <th v-show="editor" align="right">
                <a class="option" v-show="option.add" v-on:click="add()" href="javascript:;"><i class="icon icon-plus"></i> 新增</a>
            </th>
        </tr>
    </tfoot>
</table>
</div>

<script src="<?php echo $asset_url; ?>/vendor/vue.min.js"></script>
<script type="text/javascript">

var items = <?php echo $price_items; ?>;
 // 获取客户类型决定价格
var price_text = 'price<?php echo $customer['user']['post'] > 0 ? $customer['user']['post'] : 1; ?>';

new Vue({
    el: '#product-app',
    data: {
        editor:1,
        option: {
            add:1,
            remove:1,
        },
        items: items
    },
    methods: {
        add: function() {
            var item = {rate:100};
            this.items.push(item);
        },
        remove: function(index) {
            this.items.splice(index, 1);
        },
        productDialog: function(index) {
            var me     = this;
            var jqgrid = null;

            $('#product').__dialog({
                title: '产品列表',
                onShow: function() {
                    var self = this;
                    $.get(app.url('product/product/dialog', {multi:1}), function(html) {

                        self.html(html);

                        // 默认选中
                        window.productDialog.setSelecteds = function() {
                            jqgrid = this;
                            jqgrid.jqGrid('setSelection', me.items[index].product_id);
                        }

                        // 双击选中
                        window.productDialog.getSelecteds = function() {
                            var rows = jqgrid.jqGrid('getSelections');
                            $.each(rows, function(i, row) {
                                me.items.push({
                                    rate: 100,
                                    price: row[price_text], 
                                    product_text: row['text'],
                                    product_id: row['id']
                                });
                            });
                            $(this).dialog("close");
                        }

                    });
                },
                buttons: [{
                    text: "选中",
                    'class': "btn-default",
                    click: function() {
                        var self = this;
                        var rows = jqgrid.jqGrid('getSelections');
                        // 删除当前选中的行
                        me.items.splice(index, 1);
                        $.each(rows, function(i, row) {
                            me.items.push({
                                rate: 100,
                                price: row[price_text], 
                                product_text: row['text'],
                                product_id: row['id']
                            });
                        });
                    }
                },{
                    text: "选中关闭",
                    'class': "btn-default",
                    click: function() {
                        var self = this;
                        var rows = jqgrid.jqGrid('getSelections');
                        // 删除当前选中的行
                        me.items.splice(index, 1);
                        $.each(rows, function(i, row) {
                            me.items.push({
                                rate: 100,
                                price: row[price_text], 
                                product_text: row['text'],
                                product_id: row['id']
                            });
                        });
                        $(this).dialog("close");
                    }
                }]
            });
        }
    },
    mounted: function() {
        this.$nextTick(function () {
            // 保证 this.$el 已经插入文档
        });
        var me = this;
    }
});
</script>