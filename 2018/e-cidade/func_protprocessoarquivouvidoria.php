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
include("classes/db_protprocesso_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clprotprocesso = new cl_protprocesso;
$clprotprocesso->rotulo->label("p58_codproc");
$clprotprocesso->rotulo->label("p58_codproc");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
	     <form name="form2" method="post" action="" >
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tp58_codproc?>">
              <?=$Lp58_codproc?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?php
		            db_input("p58_codproc",10,$Ip58_codproc,true,"text",4,"","chave_p58_codproc");
		          ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap="nowrap">
              <strong>Atendimento: </strong>
            </td>
            <td width="96%" align="left" nowrap="nowrap">
              <?php 
                db_input("atendimento", 10, "", true, "text", 4, 
                         " onchange='js_verificaAtendimento();' ", "chave_atendimento");
              ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      
       $sWhere = '';
       
       if ( isset($atend) && trim($atend) != '' ) {
         if ( $atend == 'true') {
         	 $sWhere .= ' and exists( select ov09_ouvidoriaatendimento
         	                            from processoouvidoria
         	                           where ov09_protprocesso = protprocesso.p58_codproc )';       	
         } else {
           $sWhere .= ' and not exists( select ov09_ouvidoriaatendimento
		                                      from processoouvidoria
		                                     where ov09_protprocesso = protprocesso.p58_codproc )';         	
         }
       }
      
        $sSql = " select  p58_codproc,
                          ov01_numero || '/' || ov01_anousu as ov01_numero,
                          p58_requer,
                          z01_nome,
                          p58_dtproc,
                          p51_descr,
                          p58_obs
                     from protprocesso 
                          inner join tipoproc on tipoproc.p51_codigo = protprocesso.p58_codigo
                          inner join cgm      on cgm.z01_numcgm      = protprocesso.p58_numcgm
                          inner join processoouvidoria on processoouvidoria.ov09_protprocesso = protprocesso.p58_codproc
                          inner join ouvidoriaatendimento on ouvidoriaatendimento.ov01_sequencial = processoouvidoria.ov09_ouvidoriaatendimento
                          left  join arqproc  on arqproc.p68_codproc = protprocesso.p58_codproc
                     where p51_tipoprocgrupo = 2
                       and p68_codproc is null
                       and p58_instit        = ".db_getsession('DB_instit')."
                       and not exists ( select p63_codtran
                                          from proctransferproc 
                                               inner join proctransand on p64_codtran = p63_codtran
                                         where p63_codproc = protprocesso.p58_codproc 
                                           and p64_codandam is null )
                       {$sWhere}";
      	
      
	      if ( !isset($pesquisa_chave) ) {
	      	
	        if (isset($chave_p58_codproc) && (trim($chave_p58_codproc)!="") ){
	          $sSql .= " and p58_codproc = {$chave_p58_codproc} ";
	        }
	        if (isset($chave_atendimento) && (trim($chave_atendimento) != "")) {
	        	
	        	list($iAtendimento, $iAno) = explode("/", $chave_atendimento);
	        	$sSql .= " and ov01_numero = {$iAtendimento} and ov01_anousu = {$iAno} ";
	        }
	
	        db_lovrot($sSql,15,"()","",$funcao_js);
	        
	      } else {
	      	
	        if ( $pesquisa_chave!=null && $pesquisa_chave!="" ) {
	        	
	 	         $sSql  .= " and p58_codproc = {$pesquisa_chave}";
	           $result = $clprotprocesso->sql_record($sSql);
	          
	          if ( $clprotprocesso->numrows!=0 ) {
	            db_fieldsmemory($result,0);
	            echo "<script>".$funcao_js."('$z01_nome',false);</script>";
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
</body>
</html>
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
document.form2.chave_p58_codproc.focus();
document.form2.chave_p58_codproc.select();

  function js_verificaAtendimento() {

	  var sAtendimento = $('chave_atendimento').value;
	  if (sAtendimento.indexOf('/') == -1) {

		  alert('O atendimento deve ser informado no formato atendimento/ano. Favor verificar.');
		  $('chave_atendimento').value= '';
		  $('chave_atendimento').focus();
	  }
  }
  </script>
  <?
}
?>