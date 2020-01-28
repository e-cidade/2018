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

db_postmemory($_GET);
db_postmemory($_POST);

$clrotulo = new rotulocampo();
$clrotulo->label("z01_numcgm");
$clrotulo->label("ov02_nome");
$clrotulo->label("ov02_sequencial");
$clrotulo->label("ov02_cnpjcpf");


?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td>
      <fieldset>
        <legend>
          <b>Consulta Cidadão/CGM</b>
        </legend>
        <table width="100%">
	      <form name="form2" method="post" action="" >
          <tr> 
            <td>
              <b>Código CGM:</b>
            </td>
            <td> 
              <?
    		       db_input("z01_numcgm",10,$Iz01_numcgm,true,"text",4,"");
		         ?>
            </td>
          </tr>
          <tr>             
            <td>
              <b>Código Cidadão:</b>
            </td>
            <td> 
              <?
               db_input("ov02_sequencial",10,$Iov02_sequencial,true,"text",4,"");
             ?>
            </td>            
          </tr>
          <tr>             
            <td>
              <b>CPF/CNPJ:</b>
            </td>
            <td> 
              <?
               db_input("ov02_cnpjcpf",10,$Iov02_cnpjcpf,true,"text",4,"");
             ?>
            </td>            
          </tr>          
          <tr> 
            <td>
              <b>Nome:</b>
            </td>
            <td> 
              <?
		            db_input("ov02_nome",50,$Iov02_nome,true,"text",4,"");
		          ?>
            </td>
          </tr>
          <tr> 
            <td colspan="4" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar"    type="reset"  id="limpar"     value="Limpar" >
              <input name="Fechar"    type="button" id="fechar"     value="Fechar" onClick="parent.db_iframe_titularatend.hide();">
              <input name="cadastrar" type="button" id="cadastrar"  value="Cadastrar Cidadao" onClick="js_cadastrarCidadao();">
            </td>
          </tr>
          </form>
        </table>
      </fieldset>
    </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      
       $sWhereCGM      = " 1=1 ";
       $sWhereCidadao  = "     ov02_ativo is true  ";
       $sWhereCidadao .= " and ov03_numcgm is null ";
       
       if ( isset($z01_numcgm) && trim($z01_numcgm) != '' ) {
 	       $sWhereCGM     .= " and z01_numcgm = {$z01_numcgm}";
       }
       
       if ( isset($ov02_sequencial) && trim($ov02_sequencial) != '' ) {
         $sWhereCidadao .= " and ov02_sequencial = {$ov02_sequencial}";        
       }
       
       if ( isset($ov02_cnpjcpf) && trim($ov02_cnpjcpf) != '' ) {
         $sWhereCGM     .= " and z01_cgccpf   = {$ov02_cnpjcpf}";
         $sWhereCidadao .= " and ov02_cnpjcpf = {$ov02_cnpjcpf}";        
       }
                         
       if ( isset($ov02_nome) && trim($ov02_nome) != '' ) {
         $sWhereCGM     .= " and z01_nome  like '{$ov02_nome}%' ";
         $sWhereCidadao .= " and ov02_nome like '{$ov02_nome}%' ";        
       }                        
      
       $sSqlCidadao  = " select ov02_sequencial as codigo,";
			 $sSqlCidadao .= "        ov02_seq        as db_seq,";  
			 $sSqlCidadao .= " 	      ov02_nome      as nome,   ";
			 $sSqlCidadao .= " 	      'Cidadao'      as tipo    ";
			 $sSqlCidadao .= " 	 from cidadao                   ";
  	   $sSqlCidadao .= "        left join cidadaocgm  on ov03_cidadao = ov02_sequencial ";
  	   $sSqlCidadao .= "                             and ov03_seq     = ov02_seq        ";
			 $sSqlCidadao .= "  where $sWhereCidadao            ";
			 
			 $sSqlCgm  = " select z01_numcgm as codigo,";
			 $sSqlCgm .= "        0          as db_seq,";
			 $sSqlCgm .= " 			 z01_nome   as nome,   ";
			 $sSqlCgm .= " 			 'CGM'      as tipo    ";
			 $sSqlCgm .= " 	 from cgm                  ";
       $sSqlCgm .= " 	where $sWhereCGM           ";

       $sSqlCidadaoCgm = "{$sSqlCidadao} union all {$sSqlCgm}";
       if ( isset($z01_numcgm) || isset($ov02_sequencial) ) {
	       if ( isset($z01_numcgm) && trim($z01_numcgm) != '' && ( !isset($ov02_sequencial) || trim($ov02_sequencial) == '' ) ) {
	         $sSqlCidadaoCgm = $sSqlCgm;       
	       } else if ( isset($ov02_sequencial) && trim($ov02_sequencial) != '' && ( !isset($z01_numcgm) || trim($z01_numcgm) == '' ) ) {
	         $sSqlCidadaoCgm = $sSqlCidadao;       	       
	       } else {
	         $sSqlCidadaoCgm = "{$sSqlCidadao} union all {$sSqlCgm}";
	       }
       }
       $funcao_js        = 'js_mostraDetalhes|codigo|tipo|db_seq'; 	 											   
       db_lovrot($sSqlCidadaoCgm,15,"()","",$funcao_js);
      ?>
     </td>
   </tr>
</table>
</body>
</html>
<script>

  function js_cadastrarCidadao(){
    js_OpenJanelaIframe('top.corpo','db_iframe_cadcidadao','ouv1_cadcidadaoatendimento001.php','Cadastro de Cidadão',true);
  } 
  
  function js_mostraDetalhes(iCodigo,sTipo,iSeq){
    js_OpenJanelaIframe('top.corpo','db_iframe_detalhes','ouv1_atendimentocidadaodetalhes001.php?iCodigo='+iCodigo+'&iSeq='+iSeq+'&sTipo='+sTipo,'Detalhes',true);
  }
  
</script>