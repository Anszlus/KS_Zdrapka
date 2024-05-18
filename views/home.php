<?php
if(!defined('_IN_APP'))
{
  header('Location: /');
  exit();
}
?>

<center>
  Nowa zdrapka kosztuje <?= $config['app']['koszt'] ?> lt<br />
  <a class="link" href="?kup=<?= time() ?>">Kup zdrapkę</a>
</center>

<?php if ($zdrapki) { ?>
  <div class="zdrapki">
    <h3>Nieotware zdrapki</h3>
    <table>
      <thead>
        <tr>
          <th>Zdrapka</th>
          <th>Status</th>
          <th>Akcja</th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($zdrapki as $zdrapka) {
          echo '<tr id="zdrapka' . $zdrapka['id'] . '">';
          echo '<td>Love#' . $zdrapka['id'] . '</td>';
          echo '<td>Nie otworzona</td>';
          echo '<td><a class="link" href="/?zid=' . $zdrapka['id'] . '">wyświetl</a></td>';
          echo '</tr>';
        }
        ?>
      </tbody>
    </table>
  </div>
<?php } ?>

<?php if ($zdrapki_otwarte) { ?>
  <div class="zdrapki">
    <h3>Ostatnie zdrapki</h3>
    <table>
      <thead>
        <tr>
          <th>Zdrapka</th>
          <th>Status</th>
          <th>Akcja</th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($zdrapki_otwarte as $zdrapka) {
          echo '<tr id="zdrapka' . $zdrapka['id'] . '">';
          echo '<td>Love#' . $zdrapka['id'] . '</td>';
          if (!$zdrapka['data_otwarcia']) {
            echo '<td>Nie otworzona</td>';
            echo '<td><a class="link" href="/?oid=' . $zdrapka['id'] . '">otwórz</a></td>';
          } else {
            if ($zdrapka['wygrana'])
              echo '<td>Wygrana: ' . $zdrapka['nagroda'] . ' lt</td>';
            else
              echo '<td>Przegrana</td>';
            echo '<td><a class="link" target="_blank" href="/zdrapka.php?zid=' . $zdrapka['id'] . '">wyświetl</a></td>';
          }
          echo '</tr>';
        }
        ?>
      </tbody>
    </table>
  </div>
<?php } ?>