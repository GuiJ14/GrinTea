function next(){
    offset += 1;
    const translateX = 'translateX(-'+offset*100+'%)';
    $('.stepper_content').css('transform', translateX);
}

function previous(){
    offset -= 1;
    const translateX = 'translateX(-'+offset*100+'%)';
    $('.stepper_content').css('transform', translateX);
}