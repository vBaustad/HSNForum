<?php
require_once 'includes/db_connect.php';
require_once 'includes/header.php';
?>

<div class="traadtop"><h2>Tråd tittel<br><small>Skrevet av Admin, 17 timer siden</small></h2></div>
<div id="traadtable">
    <div class="table-row-group">
        <div class="table-row">
            <div class="table-cell traadleft">Admin<br><small>1/1-2016</small></div>
            <div class="table-cell traadright">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed metus mauris, rhoncus ut neque ac, placerat scelerisque tellus. Sed id finibus arcu, vestibulum venenatis sapien. Duis interdum a purus et varius. Nunc non lectus sed justo finibus sodales vitae fringilla leo. Aliquam mauris erat, luctus ac lacus nec, scelerisque malesuada turpis. Proin pharetra enim risus, a condimentum lorem aliquam ut. Nullam sem quam, posuere in felis vulputate, lacinia lacinia nunc. Vivamus at cursus lectus. Ut in fermentum nibh, vel rutrum velit.
                Sed condimentum dolor dui. Sed in ultricies sem. Suspendisse eget massa aliquet mi vestibulum hendrerit. Vivamus sed rutrum velit. Integer lacinia, augue sed eleifend feugiat, libero orci euismod sapien, aliquam convallis urna enim eu nunc. Sed dui nibh, feugiat eu libero vitae, pellentesque venenatis lacus. Sed pharetra sodales ipsum quis tempus. Vestibulum consectetur odio vitae varius fringilla. Nulla nunc sapien, porta vel sollicitudin ac, tincidunt ut lectus. Aliquam et arcu et arcu tristique vestibulum. Ut sed purus neque. Duis tincidunt risus a justo gravida molestie. Donec dictum venenatis est, fringilla scelerisque tortor semper ut. Curabitur interdum massa sed odio tincidunt, sed aliquam elit mattis.</td>
            </div>
        </div>
        <div class="table-row">
            <div class="table-cell traadleft">Olle<br><small>2/1-2016</small></div>
            <div class="table-cell traadright">Sed in ultricies sem. Suspendisse eget massa aliquet mi vestibulum hendrerit. Vivamus sed rutrum velit.</div>
        </div>
        <div class="table-row">
            <div class="table-cell traadleft">Marte89<br><small>3/1-2016</small></div>
            <div class="table-cell traadright">consectetur adipiscing elit. Sed metus mauris, rhoncus ut neque ac, placerat scelerisque tellus. Sed id finibus arcu, vestibulum venenatis sapien. Duis interdum a purus et varius.</div>
        </div>
    </div>
</div>

<table class="main-table table forum table-striped">
    <thead>
        <tr>
            <th class="cell-stat"><i class="fa fa-clock-o fa-2x"></i></th>
            <th colspan="100">Tråd tittel<br><small>Skrevet av Admin, 17 timer siden</small></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Admin<br><small>1/1-2016</small></td>
            <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed metus mauris, rhoncus ut neque ac, placerat scelerisque tellus. Sed id finibus arcu, vestibulum venenatis sapien. Duis interdum a purus et varius. Nunc non lectus sed justo finibus sodales vitae fringilla leo. Aliquam mauris erat, luctus ac lacus nec, scelerisque malesuada turpis. Proin pharetra enim risus, a condimentum lorem aliquam ut. Nullam sem quam, posuere in felis vulputate, lacinia lacinia nunc. Vivamus at cursus lectus. Ut in fermentum nibh, vel rutrum velit.

                Sed condimentum dolor dui. Sed in ultricies sem. Suspendisse eget massa aliquet mi vestibulum hendrerit. Vivamus sed rutrum velit. Integer lacinia, augue sed eleifend feugiat, libero orci euismod sapien, aliquam convallis urna enim eu nunc. Sed dui nibh, feugiat eu libero vitae, pellentesque venenatis lacus. Sed pharetra sodales ipsum quis tempus. Vestibulum consectetur odio vitae varius fringilla. Nulla nunc sapien, porta vel sollicitudin ac, tincidunt ut lectus. Aliquam et arcu et arcu tristique vestibulum. Ut sed purus neque. Duis tincidunt risus a justo gravida molestie. Donec dictum venenatis est, fringilla scelerisque tortor semper ut. Curabitur interdum massa sed odio tincidunt, sed aliquam elit mattis.</td>
        </tr>
        <tr>
            <td>Olle<br><small>2/1-2016</small></td>
            <td>Ikke mye, Admin...</td>
        </tr>
        <tr>
            <td>Marte89<br><small>3/1-2016</small></td>
            <td>Samme her... Wanna fuck?</td>
        </tr>
        <tr>
            <td>Olle<br><small>3/1-2016</small></td>
            <td>Haha nei din stygge bitch</td>
        </tr>
        <tr>
            <td>Admin<br><small>3/1-2016</small></td>
            <td>Kyle what the fuck? Come on, son!</td>
        </tr>

    </tbody>

</table>
<?php
if (isset($_GET['ukat_id'])) {
    $ukad_id = $_GET['ukat_id'];

}
require_once 'includes/footer.php';
?>

<!-- SLETT UNDERKATEGORI -->
<div id="slett_traad" style="display: none">
    <div class="popup-header center">
        <div class="pull-left" style="width: 80%">
            <h2 class="white icon-user pull-right"><i class="fa fa-minus-square-o"></i> Slette underkategori?</h2>
        </div>
        <div class="pull-right half" style="width: 20%;">
            <i class="box-icon-lukk fa fa-times fa-2x red pull-right"></i>
        </div>
    </div>
    <div class="popup-container center">
        <?php echo '<form id="slett_ukat_form" name="slett_ukat_form" method="post" action="http://localhost/forum/www/includes/endringer.php?slett_ukat_id=' . $ukat_id .'&kat_id=' . $kat_id . '">' ?>
        <div class="popup-divider">
            <?php echo '<p class="white">Er du vikker på at du vil slette underkategorien ' . $ukat_navn .  '?</p>' ?>
        </div>
        <button type="submit" name="slett_ukat_btn" class="button-lukk">Slett den</button>
        </form>
    </div>
</div>
