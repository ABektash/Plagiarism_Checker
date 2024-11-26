let lastForumLoad;
let lastMessageDate = null;

const toggleSidebar = () => {
    const chatListContainer = document.getElementById('chat-list-container');
    const chatContainer = document.querySelector('.chat-container');

    if (window.innerWidth <= 768) {
        const isHidden = chatListContainer.classList.toggle('hidden');
        chatContainer.classList.toggle('full', isHidden);
    }
};

const ensureSidebarVisibility = () => {
    const chatListContainer = document.getElementById('chat-list-container');
    const chatContainer = document.querySelector('.chat-container');

    if (window.innerWidth > 768) {
        chatListContainer.classList.remove('hidden');
        chatContainer.classList.remove('full');
    }
};

const highlightChatItem = (forumID) => {
    const chatItems = document.querySelectorAll('.chat-item');

    chatItems.forEach((item) => {

        if (item.dataset.chat == forumID) {
            item.classList.add('active');
            return;
        } else {
            item.classList.remove('active');
        }
    });
};

const updateChatTitle = (title) => {
    const chatTitle = document.getElementById('chat-title');
    chatTitle.textContent = title || 'Select a Chat';
};

function formatDate(date) {
    const day = date.getDate().toString().padStart(2, '0');
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
}

function getRelativeDate(date) {
    const today = new Date();
    const yesterday = new Date(today);
    yesterday.setDate(today.getDate() - 1);

    if (date.toDateString() === today.toDateString()) {
        return 'Today';
    } else if (date.toDateString() === yesterday.toDateString()) {
        return 'Yesterday';
    } else {
        return formatDate(date);
    }
}

const loadChat = async (forumID) => {
    if (lastForumLoad != forumID) {
        const chatBox = document.getElementById('chat-box');
        const forumIdInput = document.getElementById('forum-id');

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
            if (UserType_id === 2) {
                chatname = data.studentName;
            } else if (UserType_id === 3) {
                chatname = `Dr. ${data.instructorName}`;
            } else {
                chatname = 'Unknown Participant';
            }

            lastForumLoad = forumID;
            updateChatTitle(chatname);
            highlightChatItem(forumID);
            if (window.innerWidth <= 768) {
                toggleSidebar();
            }

            if (messages.length === 0) {
                chatBox.innerHTML = '<p>No messages available. Start the conversation!</p>';
                return;
            }

            messages.forEach((msg) => {
                const senderClass = parseInt(msg.SenderID, 10) === UserID ? 'right' : 'left';

                const sentTime = msg.sentat !== 'Unknown time' && msg.sentat
                    ? (() => {
                        const fullTime = new Date(msg.sentat);
                        let hours = fullTime.getHours();
                        const minutes = fullTime.getMinutes().toString().padStart(2, '0');
                        const ampm = hours >= 12 ? 'PM' : 'AM';
                        hours = hours % 12 || 12;
                        return `${hours}:${minutes} ${ampm}`;
                    })()
                    : 'Unknown time';

                const messageDate = new Date(msg.sentat);
                const relativeDate = getRelativeDate(messageDate);

                const messageHTML = `
                    ${lastMessageDate !== relativeDate ? `<div class="date-separator"><span>${relativeDate}</span></div>` : ''}
                    <div class="chat-message ${senderClass}">
                        <div class="message-content">
                            <div class="message-text">${msg.Messagetext}</div>        
                            <span class="message-info">
                                ${sentTime}
                                ${senderClass === 'right' ? (msg.Isread == 1 ? '<span class="message-status read">✔✔</span>' : '<span class="message-status">✔✔</span>') : ''}
                            </span>
                        </div>
                    </div>
                `;

                chatBox.innerHTML += messageHTML;

                lastMessageDate = relativeDate;
            });

            chatBox.scrollTop = chatBox.scrollHeight;
        } catch (error) {
            console.error('Fetch Error:', error);
            chatBox.innerHTML = '<p>Error loading messages. Please try again later.</p>';
        }
    } else {
        if (window.innerWidth <= 768) {
            toggleSidebar();
        }
    }
};

const sendMessage = async (event) => {
    event.preventDefault();

    const messageForm = document.getElementById('message-form');
    const messageInput = document.getElementById('message-input');
    const forumIdInput = document.getElementById('forum-id');

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

                if (forumID == lastForumLoad) {

                    const sentTime = new Date();
                    let hours = sentTime.getHours();
                    const minutes = sentTime.getMinutes().toString().padStart(2, '0');
                    const ampm = hours >= 12 ? 'PM' : 'AM';
                    hours = hours % 12 || 12;
                    const formattedTime = `${hours}:${minutes} ${ampm}`;

                    const currentDate = getRelativeDate(sentTime);

                    const msg = {
                        Messagetext: message,
                        senderID: messageForm.querySelector('[name="senderID"]').value,
                        time: formattedTime,
                    };

                    const senderClass = 'right';

                    let dateSeparatorHTML = '';
                    if (lastMessageDate !== currentDate) {
                        dateSeparatorHTML = `<div class="date-separator"><span>${currentDate}</span></div>`;
                        lastMessageDate = currentDate;
                    }

                    const messageHTML = `
                        ${dateSeparatorHTML}
                            <div class="chat-message ${senderClass}">
                                <div class="message-content">
                                    <div class="message-text">${msg.Messagetext}</div>        
                                    <span class="message-info">
                                        ${formattedTime}
                                        ${senderClass === 'right' ? '<span class="message-status">✔✔</span>' : ''}
                                    </span>
                                </div>
                            </div>`;

                    const chatBox = document.getElementById('chat-box');
                    chatBox.innerHTML += messageHTML;

                    chatBox.scrollTop = chatBox.scrollHeight;

                } else {
                    loadChat(forumID);
                }
            } else {
                console.error('Backend Error:', data.error);
            }
        } catch (error) {
            console.error('Fetch Error:', error);
        }
    }
};

const designInitialization = () => {
    const toggleChatListBtn = document.getElementById('toggle-chat-list');
    const messageForm = document.getElementById('message-form');
    const forumIdInput = document.getElementById('forum-id');

    if (toggleChatListBtn) {
        toggleChatListBtn.addEventListener('click', toggleSidebar);
    }

    if (messageForm) {
        messageForm.addEventListener('submit', sendMessage);
    }

    if (forumIdInput.value) {
        highlightChatItem(forumIdInput.value);
    }

    window.addEventListener('resize', ensureSidebarVisibility);
    ensureSidebarVisibility();

};


document.addEventListener('DOMContentLoaded', designInitialization);











