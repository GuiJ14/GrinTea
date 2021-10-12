function formValidation(form, validators, onSuccess = false){
    let parsedValidator = {}
    for(let validator of validators){
        const rules = [];
        for(let rule of validator[1]){
            rules.push({ type : rule[0], prompt : rule[1]});
        }
        parsedValidator[validator[0]] = { identifier : validator[0], rules : rules };
    }
    if(!onSuccess){
        form.form({fields: parsedValidator});
    }
    else{
        form.form({fields : parsedValidator , onSuccess : onSuccess });
    }
}