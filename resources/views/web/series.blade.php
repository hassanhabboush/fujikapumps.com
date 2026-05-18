@include('web.Layout.head')
<style>
   
  .shop-page-area  .container
    {
        max-width:100% !important;  
    }
   
  .shop-page-area  .container
    {
        max-width:100% !important;  
    }
    .read2
    {
            box-shadow: 0px 14px 18px #80001369;
    border-radius: 26px;
    height: 46px;
    line-height: 46px;
    padding: 0 35px;
    display: inline-block;
    background: var(--heading-color);
    color: #fff;
    }
    .read3
    {
            line-height: 1.2;
    font-size: 18px;
    margin-bottom: 14px;
    letter-spacing: 0.54px;
    color: var(--heading-color);
    font-weight: 500;
    display: block;
    transition: 0.4s;
    margin: auto;
    background: transparent;
    border: none;
    }
    .read4
    {
        font-size: 14px;
            color: #b7212e;
             font-size: inherit;
    font-weight: 500;
    line-height: 24px;
    background: transparent;
    border: none;
    
    }
    .read3:hover
    {
        color:#b7212e;
    }
    .subcategory
    {
       display:none;
    }
    .nav-item:hover > .subcategory
    {
         display:block;
    }
      #filterhead
    {
     font-size:16px;   
    }
    #filterbody
    {
        font-size:14px;
    }
    #filterbut
    {
        margin: auto;
    background-color: black;
    padding: 0px 11px;
    font-size: 12px;
    border-radius: 9px;
    color: white;
    text-align: center;
    font-weight: 600;
    }
   .pdf:before {
        /* PDF file */
  width:26px;
  height:26px;
 background:url('http://wwwimages.adobe.com/content/dam/acom/en/legal/images/badges/Adobe_PDF_file_icon_32x32.png');
  display:inline-block;
  content:' ';
}
  .product-table {
      border-collapse: separate;
      border-spacing: 0 10px;
  }

  .product-table thead th {
      background: #b7212e;
      color: #fff;
      font-weight: 600;
      font-size: 14px;
      letter-spacing: 0.5px;
      padding: 14px 16px;
      border: none;
  }

  .product-table tbody tr.product-row td {
      padding: 11px;
      border: none;
      font-size: 14px;
  }

  .product-details {
      background: #f7f7f7;
  }

  .details-wrapper {
      padding: 25px;
  }
  .expand-icon {
      display: inline-block;
      margin-right: 8px;
      transition: transform 0.2s ease;
      color: #b7212e;
  }

  .details-wrapper h5 {
      font-weight: 600;
      margin-bottom: 15px;
  }

  .details-wrapper img {
      border-radius: 8px;
      background: #fff;
      padding: 10px;
  }

  .product-table tbody tr.product-row td {
      padding: 16px;
      border: none;
      font-size: 14px;
  }

  .details-content {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.35s ease;
  }

  .product-details.open .details-content {
      max-height: 500px;
  }
  .product-search-input{
      border-radius: 0 !important;
      border: 1px solid black !important;
      color: black !important;
      cursor: text !important;
  }


  .product-table tbody tr.product-row {
      background: linear-gradient(90deg, #ffffff 0%, #f9f9f9 100%);
      transition: all 0.25s ease;
      box-shadow: 0 3px 10px rgba(0,0,0,0.04);
      border-left: 3px solid transparent;
      cursor: pointer;
  }

  .product-table tbody tr.product-row:hover {
      background: linear-gradient(90deg, #f4f4f4 0%, #ececec 100%);
      transform: translateY(-2px);
      box-shadow: 0 6px 18px rgba(0,0,0,0.08);
      border-left: 3px solid #b7212e;
  }
  .product-details > td {
      border: 2px solid #b7212e !important;
  }

  .m-sdnav {
      background: #ffffff;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.05);
  }
  .nav-list,
  .sbnav-list {
      list-style: none;
      margin: 0;
      border-left: 1px solid rgba(0,0,0,0.06);
      margin-left: 12px;
      padding-left: 12px;
  }

  .nav-list li {
      margin-bottom: 6px;
  }
  .nav-list a {
      display: block;
      padding: 8px 12px;
      font-size: 14px;
      font-weight: 500;
      color: #333;
      border-radius: 6px;
      transition: all 0.2s ease;
      text-decoration: none;
  }
  .nav-list a {
      transition:
              background 0.35s ease,
              color 0.25s ease,
              padding-left 0.35s ease;
  }
  .nav-list a:hover {
      background: rgba(183,33,46,0.08);
      color: #b7212e;
      padding-left: 14px;
  }
  /* Level 1 */
  .nav-list > li > a {
      font-weight: 600;
  }

  /* Level 2 */
  .nav-list .sbnav-list > li > a {
      padding-left: 25px;
      font-size: 13px;
  }

  /* Level 3 */
  .nav-list .sbnav-list .sbnav-list > li > a {
      padding-left: 40px;
      font-size: 12.5px;
  }
  .nav-list > li {
      border-bottom: 1px solid rgba(0,0,0,0.05);
      padding-bottom: 6px;
  }
  .subcategory {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.3s ease;
  }

  .nav-item:hover > .subcategory {
      /*max-height: 500px;*/
  }

  .nav-item > a::after {
      content: "›";
      float: right;
      font-size: 12px;
      opacity: 0.4;
      transition: transform 0.3s ease;
  }

  .nav-item:hover > a::after {
      transform: rotate(90deg);
      opacity: 0.7;
  }
  .nav-item.open > .subcategory {
      max-height: 1000px;
  }
  .product-table tbody tr.product-row.active-row {
      background: linear-gradient(90deg, #fff3f4 0%, #ffe3e6 100%) !important;
      border-left: 4px solid #b7212e !important;
      box-shadow: 0 6px 18px rgba(0,0,0,0.08);
  }

  .product-row.active-row .expand-icon {
      transform: rotate(90deg);
  }
  @media (max-width: 768px) {
      .name-content {
          margin-top: 0px !important;
          text-align: center;
      }
      .product-table tbody tr.product-row td {
          padding: 6px;
          font-size: 12px;
      }
      .product-table thead th {
          padding: 14px 6px;
      }
      .series-table {
          padding-right: 0px;
      }

  }
  .prod-section {
      padding: 10px 0;
  }

  /* CARD */
  .prod-card {
      background: #ffffff;
      border: 1px solid #eaeaea;
      border-radius: 14px;
      overflow: hidden;
      transition: all 0.25s ease;
  }

  /* Hover lift */
  .prod-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 28px rgba(0,0,0,0.08);
  }

  /* IMAGE AREA */
  .prod-image {
      width: 100%;
      height: 240px;
      overflow: hidden;
  }

  .prod-image img {
      width: 100%;
      height: 100%;
      /*object-fit: contain;*/
      display: block;
  }

  /* BODY */
  .prod-body {
      padding: 6px 2px 2px 2px;
      text-align: center;
      background: #c9ced299;
  }

  .prod-title {
      font-size: 16px;
      font-weight: bold !important;
      margin-bottom: 0px !important;

      /* Line clamp */
      display: -webkit-box;
      -webkit-line-clamp: 2;      /* max 2 lines */
      -webkit-box-orient: vertical;
      overflow: hidden;

      min-height: 32px;           /* keeps height consistent */
  }

  /* PDF ICON */
  .prod-pdf {
      display: inline-block;
      margin-bottom: 8px;
      color: #b7212e;
      font-size: 18px;
  }

  /* BUTTON */
  .prod-button {
      display: inline-block;
      /*margin-top: 8px;*/
      padding: 6px 14px;
      font-size: 14px;
      border: 1px solid #b7212e;
      border-radius: .2rem;
      color: #b7212e;
      text-decoration: none;
      transition: all 0.25s ease;
  }

  .prod-button:hover {
      background: #b7212e;
      color: #ffffff;
  }
  @media (min-width: 768px) {
      .col-md-custom {
          flex: 0 0 auto;
          width: 20%; /* 100% / 5 = 20% */
          padding-right: 0px;
      }
  }
  </style>
  <script>
      showproduct($id)
      {
          
      }
  </script>

<body>
    
@include('web.Layout.header')
@include('web.productpopup')
    <!-- Breadcumb area start  -->
    <div class="breadcumb-area breadcrumb-bg" style="padding-top:3px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcumb-inner">
                        <ul class="page-lists">
                            <li><a href="{{ url ('/')}}">Home </a> </li>
                            <li> 
                            <a href="{{url('/1/1/products/')}}">Products</a>
                            </li> 
                        </ul>
                        <h5 class="title">See our Products</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcumb area end  -->


    <div class="shop-page-area" style="padding-top:2%;">
        <div class="container row">
            <div class="col-md-3">
                <div class="m-sdnav m-sdnav1">
                    @include('web.Layout.menu-bar')
                </div>
            </div>
            <div class="col-md-9">
                <div class="prod-section">

                    <div class="row prod-grid">

                        @foreach ($products as $cat)
                            <div class="col-6 col-md-custom mb-4">

                                <div class="prod-card">

                                    <div class="prod-image">
                                        <img src="{{url($cat->photo)}}"
                                             alt="{{$cat->english_name}}" loading="lazy">
                                    </div>

                                    <div class="prod-body">
                                        <h4 class="prod-title">
                                            {{$cat->english_name}}
                                        </h4>
                                        <p>{{ $cat->text1 }}</p>
                                        <p>{{ $cat->text2 }}</p>
                                        <p>{{ $cat->text3 }}</p>
                                        <a href="{{$cat->link}}"
                                           target="_blank"
                                           class="prod-pdf">
                                            <i class="fa fa-file-pdf-o"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
{{--    {{$products->links("pagination::bootstrap-4")}}--}}
    @include('web.Layout.footer')

<script>
    document.addEventListener("DOMContentLoaded", function () {

        const detailsContainer = document.getElementById("productDetailsContainer");
        const detailsContent   = document.getElementById("productDetailsContent");
        const rows = document.querySelectorAll(".product-row");

        function openRow(row) {

            // Remove active from all
            rows.forEach(r => r.classList.remove("active-row"));

            row.classList.add("active-row");

            const name  = row.children[0].innerText.trim();
            const pdf   = row.children[4].querySelector("a").href;
            const image = row.dataset.photo;

            detailsContent.innerHTML = `
            <div class="row">
                <div class="col-md-3">
                    <img src="${image}"
                         style="width:100%; max-height:160px; object-fit:contain;">
                </div>
                <div class="col-md-9">
                    <div class="name-content" style="margin-top: 40px">
                        <h5>${name}</h5>
                        <a href="${pdf}"
                           class="btn btn-sm btn-dark"
                           target="_blank">
                            Open PDF
                        </a>
                    </div>
                </div>
            </div>
        `;

            detailsContainer.style.display = "block";
            detailsContainer.scrollIntoView({
                behavior: "smooth",
                block: "start"
            });
        }

        rows.forEach(function(row) {
            row.addEventListener("click", function () {

                const isActive = this.classList.contains("active-row");

                if (isActive) {
                    this.classList.remove("active-row");
                    detailsContainer.style.display = "none";
                    return;
                }

                openRow(this);
            });
        });

        // ✅ Open first visible row by default
        const firstRow = document.querySelector(".product-row");
        if (firstRow) {
            openRow(firstRow);
        }

    });
    document.getElementById("productSearch").addEventListener("keyup", function() {

        let value = this.value.toLowerCase();
        let visibleCount = 0;

        document.querySelectorAll(".product-row").forEach(function(row) {

            let rowText = row.innerText.toLowerCase();
            let id = row.getAttribute("data-id");
            let detailsRow = document.getElementById("details-" + id);

            if (rowText.indexOf(value) > -1) {
                row.style.display = "";
                visibleCount++;
            } else {
                row.style.display = "none";
                detailsRow.classList.remove("open");
            }

        });

        // Show / hide no results
        document.getElementById("noResults").style.display =
            visibleCount === 0 ? "block" : "none";

    });
    document.querySelectorAll('.nav-item > a').forEach(function(link) {

        link.addEventListener('click', function(e) {

            const parent = this.parentElement;
            const submenu = parent.querySelector('.subcategory');

            if (submenu) {
                e.preventDefault(); // prevent link navigation

                parent.classList.toggle('open');
            }

        });

    });
</script>
</body>
</html>