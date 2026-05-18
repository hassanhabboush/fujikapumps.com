<!-- ========================= -->
<!-- SELECTION TOOL RESULT TABLE  -->
<!-- ========================= -->



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
    }

    /* Zebra Striping - Closely related colors for eye comfort */
    .custom-pump-table tbody tr:nth-child(odd) {
        background-color: #ffffff;
    }

    .custom-pump-table tbody tr:nth-child(even) {
        background-color: #f7f7f7; /* Very light grey */
    }

    .custom-pump-table tbody tr:hover {
        background-color: #f1f1f1; /* Subtle hover highlight */
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
        text-decoration: none; /* Removes the underline */
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

    /* --- MODAL STYLES --- */
    .pdf-modal {
        display: none; 
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.8);
        backdrop-filter: blur(5px);
    }

    .modal-content {
        position: relative;
        background-color: #fefefe;
        margin: 2% auto;
        padding: 0;
        width: 80%;
        height: 85vh;
        border-radius: 8px;
        overflow: hidden;
    }

    .close-modal {
        position: absolute;
        top: 10px;
        right: 20px;
        color: #fff;
        font-size: 35px;
        font-weight: bold;
        cursor: pointer;
        z-index: 10001;
    }

    @media (max-width: 768px) {
        .modal-content { width: 95%; height: 70vh; margin: 15% auto; }
        .custom-pump-table th, .custom-pump-table td { font-size: 11px; padding: 8px 5px; }
    }
</style>


<body>
    @include('web.Layout.header')

    <div class="breadcumb-area breadcrumb-bg" style="padding: 20px 0;"> 
        <div class="container">
            <div class="breadcumb-inner">
                <h5 class="title">Selection Results</h5>
            </div>
        </div>
    </div>

    <div class="shop-page-area pd-top-40">
        <div class="container">
            <div class="table-container">
                <table class="custom-pump-table">
                    <thead>
                        <tr>
                            <th>Model</th>
                            <th>Hertz</th>
                            <th>KW</th>
                            <th>HP</th>
                            <th>Max Q</th>
                            <th>Max H</th>
                            <th>Voltage</th>
                            <th>Discharge</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products->unique('Model') as $product)
                        <tr>
                            <td class="model-cell">{{$product->Model}}</td>
                            <td>{{$product->Hertz}}</td>
                            <td>{{$product->PowerKw}}</td>
                            <td>{{$product->PowerHp}}</td>
                            <td>{{$product->q}}</td>
                            <td>{{$product->h}}</td>
                            <td>{{$product->v}}</td>
                            <td>{{$product->Discharge_diameter}}</td>
                            <td>
                                <!-- <button class="pdf-trigger" onclick="openPdf('{{$product->link}}')">
                                    View PDF
                                </button> -->
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

    <div id="pdfModal" class="pdf-modal">
        <span class="close-modal" onclick="closePdf()">&times;</span>
        <div class="modal-content">
            <iframe id="pdfFrame" src="" width="100%" height="100%" frameborder="0"></iframe>
        </div>
    </div>

    <script>
        function openPdf(url) {
            const modal = document.getElementById('pdfModal');
            const iframe = document.getElementById('pdfFrame');
            iframe.src = url;
            modal.style.display = "block";
            document.body.style.overflow = "hidden"; // Stop background scrolling
        }

        function closePdf() {
            const modal = document.getElementById('pdfModal');
            const iframe = document.getElementById('pdfFrame');
            modal.style.display = "none";
            iframe.src = ""; // Clear source to stop loading
            document.body.style.overflow = "auto"; // Re-enable scrolling
        }

        // Close modal if user clicks outside the content box
        window.onclick = function(event) {
            const modal = document.getElementById('pdfModal');
            if (event.target == modal) {
                closePdf();
            }
        }
    </script>

    @include('web.Layout.footer')
</body>
</html>
