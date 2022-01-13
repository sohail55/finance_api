@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('List Detail') }}</div>

                <div class="card-body">
                  @if(Session::has('msg'))
                    <div class="alert alert-success">
                      {{ Session::get('msg') }}
                    </div>
                  @endif
                    <form method="post" id="list_form" action="{{ route('updateWatchList',['id'=>$user_watchlists['id']]) }}">
                        @csrf
                        <div class="form-group">
                            <label>List Name</label>
                            <input type="text" class="form-control" name="list_name" value="{{ $user_watchlists['list_name'] }}" placeholder="Enter your list Name here">
                            <span class="text-danger error-text list_name_error"></span>
                        </div>
                        <div class="row mt-4 mb-4">
                            @foreach($companies_list as $company_list)
                            <div class="col-md-6">
                              <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="company_id" name="company_id[]" <?php echo in_array($company_list['id'], $companyIds ) ? ' checked' : '' ?> value="{{ $company_list['id'] }}">
                                <label class="form-check-label" for="exampleCheck1">{{ $company_list['shortname']  }}</label>
                              </div>
                            </div>
                            @endforeach
                        <br><br><br>
                        <button type="submit" class="btn btn-block btn-primary">Update</button>
                        <!-- <a href="{{ route('createList') }}" class="btn btn-block btn-primary">Back</a> -->
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body" id="finance_result" style="display: none;">
                    <form method="post" action="{{ route('saveResult') }}" id="">
                        @csrf
                        <div class="row mt-2 mb-2">
                            <select class="form-control sel_province" name="financeResult" id="">
                                
                            </select>
                        </div>
                        <button type="submit" class="btn btn-block btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> 

 <div class="container ">
    <div class="row justify-content-center mt-4">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">List Name</th>
              <th scope="col">Count</th>
              <th scope="col">View Detail</th>
            </tr>
          </thead>
          <tbody>
            @foreach($user_watchlists as $key => $user_watchlist)
              <tr>
                <th scope="row"> {{ $key+1 }}</th>
                <th>{{ $user_watchlist['list_name'] }}</th>
                <td>{{ count($user_watchlist['watch_list_company']) }}</td>
                <td><a href="{{ route('editList',  ['list_id' => $user_watchlist['id']]) }}" ><i class="fa fa-edit"></i> </a></td>
              </tr>
            @endforeach
          </tbody>
        </table>

    </div>
</div> --}}



<script src="{{ asset('js/jquery.js') }}"></script>
<script type="text/javascript">
    
$(function(){
    $("#list_form").on('submit', function(e){
        e.preventDefault();

        $.ajax({
            url:$(this).attr('action'),
            method:$(this).attr('method'),
            data:new FormData(this),
            processData:false,
            dataType:'json',
            contentType:false,
            beforeSend:function(){
                $(document).find('span.error-text').text('');
            },
            success:function(data){
              //alert(data);
                if(data.status == 0){
                    $.each(data.error, function(prefix, val){
                        $('span.'+prefix+'_error').text(val[0]);
                    });
                }else{
                    $('#list_form')[0].reset();
                    window.location.href = data.result;
                }
            }
        });
    });
});

window.setTimeout(function() {
    $(".alert").fadeTo(3500, 0).slideUp(3500, function(){
        $(this).remove();
    });
}, 2000);

</script>
@endsection