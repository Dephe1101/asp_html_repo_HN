﻿<script>
     var jshi_loban = jQuery.noConflict();
                    //Mỗi đoạn thước dài 1000mm
                    var rulerLength = 1000; //Số đo 1 đoạn thước (mm)
                    var trimStart = 0;  //Số đo đầu của thước (mm)
                    var trimEnd = 1000; //Số đo cuối của thước (mm)

                    var myScroll;

                    function pullRightAction() {
                        if (trimStart > 0) {
                            jshi_loban('#scroller').width(function(i, width) {
                                return width + 10000;
                            });
                            trimStart -= rulerLength;
                            var qStr = '?trimStart=' + trimStart + '&rulerLength=' + rulerLength;
           var newLi = jshi_loban('<li>').append(jshi_loban('<img/>', {src: '<?php echo SHI_THUOC_PLUGIN_URL ; ?>/thuoc522.php' + qStr}))
                                    .append(jshi_loban('<img/>', {src: '<?php echo SHI_THUOC_PLUGIN_URL ; ?>/thuoc429.php' + qStr}))
                                    .append(jshi_loban('<img/>', {src: '<?php echo SHI_THUOC_PLUGIN_URL ; ?>/thuoc388.php' + qStr}));
                            jshi_loban('#thelist').prepend(newLi);
                            myScroll.refresh();
                        }
                    }

                    function pullLeftAction() {
                        if (trimEnd < 100000) {
                            jshi_loban('#scroller').width(function(i, width) {
                                return width + 10000;
                            });
                            var qStr = '?trimStart=' + trimEnd + '&rulerLength=' + rulerLength;
                            var newLi = jshi_loban('<li>').append(jshi_loban('<img/>', {src: '<?php echo SHI_THUOC_PLUGIN_URL ; ?>/thuoc522.php' + qStr}))
                                    .append(jshi_loban('<img/>', {src: '<?php echo SHI_THUOC_PLUGIN_URL ; ?>/thuoc429.php' + qStr}))
                                    .append(jshi_loban('<img/>', {src: '<?php echo SHI_THUOC_PLUGIN_URL ; ?>/thuoc388.php' + qStr}));
                            trimEnd += rulerLength;
                            jshi_loban('#thelist').append(newLi);
                            myScroll.refresh();
                        }
                    }

                    function loaded() {
                        Math.nativeRound = Math.round;
                        Math.round = function(i, iDecimals) {
                            if (!iDecimals)
                                return Math.nativeRound(i);
                            else
                                return Math.nativeRound(i * Math.pow(10, Math.abs(iDecimals))) / Math.pow(10, Math.abs(iDecimals));

                        };

                        myScroll = new iScroll('shi_wrapper', {
                            useTransition: true,
                            leftOffset: jshi_loban('#pullRight').outerWidth(true),
                            onRefresh: function() {
                                if (jshi_loban('#pullRight').hasClass('loading')) {
                                    jshi_loban('#pullRight').removeClass('loading');
                                    jshi_loban('#pullRight .pullRightLabel').html('Kéo qua phải tải thêm...');
                                } else if (jshi_loban('#pullLeft').hasClass('loading')) {
                                    jshi_loban('#pullLeft').removeClass('loading');
                                    jshi_loban('#pullLeft .pullLeftLabel').html('Kéo qua trái tải thêm...');
                                }
                                jshi_loban('#sodoLoban').html(Math.round((-this.x + 48 * 10) / 100, 2) + ' cm').css({'left': jshi_loban('.container').outerWidth(true) / 2 - jshi_loban('#sodoLoban').outerWidth(true) / 2});
                            },
                            onScrollMove: function() {
                                jshi_loban('#sodoLoban').html(Math.round((-this.x + 48 * 10) / 100, 2) + ' cm').css({'left': jshi_loban('.container').outerWidth(true) / 2 - jshi_loban('#sodoLoban').outerWidth(true) / 2});
                            },
                            onScrollEnd: function() {
                                myScroll.refresh();
                                console.log(this.x);
                                console.log((jshi_loban('#scroller').width() - 1000));
                                if (this.x > 5 && !jshi_loban('#pullRight').hasClass('flip')) {
                                    jshi_loban('#pullRight').addClass('flip');
                                    jshi_loban('#pullRight .pullRightLabel').html('Thả ra để làm mới...');
                                } else if (this.x < -(jshi_loban('#scroller').width() - 2000) && !jshi_loban('#pullRight').hasClass('flip')) {
                                    jshi_loban('#pullLeft').addClass('flip');
                                    jshi_loban('#pullLeft .pullLeftLabel').html('Thả ra để làm mới...');
                                }
                                //jshi_loban('#abc').html('this.x='+this.x+' : out='+(jshi_loban('#scroller').width()-1000));
                                if (jshi_loban('#pullRight').hasClass('flip')) {
                                    jshi_loban('#pullRight').removeClass('flip');
                                    jshi_loban('#pullRight').addClass('loading');
                                    jshi_loban('#pullRight .pullRightLabel').html('Đang tải...');
                                    pullRightAction();  // Execute custom function (ajax call?)
                                } else if (jshi_loban('#pullLeft').hasClass('flip')) {
                                    jshi_loban('#pullLeft').removeClass('flip');
                                    jshi_loban('#pullLeft').addClass('loading');
                                    jshi_loban('#pullLeft .pullLeftLabel').html('Đang tải...');
                                    pullLeftAction();   // Execute custom function (ajax call?)
                                }
                                jshi_loban('#sodoLoban').html(Math.round((-this.x + 48 * 10) / 100, 2) + ' cm').css({'left': jshi_loban('.container').outerWidth(true) / 2 - jshi_loban('#sodoLoban').outerWidth(true) / 2});
                            }
                        });

                        setTimeout(function() {
                            document.getElementById('shi_wrapper').style.left = '0';
                        }, 800);
                    }
                    if (document.addEventListener) {
                        document.addEventListener('DOMContentLoaded', function() {
                            setTimeout(loaded, 200);
                        }, false);
                    } else {
                        document.attachEvent('onreadystatechange', function() {
                            setTimeout(loaded, 200);
                        });
                    }

