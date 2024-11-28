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
                <?php if (!empty($allForums)): ?>
                    <?php foreach ($allForums as $forum): ?>
                        <div class="chat-item" data-chat="<?= htmlspecialchars($forum['ID']) ?>" onclick="loadChat(<?= $forum['ID'] ?>)">
                            <p class="chat-name">
                                <?php
                                switch ($_SESSION['user']['UserType_id']) {
                                    case 2:
                                        echo htmlspecialchars($forum['StudentName'] ?? 'Unknown');
                                        break;
                                    case 3:
                                        echo htmlspecialchars('Dr. ' . $forum['InstructorName'] ?? 'Unknown');
                                        break;
                                    default:
                                        echo htmlspecialchars('Unknown');
                                        break;
                                }
                                ?>
                            </p>
                            <p class="chat-preview">Last message at: <?= htmlspecialchars($forum['last_message_time'] ?? 'N/A') ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="text-align: center;">No chats exist</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="chat-container">
            <div class="chat-header">
                <button id="toggle-chat-list" class="toggle-sidebar-btn">☰</button>
                <h2 id="chat-title">Select a Chat</h2>
            </div>

            <div id="chat-box" class="chat-box">
            </div>

            <div class="chat-input">
                <form id="message-form" method="POST" action="/Plagiarism_Checker/public/Forums/submit">
                    <input type="hidden" id="forum-id" name="forumID" value="<?= htmlspecialchars($_GET['forumID'] ?? '') ?>">
                    <input type="hidden" name="senderID" value="<?= htmlspecialchars($_SESSION['user']['ID'] ?? '') ?>">
                    <input type="text" id="message-input" name="messagetext" placeholder="Type a message..." required autocomplete="off">
                    <button type="submit" name="submitCreateMessage" id="send-btn">Send</button>
                </form>
            </div>
        </div>
    </div>

    <script src="/Plagiarism_Checker/public/assets/js/forums.js"></script>
</body>

</html>
