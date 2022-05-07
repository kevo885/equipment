<body class="vsc-initialized">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="index.php">Menu</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon rounded"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="nav-item nav-link active" href="index.php">Search<span class="sr-only">(current)</span></a>
                <a class="nav-item nav-link" href="add.php">Add device</a>
                <?php
                if (basename($_SERVER["REQUEST_URI"]) != 'index.php' && basename($_SERVER["REQUEST_URI"] != 'equipment'))
                    echo "<a class='nav-item nav-link' href='table.php'>View devices</a>";
                ?>
                <a class="nav-item nav-link" href="api/index.php">API</a>

            </div>
        </div>
    </nav>