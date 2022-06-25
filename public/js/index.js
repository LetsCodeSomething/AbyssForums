let loginTextBox, passwordTextBox, loginButton;

function processPageLoad()
{
    loginTextBox = document.getElementById("loginTextBox");
    passwordTextBox = document.getElementById("passwordTextBox");
    loginButton = document.getElementById("loginButton");
}

function processTextChange()
{
    let login = loginTextBox.value;
    let password = passwordTextBox.value;

    if(!login || !password)
    {
        loginButton.disabled = true;
        return;
    }
    loginButton.disabled = false;
}