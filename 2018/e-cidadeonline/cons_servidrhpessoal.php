<?
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

session_start();

include("libs/db_conecta.php");
include("libs/db_stdlib.php");
include("libs/db_sql.php");
include("libs/db_utils.php");
include("dbforms/db_funcoes.php");
include("classes/db_cfpess_classe.php");

validaUsuarioLogado();

$aRetorno = array();
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]),$aRetorno);

$id_usuario  = $aRetorno['id_usuario'];
$matricula   = $aRetorno['matricula'];
$instituicao = $aRetorno['instituicao'];
$numcgm      = db_getsession("DB_login");
$anoFolha    = db_anofolha($instituicao);
$mesFolha    = db_mesfolha($instituicao);

db_logs("","",0,"Consulta Funcional.");

$sUrl       = base64_encode("iMatric=".$matricula."&iInstit=".$instituicao);
$sUrlAverba = base64_encode("&averba");

/**
 * Caso o cliente seja Bage (codcli = 15) 
 * Então a variável lBloqueio passa a ser true e não mostrará os menus:
 * - Assentamento 
 * - Averbação do tempo de serviço 
 * - Férias
 * 
 * Do contrário todos os menus são mostrados normalmente.
 */
$lBloqueio = false;
$rsCodCli  = db_query("select db21_codcli from db_config where prefeitura is true limit 1");
$iCodCli   = db_utils::fieldsmemory($rsCodCli)->db21_codcli;

if ($iCodCli == 15 ) {
  $lBloqueio = true; 
}

/**
 * Declara a estrutura da folha de pagamento conforme a instituição do servidor informado.
 */
try{
  cl_cfpess::declararEstruturaFolhaPagamento($instituicao);
} catch (Exception $ex) {
  db_msgbox($ex->getMessage());
}


/**
 * Verifica para quais matriculas não deve ser exibido o menu de Comprovante 
 * de rendimentos atraves da seleção "MAT COMPR REND ECIDADEONLINE".
 */
$lComprovanteRendimentos = true;
$rsSelecaoMatriculas = db_query("select r44_where from selecao where r44_descr = 'MAT COMPR REND ECIDADEONLINE'");

if (pg_numrows($rsSelecaoMatriculas) > 0) {

  $sMatriculasExcecao = db_utils::fieldsMemory($rsSelecaoMatriculas, 0)->r44_where;

  $rsMatriculaExcecaoRendimentos = db_query("select rh01_regist from rhpessoal where {$matricula} in ($sMatriculasExcecao)");

  if (pg_numrows($rsMatriculaExcecaoRendimentos) > 0) {
    $lComprovanteRendimentos = false;
  }
  
}

?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="config/portalservidor.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="scripts/db_script.js"></script>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>">
<table width="100%" border="0" bordercolor="#cccccc" cellpadding="2" cellspacing="0" class="texto">
 <tr>
  <td><br></td>
 </tr>
</table>
<?
  if ($id_usuario != "") { 
?>
<table width="100%" border="0" cellpadding="2" cellspacing="0" class="texto">
  <tr>
    <td valign="top" width="10%">
     <table width="200" height="10%" id="navigation" border="0">
        <tr style="cursor: pointer;" onClick="js_atualizaFrame('dadosCadastrais');">
           <td nowrap="nowrap" width="100%">
             <span class="navText" >Consulta Dados Cadastrais</span>
           </td>
        </tr>
        
        <? if ($lBloqueio == false ) { ?>
        <tr style="cursor: pointer;" onClick="js_atualizaFrame('assentamentos');">
           <td nowrap="nowrap" width="100%">
             <span class="navText" >Assentamentos</span>
           </td>
        </tr>     
        <tr style="cursor: pointer;" onClick="js_atualizaFrame('averbacao');">
           <td nowrap="nowrap" width="100%">
             <span class="navText" >Averbação de Tempo de Serviço</span>
           </td>
        </tr>
        <? } ?>
        
        <tr style="cursor: pointer;" onClick="js_atualizaFrame('dependentes');">
            <td nowrap="nowrap" width="100%">
              <span class="navText" >Dependentes</span>
            </td>
         </tr>
         
        <? if ($lBloqueio == false ) { ?>
         <tr style="cursor: pointer;" onClick="js_atualizaFrame('ferias');">
            <td nowrap="nowrap" width="100%">
              <span class="navText" >Férias</span>
            </td>
         </tr>
        <? } ?>
                    
        <?php
        /**
         * Verifica se a matricula atual do servidor deve ser exibido o 
         * menu de comprovante de rendimentos.
         */
        if ($lComprovanteRendimentos) {

        ?>
         <tr style="cursor: pointer;" onClick="js_atualizaFrame('comprovanteRendimentos');">
             <td nowrap="nowrap" width="100%">
               <span class="navText" >Comprovante de Rendimentos</span>
             </td>
         </tr>                              
        <?php 
        }
        ?>
         <tr style="cursor: pointer;" onClick="js_atualizaFrame('fichaFinanceira');">
            <td nowrap="nowrap" width="100%">
              <span class="navText" >Ficha Financeira</span>
            </td>
         </tr>
                    
         <tr style="cursor: pointer;" onClick="js_voltar('<?=$id_usuario?>');">
           <td nowrap="nowrap" width="100%" align="center">
             <span class="navText" >Voltar</span>
           </td>
         </tr>          
     </table>
    </td>
    <td valign="top" width="97%">
      <iframe id="iframePortalServidor" name="iframe" src="centro_pref.php" width="100%" height="500px;" style="border:hidden;"></iframe>
    </td> 
    <td>&nbsp;</td>       
  </tr>  
</table>
<? 
  } else if ($w13_permfornsemlog == "f") {
?>
 <table width="300" align="center" border="0" bordercolor="#cccccc" cellpadding="2" cellspacing="0" class="texto">
  <tr height="220">
   <td align="center">
    <img src="imagens/atencao.gif"><br>
    Para acessar suas informações, efetue login.
   </td>
  </tr>
 </table>
<?
}
?>
</body>
</html>
<script>
function imprimir(){
 jan=window.open('',
                 '',
                 'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
                 
 jan.moveTo(0,0);
}

function js_voltar(id){
  var idusuario = id;
  document.location.href = 'cons_funcional.php?id_usuario='+idusuario;
}

function js_atualizaFrame( sOpcao ){

  var sQuery = '<?=$sUrl?>'; 
 
  if ( sOpcao == 'dadosCadastrais') {
    document.getElementById('iframePortalServidor').src = 'dadosfuncionario.php?'+sQuery;
  } else if (sOpcao == 'assentamentos') {
    document.getElementById('iframePortalServidor').src = 'dadosassentamentos.php?'+sQuery;
  } else if (sOpcao == 'averbacao') {
    document.getElementById('iframePortalServidor').src = 'dadosassentamentos.php?'+sQuery+'<?=$sUrlAverba?>';
  } else if (sOpcao == 'dependentes') {
    document.getElementById('iframePortalServidor').src = 'dependentesservidor.php?'+sQuery;
  } else if (sOpcao == 'ferias') {
    document.getElementById('iframePortalServidor').src = 'feriasservidor.php?'+sQuery;
  } else if (sOpcao == 'comprovanteRendimentos') {
    document.getElementById('iframePortalServidor').src = 'comprovanterendimentosservidor.php?'+sQuery;
  } else if (sOpcao == 'fichaFinanceira') {
    document.getElementById('iframePortalServidor').src = 'fichafinanceiraservidor.php?'+sQuery;
  } 
  

}
</script>
