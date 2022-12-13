<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

$oDaoLabControleFisicoFinanceiro = db_utils::getdao('lab_controlefisicofinanceiro');
$db_opcao                        = 1;
$db_botao                        = false;
$iTipoControle                   = 0;

/* Verifico se algum tipo de controle físico / financeiro já foi definido. Se houver, pego qualquer um deles
   para verificar qual valor do select com as opções gerais de controle (departamento sol., lab, ...)
   eu tenho que enviar. Pego qualquer um pq se tiver um controle por departamento solicitante, por exemplo,
   os para todos os demais departamentos o controle deve ser por departamento solicitante, 
   ou alguma combinação de departamento solicitante com exame, grupo de exame ou laboratório.
   Pode acontecer de um ser somente por
   departamento sol. e outro ser por exames do departamento solicitante. O que não pode é ter um controle
   por departamento e outro por laboratório, por exemplo.
   Outra coisa que não pode acontecer é ter mais de um tipo de controle para o MESMO departamento.
   Tipos de controle diferentes somente para departamentos diferentes. */
$sSql = $oDaoLabControleFisicoFinanceiro->sql_query_file(null, 'la56_i_tipocontrole');
$rs   = $oDaoLabControleFisicoFinanceiro->sql_record($sSql);
if ($oDaoLabControleFisicoFinanceiro->numrows > 0) {

  $iTipoControle = db_utils::fieldsmemory($rs, 0)->la56_i_tipocontrole;

  // Seto o tipo de controle do select, pois o valor é usado na geração do formulário
  if ($iTipoControle > 0 && $iTipoControle < 4 || $iTipoControle == 9) { // Valores de 1, 2, 3 e 9 (por depto. sol.)
    $iSelectControle = 1;
  } elseif ($iTipoControle > 3 && $iTipoControle < 7) { // Valores de 4, 5 e 6
    $iSelectControle = 2;
  } elseif ($iTipoControle == 7) {
    $iSelectControle = 3;
  } elseif ($iTipoControle == 8) {
    $iSelectControle = 4;
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
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
<br><br>
<table width="900" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
      <center>
        <fieldset style='width: 40%;'> <legend><b>Controle Físico / Financeiro</b></legend>
        <?
          $oDaoLabControleFisicoFinanceiro->rotulo->label();
          $oRotulo = new rotulocampo;
          $oRotulo->label("la02_i_codigo");
          $oRotulo->label("sd62_c_nome");
          $oRotulo->label("descrdepto");
          $oRotulo->label("la08_i_codigo");
          $oRotulo->label("sd60_c_nome");
          $oRotulo->label("sd61_c_nome");
          $oRotulo->label('la08_c_descr');
          $oRotulo->label('sd60_c_grupo');
          $oRotulo->label('sd60_c_nome');
          $oRotulo->label('sd61_c_subgrupo');
          $oRotulo->label('sd61_c_nome');
          $oRotulo->label('sd62_c_formaorganizacao');
          $oRotulo->label('sd62_c_nome');
        ?>
        <form name="form1" method="post" action="">
        <center>
<table border="0" width="70%">
  <tr>
  <?
  // Nenhum tipo de controle foi informado ainda, então, exibo o select com as opções
  if (!isset($iSelectControle) || empty($iSelectControle) || $iSelectControle < 1 || $iSelectControle > 4) { 

    ?>
      <td nowrap title="Selecione um tipo de controle." align="center">
        <center>Selecione um tipo de controle.</center>
      </td>
    </tr>
    </table>
    <? Exit;

  } else {

    ?>
      <tr>
        <td>
          <b>Periodo:</b>
        </td>
        <td nowrap>
          <?
            db_inputdata('dDataIni', @$iDia1, @$iMes1, @$iAno1, true, 'text', 1, "");
          ?>
          A
          <?
            db_inputdata('dDataFim', @$iDia2, @$iMes2, @$iAno2, true, 'text', 1, "");
          ?>
        </td>
      </tr>
      <tr>
    <?
    if ($iSelectControle == 1) { 

      $sQuebraLabel = 'Departamento'; ?>
      <td nowrap colspan="2">
        <b><?=$sQuebraLabel?>:</b>
        <?
        $oDaoDbDepart = db_utils::getdao('db_depart');
        $sSql         = $oDaoDbDepart->sql_query_file(null, 'coddepto, descrdepto', 'coddepto');
        $rs           = $oDaoDbDepart->sql_record($sSql);
        $aX           = array();
        $aX[-1]       = 'TODOS';
        for ($iCont = 0; $iCont < $oDaoDbDepart->numrows; $iCont++) {

          $oDados                = db_utils::fieldsmemory($rs, $iCont);
          $aX[$oDados->coddepto] = $oDados->coddepto.' - '.$oDados->descrdepto;

        }
        db_select('iValor1', $aX, true, 1, 
                  "onchange=\"window.frames['iframeControle'].js_getInfoControleFisicoFinanceiro();\""
                 );
        ?>
        <script>
          $('iValor1').selectedIndex = 0;
        </script>
      </td>
  <?
    } elseif($iSelectControle == 2) {
      
      $sQuebraLabel = "Laboratorio";
  ?>
      <td nowrap align="center" colspan="2">
        <b><?=$sQuebraLabel?>:</b>
        <?
        $oDaoLabLaboratorio = db_utils::getdao('lab_laboratorio');
        $sSql               = $oDaoLabLaboratorio->sql_query_file(null, 'la02_i_codigo, la02_c_descr');
        $rs                 = $oDaoLabLaboratorio->sql_record($sSql);
        $aX                 = array();
        for ($iCont = 0; $iCont < $oDaoLabLaboratorio->numrows; $iCont++) {

          $oDados                     = db_utils::fieldsmemory($rs, $iCont);
          $aX[$oDados->la02_i_codigo] = $oDados->la02_c_descr;

        }
        $aX[-1]       = 'TODOS';
        db_select('iValor1', $aX, true, 1,
                  "onchange=\"window.frames['iframeControle'].js_getInfoControleFisicoFinanceiro();\""
                 );
        ?>
        <script>
          $('iValor1').selectedIndex = 0;
        </script>
      </td>
    </tr>
  <?
    }
    // GRUPO DE EXAMES
    if ($iSelectControle == 3) {

      $sQuebraLabel = "Grupo de Exame"
      ?>
      <tr>
        <td nowrap title="<?=@$Tla56_i_grupo?>">
          <?
          db_ancora(@$Lla56_i_grupo, "js_pesquisala56_i_grupo(true);", $db_opcao);
          ?>
        </td>
        <td nowrap> 
          <?
          db_input('la56_i_grupo', 10, $Ila56_i_grupo, true, 'hidden', 3, '');
          db_input('sd60_c_grupo', 2, $Isd60_c_grupo, true, 'text', $db_opcao, 
                   " onchange='js_pesquisala56_i_grupo(false);'"
                  );
          db_input('sd60_c_nome', 50, $Isd60_c_nome, true, 'text', 3, '');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tla56_i_subgrupo?>">
          <?
          db_ancora(@$Lla56_i_subgrupo, "js_pesquisala56_i_subgrupo(true);", $db_opcao);
          ?>
        </td>
        <td nowrap> 
          <?
          db_input('la56_i_subgrupo', 10, $Ila56_i_subgrupo, true, 'hidden', 3, '');
          db_input('sd61_c_subgrupo', 2, $Isd61_c_subgrupo, true, 'text', $db_opcao, 
                   " onchange='js_pesquisala56_i_subgrupo(false);'"
                  );
          db_input('sd61_c_nome', 50, $Isd61_c_nome, true, 'text', 3, '');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tla56_i_formaorganizacao?>">
          <?
          db_ancora(@$Lla56_i_formaorganizacao, "js_pesquisala56_i_formaorganizacao(true);", $db_opcao);
          ?>
        </td>
        <td nowrap> 
          <?
          db_input('la56_i_formaorganizacao', 10, $Ila56_i_formaorganizacao, true, 'hidden', 3, '');
          db_input('sd62_c_formaorganizacao', 2, $Isd62_c_formaorganizacao, true, 'text', $db_opcao, 
                   " onchange='js_pesquisala56_i_formaorganizacao(false);'"
                  );
          db_input('sd62_c_nome', 50, $Isd62_c_nome, true, 'text', 3, '');
          ?>
        </td>
      </tr>
      <?

    } elseif($iSelectControle == 4) {

      $sQuebraLabel = "Exame";
      ?>
      <tr>
        <td nowrap title="<?=@$Tla56_i_exame?>">
          <?
          db_ancora(@$Lla56_i_exame, "js_pesquisala56_i_exame(true);", $db_opcao);
          ?>
        </td>
        <td nowrap> 
          <?
          db_input('iValor1', 10, $Ila56_i_exame, true, 'text', $db_opcao, 
                   " onchange='js_pesquisala56_i_exame(false);'"
                  );
          db_input('la08_c_descr', 50, $Ila08_c_descr, true, 'text', 3, '');
          ?>
        </td>
      </tr>
      <?

    }
  }
  ?>
  <tr>
    <td colspan="2" align="center">
      <input type="button" value="Gerar" onclick="js_gerar()">
    <td>
  </tr>
</table>
</center>
</form>
        </fieldset>
      </center>
    </td>
  </tr>
</table>
</center>
<?
db_menu(db_getsession('DB_id_usuario'), db_getsession('DB_modulo'), 
        db_getsession('DB_anousu'), db_getsession('DB_instit')
       );
?>
</body>
</html>
<script>
js_tabulacaoforms("form1","la56_i_laboratorio",true,1,"la56_i_laboratorio",true);
function js_gerar() {

  if (js_validaDados() == false) {
    return false;
  }
  sQuery       = 'dDataIni='+$F('dDataIni');
  sQuery      += '&dDataFim='+$F('dDataFim');
  sQuery      += '&sQuebraLabel=<?=$sQuebraLabel?>';
  sQuery      += '&iTpcontrole=<?=$iSelectControle?>';
  if (<?=$iSelectControle?> != 3) {
    if ($F('iValor1') == '') {
      sQuery += '&iValor1=-1';
    } else {
    	sQuery += '&iValor1='+$F('iValor1');
    }
  } else {

    if ($F('la56_i_grupo') == '') {
      sQuery += '&iValor1=-1';
    } else {

      sQuery += '&iValor1='+$F('sd60_c_grupo');
      sQuery += '&iValor2='+$F('sd61_c_subgrupo');
      sQuery += '&iValor3='+$F('sd62_c_formaorganizacao');

    }

  }
  jan = window.open('lab2_controlefisfin002.php?'+sQuery,
                    '',
                    'width='+(screen.availWidth-5) +',height='+(screen.availHeight-40)+',scrollbars=1,location=0 '
                   );
  jan.moveTo(0, 0);

}
function js_validaDados(){

  if ($F('dDataFim') == '' || $F('dDataFim') == '') {

    alert ('Entre com o periodo!');
    return false;

  }
  sData = $F('dDataIni');
  aData = sData.split('/');
  sDataini = aData.reverse().join('');
  sData = $F('dDataFim');
  aData = sData.split('/');
  sDatafim = aData.reverse().join('');
  if (parseInt(sDatafim,10) <
      parseInt(sDataini,10)) {

    alert('Final não pode ser menos que a inicial!');
    return false;

  }
  return true;

}
function js_pesquisala56_i_grupo(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('top.corpo', 'db_iframe_sau_grupo', 'func_sau_grupo.php?funcao_js='+
                        'parent.js_mostrasau_grupo|sd60_i_codigo|sd60_c_nome|sd60_c_grupo', 
                        'Pesquisa', true
                       );

  } else {

    if (document.form1.sd60_c_grupo.value != '') {

       js_OpenJanelaIframe('top.corpo', 'db_iframe_sau_grupo', 'func_sau_grupo.php?chave_sd60_c_grupo='+
                           document.form1.sd60_c_grupo.value+'&funcao_js=parent.js_mostrasau_grupo|'+
                           'sd60_i_codigo|sd60_c_nome|sd60_c_grupo&nao_mostra=true', 
                           'Pesquisa', false
                          );

    } else {

      js_limpaGrupo();
      js_limpaSubGrupo();
      js_limpaFormaOrg();

    }

  }

}
function js_mostrasau_grupo(chave1, chave2, chave3) {

  js_limpaSubGrupo();
  js_limpaFormaOrg();

  if (chave1 == '') {
    chave3 = '';
  }
  document.form1.la56_i_grupo.value = chave1;
  document.form1.sd60_c_nome.value  = chave2;
  document.form1.sd60_c_grupo.value = chave3;
  db_iframe_sau_grupo.hide();

}
function js_pesquisala56_i_subgrupo(mostra) {

  if ($F('sd60_c_grupo') == '' || $F('la56_i_grupo') == '') {

    alert('Selecione um grupo primeiro.');
    $('sd61_c_subgrupo').value = '';
    return false;

  }

  var sGet = '&chave_grupo='+$F('sd60_c_grupo');

  if (mostra == true) {

    js_OpenJanelaIframe('top.corpo', 'db_iframe_sau_subgrupo', 'func_sau_subgrupo.php?'+
                        'funcao_js=parent.js_mostrasau_subgrupo|sd61_i_codigo|sd61_c_nome|sd61_c_subgrupo'+
                        sGet, 'Pesquisa', true
                       );

  } else {

    if (document.form1.sd61_c_subgrupo.value != '') {

      js_OpenJanelaIframe('top.corpo', 'db_iframe_sau_subgrupo', 'func_sau_subgrupo.php?chave_sd61_c_subgrupo='+
                          document.form1.sd61_c_subgrupo.value+'&funcao_js=parent.js_mostrasau_subgrupo|'+
                          'sd61_i_codigo|sd61_c_nome|sd61_c_subgrupo&nao_mostra=true'+sGet, 
                          'Pesquisa', false
                         );

    } else {

      js_limpaSubGrupo();
      js_limpaFormaOrg();

    }

  }

}
function js_mostrasau_subgrupo(chave1, chave2, chave3) {

  js_limpaFormaOrg();

  if (chave1 == '') {
    chave3 = '';
  }

  document.form1.la56_i_subgrupo.value = chave1;
  document.form1.sd61_c_nome.value     = chave2;
  document.form1.sd61_c_subgrupo.value = chave3;
  db_iframe_sau_subgrupo.hide();

}

function js_pesquisala56_i_formaorganizacao(mostra) {

  if ($F('sd60_c_grupo') == '' || $F('la56_i_grupo') == '') {

    alert('Selecione um grupo primeiro.');
    $('sd62_c_formaorganizacao').value = '';
    return false;

  }

  if ($F('sd61_c_subgrupo') == '' || $F('la56_i_subgrupo') == '') {

    alert('Selecione um subgrupo primeiro.');
    $('sd62_c_formaorganizacao').value = '';
    return false;

  }

  var sGet = '&chave_grupo='+$F('sd60_c_grupo')+'&chave_subgrupo='+$F('sd61_c_subgrupo');

  if (mostra == true) {

    js_OpenJanelaIframe('top.corpo', 'db_iframe_sau_formaorganizacao', 'func_sau_formaorganizacao.php?'+
                        'funcao_js=parent.js_mostrasau_formaorganizacao|sd62_i_codigo|sd62_c_nome|'+
                        'sd62_c_formaorganizacao'+sGet, 'Pesquisa', true
                       );

  } else {

    if (document.form1.sd62_c_formaorganizacao.value != '') {

       js_OpenJanelaIframe('top.corpo', 'db_iframe_sau_formaorganizacao', 'func_sau_formaorganizacao.php?'+
                           'chave_sd62_c_formaorganizacao='+document.form1.sd62_c_formaorganizacao.value+
                           '&funcao_js=parent.js_mostrasau_formaorganizacao|sd62_i_codigo|sd62_c_nome|'+
                           'sd62_c_formaorganizacao&nao_mostra=true'+sGet, 'Pesquisa', false
                          );

    } else {
      js_limpaFormaOrg();
    }

  }

}
function js_mostrasau_formaorganizacao(chave1, chave2, chave3) {

  if (chave1 == '') {
    chave3 = '';
  }

  document.form1.la56_i_formaorganizacao.value = chave1;
  document.form1.sd62_c_nome.value             = chave2;
  document.form1.sd62_c_formaorganizacao.value = chave3;
  db_iframe_sau_formaorganizacao.hide();

}

function js_limpaGrupo() {

  $('la56_i_grupo').value = '';
  $('sd60_c_grupo').value = '';
  $('sd60_c_nome').value  = '';

}

function js_limpaSubGrupo() {

  $('la56_i_subgrupo').value         = '';
  $('sd61_c_subgrupo').value         = '';
  $('sd61_c_nome').value             = '';

}

function js_limpaFormaOrg() {

  $('la56_i_formaorganizacao').value = '';
  $('sd62_c_formaorganizacao').value = '';
  $('sd62_c_nome').value             = '';

}

function js_pesquisala56_i_exame(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('top.corpo', 'db_iframe_lab_exame', 'func_lab_exame.php?funcao_js='+
                        'parent.js_mostralab_exame1|la08_i_codigo|la08_c_descr', 'Pesquisa', true
                       );

  } else {

    if (document.form1.iValor1.value != '') { 
       js_OpenJanelaIframe('top.corpo', 'db_iframe_lab_exame', 'func_lab_exame.php?pesquisa_chave='+
                           document.form1.iValor1.value+'&funcao_js=parent.js_mostralab_exame', 
                           'Pesquisa', false
                          );

    } else {
      document.form1.la08_c_descr.value = ''; 
    }

  }

}
function js_mostralab_exame(chave, erro) {

  document.form1.la08_c_descr.value = chave; 
  if (erro == true) {

    document.form1.iValor1.focus(); 
    document.form1.iValor1.value = '';

  }

}
function js_mostralab_exame1(chave1, chave2) {

  document.form1.iValor1.value = chave1;
  document.form1.la08_c_descr.value = chave2;
  db_iframe_lab_exame.hide();

}
</script>