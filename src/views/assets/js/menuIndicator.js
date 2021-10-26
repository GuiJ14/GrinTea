const menuIndicator = (item) => {
    const menuIndicator = $("#menuIndicator");
    speedPerPx =  Math.abs($(item).offset().top - menuIndicator.offset().top);
    $("#menuIndicator").animate({ top: $(item).offset().top}, speedPerPx * 5);
}