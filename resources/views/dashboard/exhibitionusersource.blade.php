<!DOCTYPE html>
<html lang="en">
<head>
<title>Exhibition Source</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

<style type="text/css">

@font-face{font-family:"pluto";font-style:normal;font-weight:900;src:url('{{ public_path('fonts/PlutoSansBlack.eot')}}') format("eot"),url('{{ public_path('fonts/PlutoSansBlack.otf')}}') format("opentype"),url('{{ public_path('fonts/PlutoSansBlack.woff')}}') format("woff"),url('{{ public_path('fonts/PlutoSansBlack.ttf')}}') format("truetype"),url('{{ public_path('fonts/PlutoSansBlack.svg')}}') format("svg")}
@font-face{font-family:"pluto";font-style:normal;font-weight:700;src:url('{{ public_path('fonts/PlutoSansBold.eot')}}') format("eot"),url('{{ public_path('fonts/PlutoSansBold.otf')}}') format("opentype"),url('{{ public_path('fonts/PlutoSansBold.woff')}}') format("woff"),url('{{ public_path('fonts/PlutoSansBold.ttf')}}') format("truetype"),url('{{ public_path('fonts/PlutoSansBold.svg')}}') format("svg")}
@font-face{font-family:"pluto";font-style:normal;font-weight:600;src:url('{{ public_path('fonts/PlutoSansHeavy.eot')}}') format("eot"),url('{{ public_path('fonts/PlutoSansHeavy.otf')}}') format("opentype"),url('{{ public_path('fonts/PlutoSansHeavy.woff')}}') format("woff"),url('{{ public_path('fonts/PlutoSansHeavy.ttf')}}') format("truetype"),url(//) format("svg")}
@font-face{font-family:"pluto";font-style:normal;font-weight:400;src:url({{ public_path('fonts/PlutoSansRegular.eot')}}') format("eot"),url('{{ public_path('fonts/PlutoSansRegular.otf')}}') format("opentype"),url({{ public_path('fonts/PlutoSansRegular.woff')}}') format("woff"),url('{{ public_path('fonts/PlutoSansRegular.ttf')}}') format("truetype"),url('{{ public_path('fonts/PlutoSansRegular.svg')}}') format("svg")}
@font-face{font-family:"pluto";font-style:normal;font-weight:300;src:url('{{ public_path('fonts/PlutoSansLight.eot')}}') format("eot"),url('{{ public_path('fonts/PlutoSansLight.otf')}}') format("opentype"),url('{{ public_path('fonts/PlutoSansLight.woff')}}') format("woff"),url('{{ public_path('/css/fonts/PlutoSansLight.ttf')}}') format("truetype"),url('{{ public_path('fonts/PlutoSansLight.svg')}}') format("svg")}
@font-face{font-family:"pluto";font-style:normal;font-weight:100;src:url('{{ public_path('fonts/PlutoSansExtraLight.eot')}}') format("eot"),url('{{ public_path('fonts/PlutoSansExtraLight.otf')}}') format("opentype"),url('{{ public_path('fonts/PlutoSansExtraLight.woff')}}') format("woff"),url('{{ public_path('fonts/PlutoSansExtraLight.ttf')}}') format("truetype"),url('{{ public_path('fonts/PlutoSansExtraLight.svg')}}') format("svg")}

@font-face {

    src:url('{{ public_path('fonts/Harman.otf')}}');
    font-weight: 300;
    font-style: normal
}
body, table, th, td , h2,label, p, div, span, h3 {
   /*border: 1px solid black;*/
   color: #6389a8;
   /*font-weight: normal;*/
  font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
  font-style:normal;
  font-weight:400;

}

h3.thankyou {
    font-family: 'harman';
    font-weight: normal;
    font-size: 35px;
    margin-bottom: 20px;
    line-height: 28px;
    text-align: center;
    color: #a80d77;
}

.checkbox label, .radio label {
  padding-left: 50px;
  font-size: 20px;
}
.btn-success {
  background-color: #20bd99;
  border-color: #20bd99;
  margin-top: 30px;
}

.btn-success:hover{
  background-color: #20bd99;
  border-color: #20bd99;
}

.btn-success:active{
    background-color: #20bd99;
    border-color: #20bd99;
 }


.radio {
  margin-left: 110px;
}
.radio input[type=radio], .radio-inline input[type=radio]{
  position: relative !important;
}
.option-input {
  -webkit-appearance: none;
  -moz-appearance: none;
  -ms-appearance: none;
  -o-appearance: none;
  appearance: none;
  position: relative;
  top: 13.33333px;
  height: 35px;
  width: 35px;
  transition: all 0.15s ease-out 0s;
  border: 1px #000 solid;
  color: #fff;
  cursor: pointer;
  display: inline-block;
  margin-right: 0.5rem;
  outline: none;
  position: relative;
  z-index: 1000;
}
.option-input:hover {
  background: #fff;
}
.option-input:checked {
  color: #000;
}

.option-input:checked:before {
  height: 35px;
  width: 35px;
  position: absolute;
  content: '✔';
  display: inline-block;
  font-size: 24px;
  text-align: center;
  line-height: 35px;
}

input[type=radio]{
  margin: 12px 10px 0px;
  background-color: #fff;
}
</style>



</head>
<body>

<div class="container" style="background-color: #41698c; width:100%;">
  <div class="col-md-12 col-sm-12 text-center">
    <img style="width:50%; padding:10px 0px;" src="{{asset('logo_white.png')}}">
  </div>
</div>
<?php
//echo '<pre>';
//print_r($data['exhibitionsource']);
?>
<div class="container">
    <div class="row">
      <form name="form" method="POST" class="save" action="/save-source" >
        <h2 class="text-center">HOW DID YOU GET TO KNOW ABOUT THE EXHIBITION?</h2>
        <input type="hidden" name="_token" value="<?php echo csrf_token();?>">
        <input type="hidden" name="exhibitions_id" value="{{ !empty($data["exhibitionId"]["exhibitions_id"]) ? $data["exhibitionId"]["exhibitions_id"] : ''}}">
        @foreach($data['exhibitionsource'] as $source)
                <div class="radio">
                  <label><input type="radio" name="source" class="option-input radio" value="{{trim($source['id'])}}" >{{trim($source['source_name'])}}</label>
                </div>


              @endforeach


        <div class="row">
          <div class="col-sm-offset-3 col-md-offset-3 col-md-6 col-sm-6 margin-top-10">
            <input type="submit" id="submit" class="btn btn-lg btn-block btn-success center" name="Send" value="SUBMIT">
          </div>
        </div>
      </form>

      <div class="thanksection" style="display: none;">
        <h3 class="thankyou">Thank you</h3>
      </div>
    </div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>

  <script type="text/javascript">
$("#submit").css('background-color', '#20bd99', 'border-color', '#20bd99');
$("#submit").click(function() {
    
        $("#submit").css('background-color', '#20bd99', 'border-color', '#20bd99');
      if ($('input[name="source"]:checked').length == 0) {
         alert('Please Select one option');
         return false; 
      }else {
        $("#submit").hide();
        return true;
      }
       // $( "#submit" ).submit();
       // if ($('input[name="source"]:checked').length == 0) {
       //   alert('Please Select one option');
       //   return false; 
       // } else {
        
       //      $("#submit").hide();
       //      var exhibition_id = $('input[name=exhibitions_id]').val();
       //      var source = $('input[name=source]:checked').val();
       //      var token = $('input[name=_token]').val();
         
       //    $.ajax({
       //        type: "POST",
       //        url:'save-source?' + <?php echo time(); ?>,
       //        cache: false,
       //        async: false,
       //        headers: {
       //            'X-CSRF-TOKEN': '{{ csrf_token() }}',
       //            'cache-control': 'no-cache'
       //        },
       //        data:{
       //            'exhibition_id':exhibition_id,
       //            'source':source,
       //            '_token':token
       //        },
         
       //        success:function(s) {

       //          $(".save").hide();
       //          $(".thanksection").show();

       //          setTimeout(function(){ 
       //          $("#submit").show();
       //          $(".save").show();
       //          $(".thanksection").hide();
       //          location.reload();
       //          //$('.save').trigger("reset");

       //          }, '3000');

       //        }
       //   });

       // }
       
    }); 
  </script>


</body>
</html>