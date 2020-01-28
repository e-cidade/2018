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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_ouvidoriaatendimento_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clouvidoriaatendimento = new cl_ouvidoriaatendimento;
$clouvidoriaatendimento->rotulo->label("ov01_sequencial");
$clouvidoriaatendimento->rotulo->label("ov01_numero");
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
            <td width="4%" align="right" nowrap title="<?=$Tov01_sequencial?>">
              <?=$Lov01_sequencial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("ov01_sequencial",10,$Iov01_sequencial,true,"text",4,"","chave_ov01_sequencial");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tov01_numero?>">
              <?=$Lov01_numero?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("ov01_numero",10,$Iov01_numero,true,"text",4,"","chave_ov01_numero");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_ouvidoriaatendimento.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      
      $sWhere = 'ov01_instit = '.db_getsession('DB_instit');
      
      if ( isset($deptoatual)) {
        $sWhere .= " and ov01_depart = ".db_getsession('DB_coddepto');       
      }
      if ( isset($situacao) && trim($situacao) != '' ) {
        $sWhere .= " and ov01_situacaoouvidoriaatendimento = {$situacao} ";      	
      }
      if ( isset($tramiteinicial) && trim($tramiteinicial) != '' ) {
      	if ( $tramiteinicial == 'true') { 
          $sWhere .= " and ov15_sequencial is not null ";
      	} else {
          $sWhere .= " and ov15_sequencial is null ";
      	}
      }      
      
      if ( isset($tipo) && trim($tipo) != '') {
      	$sWhere .= " and ov01_tipoprocesso = {$tipo} ";
      }

      if ( isset($proc) ) {
        if ( trim($proc) == 'true' ) {
          $sWhere .= " and ov09_sequencial is not null";
      	} else {
      		$sWhere .= " and ov09_sequencial is null";
      	}
      }

      if ( isset($tramite) ) {
        $sWhere .= " and ( case
				                     when protprocesso.p58_codandam != 0 then false
				                     when not exists ( select p63_codtran   
				                                        from proctransferproc
				                                             inner join protprocesso p on p.p58_codproc = p63_codproc
				                                             left  join proctransand t on t.p64_codtran = p63_codtran
				                                       where p63_codproc    = protprocesso.p58_codproc
				                                         and p.p58_codandam = 0  
				                                         and t.p64_codtran is null
				                                       limit 1 ) then true 
				                     else false
				                   end )";
      }       
      
      if(!isset($pesquisa_chave)){

        $campos  = "distinct ov01_sequencial,";
        $campos .= "fc_numeroouvidoria(ov01_sequencial) as ov01_numero,";
        $campos .= "ov01_anousu,";
        $campos .= "ov01_requerente,";
        $campos .= "ov01_dataatend,";
        $campos .= "ov01_horaatend,";
        $campos .= "p51_descr,";
        $campos .= "ov18_descricao,";
        $campos .= "ov01_solicitacao";
        
        if(isset($chave_ov01_sequencial) && (trim($chave_ov01_sequencial)!="") ){
	         $sql = $clouvidoriaatendimento->sql_query_proc(null,$campos,"ov01_sequencial",$sWhere." and ov01_sequencial = {$chave_ov01_sequencial} ");
        }else if(isset($chave_ov01_numero) && (trim($chave_ov01_numero)!="") ){
	         $sql = $clouvidoriaatendimento->sql_query_proc("",$campos,"ov01_numero",$sWhere." and ov01_numero like '$chave_ov01_numero%' ");
        }else{
           $sql = $clouvidoriaatendimento->sql_query_proc("",$campos,"ov01_sequencial",$sWhere);
        }
        $repassa = array();
        if(isset($chave_ov01_numero)){
          $repassa = array("chave_ov01_sequencial"=>$chave_ov01_sequencial,"chave_ov01_numero"=>$chave_ov01_numero);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clouvidoriaatendimento->sql_record($clouvidoriaatendimento->sql_query_proc(null,"*",null,$sWhere." and ov01_sequencial = {$pesquisa_chave}"));
          if($clouvidoriaatendimento->numrows!=0){
            db_fieldsmemory($result,0);
            
            if ( isset($requer) ) { 
              echo "<script>".$funcao_js."('$ov01_numero','$ov01_requerente',false);</script>";
            } else {
              echo "<script>".$funcao_js."('$ov01_numero',false);</script>";
            }
            
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
<script>
js_tabulacaoforms("form2","chave_ov01_numero",true,1,"chave_ov01_numero",true);
</script>