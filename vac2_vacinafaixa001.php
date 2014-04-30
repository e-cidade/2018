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
db_postmemory($HTTP_POST_VARS);
$oDaoVacina    = db_utils::getdao('vac_vacina');
$oDaoCgsUnd    = db_utils::getdao('cgs_und');
$db_opcao      = 1;
$db_botao      = true;
$iDepartamento = db_getsession("DB_coddepto");
$iDataini_dia  = date("d",db_getsession("DB_datausu"));
$iDataini_mes  = date("m",db_getsession("DB_datausu"));
$iDataini_ano  = date("Y",db_getsession("DB_datausu"));
$iDatafim_dia  = $iDataini_dia;
$iDatafim_mes  = $iDataini_mes;
$iDatafim_ano  = $iDataini_ano;
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
      <table width="790" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
            <center>
              <br><br>
              <fieldset style='width: 70%;'> 
                <legend><b> Relatório de vacinas por faixa etária: </b></legend>
                <form name="form1" method="post" action="">
                  <center>
                    <table border="0" style="margin-top: 6px;">
                      <tr>
                        <td>
                          <b>Período:</b>
                        </td>
                        <td nowrap >
                          <? 
                          db_inputdata('dDataini', @$iDataini_dia, @$iDataini_mes, @$iDataini_ano, 
                                       true, 'text', $db_opcao
                                      );
                          ?>
                          Á
                          <? 
                          db_inputdata('dDatafim', @$iDatafim_dia, @$iDatafim_mes, @$iDatafim_ano, 
                                       true, 'text', $db_opcao
                                      );
                          ?>
                        </td>
                      </tr>
                      <tr>
                        <td nowrap>
                          <b>Vacina:</b>
                        </td>
                        <td>
                          <?
                          $aX      = array();
                          $sSql    = $oDaoVacina->sql_query_file(null, "vc06_i_codigo,vc06_c_descr");
                          $rsDados = $oDaoVacina->sql_record($sSql);
                          $aX[0]   = 'Todas';
                          for ($iX = 0; $iX < $oDaoVacina->numrows; $iX++) {

                            $oDados                     = db_utils::fieldsmemory($rsDados, $iX);
                            $aX[$oDados->vc06_i_codigo] = $oDados->vc06_c_descr;

                          }          
                          db_select('aVacina', $aX, true, $db_opcao, "");
                          ?>
                        </td>
                      </tr>
                    </table>
                  </center>
                  <br>
                  <input name = "inprimir" type = "button" id = "inprimir" value = "Gerar Relatorio" 
                         onClick = "js_imprimir()">
                  <input name = "limpar" type = "button" id = "limpar" value = "Limpar"
                         onClick = "js_limpar(1)">
                </form>
              </fieldset>
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
js_tabulacaoforms("form1", "dDataini", true, 1, "dDataini", true);
js_limpar(0);
function js_imprimir() {

  sErro = '';
  if ($F('dDataini') == '') { 
    sErro = 'Data inicial não informada ';
  } else if ($F('dDatafim') == '') {
    sErro = 'Data final não informada ';
  }
  sStr = '?dDataini='+$F('dDataini')+'&dDatafim='+$F('dDatafim')+'&iVacina='+$F('aVacina');
  oJan = window.open('vac2_vacinafaixa002.php'+sStr,'',
                     'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 '
                    );
  oJan.moveTo(0,0);

}

function js_limpar(ini) {
  
  if (ini != 0) {
    
    $('dDataini').value = '';
    $('dDatafim').value = '';

  }
  $('aVacina').selectedIndex = 0;
  
}
</script>