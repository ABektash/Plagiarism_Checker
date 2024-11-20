const chatListContainer = document.getElementById('chat-list-container');
const chatItems = document.querySelectorAll('.chat-item');
const chatTitle = document.getElementById('chat-title');
const chatBox = document.getElementById('chat-box');
const messageInput = document.getElementById('message-input');
const sendBtn = document.getElementById('send-btn');
const toggleChatListBtn = document.getElementById('toggle-chat-list');
const chatContainer = document.querySelector('.chat-container');

const updateChatTitle = (title) => {
    if (chatTitle) chatTitle.innerText = title;
};

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

const sendMessage = () => {
    const message = messageInput.value.trim();
    if (message && chatBox) {
        const newMessage = document.createElement('div');
        newMessage.classList.add('chat-message', 'right');
        newMessage.innerHTML = `${message}
            <span class="message-status read">&#10003;&#10003;</span>`;

        chatBox.appendChild(newMessage);
        messageInput.value = '';
        chatBox.scrollTop = chatBox.scrollHeight; // Auto-scroll
    }
};

chatItems.forEach((item) => {
    item.addEventListener('click', () => {
        const chatName = item.querySelector('.chat-name')?.innerText;
        updateChatTitle(chatName || 'Chat');

        toggleSidebar();
    });
});

if (sendBtn) sendBtn.addEventListener('click', sendMessage);
if (messageInput) {
    messageInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            sendMessage();
        }
    });
}

if (toggleChatListBtn) {
    toggleChatListBtn.addEventListener('click', toggleSidebar);
}

window.addEventListener('resize', ensureSidebarVisibility);

ensureSidebarVisibility();




// const chatListContainer = document.getElementById('chat-list-container');
// const messageInput = document.getElementById('message-input');
// const sendBtn = document.getElementById('send-btn');
// const toggleChatListBtn = document.getElementById('toggle-chat-list');
// const chatContainer = document.querySelector('.chat-container');
// const chatBox = document.getElementById('chat-box');

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



// document.getElementById('editForm').addEventListener('submit', function (event) {
//     event.preventDefault();

//     let formData = new FormData(this);

//     fetch('/Plagiarism_Checker/App/Controllers/editUserValidation.php', {
//         method: 'POST',
//         body: formData
//     })
//         .then(response => response.json())
//         .then(data => {
//             document.getElementById('edit-fname-error').textContent = '';
//             document.getElementById('edit-lname-error').textContent = '';
//             document.getElementById('edit-email-error').textContent = '';
//             document.getElementById('edit-organizationName-error').textContent = '';
//             document.getElementById('edit-address-error').textContent = '';
//             document.getElementById('edit-phone-error').textContent = '';
//             document.getElementById('edit-birthday-error').textContent = '';
//             document.getElementById('edit-password-error').textContent = '';
//             document.getElementById('edit-userType-error').textContent = '';

//             if (data.errors) {
//                 if (data.errors.firstNameError) {
//                     document.getElementById('edit-fname-error').textContent = data.errors.firstNameError;
//                 }
//                 if (data.errors.lastNameError) {
//                     document.getElementById('edit-lname-error').textContent = data.errors.lastNameError;
//                 }
//                 if (data.errors.emailError) {
//                     document.getElementById('edit-email-error').textContent = data.errors.emailError;
//                 }
//                 if (data.errors.organizationNameError) {
//                     document.getElementById('edit-organizationName-error').textContent = data.errors.organizationNameError;
//                 }
//                 if (data.errors.addressError) {
//                     document.getElementById('edit-address-error').textContent = data.errors.addressError;
//                 }
//                 if (data.errors.phoneError) {
//                     document.getElementById('edit-phone-error').textContent = data.errors.phoneError;
//                 }
//                 if (data.errors.birthdayError) {
//                     document.getElementById('edit-birthday-error').textContent = data.errors.birthdayError;
//                 }
//                 if (data.errors.passwordError) {
//                     document.getElementById('edit-password-error').textContent = data.errors.passwordError;
//                 }
//                 if (data.errors.userTypeError) {
//                     document.getElementById('edit-userType-error').textContent = data.errors.userTypeError;
//                 }
//             } else if (data.success) {
//                 closeEditForm();
//                 location.reload();
//             }
//         })
//         .catch(error => {
//             console.error('Error:', error);
//         });
// });


