let loginTextBox, passwordTextBox, registerButton;

function processPageLoad()
{
    loginTextBox = document.getElementById("loginTextBox");
    passwordTextBox = document.getElementById("passwordTextBox");
    registerButton = document.getElementById("registerButton");
}

function processTextChange()
{
    let login = loginTextBox.value;
    let password = passwordTextBox.value;

    if(!login || !password)
    {
        registerButton.disabled = true;
        return;
    }
    registerButton.disabled = false;
}