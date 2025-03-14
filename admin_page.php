<?php
session_start();  // 啟用交談期
$link = mysqli_connect("localhost", "root", "921013", "library") or die("無法開啟MySQL資料庫連接!");
mysqli_query($link, 'SET NAMES utf8');

?>

<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <title>管理員介面</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="style.css" />
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">


    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="assets/css/fontawesome.css">
    <link rel="stylesheet" href="assets/css/templatemo-plot-listing.css">
    <link rel="stylesheet" href="assets/css/animated.css">
    <link rel="stylesheet" href="assets/css/owl.css">
    
    <style>
    /* 讓下拉選單在滑鼠移入或移出時觸發 */
    .dropdown:hover .dropdown-menu {
      display: block;
    }

    .dropdown-menu {
      margin-top: 0; /* 調整下拉選單位置 */
    }

    .wrapper {
            display: grid;
            grid-template-columns: 1fr 3fr;
            gap: 15px;
            max-width: 1300px;
            margin: 50px auto;
            padding: 30px;
            border: 2px solid #ddd;
            border-radius: 5px;
        }
        .wrapper div {
            padding: 15px;
            font-size: 20px; 
        }
        .label {
            color: white;
            font-weight: bold;
            background-color: rgba(141, 153, 175, 0.75); 
            /*ackground-color: #8d99af;*/
        }

        .book-management {
            display: flex;
            justify-content: center; /* 水平居中 */
            align-items: center;     /* 垂直居中 */
            flex-direction: column;  /* 使表格和新增書籍連結垂直排列 */
            text-align: center;      /* 使內容文字居中 */
            margin-top: 20px;        /* 調整距離上方的間隔 */
        }
        
        table.center-table th, table.center-table td {
            padding: 10px;
            border: 1px solid #ddd; /* 添加邊框 */
            text-align: center;      /* 文字置中 */
        }

        
  </style>
  
  <script>
    function showTable(tableId) {
    // 先將所有表格隱藏
    const tables = document.querySelectorAll('.record-table');
    tables.forEach(function(table) {
        table.style.display = 'none';
    });

    // 顯示指定的表格
    const selectedTable = document.getElementById(tableId);
    if (selectedTable) {
        selectedTable.style.display = 'block';
    }
}
</script>


<!--

TemplateMo 564 Plot Listing

https://templatemo.com/tm-564-plot-listing

-->
  </head>

<body>

  <!-- ***** Preloader Start ***** -->
  <div id="js-preloader" class="js-preloader">
    <div class="preloader-inner">
      <span class="dot"></span>
      <div class="dots">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>
  </div>
  <!-- ***** Preloader End ***** -->

  <!-- ***** Header Area Start ***** -->
  <header class="header-area header-sticky wow slideInDown" data-wow-duration="0.75s" data-wow-delay="0s">
    <div class="container">
      <div class="row">
          <nav class="main-nav">
            <!-- ***** Logo Start ***** -->
            <a href="admin_page.php" class="logo">
              <img src="assets/images/logo.jpg">
            </a>
            <!-- ***** Logo End ***** -->
            <!-- ***** Menu Start ***** -->

            <ul class="nav">
            
            <li class="dropdown">
              <a class="nav-link dropdown-toggle text-center" href="#" style="height: 40px; line-height: 23px;">功能</a>
                <div class="dropdown-menu">
                <a class="dropdown-item" href="admin_page.php?page=manage_books">管理書籍</a>
                <a class="dropdown-item" href="admin_page.php?page=add_books">上架書籍</a>
                <a class="dropdown-item" href="admin_page.php?page=view_records">查看紀錄</a>
               </div>
            </li>

            <li><a href="login.php">登出</a></li>
            <li></li>
          </ul>
          
			      
            <a class='menu-trigger'>
                <span>Menu</span>
            </a>
            
              
            <!-- ***** Menu End ***** -->
          </nav>
        </div>
      </div>
    </div>
  </header>
  <!-- ***** Header Area End ***** -->

  <div class="page-heading">
    <div class="container">
      <div class="row">
        <div class="col-lg-8">
          <div class="top-text header-text">
            <h2>管理員介面</h2>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <?php
