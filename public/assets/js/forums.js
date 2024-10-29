
class Message {
constructor(parent, content, username, imageSrc = null) {
this.parent = parent;
this.content = content;
this.username = username;


this.messageElement = document.createElement('div');
this.messageElement.className='userMsg';

const usernameLabel = document.createElement('label');
usernameLabel.className = 'nameUser';
usernameLabel.textContent = this.username;


const contentLabel = document.createElement('label');
contentLabel.className = 'msgContent';
contentLabel.textContent = this.content;

this.messageElement.appendChild(usernameLabel);
if (imageSrc) {
    const imageElement = document.createElement('img');
    imageElement.src = imageSrc;
    imageElement.className = 'pic';
    this.messageElement.appendChild(imageElement);
}else
{
    const imageElement = document.createElement('img');
    imageElement.src = '/Plagiarism_Checker/public/assets/images/defaultpic.jpg';
    imageElement.className = 'pic';
    this.messageElement.appendChild(imageElement);
}
this.messageElement.appendChild(contentLabel);

this.parent.appendChild(this.messageElement);
}
}


const parentContainer = document.getElementById('chatMessages');

document.getElementById('sendMessage').addEventListener('click', () => {
if(document.getElementById('messageTextBox').value != '')
{
new Message(parentContainer, document.getElementById('messageTextBox').value, "User");

document.getElementById('messageTextBox').value = '';
}
});

