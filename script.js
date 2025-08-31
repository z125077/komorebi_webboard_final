// Main JavaScript for Komorebi
class Komorebi {
    constructor() {
        this.currentPage = 1;
        this.postsPerPage = 6;
        this.currentCategory = 'all';
        this.posts = [];
        this.comments = [];
        this.init();
    }

async init() {
    await this.checkSession();
    await this.loadPosts();
    this.setupEventListeners();
    this.renderPosts();
}

async checkSession() {
    try {
        const response = await fetch('php/check_session.php');
        const data = await response.json();
        if (data.loggedIn) {
            document.getElementById('loginLink').style.display = 'none';
            document.getElementById('logoutLink').style.display = 'block';
        }
    } catch (error) {
        console.error('Session check failed:', error);
    }
}


    async loadPosts() {
        try {
            const response = await fetch('php/get_posts.php');
            const data = await response.json();
            this.posts = data.posts || [];
        } catch (error) {
            console.error('Error loading posts:', error);
            this.posts = [];
        }
    }

    async loadComments(postId) {
        try {
            const response = await fetch(`php/get_comments.php?post_id=${postId}`);
            const data = await response.json();
            return data.comments || [];
        } catch (error) {
            console.error('Error loading comments:', error);
            return [];
        }
    }

    setupEventListeners() {
        // Filter buttons
        const filterBtns = document.querySelectorAll('.filter-btn');
        filterBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                const category = e.target.dataset.category;
                this.filterPosts(category);
                
                // Update active button
                filterBtns.forEach(b => b.classList.remove('active'));
                e.target.classList.add('active');
            });
        });

        // Load more button
        const loadMoreBtn = document.getElementById('loadMoreBtn');
        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', () => {
                this.currentPage++;
                this.renderPosts(true);
            });
        }

        // Modal close
        const closeModal = document.getElementById('closeModal');
        const modal = document.getElementById('postModal');
        
        if (closeModal) {
            closeModal.addEventListener('click', () => {
                modal.style.display = 'none';
            });
        }

        if (modal) {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.style.display = 'none';
                }
            });
        }
    }

    filterPosts(category) {
        this.currentCategory = category;
        this.currentPage = 1;
        this.renderPosts();
    }

    getFilteredPosts() {
        if (this.currentCategory === 'all') {
            return this.posts;
        }
        return this.posts.filter(post => post.category === this.currentCategory);
    }

    renderPosts(append = false) {
        const container = document.getElementById('postsContainer');
        const filteredPosts = this.getFilteredPosts();
        const startIndex = append ? (this.currentPage - 1) * this.postsPerPage : 0;
        const endIndex = this.currentPage * this.postsPerPage;
        const postsToShow = filteredPosts.slice(startIndex, endIndex);

        if (!append) {
            container.innerHTML = '';
        }

        if (postsToShow.length === 0 && !append) {
            container.innerHTML = '<div class="no-posts">No stories found in this category.</div>';
            return;
        }

        postsToShow.forEach(post => {
            const postElement = this.createPostElement(post);
            container.appendChild(postElement);
        });

        // Update load more button visibility
        const loadMoreBtn = document.getElementById('loadMoreBtn');
        if (loadMoreBtn) {
            const hasMorePosts = endIndex < filteredPosts.length;
            loadMoreBtn.style.display = hasMorePosts ? 'block' : 'none';
        }
    }

    createPostElement(post) {
        const postDiv = document.createElement('div');
        postDiv.className = 'post-card';
        postDiv.dataset.postId = post.post_id;

        // Format date
        const date = new Date(post.created_at);
        const formattedDate = date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });

        postDiv.innerHTML = `
            <div class="post-header">
                <div>
                    <h3 class="post-title">${this.escapeHtml(post.title)}</h3>
                </div>
                <span class="post-category">${this.formatCategory(post.category)}</span>
            </div>
            <div class="post-content">
                ${this.escapeHtml(post.content)}
            </div>
            <div class="post-meta">
                <span class="post-author">by ${this.escapeHtml(post.author_name || 'Anonymous')}</span>
                <div class="post-stats">
                    <span class="stat">👁️ ${post.views || 0}</span>
                    <span class="stat">
                        <button class="like-btn" data-post-id="${post.post_id}">
                            ❤️
                        </button>
                        ${post.likes || 0}
                    </span>
                    <span class="stat">📅 ${formattedDate}</span>
                </div>
            </div>
        `;

        // Add click event for opening modal
        postDiv.addEventListener('click', (e) => {
            if (!e.target.classList.contains('like-btn')) {
                this.openPostModal(post);
            }
        });

        // Add like button event
        const likeBtn = postDiv.querySelector('.like-btn');
        likeBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            this.toggleLike(post.post_id, likeBtn);
        });

        return postDiv;
    }

    async openPostModal(post) {
        const modal = document.getElementById('postModal');
        const modalBody = document.getElementById('modalBody');

        // Increment view count
        await this.incrementViews(post.post_id);

        // Load comments
        const comments = await this.loadComments(post.post_id);

        // Format date
        const date = new Date(post.created_at);
        const formattedDate = date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });

        modalBody.innerHTML = `
            <h2 class="modal-post-title">${this.escapeHtml(post.title)}</h2>
            <div class="modal-post-meta">
                <div>
                    <span class="post-category">${this.formatCategory(post.category)}</span>
                    <span class="post-author">by ${this.escapeHtml(post.author_name || 'Anonymous')}</span>
                </div>
                <div class="post-stats">
                    <span class="stat">👁️ ${(post.views || 0) + 1}</span>
                    <span class="stat">❤️ ${post.likes || 0}</span>
                    <span class="stat">📅 ${formattedDate}</span>
                </div>
            </div>
            <div class="modal-post-content">
                ${this.escapeHtml(post.content).replace(/\n/g, '<br>')}
            </div>
            <div class="comments-section">
                <h3 class="comments-title">Comments (${comments.length})</h3>
                <div class="comments-list">
                    ${comments.map(comment => `
                        <div class="comment">
                            <div class="comment-author">${this.escapeHtml(comment.author_name)}</div>
                            <div class="comment-content">${this.escapeHtml(comment.content)}</div>
                            <div class="comment-date">${new Date(comment.created_at).toLocaleDateString()}</div>
                        </div>
                    `).join('')}
                </div>
                <div class="comment-form">
                    <h4>Add a Comment</h4>
                    <form id="commentForm">
                        <div class="form-group">
                            <label for="commentAuthor">Name:</label>
                            <input type="text" id="commentAuthor" name="author" required>
                        </div>
                        <div class="form-group">
                            <label for="commentContent">Comment:</label>
                            <textarea id="commentContent" name="content" required placeholder="Share your thoughts..."></textarea>
                        </div>
                        <button type="submit" class="submit-btn">Post Comment</button>
                    </form>
                </div>
            </div>
        `;

        // Setup comment form
        const commentForm = document.getElementById('commentForm');
        commentForm.addEventListener('submit', (e) => {
            e.preventDefault();
            this.submitComment(post.post_id);
        });

        modal.style.display = 'block';
    }

    async submitComment(postId) {
        const form = document.getElementById('commentForm');
        const formData = new FormData(form);
        formData.append('post_id', postId);

        try {
            const response = await fetch('php/add_comment.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();
            
            if (result.success) {
                // Reload the modal with new comments
                const post = this.posts.find(p => p.post_id == postId);
                if (post) {
                    await this.openPostModal(post);
                }
            } else {
                alert('Error posting comment. Please try again.');
            }
        } catch (error) {
            console.error('Error submitting comment:', error);
            alert('Error posting comment. Please try again.');
        }
    }

    async toggleLike(postId, btn) {
        try {
            const response = await fetch('php/toggle_like.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ post_id: postId })
            });

            const result = await response.json();
            
            if (result.success) {
                // Update the like count in the UI
                const postCard = btn.closest('.post-card');
                const likeSpan = postCard.querySelector('.stat:nth-child(2)');
                const currentCount = parseInt(likeSpan.textContent.split(' ')[1]) || 0;
                
                if (result.liked) {
                    btn.classList.add('liked');
                    likeSpan.innerHTML = `❤️ ${currentCount + 1}`;
                } else {
                    btn.classList.remove('liked');
                    likeSpan.innerHTML = `❤️ ${Math.max(0, currentCount - 1)}`;
                }

                // Update the post data
                const post = this.posts.find(p => p.post_id == postId);
                if (post) {
                    post.likes = result.liked ? (post.likes || 0) + 1 : Math.max(0, (post.likes || 0) - 1);
                }
            }
        } catch (error) {
            console.error('Error toggling like:', error);
        }
    }

    async incrementViews(postId) {
        try {
            await fetch('php/increment_views.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ post_id: postId })
            });

            // Update local post data
            const post = this.posts.find(p => p.post_id == postId);
            if (post) {
                post.views = (post.views || 0) + 1;
            }
        } catch (error) {
            console.error('Error incrementing views:', error);
        }
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    formatCategory(category) {
        return category.split(' ').map(word => 
            word.charAt(0).toUpperCase() + word.slice(1)
        ).join(' ');
    }
}

// Initialize the application when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new Komorebi();
});