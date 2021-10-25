let offset = 0;

const buttons = $(".toggleInputVisibility");
buttons.each((index, button) => button.addEventListener('click', () => toggleInputVisibility(button) , false));

const generateButton = document.getElementById("generatePassword");
if(generateButton){
    generateButton.addEventListener('click',function(){
            const generatedPassword = createRandomPassword(8);
            $('input[name*="password"]').each((index,input)=> { input.value = generatedPassword;input.type = "text";} )
        });
}

const success = () => {

}