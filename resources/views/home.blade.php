@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                  @if(Session::has('msg'))
                    <div class="alert alert-success">
                      {{ Session::get('msg') }}
                    </div>
                  @endif
                    <form method="post" id="main_form" action="{{ route('search') }}">
                        @csrf
                        <div class="form-group">
                             <label>Search</label>
                             <input type="text" class="form-control" name="searchQuery" placeholder="Enter your query">
                             <span class="text-danger error-text searchQuery_error"></span>
                        </div>
                        <br>
                        <button type="submit" class="btn btn-block btn-primary">Search</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
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
              <th scope="col">Exchange</th>
              <th scope="col">Short Name</th>
              <th scope="col">Quote Type</th>
              <th scope="col">Symbol</th>
            </tr>
          </thead>
          <tbody>
            @foreach($financeResult as $key=> $finance)
            <tr>
              <th scope="row"> {{ $key+1 }}</th>
              <th>{{ $finance['exchange'] }}</th>
              <td>{{ $finance['shortname'] }}</td>
              <td>{{ $finance['quoteType'] }}</td>
              <td>{{ $finance['symbol'] }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>

    </div>
</div>



<script src="{{ asset('js/jquery.js') }}"></script>
<script type="text/javascript">
    
$(function(){
    $("#main_form").on('submit', function(e){
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
                if(data.status == 0){
                    $.each(data.error, function(prefix, val){
                        $('span.'+prefix+'_error').text(val[0]);
                    });
                }else{
                    $('#main_form')[0].reset();
                    var len = 0;

                   if(data.result != null){
                     len = data.result.length;
                   }
                    if(len > 0){
                     // Read data and create <option >
                     var option = "<option value='selected'>Select One</option>";
                     for(var i=0; i<len; i++){
                       var name = data.result[i].shortname;
                       var value = data.result[i].exchange+'_'+data.result[i].shortname+'_'+data.result[i].quoteType+'_'+data.result[i].symbol;
                       option += "<option value='"+value+"'>"+name+"</option>";
                     }
                     $("#finance_result").show();
                     $(".sel_province").html(option);
                   }
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
