<link href="{{$asset_url}}/vendor/extjs/resources/ext-theme-neptune-all.css" rel="stylesheet" type="text/css">
<script src="{{$asset_url}}/vendor/extjs/ext-all.js"></script>
<script src="{{$asset_url}}/vendor/extjs/ext-locale-zh_CN.js"></script>
<script>

Ext.require([
    'Ext.grid.*',
    'Ext.data.*',
    'Ext.panel.*',
    'Ext.layout.container.Border'
]);

Ext.onReady(function() {

    Ext.define('Book',{
        extend: 'Ext.data.Model',
        fields: [
            'name', 'age'
        ]
    });

    // create the Data Store
    var store = Ext.create('Ext.data.Store', {
        model: 'Book',
        proxy: {
            type: 'ajax',
            url: '{{url("json")}}',
            reader: {type: 'json'}
        }
    });

    // create the grid
    var grid = Ext.create('Ext.grid.Panel', {
        bufferedRenderer: false,
        store: store,
        columns: [
            {text: "name", width: 120, dataIndex: 'name', sortable: true},
            {text: "age", flex: 1, dataIndex: 'age', sortable: true}
        ],
        forceFit: true,
        height:210,
        split: true,
        region: 'north'
    });
        
    // define a template to use for the detail view
    var bookTplMarkup = [
        'Title: <a href="{DetailPageURL}" target="_blank">{Title}</a><br/>',
        'Author: {Author}<br/>',
        'Manufacturer: {Manufacturer}<br/>',
        'Product Group: {ProductGroup}<br/>'
    ];
    var bookTpl = Ext.create('Ext.Template', bookTplMarkup);

    Ext.create('Ext.Panel', {
        renderTo: 'binding-example',
        //frame: true,
        title: 'Book List',
        width: 580,
        height: 400,
        layout: 'border',
        items: [
            grid, {
                id: 'detailPanel',
                region: 'center',
                bodyPadding: 7,
                bodyStyle: "background: #ffffff;",
                html: 'Please select a book to see additional details.'
        }]
    });
    
    // update panel body on selection change
    grid.getSelectionModel().on('selectionchange', function(sm, selectedRecord) {
        if (selectedRecord.length) {
            var detailPanel = Ext.getCmp('detailPanel');
            detailPanel.update(bookTpl.apply(selectedRecord[0].data));
        }
    });
    store.load();
});
</script>

<div id="binding-example"></div>