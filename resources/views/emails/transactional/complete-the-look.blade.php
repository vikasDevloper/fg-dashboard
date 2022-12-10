@if(!empty($boughtProduct))
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
   <head>
      <!-- NAME: ANNOUNCE -->
      <!--[if gte mso 15]>
      <xml>
         <o:OfficeDocumentSettings>
            <o:AllowPNG/>
            <o:PixelsPerInch>96</o:PixelsPerInch>
         </o:OfficeDocumentSettings>
      </xml>
      <![endif]-->
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>{{ $subject }}</title>
      <style type="text/css">
         p{
         margin:10px 0;
         padding:0;
         }
         table{
         border-collapse:collapse;
         }
         h1,h2,h3,h4,h5,h6{
         display:block;
         margin:0;
         padding:0;
         }
         img,a img{
         border:0;
         height:auto;
         outline:none;
         text-decoration:none;
         }
         body,#bodyTable,#bodyCell{
         height:100%;
         margin:auto;
         padding:0;
         width: 400px;
         /*min-width:100%;
         max-width: 400px;*/
         }
         .mcnPreviewText{
         display:none !important;
         }
         #outlook a{
         padding:0;
         }
         img{
         -ms-interpolation-mode:bicubic;
         }
         table{
         mso-table-lspace:0pt;
         mso-table-rspace:0pt;
         }
         .ReadMsgBody{
         width:100%;
         }
         .ExternalClass{
         width:100%;
         }
         p,a,li,td,blockquote{
         mso-line-height-rule:exactly;
         }
         a[href^=tel],a[href^=sms]{
         color:inherit;
         cursor:default;
         text-decoration:none;
         }
         p,a,li,td,body,table,blockquote{
         -ms-text-size-adjust:100%;
         -webkit-text-size-adjust:100%;
         }
         .ExternalClass,.ExternalClass p,.ExternalClass td,.ExternalClass div,.ExternalClass span,.ExternalClass font{
         line-height:100%;
         }
         a[x-apple-data-detectors]{
         color:inherit !important;
         text-decoration:none !important;
         font-size:inherit !important;
         font-family:inherit !important;
         font-weight:inherit !important;
         line-height:inherit !important;
         }
         .templateContainer{
         max-width:400px !important;
         }
         a.mcnButton{
         display:block;
         }
         .mcnImage{
         vertical-align:bottom;
         }
         .mcnTextContent{
         word-break:break-word;
         }
         .mcnTextContent img{
         height:auto !important;
         }
         .mcnDividerBlock{
         table-layout:fixed !important;
         }
h1{
color:#222222;
font-family:Helvetica;
font-size:40px;
font-style:normal;
font-weight:bold;
line-height:150%;
letter-spacing:normal;
text-align:center;
         }

h2{
color:#222222;
font-family:Helvetica;
font-size:34px;
font-style:normal;
font-weight:bold;
line-height:150%;
letter-spacing:normal;
text-align:left;
         }

h3{
color:#444444;
font-family:Helvetica;
font-size:22px;
font-style:normal;
font-weight:bold;
line-height:150%;
letter-spacing:normal;
text-align:left;
         }

h4{
color:#999999;
font-family:Georgia;
font-size:20px;
font-style:italic;
font-weight:normal;
line-height:125%;
letter-spacing:normal;
text-align:center;
         }

#templateHeader{
background-color:#f9f8f6;
background-image:none;
background-repeat:no-repeat;
background-position:center;
background-size:cover;
border-top:0;
border-bottom:0;
padding-top:0px;
padding-bottom:0px;
         }

.headerContainer{
background-color:transparent;
background-image:none;
background-repeat:no-repeat;
background-position:center;
background-size:cover;
border-top:0;
border-bottom:0;
padding-top:0;
padding-bottom:0;
         }

.headerContainer .mcnTextContent,.headerContainer .mcnTextContent p{
color:#808080;
font-family:Helvetica;
font-size:16px;
line-height:150%;
text-align:left;
         }

.headerContainer .mcnTextContent a,.headerContainer .mcnTextContent p a{
   font-family: Helvetica;
color:#00ADD8;
font-weight:normal;
text-decoration:underline;
         }

