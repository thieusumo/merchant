//*
// input = date
// return = date1 - date2
// *
function btnDaily(data){
        return data+" - "+data;
    }
function btnWeekly(data){        
    var date = data;
    var d = new Date(date);

    var firstday = new Date(d.setDate(d.getDate() - d.getDay()));
    var lastday = new Date(d.setDate(d.getDate() - d.getDay()+6));
    
    var Month = d.getMonth() + 1;
    var startDay = firstday.getDate();      
    var Year = d.getFullYear();
    var endDay = lastday.getDate();
    return Month+'/'+startDay+'/'+Year+" - "+Month+'/'+endDay+'/'+Year;
}
function btnMonthly(data){
    var date = data;
    var d = new Date(date);  

    var Month = d.getMonth() + 1;
    var startDay = 1;      
    var Year = d.getFullYear();
    //get max date In Month
    var dateInMonth = new Date(Year, Month, 0).getDate();     
    var endDay = dateInMonth;
    return Month+'/'+startDay+'/'+Year+" - "+Month+'/'+endDay+'/'+Year;
}
function btnQuaterly(data){
    var date = data;
    var d = new Date(date); 
    var startMonth = ''; 
    var endMonth = '';         

    var Month = d.getMonth() + 1;
    var Year = d.getFullYear();
    switch(Month){
        case 1: startMonth = 1; endMonth = 3; endDay = 31; break;
        case 2: startMonth = 1; endMonth = 3; endDay = 31; break;
        case 3: startMonth = 1; endMonth = 3; endDay = 31; break;
        case 4: startMonth = 4; endMonth = 6; endDay = 30; break;
        case 5: startMonth = 4; endMonth = 6; endDay = 30; break;
        case 6: startMonth = 4; endMonth = 6; endDay = 30; break;
        case 7: startMonth = 7; endMonth = 9; endDay = 30; break;
        case 8: startMonth = 7; endMonth = 9; endDay = 30; break;
        case 9: startMonth = 7; endMonth = 9; endDay = 30; break;
        case 10: startMonth = 10; endMonth = 12; endDay = 31; break;
        case 11: startMonth = 10; endMonth = 12; endDay = 31; break;
        case 12: startMonth = 10; endMonth = 12; endDay = 31; break;
    }
    return startMonth+'/01/'+Year+" - "+endMonth+'/'+endDay+'/'+Year;
}
function btnYearly(data){
    var date = data;
    var d = new Date(date);  
    var Year = d.getFullYear();
    return "01/01/"+Year+" - "+"12/31/"+Year;
}