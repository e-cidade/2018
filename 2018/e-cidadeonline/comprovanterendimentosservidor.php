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

validaUsuarioLogado();

$aRetorno = array();

parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]),$aRetorno);

$iMatric  = $aRetorno['iMatric'];
$iInstit  = $aRetorno['iInstit'];

$sSqlCalculoAnos = " select distinct rh02_anousu,rh02_anousu 
                       from rhpessoalmov 
                      where rh02_regist  = {$iMatric}
                        and rh02_instit  = {$iInstit}
                        and rh02_anousu != fc_anofolha({$iInstit})
                      order by rh02_anousu desc";  
                        
$rsCalculoAnos   = db_query($sSqlCalculoAnos);
$iNroCalculoAnos = pg_num_rows($rsCalculoAnos);                        


?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="config/estilos.css"        rel="stylesheet" type="text/css">
<link href="config/portalservidor.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="scripts/scripts.js"></script>
<script language="JavaScript" src="scripts/db_script.js"></script>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" onLoad="" <?mens_OnHelp()?>>

  <form name="form1" method="post" action="pes2_cedulac002.php">
          <?
           
         if ( $iNroCalculoAnos > 0 ) {
          
          ?>
          <table  class="tableForm" width="250px;">
            <tr>
              <td class="tituloForm" colspan="2" nowrap>
                 Comprovante de Rendimentos
              </td>
            </tr>
            <tr>
              <td class="labelForm">
                Ano Base:
              </td>
              <td class="dadosForm">
                <?
                   db_selectrecord('anobase',$rsCalculoAnos,true,1,'','','','','',1);
                   db_input('iMatric',10,'',true,'hidden',1,'');
                   db_input('iInstit',10,'',true,'hidden',1,'');
                ?>
              </td>
            </tr>
            <tr align="center">
              <td colspan="2">
                <input type="button" name="emitir" id="emitir" value="Emitir" onClick='js_emitir()'>
              </td>
            </tr>
          </table>
          <?
              
            } else {
          ?>      
          <table  class="tableForm" align="center">
            <tr>
              <td class="labelForm">      
                <b>Nenhum Registro Encontrado</b>
              </td>
            </tr>
          </table>          
        
          <?
            }
          ?>  
  
  </form>
</body>
<script>
   
  function js_emitir(){
    
    jan = window.open('','iFrameComprovante','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
    
    document.form1.target = 'iFrameComprovante';
    document.form1.submit();
    
  } 
   
</script>