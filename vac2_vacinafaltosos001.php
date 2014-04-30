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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
require_once("libs/db_stdlibwebseller.php");
db_postmemory($HTTP_POST_VARS);
$oDaoVacina    = db_utils::getdao('vac_vacina');
$oDaoCgsUnd    = db_utils::getdao('cgs_und');
$db_opcao      = 1;
$db_botao      = true;
$iDepartamento = db_getsession("DB_coddepto");
$dHoje         = date("d/m/Y",db_getsession("DB_datausu"));
$aHoje         = explode("/",$dHoje);
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
    db_app::load("prototype.js, datagrid.widget.js, strings.js, webseller.js");
    db_app::load("scripts.js, grid.style.css, estilos.css"); 
    ?>
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
    <br><br>
      <center>
        <table width="650" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
              <center>
                <form name="form1" method="post" action="">
                  <fieldset style='width: 630;  margin-bottom: 8px;'>
                    <legend><b>Relatório de Faltosos e Aprazamento:</b></legend>
                    <center>
                      <fieldset style='width: 610; margin-bottom: 8px; margin-top: 6px;'>
                        <center>
                          <table width="100%" align="center">
                            <tr>
                              <td align="center" onclick="Document.form1.tipo[0].checked = true;">
                                <input type="radio" name="tipo" id="tipo" value="1" 
                                       onclick="js_tipo(1);" checked>
                                Faltoso
                              </td>
                              <td align="center">
                                <input type="radio" name="tipo" id="tipo" value="2" 
                                       onclick="js_tipo(2);" >
                                Aprazamento
                              </td>
                            </tr>
                          </table>
                        </center>
                      </fieldset>
                      <fieldset style='width: 610; margin-bottom: 6px;'>
                        <table>
                          <tr>
                           <td>
                             <b>Período:</b>
                           </td>
                           <td nowrap >
                             <? 
                             db_inputdata('dDataIni', @$iDataIni_dia, @$iDataIni_mes, @$iDataIni_ano, 
                                          true, 'text', $db_opcao
                                         );
                             ?>
                             Á
                             <? 
                             db_inputdata('dDataFim', @$iDataFim_dia, @$iDataFim_mes, @$iDataFim_ano, 
                                          true, 'text', $db_opcao
                                         );
                             ?>
                           </td>
                         </tr>
                       </table>
                     </fieldset>
                     <fieldset style='width: 610; margin-bottom: 6px;'>
                       <legend><b>Faixa Etária:</b></legend>
                       <table>
                         <tr>
                           <td>
                           </td>
                           <td>
                             <b>Ano(s)</b>
                           </td>
                           <td>
                             <b>Mes(es)</b>
                           </td>
                           <td>
                           </td>
                           <td>
                             <b>Ano(s)</b>
                           </td>
                           <td>
                             <b>Mes(es)</b> 
                           </td>
                         </tr>
                         <tr align="center">
                           <td>
                             <b>Idade:</b>
                           </td>
                           <? 
                           $iAnoIni = '00';
                           $iMesIni = '00';
                           $iAnoFim = '99';
                           $iMesFim = '00';
                           ?>
                          <td>
                            <?
                            db_input('iAnoIni', 4, "", true, 'text', 1, '');
                            ?>
                          </td>
                          <td>
                            <?
                            db_input('iMesIni', 2, "", true, 'text', 1, '');
                            ?>
                          </td>
                          <td>
                            <b>Á </b>
                          </td>
                          <td>
                            <?
                            db_input('iAnoFim', 4, "", true, 'text', 1, '');
                            ?>
                          </td>
                          <td>
                            <?
                            db_input('iMesFim', 2, "", true, 'text', 1, '');
                            ?>
                          </td>
                        </tr>
                      </table>
                    </fieldset>
                    <fieldset style='width: 610; margin-bottom: 6px;'>
                      <legend><b>Vacinas:</b></legend>
                      <table>
                        <tr>
                          <td>
                            <?
                            $sSql    = $oDaoVacina->sql_query("","vc06_i_codigo,vc06_c_descr");
                            $rsDados = $oDaoVacina->sql_record($sSql);
                            db_multiploselect("vc06_i_codigo",
                                              "vc06_c_descr",
                                              "nselecionados",
                                              "sselecionados",
                                              $rsDados,
                                              array(),
                                              5,
                                              250
                                             );
                            ?>
                          </td>
                        </tr>
                      </table>
                    </fieldset>
                    <fieldset style='width: 610; margin-bottom: 10px;'>
                      <legend><b>Agrupado por:</b></legend>
                      <center>
                        <table width="100%" align="center">
                          <tr>
                            <td align="center">
                              <input type="radio" name="agrupar" id="agrupar" value="1" checked>
                              Vacina
                            </td>
                            <td align="center">
                              <input type="radio" name="agrupar" id="agrupar" value="2">
                              Nome
                            </td>
                            <td align="center">
                              <input type="radio" name="agrupar" id="agrupar" value="3">
                              Bairro
                            </td>
                          </tr>
                        </table>
                      </center>
                    </fieldset>
                    <input name = "inprimir"  type = "button" id = "inprimir" 
                           value = "Gerar Relatorio" onClick = "js_imprimir()">
                    <input name = "limpar"  type = "button" id = "limpar" value = "Limpar" 
                           onClick = "js_limpar()">
                  </center>
                </fieldset>
              </form>
            </center>
          </td>
        </tr>
      </table>
    </center>
  </body>
  <? 
  db_menu(db_getsession("DB_id_usuario"),
          db_getsession("DB_modulo"),
          db_getsession("DB_anousu"),
          db_getsession("DB_instit")
         );
  ?>
