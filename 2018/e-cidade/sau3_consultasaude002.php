<?php
/*
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
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/verticalTab.widget.php"));

$oDaoCgsUnd = new cl_cgs_und();
$oDaoCgsUnd->rotulo->label();

?>
<html>
<head>
<title>DBSeller Informática Ltda - Página Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php
  $sLib  = "scripts.js,prototype.js,datagrid.widget.js,strings.js,grid.style.css,";
  $sLib .= "estilos.css,/widgets/dbautocomplete.widget.js,webseller.js";
  db_app::load($sLib);
?>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/tab.style.css" rel="stylesheet" type="text/css">
<style>

body, html {

  width  : 100%;
  height : 100%;
  padding: 0px;
  margin : 0px;
  border : 0px;
}

body {
  padding: 5px;
  width:   calc(100% - 20px);
  height:  calc(100% - 20px);
}

.container-abas {
  padding-top: 5px;
  height: calc(100% - 50px);
}

.container-paciente {
  height: 50px;
}

#fieldDadosSaude {
  height: calc(100% - 20px);
}

#fieldDadosSaude > table:nth-child(2) {
  height: 100%;
}
</style>
</head>
<body class="body-default">
  <div class="container-paciente">
    <fieldset class="form-container">
      <legend>Paciente:</legend>
      <table align="left">
        <tr>
          <td nowrap title="<?=@$Tz01_i_cgsund?>">
            <?=$Lz01_i_cgsund?>&nbsp;
          </td>
          <td nowrap>
            <?db_input('z01_i_cgsund', 5, $Iz01_i_cgsund, true, 'text', 3, '');?>
          </td>
          <td title='<?=$Tz01_v_nome?>' nowrap>
            <?=$Lz01_v_nome?>&nbsp;
          </td>
          <td nowrap>
            <?db_input('z01_v_nome', 40, @$Iz01_v_nome, true, 'text', 3, '');?>
          </td>
        </tr>
      </table>
    </fieldset>
  </div>
  <div class="container-abas">
    <fieldset id="fieldDadosSaude">
      <legend>Dados da Saúde:</legend>
      <?php
      $oMenuVertical = new verticalTab('menus','100%');
      $oMenuVertical->add(
        'info',
        'Informações Pessoais', 
        'sau3_consultasaude004.php?z01_i_cgsund='.$z01_i_cgsund
      );
      $oMenuVertical->add(
        'documentos', 
        'Documentos', 
        'sau1_cgs_doc002.php?chavepesquisa='.$z01_i_cgsund.'&lReadOnly=true'
      );
      
      $oMenuVertical->add(
        'cartao_sus', 
        'Cartão SUS', 
        'sau3_cartaosuscgs.iframe.php?z01_i_cgsund='.$z01_i_cgsund
      );
      $oMenuVertical->add(
        'agendamentos', 
        'Agendamentos', 'sau3_agendamentoscgs.iframe.php?z01_i_cgsund='.$z01_i_cgsund.'&iLinhas='.$iLinhas
      );
      $oMenuVertical->add(
        'atendimentos',
        'Atendimentos', 
        'sau3_prontuarioscgs.iframe.php?z01_i_cgsund='.$z01_i_cgsund.'&iLinhas='.$iLinhas
      );

      $oMenuVertical->add('farmacia', 'Farmácia', 'sau3_consultasaude006.php?z01_i_cgsund='.$z01_i_cgsund.
                          '&iLinhas='.$iLinhas);
      $oMenuVertical->add('laboratorio', 'Laboratorio', 'sau3_examescgs.iframe.php?z01_i_cgsund='.$z01_i_cgsund.
                          '&iLinhas='.$iLinhas);
      $oMenuVertical->add('tfd', 'TFD', 'sau3_pedidostfdcgs.iframe.php?z01_i_cgsund='.$z01_i_cgsund.
                          '&iLinhas='.$iLinhas);
      $oMenuVertical->add('vacinas', 'Vacinas', 'sau3_vacinascgs.iframe.php?z01_i_cgsund='.$z01_i_cgsund.
                          '&iLinhas='.$iLinhas);
      $oMenuVertical->add('hiperdia', "Hiperdia", 'sau3_consultasaude005.php?z01_i_cgsund='.$z01_i_cgsund.
                          '&iLinhas='.$iLinhas);
      if (db_permissaomenu(date('Y'), db_getsession('DB_modulo'), 8813) == 'true') {
        $oMenuVertical->add('cid', "CID's", 'sau3_consultacid001.php?lPopup=true&z01_i_cgsund='.$z01_i_cgsund.
                            '&iLinhas='.$iLinhas);
      }


      $oMenuVertical->add('imprimir', 'Imprimir', 'sau2_consultageral001.php?z01_i_cgsund='.$z01_i_cgsund);
      
      $oMenuVertical->show();
      ?>
    </fieldset>
  </div>
</body>
<script>

js_getInfoCgs();

function js_getInfoCgs() {

  var oParam  = new Object();
	oParam.exec = "getInfoCgs";
	oParam.iCgs = $F('z01_i_cgsund');

  if($F('z01_i_cgsund') != '') {
    js_webajax(oParam, 'js_retornogetInfoCgs', 'sau4_ambulatorial.RPC.php');
  }
}

function js_retornogetInfoCgs(oRetorno) {

  oRetorno = JSON.parse(oRetorno.responseText);
  $('z01_v_nome').value   = oRetorno.z01_v_nome.urlDecode();
}

</script>
</html>