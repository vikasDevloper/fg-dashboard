	<a href="{{ route('generate-pdf',['download'=>'pdf']) }}">Download PDF</a>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><script type="text/javascript" src="https://bam.nr-data.net/1/eeff37e7dc?a=49160072&amp;v=1130.54e767a&amp;to=NV0GMkQAWkACBUdQWgwXJQVCCFtdTABUWFEPUQpJXhVZXwoIRVZcAV07FkQIWkdMD11PWgtbAQ%3D%3D&amp;rst=638&amp;ref=https://fgadmin.faridagupta.com/index.php/fgadmin/htmlinvoice_print/invoice/order_id/83766/key/3535d0e9bdbbf32811f3db27f9198342/&amp;ap=338&amp;be=516&amp;fe=629&amp;dc=627&amp;perf=%7B%22timing%22:%7B%22of%22:1563533339782,%22n%22:0,%22f%22:18,%22dn%22:18,%22dne%22:18,%22c%22:18,%22ce%22:18,%22rq%22:25,%22rp%22:458,%22rpe%22:510,%22dl%22:466,%22di%22:623,%22ds%22:623,%22de%22:624,%22dc%22:625,%22l%22:625,%22le%22:627%7D,%22navigation%22:%7B%7D%7D&amp;at=GRoFRAwaSU4%3D&amp;jsonp=NREUM.setToken"></script><script src="https://js-agent.newrelic.com/nr-1130.min.js"></script><script type="text/javascript">window.NREUM||(NREUM={}),__nr_require=function(e,n,t){function r(t){if(!n[t]){var o=n[t]={exports:{}};e[t][0].call(o.exports,function(n){var o=e[t][1][n];return r(o||n)},o,o.exports)}return n[t].exports}if("function"==typeof __nr_require)return __nr_require;for(var o=0;o<t.length;o++)r(t[o]);return r}({1:[function(e,n,t){function r(){}function o(e,n,t){return function(){return i(e,[c.now()].concat(u(arguments)),n?null:this,t),n?void 0:this}}var i=e("handle"),a=e(3),u=e(4),f=e("ee").get("tracer"),c=e("loader"),s=NREUM;"undefined"==typeof window.newrelic&&(newrelic=s);var p=["setPageViewName","setCustomAttribute","setErrorHandler","finished","addToTrace","inlineHit","addRelease"],d="api-",l=d+"ixn-";a(p,function(e,n){s[n]=o(d+n,!0,"api")}),s.addPageAction=o(d+"addPageAction",!0),s.setCurrentRouteName=o(d+"routeName",!0),n.exports=newrelic,s.interaction=function(){return(new r).get()};var m=r.prototype={createTracer:function(e,n){var t={},r=this,o="function"==typeof n;return i(l+"tracer",[c.now(),e,t],r),function(){if(f.emit((o?"":"no-")+"fn-start",[c.now(),r,o],t),o)try{return n.apply(this,arguments)}catch(e){throw f.emit("fn-err",[arguments,this,e],t),e}finally{f.emit("fn-end",[c.now()],t)}}}};a("actionText,setName,setAttribute,save,ignore,onEnd,getContext,end,get".split(","),function(e,n){m[n]=o(l+n)}),newrelic.noticeError=function(e,n){"string"==typeof e&&(e=new Error(e)),i("err",[e,c.now(),!1,n])}},{}],2:[function(e,n,t){function r(e,n){if(!o)return!1;if(e!==o)return!1;if(!n)return!0;if(!i)return!1;for(var t=i.split("."),r=n.split("."),a=0;a<r.length;a++)if(r[a]!==t[a])return!1;return!0}var o=null,i=null,a=/Version\/(\S+)\s+Safari/;if(navigator.userAgent){var u=navigator.userAgent,f=u.match(a);f&&u.indexOf("Chrome")===-1&&u.indexOf("Chromium")===-1&&(o="Safari",i=f[1])}n.exports={agent:o,version:i,match:r}},{}],3:[function(e,n,t){function r(e,n){var t=[],r="",i=0;for(r in e)o.call(e,r)&&(t[i]=n(r,e[r]),i+=1);return t}var o=Object.prototype.hasOwnProperty;n.exports=r},{}],4:[function(e,n,t){function r(e,n,t){n||(n=0),"undefined"==typeof t&&(t=e?e.length:0);for(var r=-1,o=t-n||0,i=Array(o<0?0:o);++r<o;)i[r]=e[n+r];return i}n.exports=r},{}],5:[function(e,n,t){n.exports={exists:"undefined"!=typeof window.performance&&window.performance.timing&&"undefined"!=typeof window.performance.timing.navigationStart}},{}],ee:[function(e,n,t){function r(){}function o(e){function n(e){return e&&e instanceof r?e:e?f(e,u,i):i()}function t(t,r,o,i){if(!d.aborted||i){e&&e(t,r,o);for(var a=n(o),u=v(t),f=u.length,c=0;c<f;c++)u[c].apply(a,r);var p=s[y[t]];return p&&p.push([b,t,r,a]),a}}function l(e,n){h[e]=v(e).concat(n)}function m(e,n){var t=h[e];if(t)for(var r=0;r<t.length;r++)t[r]===n&&t.splice(r,1)}function v(e){return h[e]||[]}function g(e){return p[e]=p[e]||o(t)}function w(e,n){c(e,function(e,t){n=n||"feature",y[t]=n,n in s||(s[n]=[])})}var h={},y={},b={on:l,addEventListener:l,removeEventListener:m,emit:t,get:g,listeners:v,context:n,buffer:w,abort:a,aborted:!1};return b}function i(){return new r}function a(){(s.api||s.feature)&&(d.aborted=!0,s=d.backlog={})}var u="nr@context",f=e("gos"),c=e(3),s={},p={},d=n.exports=o();d.backlog=s},{}],gos:[function(e,n,t){function r(e,n,t){if(o.call(e,n))return e[n];var r=t();if(Object.defineProperty&&Object.keys)try{return Object.defineProperty(e,n,{value:r,writable:!0,enumerable:!1}),r}catch(i){}return e[n]=r,r}var o=Object.prototype.hasOwnProperty;n.exports=r},{}],handle:[function(e,n,t){function r(e,n,t,r){o.buffer([e],r),o.emit(e,n,t)}var o=e("ee").get("handle");n.exports=r,r.ee=o},{}],id:[function(e,n,t){function r(e){var n=typeof e;return!e||"object"!==n&&"function"!==n?-1:e===window?0:a(e,i,function(){return o++})}var o=1,i="nr@id",a=e("gos");n.exports=r},{}],loader:[function(e,n,t){function r(){if(!E++){var e=x.info=NREUM.info,n=l.getElementsByTagName("script")[0];if(setTimeout(s.abort,3e4),!(e&&e.licenseKey&&e.applicationID&&n))return s.abort();c(y,function(n,t){e[n]||(e[n]=t)}),f("mark",["onload",a()+x.offset],null,"api");var t=l.createElement("script");t.src="https://"+e.agent,n.parentNode.insertBefore(t,n)}}function o(){"complete"===l.readyState&&i()}function i(){f("mark",["domContent",a()+x.offset],null,"api")}function a(){return O.exists&&performance.now?Math.round(performance.now()):(u=Math.max((new Date).getTime(),u))-x.offset}var u=(new Date).getTime(),f=e("handle"),c=e(3),s=e("ee"),p=e(2),d=window,l=d.document,m="addEventListener",v="attachEvent",g=d.XMLHttpRequest,w=g&&g.prototype;NREUM.o={ST:setTimeout,SI:d.setImmediate,CT:clearTimeout,XHR:g,REQ:d.Request,EV:d.Event,PR:d.Promise,MO:d.MutationObserver};var h=""+location,y={beacon:"bam.nr-data.net",errorBeacon:"bam.nr-data.net",agent:"js-agent.newrelic.com/nr-1130.min.js"},b=g&&w&&w[m]&&!/CriOS/.test(navigator.userAgent),x=n.exports={offset:u,now:a,origin:h,features:{},xhrWrappable:b,userAgent:p};e(1),l[m]?(l[m]("DOMContentLoaded",i,!1),d[m]("load",r,!1)):(l[v]("onreadystatechange",o),d[v]("onload",r)),f("mark",["firstbyte",u],null,"api");var E=0,O=e(5)},{}]},{},["loader"]);</script>
    <style type="text/css">

        @media screen { #printFooter { display: none; } }
        @media print { #printFooter { position: fixed; bottom: 0; } }
        a.addr_link { color: #000; text-decoration: none; }
        .option-menu { padding: 8px; background: #eee; font-size: 10px; }
        div.watermark div { position: absolute; left: 0; width: 99%; }
        .center { text-align: center; }
        div.top div { top: 0; }

        .button {
            -moz-box-shadow: inset 0px 1px 0px 0px #fce2c1;
            -webkit-box-shadow: inset 0px 1px 0px 0px #fce2c1;
            box-shadow: inset 0px 1px 0px 0px #fce2c1;
            background: -webkit-gradient( linear, left top, left bottom, color-stop(0.05, #ffc477), color-stop(1, #fb9e25));
            background: -moz-linear-gradient( center top, #ffc477 5%, #fb9e25 100%);
            filter: progid: DXImageTransform.Microsoft.gradient(startColorstr='#ffc477', endColorstr='#fb9e25');
            background-color: #ffc477;
            -moz-border-radius: 6px;
            -webkit-border-radius: 6px;
            border-radius: 6px;
            border: 1px solid #eeb44f;
            display: inline-block;
            color: #ffffff;
            font-family: arial;
            font-size: 12px;
            font-weight: bold;
            padding: 6px 24px;
            text-decoration: none;
            text-shadow: 1px 1px 0px #cc9f52;
        }
       
        .dataTableHeadingRow { background: none; }
        .seller { background: #e1e1e1 none repeat scroll 0 0; padding: 4px 5px; }
        #footer {border-top: 2px solid;color: #666;margin: 60px 0 0;padding: 10px 0;text-transform: capitalize;text-align: left;}
        #footer strong { color: #000; }
        .gray-bg { background: #e1e1e1 none repeat scroll 0 0; }
        div.watermark { display: block; position: fixed; z-index: 100; width: 100%; height: 100%; }
        div.content > *:first-child, x:-moz-any-link { margin-top: 0; }
        @media all and (min-width: 0px) { div.watermark { width: 8.5in; } }
        div.right { text-align: right; }
        body:last-child:not(:root:root) div.right div { left: -160px; }
        .clearfix { display: inline-block; }
        .sel-panel td { padding: 9px; }
        #invoice12 h1 { text-align: center; font-size: 12px; }
        .sel-buy-hed { background: #e1e1e1 none repeat scroll 0 0; float: left; width: 100%; border-top: 2px solid #515151; }
        .bold-one.store-name { font-size: 12px; font-weight: 100; }
        body { font-family: Arial, Helvetica, sans-serif; margin: 0; padding: 0; font-size: 9px; text-transform: uppercase; }
        .dataTableContent { border-bottom: 1px solid #7e7e7d; border-right: 1px solid #7e7e7d; padding: 5px 3px; }
        .alt TD { background: #eee; }
        @media screen { #printFooter { display: none; } }
        @media print { #printFooter { position: fixed; bottom: 0; } }
        #notes { border: 1px solid #999; width: 530px; height: 80px; padding: 5px; margin: 0 0 0 20px; }
        div.content > *:first-child, x:-moz-any-link { margin-top: 0; }
        div.left { text-align: left; }
        div.right {text-align: right; }
        body:last-child:not(:root:root) div.right div { left: -160px; }
       
        .button:hover {
            background: -webkit-gradient( linear, left top, left bottom, color-stop(0.05, #fb9e25), color-stop(1, #ffc477));
            background: -moz-linear-gradient( center top, #fb9e25 5%, #ffc477 100%);
            filter: progid: DXImageTransform.Microsoft.gradient(startColorstr='#fb9e25', endColorstr='#ffc477');
            background-color: #fb9e25;
        }
        .button:active { position: relative; top: 1px;}
        .bold-one { font-weight: bold; }
        .dataTableHeadingContent { border-bottom: 2px solid #999; color: #000; font-size: 9px; font-weight: bold; padding: 5px 3px; background: #e1e1e1 none repeat scroll 0 0; border-top: 1px solid #000; border-right: 1px solid #000; text-align: center; }
        .dataTableHeadingContent:last-child{ border-right: 0px; }
        .buyer { background: #e1e1e1 none repeat scroll 0 0; padding: 4px 5px; }
        .dataTableRow { background: none; }      
        .dataTableContent:last-child{ border-right: 0px; }       
        #notes TEXTAREA { border: none; width: 470px; height: 60px; overflow: hidden; margin: 5px 0 0 0; border: 0; }
        #footer strong { color: #000; }
        .gray-bg { background: #e1e1e1 none repeat scroll 0 0; }
        .totals .label { padding: 5px 20px 5px 0; }
        .totals .total td { font-size: 10px; border-top: 1px solid #ccc; }
        div.content > *:first-child, x:-moz-any-link { margin-top: 0; }
        div.watermark, x:-moz-any-link { z-index: auto; }
        div.watermark, x:-moz-any-link, x:default { z-index: 100; }
        div.middle div { top: 50%; margin-top: -210px; }
        div.bottom div { bottom: 2px; }
        .clearfix:after { content: "."; display: block; height: 0; clear: both; visibility: hidden; }
        .wrapper { text-align: center; background: #e1e1e1 none repeat scroll 0 0; border-bottom: 5px solid #515151; border-top: 2px solid #515151; font-family: serif; margin: 20px 0px; }
        .half { width: 50%; float: left; padding-left: 8px; }
        .border-tt td { border-bottom: 2px solid #515151; border-top: 2px solid; padding: 4px 0; }       
        #invoice12 {background: #e1e1e1 none repeat scroll 0 0; border-bottom: 3px solid #515151; border-top: 3px solid;width: 100%; }
        .seller-and-buyer td { padding: 1px 6px; width: 50%; font-family: serif; font-size: 10px; font-weight: 100; line-height: 14px; margin: 7px 0; }
        .seller-and-buyer p { font-family: serif; font-size: 8px; font-weight: 100; line-height: 14px; margin: 7px 0; }
        .seller-and-buyer td:first-child { border-right: 2px solid #515151; }       
        .sel-buy-hed h2 { float: left; margin: 0; padding: 3px 0; text-align: left; width: 50%; }   
    </style>
    <style media="print" type="text/css">
        .option-menu { display: none; }
    </style>





    <title>Invoice #15588</title>
    </head>
<body>




        
         
        <div class="invoice-page" style="/*margin: 0px 2% 0px 2%; */border-left: 1px solid #000; border-right: 1px solid #000;">
            <div id="invoice 1" class="">
                <div id="invoice12"><h1>Tax INVOICE</h1></div>
            </div> 
                      <div class="in-wrap">
                <div class="half">
                    <p><span class="bold-one">1. GSTIN : </span><strong>07AABCF7736N1Z6</strong></p>
                    <p><span class="bold-one">2. Name : </span><strong>Farida Gupta Retail Pvt Ltd</strong></p>
                     <p><span class="bold-one">3. Address : </span><strong>138/2/9 1st floor Kishan Garh Village, New Delhi - 110070</strong></p>
                    <p><span class="bold-one">4. Serial No. of Invoice : </span><strong>FGR-15588</strong></p>
                    <p><span class="bold-one">5. Date of Invoice : </span><strong>19/07/2019</strong></p>
                    <p><span class="bold-one">6. Order Number : </span><strong>100084571</strong></p>
                </div>
            </div>
          

          <table width="100%" border="0" cellspacing="0" cellpadding="2" class="seller-and-buyer">
            <tbody><tr bgcolor="#e1e1e1">
                <td valign="top" style="border-top: 1px solid #000; border-bottom: 2px solid #999;"><h2>Details of Receiver (Billed to)</h2></td>
                <td valign="top" style="border-top: 1px solid #000; border-bottom: 2px solid #999;"><h2>Details of Consignee (Shipped to)</h2></td>
            </tr>
            <tr>
                <td valign="top">
                                                            <div id="th_2" class="t s5_2">Name : Sapna Kapur </div>
                    <div id="ti_2" class="t s5_2">Address : 802 International Trade Tower Nehru Place</div>
                    <div id="tj_2" class="t s5_2">State : Delhi</div>
                    <div id="tj_2_1" class="t s5_2">Country : India</div>
                    <div id="tj_2_2" class="t s5_2">Pincode : 110019</div>
                    <div id="tk_2" class="t s5_2">State Code : DL</div>
                    <div id="tl_2" class="t s5_2">GSTIN/Unique ID : N/A</div>
                </td>
                <td valign="top">
                                                            <div id="tn_2" class="t s5_2">Name : Sapna Kapur  </div>
                    <div id="to_2" class="t s5_2">Address : 802 International Trade Tower Nehru Place </div>
                    <div id="tp_2" class="t s5_2">State : Delhi</div>
                    <div id="tq_2" class="t s5_2">Country : India</div>
                    <div id="tr_2" class="t s5_2">Pincode : 110019</div>
                    <div id="tr_2_1" class="t s5_2">State Code : DL</div>
                    <div id="tr_2_2" class="t s5_2">GSTIN/Unique ID : N/A</div>
                </td>
            </tr>
          </tbody></table>

          <!-- Order -->
          <table border="0" width="100%" cellspacing="0" cellpadding="3">
            <tbody><tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" rowspan="2">S.No.</td>
                <td class="dataTableHeadingContent" rowspan="2">DESCRIPTION of Goods</td>
                <td class="dataTableHeadingContent" rowspan="2">HSN</td>
                <td class="dataTableHeadingContent" rowspan="2">Qty</td>
                <td class="dataTableHeadingContent" rowspan="2">Unit</td>
                <td class="dataTableHeadingContent" rowspan="2">MRP</td>
                <td class="dataTableHeadingContent" rowspan="2">Total</td>
                <td class="dataTableHeadingContent" rowspan="2">Discount</td>
                <!--  <td class="dataTableHeadingContent" align="right">Selling Price</td> -->
                <td class="dataTableHeadingContent" rowspan="2">Taxable value</td>
                <!-- <td class="dataTableHeadingContent" align="right">Sales Tax@5%</td>-->
                <!-- <td class="dataTableHeadingContent" align="right">Total Amount (Included of all Taxes)</td> -->
                <td class="dataTableHeadingContent" colspan="2">CGST</td>
                
                <td class="dataTableHeadingContent" colspan="2">SGST/UTGST</td>
                
                <td class="dataTableHeadingContent" colspan="2">IGST</td>
                </tr><tr>
                    <td class="dataTableHeadingContent" style="border-top: 0px;">Rate</td>
                    <td class="dataTableHeadingContent" style="border-top: 0px;">Amt</td>

                    <td class="dataTableHeadingContent" style="border-top: 0px;">Rate</td>
                    <td class="dataTableHeadingContent" style="border-top: 0px;">Amt</td>
                
                    <td class="dataTableHeadingContent" style="border-top: 0px;">Rate</td>
                    <td class="dataTableHeadingContent" style="border-top: 0px;">Amt</td>
                </tr>
            
                  <tr class="dataTableRow ">
        <td class="dataTableContent" valign="top" align="">1</td>
         <td class="dataTableContent" valign="top">Off-White Cotton Pants<br><em>Size:</em>&nbsp;&nbsp;M  </td>
  <td class="dataTableContent" valign="top" align="">62114210</td>
  <td class="dataTableContent" valign="top" align="">1</td>
 <td class="dataTableContent" valign="top" align="">Pc.</td>
 <td class="dataTableContent" align="right" valign="top"><b>₹ 950.00</b></td>
 <td class="dataTableContent" align="right" valign="top"><b>₹ 950.00</b></td>
 <td class="dataTableContent" align="right" valign="top"><b>₹ 0.00</b></td>
  <td class="dataTableContent" align="right" valign="top"><b>₹904.76</b></td>
 <td class="dataTableContent" align="right" valign="top"><b>2.5%</b></td>
<td class="dataTableContent" align="right" valign="top"><b>₹22.62</b></td>
<td class="dataTableContent" align="right" valign="top"><b>2.5%</b></td>
<td class="dataTableContent" align="right" valign="top"><b>₹22.62</b></td>
<td class="dataTableContent" align="right" valign="top"><b></b></td>
<td class="dataTableContent" align="right" valign="top"><b>₹0.00</b></td>
  </tr>
      <tr class="dataTableRow alt">
        <td class="dataTableContent" valign="top" align="">2</td>
         <td class="dataTableContent" valign="top">Roza Zara Black Top<br><em>Size:</em>&nbsp;&nbsp;S  </td>
  <td class="dataTableContent" valign="top" align="">62114210</td>
  <td class="dataTableContent" valign="top" align="">1</td>
 <td class="dataTableContent" valign="top" align="">Pc.</td>
 <td class="dataTableContent" align="right" valign="top"><b>₹699.00</b></td>
 <td class="dataTableContent" align="right" valign="top"><b>₹699.00</b></td>
 <td class="dataTableContent" align="right" valign="top"><b>₹0.00</b></td>
  <td class="dataTableContent" align="right" valign="top"><b>₹665.71</b></td>
 <td class="dataTableContent" align="right" valign="top"><b>2.5%</b></td>
<td class="dataTableContent" align="right" valign="top"><b>₹16.64</b></td>
<td class="dataTableContent" align="right" valign="top"><b>2.5%</b></td>
<td class="dataTableContent" align="right" valign="top"><b>₹16.64</b></td>
<td class="dataTableContent" align="right" valign="top"><b></b></td>
<td class="dataTableContent" align="right" valign="top"><b>₹0.00</b></td>
  </tr>
                                   
            
            <tr class="dataTableRow">
                <td class="dataTableContent" valign="top" align="" rowspan="12"></td>
                <td class="dataTableContent" valign="top" align="" colspan="5">Freight</td>
                <td class="dataTableContent" valign="top" align="right">₹100.00</td>
                <td class="dataTableContent" valign="top" align="right"></td>
                <td class="dataTableContent" valign="top" align="right">₹95.24</td>
                <td class="dataTableContent" valign="top" align="right">2.5%</td>
                <td class="dataTableContent" valign="top" align="right">₹2.38</td>
                <td class="dataTableContent" valign="top" align="right">2.5%</td>
                <td class="dataTableContent" valign="top" align="right">₹2.38</td>
                <td class="dataTableContent" valign="top" align="right"></td>
                <td class="dataTableContent" valign="top" align="right">₹0.00</td>
            </tr>
            <tr class="dataTableRow">
                <td class="dataTableContent" valign="top" align="" colspan="5">Insurance</td>
                <td class="dataTableContent" valign="top" align=""></td>
                <td class="dataTableContent" valign="top" align=""></td>
                <td class="dataTableContent" valign="top" align=""></td>
                <td class="dataTableContent" valign="top" align=""></td>
                <td class="dataTableContent" valign="top" align=""></td>
                <td class="dataTableContent" valign="top" align=""></td>
                <td class="dataTableContent" valign="top" align=""></td>
                <td class="dataTableContent" valign="top" align=""></td>
                <td class="dataTableContent" valign="top" align=""></td>
            </tr>
            <tr class="dataTableRow">
                <td class="dataTableContent" valign="top" align="" colspan="5">Packing and Forwarding Charges</td>
                <td class="dataTableContent" valign="top" align=""></td>
                <td class="dataTableContent" valign="top" align=""></td>
                <td class="dataTableContent" valign="top" align=""></td>
                <td class="dataTableContent" valign="top" align=""></td>
                <td class="dataTableContent" valign="top" align=""></td>
                <td class="dataTableContent" valign="top" align=""></td>
                <td class="dataTableContent" valign="top" align=""></td>
                <td class="dataTableContent" valign="top" align=""></td>
                <td class="dataTableContent" valign="top" align=""></td>
            </tr>

            <tr class="dataTableRow">
                <td class="dataTableContent" valign="top" align="" rowspan="2"></td>
                <td class="dataTableContent" valign="top" align="" colspan="4">&nbsp;</td>
                <td class="dataTableContent" valign="top" align=""></td>
                <td class="dataTableContent" valign="top" align=""></td>
                <td class="dataTableContent" valign="top" align=""></td>
                <td class="dataTableContent" valign="top" align=""></td>
                <td class="dataTableContent" valign="top" align=""></td>
                <td class="dataTableContent" valign="top" align=""></td>
                <td class="dataTableContent" valign="top" align=""></td>
                <td class="dataTableContent" valign="top" align=""></td>
                <td class="dataTableContent" valign="top" align=""></td>
            </tr>

            <tr class="dataTableRow">
                <td class="dataTableContent" valign="top" align="" colspan="4">Total</td>
                <td class="dataTableContent" valign="top" align="right">
                    ₹1,749.00</td>
                <td class="dataTableContent" valign="top" align="right">₹0.00</td>
                <td class="dataTableContent" valign="top" align="right">₹1,665.71</td>
                <td class="dataTableContent" valign="top" align="right" colspan="2">₹41.64</td>
                <td class="dataTableContent" valign="top" align="right" colspan="2">₹41.64</td>
                <td class="dataTableContent" valign="top" align="right" colspan="2">₹0.00</td>
            </tr>

            <tr class="dataTableRow">
                <td class="dataTableContent" valign="top" align="" colspan="15">&nbsp;</td>
            </tr>

            

                        <tr class="dataTableRow">
                <td class="dataTableContent" valign="top" align="" colspan="8">Total Value of invoice (In figure)</td>
                <td class="dataTableContent" valign="top" align="" colspan="6">₹1,749.00</td>
            </tr>

            <!-- <tr class="dataTableRow">
                <td class="dataTableContent" valign="top" align="" colspan="8">Total Invoice Value (In Word)</td>
                <td class="dataTableContent" valign="top" align="" colspan="6">Rupees one thousand seven hundred forty-nine Only </td>
            </tr> -->

             <tr class="dataTableRow">
                <td class="dataTableContent" valign="top" align="" colspan="8">Customer Credit Use</td>
                <td class="dataTableContent" valign="top" align="" colspan="6">₹0.00</td>
            </tr>


         <!--  
              <tr class="dataTableRow">
                <td class="dataTableContent" valign="top" align="" colspan="8">Additional Discount</td>
                <td class="dataTableContent" valign="top" align="" colspan="6">₹0.00</td>
            </tr>

           -->


            <tr class="dataTableRow">
                <td class="dataTableContent" valign="top" align="" colspan="8">Amount paid / collected from customer (In figure)</td>
                <td class="dataTableContent" valign="top" align="" colspan="6"> ₹1,749.00</td>
            </tr>

            <tr class="dataTableRow">
                <td class="dataTableContent" valign="top" align="" colspan="8">Amount paid / collected from customer (In Word)</td>
                <td class="dataTableContent" valign="top" align="" colspan="6">Rupees one thousand seven hundred forty-nine Only</td>
            </tr>

            <tr class="dataTableRow">
                <td class="dataTableContent" valign="top" align="" colspan="8">Amount of Tax subject to Reverse Charges</td>
                <td class="dataTableContent" valign="top" align="" colspan="2"></td>
                <td class="dataTableContent" valign="top" align="" colspan="2"></td>
                <td class="dataTableContent" valign="top" align="" colspan="2"></td>
            </tr>
           
            
          </tbody></table>

<div style="width: 600px; margin: auto; margin-bottom: 20px;">
    <p style="font-size: 11px; text-align: center;">Tax Details</p>
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tbody><tr class="dataTableHeadingRow">
            <td class="dataTableHeadingContent" valign="top" style="text-align: center;">GST Tax %</td>
            <td class="dataTableHeadingContent" valign="top" style="text-align: center;">Taxable Amount</td>
            <td class="dataTableHeadingContent" valign="top" style="text-align: center;">CGST Tax</td>
            <td class="dataTableHeadingContent" valign="top" style="text-align: center;">SGST Tax</td>
             <td class="dataTableHeadingContent" valign="top" style="text-align: center;">IGST Tax</td>
            <td class="dataTableHeadingContent" valign="top" style="text-align: center;">Total Tax</td>
        </tr>

        <tr class="dataTableRow">
            <td class="dataTableContent" valign="top" style="text-align: center;">12%</td>
            <td class="dataTableContent" valign="top" style="text-align: right;">
            ₹0.00</td>
            <td class="dataTableContent" valign="top" style="text-align: right;">
                ₹0.00            </td>
            <td class="dataTableContent" valign="top" style="text-align: right;">
                ₹0.00            </td>
             <td class="dataTableContent" valign="top" style="text-align: right;">
                ₹0.00            </td>
            <td class="dataTableContent" valign="top" style="text-align: right;">

                        ₹0.00</td>
        </tr>

        <tr class="dataTableRow">
            <td class="dataTableContent" valign="top" style="text-align: center;">5%</td>
            <td class="dataTableContent" valign="top" style="text-align: right;">
                ₹1,570.48</td>
            
            <td class="dataTableContent" valign="top" style="text-align: right;">
                ₹39.26            </td>
            <td class="dataTableContent" valign="top" style="text-align: right;">
                ₹39.26            </td>
            <td class="dataTableContent" valign="top" style="text-align: right;">
                ₹0.00            </td>
            <td class="dataTableContent" valign="top" style="text-align: right;">

                                ₹78.52            </td>
        </tr>

         <tr class="dataTableRow">
            <td class="dataTableContent" valign="top" style="text-align: center;">Freight</td>
            <td class="dataTableContent" valign="top" style="text-align: right;">
                ₹95.24</td>
            
            <td class="dataTableContent" valign="top" style="text-align: right;">
                ₹2.38            </td>
            <td class="dataTableContent" valign="top" style="text-align: right;">
                ₹2.38            </td>
            <td class="dataTableContent" valign="top" style="text-align: right;">
                ₹0.00            </td>
            <td class="dataTableContent" valign="top" style="text-align: right;">
                                ₹4.76            </td>
        </tr>

        <tr class="dataTableRow">
            <td class="dataTableContent" valign="top" style="text-align: center;">GRAND TOTALS </td>
            <td class="dataTableContent" valign="top" style="text-align: right;"></td>
            <td class="dataTableContent" valign="top" style="text-align: right;">
                ₹41.64 
                      
            </td>
            <td class="dataTableContent" valign="top" style="text-align: right;">
                ₹41.64            </td>
            <td class="dataTableContent" valign="top" style="text-align: right;">
                ₹0.00            </td>
            <td class="dataTableContent" valign="top" style="text-align: right;">
                ₹83.29            </td>
        </tr>
    </tbody></table>
</div>

        <p style="text-align: center; margin-top: 10px;"><strong>DECLARATION</strong>
                    <br>
                        We declare that this invoice shows actual price of the goods and that all particulars are true and correct.
<br>
                     

THIS IS A COMPUTER GENERATED INVOICE AND DOES NOT REQUIRE SIGNATURE</p>
        </div>
        <!--  Invoice Page   -->
       

    
 
 <script type="text/javascript">window.NREUM||(NREUM={});NREUM.info={"beacon":"bam.nr-data.net","licenseKey":"eeff37e7dc","applicationID":"49160072","transactionName":"NV0GMkQAWkACBUdQWgwXJQVCCFtdTABUWFEPUQpJXhVZXwoIRVZcAV07FkQIWkdMD11PWgtbAQ==","queueTime":0,"applicationTime":338,"atts":"GRoFRAwaSU4=","errorBeacon":"bam.nr-data.net","agent":""}</script>
    </body></html>