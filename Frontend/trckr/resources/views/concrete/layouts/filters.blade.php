<div class="row">
    <div class="col-sm-1">
        <select class="form-control pageCountselect">
            <option>5</option>
            <option>10</option>
            <option selected="selected">25</option>
            <option>50</option>
            <option>100</option>
        </select>
    </div>

    <div class="col-sm-9">
        @yield('tableFilters')
    </div>
    
    <div class="col-sm-2 text-right">
        <nav aria-label="Page navigation">
        <ul class="pagination" style="float: right">
    	   @if($pagination['current_page'] > 1)
            <li class="page-item"><a class="page-link" href="{{ URL::current() }}?page={{ ($pagination['current_page'] - 1) }}">Previous</a></li>
            @endif

            @if($pagination['total_pages'] > 1)
            <li class="page-item">
                <select class="form-control pages_option pagination_current_page">
                    @for ($i = 1; $i < $pagination['total_pages'] + 1; $i++)
                    <option {{ ($i==$pagination['current_page'])? 'selected=""' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </li>
            @endif
        
            @if($pagination['current_page'] < $pagination['total_pages'])
            <li class="page-item"><a class="page-link" href="{{ URL::current() }}?page={{ ($pagination['current_page'] + 1) }}">Next</a></li>
            @endif
        </ul>
        </nav>
    </div>
</div>