<!DOCTYPE html>
<html lang="en">
<head>
<title>Exhibition Source</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

<style type="text/css">

@font-face{

  src:url('{{ public_path('fonts/PlutoSansRegular.eot') }}') format("eot"),
  url('{{ public_path('fonts/PlutoSansRegular.otf') }}') format("opentype"),
  url('{{ public_path('fonts/PlutoSansRegular.woff')}}') format("woff"),
  url('{{ public_path('fonts/PlutoSansRegular.ttf')}}') format("truetype"),
  url('{{ public_path('fonts/PlutoSansRegular.svg')}}') format("svg")

}
@font-face {
  
    src:url('{{ public_path('fonts/Harman.otf')}}');
    font-weight: 300;
    font-style: normal
}
 table, th, td , h2,label {
   /*border: 1px solid black;*/
   color: #6389a8;
   /*font-weight: normal;*/
  font-family:"pluto";
  font-style:normal;
  font-weight:400;
  
}

h3.thankyou {
    font-family: 'harman';
    font-weight: normal;
    font-size: 25px;
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
      
      <div class="thanksection">
        <h3 class="thankyou">Thank you</h3>
      </div>
    </div>   
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
<script type="text/javascript">
//   $('.clickme').click(function() {
//    if(confirm("Are you sure you want to navigate away from this page?"))
//    {
//       history.go(-1);
//    }        
//    return false;
// });
  setTimeout(function(){ 
            window.location.replace("/exhibition-source");
            }, '2000');
 
</script>

</body>
</html>