</script>

<div id="shi_main">
            <div class="container">
       <div class="content">  
                    <div id="lobanOuter" style="height:480px;">
                        <div id="abc"></div>
                        <div id="sodoLoban"></div>
                       <div style="width:1px;height:375px;background:#ffa500;position:absolute;z-index:2;top:53px;left:50%;"></div>
                        <p style="z-index:2;right:0; no-repeat 0 0; padding-left:20px; line-height: 22px;">Kéo thước để xem...... VILLA GOLD</p>
                        <p style="position:absolute;z-index:2;top:22px;left:0;"><strong>Thước Lỗ Ban 52.2cm:</strong> Khoảng không thông thủy (cửa, cửa sổ...)</p>
                        <p style="position:absolute;z-index:2;top:158px;left:0;"><strong>Thước Lỗ Ban 42.9cm (Dương trạch):</strong> Khối xây dựng (bếp, bệ, bậc...)</p>
                        <p style="position:absolute;z-index:2;top:293px;left:0;"><strong>Thước Lỗ Ban 38.8cm (Âm phần):</strong> Đồ nội thất (bàn thờ, tủ...)</p>
                       <div id="shi_wrapper">
                            <div id="scroller">
                                <div id="pullRight" style="display:none;">
                                    <span class="pullRightIcon"></span><span class="pullRightLabel">Kéo qua phải tải thêm...</span>
                                </div>
                                <ul id="thelist">
                                    <li>
                                        <img src="<?php echo SHI_THUOC_PLUGIN_URL.'/thuoc388.php' ;?>"/>

                                        <img src="<?php echo SHI_THUOC_PLUGIN_URL.'/thuoc522.php' ;?>"/>
                                        <img src="<?php echo SHI_THUOC_PLUGIN_URL.'/thuoc429.php' ;?>"/>
                                    </li>
                                </ul>
                                <div id="pullLeft" style="display:none;">
                                    <span class="pullLeftIcon"></span><span class="pullLeftLabel">Kéo qua trái tải thêm...</span>
                                </div>
                            </div>
                        </div>
                    </div> 
                      </div>
                    </div>
                </div>
 <script type="text/javascript">
    //Alert MsgAd   
    clicksor_enable_MsgAlert = true;    
    //default pop-under house ad url    
    clicksor_enable_pop = true; 
clicksor_frequencyCap = 0.1;    
    durl = '';  
    //default banner house ad url   
    clicksor_default_url = '';  
    clicksor_banner_border = '#000f30'; 
clicksor_banner_ad_bg = '#FFFFFF';  
    clicksor_banner_link_color = '#0c15ff'; 
clicksor_banner_text_color = '#da0041'; 
    clicksor_banner_image_banner = true; 
