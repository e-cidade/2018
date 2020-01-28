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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");

db_postmemory($HTTP_POST_VARS);

$oDaoFarControlemed      = db_utils::getdao('far_controlemed');
$oDaoFarControle         = db_utils::getdao('far_controle');
$oDaoTmpFarRetiradaitens = db_utils::getdao('tmp_far_retiradaitens');
$oDaoFarPrograma         = db_utils::getdao('far_programa');
$oDaoFarParametros       = db_utils::getdao('far_parametros');
$oIframeAlterarExcluir   = new cl_iframe_alterar_excluir;

$db_opcao                = 1;
$db_botao                = true;
$db_opcao1               = 1; //campo cgsund
$db_opcao2               = 1; // campo obs

if (isset($fa11_i_cgsund) && $fa11_i_cgsund != '') {

  $db_opcao1 = 3;
  $sSql      = $oDaoFarControlemed->sql_query(null, 'fa11_t_obs', '', "fa11_i_cgsund = $fa11_i_cgsund");
  $result12  = $oDaoFarControlemed->sql_record($sSql);
  if ($oDaoFarControlemed->numrows > 0) {

    db_fieldsmemory($result12,0);
    $db_opcao2 = 3;

  } else {
    $db_opcao2 = 1;
  }

}

if (isset($opcao)) {

  $db_botao1 = true;
  if ( $opcao == "alterar") {

    $db_opcao  = 2;
    $db_opcao1 = 3;
    $db_opcao2 = 1;
    $sSql      = $oDaoFarControlemed->sql_query($fa10_i_codigo);
    $result1   = $oDaoFarControlemed->sql_record($sSql);
    db_fieldsmemory($result1, 0);

    if ($oDaoFarControlemed->numrows > 0) {
      db_fieldsmemory($result1, 0);
    }  
  } elseif ( $opcao=="excluir" || isset($db_opcao) && $db_opcao==3) {

    $db_opcao  = 3; 
    $db_opcao1 = 3; 
    $db_opcao2 = 3;
    $sSql      = $oDaoFarControle->sql_query($fa11_i_codigo);
    $result2   = $oDaoFarControle->sql_record($sSql);
    if ($oDaoFarControle->numrows > 0) {
      db_fieldsmemory($result2, 0);
    }
    
    $sSql    = $oDaoFarControlemed->sql_query($fa10_i_codigo);
    $result3 = $oDaoFarControlemed->sql_record($sSql);
    if ($oDaoFarControlemed->numrows > 0) {
      db_fieldsmemory($result3, 0);
    }

  }

}

if (isset($fa11_i_cgsund)) {
  
  $sSql    = $oDaoFarControle->sql_query('', 'fa11_t_obs,fa11_i_codigo', '', "fa11_i_cgsund = $fa11_i_cgsund");
  $result7 = $oDaoFarControle->sql_record($sSql);
  if ($oDaoFarControle->numrows>0) {
    db_fieldsmemory($result7,0);   
  }

}

$sqlerror = false;
if (isset($incluir)) {

  db_inicio_transacao();
  $sSql       = $oDaoFarControlemed->sql_query('', 'fa10_i_medicamento', '', "fa10_i_medicamento = $fa10_i_medicamento".
                                               " and fa11_i_cgsund = $fa11_i_cgsund"
                                              );
  $result_cont = $oDaoFarControlemed->sql_record($sSql);
  if ($oDaoFarControlemed->numrows > 0) {
?>
    <script> alert('Medicamento já incluido!!')</script>;
<?
    $sqlerror= true;       
  }

  $sSql     = $oDaoFarControle->sql_query('' ,'fa11_t_obs,fa11_i_codigo', '', "fa11_i_cgsund = $fa11_i_cgsund");
  $result10 = $oDaoFarControle->sql_record($sSql);
  
  if ($oDaoFarControle->numrows > 0) {

    db_fieldsmemory($result10, 0);
    $oDaoFarControle->fa11_t_obs    = str_replace("'", ".", $fa11_t_obs);
    $oDaoFarControle->fa11_i_codigo = $fa11_i_codigo;
    $oDaoFarControle->alterar($fa11_i_codigo);

  } else {

    $oDaoFarControle->fa11_t_obs= str_replace("'", ".", $fa11_t_obs);
    $oDaoFarControle->incluir(null);
  } 
    
  $oDaoFarControlemed->fa10_i_controle= $oDaoFarControle->fa11_i_codigo;          
  $oDaoFarControlemed->incluir(null);
  db_fim_transacao($oDaoFarControlemed->erro_status == '0' ? true : false);

}

if (isset($alterar)) {

  db_inicio_transacao();
  $oDaoFarControle->alterar($fa11_i_codigo);    
  $oDaoFarControlemed->alterar($fa10_i_codigo);
  db_fim_transacao($oDaoFarControlemed->erro_status == '0' ? true : false);

}

