<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
  <center>
    <table class="formgeral">
      <tr>
        <td valign="top">   
          <fieldset>
            <legend>
              <b>Cadastro Logo</b>
            </legend>
            <table align="center" border="0" width="100%">
              <tr>
                <td  valign="top">
                  <table>
                    <tr>  
                      <td valign="top">
                        <b>Imagem Logo:</b>
                      </td>
                      <td valign='top'>
                        <?
                          db_input("uploadfilelogo", 30, 0, true, "file", 1);
                          db_input("namefilelogo", 30, 0, true, "hidden", 3);
                          db_input("codigo", 10, 0, true, "hidden", 3);
                        ?>
                        <div>
                          <strong style="color: #FF0000" id="uploadfilelogomensagem">
                            <?=(empty($sSrcPreviewLogo)?"Sem imagem definida!":"")?>
                          </strong>
                        </div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td align="right">
                  <img src="<?=(empty($sSrcPreviewLogo)?"imagens/none1.jpeg":$sSrcPreviewLogo)?>" align="right" 
                       width="95" height="120" id='previewlogo' style="border: 1px inset white">
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
      </tr>
      <tr>
        <td valign="top">         
          <fieldset>
            <legend>
              <b>Cadastro Figura</b>
            </legend>
            <table align="center" border="0" width="100%">
              <tr>
                <td valign="top">
                  <table>
                    <tr>  
                      <td valign="top">
                        <b>Imagem Figura:</b>
                      </td>
                      <td valign='top'>
                        <?
                          db_input("uploadfilefigura", 30, 0, true, "file", 1);
                          db_input("namefilefigura", 30, 0, true, "hidden", 3);
                        ?>
                        <div>
                          <strong style="color: #FF0000" id="uploadfilefiguramensagem">
                            <?=(empty($sSrcPreviewFigura)?"Sem imagem definida!":"")?>
                          </strong>
                        </div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td align="right">
                  <img src="<?=(empty($sSrcPreviewFigura)?"imagens/none1.jpeg":$sSrcPreviewFigura)?>" align="right" 
                       width="95" height="120" id='previewfigura' style="border: 1px inset white">
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
      </tr>
      <tr>
        <td valign="top">  
          <fieldset>
            <legend>
              <b>Cadastro Marca D'agua Institução</b>
            </legend>
            <table align="center" border="0" width="100%">
              <tr>
                <td valign="top">
                  <table>
                    <tr>  
                      <td valign="top">
                        <b>Imagem Marca D'agua Instituição:</b>
                      </td>
                      <td valign='top'>
                        <?
                          db_input("uploadfilemarcadagua", 30, 0, true, "file", 1);
                          db_input("namefilemarcadagua", 30, 0, true, "hidden", 3);
                          db_input("db21_imgmarcadaguaold", 10, 0, true, "hidden", 3);
                        ?>
                        <div>
                          <strong style="color: #FF0000" id="uploadfilemarcadaguamensagem">
                            <?=(empty($sSrcPreviewMarcaDAgua)?"Sem imagem definida!":"")?>
                          </strong>
                        </div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td align="right">
                  <img src="<?=(empty($sSrcPreviewMarcaDAgua)?"imagens/none1.jpeg":$sSrcPreviewMarcaDAgua)?>" 
                       align="right" width="95" height="120" id='previewmarcadagua' style="border: 1px inset white">
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
      </tr>
    </table>
    <table>
      <tr>
        <td>
          <b>Apenas será aceitos imagens do tipo "JPG,PNG e GIF", e com tamanho máximo de 
            <span style='color:blue'>100 KB</span>
          </b>
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td align="center">
          <input type="submit" id='btnSalvar' name="btnSalvar" Value='Salvar' onclick="return js_validaForm();">
        </td>
      </tr>
    </table>      
  </center>
</form>
<div id='uploadIframeBox' style='display: none;'></div>
<script>
function js_validaForm(){
  
  var sSrcLogo   = '<?=(empty($sSrcPreviewLogo)?"imagens/none1.jpeg":$sSrcPreviewLogo)?>';
  var sSrcFigura = '<?=(empty($sSrcPreviewFigura)?"imagens/none1.jpeg":$sSrcPreviewFigura)?>';
  
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