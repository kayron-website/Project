const textHours = document.getElementById('text-hours'),
      textMinutess = document.getElementById('text-minutess'),
      textAmPms = document.getElementById('text-ampms'),
      dateDays = document.getElementById('date-days'),
      dateMonths = document.getElementById('date-months'),
      dateYears = document.getElementById('date-years')

const clockTexts = () =>{
    let date = new Date()

    let hh = date.getHours(),
        ampm,
        mm = date.getMinutes(),
        day = date.getDate(),
        month = date.getMonth(),
        year = date.getFullYear()

    if(hh >= 12){
        hh = hh - 12
        ampm = 'PM'
    }else{
        ampm = 'AM'
    }

    if(hh == 0){hh = 12}

    if(hh < 10){hh = `0${hh}`}

    textHours.innerHTML = `${hh}:`
    
    if(mm < 10){mm = `0${mm}`}
    
    textMinutess.innerHTML = mm

    textAmPms.innerHTML = ampm

    // let week = ['Sun', 'Mon', 'Tues', 'Wed', 'Thurs', 'Fri', 'Sat']

    let months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
    
    dateMonths.innerHTML = `${months[month]},`
    dateDays.innerHTML = day
    dateYears.innerHTML = year
}
setInterval(clockTexts, 1000)
