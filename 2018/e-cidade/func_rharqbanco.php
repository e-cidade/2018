<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
require_once("classes/db_rharqbanco_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$oGet         = db_utils::postMemory($_GET);
$clrharqbanco = new cl_rharqbanco;
$clrharqbanco->rotulo->label("rh34_codarq");
$clrharqbanco->rotulo->label("rh34_descr");

if ( isset($chave_rh34_codarq) && !DBNumber::isInteger($chave_rh34_codarq) ) {
  $chave_rh34_codarq = '';
}

$chave_rh34_descr = isset($chave_rh34_descr) ? pg_escape_string( $chave_rh34_descr ) : '';

$sWhereBancosAtivos = '';
if( !empty($ativas) ) {
  $sWhereBancosAtivos = ' and rh34_ativo = true ';
}

(isset($GLOBALS['opt_todosbcos'])) ? $GLOBALS['opt_todosbcos'] = $GLOBALS['opt_todosbcos'] : '';
?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  </head>
  <body>
    <form name="form2" method="post" action="" class="container" >
      <fieldset>
        <legend>Pesquisa de Arquivo Bancário</legend>

        <table width="35%" border="0" align="center" cellspacing="0" class="form-container">
          <tr> 
          <td title="<?=$Trh34_codarq?>">
            <?php echo $Lrh34_codarq; ?>
          </td>
          <td> 
          <?
          db_input("rh34_codarq",6,$Irh34_codarq,true,"text",4,"","chave_rh34_codarq");
          ?>
          </td>
          </tr>
          <tr> 
          <td title="<?=$Trh34_descr?>">
            Descrição:
          </td>
          <td> 
          <?
          db_input("rh34_descr",40,$Irh34_descr,true,"text",4,"","chave_rh34_descr");
          ?>
          </td>
          </tr>
        </table>
      </fieldset>
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar" /> 
      <input name="limpar" type="reset" id="limpar" value="Limpar" />
      <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_rharqbanco.hide();" />
    </form>
    <div class="container">
      <?php
      $chave_rh34_descr = addslashes($chave_rh34_descr);

      if(!isset($pesquisa_chave)) {

        if(isset($campos)==false) {
          if(file_exists("funcoes/db_func_rharqbanco.php")==true) {
            include("funcoes/db_func_rharqbanco.php");
          }else{
            $campos = "rharqbanco.*";
          }
        }

        if( isset($chave_rh34_codarq) ) {
          if (  !DBNumber::isInteger($chave_rh34_codarq) ) {
            $chave_rh34_codarq = '';
          }
        }
        $sWhere  = "rh34_instit = ".db_getsession('DB_instit');
        $sWhere .= $sWhereBancosAtivos;

        if ( isset( $oGet->iCodigoBanco ) ) {
          $sWhere .= " and rh34_codban = '".pg_escape_string($oGet->iCodigoBanco)."' ";
        }

        if(isset($chave_rh34_codarq) && (trim($chave_rh34_codarq)!="" && DBNumber::isInteger($chave_rh34_codarq))){
          $sql = $clrharqbanco->sql_query("",db_getsession('DB_instit'),$campos,"rh34_codarq","rh34_codarq = {$chave_rh34_codarq} and {$sWhere}" );
        }else if(isset($chave_rh34_descr) && (trim($chave_rh34_descr)!="") ){
          $sql = $clrharqbanco->sql_query("",db_getsession('DB_instit'),$campos,"rh34_descr"," rh34_descr like '$chave_rh34_descr%' and {$sWhere}");
        }else{
          $sql = $clrharqbanco->sql_query("",db_getsession('DB_instit'),$campos,"rh34_codarq",$sWhere);
        }

        if( isset($chave_rh34_descr) ){
          $chave_rh34_descr = str_replace("\\", "", $chave_rh34_descr);
        }
        echo "<fieldset>                               \n";
        echo "  <legend>Resultado da Pesquisa</legend> \n";
        db_lovrot($sql,15,"()","",$funcao_js);
        echo "</fieldset>                              \n";
      }else{

        if($pesquisa_chave!=null && $pesquisa_chave!=""){

          $sWhere = "rh34_codarq = $pesquisa_chave ";

          if ( isset( $oGet->iCodigoBanco ) ) {
            $sWhere .= " and rh34_codban = '".pg_escape_string($oGet->iCodigoBanco)."' ";
          }

          $result = $clrharqbanco->sql_record($clrharqbanco->sql_query($pesquisa_chave,db_getsession('DB_instit'), '*', null, $sWhere));

          if($clrharqbanco->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$rh34_descr',false);</script>";
          }else{
            echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }
        }else{
          echo "<script>".$funcao_js."('',false);</script>";
        }
      }
?>
    </div>
  </body>
</html>
<?
if(!isset($pesquisa_chave)){
?>
<script>
  (function(){
   var identificadorArquivo = '<?php echo (isset($chave_rh34_codarq)) ? $chave_rh34_codarq : ''; ?>';

   if( identificadorArquivo != ''){
    if( document.getElementById(identificadorArquivo).value != '') {
     var oRegex  = /^[0-9]+$/;
     if ( !oRegex.test( document.getElementById(identificadorArquivo).value ) ) {
       alert('Código do Arquivo deve ser preenchido somente com números!');
       document.getElementById(identificadorArquivo).value = '';
       return false;  
     }
    }
   }

   })();
</script>
<?
}
?>