// 檢查是否有 page 參數，若沒有則預設為 'manage_books'
$page = isset($_GET['page']) ? $_GET['page'] : 'manage_books';  // 預設為 'manage_books'

// 根據 URL 參數來決定顯示的內容
if ($page == 'manage_books') {
    // 顯示管理書籍的內容
    ?>
    <div class="book-management">
    <div class="table-container">
        <table class="center-table">
            <thead>
                <h2 style="text-align: center">管理書籍</h2><br>
                <tr>
                    <th>書籍編號</th>
                    <th>書名</th>
                    <th colspan="2">操作</th>
                    <th>狀態</th>
                </tr>
            </thead>
            <tbody>
    <?php
    // 連接資料庫
    $link = mysqli_connect("localhost", "root", "921013", "library") or die("無法開啟MySQL資料庫連接!");
    mysqli_query($link, 'SET NAMES utf8');
    $query = "
    SELECT * 
    FROM book 
    ORDER BY 
        CASE 
            WHEN status IN ('遺失，待處理') THEN 1
             WHEN status NOT IN ('借閱中', '借閱中，有預訂', '已預訂') THEN 2
            ELSE 3
        END, bId ASC";

    $result = mysqli_query($link, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['bId'] . "</td>";
        echo "<td>" . $row['title'] . "</td>";
        echo "<td>
            <form action='edit_book.php' method='GET'>
                <input type='hidden' name='bId' value='" . $row['bId'] . "'>
                <button type='submit' class='btn btn-primary btn-sm'>編輯</button>
            </form>
        </td>";

        // 根據狀態動態生成刪除按鈕
        if ($row['status'] === '借閱中' || $row['status'] === '借閱中，有預訂' || $row['status'] === '已預訂') {
            // 禁用刪除按鈕
            echo "<td>
                <button class='btn btn-danger btn-sm' disabled>刪除</button>
            </td>";
        } else {
            // 啟用刪除按鈕
            echo "<td>
                <form action='delete_book.php' method='POST' onsubmit='return confirm(\"確定要刪除此書籍嗎？\");'>
                    <input type='hidden' name='book_id' value='" . $row['bId'] . "'>
                    <button type='submit' name='delete' class='btn btn-danger btn-sm'>刪除</button>
                </form>
            </td>";
        }
        // 根據 status 動態設置文字顏色
        if ($row['status'] === '遺失，待處理') {
            echo "<td style='color: red; font-weight: bold;'>" . $row['status'] . "</td>";
        }else{
            echo "<td>" . $row['status'] . "</td>";
        }
        

        echo "</tr>";
    }
    ?>
</tbody>

<script>
    function confirmDelete(bId) {
        // 跳出確認視窗
        if (confirm("確定要刪除此書籍嗎？")) {
            // 使用 Fetch API 發送刪除請求
            fetch('delete_book_ajax.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ book_id: bId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("書籍刪除成功！");
                    window.location.href = 'manage_books.php'; // 刪除成功後跳轉回管理畫面
                } else {
                    alert("刪除失敗：" + data.message);
                }
            })
            .catch(error => {
                alert("發生錯誤：" + error);
            });
        }
    }
</script>

        </table>
    </div>
