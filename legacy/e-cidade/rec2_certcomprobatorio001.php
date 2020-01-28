<?php
/**
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_rhpesrescisao_classe.php"));

db_postmemory($_POST);

$clrhpesrescisao = new cl_rhpesrescisao;
$rotulocampo = new rotulocampo;

$rotulocampo->label("rh01_regist");
$rotulocampo->label("rh02_seqpes");
$rotulocampo->label("z01_nome");

if(empty($rh01_regist)){

  $datai_dia = '';
  $datai_mes = '';
  $datai_ano = '';
}

if (isset($rh01_regist) && !empty($rh01_regist)) {

  $ano             = db_anofolha();
  $mes             = db_mesfolha();
  $rsRhpesrescisao = $clrhpesrescisao->sql_record( $clrhpesrescisao->sql_query_ngeraferias( null,
                                                                                            'rh05_recis',
                                                                                            null,
                                                                                            "rh02_regist = $rh01_regist and rh02_anousu = $ano and rh02_mesusu = $mes" ));

  if ($rsRhpesrescisao && $clrhpesrescisao->numrows > 0) {

    $oRecisao = db_utils::fieldsMemory($rsRhpesrescisao, 0);

    $datai_dia = date('d', strtotime($oRecisao->rh05_recis));
    $datai_mes = date('m', strtotime($oRecisao->rh05_recis));
    $datai_ano = date('Y', strtotime($oRecisao->rh05_recis));
  } else {

    $datai_dia = date('d', db_getsession('DB_datausu'));
    $datai_mes = date('m', db_getsession('DB_datausu'));
    $datai_ano = date('Y', db_getsession('DB_datausu'));
  }

}
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <style type="text/css">
      #datai{
        width: 78px;
      }
    </style>
  </head>
  <body class="body-default">
    <div class="container">
      <form name="form1" method="post" action="" >
        <fieldset>
          <legend>Certidão de Tempo de Serviço</legend>

          <table>
            <tr>
              <td nowrap title="<?php echo $Trh01_regist; ?>">
                <label class="bold" for="rh01_regist" id="lbl_rh01_regist">
                  <?php db_ancora($Srh01_regist . ':', "js_pesquisarh01_regist(true);", 1); ?>
                </label>
              </td>
              <td>
                <?php
                  db_input('rh01_regist', 8, $Irh01_regist, true, 'text', 1, " onchange='js_pesquisarh01_regist(false);'");
                  db_input('z01_nome', 30, $Iz01_nome, true, 'text', 3, '');
                ?>
              </td>
            </tr>

            <tr>
              <td nowrap title="Data Certidão">
                <label class="bold" for="datai" id="lbl_datai">Data de Certidão:</label>
              </td>
              <td>
                <?php
                  db_inputdata("datai", @$datai_dia, @$datai_mes, @$datai_ano, true, 'text', 1);
                ?>
              </td>
            </tr>

            <tr>
              <td nowrap title="Número Certidão">
                <label class="bold" for="numcert" id="lbl_numcert">Número de Certidão:</label>
              </td>
              <td>
                <?php
                  db_input('numcert', 8, 1, true, 'text', 1, "");
                ?>
              </td>
            </tr>

            <tr>
              <td nowrap title="Emissor">
                <label class="bold" for="emissor" id="lbl_emissor">
                  <?php
                    db_ancora('Emissor:', "js_pesquisaemissor(true);", 1);
                  ?>
                </label>
              </td>
              <td>
                <?php
                  db_input('emissor', 8,'' , true, 'text', 1, " onchange='js_pesquisaemissor(false);'");
                  db_input('z01_nomeemissor', 30,'' , true, 'text', 3, '');
                ?>
              </td>
            </tr>
          </table>
        </fieldset>

        <input name="relatorio" id="relatorio" type="button" value="Processar" onclick="js_emite();" >

      </form>
    </div>

    <?php
      db_menu( db_getsession("DB_id_usuario"),
               db_getsession("DB_modulo"),
               db_getsession("DB_anousu"),
               db_getsession("DB_instit") );
    ?>
  </body>
  <script>
    var sMensagens = "recursoshumanos.rh.rec2_certcomprobatorio.";

    function js_limparCampos(){

      $('rh01_regist').value = '';
      $('z01_nome').value    = '';
      $('datai').value       = '';
      $('numcert').value     = '';
      $('emissor').value     = '';
      $('z01_nomeemissor').value = '';
    }

    function js_emite() {

      if (isNaN(document.form1.rh01_regist.value)) {

        alert( _M( sMensagens + "matricula_invalida") );
        $('rh01_regist').value = '';
        document.form1.rh01_regist.focus();
        return false
      }

      if (document.form1.rh01_regist.value == '' || isNaN(document.form1.rh01_regist.value)) {
        alert( _M( sMensagens + "matricula_obrigatoria") );
        document.form1.rh01_regist.focus();
        return false
      }

      if (document.form1.datai_dia.value == '') {
        alert( _M(sMensagens + "data_invalida") );
        document.form1.datai.focus();
        return false
      }

      if (document.form1.numcert.value.trim() != '') {

        if (isNaN(document.form1.numcert.value)) {

          alert( _M( sMensagens + "numerocertidao_invalido") );
          $('numcert').value = '';
          document.form1.numcert.focus();
          return false
        }
      }
      if (isNaN(document.form1.emissor.value)) {

        alert( _M( sMensagens + "emissor_invalido") );
        $('emissor').value = '';
        document.form1.emissor.focus();
        return false
      }

      if (document.form1.emissor.value == '' || isNaN(document.form1.emissor.value)) {
        alert( _M(sMensagens + "emissor_obrigatorio") );
        document.form1.emissor.focus();
        return false
      }

      qry  = "?regist="+ document.form1.rh01_regist.value;
      qry += "&datacert=" + document.form1.datai_ano.value+'-'+document.form1.datai_mes.value+'-'+document.form1.datai_dia.value;
      qry += "&numcert=" + document.form1.numcert.value;
      qry += "&emissor=" + document.form1.emissor.value;

      jan = window.open( 'rec2_certcomprobatorio002.php' + qry,
                         '',
                         'width=' + (screen.availWidth-5) + ',height='+(screen.availHeight-40) + ',scrollbars=1,location=0 ' );
      jan.moveTo(0,0);

      js_limparCampos();
    }

    function js_pesquisaemissor(mostra){

      if (mostra == true) {
        js_OpenJanelaIframe( 'CurrentWindow.corpo',
                             'db_iframe_rhpessoal',
                             'func_rhpessoal.php?filtro_lotacao=true&funcao_js=parent.js_mostraemissor1|rh01_regist|z01_nome&instit=<?=(db_getsession("DB_instit"))?>',
                             'Pesquisa',
                             true );
      } else {

          js_OpenJanelaIframe( 'CurrentWindow.corpo',
                               'db_iframe_rhpessoal',
                               'func_rhpessoal.php?filtro_lotacao=true&pesquisa_chave=' + document.form1.emissor.value
                               + '&funcao_js=parent.js_mostraemissor&instit=<?=(db_getsession("DB_instit"))?>',
                               'Pesquisa',
                               false );
      }
    }

    function js_mostraemissor(chave, erro) {

      document.form1.z01_nomeemissor.value = chave;

      if (erro == true) {
        document.form1.emissor.focus();
        document.form1.emissor.value = '';
      }
    }

    function js_mostraemissor1(chave1, chave2) {

      document.form1.emissor.value         = chave1;
      document.form1.z01_nomeemissor.value = chave2;
      db_iframe_rhpessoal.hide();
    }

    function js_pesquisarh01_regist(mostra) {

      if (mostra == true) {
        js_OpenJanelaIframe( 'CurrentWindow.corpo',
                             'db_iframe_rhpessoal',
                             'func_rhpessoal.php?filtro_lotacao=true&funcao_js=parent.js_mostrapessoal1|rh01_regist|z01_nome&instit=<?=(db_getsession("DB_instit"))?>',
                             'Pesquisa',
                             true );
      } else {

        if (document.form1.rh01_regist.value != '') {
          js_OpenJanelaIframe( 'CurrentWindow.corpo',
                               'db_iframe_rhpessoal',
                               'func_rhpessoal.php?filtro_lotacao=true&testarescisao=true&pesquisa_chave=' + document.form1.rh01_regist.value
                               + '&funcao_js=parent.js_mostrapessoal&instit=<?=(db_getsession("DB_instit"))?>',
                               'Pesquisa',
                               false );
        } else {
          document.form1.z01_nome.value = '';
          document.form1.submit();
        }
      }
    }

    function js_mostrapessoal(chave, erro) {

      document.form1.z01_nome.value = chave;
      if (erro == true) {

        document.form1.rh01_regist.focus();
        document.form1.rh01_regist.value = '';
      } else {
        document.form1.submit();
      }
    }

    function js_mostrapessoal1(chave1, chave3) {

      document.form1.rh01_regist.value = chave1;
      document.form1.z01_nome.value    = chave3;

      db_iframe_rhpessoal.hide();
      document.form1.submit();
    }
  </script>
</html>
