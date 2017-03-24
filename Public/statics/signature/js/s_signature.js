/**
 * Created by shenl on 2017-01-10.
 */
$(document).on('ready', function() {
    $('table tr:last-child').before(_html);
    var subBtn = $('table tr:last-child').find('button');
    subBtn.on('click',function(){
        return saveSignature();
    })
    $("#signature").attr('data-prime','false');
    $('.js-signature').on('jq.signature.changed', function() {
        //$('#saveBtn').attr('disabled', false);
        $("#signature").attr('data-prime','true');
    });
    if ($('.js-signature').length) {
        $('.js-signature').jqSignature();
    }
});
function clearCanvas() {
    //$('#signature').html('<p><em>Your signature will appear here when you click "Save Signature"</em></p>');
    $('.js-signature').jqSignature('clearCanvas');
    //$('#saveBtn').attr('disabled', true);
    $("#signature").attr('data-prime','false');
    $('#signature').empty();
}
//生成电子签名隐藏域
function saveSignature() {
    $('#signature').empty();
    var dataUrl = $('.js-signature').jqSignature('getDataURL');
    var ipt = $('<input>').attr({
        value:dataUrl,
        type:'hidden',
        name:'signature_pic'
    })
    //var img = $('<img>').attr('src', dataUrl);
    //$('#signature').append(img);
    $('#signature').append(ipt);
    return vali();
}
//验证电子签名
function vali(){
    var prime = $("#signature").attr('data-prime');
    if(prime==="false"){
        alert('请输入电子签名！')
        return false;
    }else{
        return true;
    }
}
var _html = '';
_html+='<tr class="htmleaf-container">';
_html+='<td class="container" colspan="2">';
_html+='<p><b>电子签名</b></p>';
_html+='<div class="js-signature" data-width="260" data-height="65" data-border="1px solid black" data-line-color="#000" data-auto-fit="true"></div>';
_html+='<p>';
_html+='<a id="clearBtn" class="btn btn-default btn-block" href="javascript:;" onclick="clearCanvas();">重写</a>';
//_html+='<button id="saveBtn" class="btn btn-default btn-block" onclick="saveSignature();" disabled>保存签名</button></p>';
_html+='<div id="signature">';
_html+='</div>';
_html+='</td>';
_html+='</tr>';