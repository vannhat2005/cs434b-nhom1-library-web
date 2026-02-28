<template>
  <div>
    <div class="container">
      <h2>Quản lý yêu cầu mượn</h2>
      <p class="sub">Quản lý danh sách yêu cầu mượn sách từ người dùng</p>

      <div class="search-box">
        <input type="text" placeholder="Tìm kiếm theo tên sách, sinh viên...">
      </div>

      <table>
        <thead>
          <tr>
            <th>STT</th>
            <th>TÊN SÁCH</th>
            <th>NGƯỜI MƯỢN</th>
            <th>MÃ SV</th>
            <th>NGÀY YÊU CẦU</th>
            <th>TRẠNG THÁI</th>
            <th>THAO TÁC</th>
          </tr>
        </thead>
        <tbody>
          <template v-for="(item, index) in list_requests" :key="index">
            <tr>
              <td>{{ index + 1 }}</td>
              <td>{{ item.book_title }}</td>
              <td>{{ item.user_name }}</td>
              <td>SV00{{ item.user_id }}</td>
              <td>{{ formatDate(item.request_date) }}</td>
              <td>
                <span v-if="item.status == 0" class="status pending">Chờ duyệt</span>
                <span v-else-if="item.status == 1" class="status approved">Đã duyệt</span>
                <span v-else-if="item.status == 2" class="status reject">Từ chối</span>
              </td>

              <td class="actions">
                <i v-on:click="Object.assign(approve_request, item)" data-bs-toggle="modal"
                    data-bs-target="#detailModal" class="fa fa-eye"></i>
                <span>
                  <i v-on:click="Object.assign(approve_request, item)" data-bs-toggle="modal"
                    data-bs-target="#duyetModal" v-if="item.status == 0" class="fa fa-check"></i>
                  <i v-on:click="Object.assign(reject_request, item)" data-bs-toggle="modal"
                    data-bs-target="#deleteModal" v-if="item.status == 0" class="fa fa-xmark"></i>
                </span>
              </td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>
  </div>
  <!-- Modal Detail -->
  <div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Chi tiết yêu cầu mượn sách</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <b>Tên sách:</b> {{ approve_request.book_title }}<br>
          <b>Người mượn:</b> {{ approve_request.user_name }}<br>
          <b>Mã SV:</b> SV00{{ approve_request.user_id }}<br>
          <b>Ngày yêu cầu:</b> {{ formatDate(approve_request.request_date) }}<br>
          <b>Trạng thái:</b> 
            <span v-if="approve_request.status == 0" class="status pending">Chờ duyệt</span>
            <span v-else-if="approve_request.status == 1" class="status approved">Đã duyệt</span>
            <span v-else-if="approve_request.status == 2" class="status reject">Từ chối</span>
        </div>
        <!-- <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal" v-on:click="rejectRequest()">
            Từ chối
          </button>
          <button type="button" class="btn btn-success" data-bs-dismiss="modal" v-on:click="approveRequest()">
            Duyệt yêu cầu
          </button>
        </div> -->
      </div>
    </div>
  </div>

  <!-- Modal Duyệt -->
  <div class="modal fade" id="duyetModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Duyệt yêu cầu mượn</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="alert alert-danger" role="alert">
            Bạn có chắc chắn muốn duyệt yêu cầu mượn sách
            <strong>{{ approve_request.book_title }}</strong>?
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Đóng
          </button>
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal" v-on:click="approveRequest()">
            Xác nhận
          </button>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal Xóa -->
  <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Từ chối yêu cầu mượn sách</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="alert alert-danger" role="alert">
            Bạn có chắc chắn từ chối yêu cầu mượn sách
            <strong>{{ reject_request.book_title }}</strong>?
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Đóng
          </button>
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal" v-on:click="rejectRequest()">
            Xác nhận
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import axios from 'axios';
export default {
  data() {
    return {
      list_requests: [],
      approve_request: {},
      reject_request: {},
      detail_request: {},
    }
  },
  mounted() {
    this.getData();
  },
  methods: {

    // Các phương thức để lấy dữ liệu từ backend sẽ được thêm vào đây
    formatDate(dateString) {
      if (!dateString) return '';
      return dateString.split(' ')[0];
    },
    getData() {
      axios.get('http://127.0.0.1:8000/api/librarian/borrow-request/get-data')
        .then(res => {
          this.list_requests = res.data.data;
        })
        .catch(error => {
          console.error('Lỗi khi lấy dữ liệu yêu cầu mượn:', error);
        });
    },
    approveRequest() {
      axios.post('http://127.0.0.1:8000/api/librarian/borrow-request/update-data/' + this.approve_request.id, {})
        .then(res => {
          this.$toast.success(res.data.message);
          this.getData();
        })
        .catch(error => {
          console.error('Lỗi khi duyệt yêu cầu mượn:', error);
        });
    },
    rejectRequest() {
      axios.post('http://127.0.0.1:8000/api/librarian/borrow-request/reject-data/' + this.reject_request.id, {})
        .then(res => {
          this.$toast.success(res.data.message);
          this.getData();
        })
        .catch(error => {
          console.error('Lỗi khi từ chối yêu cầu mượn:', error);
        });
    }
  }
};
</script>
<style>
body {
  font-family: Arial, sans-serif;
  background: #f5f6f8;
  margin: 0;
}

.container {
  width: 100%;
  margin: 0px auto;
  background: white;
  padding: 20px 25px;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

h2 {
  margin-bottom: 5px;
}

.sub {
  color: #777;
  font-size: 14px;
  margin-bottom: 20px;
  border-bottom: solid;
}

.search-box {
  margin-bottom: 15px;
}

.search-box input {
  width: 300px;
  padding: 10px 14px;
  border-radius: 6px;
  border: 1px solid #ddd;
  outline: none;
}

table {
  width: 100%;
  border-collapse: collapse;
}

thead {
  background: #f0f2f5;
}

th,
td {
  padding: 12px 10px;
  text-align: left;
  border-bottom: 1px solid #eee;
  font-size: 14px;
}

.status {
  padding: 5px 10px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: bold;
}

.pending {
  background: #fff3cd;
  color: #856404;
}

.approved {
  background: #d4edda;
  color: #155724;
}

.reject {
  background: #f8d7da;
  color: #721c24;
}

.actions i {
  margin-right: 10px;
  cursor: pointer;
}

.actions .fa-eye {
  color: #007bff;
}

.actions .fa-check {
  color: green;
}

.actions .fa-xmark {
  color: red;
}
</style>