</html>
<script>
js_tipo(1);
js_tabulacaoforms("form1", "dDataIni", true, 1, "dDataIni", true);
function js_tipo(value) {
  
  if (value == 1) {
    
    $('dDataIni').value                    = '';
    $('dDataFim').value                    = '<?=somaDataDiaMesAno($aHoje[0], $aHoje[1], $aHoje[2], -1, 0, 0, 1)?>';
    $('dDataIni').disabled                 = false;
    document.form1.dtjs_dDataIni.disabled  = false;
    $('dDataFim').disabled                 = true;
    document.form1.dtjs_dDataFim.disabled  = true;

  } else {

    $('dDataIni').value                   = '<?=$dHoje?>';
    $('dDataFim').value                   = '';
    $('dDataIni').disabled                = true;
    document.form1.dtjs_dDataIni.disabled = true;
    $('dDataFim').disabled                = false;
    document.form1.dtjs_dDataFim.disabled = false;
    
  }
  
}

function js_imprimir() {

  var sErro = '';
  if ($F('dDataIni') == '') { 
    sErro = 'Data inicial não informada ';
  } else if ($F('dDataFim') == '') {
    sErro = 'Data final não informada ';
  }
  var iTam = $('sselecionados').length;
  if (iTam == 0) {
    sErro = "Selecione uma Vacina!";
  }
  if (sErro != '') {
	  
    alert(sErro);
    return false;
    
  }
  var sLista = '';
  var sSep   = '';
  for (iX = 0; iX < iTam; iX++) {

    sLista += sSep+$('sselecionados').options[iX].value;
    sSep    = ",";

  }
  sStr  = '?dDataIni=' + document.getElementById('dDataIni').value;
  sStr += '&dDataFim=' + document.getElementById('dDataFim').value;
  for (i = 0; i < document.form1.tipo.length; i++) {  

	if (document.form1.tipo[i].checked) {
      sTipo = document.form1.tipo[i].value;
    } 
    
  }
  iGrupo = 0;
  for (iInd = 0; iInd < document.form1.agrupar.length; iInd++) {
	  
    if (document.form1.agrupar[iInd].checked == true) {
      iGrupo = document.form1.agrupar[iInd].value; 
    } 
    
  }
  if (iGrupo == 0) {
    
    alert('Selecione um Grupo!');
    return false;
    
  }
  sStr += '&iTipo='+sTipo+'&iGrupo='+iGrupo;
  sStr += '&sFaixainimes='+$F('iMesIni')+'&sFaixainiano='+$F('iAnoIni');
  sStr += '&sFaixafimmes='+$F('iMesFim')+'&sFaixafimano='+$F('iAnoFim');
  sStr += '&sVacinas='+sLista;
  jan = window.open('vac2_vacinafaltosos002.php'+sStr,
                    '',
                    'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 '
                   );
  jan.moveTo(0,0);

}

function js_limpar() {

  $('iMesIni').value  = '00';
  $('iAnoIni').value  = '00';
  $('iMesFim').value  = '00';
  $('iAnoFim').value  = '99';
  if ($('sselecionados').length > 0) {
    js_db_multiposelect_incluir_todos(document.form1.sselecionados, document.form1.nselecionados);
  }
  
}
</script>