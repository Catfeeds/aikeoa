<table class="table">
@foreach($rows as $row)
<tr>
    <td align="center">
        <?php $path = strpos($row['path'], '.'); ?>
        @if($path)
            <img src="{{$public_url.'/uploads/'.$row['path']}}">
        @else
            <img src="{{$public_url.'/uploads/'.$row['path']}}/{{$row['name']}}">
        @endif
    </td>
</tr>
@endforeach
</table>