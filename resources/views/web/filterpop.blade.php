@include('web.Layout.head')
<style>
    /* Table Container */
    .table-container {
        overflow-x: auto;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 40px;
        border: 1px solid #eee;
    }

    .custom-pump-table {
        width: 100%;
        border-collapse: collapse;
        background-color: #fff;
        font-family: 'Segoe UI', Roboto, sans-serif;
    }

    /* Header - Modern Dark */
    .custom-pump-table thead {
        background-color: #1a1a1a;
        color: #ffffff;
    }

    .custom-pump-table th {
        padding: 15px 10px;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        border-bottom: 3px solid #b31f24; /* Branding Red */
        text-align: center;
    }

    /* Zebra Striping */
    .custom-pump-table tbody tr:nth-child(odd) {
        background-color: #ffffff;
    }

    .custom-pump-table tbody tr:nth-child(even) {
        background-color: #f7f7f7;
    }

    .custom-pump-table tbody tr:hover {
        background-color: #f1f1f1;
    }

    .custom-pump-table td {
        padding: 12px 10px;
        font-size: 14px;
        color: #333;
        text-align: center;
        border-bottom: 1px solid #efefef;
    }

    .model-cell {
        font-weight: 700;
        color: #b31f24 !important;
    }

    /* PDF Button */
    .pdf-trigger {
        display: inline-block;
        text-decoration: none;
        background-color: #333;
        color: white !important;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 11px;
        text-transform: uppercase;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: 0.3s;
    }

    .pdf-trigger:hover {
        background-color: #b31f24;
    }

    @media (max-width: 768px) {
        .custom-pump-table th, .custom-pump-table td { 
            font-size: 11px; 
            padding: 8px 5px; 
        }
    }
</style>

<body>
    @include('web.Layout.header')

    <div class="breadcumb-area breadcrumb-bg" style="padding: 20px 0;"> 
        <div class="container">
            <div class="breadcumb-inner">
                <ul class="page-lists">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li><a href="{{ url('/1/1/products/') }}">Products</a></li>
                </ul>
                <h5 class="title">See our Products</h5>
            </div>
        </div>
    </div>

    <div class="shop-page-area pd-top-40">
        <div class="container">
            <div class="table-container">
                <table class="custom-pump-table">
                    <thead>
                        <tr>
                            <th style="width: 70%;">Model</th>
                            <th style="width: 30%;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($res as $product)
                        <tr>
                            <td class="model-cell">{{ $product->name }}</td>
                            <td>
                                <a href="{{ $product->link }}" class="pdf-trigger" target="_blank">
                                    View PDF
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('web.Layout.footer')
</body>
</html>
