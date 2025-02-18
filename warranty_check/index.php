<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alliance Laundry Systems Warranty Check</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="stylesheet" href="../shared/normalize.css">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Serif:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <style>
        .disclaimer {
            font-size: 0.8em;
            color: #555;
            margin-top: 20px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <main>
        <h1 class="ibm-plex-serif-bold">Warranty Verification</h1>
        <form id="warrantyForm" title="warranty check" method="post">
            <label class="ibm-plex-serif-semibold" for="serialNumber">Enter your serial number</label><br>
            <input class="input-text" type="text" id="serialNumber" name="serialNumber" placeholder="ie: 093547816" required><br>
            <button class="btn btn-primary" id="submit" onclick="check_warranty($('#serialNumber').val())">Submit</button>
        </form>
        <div id="warranty-status">
            <?php
            if(isset($result) && $result != ''): echo $result; endif;
            ?>
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="./scripts/warranty.js"></script>
    <script src="../shared/script.js"></script>
</body>
</html>