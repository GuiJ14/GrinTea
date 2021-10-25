const toggleInputVisibility = (button) => {
    field = button.parentNode;
    if( field.getElementsByTagName('input')[0].type == 'text' ){
        field.getElementsByTagName('input')[0].type = 'password';
        field.querySelectorAll('.button > i')[0].classList.remove('slash');
    }
    else{
        field.getElementsByTagName('input')[0].type = 'text';
        field.querySelectorAll('.button > i')[0].classList.add('slash');
    }
};
