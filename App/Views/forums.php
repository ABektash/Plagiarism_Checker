<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Chat Interface</title>
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/forums.css">
</head>

<body>
    <?php include 'inc/header.php'; ?>

    <div class="app-container">
        <div id="chat-list-container" class="chat-list">
            <div class="chat-list-header">
                <h2>Chats</h2>
            </div>
            <div id="chat-items" class="chat-items">
                <div class="chat-item" data-chat="user1">
                    <p class="chat-name">User 1</p>
                    <p class="chat-preview">Hello! How are you?</p>
                </div>
                <div class="chat-item" data-chat="user2">
                    <p class="chat-name">User 2</p>
                    <p class="chat-preview">Let’s meet tomorrow.</p>
                </div>
                <div class="chat-item" data-chat="user3">
                    <p class="chat-name">User 3</p>
                    <p class="chat-preview">Where are you?</p>
                </div>
            </div>
        </div>

        <div class="chat-container">
            <div class="chat-header">
                <button id="toggle-chat-list" class="toggle-sidebar-btn">☰</button>
                <h2 id="chat-title">Select a Chat</h2>
            </div>

            <div id="chat-box" class="chat-box">
                <?php
                // Static data array, formatted as if coming from the backend
                $messages = [
                    ['sender' => 'left', 'message' => 'Hi, how are you?', 'time' => '10:15 AM'],
                    ['sender' => 'right', 'message' => "I'm good, thanks! What about you?", 'time' => '10:16 AM'],
                    ['sender' => 'left', 'message' => "I'm working on a project.", 'time' => '10:17 AM'],
                    ['sender' => 'right', 'message' => "That's great! Let me know if you need help.", 'time' => '10:18 AM'],
                ];

                
                foreach ($messages as $msg) {
                    $senderClass = htmlspecialchars($msg['sender']);
                    $messageText = htmlspecialchars($msg['message']);
                    $sentTime = htmlspecialchars($msg['time']);
                    $isRead = $senderClass === 'right' ? '<span class="message-status read">✔✔</span>' : '';

                    echo "<div class='chat-message $senderClass'>
                            <div class='message-text'>
                                $messageText
                            </div>
                            <div class='message-info'>
                                <span class='message-time'>$sentTime</span>
                                $isRead
                            </div>
                          </div>";
                }
                ?>
            </div>

            <div class="chat-input">
                <input type="text" id="message-input" placeholder="Type a message..." />
                <button id="send-btn">Send</button>
            </div>
        </div>
    </div>

    <script src="/Plagiarism_Checker/public/assets/js/forums.js"></script>
</body>

</html>
