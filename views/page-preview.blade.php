
        <div class='box box-default'>
            <div class='box-header with-border'>
                <h3 class='box-title'><i class="fa fa-tag"></i> Page Preview: {{ $page->name }}</h3>

            </div>
            <div class='box-body'>
                <iframe src="{{$page->url}}" style="min-height:700px;border:0;width:100%"></iframe>
            </div>
        </div>
