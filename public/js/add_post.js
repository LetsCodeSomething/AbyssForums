let postNameTextBox, postTextTextArea, submitButton;

function processPageLoad()
{
    postNameTextBox = document.getElementById("postNameTextBox");
    postTextTextArea = document.getElementById("postTextTextArea");
    submitButton = document.getElementById("submitButton");
}

function processTextChange()
{
    let postName = postNameTextBox.value;
    let postText = postTextTextArea.value;

    if(!postName || !postText)
    {
        submitButton.disabled = true;
        return;
    }
    submitButton.disabled = false;
}