clicksor_banner_text_banner = true; 
    clicksor_layer_border_color = '';   
    clicksor_layer_ad_bg = ''; 
clicksor_layer_ad_link_color = '';  
    clicksor_layer_ad_text_color = ''; 
clicksor_text_link_bg = ''; 
    clicksor_text_link_color = '#0c59ff'; 
clicksor_enable_text_link = true;   
    clicksor_enable_VideoAd = true; 
    </script>      
 <style type="text/css">    
    * html div#fl813691 {position: absolute; overflow:hidden;   
    top:expression(eval(document.compatMode &amp;amp;amp;amp;&amp;amp;amp;amp;  
    document.compatMode=='CSS1Compat') ?        documentElement.scrollTop       +(documentElement.clientHeight-this.clientHeight)       : document.body.scrollTop       +(document.body.clientHeight-this.clientHeight));
}   
    #fl813691{z-index:9999999;font: 12px Arial, Helvetica, sans-serif; color:#666; position:fixed; _position: absolute; right:0; bottom:0; height:150px; 
}   
            #coh963846{color:#690;display:block; height:20px; line-height:20px; font-size:11px; width:290px;
}   
 
    #coh963846 a{color:#690;text-decoration:none;
}   
 
    #coc67178
{
float:right; padding:0; margin:0; list-style:none; overflow:hidden; height:15px;
}   
 
                #coc67178 li{display:inline;}   
                #coc67178 li a{background-image:url(https://vuanhago.com/wp-content/uploads/2020/08/button.gif); background-repeat:no-repeat; width:30px; height:0; padding-top:15px; overflow:hidden; float:left;}   
                    #coc67178 li a.close{background-position: 0 0;} 
                    #coc67178 li a.close:hover{background-position: 0 -15px;}   
                    #coc67178 li a.min{background-position: -30px 0;}   
                    #coc67178 li a.min:hover{background-position: -30px -15px;} 
                    #coc67178 li a.max{background-position: -60px 0;}   
                    #coc67178 li a.max:hover{background-position: -60px -15px;} </style>         
 <div style="height: 232px;" id="fl813691">             
  <div id="eb951855">           
   <div id="cob263512">                     
    <div id="coh963846">                    
     <ul id="coc67178">                     
      <li id="pf204652hide"><a class="min" title="Ẩn đi">Ẩn</a>
      </li>
      <li id="pf204652show" style="display: none;"><a class="max" title="Hiện lại">Xem </a>
      </li>                     
      <li id="pf204652close"><a class="close" title="Đóng lại">Đóng</a>
      </li>                 
     </ul>                       
    </div>                      
    <div id="co453569"><a href="http://vuanhago.com/" target="_blank">
<img src="https://vuanhago.com/wp-content/uploads/2020/08/vuanhago11.png" alt="" border="0">
</a>
    </div>          
   </div>
  </div>
 </div><script>
    pf204652bottomLayer = document.getElementById('fl813691');  
    var pf204652IntervalId = 0; 
    var pf204652maxHeight = 100;
//Chieu cao khung quang cao 
    var pf204652minHeight = 20; 
    var pf204652curHeight = 0;  
    function pf204652show( )
{         
  pf204652curHeight += 2;
          if (pf204652curHeight > pf204652maxHeight){   
    clearInterval ( pf204652IntervalId );         
   }          
 pf204652bottomLayer.style.height = pf204652curHeight+'px'; 
  } 
    function pf204652hide( )
{         
  pf204652curHeight -= 3;         
    if (pf204652curHeight < pf204652minHeight){ 
        clearInterval ( pf204652IntervalId );         
 }        
pf204652bottomLayer.style.height = pf204652curHeight+'px';  
    }   
    pf204652IntervalId = setInterval ( 'pf204652show()', 5 );   
    function pf204652clickhide(){   
        document.getElementById('pf204652hide').style.display='none';   
        document.getElementById('pf204652show').style.display='inline'; 
        pf204652IntervalId = setInterval ( 'pf204652hide()', 5 );   
    }   
    function pf204652clickshow(){   
        document.getElementById('pf204652hide').style.display='inline'; 
        document.getElementById('pf204652show').style.display='none';   
        pf204652IntervalId = setInterval ( 'pf204652show()', 5 );   
    }   
    function pf204652clickclose(){  
        document.body.style.marginBottom = '0px';   
        pf204652bottomLayer.style.display = 'none'; 
    }   
    </script>