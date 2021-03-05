function getTimeRemaining(endtime){
	var lind=new Date(Date.parse(new Date())+endtime),t=Date.parse(endtime)-Date.parse(new Date());var seconds=Math.floor((t/1000)%60);var minutes=Math.floor((t/1000/60)%60);var hours=Math.floor((t/(1000*60*60))%24);var days=Math.floor(t/(1000*60*60*24));return{'total':t,'days':days,'hours':hours,'minutes':minutes,'seconds':seconds};}

function initializeClock(id,endtime,xc){
	var lind = new Date(Date.parse(new Date())+endtime),daysSpan=$('.days');var hoursSpan=$('.hours');var minutesSpan=$('.minutes');var secondsSpan=$('.seconds');


function updateClock(){
	var t=getTimeRemaining(lind);daysSpan.html(t.days);hoursSpan.html(('0'+t.hours).slice(-2));minutesSpan.html(('0'+t.minutes).slice(-2));secondsSpan.html(('0'+t.seconds).slice(-2))
if(t.total<=0){clearInterval(timeinterval);xc(t.total);$(id).attr('disabled',false);
}else{
    if(typeof id == "function"){
        id(t.total);
    }
    if(t.total != endtime){
    $(id).css('opacity',1-(t.total/endtime));
    }
}}
updateClock();var timeinterval=setInterval(updateClock,1000);
}




