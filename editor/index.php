<?html session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laws's Edit Page</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./summernote/summernote-lite.css">
    <script src="./js/jquery-3.6.0.min.js"></script>
    <script src="./summernote/summernote-lite.js"></script>
    <script src="./js/bootstrap.min.js"></script>
    <style>
         :root {
            --bs-success-rgb: 71, 222, 152 !important;
        }
        
        html,
        body {
            height: 100%;
            width: 100%;
            font-family: Apple Chancery, cursive;
        }
        .btn-info.text-light:hover,.btn-info.text-light:focus{
            background: #000;
        }
    </style>
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary bg-gradient" id="topNavBar">
        <div class="container">
            <a class="navbar-brand" href="https://Admin Edit Page.com">
            Admin Edit Page
            </a>
        </div>
    </nav>
    <div class="container py-3" id="page-container">
        <h3>Laws's Edit Page</h3>
        <hr>
        <?html 
        if(isset($_SESSION['msg'])):
        ?>
        <div class="alert alert-<?html echo $_SESSION['msg']['type'] ?>">
            <div class="d-flex w-100">
                <div class="col-11"><?html echo $_SESSION['msg']['text'] ?></div>
                <div class="col-1 d-flex justify-content-end align-items-center"><button class="btn-close" onclick="$(this).closest('.alert').hide('slow')"></button></div>
            </div>
        </div>
        <?html 
            unset($_SESSION['msg']);
        endif;
        ?>
        <div class="col-12 my-2">
            <a href="manage_page.html" class="btn btn-info text-light text-decoration-none"> + Add New Page Content</a>
        </div>
        <div class="row row-cols-sm-1 row-cols-md-3 row-cols-xl-4 gx-4 gy-2">
        <?html 
        $pages = scandir('./pages');
        asort($pages);
        foreach($pages as $page):
            if(in_array($page,array('.','..')))
            continue;
        ?>
        <div class="col">
            <div class="card border-right border-primary rounded-0">
                <div class="card-body">
                    <div class="col-12 text-truncate"><a href="view_page.html?page=<?html echo urlencode($page) ?>" title="<?html echo $page ?>" class=""><b><?html echo $page ?></b></a></div>
                    <div class="w-100 d-flex justify-content-end">
                        <a href="manage_page.html?page=<?html echo urlencode($page) ?>" class="btn btn-sm rounded-0 btn-primary me-2">Edit</a>
                        <a href="delete_page.html?page=<?html echo urlencode($page) ?>" class="btn btn-sm rounded-0 btn-danger delete_data">Delete</a>
                    </div>
                </div>
            </div>
        </div>
        <?html endforeach; ?>
        </div>
    </div>
    <script>
        $(function(){
            $('.delete_data').click(function(e){
                e.preventDefault()
                var _conf = confirm("Are you sure to delete this page content?")
                if(_conf ===true){
                    location.href=$(this).attr('href')
                }
            })
        })
    </script>
</body>

</html>