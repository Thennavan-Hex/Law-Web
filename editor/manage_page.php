<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creating Page Content using Summernote</title>
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
        input.form-control.border-0{
            transition:border .3s linear
        }
        input.form-control.border-0:focus{
            box-shadow:unset !important;
            border-color:var(--bs-info) !important
        }
        .note-editor.note-frame .note-editing-area .note-editable, .note-editor.note-airframe .note-editing-area .note-editable {
            background: var(--bs-white);
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
        <?php 
        if(isset($_SESSION['msg'])):
        ?>
        <div class="alert alert-<?php echo $_SESSION['msg']['type'] ?>">
            <div class="d-flex w-100">
                <div class="col-11"><?php echo $_SESSION['msg']['text'] ?></div>
                <div class="col-1"><button class="btn-close" onclick="$(this).closest('.alert').hide('slow')"></button></div>
            </div>
        </div>
        <?php 
            unset($_SESSION['msg']);
        endif;
        ?>
        <div class="card">
            <div class="card-header">
                Manage Page Content
            </div>
            <div class="card-body">
                <form action="save_page.php" id="content-form" method="POST">
                    <input type="hidden" name="filename" value="<?php echo isset($_SESSION['POST']['filename']) ? $_SESSION['POST']['filename'] : (isset($_GET['page']) ? str_replace('.html','',$_GET['page']) : '')  ?>">
                    <div class="form-group col-6">
                        <label for="fname" class="control-label">File Name <span class="text-info"><small>([a-z0-9A-Z_-])</small></span></label>
                        <input type="text" pattern="[a-z0-9A-Z_-]+" name="fname" id="fname" autofocus autocomplete="off" class="form-control form-control-sm border-0 border-bottom rounded-0" value="<?php echo isset($_SESSION['POST']['fname']) ? $_SESSION['POST']['fname'] : (isset($_GET['page']) ? str_replace('.html','',$_GET['page']) : '')  ?>" required>
                        <span class="text-info"><small>This will be added with .html file extension upod saving.</small></span>
                    </div>
                    <div class="form-group col-12">
                        <label for="content" class="control-label">Content</label>
                        <textarea name="content" id="content" class="summernote" required><?php echo isset($_SESSION['POST']['content']) ? $_SESSION['POST']['content'] : (isset($_GET['page']) ? file_get_contents("./pages/{$_GET['page']}") : '')  ?></textarea>
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <button class="btn btn-sm rounded-0 btn-primary" type="submit" form="content-form">Save</button>
                <a class="btn btn-sm rounded-0 btn-light" href="./">Cancel</a>
            </div>
        </div>
        </div>
    </div>
    <script>
      $('.summernote').summernote({
        placeholder: 'Create you content here.',
        tabsize: 5,
        height: '50vh',
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture', 'video']],
          ['view', ['fullscreen', 'codeview', 'help']]
        ]
      });
    </script>
</body>

</html>