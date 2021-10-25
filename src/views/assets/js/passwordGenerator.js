const createRandomPassword = (passwordLength = 8) => {
    let _allowedChars = "ABCDEFGHJKLMNOPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz0123456789@#*%$^";
    var chars = "";
    allowedCharCount = _allowedChars.length;
    if(allowedCharCount == 0)
        return " ";
    for (var i = 0; i < passwordLength; i++)
    {
        chars += _allowedChars[Math.floor(Math.random() * Math.floor(allowedCharCount))];
    }
    return chars;
}