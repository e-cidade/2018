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
include("classes/db_placaixa_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clplacaixa = new cl_placaixa;
$clplacaixa->rotulo->label("k80_codpla");
$clplacaixa->rotulo->label("k80_data");
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
            <td width="4%" align="right" nowrap title="<?=$Tk80_codpla?>">
              <?=$Lk80_codpla?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("k80_codpla",6,$Ik80_codpla,true,"text",4,"","chave_k80_codpla");
		       ?>
            </td>
          </tr>
          <tr>
            <td>
               <b>Mostrar:</b>
            </td>
            <td>
              <?
                $aTipo = array(
                               1 => "Não Autenticadas" ,
                               2 => "Todas"
                              );
               db_select("mostrar", $aTipo,true,1,"onchange='document.form2.submit()'" );               
              ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_placaixa.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $where  = "";
       $ano = db_getsession("DB_anousu");               
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_placaixa.php")==true){
             include("funcoes/db_func_placaixa.php");
           }else{
           $campos = "placaixa.*";
           }
        }
        if (!isset($mostrar)) {
          $mostrar = 1;
        }
        if ($mostrar == 1) {
          $sWhere = " and k82_seqpla is null";
        } else {
          $sWhere = null;
        }
        if(isset($chave_k80_codpla) && (trim($chave_k80_codpla)!="") ){
           //$sql = $clplacaixa->sql_query_rec(null,$campos,"k80_codpla desc"," k80_instit = ".db_getsession("DB_instit")."  and k80_dtaut is null and k80_codpla = $chave_k80_codpla");
	       $where .= "and k80_codpla = $chave_k80_codpla";
        }else if(isset($chave_k80_data) && (trim($chave_k80_data)!="") ){
           $where .= "and k80_data like '$chave_k80_data%'";
	       //  $sql = $clplacaixa->sql_query_rec("",$campos,"k80_data desc"," k80_instit = ".db_getsession("DB_instit")." and  k80_dtaut is null and k80_data like '$chave_k80_data%' ");
        }
          
        $sql  = " select k80_codpla, k80_data, k80_dtaut, k81_valor ";
        $sql .= "   from ( select distinct k80_codpla,k80_data, k80_dtaut,"; 
        $sql .= "                 (select sum(k81_valor)"; 
        $sql .= "                    from placaixarec";
        $sql .= "                   where k81_codpla = k80_codpla ) as k81_valor"; 
				$sql .= "	  from placaixa";
				$sql .= "	      left join placaixarec a on k81_codpla = k80_codpla "; 
				$sql .= "	      left join corplacaixa   on k82_seqpla = k81_seqpla";
				$sql .= "	      inner join db_config    on db_config.codigo = placaixa.k80_instit ";
				$sql .= "	 where k80_instit = ".db_getsession("DB_instit");   
				$sql .= "     and extract(year from k80_data) = $ano ";  
				$sql .= "	   and k80_dtaut is null ";
				$sql .= "     $where ";
				$sql .= "	   {$sWhere} ";
				$sql .= "	 order by k80_codpla desc )as x ";
				
        // $sql = $clplacaixa->sql_query_rec(null,$campos,"k80_codpla desc"," k80_instit = ".db_getsession("DB_instit")." and k80_dtaut is null" );
        // die($sql);
        $repassa = array();
        if (isset($chave_k80_codpla)) {
          $repassa = array("chave_k80_codpla" => $chave_k80_codpla);      
        }
        db_lovrot($sql, 15,"()","",$funcao_js,"","NoMe",$repassa, false);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
	        $sql = $clplacaixa->sql_query_rec(null,$campos,"k80_codpla desc"," k80_instit = ".db_getsession("DB_instit")."  and k80_dtaut is null and k80_codpla = $pesquisa_chave");
          $result = $clplacaixa->sql_record($sql);
          if($clplacaixa->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$k80_data',false);</script>";
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