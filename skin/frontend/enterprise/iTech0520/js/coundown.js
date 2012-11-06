 function calcage(secs, num1, num2) {
            s = ((Math.floor(secs/num1))%num2).toString();
            if (LeadingZero && s.length < 2)
                s = "0" + s;
            return s;
 }
    function CountBack(secs) {
        if (secs < 0) {
            document.getElementById("cntdwn").innerHTML = FinishMessage;
            return;
        }
        DisplayStr = DisplayFormat.replace(/%%D%%/g, calcage(secs,86400,100000));
        DisplayStr = DisplayStr.replace(/%%H%%/g, calcage(secs,3600,24));
        DisplayStr = DisplayStr.replace(/%%M%%/g, calcage(secs,60,60));
        DisplayStr = DisplayStr.replace(/%%S%%/g, calcage(secs,1,60));

        document.getElementById("cntdwn").innerHTML = DisplayStr;
        if (CountActive)
            setTimeout("CountBack(" + (secs+CountStepper) + ")", SetTimeOutPeriod);
    }



    function putspan(backcolor, forecolor) {
        document.write("<label id='cntdwn'></label>");
    }
    
    
    if (typeof(DisplayFormat)=="undefined")
        DisplayFormat = "<label style='display:block; float:left'> %%D%%</label> <span style='display:block; float:left; padding-top:2px'>DAYS:&nbsp;&nbsp;</span> <label style='display:block; float:left'>%%H%% </label><span style='display:block; float:left;  padding-top:2px'>HRS: &nbsp;&nbsp;</span> <label style='display:block; float:left'>%%M%%</label> <span style='display:block; float:left;  padding-top:2px'>MINS: &nbsp;&nbsp;</span><label style='display:block; width:25px; float:left'> %%S%% </label><span style='display:block; float:left;  padding-top:2px'>SEC</span>";
    if (typeof(CountActive)=="undefined")
        CountActive = true;
        CountStepper = Math.ceil(CountStepper);
    if (CountStepper == 0)
        CountActive = false;
    var SetTimeOutPeriod = (Math.abs(CountStepper)-1)*1000 + 990;
        putspan(BackColor, ForeColor);
        var dthen = new Date(TargetDate);
        var dnow = new Date();
    if(CountStepper>0)
        ddiff = new Date(dnow-dthen);
    else
        ddiff = new Date(dthen-dnow);
        gsecs = Math.floor(ddiff.valueOf()/1000);
        CountBack(gsecs);