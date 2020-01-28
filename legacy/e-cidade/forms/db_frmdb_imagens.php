<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */
?>
<form name="form1" id='form1' method="post" action="" enctype="multipart/form-data">

  <fieldset>
    <legend>Cadastro Logo</legend>
    <table class="form-container">
      <tr>
        <td valign="top">
          <table>
            <tr>
              <td valign="top">
                <strong>Imagem Logo:</strong>
              </td>
              <td valign="top">
                <?php
                  db_input("uploadfilelogo", 30, 0, true, "file", 1);
                  db_input("namefilelogo", 30, 0, true, "hidden", 3);
                  db_input("codigo", 10, 0, true, "hidden", 3);
                ?>
                <div>
                  <strong style="color: #FF0000" id="uploadfilelogomensagem">
                    <?php echo (empty($sSrcPreviewLogo)?"Sem imagem definida!":""); ?>
                  </strong>
                </div>
              </td>
            </tr>
          </table>
        </td>
        <td align="right">
          <img src="<?php echo (empty($sSrcPreviewLogo)?"imagens/none1.jpeg":$sSrcPreviewLogo); ?>" align="right"
               width="95" height="120" id='previewlogo' style="border: 1px inset white">
        </td>
      </tr>
    </table>
  </fieldset>

  <fieldset>
    <legend>Cadastro Figura</legend>
    <table class="form-container">
      <tr>
        <td valign="top">
          <table>
            <tr>
              <td valign="top">
                <strong>Imagem Figura:</strong>
              </td>
              <td valign="top">
                <?php
                  db_input("uploadfilefigura", 30, 0, true, "file", 1);
                  db_input("namefilefigura", 30, 0, true, "hidden", 3);
                ?>
                <div>
                  <strong style="color: #FF0000" id="uploadfilefiguramensagem">
                    <?php echo (empty($sSrcPreviewFigura)?"Sem imagem definida!":""); ?>
                  </strong>
                </div>
              </td>
            </tr>
          </table>
        </td>
        <td align="right">
          <img src="<?php echo (empty($sSrcPreviewFigura)?"imagens/none1.jpeg":$sSrcPreviewFigura); ?>" align="right"
               width="95" height="120" id='previewfigura' style="border: 1px inset white">
        </td>
      </tr>
    </table>
  </fieldset>

  <fieldset>
    <legend>Cadastro Marca D'água Institução</legend>
    <table class="form-container">
      <tr>
        <td valign="top">
          <table>
            <tr>
              <td valign="top">
                <strong>Imagem Marca D'água Instituição:</strong>
              </td>
              <td valign="top">
                <?php
                  db_input("uploadfilemarcadagua", 30, 0, true, "file", 1);
                  db_input("namefilemarcadagua", 30, 0, true, "hidden", 3);
                  db_input("db21_imgmarcadaguaold", 10, 0, true, "hidden", 3);
                ?>
                <div>
                  <strong style="color: #FF0000" id="uploadfilemarcadaguamensagem">
                    <?php echo (empty($sSrcPreviewMarcaDAgua)?"Sem imagem definida!":""); ?>
                  </strong>
                </div>
              </td>
            </tr>
          </table>
        </td>
        <td align="right">
          <img src="<?php echo (empty($sSrcPreviewMarcaDAgua)?"imagens/none1.jpeg":$sSrcPreviewMarcaDAgua); ?>"
               align="right" width="95" height="120" id='previewmarcadagua' style="border: 1px inset white">
        </td>
      </tr>
    </table>
  </fieldset>

  <div class="form-container">
    <p><strong>Apenas serão aceitas imagens nos formatos "JPG" e "PNG", com tamanho máximo de <span style="color:blue">100 KB</span>.</strong></p>
    <input type="submit" id='btnSalvar' name="btnSalvar" Value='Salvar' onclick="return js_validaForm();" />
  </div>

</form>
<div id='uploadIframeBox' style='display: none;'></div>
<script type="text/javascript">
function js_validaForm(){

  var sSrcLogo   = '<?php echo (empty($sSrcPreviewLogo)?"imagens/none1.jpeg":$sSrcPreviewLogo); ?>';
  var sSrcFigura = '<?php echo (empty($sSrcPreviewFigura)?"imagens/none1.jpeg":$sSrcPreviewFigura); ?>';

  if (sSrcLogo == 'imagens/none1.jpeg' || sSrcFigura == 'imagens/none1.jpeg') {

    if ( ( $('uploadfilelogo').value == '' ) || ( $('uploadfilefigura').value == '' ) ) {

      alert('Favor cadastrar as imagens de logo e figura!');
      return false;
    }
  }
}

 $('uploadfilelogo').observe('change', function() {
   js_criarIframeBox('uploadfilelogo', 'namefilelogo', 'previewlogo');
 });

 $('uploadfilefigura').observe('change', function() {
   js_criarIframeBox('uploadfilefigura', 'namefilefigura', 'previewfigura');
 });

 $('uploadfilemarcadagua').observe('change', function() {
   js_criarIframeBox('uploadfilemarcadagua', 'namefilemarcadagua', 'previewmarcadagua');
 });

 function js_criarIframeBox(sIdCampo, sIdArquivo, sIdPreview) {

   js_divCarregando('Aguarde... carregando imagem.', 'msgbox');

   var iFrame      = document.createElement("iframe");
   var sParametros = "clone=form1&idcampo="+sIdCampo+"&idarquivo="+sIdArquivo+"&idpreview="+sIdPreview
   iFrame.src      = "func_uploadfileimages.php?"+sParametros;
   iFrame.id       = 'uploadIframe';
   iFrame.width    = '100%';

   $('uploadIframeBox').appendChild(iFrame);
 }

 function js_endloading() {

   js_removeObj('msgbox');
   $('uploadIframeBox').removeChild($('uploadIframe'));
 }

 function js_previewMarcaDAgua() {

   var iOidMarcaDAgua = $('db21_imgmarcadaguaold').value;
   if (iOidMarcaDAgua != '') {

     $('previewmarcadagua').src                  = 'func_mostrarimagem.php?oid='+iOidMarcaDAgua+'&type=false';
     $('uploadfilemarcadaguamensagem').innerHTML = '';
   } else {

     $('previewmarcadagua').src                  = 'imagens/none1.jpeg';
     $('uploadfilemarcadaguamensagem').innerHTML = 'Sem imagem definida!';
   }
 }
</script>