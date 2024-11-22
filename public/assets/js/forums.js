// const chatListContainer = document.getElementById('chat-list-container');
// const chatItems = document.querySelectorAll('.chat-item');
// const chatTitle = document.getElementById('chat-title');
// const chatBox = document.getElementById('chat-box');
// const messageInput = document.getElementById('message-input');
// const sendBtn = document.getElementById('send-btn');
// const toggleChatListBtn = document.getElementById('toggle-chat-list');
// const chatContainer = document.querySelector('.chat-container');

// const updateChatTitle = (title) => {
//     if (chatTitle) chatTitle.innerText = title;
// };

// const toggleSidebar = () => {
//     if (window.innerWidth <= 768) {
//         const isHidden = chatListContainer.classList.toggle('hidden');
//         chatContainer.classList.toggle('full', isHidden);
//     }
// };

// const ensureSidebarVisibility = () => {
//     if (window.innerWidth > 768) {
//         chatListContainer.classList.remove('hidden');
//         chatContainer.classList.remove('full');
//     }
// };

// const sendMessage = () => {
//     const message = messageInput.value.trim();
//     if (message && chatBox) {
//         const newMessage = document.createElement('div');
//         newMessage.classList.add('chat-message', 'right');
//         newMessage.innerHTML = `${message}
//             <span class="message-status read">&#10003;&#10003;</span>`;

//         chatBox.appendChild(newMessage);
//         messageInput.value = '';
//         chatBox.scrollTop = chatBox.scrollHeight; // Auto-scroll
//     }
// };

// chatItems.forEach((item) => {
//     item.addEventListener('click', () => {
//         const chatName = item.querySelector('.chat-name')?.innerText;
//         updateChatTitle(chatName || 'Chat');

//         toggleSidebar();
//     });
// });

// if (sendBtn) sendBtn.addEventListener('click', sendMessage);
// if (messageInput) {
//     messageInput.addEventListener('keypress', (e) => {
//         if (e.key === 'Enter') {
//             e.preventDefault();
//             sendMessage();
//         }
//     });
// }

// if (toggleChatListBtn) {
//     toggleChatListBtn.addEventListener('click', toggleSidebar);
// }

// window.addEventListener('resize', ensureSidebarVisibility);

// ensureSidebarVisibility();




document.addEventListener('DOMContentLoaded', () => {
    const chatListContainer = document.getElementById('chat-list-container');
    const chatContainer = document.querySelector('.chat-container');
    const chatBox = document.getElementById('chat-box');
    const chatTitle = document.getElementById('chat-title');
    const toggleChatListBtn = document.getElementById('toggle-chat-list');
    const messageForm = document.getElementById('message-form');
    const messageInput = document.getElementById('message-input');
    const forumIdInput = document.getElementById('forum-id');

    const toggleSidebar = () => {
        if (window.innerWidth <= 768) {
            const isHidden = chatListContainer.classList.toggle('hidden');
            chatContainer.classList.toggle('full', isHidden);
        }
    };

    const ensureSidebarVisibility = () => {
        if (window.innerWidth > 768) {
            chatListContainer.classList.remove('hidden');
            chatContainer.classList.remove('full');
        }
    };

    const highlightChatItem = (forumID) => {
        const chatItems = document.querySelectorAll('.chat-item');
        chatItems.forEach((item) => {
            if (item.dataset.chat === forumID) {
                item.classList.add('active');
            } else {
                item.classList.remove('active');
            }
        });
    };

    const updateChatTitle = (title) => {
        chatTitle.textContent = title || 'Select a Chat';
    };

    const loadChat = async (forumID) => {
        chatBox.innerHTML = '<p>Loading messages...</p>';
        forumIdInput.value = forumID;

        try {
            const response = await fetch(`/Plagiarism_Checker/public/Forums/submit?submitGetForum=true&forumID=${forumID}`, {
                method: 'GET',
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            if (data.error) {
                chatBox.innerHTML = `<p>${data.error}</p>`;
                return;
            }

            chatBox.innerHTML = '';
            const messages = data.messages;
            const UserID = parseInt(data.UserID, 10);
            const UserType_id = parseInt(data.UserType_id, 10);
            
            let chatname;
            if (UserType_id == 2) {
                chatname = data.studentName;
            } else if (UserType_id == 3) {
                chatname = `Dr. ${data.instructorName}`;
            } else {
                chatname = 'Unknown Participant';
            }
    
            updateChatTitle(chatname);
            

            if (messages.length === 0) {
                chatBox.innerHTML = '<p>No messages available. Start the conversation!</p>';
                return;
            }

            messages.forEach((msg) => {
                const senderClass = parseInt(msg.SenderID, 10) === UserID ? 'right' : 'left';
                const sentTime = msg.sentat !== 'Unknown time' && msg.sentat? (() => {
                        const fullTime = new Date(msg.sentat);
                        let hours = fullTime.getHours();
                        const minutes = fullTime.getMinutes().toString().padStart(2, '0');
                        const ampm = hours >= 12 ? 'PM' : 'AM';
                        hours = hours % 12 || 12;
                        return `${hours}:${minutes} ${ampm}`;
                    })(): 'Unknown time';

                const messageHTML = `
                    <div class="chat-message ${senderClass}">
                        <div class="message-text">${msg.Messagetext}</div>
                        <div class="message-info">
                            <span class="message-time">${sentTime}</span>
                            ${senderClass === 'right' ? '<span class="message-status read">✔✔</span>' : ''}
                        </div>
                    </div>`;
                chatBox.innerHTML += messageHTML;
            });

            // Scroll to the bottom of the chat box
            chatBox.scrollTop = chatBox.scrollHeight;
        } catch (error) {
            console.error('Fetch Error:', error);
            chatBox.innerHTML = '<p>Error loading messages. Please try again later.</p>';
        }
    };


    window.loadChat = loadChat;

    const sendMessage = async (event) => {
        event.preventDefault();

        const message = messageInput.value.trim();
        const forumID = forumIdInput.value;

        if (message) {
            const formData = new FormData();
            formData.append('messagetext', message);
            formData.append('forumID', forumID);
            formData.append('senderID', messageForm.querySelector('[name="senderID"]').value);
            formData.append('submitCreateMessage', 'true');

            try {
                const response = await fetch('/Plagiarism_Checker/public/Forums/submit', {
                    method: 'POST',
                    body: formData,
                });

                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    const responseText = await response.text();
                    console.error('Unexpected Response:', responseText);
                    throw new Error('Unexpected response format');
                }

                const data = await response.json();

                if (data.success) {
                    messageInput.value = '';
                    loadChat(forumID);
                } else {
                    console.error('Backend Error:', data.error);
                }
            } catch (error) {
                console.error('Fetch Error:', error);
            }
        } 
    };

    if (messageForm) {
        messageForm.addEventListener('submit', sendMessage);
    }

    if (toggleChatListBtn) {
        toggleChatListBtn.addEventListener('click', toggleSidebar);
    }

    window.addEventListener('resize', ensureSidebarVisibility);
    ensureSidebarVisibility();
});









