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

require("libs/db_conecta.php");
require("libs/db_stdlib.php");
include ("libs/db_utils.php");
include("classes/db_issbase_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$oGet  = db_utils::postMemory($_GET);

$sTipo  = $oGet->tipo;
$numcgm = $oGet->z01_numcgm;

$clissbase = new cl_issbase;
$clrotulo  = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("q02_inscr");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/db_script.js"></script>
</head>
<style>
td{
  font-size: 12px
  }
</style>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
             <form name="form2" method="post" action="" >
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tq02_inscr?>">
              <?=$Lq02_inscr?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
                 db_input("q02_inscr",4,$Iq02_inscr,true,"text",4,"","chave_q02_inscr");
              ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tz01_nome?>">
              <?=$Lz01_nome?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
                 db_input("z01_nome",40,$Iz01_nome,true,"text",4,"","chave_z01_nome");
              ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_inscr.hide();">
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
        $campos = "issbase.q02_inscr,issbase.q02_dtinic,cgm.z01_nome,cgm.z01_ender,cgm.z01_numero,cgm.z01_compl,cgm.z01_cgccpf";
        if(isset($chave_q02_inscr) && (trim($chave_q02_inscr)!="") ){
          $sql = "select $campos ";
          $sql .= "      from  issbase ";
          $sql .= "      inner join cgm on cgm.z01_numcgm = issbase.q02_numcgm";
          $sql .= "      left join escrito on escrito.q10_inscr = q02_inscr";
          $sql .= "      where issbase.q02_inscr = $chave_q02_inscr and q02_inscr not in (select p12_inscr from listainscr) and q02_dtbaix is null and ";
          $sql .= "      (q10_numcgm is null or q10_numcgm = $z01_numcgm)";
        }else if(isset($chave_z01_nome) && (trim($chave_z01_nome)!="") ){
          $sql = "select $campos ";
          $sql .= "      from  issbase ";
          $sql .= "      inner join cgm on cgm.z01_numcgm = issbase.q02_numcgm";
          $sql .= "      left join escrito on escrito.q10_inscr = q02_inscr";
          $sql .= "      where  cgm.z01_nome like '$chave_z01_nome%' and q02_inscr not in (select p12_inscr from listainscr)  and q02_dtbaix is null and ";
          $sql .= "      (q10_numcgm is null or q10_numcgm = $z01_numcgm)";
        }else{
          $sql = "";
        }
        
        if(!empty($sql)){
          db_lovrot($sql,15,"()","",$funcao_js);
        }
      }else{
      	 if (isset($sTipo) && $sTipo == 1) {
      	    $campos = "cgm.z01_nome,cgm.z01_cgccpf                                             ";
            $sql  = " select $campos                                                           ";
            $sql .= "  from  issbase                                                           ";
            $sql .= "        inner join cgm       on cgm.z01_numcgm    = issbase.q02_numcgm    ";
            $sql .= "        left  join escrito e on e.q10_inscr = q02_inscr                   ";
            $sql .= "  where issbase.q02_inscr = {$pesquisa_chave}                             ";
            $sql .= " 	 and q02_dtbaix is null                                                ";
            $sql .= "	 and not exists( select p12_inscr                                      "; 
            $sql .= "	 				   from listainscr                                     ";
            $sql .= "	 				        inner join listainscrcab on p11_codigo = p12_codigo  "; 
            $sql .= "	 				  where p11_processado is false           ";
            $sql .= "	 				    and p12_inscr = q02_inscr  limit 1)   "; 
            $sql .= "    and ( not exists ( select q10_inscr from escrito where escrito.q10_inscr = q02_inscr limit 1 )  ";
            $sql .= "     or e.q10_dtfim is not null)  ";
 
            $result = $clissbase->sql_record($sql);
              if($clissbase->numrows != 0){
                 db_fieldsmemory($result,0);
                 echo "<script>".$funcao_js."(\"$z01_nome\",\"\",false);</script>";
              } else {                 
                 echo "<script>".$funcao_js."('Inscrição ".$pesquisa_chave." não encontrado ou já está sendo utilizado','',true);</script>";
              }      	 	
      	 } else if (isset($sTipo) && $sTipo == 0) {
            $campos = "cgm.z01_nome,cgm.z01_cgccpf,q02_dtbaix";
            $sql  = " select $campos ";
            $sql .= "  from  issbase ";
            $sql .= "        inner join cgm     on cgm.z01_numcgm    = issbase.q02_numcgm";
            $sql .= "        left  join escrito on escrito.q10_inscr = q02_inscr";
            $sql .= "  where issbase.q02_inscr = {$pesquisa_chave} ";
             
            $result = $clissbase->sql_record($sql);
              if($clissbase->numrows != 0){
                 db_fieldsmemory($result,0);
                 echo "<script>".$funcao_js."(\"$z01_nome\",\"\",false);</script>";
              } else {
                 echo "<script>".$funcao_js."('Inscrição ".$pesquisa_chave." não encontrado','',true);</script>";
              }
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
     document.form2.chave_z01_nome.focus();
     document.form2.chave_z01_nome.select();
  </script>
  <?
}
?>