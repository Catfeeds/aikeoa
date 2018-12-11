<ul class="list-group list-group-lg no-radius no-bg auto m-b-none">

	<li class="list-group-item no-border">
	    <span class="text-ellipsis">
	      	<span class="label bg-info">CalDAV URL</span>
	      	<code>{{$public_url}}/caldav</code>
             <p class="text-muted">
                安卓系统黑莓请使用此地址
            </p>
	    </span>
  	</li>

  	<li class="list-group-item" style="border-bottom:0;">
	    <span class="text-ellipsis">
	      	<span class="label bg-info">CalDAV URL(<strong>IOS/OS X</strong>)</span>
	      	<code>{{$public_url}}/caldav/principals/{{Auth::user()->username}}</code>
            <p class="text-muted">
                苹果系统及苹果手机请使用此地址
            </p>
	    </span>
  	</li>
</ul>
