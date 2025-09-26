<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../settings/core.php';
require_admin(); // only admins
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Manage Categories</title>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h4 mb-0">Categories</h1>
      <a href="../index.php" class="btn btn-outline-secondary btn-sm">Back to Home</a>
    </div>

    <form id="addForm" class="row g-3 mb-4">
      <div class="col-md-6">
        <input type="text" class="form-control" name="category_name" placeholder="New category name" required>
      </div>
      <div class="col-auto">
        <button class="btn btn-primary">Add Category</button>
      </div>
      <div class="col-12">
        <div id="addMsg" class="small"></div>
      </div>
    </form>

    <table class="table table-striped align-middle" id="catTable">
      <thead>
        <tr>
          <th style="width:70%">Name</th>
          <th style="width:30%">Actions</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>

  <script src="../js/category.js"></script>
</body>
</html>
