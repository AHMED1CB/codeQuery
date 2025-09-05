# 💡 CodeQuery - Programming Q&A Platform

**CodeQuery** is a specialized **Q&A platform for developers**.  
It allows programmers to ask coding questions, share answers, and earn reputation through community-driven voting and badges.

---

## 🚀 Features

- 👤 **Authentication**  
  - User registration, login, and logout.  
  - Profile page with avatar and bio.  

- ❓ **Questions**  
  - Post new questions with title, body, and tags.  
  - Browse and search questions by keywords or tags.  
  - View counter for each question.  

- 💬 **Answers**  
  - Submit answers to questions.  
  - Mark an answer as "Accepted".  
  - Order answers by votes.  

- 🏷 **Tags**  
  - Use tags like [php], [javascript], [python].  
  - Dedicated page for each tag with related questions.  

- 👍 **Voting & Reputation**  
  - Upvote/downvote questions and answers.  
  - Reputation system for active contributors.  
  - Badges (Bronze, Silver, Gold) for milestones.  

- 💬 **Comments**  
  - Comment on questions and answers.  

- 🔔 **Notifications**  
  - Get notified when someone answers your question.  
  - Get notified when your answer is accepted or upvoted.  

---

## 📌 API Routes (Example)

> Example routes in RESTful API style. Will Edit on Future

### 🔑 Authentication
- `POST /register` → Create a new account.  
- `POST /login` → User login.  
- `POST /logout` → User logout.  
- `GET /profile/{id}` → View user profile.  
- `PUT /profile/{id}` → Update profile.  

### ❓ Questions
- `GET /questions` → List all questions.  
- `GET /questions/{id}` → View question details.  
- `POST /questions` → Post a new question.  
- `PUT /questions/{id}` → Edit a question.  
- `DELETE /questions/{id}` → Delete a question.  

### 💬 Answers
- `POST /questions/{id}/answers` → Add an answer.  
- `PUT /answers/{id}` → Edit an answer.  
- `DELETE /answers/{id}` → Delete an answer.  
- `POST /answers/{id}/accept` → Accept an answer.  

---

## ⚙️ Tech Stack

- **Backend:** PHP And Libraries  
- **Database:** MySQL/MariaDB.  
- **Frontend:** HTML, CSS, JavaScript, Tailwind , sweetalert , highlightjs

---

## 📜 License
MIT-License
Open-source project — feel free to use, modify, and improve 🚀- (Im Using Ai To Improve The Ui)
