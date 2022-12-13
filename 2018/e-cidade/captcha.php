<div>
    <div class="container-captcha-controllers">

      <!-- Reload -->
      <a href="#" tabindex="-1" title="Clique para recarregar a imagem" onclick="return reloadCaptcha();" class="captcha-controllers-images">
         <img src="./securimage/images/refresh.png" alt="Clique para recarregar a imagem" onclick="this.blur()" />
      </a>
      <!-- Audio -->
      <object type="application/x-shockwave-flash" data="./securimage/securimage_play.swf?audio_file=./securimage/securimage_play.php&icon_file=./securimage/images/audio.png" height="20" width="20">
        <param name="movie" value="./securimage/securimage_play.swf?audio_file=./securimage/securimage_play.php&icon_file=./securimage/images/audio.png" />
      </object>
    </div>
    <img id="siimage" src="./carregaCaptcha.php?sid=<?php echo md5(uniqid()); ?>" alt="Digite os caracteres no campo abaixo" class="imgCaptcha" />

    <!-- Informe captcha -->
    <div class="captcha-controllers-input">
      <label for="ct_captcha">O que você vê acima?</label>
      <input type="text" id="ct_captcha" name="ct_captcha" maxlength="8" />
    </div>
</div>
<script type="text/javascript">

  function reloadCaptcha() {

    document.getElementById('siimage').src = './carregaCaptcha.php?sid=' + Math.random();
    this.blur();
  }
</script>