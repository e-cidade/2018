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

include("libs/db_utils.php");
require("libs/db_stdlib.php");
require("std/db_stdClass.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
require("libs/db_liborcamento.php");
require("classes/db_orcdotacao_classe.php");
require("classes/db_orcparametro_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
//echo($HTTP_SERVER_VARS["QUERY_STRING"]);

$clorcdotacao = new cl_orcdotacao;
$clorcdotacao->rotulo->label();
$clestrutura = new cl_estrutura;
$sWhere = null;
if (isset($pactoplano) && $pactoplano != "") {
 
  $oDaoPactoSolicita = db_utils::getDao("pactoplano");
  $sSqlPacto         = $oDaoPactoSolicita->sql_query(null,"*",null,"o74_sequencial={$pactoplano}");
  $rsPacto           = $oDaoPactoSolicita->sql_record($sSqlPacto);
  if ($oDaoPactoSolicita->numrows > 0) {
      
    $oPlano  = db_utils::fieldsMemory($rsPacto, 0);
    $sWhere .= " (o15_tipo = 1  or o58_codigo = {$oPlano->o16_orctiporec})"; 
     
  }
}
// variável que determina o obrigatoriedade de digitar departamento
//$obriga_depto = "sim";


  if(empty($elemento)){
    $elemento=null;
  }
  if(!isset($secretaria) || $secretaria == 0 ){
    $secretaria = null;
  }
  if(!isset($departamento) || $departamento == 0 ){
    $departamento = null;
  }
  $erro = "";
  $clpermusuario_dotacao =  new cl_permusuario_dotacao(
                                 db_getsession('DB_anousu'),
                                 db_getsession('DB_id_usuario'),
                                 $elemento,
                                 $secretaria,
                                 $departamento,
                                 'M',
                                 "",
                                 $sWhere
                                );

if(!isset($filtroquery)){
  // variável usada na solicitação de compras para retornar departamento quando o reduzido é digitado
  if(!isset($retornadepart)){
    $retornadepart = null;
  }
  // desmentando abaixo descobrimos se a classe 
  // esta retornando as permissões
  if($clpermusuario_dotacao->sql!=""){
    if(isset($chave_o58_coddot) && $chave_o58_coddot != ""){
      $result = pg_query($clpermusuario_dotacao->sql);
      $tem_perm = 0;
      for($i=0;$i<pg_numrows($result);$i++){
        if($chave_o58_coddot == pg_result($result,$i,"o58_coddot")){
  	  $tem_perm = 1;
        }
      }
      if($tem_perm == 1){
        $passar = true;
        if( $obriga_depto=="sim"  ){
          if($departamento==0){
	    $passar = false;
	  }
        }
        if($passar){
          $executa = split("\|",$executar);
          // variável retornadepart usada na solicitação de compras para retornar departamento quando o reduzido é digitado
	  if($retornadepart==null){
            echo "<script>".$executa[0]."('$chave_o58_coddot');</script>";
	  }else{
            echo "<script>".$executa[0]."('$chave_o58_coddot','$departamento');</script>";
	  }
          exit;
        }else{
          $erro  = "Você deve selecionar um Departamento!";
        }
      }else{
        $erro = "Sem permissão para esta dotação!";
      }
    }
  }
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_secretaria(){
  if( document.form1.departamento != undefined ){
    document.form1.departamento[0].selected = true;
    document.form1.departamentodescr[0].selected = true;
  }
  document.form1.submit();
}
function js_departamento(){
  document.form1.secretaria[0].selected = true;
  document.form1.secretariadescr[0].selected = true;

  document.form1.submit();
}
function js_verifica_depto(coddot){
  if( document.form1.departamento == undefined || document.form1.departamento.value == 0){
     alert('Selecione um departamento.');
  }else{
//    alert(coddot);
//    alert(document.form1.departamento.value);
    <?
    $executa = split("\|",$funcao_js);
    echo $executa[0]."(coddot,document.form1.departamento.value);";
    ?>
  }
}
function js_origempermissao(){

  var sUrl = 'func_origempermissao.php';
  js_OpenJanelaIframe('parent','db_iframe_origempermissao',sUrl,'Origem Permissão',true,'0',1);
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<center>
<table height="100%" border="0"  cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td valign="top" align="center">
	<form name="form1" method="post" action="" >
        <table border="0" cellspacing="0">
          <tr> 
            <td align="center" nowrap title="<?=$To58_coddot?>">
              <?=$Lo58_coddot?>
            </td>
            <td nowrap> 
              <?
	      db_input("o58_coddot",6,$Io58_coddot,true,"text",4,"","chave_o58_coddot");
	      ?>
            </td>
          </tr>
             <?
             //$clestrutura->nomeform="form2";//o nome do campo é DB_txtdotacao
	     //$clestrutura->estrutura('o50_estrutdespesa')
             ?>
	  <tr>
	  <td><strong>Secretaria:</strong></td>
	  <td>
	  <?
          if($clpermusuario_dotacao->sql!=""){
//            echo "<br>";
//            echo $clpermusuario_dotacao->orgaos;
            $result = pg_query($clpermusuario_dotacao->orgaos);
	    if($result!=false && pg_numrows($result)>0){
              db_selectrecord("secretaria",$result,true,2,"","","","0","js_secretaria()");
	      if(pg_numrows($result)==1){
		echo "<script>
		      document.form1.secretaria[1].selected = true;
		      document.form1.secretariadescr[1].selected = true;
		      </script>";
	      }

	    }
          }

          ?>
          </td>
          </tr>
	  <tr>
	  <td><strong>Departamentos:</strong></td>
	  <td>
	  <?
          if($clpermusuario_dotacao->sql!=""){
            //echo "<br>";
            //echo $clpermusuario_dotacao->depart;
	    $result = pg_query($clpermusuario_dotacao->depart);
	    if($result!=false && pg_numrows($result)>0){
               db_selectrecord("departamento",$result,true,2,"","","","0","js_departamento()");
	       if(pg_numrows($result)==1){
//	 	 echo "<script>
//		      document.form1.departamento[1].selected = true;
//		      document.form1.departamentodescr[1].selected = true;
//		      </script>";
	       }
	    }else{
	       global $sem_departamento;
	       $sem_departamento = "Não existem departamentos para esta secretária";
	       db_input("sem_departamento",50,1,true,3);
	    }
	    
          }

          ?>
          </td>
          </tr>

          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="executar" type="hidden" id="executar" value="<?=$funcao_js?>" >
              <input name="obriga_depto" type="hidden" id="obriga_depto" value="<?=(isset($obriga_depto)?"sim":"nao")?>" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_orcdotacao.hide();">
              <input name="origempermissao" type="button" id="origempermissao" value="Origem Permissão" onClick="js_origempermissao();">
             </td>
          </tr>
        </table>
	  </form>
    <?
		

   // echo $elemento;
//    echo "<br>sql: " . $clpermusuario_dotacao->sql;
    if($clpermusuario_dotacao->sql != "" ){
      if(isset($obriga_depto) && $obriga_depto=="sim"){
        $funcao_js = "js_verifica_depto|o58_coddot";
      }

      $variaveis["secretaria"] =  (isset($secretaria)?$secretaria:0);
      $variaveis["departamento"] =(isset($departamento)?$departamento:0);
      db_lovrot($clpermusuario_dotacao->sql,15,"()","",$funcao_js,"","NoMe",$variaveis,false);
      
    }else{
      echo "<table><tr><td><br><strong>Não existe dotação para este item</strong>.</td></tr></table>";
    }

    ?>
    </td>
   </tr>
</table>
</center>
</body>
</html>
<?
if($erro!=""){
  db_msgbox($erro);
  echo "<script>document.form1.chave_o58_coddot.value = '';</script>";

}

?>