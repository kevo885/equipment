<?php
function message($alert, $message)
{
    $_SESSION['alert'] = $alert;
    $_SESSION['message'] .= $message;
}
if (isset($_SESSION['message']) && isset($_SESSION['alert'])) { ?>
    <div class="<?php echo $_SESSION['alert'] ?>" role="alert">
        <i class="dripicons-information me-2"></i>
        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
        <i> <?php echo $_SESSION['message']; ?></i>
    </div>

<?php
    unset($_SESSION['message']);
    unset($_SESSION['alert']);
} ?>