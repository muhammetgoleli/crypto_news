<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crypto News</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div id="app" class="container mt-5">
    <div class="card p-4">
        <h2 class="mb-4">Crypto News</h2>

        <form @submit.prevent="fetchNews">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Select Coin</label>
                    <select v-model="filters.coin" class="form-select">
                        <option value="">All Coins</option>
                        <option v-if="coins.length === 0" disabled>Loading coins...</option>
                        <option v-for="coin in coins" :key="coin" :value="coin">@{{ coin }}</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Start Date</label>
                    <input type="date" v-model="filters.start_date" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">End Date</label>
                    <input type="date" v-model="filters.end_date" class="form-control">
                </div>
            </div>
            <div class="d-flex justify-content-start mt-3">
                <button type="submit" class="btn btn-primary me-2">Filter</button>
                <button type="button" class="btn btn-secondary" @click="resetFilters">Clear</button>
            </div>

        </form>

        <div v-if="loading" class="text-center my-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

        <div v-else>
            <table class="table table-striped mt-4">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Published At</th>
                    <th>Coin</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(newsItem, index) in paginatedNews" :key="index">
                    <td>@{{ index + 1 }}</td>
                    <td>@{{ newsItem.title }}</td>
                    <td>@{{ newsItem.date }}</td>
                    <td>@{{ newsItem.coin }}</td>
                </tr>
                </tbody>
            </table>

            <nav v-if="totalPages > 1" class="mt-3">
                <ul class="pagination justify-content-center">
                    <li class="page-item" :class="{ disabled: currentPage === 1 }">
                        <a class="page-link" href="#" @click.prevent="changePage(currentPage - 1)">Previous</a>
                    </li>
                    <li class="page-item" v-for="page in totalPages" :key="page" :class="{ active: currentPage === page }">
                        <a class="page-link" href="#" @click.prevent="changePage(page)">@{{ page }}</a>
                    </li>
                    <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                        <a class="page-link" href="#" @click.prevent="changePage(currentPage + 1)">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Vue.js -->
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
<script>
    new Vue({
        el: '#app',
        data: {
            coins: [],
            filters: {
                coin: '',
                start_date: '',
                end_date: '',
            },
            news: [],
            loading: false,
            currentPage: 1,
            perPage: 20,
        },
        computed: {
            totalPages() {
                return Math.ceil(this.news.length / this.perPage);
            },
            paginatedNews() {
                const start = (this.currentPage - 1) * this.perPage;
                const end = start + this.perPage;
                return this.news.slice(start, end);
            },
        },
        methods: {
            fetchNews() {
                this.loading = true;
                const params = {
                    coin: this.filters.coin,
                    start_date: this.filters.start_date,
                    end_date: this.filters.end_date,
                };

                $.ajax({
                    url: '{{ route('news') }}',
                    method: 'GET',
                    data: params,
                    success: (response) => {
                        this.news = response.news;
                        this.loading = false;
                    },
                    error: (error) => {
                        console.error('Error fetching news:', error);
                        this.loading = false;
                    },
                });
            },
            fetchCoins() {
                $.ajax({
                    url: '{{ route('coins') }}',
                    method: 'GET',
                    success: (response) => {
                        this.coins = response.coins;
                    },
                    error: (error) => {
                        console.error('Error fetching coins:', error);
                    },
                });
            },
            changePage(page) {
                if (page > 0 && page <= this.totalPages) {
                    this.currentPage = page;
                }
            },
            resetFilters() {
                this.filters = {
                    coin: '',
                    start_date: '',
                    end_date: '',
                };
                this.fetchNews();
            },
        },
        mounted() {
            this.fetchCoins();
            this.fetchNews();
        },
    });
</script>
</body>
</html>
