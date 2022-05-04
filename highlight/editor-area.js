/*function scrollprecode(){
    $('pre code').scrollTop($('pre textarea').scrollTop());
}*/
function scrollprecode(){
    document.querySelector('pre code').scrollTo((document.querySelector('pre textarea').scrollLeft),(document.querySelector('pre textarea').scrollTop));
}
$(function(){
    var origin='';
    setTimeout(function(){
        origin=$('pre code ~ textarea').val();
    },10);
    var doc=new DOMParser().parseFromString($('pre code').html(), 'text/html');
    $('pre textarea').text(doc.body.textContent || "");
    $(document).on('keydown','pre code ~ textarea',function(e){
        if(e.keyCode==9){//TAB
            event.preventDefault();
            var start = $(this)[0].selectionStart;
            var finish = $(this)[0].selectionEnd;
            var add="    ";
            if(start!=finish && finish>0){
                start=$(this)[0].value.lastIndexOf('\n',start-1);
                add = $(this)[0].value.substring(start, finish);
                add=add.replaceAll('\n','\n    ');
                $(this)[0].setSelectionRange(start,finish);
            }
            document.execCommand("insertText",true,add);

        }
        if(e.keyCode==13){//ENTRER
            var cursor = $(this)[0].selectionStart;
            var val = $(this)[0].value;
            var preindx=(val.lastIndexOf('\n',cursor-1))+1;
            var pretxt= val.substring(preindx, cursor);
            var space='',pos=0;
            while(pos<pretxt.length && pretxt[pos]==' '){
                pos++;
            }
            var ispace=Math.ceil(pos/4);
            ispace= Math.max(ispace-Math.max((pretxt.match(/\}/g) || []).length-1,0),0);
            ispace= ispace+(pretxt.match(/\{/g) || []).length;
            if(pretxt.trim()[0]=='<' && pretxt.trim()[0]!='/' && pretxt.trim()[pretxt.trim().length-1]=='>'){
                ispace++;
            }
            ispace= Math.max(ispace-Math.max((pretxt.match(/<\//g) || []).length,0),0);
            while(ispace>0){
                ispace--;
                space+="    ";
            }
            e.preventDefault();
            document.execCommand("insertText",false,'\n'+space);

        }if(e.key=='}'){
            var cursor = $(this)[0].selectionStart;
            var val = $(this)[0].value;
            var preindx=(val.lastIndexOf('\n',cursor-1))+1;
            var pretxt= val.substring(preindx, cursor);
            if(pretxt.length>=4 && pretxt.trim()===''){
                for(i=0;i<4;i++) document.execCommand('delete');
            }
        }
        if(e.key=='/'){
            var cursor = $(this)[0].selectionStart;
            var val = $(this)[0].value;
            var preindx=(val.lastIndexOf('\n',cursor-1))+1;
            var pretxt= val.substring(preindx, cursor);
            if(pretxt.length>=5 && pretxt.trim()==='<'){
                var currentBalise=val.substring(val.lastIndexOf('<',cursor-3)+1,val.lastIndexOf('>',cursor-3));
                if(currentBalise.indexOf(' ')!==-1){
                    currentBalise.substring(0,currentBalise.indexOf(' '));
                }
                for(i=0;i<5;i++) document.execCommand('delete');
                document.execCommand("insertText",true,('</'+currentBalise+'>'));
                e.preventDefault()
            }
        }/*if(e.keyCode==8){//BACKSPACE
            var cursor = $(this)[0].selectionStart;
            var val = $(this)[0].value;
            var preindx=(val.lastIndexOf('\n',cursor-1))+1;
            var pretxt= val.substring(preindx, cursor);
            if(pretxt.length==4 && pretxt.trim()===''){
                for(i=pretxt.length;i>0;i--) document.execCommand('delete');
            }
        }*/
    })
    $(document).on('keypress keydown dragend','pre code ~ textarea',function(e){
        setTextInCode();
        scrollprecode();
    }).on('paste','pre code ~ textarea',function(e){
        setTextInCode();
        scrollprecode();
        setTimeout(function(){
            scrollprecode();
        },5);
    })
    $('pre textarea').on('scroll', function() {
        scrollprecode();
    })
    $('.btn-save').on('click',function(){
        $(this).attr('disabled',true);
        $('.btn-see').removeAttr('disabled');
        origin=$('pre code ~ textarea').val();
    })
    $(document).on('keydown', function(e){
        if(e.ctrlKey && e.which === 83){ // Check for the Ctrl key being pressed, and if the key = [S] (83)
            e.preventDefault();
            $('.btn-save').click();
        }
    });
    function setTextInCode(){
        $('.btn-hljs[disabled]').removeAttr('disabled');
        $el=$('pre code ~ textarea');
        setTimeout(function () {
            $('pre code').html(($el.val().replace(/&/g, "&amp;").replace(/>/g, "&gt;").replace(/</g, "&lt;").replace(/"/g, "&quot;"))+" ");
            document.querySelectorAll('pre code').forEach((block) => {hljs.highlightBlock(block);});
        },5);
        setTimeout(function () {
            $('pre code').html(($el.val().replace(/&/g, "&amp;").replace(/>/g, "&gt;").replace(/</g, "&lt;").replace(/"/g, "&quot;"))+" ");
            document.querySelectorAll('pre code').forEach((block) => {hljs.highlightBlock(block);});
            if(origin==$('pre code ~ textarea').val()){
                $('.name-info').removeClass('unsave');
                $('.btn-save').attr('disabled',true);
            }else{
                $('.name-info').addClass('unsave');
                $('.btn-save').removeAttr('disabled');
            }
        },50);
    }
})
