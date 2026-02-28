<template>
    <div class="dashboard">

        <h1 class="title">Báo cáo thống kê</h1>

        <!-- ===== HÀNG THỐNG KÊ ===== -->
        <div class="stat-row">
            <div class="stat-box">
                <div class="stat-label">Tổng số sách</div>
                <div class="stat-value">{{ stats.totalBooks }}</div>
                <div class="stat-sub">Sách trong thư viện</div>
            </div>

            <div class="stat-box">
                <div class="stat-label">Sách đang được mượn</div>
                <div class="stat-value">{{ stats.borrowedBooks }}</div>
                <div class="stat-sub">Đang lưu hành</div>
            </div>

            <div class="stat-box">
                <div class="stat-label">Sách quá hạn</div>
                <div class="stat-value">{{ stats.overdueBooks }}</div>
                <div class="stat-sub">Chưa trả</div>
            </div>

            <div class="stat-box">
                <div class="stat-label">Tổng người dùng</div>
                <div class="stat-value">{{ stats.totalUsers }}</div>
                <div class="stat-sub">Người dùng đăng ký</div>
            </div>
        </div>

        <!-- ===== BẢNG DANH SÁCH ===== -->
        <div class="table-card">
            <div class="table-title">Danh sách mượn sách gần đây</div>
            <table>
                <thead>
                    <tr>
                        <th>STT</th>
                        <th class="text-start ms-2">TÊN SÁCH</th>
                        <th>NGƯỜI MƯỢN</th>
                        <th>NGÀY MƯỢN</th>
                        <th>TRẠNG THÁI</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(item, index) in recentBorrows" :key="index">
                        <tr>
                            <td>{{ index + 1 }}</td>
                            <td class="text-start ms-2">{{ item.book_title }}</td>
                            <td>{{ item.user_name }}</td>
                            <td>{{ formatDate(item.borrow_date) }}</td>
                            <td class="text-center">
                                <span v-if="item.status == 1" class="badge badge-borrowing">Đang mượn</span>
                                <span v-if="item.status == 2" class="badge badge-returned">Đã trả</span>
                                <span v-if="item.status == 3" class="badge badge-overdue">Quá hạn</span>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</template>
<script>
import axios from 'axios';
export default {
    data() {
        return {
            // Dữ liệu mẫu, sẽ được thay thế bằng dữ liệu thực tế từ backend
            stats: {
                totalBooks: 0,
                borrowedBooks: 0,
                overdueBooks: 0,
                totalUsers: 0
            },
            recentBorrows: [
                // Mảng này sẽ chứa các đối tượng mượn sách gần đây
            ]
        }
    },
    mounted() {
        this.getSumary();
        this.getRecentBorrows();
    },
    methods: {

        // Các phương thức để lấy dữ liệu từ backend sẽ được thêm vào đây
        formatDate(dateString) {
            if (!dateString) return '';
            return dateString.split(' ')[0];
        },
        getSumary() {
            axios.get('http://127.0.0.1:8000/api/admin/thongke/summary')
                .then(res => {
                    this.stats.totalBooks = res.data.total_books;
                    this.stats.borrowedBooks = res.data.borrowed_books;
                    this.stats.overdueBooks = res.data.overdue_books;
                    this.stats.totalUsers = res.data.total_users;
                })
                .catch(error => {
                    console.error('Lỗi khi lấy dữ liệu thống kê:', error);
                });
        },
        getRecentBorrows() {
            axios.get('http://127.0.0.1:8000/api/admin/thongke/recent-borrow')
                .then(res => {
                    this.recentBorrows = res.data.data;
                })
                .catch(error => {
                    console.error('Lỗi khi lấy dữ liệu mượn sách gần đây:', error);
                });

        }
    }
};
</script>
<style>
.dashboard {
    padding: 20px;
    font-family: Arial, sans-serif;
}

.title {
    font-size: 26px;
    font-weight: bold;
    margin-bottom: 20px;
}

/* ==== STAT BOX ==== */
.stat-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-box {
    background: #fff;
    border-radius: 10px;
    padding: 18px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.stat-label {
    font-size: 14px;
    color: #555;
}

.stat-value {
    font-size: 28px;
    font-weight: bold;
    margin: 8px 0;
}

.stat-sub {
    font-size: 12px;
    color: #999;
}

/* ==== TABLE ==== */
.table-card {
    background: #fff;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.table-title {
    font-weight: bold;
    margin-bottom: 15px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th,
td {
    padding: 10px;
    text-align: center;
    border-bottom: 1px solid #eee;
}

th {
    background: #f4f6f9;
    font-size: 13px;
    color: #555;
}
.badge {
    display: inline-block;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
}

/* Đang mượn - xanh dương nhạt */
.badge-borrowing {
    background-color: #e8f0ff;
    color: #2f6fed;
}

/* Quá hạn - đỏ nhạt */
.badge-overdue {
    background-color: #fdeaea;
    color: #d93025;
}

/* Đã trả - xám nhạt */
.badge-returned {
    background-color: #f1f3f5;
    color: #6c757d;
}
</style>