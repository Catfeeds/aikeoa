<table class="tlist">
  <tr>
    <td>
        <div class="category-tag">
         @if($categorys) @foreach($categorys as $category)
            <a @if($category['id']==$type_id)  class="selected" @endif  href="{{url()}}?type_id={{$category['id']}}">{{$category['name']}}</a>
         @endforeach @endif
        </div>
    </td>
  </tr>
</table>

<table class="list">
  <tr>
    <?php $i = 0; ?>
     @if($rows) @foreach($rows as $row)
      <td align="center" valign="top">
        <p class="left" style="padding:5px;border-bottom:1px solid #eee;color:#999;">
        <span style="float:right;">
         @if(isset($access['add']))
            <a class="option" href="{{url('add',['id'=>$row['id']])}}">编辑</a>
         @endif
         @if(isset($access['delete']))
        <a class="option" onclick="app.confirm('{{url('delete',['id'=>$row['id']])}}','确定要删除吗？');" href="javascript:;">删除</a>
         @endif
        </span>
        主题: <span style="color:#333;">{{$row['title']}}</span></p>
        <p class="left" style="padding:5px;text-align:center;"><img style="margin:2px;padding:2px;border:1px solid #eee;max-width:350px;" src="{{$public_url}}/uploads/{{$row['path']}}/{{$row['attach_file']}}"></p>
        <div class="left" style="padding:5px;color:#999;">{{$row['content']}}</div>
    </td>
    <?php $i++; ?>
     @if($i%3==0)
        </tr><tr>
     @endif

 @endforeach @endif

</table>

{{$rows->render()}}