#templateBody{
background-color:#ffffff;
background-image:none;
background-repeat:no-repeat;
background-position:center;
background-size:cover;
border-top:0;
border-bottom:0;
padding-top:0px;
padding-bottom:0px;
         }

.bodyContainer{
background-color:#transparent;
background-image:none;
background-repeat:no-repeat;
background-position:center;
background-size:cover;
border-top:0;
border-bottom:0;
padding-top:0;
padding-bottom:0;
         }

.bodyContainer .mcnTextContent,.bodyContainer .mcnTextContent p{
color:#808080;
font-family:Helvetica;
font-size:16px;
line-height:150%;
text-align:left;
         }

.bodyContainer .mcnTextContent a,.bodyContainer .mcnTextContent p a{
   font-family: Helvetica;
color:#808080;
font-weight:normal;
text-decoration:none;
         }
.ctabutton{
   background-color: #00c69b;
   padding: 7px 15px;
   text-decoration: none;
   border-radius: 5px;
   color: #fff;
}

#templateFooter{
background-color:#557EA0;
background-image:none;
background-repeat:no-repeat;
background-position:center;
background-size:cover;
border-top:0;
border-bottom:0;
padding-top:0px;
padding-bottom:0px;
         }

.footerContainer{
background-color:transparent;
background-image:none;
background-repeat:no-repeat;
background-position:center;
background-size:cover;
border-top:0;
border-bottom:0;
padding-top:0;
padding-bottom:0;
         }

.footerContainer .mcnTextContent,.footerContainer .mcnTextContent p{
color:#FFFFFF;
font-family:Helvetica;
font-size:12px;
line-height:150%;
text-align:center;
         }

.footerContainer .mcnTextContent a,.footerContainer .mcnTextContent p a{
font-family: Helvetica;
color:#FFFFFF;
font-weight:normal;
text-decoration:underline;
         }
         @media only screen and (min-width:768px){
         .templateContainer{
         width:400px !important;
         }
         }  @media only screen and (max-width: 480px){
         body,table,td,p,a,li,blockquote{
         -webkit-text-size-adjust:none !important;
         }
         body,#bodyTable,#bodyCell{
            width: 100%;
         }
         #templateBody{
background-color:#f9f8f6;
         }
         }  @media only screen and (max-width: 480px){
         body{
         width:100% !important;
         min-width:100% !important;
         }
         }  @media only screen and (max-width: 480px){
         .mcnImage{
         width:100% !important;
         }
         }  @media only screen and (max-width: 480px){

}  @media only screen and (max-width: 480px){

}  @media only screen and (max-width: 480px){
   .mcnImageCardTopImageContent,.mcnCaptionBlockInner .mcnCaptionTopContent:last-child .mcnTextContent{
   padding-top:18px !important;
   }
}  @media only screen and (max-width: 480px){
   .mcnImageCardBottomImageContent{
   padding-bottom:9px !important;
   }
}  @media only screen and (max-width: 480px){
   .mcnImageGroupBlockInner{
   padding-top:0 !important;
   padding-bottom:0 !important;
   }
}  @media only screen and (max-width: 480px){
   .mcnImageGroupBlockOuter{
   padding-top:9px !important;
   padding-bottom:9px !important;
   }
}  @media only screen and (max-width: 480px){
   .mcnTextContent,.mcnBoxedTextContentColumn{
   padding-right:18px !important;
   padding-left:18px !important;
   }
}  @media only screen and (max-width: 480px){
   .mcnImageCardLeftImageContent,.mcnImageCardRightImageContent{
   padding-right:18px !important;
   padding-bottom:0 !important;
   padding-left:18px !important;
   }
}  @media only screen and (max-width: 480px){
   .mcpreview-image-uploader{
   display:none !important;
   width:100% !important;
   }
}  @media only screen and (max-width: 480px){

h1{
font-size:30px !important;
line-height:125% !important;
         }
         }  @media only screen and (max-width: 480px){

h2{
font-size:26px !important;
line-height:125% !important;
         }
         }  @media only screen and (max-width: 480px){

h3{
font-size:20px !important;
line-height:150% !important;
         }
         }  @media only screen and (max-width: 480px){

h4{
font-size:18px !important;
line-height:150% !important;
         }
         }  @media only screen and (max-width: 480px){

.mcnBoxedTextContentContainer .mcnTextContent,.mcnBoxedTextContentContainer .mcnTextContent p{
font-size:14px !important;
line-height:150% !important;
         }
         }  @media only screen and (max-width: 480px){

.headerContainer .mcnTextContent,.headerContainer .mcnTextContent p{
font-size:16px !important;
line-height:150% !important;
         }
         }  @media only screen and (max-width: 480px){

.bodyContainer .mcnTextContent,.bodyContainer .mcnTextContent p{
font-size:16px !important;
line-height:150% !important;
         }
         }  @media only screen and (max-width: 480px){

.footerContainer .mcnTextContent,.footerContainer .mcnTextContent p{
font-size:14px !important;
line-height:150% !important;
         }
         }
      </style>
   </head>
   <body>

