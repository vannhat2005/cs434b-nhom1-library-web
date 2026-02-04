<template>
  <div class="home-container">
    
    <div class="header-section">
      <div class="welcome-text">
        <h1>Thư Viện Trực Tuyến</h1>
        <p>Tìm kiếm và gửi yêu cầu mượn sách nhanh chóng.</p>
      </div>
      
      <div class="search-wrapper">
        <input 
          type="text" 
          v-model="searchQuery" 
          placeholder="Nhập tên sách hoặc tác giả để tìm..." 
          class="search-input"
        />
        <button class="search-btn">🔍</button>
      </div>
    </div>

    <div class="content-section">
      <h2 class="section-title">
        <span v-if="!searchQuery">📚 Sách nổi bật</span>
        <span v-else>🔎 Kết quả tìm kiếm cho: "{{ searchQuery }}"</span>
      </h2>
      
      <div v-if="filteredBooks.length === 0" class="no-result">
        <p>Không tìm thấy cuốn sách nào phù hợp!</p>
      </div>

      <div v-else class="book-grid">
        <div v-for="book in filteredBooks" :key="book.id" class="book-card">
          <div class="card-img-wrapper">
             <img :src="book.img" alt="Book Cover" class="book-img" />
             <span class="tag" :class="book.categoryClass">{{ book.category }}</span>
          </div>
          <div class="book-info">
            <h3 class="book-title">{{ book.title }}</h3>
            <p class="book-author">{{ book.author }}</p>
            <div class="stars">
              <span v-for="n in 5" :key="n" :class="{ 'gold': n <= book.stars, 'gray': n > book.stars }">★</span>
            </div>
            
            <button @click="openModal(book)" class="btn-borrow-card">
              📖 Yêu cầu mượn
            </button>
          </div>
        </div>
      </div>
    </div>

    <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
      <div class="modal-content scale-in">
        
        <div class="modal-header">
          <h3>GỬI YÊU CẦU MƯỢN SÁCH</h3>
          <span class="close-icon" @click="closeModal">&times;</span>
        </div>

        <div class="modal-body">
          <div class="selected-book-info" v-if="selectedBook">
            Bạn đang chọn mượn: <strong>{{ selectedBook.title }}</strong>
          </div>

          <form @submit.prevent="submitRequest">
            <div class="form-group">
              <label>Số lượng:</label>
              <input type="number" v-model="form.quantity" min="1" max="3" class="form-control" required>
            </div>

            <div class="form-group">
              <label>Ngày mượn:</label>
              <input type="date" v-model="form.borrowDate" class="form-control" required>
            </div>

            <div class="form-group">
              <label>Ngày trả dự kiến:</label>
              <input type="date" v-model="form.returnDate" class="form-control" required>
            </div>

            <div class="form-group">
              <label>Ghi chú:</label>
              <textarea 
                v-model="form.note" 
                class="form-control" 
                rows="3" 
                placeholder="Nhập ghi chú (nếu có)..."></textarea>
            </div>

            <div class="modal-actions">
              <button type="submit" class="btn-submit">[ Gửi yêu cầu ]</button>
              <button type="button" @click="closeModal" class="btn-cancel">[ Hủy ]</button>
            </div>
          </form>

          <p class="footer-note">* Yêu cầu sẽ được thủ thư duyệt trong 24h *</p>
        </div>
      </div>
    </div>

  </div>
</template>
<script>
export default {
    
}
</script>
<style>
    
</style>