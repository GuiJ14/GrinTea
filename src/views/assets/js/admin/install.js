let offset = 0;

const buttons = $(".toggleInputVisibility");
buttons.each((index, button) => button.addEventListener('click', () => toggleInputVisibility(button) , false));

formValidation($('#userCreationForm'),[
    ['email', [['empty','Veuillez entrer un email']]],
    ['password',[['empty','Veuillez entrer un mot de passe'],['minLength[8]','Le mot de passe doit faire au minimum 8 caractères']]],
    ['repeatPassword',[['match[password]','Les mots de passe renseignés ne correspondent pas'],['empty','Veuillez répéter votre mot de passe']]]
],function(){
    event.preventDefault();
    url = '/createUser';
    $('#userCreationForm').trigger('ajaxSubmit');
    $.ajax({'url':url,'method':'POST','data':$('#userCreationForm').serialize(),'async':true}).done( (data) => {
        $('#response').html( data );
        console.log(data);
        next();
    });
});

//document.getElementsByClassName('generatePassword')[0].addEventListener('click',() => this..value = CreateRandomPassword(12, true, true, true, '@#$%'));
