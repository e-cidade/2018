<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

require_once('libs/db_stdlib.php');
require_once('libs/db_conecta.php');
require_once('libs/db_sessoes.php');
require_once('libs/db_usuariosonline.php');
require_once('libs/db_utils.php');
require_once('dbforms/db_funcoes.php');

$oDaoSauMotivoAusencia = db_utils::getdao('sau_motivo_ausencia');
$oRotulo               = new rotulocampo;
$oRotulo->label('sd06_i_especmed');
$oRotulo->label('sd06_i_tipo');
$oRotulo->label('rh70_descr');
$oRotulo->label('sd06_d_inicio');
$oRotulo->label('sd06_d_fim');
$oRotulo->label('sd06_c_horainicio');
$oRotulo->label('sd06_c_horafim');
$oRotulo->label('z01_nome');
$oRotulo->label('descrdepto');

function novoHorario($iId, $sHoraIni, $sHoraFim) {
?>
  <td>
    <fieldset><legend><b>Horário</b></legend>
      <table>
        <tr>
          <td nowrap>
            <b>Início:</b>
          </td>
          <td>
            <input type="text" name="sd06_c_horainicio" id="sd06_c_horainicio<?=$iId?>" size="5" 
              value="<?=$sHoraIni?>" style="background-color: #DEB887;" readonly>
          </td>
        </tr>
        <tr>
          <td nowrap>
            <b>Fim:</b>
          </td>
          <td>
            <input type="text" name="sd06_c_horafim" id="sd06_c_horafim<?=$iId?>" size="5"
              value="<?=$sHoraFim?>" style="background-color: #DEB887;" readonly>
          </td>
        </tr>
      </table>
    </fieldset>
  </td>
<?
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/webseller.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<center>
<br>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
      <fieldset style='width: 75%;'> <legend><b>Lançamento de Ausência</b></legend>

        <center>
          <table border="0">
            <tr>
              <td nowrap title="Médico">
                <b>Profissional:</b>
              </td>
              <td nowrap>
                <?
                db_input('sd06_i_medico', 10, '', true, 'text', 3);
                db_input('z01_nome', 50, $Iz01_nome, true, 'text', 3, '');
                ?>
              </td>
            </tr>

            <tr>
              <td nowrap title="Unidade">
                <b>Unidade:</b>
              </td>
              <td nowrap>
                <?
                db_input('sd06_i_unidade', 10, '', true, 'text', 3);
                db_input('descrdepto', 50, $Idescrdepto, true, 'text', 3, '');
                ?>
              </td>
            </tr>

            <tr>
              <td nowrap title="<?=@$Tsd06_i_especmed?>">
                <?=$Lsd06_i_especmed?>
              </td>
              <td nowrap>
                <?
                db_input('sd06_i_especmed', 10, $Isd06_i_especmed, true, 'text', 3);
                db_input('rh70_descr', 50, $Irh70_descr, true, 'text', 3, '');
                ?>
              </td>
            </tr>

            <tr>
              <td nowrap title="<?=$Tsd06_i_tipo?>">
                <?=$Lsd06_i_tipo?>
              </td>
              <td>
                <?
                $sSql = $oDaoSauMotivoAusencia->sql_query_file(null, 's139_i_codigo, s139_c_descr', 's139_i_codigo');
                $rs   = $oDaoSauMotivoAusencia->sql_record($sSql);
                if ($oDaoSauMotivoAusencia->numrows > 0) {
                  db_selectrecord('s139_i_codigo', $rs, true, 1, '', 'sd06_i_tipo', '', '', '', 1);
                } else {
                  echo '<b>Nenhum  motivo de ausência encontrado!</b>';
                }
                ?>                                             
              </td>
            </tr>

            <tr>
              <td colspan="2"> 
                <table>
                  <tr>
                    <td>
                      <fieldset><legend><b>Data</b></legend>
                        <table>
                          <tr>
                            <td nowrap title="<?=$Tsd06_d_inicio?>">
                              <?=$Lsd06_d_inicio?>
                            </td>
                            <td>
                               <?
                               if (isset($sd06_d_inicio)) {

                                 $aHora = explode('-', $sd06_d_inicio);
                                 if (count($aHora) != 3) {
                                   $aHora = explode('/', $sd06_d_inicio);
                                 } else {
                                   $aHora = array_reverse($aHora);
                                 }

                                 $sd06_d_inicio_dia = $aHora[0];
                                 $sd06_d_inicio_mes = $aHora[1];
                                 $sd06_d_inicio_ano = $aHora[2];

                               }

                               db_inputdata('sd06_d_inicio', @$sd06_d_inicio_dia, @$sd06_d_inicio_mes, 
                                            @$sd06_d_inicio_ano, true, 'text', 3
                                           );
                               ?>
                            </td>
                          </tr>
                          <tr>
                            <td nowrap title="<?=$Tsd06_d_fim?>">
                              <?=$Lsd06_d_fim?>
                            </td>
                            <td>
                              <?
                              if (isset($sd06_d_fim)) {

                                 $aHora = explode('-', $sd06_d_fim);
                                 if (count($aHora) != 3) {
                                   $aHora = explode('/', $sd06_d_fim);
                                 } else {
                                   $aHora = array_reverse($aHora);
                                 }

                                 $sd06_d_fim_dia = $aHora[0];
                                 $sd06_d_fim_mes = $aHora[1];
                                 $sd06_d_fim_ano = $aHora[2];

                               }

                              db_inputdata('sd06_d_fim', @$sd06_d_fim_dia, @$sd06_d_fim_mes, 
                                           @$sd06_d_fim_ano,true,'text',3
                                          );
                              ?>
                            </td>
                          </tr>
                        </table>
                      </fieldset>
                    </td>
                    <?
                    $aHoraIni = explode(',', $sd06_c_horainicio);
                    $aHoraFim = explode(',', $sd06_c_horafim);
                    $iTam     = count($aHoraFim);

                    for ($iCont = 0; $iCont < $iTam; $iCont++) {

                      if (($iCont + 1) % 5 == 0) {
                        echo '</tr><tr>';
                      }
                      novoHorario($iCont, $aHoraIni[$iCont], $aHoraFim[$iCont]);

                    }
                    echo '</tr>';
                    ?>
                    
                  </tr>
                </table>
              </td>
            </tr>

          </table>
          <br>
          <input type="button" id="confirmar" value="Confirmar" onclick="js_confirmar();">
          <input type="button" id="fechar" value="Fechar" onclick="js_fechar();">
        </center>

      </fieldset>
    </center>
  </td>
</tr>
</table>
</center>
<?
/*
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"),
        db_getsession("DB_anousu"), db_getsession("DB_instit")
       );
*/
?>

<script>

function js_ajax(oParam, jsRetorno, sUrl) {

  var mRetornoAjax;

  if (sUrl == undefined) {
    sUrl = 'sau4_ambulatorial.RPC.php';
  }
  var objAjax = new Ajax.Request(sUrl, 
                                 {
                                  method: 'post',
                                  asynchronous: false,
                                  parameters: 'json='+Object.toJSON(oParam),
                                  onComplete: function(oAjax) {
                                                var evlJS = jsRetorno+'(oAjax);';
                                                return mRetornoAjax = eval(evlJS);
                                              }
                                 }
                                );

  return mRetornoAjax;

}

function js_confirmar() {

  sHoraIni = '<?=$sd06_c_horainicio?>';
  sHoraFim = '<?=$sd06_c_horafim?>';

  if ($F('sd06_i_especmed') == '') {

    alert('Informe o vínculo.');
    return false;

  }

  if ($('sd06_i_tipo') == undefined) {

    alert('Informe o motivo de ausência do profissional.');
    return false;

  }

  if ($F('sd06_d_inicio') == '') {

    alert('Informe a data de início.');
    return false;

  }

  if ($F('sd06_d_fim') == '') {

    alert('Informe a data de fim.');
    return false;

  }

  if (sHoraIni == '') {

    alert('Informe o horário de início.');
    return false;

  }

  if (sHoraFim == '') {

    alert('Informe o horário de fim.');
    return false;

  }

  var oParam          = new Object();
  oParam.exec         = "lancarAusenciaProfissional";
  oParam.iEspecMed    = $F('sd06_i_especmed');
  oParam.dIni         = $F('sd06_d_inicio');
  oParam.dFim         = $F('sd06_d_fim');
  oParam.sHorariosIni = sHoraIni;
  oParam.sHorariosFim = sHoraFim;
  oParam.sd06_i_tipo  = $F('sd06_i_tipo');
  
  js_ajax(oParam, 'js_retornoConfirmar');

}

function js_retornoConfirmar(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");

  message_ajax(oRetorno.sMessage.urlDecode());
  js_fechar(true);

}

function js_fechar(lAtualizarGrid) {

  if (lAtualizarGrid) { 
    parent.js_agendados();
  }
  parent.db_iframe_ausencia<?=@$iCodFrame?>.hide();

}
</script>

</body>
</html>