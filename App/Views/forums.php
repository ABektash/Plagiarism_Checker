<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fourms</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/forums.css">
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/header.css">
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/footer.css">
</head>

<body>
    <?php include 'inc/header.php'; ?>


    <main class="chatMain">
    <div id="chatContainer">
        <!-- <div id="contactList">
            <div class="contact">
                <span class="contactName">Chat 1</span>
                <span class="contactStatus">Just now</span>
            </div>
            <div class="contact">
                <span class="contactName">Chat 2</span>
                <span class="contactStatus">Typing...</span>
            </div>
           // Add more contacts dynamically here 
        </div> -->
        <div id="chatSection">
            <div id="chatHeader">
                <h2>Chat 1</h2>
                <div id="chatIcons">
                    <!-- Icons for settings or other actions -->
                </div>
            </div>
            <div id="chatMessages">
                <!-- Chat messages will be dynamically generated here -->
                 
            </div>
            <div id="chatInput">
                <input type="text" id="messageTextBox" placeholder="Type a message...">
                <button id="sendMessage"><i class="fa-solid fa-circle-right fa-lg"></i></button>
            </div>
        </div>
    </div>
    </main>


    <?php include 'inc/footer.php'; ?>

    
    <script>
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

    </script>
</body>

</html>