@if(!empty($mcPreviewText))
   <span class="mcnPreviewText" style="display:none; font-size:0px; line-height:0px; max-height:0px; max-width:0px; opacity:0; overflow:hidden; visibility:hidden; mso-hide:all;">{{ $mcPreviewText }}
   </span>
@endif
<center>
         <table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
            <tr>
               <td align="center" valign="top" id="bodyCell">
                  <!-- BEGIN TEMPLATE // -->
                  <table border="0" cellpadding="0" cellspacing="0" width="100%">
                     {{-- <tr>
                        <td align="center" valign="top" id="templateHeader" data-template-container>
                           <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" class="templateContainer">
                              <tr>
                                 <td valign="top" class="headerContainer" style="background-color: #ffffff;">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnImageBlock" style="min-width:100%;">
                                       <tbody class="mcnImageBlockOuter">
                                          <tr>
                                             <td valign="top" class="mcnImageBlockInner">
                                                <table align="left" width="100%" border="0" cellpadding="0" cellspacing="0" class="mcnImageContentContainer" style="min-width:100%;">
                                                   <tbody>
                                                      <tr>
                                                         <td class="mcnImageContent" valign="top" style="padding-top: 5px; padding-bottom: 5px; text-align:left;">
                                                            <p>Hi {{ $firstname }},</p>
                                                         </td>
                                                      </tr>
                                                   </tbody>
                                                </table>
                                             </td>
                                          </tr>
                                       </tbody>
                                    </table>
                                 </td>
                              </tr>
                           </table>
                        </td>
                     </tr> --}}
                     <tr>
                        <td align="center" valign="top" data-template-container>
                           <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" class="templateContainer">
                              <tr>
                                 <td valign="top" class="headerContainer">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnImageBlock" style="min-width:100%;">
                                       <tbody class="mcnImageBlockOuter">
                                          <tr>
                                             <td valign="top" class="mcnImageBlockInner">
                                                <table align="left" width="100%" border="0" cellpadding="0" cellspacing="0" class="mcnImageContentContainer" style="min-width:100%;">
                                                   <tbody>
                                                      <tr>
                                                         <td class="mcnImageContent" valign="top" style="padding-top: 0; padding-bottom: 0; text-align:center;">
                                                            <a href="{{$homePageUrl}}" target="_blank"><img align="center" alt="" src="https://www.faridagupta.com/media/wysiwyg/exhibitions-mail/mailer_01.jpg" class="logo" width="400" style="max-width:400px; padding-bottom: 0; display: inline !important; vertical-align: bottom;" class="mcnImage"></a>
                                                         </td>
                                                      </tr>
                                                   </tbody>
                                                </table>
                                             </td>
                                          </tr>
                                       </tbody>
                                    </table>
                                 </td>
                              </tr>
                           </table>
                        </td>
                     </tr>
                     <tr>
                        <td align="center" valign="top" id="templateBody" data-template-container>
                           <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" class="templateContainer" style="background-color: #f9f8f5;">
                              <tr>
                                 <td valign="top" class="bodyContainer">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnImageBlock" style="min-width:100%;">
                                       <tbody class="mcnImageBlockOuter">
                                          <tr>
                                             <td valign="top" class="mcnImageBlockInner">
                                                <table align="left" width="100%" border="0" cellpadding="0" cellspacing="0" class="mcnImageContentContainer" style="min-width:100%;">
                                                   <tbody>
                                                      <tr>
                                                         <td class="mcnImageContent" valign="top" style="padding-top: 0px; padding-bottom: 0px; text-align:center;">
                                                            <a href="{{$homePageUrl}}" target="_blank"><img align="center" alt="" src="https://www.faridagupta.com/media/wysiwyg/completethelook.jpg" class="exhbin" width="400" style="max-width:400px; padding-bottom: 0; display: inline !important; vertical-align: bottom;" class="mcnImage"></a>
                                                         </td>
                                                      </tr>
                                                   </tbody>
                                                </table>
                                             </td>
                                          </tr>
                                       </tbody>
                                    </table>

                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnImageBlock" style="min-width:100%;">
                                       <tbody class="mcnImageBlockOuter">
                                          <tr>
                                             <td valign="top" class="mcnImageBlockInner">
                                                <table align="left" width="100%" border="0" cellpadding="0" cellspacing="0" class="mcnImageContentContainer" style="min-width:100%;">
                                                   <tbody>
                                                      <tr>
                                                         <td class="mcnImageContent" valign="top" style="padding-top: 0px; padding-bottom: 10px; text-align:center; color: #585858;">
                                                            {{-- @php ($kurtaWord = 'kurtas')
                                                            @if($boughtProductCount == 1)
                                                               @php ($kurtaWord = 'kurta')
                                                            @endif --}}
                                                            <p>Hi {{ $firstname }},<br><br>Great choice with your recent purchase! Here are the matching products that'll give it the FG look.</p>
                                                         </td>
                                                      </tr>
                                                   </tbody>
                                                </table>
                                             </td>
                                          </tr>
                                       </tbody>
                                    </table>

                                    @foreach($boughtProduct as $bought)
                                       @if(empty($shopthelook[$bought['productName']]))
                                          @continue;

                                       {{-- <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnImageBlock" style="min-width:100%;">
                                          <tbody class="mcnImageBlockOuter">
                                             <tr>
                                                <td valign="top" class="mcnImageBlockInner">
                                                   <table align="left" width="100%" border="0" cellpadding="0" cellspacing="0" class="mcnImageContentContainer" style="min-width:100%;">
                                                      <tbody>
                                                         <tr>
                                                            <td class="mcnImageContent" valign="top" style="padding-top: 10px; padding-bottom: 10px; text-align:center;">
                                                               <a href="{{$bought['productUrl']}}" target="_blank"><img align="center" alt="" src="{{$bought['productImageUrl']}}" class="exhbin" width="380" style="max-width:380px; padding-bottom: 0; display: inline !important; vertical-align: bottom;" class="mcnImage"></a>
                                                            </td>
                                                         </tr>
                                                         <tr>
                                                            <td class="mcnImageContent" valign="top" style="padding-top: 10px; padding-bottom: 10px; text-align:center;">
                                                               <a href="{{$bought['productUrl']}}" target="_blank">{{$bought['productName']}}</a>
                                                            </td>
                                                         </tr>
                                                      </tbody>
                                                   </table>
                                                </td>
                                             </tr>
                                          </tbody>
                                       </table> --}}
                                       @else
                                       <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnImageBlock" style="min-width:100%;">
                                          <tbody class="mcnImageBlockOuter">
                                             <tr>
                                                <td valign="top" class="mcnImageBlockInner">
                                                   <table align="left" width="30%" border="0" cellpadding="0" cellspacing="0" class="mcnImageGroupContentContainer">
                                                      <tbody>
                                                         <tr>
                                                            <td class="mcnImageGroupContent" valign="top" style="padding-top: 10px; padding-bottom: 10px; text-align:center;">
                                                               <a href="{{$bought['productUrl']}}" target="_blank"><img alt="" src="{{$bought['productImageUrl']}}" width="100" style="max-width:100px; padding-bottom: 0;" class="mcnImage"></a>
                                                            </td>
                                                         </tr>
                                                      </tbody>
                                                   </table>
                                                   <table align="left" width="70%" border="0" cellpadding="0" cellspacing="0" class="mcnImageGroupContentContainer">
                                                      <tbody>
                                                         <tr>
                                                            <td class="mcnImageContent" valign="top" style="padding-top: 10px; padding-bottom: 10px; text-align:center; height: 140px; vertical-align: middle;">
                                                               <a href="{{$bought['productUrl']}}" target="_blank" style="font-size: 25px; line-height: 37px; text-decoration: none; color: #585858;">{{$bought['productName']}}</a>
                                                            </td>
                                                         </tr>
                                                      </tbody>
                                                   </table>
                                                </td>
                                             </tr>
                                          </tbody>
                                       </table>
                                       <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnImageGroupBlock">
                                          <tbody class="mcnImageGroupBlockOuter">
                                             <tr>
                                                <td valign="top" class="mcnImageGroupBlockInner">
                                                   {{-- <table align="left" width="100%" border="0" cellpadding="0" cellspacing="0" class="mcnImageContentContainer" style="min-width:100%;">
                                                      <tbody>
                                                         <tr>
                                                            <td class="mcnImageContent" valign="top" style="padding-top: 0px; padding-bottom: 0px; text-align:center;">
                                                               <p style="color: #a80d77; font-size: 18px; line-height: 24px;">Complete the Look</p>
                                                            </td>
                                                         </tr>
                                                      </tbody>
                                                   </table> --}}
                                                   @php ($tableWidth = '50%')
                                                   @php ($imageWidth = '180px')

                                                   @if(count($shopthelook[$bought['productName']]) == 1)
                                                      @php ($tableWidth = '100%')
                                                      @php ($imageWidth = '380px')
                                                   @endif

                                                   @foreach($shopthelook[$bought['productName']] as $matchings)
                                                      <table align="left" class="{{count($matchings)}}" width="{{$tableWidth}}" border="0" cellpadding="0" cellspacing="0" class="mcnImageGroupContentContainer">
                                                         <tbody>
                                                            <tr>
                                                               <td class="mcnImageGroupContent" valign="top" style="padding-top: 10px; padding-bottom: 10px; text-align:center;">
                                                                  <a href="{{$matchings['productUrl']}}" target="_blank"><img alt="" src="{{$matchings['productImageUrl']}}" width="{{$imageWidth}}" style="max-width:{{$imageWidth}}; padding-bottom: 0;" class="mcnImage"></a>
                                                               </td>
                                                            </tr>
                                                            <tr>
                                                               <td class="mcnImageContent" valign="top" style="padding-top: 10px; padding-bottom: 0px; text-align:center;">
                                                                  <a href="{{$matchings['productUrl']}}" style=" text-decoration: none; color: #000;" target="_blank">{{$matchings['productName']}}</a>
                                                                  <br>
                                                                  <p>&#8377; {{$matchings['productPrice']}}</p>
                                                               </td>
                                                            </tr>
                                                            <tr>
                                                               <td class="mcnImageContent" valign="top" style="padding-top: 10px; padding-bottom: 20px; text-align:center;">
                                                                  <a href="{{$matchings['productUrl']}}" class="ctabutton" target="_blank">Buy Now</a>
                                                               </td>
                                                            </tr>
                                                         </tbody>
                                                      </table>
                                                   @endforeach
                                                </td>
                                             </tr>
                                          </tbody>
                                       </table>
                                       @endif
                                    @endforeach
                                 </td>
                              </tr>
                           </table>
                        </td>
                     </tr>
                     <tr>
                        <td align="center" valign="top" id="templateFooter" data-template-container>
                           @include('emails.transactional.email-footer')
                        </td>
                     </tr>
                  </table>
                  <!-- // END TEMPLATE -->
               </td>
            </tr>
         </table>
      </center>
   </body>
</html>
@endif