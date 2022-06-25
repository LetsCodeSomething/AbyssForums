let commentTextTextBox, submitButton;

function processPageLoad()
{
    commentTextTextBox = document.getElementById("commentTextTextBox");
    submitButton = document.getElementById("submitButton");
}

function processTextChange()
{
    let commentText = commentTextTextBox.value;

    if(!commentText)
    {
        submitButton.disabled = true;
        return;
    }
    submitButton.disabled = false;
}