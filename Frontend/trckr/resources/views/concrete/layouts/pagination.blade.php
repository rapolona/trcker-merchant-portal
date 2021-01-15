<div class="row">
                    <div class="col-sm-1">
                        <select class="form-control " id="pageCountselect">
                            <option>20</option>
                        </select>
                    </div>
                    <div class="col-sm-11 text-right">



<nav aria-label="Page navigation">
    <ul class="pagination" style="float: right">
    	@if($pagination['current_page'] > 1)
        <li class="page-item"><a class="page-link" href="{{ URL::current() }}?page={{ ($pagination['current_page'] - 1) }}">Previous</a></li>
        @endif

        <!--<li class="page-item"><a class="page-link" href="#">1</a></li>
        <li class="page-item"><a class="page-link" href="#">2</a></li>
        <li class="page-item"><a class="page-link" href="#">3</a></li>-->

        @if($pagination['current_page'] < $pagination['total_pages'])
        <li class="page-item"><a class="page-link" href="{{ URL::current() }}?page={{ ($pagination['current_page'] + 1) }}">Next</a></li>
        @endif
    </ul>
</nav>


</div>
</div>