<?php
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");

$dataFinalDia = date('d');
$dataFinalMes = date('m');
$dataFinalAno = date('Y');
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php 
    db_app::load("estilos.css");
    db_app::load("prototype.js");
    db_app::load("scripts.js");
    db_app::load("strings.js");
  ?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">

  <center>

    <fieldset style="width:230px;margin-top:30px;">
      <legend>Recibos integrados com WEBISS:</legend>

      <table border="0" width="100%">

        <tr>
          <td width="90" nowrap title="Data inicial da integração" >
             <strong>Data inicial: </strong>
          </td>
          <td>
            <?php db_inputdata('dataInicial',null, null, null, true, 'text', 1); ?>
          </td>
        </tr>

        <tr>
          <td width="90" nowrap title="Data final da integração" >
             <strong>Data final: </strong>
          </td>
          <td>
            <?php db_inputdata('dataFinal', $dataFinalDia, $dataFinalMes, $dataFinalAno, true, 'text', 1); ?>
          </td>
        </tr>

      </table>
        
    </fieldset>

    <br />
    <input name="processar" type="button" value="Processar" onclick="js_processar();">
    
  </center>

  <?php db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit")); ?>

</body>
</html>
<script type="text/javascript">

/**
 * Processar
 * - Abre janela com PDF
 *
 * @access public
 * @return void
 */
function js_processar() {

  var sDataInicial = js_formatar( $('dataInicial').value, 'd');
  var sDataFinal   = js_formatar( $('dataFinal').value, 'd');

  /**
   * Data inicial vazia 
   */
  if ( js_empty(sDataInicial) ) {

    alert('Data inicial não informada.');
    return false;
  }

  /**
   * Diferenca entra duas datas, retorna 'i' se forem iguais e false se primeira data é maior 
   */
  var diferencaDatas = js_diferenca_datas(sDataInicial, sDataFinal, 3);
  
  /**
   * Valida datas
   */
  if ( !js_empty(sDataFinal) && diferencaDatas != 'i' && diferencaDatas ) {

    alert('Data inicial maior que data final.');
    return false;
  }

  /**
   * Tamanho da janela 
   */
  var iWidth  = screen.availWidth - 5;
  var iHeight = screen.availHeight - 40;

  var sArquivo = "iss2_relatorioreciboswebiss002.php?sDataInicial=" + sDataInicial + "&sDataFinal=" + sDataFinal;
  var oJanela  = window.open(sArquivo, 'Relatório', 'width='+ iWidth + ', height=' + iHeight + ',scrollbars=1,location=0');

  oJanela.moveTo(0, 0);
}

</script>