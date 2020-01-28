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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_orcdotacao_classe.php");
require("libs/db_liborcamento.php");
include("classes/db_orcparametro_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clorcdotacao = new cl_orcdotacao;
$clorcdotacao->rotulo->label("o58_anousu");
$clorcdotacao->rotulo->label("o58_coddot");
$clorcdotacao->rotulo->label("o58_orgao");
$clestrutura = new cl_estrutura;
$clorcparametro = new cl_orcparametro;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
        <form name="form2" method="post" action="" >
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$To58_coddot?>">
            <?=$Lo58_coddot?>
            </td>
            <td width="96%" align="left" nowrap> 
            <? db_input("o58_coddot",6,$Io58_coddot,true,"text",4,"","chave_o58_coddot"); ?>
            </td>
          </tr>
          <?
           $clestrutura->nomeform="form2";//o nome do campo é DB_txtdotacao
	   $clestrutura->estrutura('o50_estrutdespesa')
          ?>	  
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_orcdotacao.hide();">
             </td>
          </tr>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
<?
if(!isset($pesquisa_chave)){
  if(isset($campos)==false){
     if(file_exists("funcoes/db_func_orcdotacao.php")==true){
       include("funcoes/db_func_orcdotacao.php");
     }else{
	$campos = "orcdotacao.*";
     }
  }
  $dbwhere='';
  if(isset($chave_o58_coddot) && (trim($chave_o58_coddot)!="") ){
      $dbwhere=" and o58_coddot=$chave_o58_coddot";
  }else if(isset($o50_estrutdespesa) && ($o50_estrutdespesa!="") ){
    $matriz=split('\.',$o50_estrutdespesa);
    for($i=0; $i<count($matriz); $i++){
      switch($i){
	    case 0://orgao
		$dbwhere.=" and o58_orgao =".$matriz[$i];
		break;
	    case 1://unidade
		$dbwhere.="  and o58_unidade =".$matriz[$i];
		break;
	    case 2://funcao
		$dbwhere.="  and o58_funcao =".$matriz[$i];
		break;
	    case 3://subfuncao	
		$dbwhere.=" and  o58_subfuncao =".$matriz[$i];
		break;
	    case 4://programa
		$dbwhere.="  and o58_programa =".$matriz[$i];
		break;
	    case 5://projativ
		$dbwhere.="  and o58_projativ =".$matriz[$i];
		break;
	    case 6://elemento de despesa	
		$dbwhere.="  and o56_elemento ='".$matriz[$i]."'";
		break;
	    case 7://tipo de  recurso
		$dbwhere.="  and o58_codigo =".$matriz[$i];
		break;
      } 
    }
  }
  /* quando a instituição é prefeitura, é permitido selecionar dotações de outras instituições */
  $where_instit = "o58_instit=".db_getsession("DB_instit");
  $sql_instit = "select prefeitura  /* campo boolean */
                 from db_config 
		 where codigo = ".db_getsession("DB_instit");
  $res_instit = $clorcdotacao->sql_record($sql_instit);   
  if ($clorcdotacao->numrows !=0){
      db_fieldsmemory($res_instit,0);
      if  ($prefeitura =='t')
	$where_instit ="1=1 ";
  }    
  /* --- */  
  $sql = "
     select fc_estruturaldotacao(".db_getsession("DB_anousu").",o58_coddot) as dl_estrutural,
            o56_elemento,
	    o55_descr::text,
	    o56_descr,
	    o58_coddot,
	    o58_instit
     from orcdotacao d
 	  inner join orcprojativ p on p.o55_anousu = ".db_getsession("DB_anousu")." and p.o55_projativ = d.o58_projativ
	  inner join orcelemento e on e.o56_codele = d.o58_codele and o56_anousu = o58_anousu
     where  $where_instit  
        and o58_anousu=".db_getsession('DB_anousu')." $dbwhere
     order by dl_estrutural
     ";
  // ve se algum campo está preenchido
  if ((isset($chave_o58_coddot)&&(trim($chave_o58_coddot)!=""))
       || (isset($o50_estrutdespesa) && ($o50_estrutdespesa!=""))){
      db_lovrot($sql,15,"()","",$funcao_js);
  }  
}else{
  if($pesquisa_chave!=null && $pesquisa_chave!=""){
      // Dim result as RecordSet
      $result = $clorcdotacao->sql_record($clorcdotacao->sql_query(db_getsession("DB_anousu"),$pesquisa_chave));
      if($clorcdotacao->numrows!=0){
         db_fieldsmemory($result,0);
         echo "<script>".$funcao_js."('$o56_descr',false);</script>";
      }else{
         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
      }
  }else{
       echo "<script>".$funcao_js."('',false);</script>";
  }
}
?>
     </td>
   </tr>
</table>
        </form>
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