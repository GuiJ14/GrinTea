function CreateRandomPassword(Length, isUpperAlpha, isLowerAlpha, isNumeric ,SpecialChars) {
    let _allowedChars = "";
    if (isUpperAlpha != false)
        _allowedChars += "ABCDEFGHJKLMNOPQRSTUVWXYZ";
    if (isLowerAlpha != false)
        _allowedChars += "abcdefghijkmnopqrstuvwxyz";
    if (isNumeric != false)
        _allowedChars += "0123456789";
    _allowedChars += SpecialChars;
    if(!Length)
        Length = 8
    var chars = "";
    allowedCharCount = _allowedChars.length;
    if(allowedCharCount == 0)
        return " ";
    for (var i = 0; i < Length; i++)
    {
        chars += _allowedChars[Math.floor(Math.random() * Math.floor(allowedCharCount))];
    }
    return chars;
}