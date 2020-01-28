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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_cgm_classe.php");
require_once("classes/db_inicialnomes_classe.php");
require_once("classes/db_processoforo_classe.php");
require_once("classes/db_processoforoinicial_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$oPost  = db_utils::postMemory($_POST);
$oGet   = db_utils::postMemory($_GET);

$clcgm                 = new cl_cgm;
$clinicialnomes        = new cl_inicialnomes;
$clprocessoforo        = new cl_processoforo;
$clprocessoforoinicial = new cl_processoforoinicial;

$clcgm->rotulo->label();
$clinicialnomes->rotulo->label();
$clprocessoforo->rotulo->label();
$clprocessoforoinicial->rotulo->label();

$sWhere = "";
$sAnd   = "";
$lCgf   = false;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<script>
function js_pesquisacgm(mostra) {

  var cgm = document.form1.chave_v58_numcgm.value;
  if (mostra == true) {
  
    var sUrl = 'func_nome.php?funcao_js=parent.js_mostracgm|0|1';
    js_OpenJanelaIframe('', 'db_iframe_numcgm', sUrl, 'Pesquisa', true);
  } else {
  
    if (cgm != "") {
    
      var sUrl = 'func_nome.php?pesquisa_chave='+cgm+'&funcao_js=parent.js_mostracgm1';
      js_OpenJanelaIframe('', 'db_iframe_numcgm', sUrl, 'Pesquisa', false);
    } else {
    
      document.form1.chave_v58_numcgm.value = '';
      document.form1.z01_nome.value         = '';
    }  
  }
}

function js_mostracgm(chave1, chave2) {

  document.form1.chave_v58_numcgm.value  = chave1;
  document.form1.z01_nome.value          = chave2;
  db_iframe_numcgm.hide();
}

function js_mostracgm1(erro,chave) {

  document.form1.z01_nome.value = chave; 
  if (erro == true) { 
  
    document.form1.chave_v58_numcgm.focus(); 
    document.form1.chave_v58_numcgm.value = '';
  }
}

function js_pesquisaInicial(lMostra) {

	if (lMostra) {

		js_OpenJanelaIframe('', 'db_iframe_inicial', 'func_inicial.php?funcao_js=parent.js_retornaInicial|v50_inicial', 'Pesquisa Inicial', true, 0, 0, document.body.clientWidth, document.body.clientHeight);
		
	} 
	
}

function js_retornaInicial(iCodigoInicial) {

	document.form1.chave_v71_inicial.value = iCodigoInicial;

	db_iframe_inicial.hide();
	
}
</script>


<table align="center" >
	<tr>
		<td>
      <form name="form1" method="post" action="">
      <fieldset>
      	<legend><strong>Pesquisa Processo do Foro</strong></legend>
      <table border="0" align="center" >
        <tr> 
          <td nowrap title="<?=$Tv70_sequencial?>">
            <?=$Lv70_sequencial?>
          </td>
          <td nowrap> 
            <?
              db_input("v70_sequencial",10,$Iv70_sequencial,true,"text",4,"","chave_v70_sequencial");
            ?>
          </td>
        </tr>
        <tr> 
          <td nowrap title="<?=$Tv70_codforo?>">
            <?=$Lv70_codforo?>
          </td>
          <td nowrap> 
            <?
              db_input("v70_codforo",30,$Iv70_codforo,true,"text",4,"","chave_v70_codforo");
            ?>
          </td>
        </tr>
        <tr> 
          <td nowrap title="<?=$Tv71_inicial?>">
            <?
              db_ancora($Lv71_inicial, "js_pesquisaInicial(true)", 1)?>
          </td>
          <td nowrap> 
            <?
              db_input("v71_inicial",10,$Iv71_inicial,true,"text",4,"onchange='js_pesquisaInicial(false)'","chave_v71_inicial");
            ?>
          </td>
        </tr>          
        <tr>
          <td nowrap title="<?=@$Tv58_numcgm?>">
             <?
              db_ancora(@$Lv58_numcgm," js_pesquisacgm(true); ",1);
             ?>
          </td>
          <td> 
             <?
              db_input('v58_numcgm',10,$Iv58_numcgm,true,'text',4," onchange='js_pesquisacgm(false);'","chave_v58_numcgm");
              db_input('z01_nome',40,$Iz01_nome,true,'text',3);
             ?>
          </td>
        </tr>
        <?
          if (isset($oGet->lSituacao) && $oGet->lSituacao == 'true') {
        ?>
        <tr>
          <td nowrap title="<?=@$Tv58_numcgm?>">
             <b>Situação:</b>
          </td>
          <td> 
             <?
               $aSituacao = array('T'  => 'Todos',
                                  'AT' => 'Ativo',
                                  'AN' => 'Anulado');
               db_select('v70_anulado', $aSituacao, true, 1);
             ?>
          </td>
        </tr>
        <?
          }
        ?>
        
        <tr>
        	<td title="<?=$Tv70_data?>">
        		<strong>Período:</strong>
        	</td>
        	<td>
        		<?php 
        		  db_inputdata('v70_datainicial', @$v70_datainicial_dia, @$v70_datainicial_mes, @$v70_datainicial_ano, true, 'text', 1);
        		?>
        		a
        		<?php 
        		  db_inputdata('v70_datafinal', @$v70_datafinal_dia, @$v70_datafinal_mes, @$v70_datafinal_ano, true, 'text', 1);
        		?>
        	</td>
        </tr>
        
        <tr> 
          <td colspan="2" align="center"> 
            <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
            <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_processoforo.hide();">
           </td>
        </tr>
      </table>
      </fieldset>
      </form>
		</td>
	</tr>
	<tr>
		<td>
      <fieldset>
      <table align="center">
        <tr> 
          <td align="center" valign="top"> 
            <?
            
            $sWhere = " processoforo.v70_instit = ".db_getsession('DB_instit');
            
            if (isset($oGet->lAnuladas) && ($oGet->lAnuladas == 'false')) {
              $sWhere .= " and processoforo.v70_anulado is false";
            }
            
            if (isset($oPost->v70_anulado)) {
                    
            	if ($oPost->v70_anulado == 'AT') {
                $sWhere .= " and processoforo.v70_anulado is false";
            	} else if ($oPost->v70_anulado == 'AN') {
                $sWhere .= " and processoforo.v70_anulado is true";
            	}
            }
            
            if (!isset($pesquisa_chave)) {
            	
              if (isset($campos) == false) {
                  
                if(file_exists("funcoes/db_func_processoforo.php")==true){
                  include("funcoes/db_func_processoforo.php");
                } else {
                  $campos  = "processoforo.v70_sequencial,processoforo.v70_codforo, processoforo.v70_vara,processoforo.v70_data,processoforo.v70_anulado, v71_inicial ";
                  
                }
              }
      
              /*
               * Caso as variáveis $matric, $inscr, $numcgm e $numpre existam, significa que a requisição ao fonte tem origem da CGF.  
               */
              if ( isset($matric) ) {
                $lCgf = true;
                if (!empty($sWhere)) {
                  $sAnd = " and ";  
                } 
                $sWhere .= $sAnd."arrematric.k00_matric = {$matric}";
                
              }
              if ( isset($inscr)  ) {
                $lCgf = true;
                if (!empty($sWhere)) {
                  $sAnd = " and ";  
                }
                $sWhere .= $sAnd."arreinscr.k00_inscr = {$inscr}";
              } 
              if ( isset($numcgm) ) {
                $lCgf = true;
                if (!empty($sWhere)) {
                  $sAnd = " and ";  
                }          
                $sWhere .= $sAnd."arrenumcgm.k00_numcgm = {$numcgm}";
              }
              if ( isset($numpre) ) {
                $lCgf = true;
                if (!empty($sWhere)) {
                  $sAnd = " and ";  
                }          
                $sWhere .= $sAnd."inicialnumpre.v59_numpre = {$numpre}";
              }
              
              if((isset($v70_datainicial) and $v70_datainicial != '') and (isset($v70_datafinal) and $v70_datafinal != '')) {
                $sWhere .= " and processoforo.v70_data between '{$v70_datainicial}' and '{$v70_datafinal}' ";
              } else if (isset($v70_datainicial) and $v70_datainicial != '') {
                $sWhere .= " and processoforo.v70_data >= '{$v70_datainicial}' ";
              } else if (isset($v70_datafinal) and $v70_datafinal != '') {
                $sWhere .= " and processoforo.v70_data <= '{$v70_datafinal}' ";
              }
                
              if (isset($chave_v70_sequencial) && (trim($chave_v70_sequencial) != "")) {
                $sWhere .= " and processoforo.v70_sequencial = {$chave_v70_sequencial} ";
              } else if (isset($chave_v70_codforo) && (trim($chave_v70_codforo) != "")) {
                $sWhere .= " and processoforo.v70_codforo like '{$chave_v70_codforo}%' "; 
              } else if (isset($chave_v71_inicial) && (trim($chave_v71_inicial) != "")) {
                $sWhere .= " and processoforoinicial.v71_inicial  = {$chave_v71_inicial} "; 
              } else if (isset($chave_v58_numcgm) && (trim($chave_v58_numcgm) != "")) {
                $sWhere .= " and inicialnomes.v58_numcgm  = {$chave_v58_numcgm} or cgmr.z01_numcgm  = {$chave_v58_numcgm} "; 
              }
              
              if (isset($lPossuiIniciais) and $lPossuiIniciais) {
                $sWhere .= " and processoforoinicial.v71_inicial is not null ";
              }
              
              if ($lCgf) {
                $sql    = $clprocessoforo->sql_query_envolvidos(null, " distinct {$campos}", "processoforo.v70_sequencial", $sWhere,true);
              } else {
                $sql    = $clprocessoforo->sql_query_cgm_nome(null, " distinct $campos", "processoforo.v70_sequencial", $sWhere,true);
              }
      
              $repassa = array();
              if (isset($chave_v70_sequencial)) {
                $repassa = array("chave_v70_sequencial" => $chave_v70_sequencial);
              }
              db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
            } else {
            	
              if ($pesquisa_chave != null && $pesquisa_chave != "") {
              	
              	$sWhere .= " and processoforo.v70_sequencial = {$pesquisa_chave}";
              	$sSqlProcessoForo = $clprocessoforo->sql_query_cgm_inicial(null, "processoforo.*", "processoforo.v70_sequencial, processoforo.v70_codforo", $sWhere);
                
              	$result           = $clprocessoforo->sql_record($sSqlProcessoForo);
                if ($clprocessoforo->numrows != 0) {
                	
                  db_fieldsmemory($result,0);
                  echo "<script>".$funcao_js."('$v70_sequencial',false, '$v70_codforo');</script>";
                } else {
      	          echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
                }
              } else {
      	        echo "<script>".$funcao_js."('',false);</script>";
              }
            }
            ?>
           </td>
         </tr>
			</table>
		</fieldset>	
	</td>
</tr>
</table>
</body>
</html>
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
  </script>
  <?
}
?>
<script>
js_tabulacaoforms("form1","chave_v70_sequencial",true,1,"chave_v70_sequencial",true);

if (document.form1.v70_anulado) {
  document.form1.v70_anulado.style.width = '92px';
}
</script>