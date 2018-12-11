<?php

/**
 * 业务流程审核设置，这些设置会影响到关于角色审核的特殊定义
 * 目前共10个步骤，不具备回溯功能
 */
$order['audit'] = array(
    // 客户创建订单
    0 => array(
        'name'=>'创建新订单',
        'sms'=>array(
            'orderassist' => '%sender%的客户于%time%提交了新订单,数量：%amount%件，请及时处理。'
        ),
        'role' => 'customer',
        'flow_step_state' => 'start',
        'next_step_ids' => array(1),
        'fields' => array(
            'edit' => array('level_amount'),
        ),        
    ),
    // 订单助理
    1=> array(
        'name'=>'等待[订单助理]审核',
        'sms' => array(
            'salesman' => '%sender%的客户于%created%创建的订单，数量：%amount%件，请尽快进入系统审核。',
            'customer' => '您在%created%创建的订单已经收到，具体订单处理状态请于次日登录发货管理查询。',
        ),
        'role_text' => '订单助理',
        'role' => 'orderassist',
        'last_step_ids' => array(0),
        'next_step_ids' => array(2),
        'flow_step_state' => 'next',
        'prints' => array('default'),
        'forms' => array(
            'a_1' => array(
                'title' => '该客户上次发货日期','required' => 1,
                'text' => '<input placeholder="" type="text" class="form-control input-sm" id="flow_content_add" name="flow[content][a_1]" data-toggle="date" size="13">',
            ),
            'a_2' => array(
                'title' => '为几天前','required' => 1,
                'text' => '<input type="text" class="form-control input-sm" id="flow_content_explain" name="flow[content][a_2]" size="24">',
            ),
            'a_3' => array(
                'title' => '上次不含物料总计多少个条码多少件货','required' => 1,
                'text' => '<input type="text" class="form-control input-sm" id="flow_content_explain" name="flow[content][a_3]" size="24">',
            ),
        ),
        'fields' => array(
            'edit' => array('amount','fact_amount','price','remark','content'),
        ),
    ),
    // 销售人员
    2 => array(
        'name' => '等待[销售人员]审核',
        'sms'  => array(
            'supervise' => '有新的订单等待你审核，请尽快进入系统审核。',
        ),
        'role_text' => '销售人员',
        'last_step_ids' => array(1),
        'next_step_ids' => array(3),
        'flow_step_state' => 'next',
        'role' => 'salesman',
        'forms' => array(
            'add' => array(
                'title' => '订单新增产品及数量','required' => 1,
                'text' => '<input placeholder="若增加已下单品的数量，请直接在订单列表里修改，本栏填写新增单品及数量" type="text" class="form-control input-sm" id="flow_content_add" name="flow[content][add]" size="24">',
            ),
            'explain' => array(
                'title' => '新增原因','required' => 1,
                'text' => '<input type="text" class="form-control input-sm" id="flow_content_explain" name="flow[content][explain]" size="24">',
            ),
        ),
        'fields' => array(
            'edit' => array('level_amount'),
        ),
    ),
    // 客户经理
    3 => array(
        'name'=>'等待[客户经理]审核',
        'sms'=>array(
            'finance' => '有新的订单等待你审核，请尽快进入系统审核。',
            'admin'   => '有新的订单等待你审核，请尽快进入系统审核。',
        ),
        'role_text' => '客户经理',
        'role' => 'supervise',
        'last_step_ids' => array(2),
        'next_step_ids' => array(4,14),
        'flow_step_state' => 'next',
        'fields' => array(
            'edit' => array('amount','fact_amount'),
        ),
    ),
    // 总经理分支
    14=> array(
        // 订单提醒状态
        'name'=>'等待[总经理]审核',
        'sms' => array(
            'orderassist' => '有新的订单等待你审核，请尽快进入系统审核。',
        ),
        'role_text' => '总经理',
        'role' => 'admin',
        'last_step_ids' => array(3),
        'next_step_ids' => array(4),
        'flow_step_state' => 'next',
        'prints' => array('default'),
        'fields' => array(
            'edit' => array('amount','fact_amount','price'),
        ),        
    ),
    // 订单助理第二次
    4 => array(
        // 订单提醒状态
        'name'=>'等待[订单助理]回传客户',
        'sms' => array(
            'finance' => '有新的订单等待你审核，请尽快进入系统审核。',
            'customer'  => '尊敬的%sender% 你好，您于%created%创建的订单已经通过传真回传。请注意查收及办款。',
        ),
        'role_text' => '订单助理',
        'role' => 'orderassist',
        'last_step_ids' => array(3),
        'next_step_ids' => array(5),
        'flow_step_state' => 'next',
        'prints' => array('default'),
        'fields' => array(
            'edit' => array('amount','fact_amount','price','remark','content'),
        ),
        'forms' => array(
            'fax_at' => array(
                'title' => '传真时间','required' => 1,
                'text' => '<input type="text" class="form-control input-sm" id="order_fax_at" name="order[fax_at]" data-toggle="datetime" size="23" readonly />',
            ),
            /*
            'pay_time' => array(
                'title' => '付款时间','required' => 1,
                'text' => '<input type="text" class="form-control input-sm" id="order_pay_time" name="order[pay_time]" value="<?php echo format_datetime($order["pay_time"]); ?>" data-toggle="datetime" size="22" readonly />',
            ),
            */
        ),
    ),
   // 销售会计
   5 => array(
        // 订单提醒状态
        'name'=>'等待[销售会计]审核',
        'sms'=>array(
            'orderassist' => '有新的订单等待你审核，请尽快进入系统审核。',
        ),
        'role_text' => '销售会计',
        'role' => 'finance',
        'last_step_ids' => array(4),
        'next_step_ids' => array(6),
        'flow_step_state' => 'next',
        'forms' => array(
            'pay_time' => array(
                'title' => '付款时间','required' => 1,
                'text' => '<input type="text" class="form-control input-sm" id="order_pay_time" name="order[pay_time]" value="<?php echo format_datetime($order["pay_time"]); ?>" data-toggle="datetime" size="22" readonly />',
            ),
        ),
    ),
    // 订单助理第三次
    6=> array(
        'name'=>'等待[订单助理]备货',
        'sms'=>array(
            'transport' => '有新的订单等待你审核，请尽快进入系统审核。',
        ),
        'role_text' => '订单助理',
        'last_step_ids' => array(5),
        'next_step_ids' => array(7),
        'flow_step_state' => 'next',
        'fields' => array(
            'edit' => array('fact_amount','price','remark','content'),
        ),
        'forms' => array(
            'plan_time' => array(
                'title' => 'OP-PM备货计划','required' => 1,
                'text' => '<input type="text" class="form-control input-sm" id="order_plan_time" name="order[plan_time]" data-toggle="date" size="13" readonly />',
            ),            
            'stock' => array(
                'title' => 'OP-DC发货计划','required' => 1,
                'text' => '<input type="text" class="form-control input-sm" id="flow_content_stock" name="flow[content][stock]" data-toggle="date" size="13" readonly /> ',
            ),
        ),
        'role' => 'orderassist',
    ),
    // 配送助理
    7=> array(
        'name'=>'等待[配送助理]预发信息',
        'sms'=>array(
            'finance' => '有新的订单等待你审核，请尽快进入系统审核。',
        ),
        'role_text' => '配送助理',
        'role' => 'transport',
        'last_step_ids' => array(6),
        'next_step_ids' => array(8),
        'flow_step_state' => 'next',
        'forms' => array(
            'logistics_id' => array(
                'title' => '预发物流公司','required' => 1,
                'text' => Dialog::user('logistics', 'transport[logistics_id]', '', 0, 0),
            ),
            'advance_car_company' => array(
                'title' => '预发物流公司备注','required' => 1,
                'text' => '<input type="text" class="form-control input-sm" id="transport_advance_car_company" name="transport[advance_car_company]" />',
            ),
            'advance_car_number' => array(
                'title' => '预发车牌号','required' => 1,
                'text' => '<input type="text" class="form-control input-sm" id="transport_advance_car_number" name="transport[advance_car_number]" />',
            ),
            'advance_amount' => array(
                'title' => '预发数量','required' => 1,
                'text' => '<input type="text" class="form-control input-sm" id="transport_advance_amount" name="transport[advance_amount]" placeholder="件" />',
            ),
            'advance_weight' => array(
                'title' => '预发重量','required' => 1,
                'text' => '<input type="text" class="form-control input-sm" id="transport_advance_weight" name="transport[advance_weight]" />',
            ),
            'advance_time' => array(
                'title' => '预发时间','required' => 1,
                'text' => '<input type="text" class="form-control input-sm" id="transport_advance_time" name="transport[advance_time]" data-toggle="datetime" />',
            ),
            'advance_depot' => array(
                'title' => '预发仓位','required' => 1,
                'text' => '<input type="text" class="form-control input-sm" id="transport_advance_depot" name="transport[advance_depot]" />',
            ),
            'advance_depot_number' => array(
                'title' => '预发仓号','required' => 1,
                'text' => '<input type="text" class="form-control input-sm" id="transport_advance_depot_number" name="transport[advance_depot_number]" placeholder="格式：仓位号-发货序号-装车序号" />',
            ),      
        ),
    ),
   // 订单助理第四次
   8=> array(
        'name'=>'等待[订单助理]打印发货单',
        'sms'=>array(
            'orderassist' => '有新的订单等待你审核，请尽快进入系统审核。',
            'transport'   => '有新的订单等待你审核，请尽快进入系统审核。',
        ),
        'role_text' => '订单助理',
        'role' => 'orderassist',
        'last_step_ids' => array(7),
        'next_step_ids' => array(9),
        'flow_step_state' => 'next',     
        'prints' => array('delivery'),
        'fields' => array(
            'edit' => array('fact_amount','price','remark','content','batch_number'),
        ),        
        'forms' => array(
            'deliver' => array(
                'title' => '发货单交付发货助理时间','required' => 1,
                'text' => '<input type="text" class="form-control input-sm" id="flow_content_deliver" name="flow[content][deliver]" data-toggle="datetime" size="24" readonly />',
            ),
        ),
    ),
    // 发货助理第一次
    9=> array(
        'name'=>'等待[发货助理]发货',
        'sms' => array(
            'salesman' => '%sender%的%created%所下订单(订单号：%number%)已于%time%发出，请登陆盛华系统运营管理中进行查询。',
            'customer'   => '您在%created%所下订单(订单号：%number%)已于%time%发出，请登陆盛华系统运营管理中进行查询。',
        ),
        'role_text' => '发货助理',
        'role' => 'delivery',
        'prints' => array('follow'),
        //调用出库函数
        'outStock' => 1,
        'last_step_ids' => array(8),
        'next_step_ids' => array(10),
        'flow_step_state' => 'next',
        'fields' => array(
            'edit' => array('content','fact_amount','batch_number','remark'),
        ),
        'forms' => array(
            'delivery_time' => array(
                'title' => '发货时间','required' => 1,
                'text' => '<input type="text" class="form-control input-sm" id="order_delivery_time" name="order[delivery_time]" data-toggle="datetime" size="24" readonly />',
            ),
        ),        
    ),
    // 销售会计
    10=> array(
        'name'=>'等待[销售会计]审核实发数量',
        'sms' => array(),
        'role_text' => '销售会计',
        'role' => 'finance',
        'last_step_ids' => array(9),
        'next_step_ids' => array(11),
        'flow_step_state' => 'next',
        'fields' => array(
            'edit' => array('fact_amount'),
        ),
        'forms' => array(
            'accord' => array(
                'title' => '发货助理提交数据与营运的出货单是否符合','required' => 1,
                'text' => '<input type="text" class="form-control input-sm" id="flow_content_accord" name="flow[content][accord]" class="input-text" size="24" /> <span class="help-inline">请输入是与否。</span>',
            ),
        ), 
    ),
    // 配送助理第二次
    11 => array(
        'name'=>'等待[配送助理]提供发货信息',
        'sms'=>array(),
        'role_text' => '配送助理',
        'role' => 'transport',
        'last_step_ids' => array(10),
        'next_step_ids' => array(12),
        'flow_step_state' => 'next',
        'prints' => array('transport'),
        'forms' => array(
            'advance_car_company' => array(
                'title' => '承运公司',
                'text' => '<?php echo $logistics["name"]; ?>',
            ),
            'contact' => array(
                'title' => '承运司机','required' => 1,
                'text' => '<input type="text" class="form-control input-sm" id="transport_contact" name="transport[contact]" value="<?php echo $transport["contact"]; ?>" />',
            ),
            'phone' => array(
                'title' => '承运司机电话','required' => 1,
                'text' => '<input type="text" class="form-control input-sm" id="transport_phone" name="transport[phone]" value="<?php echo $transport["phone"]; ?>"  />',
            ),          
            'reference_number' => array(
                'title' => '运单号','required' => 1,
                'text' => '<input type="text" class="form-control input-sm" id="transport_reference_number" name="transport[reference_number]" value="<?php echo $transport["reference_number"]; ?>" />',
            ),
            'manner' => array(
                'title' => '发货方式','required' => 1,
                'text' => '<input type="text" class="form-control input-sm" id="transport_manner" name="transport[manner]" value="<?php echo $transport["manner"]; ?>" />',
            ),
            'advance_arrival_time' => array(
                'title' => '预计到货时间','required' => 1,
                'text' => '<input type="text" class="form-control input-sm" id="transport_advance_arrival_time" name="transport[advance_arrival_time]" value="<?php echo $transport["advance_arrival_time"] > 0 ? date("Y-m-d H:i:s", $transport["advance_arrival_time"]) : ""; ?>" data-toggle="datetime" readonly />',
            ),
            'freight_weight' => array(
                'title' => '实际重量','required' => 1,
                'text' => '<input type="text" class="form-control input-sm" id="freight_weight" name="transport[freight_weight]" value="<?php echo $transport["freight_weight"]; ?>" />',
            ),
            'freight_tonnage' => array(
                'title' => '实际吨位','required' => 1,
                'text' => '<input type="text" class="form-control input-sm" id="freight_tonnage" name="transport[freight_tonnage]" value="<?php echo $transport["freight_tonnage"]; ?>" />',
            ),
            'freight_price' => array(
                'title' => '运价','required' => 1,
                'text' => '<input type="text" class="form-control input-sm" id="freight_price" name="transport[freight_price]" value="<?php echo $transport["freight_price"]; ?>" />',
            ),
            'delivery_money' => array(
                'title' => '送货费','required' => 1,
                'text' => '<input type="text" class="form-control input-sm" id="delivery_money" name="transport[delivery_money]" value="<?php echo $transport["delivery_money"]; ?>" />',
            ),
            'freight_money' => array(
                'title' => '运费金额','required' => 1,
                'text' => '<input type="text" class="form-control input-sm" id="freight_money" name="transport[freight_money]" value="<?php echo $transport["freight_money"]; ?>" />',
            ),
            /*
            'arrivalpattern' => array(
                'title' => '到货方式','required' => 1,
                'text' => '<input type="text" class="form-control input-sm" id="transport_arrivalpattern" name="transport[arrivalpattern]" value="<?php echo $transport["arrivalpattern"]; ?>" />',
            ),
            'freight' => array(
                'title' => '运费承担','required' => 1,
                'text' => '<input type="text" class="form-control input-sm" id="transport_freight" name="transport[freight]" value="<?php echo $transport["freight"]; ?>" />',
            ),
            'freight_manner' => array(
                'title' => '运费付款方式','required' => 1,
                'text' => '<input type="text" class="form-control input-sm" id="transport_freight_manner" name="transport[freight_manner]" value="<?php echo $transport["freight_manner"]; ?>" />',
            ),
             */
        ),
    ),
    // 配送助理第二次
    12=> array(
        'name'=>'等待[客户经理]确认到货时间',
        'sms'=>array(),
        'role_text' => '客户经理',
        'role' => 'supervise',
        'last_step_ids' => array(11),
        'next_step_ids' => array(13),
        'flow_step_state' => 'end',
        'forms' => array(
            'arrival_time' => array(
                'title' => '客户到货时间','required' => 1,
                'text' => '<input type="text" class="form-control input-sm" id="order_arrival_time" name="order[arrival_time]" data-toggle="datetime" size="24" readonly />',
            ),
            'receipt_time' => array(
                'title' => '收到回执单及运单时间','required' => 1,
                'text' => '<input type="text" class="form-control input-sm" id="flow_content_receipt_time" name="flow[content][receipt_time]" data-toggle="datetime" class="input-text" size="24" readonly />',
            ),
            /*
            'receipt' => array(
                'title' => '回执方式','required' => 1,
                'text' => '<select type="text" class="form-control input-sm" id="flow_content_receipt" name="flow[content][receipt]" class="input-text"><option value="客户回执单">客户回执单</option><option value="货运回执单">货运回执单</option></select>',
            ),*/
            'receipt_question' => array(
                'title' => '到货问题以及解决方案','required' => 1,
                'text' => '<input type="text" class="form-control input-sm" id="flow_content_receipt_question" name="flow[content][receipt_question]" class="input-text" size="24" />',
            ),
            'compensate_money' => array(
                'title' => '赔付金额','required' => 1,
                'text' => '<input type="text" class="form-control input-sm" id="transport_compensate_money" name="transport[compensate_money]" value="<?php echo $transport["compensate_money"]; ?>" />',
            ),
        ), 
    ),
    // 配送助理第三次
    13 => array(
        'name'=>'订单结束(物流结算)',
        'sms'=>array(),
        'role' => 'complete',
    ),
);

// 步骤默认行为
$order['defaults'] = [
    // 那些角色可以编辑字段
    'edit' => [
        'price'   => ['orderassist'],
        'content' => ['orderassist'],
    ],
    // 那些角色隐藏字段
    'hidden' => [
        'price'   => ['customer'],
    ],
];

$order['role'] = array(
    'step' => array(
        // 配送助理角色
        '23'  => '7',
        // 发货助理角色
        '32'  => '9',
        // 营运仓管A角色
        '34'  => '8',
        '35'  => '8',
        '36'  => '8',
    ),
    'print' => array(
        '34' => array('shipping'),
        '35' => array('delivery'),
        '36' => array('delivery'),
        '32' => array('follow'),
        '23' => array('transport','follow'),
        '19' => array('transport','follow','shipping','default'),
        '1'  => array('transport','follow','shipping','default','delivery'),
        '22' => array('transport','follow','shipping','default','delivery'),
        '16' => array('follow'),
    ),
);

return $order;