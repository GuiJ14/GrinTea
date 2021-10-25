const setMessage = (div, response) => {
    div.classList = "ui message "+response.type;
    div.getElementsByClassName('header')[0].innerHTML = response.header;
    div.getElementsByClassName('content')[0].innerHTML = response.message;
}

const clearMessage = (div) => {
    div.classList = "ui message hidden";
    div.getElementsByClassName('header')[0].innerHTML = "";
    div.getElementsByClassName('content')[0].innerHTML = "";
}

const ajaxCallback = (data, globalCallback, successCallback = null, errorCallback = null) => {
    globalCallback();
    if(data.type == "success" && successCallback){
        successCallback();
    }
    else if(data.type == "error" && errorCallback){
        errorCallback();
    }
}

const toggleVisibility = (div) => {
    div.classList.toggle('hidden');
}

const closeMessage = (div) => {
    const parent = div.parentNode;
    toggleVisibility(parent)
}
const closes = $(".close.icon");
closes.each((index, close) => close.addEventListener('click', () => closeMessage(close) , false));