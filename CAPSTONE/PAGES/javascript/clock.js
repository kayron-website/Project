const textHour = document.getElementById('text-hour'),
      textMinutes = document.getElementById('text-minutes'),
      textAmPm = document.getElementById('text-ampm'),
      dateDay = document.getElementById('date-day'),
      dateMonth = document.getElementById('date-month'),
      dateYear = document.getElementById('date-year')

const clockText = () =>{
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

    textHour.innerHTML = `${hh}:`
    
    if(mm < 10){mm = `0${mm}`}
    
    textMinutes.innerHTML = mm

    textAmPm.innerHTML = ampm

    // let week = ['Sun', 'Mon', 'Tues', 'Wed', 'Thurs', 'Fri', 'Sat']

    let months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
    
    dateMonth.innerHTML = `${months[month]},`
    dateDay.innerHTML = day
    dateYear.innerHTML = year
}
setInterval(clockText, 1000)
