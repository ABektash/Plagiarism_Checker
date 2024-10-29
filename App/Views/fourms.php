<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fourms</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/fourms.css">
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/header.css">
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/footer.css">
</head>

<body>
    <?php include 'inc/header.php'; ?>
    <div id="chatContainer">
        <div id="contactList">
            <div class="contact">
                <span class="contactName">Chat 1</span>
                <span class="contactStatus">Just now</span>
            </div>
            <div class="contact">
                <span class="contactName">Chat 2</span>
                <span class="contactStatus">Typing...</span>
            </div>
            <!-- Add more contacts dynamically here -->
        </div>
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
                <button id="sendMessage" class="mdi mdi-chat"></button>
            </div>
        </div>
    </div>
    <?php include 'inc/footer.php'; ?>

    <script src="/Plagiarism_Checker/public\assets\js\forums.js"></script>
</body>

</html>
