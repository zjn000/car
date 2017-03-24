/**
 * Created by shenls on 2017/1/20.
 */
window.onload = function(){
    var pageList = $('.page');
    if(pageList){
        addGz();
    }
}
//添加骑缝章图片
function addGz(){
    var pageList = $('.page'),
        _length=pageList.length,
        _html = '<div class="page_gz"></div>',
        publicUrl = $('body').attr('data-url');
    pageList.append(_html);
    var pageGz = $('.page_gz');
    pageGz.css({
        position:'absolute',
        top:'50%',
        marginTop:'-85px',
        right:'0',
        height:'170px',
        background:'url('+publicUrl+'/statics/images/gz.png)0 0 no-repeat',
    })
    //如果页面是四张
    if(_length==4){
        pageGz.css({
            width:'42.5px',
            backgroundSize:'400%'
        })
        for (var i = 0; i < _length;i++){
            pageGz[i].style.backgroundPositionX = '-'+i*42.5+'px';
        }
    }
    //如果页面是三张
    if(_length==3){
        pageGz.css({
            width:'56.67px',
            backgroundSize:'300%'
        })
        for (var i = 0; i < _length;i++){
            var n = i+1;
            pageGz[i].style.backgroundPositionX = '-'+i*56.67+'px';
        }
    }
}
