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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_conplano_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clconplano = new cl_conplano;
$clconplano->rotulo->label("c60_codcon");
$clconplano->rotulo->label("c60_descr");
$clconplano->rotulo->label("c60_estrut");
$clrotulo = new rotulocampo;
$clrotulo->label("c61_reduz"); 



function sql_query_contacorrente ( $c60_codcon=null,$c60_anousu=null,$campos="*",$ordem=null,$dbwhere=""){
  $sql = "select ";
  if($campos != "*" ){
    $campos_sql = split("#",$campos);
    $virgula = "";
    for($i=0;$i<sizeof($campos_sql);$i++){
      $sql .= $virgula.$campos_sql[$i];
      $virgula = ",";
    }
  }else{
    $sql .= $campos;
  }
  $sql .= " from conplano ";
  $sql .= "      inner join conplanoreduz          on conplano.c60_codcon = conplanoreduz.c61_codcon              ";
  $sql .= "                                       and conplano.c60_anousu = conplanoreduz.c61_anousu              ";
  $sql .= "      inner join conplanocontacorrente  on conplanoreduz.c61_codcon = conplanocontacorrente.c18_codcon ";
  $sql .= "                                       and conplanoreduz.c61_anousu = conplanocontacorrente.c18_anousu ";
  $sql2 = "";
  
  if($dbwhere==""){
    if($c60_codcon!=null ){
      $sql2 .= " where conplano.c60_codcon = $c60_codcon ";

      if($c60_anousu!=null ){
        $sql2 .= " where conplano.c60_codcon = $c60_codcon and c60_anousu= $c60_anousu ";
      }
    } elseif($c60_anousu!=null ){
      $sql2 .= " where conplano.c60_anousu = $c60_anousu ";
    }
  }else if($dbwhere != ""){
    $sql2 = " where $dbwhere";
  }

  $sql2 .= ($sql2!=""?" and ":" where ") . " c60_anousu=".db_getsession("DB_anousu")." and c61_instit = " . db_getsession("DB_instit");
   
  $sql .= $sql2;
  if($ordem != null ){
    $sql .= " order by ";
    $campos_sql = split("#",$ordem);
    $virgula = "";
    for($i=0;$i<sizeof($campos_sql);$i++){
      $sql .= $virgula.$campos_sql[$i];
      $virgula = ",";
    }
  }
  return $sql;
}


$sWhereContaCorrente = ' and 1 = 1 ';
if (isset($iConta)) {
  
  if ($iConta == '') {
    
    db_msgbox("Selecione uma conta corrente antes de selecionar reduzidos.");
    echo "<script>parent.db_iframe_conplano.hide();</script>";
    
  }
  
  $sWhereContaCorrente .= " and c18_contacorrente = {$iConta} ";
}


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
            <td width="4%" align="right" nowrap title="<?=$Tc60_codcon?>">
              <?=$Lc60_codcon?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("c60_codcon",6,$Ic60_codcon,true,"text",4,"","chave_c60_codcon");
		       ?>
            </td>
            <td width="4%" align="right" nowrap title="<?=$Tc60_estrut?>">
              <?=$Lc60_estrut?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("c60_estrut",15,$Ic60_estrut,true,"text",4,"","chave_c60_estrut");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tc61_reduz?>">
              <?=$Lc61_reduz?>
	    </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("c61_reduz",6,$Ic61_reduz,true,"text",4,"","chave_c61_reduz");
		       ?>
            </td>
            <td width="4%" align="right" nowrap title="<?=$Tc60_descr?>">
              <?=$Lc60_descr?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("c60_descr",50,$Ic60_descr,true,"text",4,"","chave_c60_descr");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="4" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_conplano.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      if(!isset($pesquisa_chave)){
            
        
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_conplano.php")==true){
             include("funcoes/db_func_conplano.php");
           }else{
           $campos = "conplano.*";
           }
        }
        
        $campos  = "conplano.c60_codcon,                                  ";
        $campos .= "conplano.c60_estrut,                                  ";
        $campos .= "conplanoreduz.c61_reduz,                              ";
        $campos .= "fc_nivel_plano2005(conplano.c60_estrut) as dl_Nivel,  ";
        $campos .= "conplanoreduz.c61_instit,                             ";
        $campos .= "conplano.c60_descr,                                   ";
        $campos .= "conplano.c60_finali,                                  ";
        $campos .= "conplano.c60_codsis as DB_codsis,                     ";
        $campos .= "conplano.c60_codcla as DB_codcla                      ";
        
        echo @$chave_c60_codcon;
        
        if(isset($chave_c60_codcon) && (trim($chave_c61_reduz)!="") ){
	         $sql = sql_query_contacorrente(null,null,$campos,"c60_codcon","c61_reduz=$chave_c61_reduz $sWhereContaCorrente");
        }elseif(isset($chave_c60_codcon) && (trim($chave_c60_codcon)!="") ){
	         $sql = sql_query_contacorrente($chave_c60_codcon,null,$campos,"c60_codcon","c60_codcon = $chave_c60_codcon $sWhereContaCorrente");
        }else if(isset($chave_c60_estrut) && (trim($chave_c60_estrut)!="") ){
	         $sql = sql_query_contacorrente("",null,$campos,"c60_codcon"," c60_estrut like '$chave_c60_estrut%' $sWhereContaCorrente");
        }else if(isset($chave_c60_descr) && (trim($chave_c60_descr)!="") ){
	         $sql = sql_query_contacorrente("",null,$campos,"c60_descr"," upper(c60_descr) like '$chave_c60_descr%' $sWhereContaCorrente");
        }else if( isset($tipo_sql) ){//zé... coloquei esta opcao para o formulario do tabrec
           $sql = sql_query_contacorrente("",$campos.",c61_reduz as db_c61_reduz,c60_estrut as db_c60_estrut","c60_estrut",$sWhereContaCorrente);
        }else{
          
           $sql = sql_query_contacorrente("",null,$campos,"c60_estrut", "c60_anousu=".db_getsession("DB_anousu") . $sWhereContaCorrente);           
        }        

        db_lovrot($sql,15,"()","",$funcao_js);
        
      }else{
        
        if ($pesquisa_chave != null && $pesquisa_chave != ""){
          
          $sSql = sql_query_contacorrente($pesquisa_chave, null, "c61_reduz,c60_descr", null, " c61_reduz=" . $pesquisa_chave . $sWhereContaCorrente );

          $result = $clconplano->sql_record($sSql);
                    
          if ($clconplano->numrows != 0) {
            
            db_fieldsmemory($result,0);
            
            echo "<script>".$funcao_js."('$c60_descr',false, '$c60_estrut');</script>";
            
          }else{
            
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