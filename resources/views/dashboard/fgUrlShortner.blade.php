@extends('layouts.app')
@section('page-css')
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css"> 
<style type="text/css">

a.url_link {
    word-break: break-all;
}
</style>

@endsection
@section('content')


    <div class="row">
        <div class="col-md-12">
            <div class="text-center">
                <h1>
                    URL Shortener
                </h1>
            </div>
            <div class="p-relative">
                <form action="" id="main-form" role="form" method="post" autocomplete="off">

                     {{ csrf_field() }} 
                    <div class="main-form">
                        <div class="row" id="single">
                            <div class="col-xs-12 col-md-10">
                                <input type="text" class="form-control main-input input-url" name="input-url"
                                    value="" placeholder="Paste a long url" autocomplete="off">
                            </div>
                            <div class="col-xs-12 col-md-2">
                                <button class="btn btn-primary btn-block main-button" id="submit-btn"
                                    type="submit">Shorten</button>
                            </div>
                        </div>
                    </div>
                </form>
                <!--/.form-->
            </div>
        </div>
    </div>
    <div class="row results-row" style="border: 1px solid #eee; margin-top:50px; padding:15px;">
        <div class="col-xs-12 col-md-6">
            <p>Original Link: <a href="" target="blank" class="url_link"></a></p>
            <p class="short_link">Shortened Link: <a href="" class="updated_link"
                  id="updated_link"  target="blank"></a></p>

            <input type="text" class="hidden_url" name="hidden-url" id="hidden_url" style="position: absolute; top: -9999px; left: -9999px; opacity: 0;">
        </div>
        <div class="col-xs-12 col-md-6">
            <button class="btn btn-default btn-block" onclick="copyToClipboard()">Copy</button>
            <hr>
            <div class="main-form">
                <div class="row" id="single">
                    <div class="col-xs-12 col-md-6">
                        <input type="text" class="form-control main-input custom-name-input" id="input-url"
                            name="url" value="" placeholder="fgurl/" autocomplete="off">
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <button type="button" class="btn btn-info btn-block main-button"
                            id="custom-name-btn">Add custom Name</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="alert alert-success copied-msg" style="display:none; margin-top:20px">Copied!</div>
    </div>
    <button type="button" class="btn_getlog btn btn-md btn-primary" style="margin-top:20px">Get URL Logs</button>
    <div class="row links-table table-responsive" style="margin-top:50px">
        <table class="table table-bordered table-striped" id="table_log">
            <thead>
                <tr>
                     <td><strong>Clicks</strong></td>
                    <td><strong>Shortend Link</strong></td>
                    <td><strong>Device</strong></td>
                    <td><strong>Original Link</strong></td>
       
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
        
    </div>
@endsection
@section('scripts')
<script src="https://nightly.datatables.net/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js" type="text/javascript"></script>
<script>
        $('.results-row').hide();
        $('#main-form').on('submit', function(e){
            $('.error_btn').remove();
            e.preventDefault();
            var inputval = $('.input-url').val();
            var pattern = /^(http(s)?:\/\/)?(www\.)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/;
            if( inputval != '' && pattern.test(inputval)){
            $('.input-url').attr('href', inputval);
            $('.url_link').text();
            $.ajax({
                type: "POST",
                url: '<?php echo env('APP_URL');?>' + "/fgurl-apicall",
                data: $(this).serialize(),
                success: function(result){
                    $('.results-row').show();
                    $('.url_link').text(inputval);
                   var res = jQuery.parseJSON(result);
                    console.log(res.result);
                    updated_str = res.result;
                    $('.updated_link').text(updated_str);
                    $('.url_link').text(inputval);
                    $('.url_link').attr('href',inputval);
                    $('.updated_link').attr('href',updated_str);
                    //var last = updated_str.substring(updated_str.lastIndexOf("/") + 1, updated_str.length);

                    $('#hidden_url').val(updated_str);
                },
                error: function(result){
                    alert("Error");
                }
            });
        }else{
            $("#single").after('<button type="button" class="btn btn-md btn-danger error_btn" style="margin-top: 20px;">Please Enter Valid URL</button>');
        }
        })
        $('#custom-name-btn').on('click', function(e){
            e.preventDefault();
            var custom_url = $('#hidden_url').val();
            var custom_url_split = custom_url.substring(custom_url.lastIndexOf("/") + 1, custom_url.length);
            $.ajax({
                type: "POST",
                url: '<?php echo env('APP_URL');?>' + "/fgurl-updated-apicall",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "custom_url": $('.custom-name-input').val(),
                    "url": custom_url_split,
                },
                success: function(result){
                    var res = jQuery.parseJSON(result);
                    console.log(res,res.status_code); 
                    if( res.status_code == 200 ){
                         $('.short_msg').text('');
                         $('.updated_link').text('http://fgurl.in/'+res.success);
                         $('.updated_link').attr('href',res.success);
                         $('#hidden_url').val('http://fgurl.in/'+res.success);
                    }
                    else if(res.status_code == 201){
                         $('.short_msg').text('');
                         $(".short_link").after('<p class="short_msg" style="color:red">'+res.result.custom_url[0]+'</p>');
                    }
                },
                error: function(result){
                    alert("Error");
                }
            });
        });

        $('.btn_getlog').on('click',function(e){
            $('#table_log tbody').html('');
          //  console.log( $('#table_log tbody').html());
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: '<?php echo env('APP_URL');?>' + "/fgurl-get-urlshortenlog",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                success: function(result){
                     
                    $('#table_log').DataTable().destroy();
                    $('#table_log tbody').html('');
                    var res = $.parseJSON(result);
                  //  console.log(res);
                    $.each( res , function(idx, obj) {  
                    if( obj.is_mobile == 1 ){
                        $('#table_log tbody').append('<tr><td>'+ obj.clicks+ '</td><td>'+ 'http://fgurl.in/' + obj.short_url+ '</td><td>Mobile</td><td>'+ obj.redirect_url+ '</td></tr>'); 
                    }else{
                        $('#table_log tbody').append('<tr><td>'+ obj.clicks+ '</td><td>'+ 'http://fgurl.in/' +obj.short_url+ '</td><td>Desktop</td><td>'+ obj.redirect_url+ '</td></tr>'); 
                    }    
                      
                    });
                    $('#table_log').DataTable({
                    "aaSorting": [],
                    "pageLength": 25,
                    "lengthChange": true,
                    "lengthMenu": [[10, 100, 250, 500, -1], [10, 100, 250, 500, "All"]]
                    });
                },
                error: function(result){
                    console.log(result);
                    
                }
            });
        });

         function copyToClipboard() {
            var copyText = $('#hidden_url').val();
            var obj = $('#hidden_url');        
            obj.select();
            document.execCommand("copy");
            $('.copied-msg').show();
            setTimeout(function() { $(".copied-msg").hide('slow'); }, 2000);
           
        }
    </script>
@endsection
