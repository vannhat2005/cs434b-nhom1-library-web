<template>
  <div class="search-page">
    <!-- TIÊU ĐỀ -->
    <h2 class="title">TRA CỨU - TÌM KIẾM SÁCH</h2>

    <!-- FORM TÌM KIẾM -->
    <div class="search-form">
      <div class="form-group">
        <label>Tên sách</label>
        <input v-model="searchParams.title" type="text" placeholder="Nhập tên sách" />
      </div>

      <div class="form-group">
        <label>Tác giả:</label>
        <input v-model="searchParams.author" type="text" placeholder="Nhập tên tác giả" />
      </div>

      <div class="form-group">
        <label>Thể loại:</label>
        <select v-model="searchParams.category_id">
          <option value="">-- Chọn thể loại --</option>
          <option v-for="category in list_category" :key="category.id" :value="category.id">
            {{ category.name }}
          </option>
        </select>
      </div>

      <div class="form-group">
        <label>Năm xuất bản</label>
        <input v-model="searchParams.publication_year" type="number" placeholder="Nhập năm" />
      </div>

      <!-- BUTTON -->
      <div class="button-group">
        <button class="btn-search" @click="searchBooks">Tìm kiếm</button>
        <button class="btn-reset" @click="resetSearch">Làm mới</button>
      </div>
    </div>

    <!-- KẾT QUẢ -->
    <h3 class="result-title">KẾT QUẢ TÌM KIẾM</h3>

    <table class="result-table">
      <thead>
        <tr>
          <th>STT</th>
          <th>Tên sách</th>
          <th>Tác giả</th>
          <th>Thể loại</th>
          <th>Năm XB</th>
          <th>Trạng thái</th>
          <th>Thao tác</th>
        </tr>
      </thead>

      <tbody>
        <template v-for="(book, index) in list_books" :key="index">
          <tr>
            <td>{{ index + 1 }}</td>
            <td>{{ book.title }}</td>
            <td>{{ book.author_name }}</td>
            <td>{{ book.category_name }}</td>
            <td>{{ book.publication_year }}</td>
            <td>
              <span v-if="book.stock_text == 'Còn sách'" class="status-available">Còn sách</span>
              <span v-else class="status-unavailable">Hết sách</span>
            </td>
            <td>
              <button class="btn-borrow" v-if="book.is_available == true">Mượn sách</button>
              <button class="btn-borrow" v-if="book.is_available == false" disabled>Không khả dụng</button>

            </td>
          </tr>
        </template>
      </tbody>
    </table>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      searchParams: {
        title: '',
        author: '',
        category_id: '',
        publication_year: ''
      },
      list_books: [],
      list_category: [],
    }
  },
  mounted() {
    this.getCategory();
  },
  methods: {
    getCategory() {
      axios.get('http://127.0.0.1:8000/api/librarian/category/get-data')
        .then(res => {
          this.list_category = res.data.data;
        });
    },
    searchBooks() {
      // Gọi API tìm kiếm sách với searchParams
      axios.post('http://127.0.0.1:8000/api/student/books/search', this.searchParams)
        .then(response => {
          this.list_books = response.data.data; // Giả sử API trả về mảng sách trong data
        })
        .catch(error => {
          console.error('Lỗi khi tìm kiếm sách:', error);
        });
    },
    resetSearch() {
      this.searchParams = {
        title: '',
        author: '',
        category_id: '',
        publication_year: ''
      };
    }
  }

};
</script>

<style scoped>
.search-page {
  padding: 20px;
  background: #f8f9fa;
}

.title {
  text-align: center;
  color: #d32f2f;
  margin-bottom: 20px;
}

.search-form {
  background: #ffffff;
  padding: 20px;
  border-radius: 6px;
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 15px;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.form-group label {
  font-weight: 600;
  margin-bottom: 5px;
}

.form-group input,
.form-group select {
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.button-group {
  grid-column: span 2;
  display: flex;
  gap: 12px;
}

/* Nút tìm kiếm */
.btn-search {
  background-color: #ffffff;
  color: #e53935;
  border: 1.5px solid #e53935;
  padding: 8px 16px;
  border-radius: 20px;
  font-weight: 600;
  cursor: pointer;
}

.btn-search:hover {
  background-color: #fdecea;
}

/* Nút làm mới */
.btn-reset {
  background-color: #ffffff;
  color: #e53935;
  border: 1.5px solid #e53935;
  padding: 8px 16px;
  border-radius: 20px;
  font-weight: 600;
  cursor: pointer;
}

.btn-reset:hover {
  background-color: #fdecea;
}

.result-title {
  margin: 25px 0 10px;
  font-weight: bold;
}

.result-table {
  width: 100%;
  border-collapse: collapse;
  background: #fff;
}

.result-table th,
.result-table td {
  border: 1px solid #ddd;
  padding: 10px;
  text-align: center;
}

.result-table th {
  background: #eeeeee;
}

.status-available {
  color: green;
  font-weight: bold;
}

.status-unavailable {
  color: red;
  font-weight: bold;
}

.btn-borrow {
  background: blue;
  color: #fff;
  border: none;
  padding: 6px 12px;
  border-radius: 4px;
  cursor: pointer;
}

.btn-borrow:disabled {
  background: #bdbdbd;
  cursor: not-allowed;
}
</style>