</div>

    <?php
} elseif ($page == 'view_records') {
    // 顯示查看紀錄的內容
    ?>

    <div class="listing-page">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="naccs">
                        <div class="grid">
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="menu">
                                        <div class="thumb active" onclick="showTable('ongoing-loan-record')">
                                            <span class="icon"><img src="assets/images/listing-icon-heading.png" alt=""></span>借閱中
                                        </div>
                                        <div class="thumb" onclick="showTable('ongoing-reservation-record')">
                                            <span class="icon"><img src="assets/images/listing-icon-heading.png" alt=""></span>預訂中
                                        </div>
                                        <div class="thumb" onclick="showTable('loan-record')">
                                            <span class="icon"><img src="assets/images/listing-icon-heading.png" alt=""></span>借閱歷史紀錄
                                        </div>
                                        <div class="thumb" onclick="showTable('reservation-record')">
                                            <span class="icon"><img src="assets/images/listing-icon-heading.png" alt=""></span>預訂歷史紀錄
                                        </div>
                                        <div class="thumb" onclick="showTable('violation-record')">
                                            <span class="icon"><img src="assets/images/listing-icon-heading.png" alt=""></span>違規歷史紀錄
                                        </div>
                                    </div>
                                </div>

                                <!-- 借閱中表格 -->
                                <div class="col-lg-9">
                                    <div id="ongoing-loan-record" class="record-table" style="display: block;">
                                        <h2 style="text-align: center">借閱中的書籍</h2>
                                        <br>
                                        <div class="container">
                                            <table class="table table-sm table-bordered" style="text-align:center;">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 10%;">借閱編號</th>
                                                        <th style="width: 10%;">借閱者學號</th>
                                                        <th style="width: 40%;">借閱書籍名稱</th>
                                                        <th style="width: 15%;">借閱日期</th>
                                                        <th style="width: 15%;">借閱到期日</th>
                                                        <th style="width: 10%;">續借次數</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    // 取得當前使用者的會員ID
                                                    $sId = $_SESSION["sId"];

                                                    // 建立MySQL的資料庫連接
                                                    $link = mysqli_connect("localhost", "root", "921013", "library") or die("無法開啟MySQL資料庫連接!");

                                                    // 設定UTF-8編碼
                                                    mysqli_query($link, 'SET NAMES utf8');

                                                    // 查詢所有會員的借閱紀錄
                                                    $query = "SELECT loan.loan_Id, loan.sId, book.title, loan.loan_date, loan.due_date, loan.extend_count
                                                    FROM loan
                                                    JOIN book ON loan.bId = book.bId 
                                                    WHERE loan.return_date IS NULL
                                                    ORDER BY loan.due_date DESC";
                                                    $query_run = mysqli_query($link, $query);

                                                    // 顯示所有會員的借閱紀錄
                                                    if (mysqli_num_rows($query_run) > 0) {
                                                        while ($row = mysqli_fetch_assoc($query_run)) {
                                                            echo "<tr>";
                                                            echo "<td>" . htmlspecialchars($row['loan_Id']) . "</td>";
                                                            echo "<td>" . htmlspecialchars($row['sId']) . "</td>";  // 顯示借閱者學號
                                                            echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                                                            echo "<td>" . htmlspecialchars($row['loan_date']) . "</td>";
                                                            echo "<td>" . htmlspecialchars($row['due_date']) . "</td>";
                                                            echo "<td>" . htmlspecialchars($row['extend_count']) . "</td>";
                                                            echo "</tr>";
                                                        }
                                                    } else {
                                                        echo "<tr><td colspan='6'>目前沒有借閱中的書籍</td></tr>";
                                                    }

                                                    // 關閉資料庫連接
                                                    mysqli_close($link);
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div> 



                                    
                                <!-- 預訂中表格 -->
                                    <div id="ongoing-reservation-record" class="record-table" style="display: none;">
                                        <h2 style="text-align: center">預訂中的書籍</h2>
                                        <br>
                                        <div class="container">
                                            <table class="table table-sm table-bordered" style="text-align:center;">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 5%;">預訂編號</th>
                                                        <th style="width: 5%;">預訂者學號</th>
                                                        <th style="width: 11%;">預訂書籍名稱</th>
                                                        <th style="width: 3%;">預訂日期</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    // 建立MySQL的資料庫連接
                                                    $link = mysqli_connect("localhost", "root", "921013", "library") or die("無法開啟MySQL資料庫連接!");
                                                    mysqli_query($link, 'SET NAMES utf8');

                                                    // 查詢所有會員的預訂紀錄
                                                    $query_reservation = "SELECT reservation.rId, reservation.sId, book.title, reservation.reservation_date, reservation.getbook_date,
                                                                        book.status
                                                                        FROM reservation
                                                                        JOIN book ON reservation.bId = book.bId 
                                                                        WHERE reservation.getbook_date IS NULL
                                                                        ORDER BY reservation.reservation_date DESC";
                                                    $query_run_reservation = mysqli_query($link, $query_reservation);

                                                    if (mysqli_num_rows($query_run_reservation) > 0) {
                                                        while ($row = mysqli_fetch_assoc($query_run_reservation)) {
                                                            echo "<tr>";
                                                            echo "<td>" . htmlspecialchars($row['rId']) . "</td>";
                                                            echo "<td>" . htmlspecialchars($row['sId']) . "</td>";
                                                            echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                                                            echo "<td>" . htmlspecialchars($row['reservation_date']) . "</td>";
                                                            echo "</tr>";
                                                        }
                                                    } else {
                                                        echo "<tr><td colspan='5'>目前沒有預訂中的書籍</td></tr>";
                                                    }

                                                    // 關閉資料庫連接
                                                    mysqli_close($link);
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div> 

                                <!-- 借閱紀錄表格 -->
                                <div id="loan-record" class="record-table" style="display: none;">
                                <h2 style="text-align: center">借閱歷史紀錄</h2>
                                <br>
                                <table class="table table-bordered" style="text-align: center">
                                    <thead>
                                    <tr>
                                        <th style="width: 10%;">借閱編號</th>
                                        <th style="width: 15%;">借閱者學號</th>
                                        <th style="width: 30%;">書籍名稱</th>
                                        <th style="width: 15%;">借閱日期</th>
                                        <th style="width: 15%;">到期日期</th>
                                        <th style="width: 15%;">還書日期</th>
                                    </tr>
                                    </thead>
                                    <tbody style="text-align: center">
                                    <?php
                                     // 建立MySQL的資料庫連接
                                     $link = mysqli_connect("localhost", "root", "921013", "library") or die("無法開啟MySQL資料庫連接!");
                                     mysqli_query($link, 'SET NAMES utf8');
                                        // 修改查詢，顯示所有會員的借閱紀錄
                                        $query = "SELECT loan.loan_Id, loan.sId, book.title, loan.loan_date, loan.due_date, loan.return_date
                                                FROM loan
                                                JOIN book ON loan.bId = book.bId
                                                ORDER BY loan.due_date DESC";
                                        $query_run = mysqli_query($link, $query);

                                        // 檢查是否有紀錄
                                        if (mysqli_num_rows($query_run) > 0) {
                                            // 顯示所有借閱紀錄
                                            while ($row = mysqli_fetch_assoc($query_run)) {
                                                echo "<tr>";
                                                echo "<td>" . htmlspecialchars($row['loan_Id']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['sId']) . "</td>"; // 顯示借閱者學號
                                                echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['loan_date']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['due_date']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['return_date'] ?? '') . "</td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            // 沒有借閱紀錄
                                            echo "<tr><td colspan='6'>目前沒有借閱紀錄</td></tr>";
                                        }
                                    ?>
                                    </tbody>
                                </table>
                                </div>


                                <!-- 預訂紀錄表格 -->
                                <div id="reservation-record" class="record-table" style="display: none;">
                                <h2 style="text-align: center">預訂歷史紀錄</h2>
                                <br>
                                <table class="table table-bordered" style="text-align: center">
                                    <thead>
                                    <tr>
                                        <th style="width: 10%;">預訂編號</th>
                                        <th style="width: 15%;">預訂者學號</th>
                                        <th style="width: 45%;">書籍名稱</th>
                                        <th style="width: 15%;">預訂日期</th>
                                        <th style="width: 15%;">取書日期</th>
                                    </tr>
                                    </thead>
                                    <tbody style="text-align: center">
                                    <?php
                                        // 建立MySQL的資料庫連接
                                        $link = mysqli_connect("localhost", "root", "921013", "library") or die("無法開啟MySQL資料庫連接!");
                                        mysqli_query($link, 'SET NAMES utf8');
                                        // 查詢所有會員的預訂紀錄
                                        $query = "SELECT reservation.rId, reservation.sId, book.title, reservation.reservation_date, reservation.getbook_date
                                                FROM reservation
                                                JOIN book ON reservation.bId = book.bId 
                                                ORDER BY reservation.reservation_date DESC";
                                        $query_run = mysqli_query($link, $query);

                                        if (mysqli_num_rows($query_run) > 0) {
                                            while ($row = mysqli_fetch_assoc($query_run)) {
                                                echo "<tr>";
                                                echo "<td>" . htmlspecialchars($row['rId'] ?? '') . "</td>";
                                                echo "<td>" . htmlspecialchars($row['sId'] ?? '') . "</td>";  // 顯示預訂者學號
                                                echo "<td>" . htmlspecialchars($row['title'] ?? '') . "</td>";
                                                echo "<td>" . htmlspecialchars($row['reservation_date'] ?? '') . "</td>";
                                                echo "<td>" . htmlspecialchars($row['getbook_date'] ?? '') . "</td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='5'>無紀錄</td></tr>";
                                        }
                                    ?>
                                    </tbody>
                                </table>
                                </div>


                                <!-- 違規紀錄表格 -->
                                <div id="violation-record" class="record-table" style="display: none;">
                                <h2 style="text-align: center">違規歷史紀錄</h2>
                                <br>
                                <table class="table table-bordered" style="text-align: center">
                                    <thead>
                                    <tr>
                                        <th style="width: 10%;">違規編號</th>
                                        <th style="width: 15%;">違規者學號</th>
                                        <th style="width: 50%;">書籍名稱</th>
                                        <th style="width: 15%;">違規日期</th>
                                        <th style="width: 15%;">違規次數</th>
                                    </tr>
                                    </thead>
                                    <tbody style="text-align: center">
                                    <?php
                                        // 建立MySQL的資料庫連接
                                        $link = mysqli_connect("localhost", "root", "921013", "library") or die("無法開啟MySQL資料庫連接!");
                                        mysqli_query($link, 'SET NAMES utf8');
                                        // 查詢所有會員的違規紀錄
                                        $query = "SELECT violation.vId, violation.sId, book.title, violation.violation_date, violation.point 
                                                FROM violation
                                                JOIN book ON violation.bId = book.bId
                                                ORDER BY violation.violation_date DESC";
                                        $query_run = mysqli_query($link, $query);

                                        if (mysqli_num_rows($query_run) > 0) {
                                            while ($row = mysqli_fetch_assoc($query_run)) {
                                                echo "<tr>";
                                                echo "<td>" . htmlspecialchars($row['vId'] ?? '') . "</td>";
                                                echo "<td>" . htmlspecialchars($row['sId'] ?? '') . "</td>";  // 顯示違規者學號
                                                echo "<td>" . htmlspecialchars($row['title'] ?? '') . "</td>";
                                                echo "<td>" . htmlspecialchars($row['violation_date'] ?? '') . "</td>";
                                                echo "<td>" . htmlspecialchars($row['point'] ?? '') . "</td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='5'>無紀錄</td></tr>";
                                        }
                                    ?>
                                    </tbody>
                                </table>
                                </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <?php
} else {
    // 先處理新增書籍的邏輯
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $publisher = $_POST['publisher'];
    $publication_year = $_POST['publication_year'];
    $isbn = $_POST['isbn'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $reservation_number = 0;
    
    

    // 連接資料庫
    $link = mysqli_connect("localhost", "root", "921013", "library") or die("無法開啟MySQL資料庫連接!");
    mysqli_query($link, 'SET NAMES utf8');

    

    // 檢查 bId 是否已經存在
    $query_check_bId = "SELECT * FROM book WHERE bId = '$bId'";
    $result_check_bId = mysqli_query($link, $query_check_bId);
    if (mysqli_num_rows($result_check_bId) > 0) {
        // 如果 bId 已存在，提示錯誤並返回
        echo "<script>alert('該書籍編號已經存在！'); history.back();</script>";
        exit;
    }

    // 插入資料庫
    $query = "INSERT INTO book (title, author, publisher, publication_year, isbn, category, description, status, reservation_number) 
              VALUES ('$title', '$author', '$publisher', '$publication_year', '$isbn', '$category', '$description', '$status', '$reservation_number')";

    if (mysqli_query($link, $query)) {
        echo "<script>alert('書籍上架成功！'); window.location.href='manage_books.php';</script>"; // 可根據需要重定向
    } else {
        echo "<script>alert('上架書籍失敗！');</script>";
    }

    mysqli_close($link);
} else {
    // 當沒有提交表單時顯示新增書籍表單
    ?>
   <div class="d-flex justify-content-center align-items-center vh-100">
    <div class="col-md-8">
        <div class="book-management">
            <div class="container form-container">
                <br><br><br><br><br><br>
                <h2 style="text-align: center">上架書籍</h2><br>
                <form action="add_book.php" method="POST">
                    <table class="table caption-top table-hover">
                        <tbody>
                            <tr>
                                <th style="width: 150px;">編號</th>
                                <td><input type="text" name="bId" class="form-control" maxlength="6" required></td>
                            </tr>
                            <tr>
                                <th style="width: 150px;">書名</th>
                                <td><input type="text" name="title" class="form-control" required></td>
                            </tr>
                            <tr>
                                <th>作者</th>
                                <td><input type="text" name="author" class="form-control" required></td>
                            </tr>
                            <tr>
                                <th>出版者</th>
                                <td><input type="text" name="publisher" class="form-control" required></td>
                            </tr>
                            <tr>
                                <th>出版年</th>
                                <td><input type="text" name="publication_year" class="form-control" maxlength="4" required></td>
                            </tr>
                            <tr>
                                <th>ISBN</th>
                                <td><input type="text" name="isbn" class="form-control" maxlength="13" required></td>
                            </tr>
                            <tr>
                                <th>分類</th>
                                <td>
                                    <select name="category" class="form-control" required>
                                        <option value="" disabled selected>請選擇分類</option>
                                        <option value="文學小說">文學小說</option>
                                        <option value="商業理財">商業理財</option>
                                        <option value="藝術設計">藝術設計</option>
                                        <option value="人文社科">人文社科</option>
                                        <option value="心理勵志">心理勵志</option>
                                        <option value="宗教命理">宗教命理</option>
                                        <option value="自然科普">自然科普</option>
                                        <option value="醫療保健">醫療保健</option>
                                        <option value="飲食">飲食</option>
                                        <option value="生活風格">生活風格</option>
                                        <option value="旅遊">旅遊</option>
                                        <option value="童書/青少年文學">童書/青少年文學</option>
                                        <option value="國中小參考書">國中小參考書</option>
                                        <option value="親子教養">親子教養</option>
                                        <option value="影視偶像">影視偶像</option>
                                        <option value="輕小說">輕小說</option>
                                        <option value="漫畫/圖文書">漫畫/圖文書</option>
                                        <option value="語言學習">語言學習</option>
                                        <option value="考試用書">考試用書</option>
                                        <option value="電腦資訊">電腦資訊</option>
                                        <option value="教科書/政府出版品">教科書/政府出版品</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <th>圖片</th>
                                <td><input type="text" name="image" class="form-control" required></td>
                            </tr>
                            <tr>
                                <th>書籍簡介</th>
                                <td><textarea name="description" class="form-control" rows="5" required></textarea></td>
                            </tr>
                            <tr>
                                <th>借閱狀況</th>
                                <td><input type="enum" name="status" class="form-control" value="可借閱/可預訂" readonly></td>
                            </tr>
                            <tr>
                                <th>預訂人數</th>
                                <td><input type="number" name="reservation_number" class="form-control" value="0" readonly required></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="text-center">
                        <button type="submit" class="btn btn-success">確認上架</button>
                    </div>
                </form>
                <br>
            </div>
        </div>
    </div>
</div>


    <?php
}
}
?>

<br><br><br><br><br><br>
<footer>
        <div class="col-lg-12">
          <div class="row" style="text-align: center;">
            <p><b>「書籍是全世界的營養品。生活裡沒有書籍，就好像沒有陽光，智慧沒有書籍，就好像鳥兒沒有翅膀。」—— 威廉・莎士比亞</b></p>
            <p>Copyright © 2021 Plot Listing Co., Ltd. All Rights Reserved.
            <br>
         Design: <a rel="nofollow" href="https://templatemo.com" title="CSS Templates">TemplateMo</a></p>
          </div>
        </div>
      </div>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/owl-carousel.js"></script>
  <script src="assets/js/animation.js"></script>
  <script src="assets/js/imagesloaded.js"></script>
  <script src="assets/js/custom.js"></script>

</body>

</html>
