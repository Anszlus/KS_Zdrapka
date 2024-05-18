<?php
if (!defined('_IN_APP')) {
  header('Location: /');
  exit();
}
?>
<a class="link back-link" href="/">&#60; powrót</a>

<div id="zdrapka">
  <canvas id="zdrapka_canvas" width="236" height="107"></canvas>
</div>

<style>
  .back-link {
    border-bottom: 30px;
  }

  #zdrapka {
    margin: auto;
    width: 300px;
    height: 300px;
    background-image: url('/zdrapka.php?zid=<?= $zdrapka['id'] ?>&open');
  }

  #zdrapka > #zdrapka_canvas {
    margin-top: 126px;
    margin-left: 35px;
    height: 107px;
    width: 237px;
  }
</style>

<script>
  const canvas = document.getElementById('zdrapka_canvas');
  const ctx = canvas.getContext('2d');

  // Wypełnienie canvas czarnym tłem
  ctx.fillStyle = '#808080';
  ctx.fillRect(0, 0, canvas.width, canvas.height);

  let isScratching = false;
  let isSave = false;

  canvas.addEventListener('mousedown', startScratch);
  canvas.addEventListener('touchstart', startScratch);

  canvas.addEventListener('mouseup', stopScratch);
  canvas.addEventListener('mouseleave', stopScratch);
  canvas.addEventListener('touchend', stopScratch);

  canvas.addEventListener('mousemove', scratch);
  canvas.addEventListener('touchmove', scratch);

  function startScratch(e) {
    isScratching = true;
    scratch(e);
  }

  function stopScratch() {
    isScratching = false;
    checkScratchPercentage();
  }

  function scratch(e) {
    if (!isScratching) {
      return;
    }
    const rect = canvas.getBoundingClientRect();
    let x, y;

    if (e.touches) {
      x = e.touches[0].clientX - rect.left;
      y = e.touches[0].clientY - rect.top;
    } else {
      x = e.clientX - rect.left;
      y = e.clientY - rect.top;
    }

    ctx.globalCompositeOperation = 'destination-out';
    ctx.beginPath();
    ctx.arc(x, y, 15, 0, Math.PI * 2);
    ctx.fill();
    ctx.closePath();
  }

  function checkScratchPercentage() {
    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
    const pixels = imageData.data;
    let transparentPixelCount = 0;

    for (let i = 3; i < pixels.length; i += 4) {
      if (pixels[i] === 0) {
        transparentPixelCount++;
      }
    }

    const totalPixels = canvas.width * canvas.height;
    const transparentPercentage = (transparentPixelCount / totalPixels) * 100;

    if (transparentPercentage >= 60 && !isSave) {
      isSave = true;
      (async () => {
        const rawResponse = await fetch('/index.php?oid=<?= $zdrapka['id'] ?>', {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({})
        });
        const content = await rawResponse.json();
      })();
    }
  }
</script>