if (isset($excluir)) {

  $lErro = false;
  db_inicio_transacao();
  $oDaoFarControlemed->excluir($fa10_i_codigo);
  if ($oDaoFarControlemed->erro_status == '0') {
    $lErro = true;
  }
  $sSql    = $oDaoFarControlemed->sql_query('', '*', '', "fa10_i_controle = $fa11_i_codigo");
  $results = $oDaoFarControlemed->sql_record($sSql);
  if ($oDaoFarControlemed->numrows == 0) {

    $oDaoFarControle->excluir($fa11_i_codigo);

    if ($oDaoFarControle->erro_status == '0') {
      $lErro = true;
    }

  }    
  db_fim_transacao($lErro);

}

if (!isset($incluir) && !isset($alterar) && !isset($excluir) && !isset($fa10_i_programa)) {

  $sSql = $oDaoFarParametros->sql_query2(null, 'fa02_i_acaoprog as fa10_i_programa, fa12_c_descricao');
  $rs   = $oDaoFarParametros->sql_record($sSql);
  if ($oDaoFarParametros->numrows > 0) {
    db_fieldsmemory($rs, 0);
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
<script language="JavaScript" type="text/javascript" src="scripts/widgets/dbautocomplete.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="scripts/prototype.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<center>
<br><br><br>
<table border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td align="left" valign="top" bgcolor="#CCCCCC"> 
      <fieldset style="width: auto;"><legend><b>Medicamentos Continuados</b></legend>
        <?
        require_once("forms/db_frmfar_controlemed.php");
        ?>
      </fieldset>
    </td>
  </tr>
</table>
</center>
<?
$sVarsGet1 = '';
$sVarsGet2 = '';
if (!isset($lBotao)) {

  db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), 
          db_getsession("DB_anousu"),
          db_getsession("DB_instit")
         );

} else {

  $sVarsGet1 = "?fa11_i_cgsund=$fa11_i_cgsund&lBotao=true";
  $sVarsGet2 = "&lBotao=true";

}
?>
</body>
</html>
<script>

<? 
if ( isset($fa11_i_cgsund) && (int)$fa11_i_cgsund > 0 ) {
  echo "js_tabulacaoforms('form1','fa10_i_medicamento',true,1,'fa10_i_medicamento',true);";
} else {
  echo "js_tabulacaoforms('form1','fa11_i_cgsund',true,1,'fa11_i_cgsund',true);";
}
?>

$("fa10_d_dataini").tabIndex = 0; //$("fa10_d_dataini").tabIndex+2;
$("fa10_d_datafim").tabIndex = 0; //$("fa10_d_datafim").tabIndex+2;

function js_controla_tecla_enter(obj,evt) {

  var evt = (evt) ? evt : (window.event) ? window.event : "";
  
  //13=enter, 40=seta cima, 38=seta baixo, 37=set esquerda, 39=seta direita
  if (evt.keyCode == 13) {
    if ( evt.keyCode==13 || evt.keyCode==40 || evt.keyCode==39 ) {
      var iTabindex = obj.tabIndex + 1;
    }else {
      var iTabindex = obj.tabIndex - 1;      
    }
      
    //Varre todos os campos que foram setados com tabindex
    var aTabindex = new Array();
    for(var iCount = 0; iCount <= document.form1.elements.length; iCount++) {
      //verifica se tem tabindex
      if ( document.form1.elements[iCount] != undefined && document.form1.elements[iCount].tabIndex > 0 ) {
        aTabindex[ document.form1.elements[iCount].tabIndex ] = iCount;
      }

    }
    //varre todos os tabindex setado
    for(var iCount = 0; iCount <= aTabindex.length; iCount++) {
      //verificar se o próximo tabindex é valido
      if ( aTabindex[ iTabindex ] != undefined) {

        document.form1.elements[ aTabindex[ iTabindex ] ].focus();
        break;

      } else {
        //se não for valido incrementa para o próximo tabindexs
        if ( evt.keyCode==13 || evt.keyCode==40 || evt.keyCode==39 ) {
          iTabindex++;
        } else {
          iTabindex--;
        }

      }

    }
    
    return false;
  }

}

$('m60_descr').onkeydown = '';
</script>
<?
if (isset($incluir) || isset($alterar) || isset($excluir)) {

  $sSql           = $oDaoFarControlemed->sql_query('', '*', '', "fa11_i_cgsund = $fa11_i_cgsund");
  $result_excluir = $oDaoFarControlemed->sql_record($sSql);
   if ($oDaoFarControlemed->numrows == 0) {?>
     <script>          
       location.href='far1_far_controlemed001.php<?=@$sVarsGet1?>';
     </script>
     <?
   }
   ?>
   <script>          
     location.href='far1_far_controlemed001.php?fa11_i_cgsund=<?=$fa11_i_cgsund.$sVarsGet2?>'
   </script>
   <?
}
?>