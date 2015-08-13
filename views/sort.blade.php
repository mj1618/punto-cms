
<script src="https://cdn.communitytogo.com.au/html5sortable/sort.js" type="text/javascript"></script>
<script>
    $(function(){

        $('.sortable').sortable();

        $('#sort-form').submit(function(e){
            var ret=[];
            $('.sortable li').each(function(i, val){
                ret.push({'id':$(val).attr('id'),'sort':i});
            });
            console.log(JSON.stringify(ret));
            $('#json').val(JSON.stringify(ret));
            //e.preventDefault();
        });
    });
</script>

<ul class="sortable">
    @foreach($items as $i)
        <li id="{{$i->id}}">{{ $i->{$key?$key:'name'} }}</li>
    @endforeach
</ul>

<form id="sort-form" method="post" action="{{$actionUrl}}">
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
    <input type="hidden" id="json" name="json" value=""/>
    <button type="submit" class="btn btn-default">Save</button>
</form>