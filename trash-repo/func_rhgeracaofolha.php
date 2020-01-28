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

  require_once("libs/db_stdlib.php");
  require_once("libs/db_conecta.php");
  require_once("libs/db_sessoes.php");
  require_once("libs/db_utils.php");
  require_once("libs/db_usuariosonline.php");
  require_once("dbforms/db_funcoes.php");
  require_once("classes/db_rhgeracaofolha_classe.php");
  db_postmemory($HTTP_POST_VARS);
  parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
  $oRhGeracaoFolha = new cl_rhgeracaofolha;
  $oRhGeracaoFolha->rotulo->label("rh102_sequencial");
  $oRhGeracaoFolha->rotulo->label("rh102_descricao");
  $oRhGeracaoFolha->rotulo->label("rh102_anousu");
  $oRhGeracaoFolha->rotulo->label("rh102_mesusu");
  
  $oPost = db_utils::postMemory($HTTP_POST_VARS);

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<br><br>
<center>
  <form id="form2" method="post">
  <table align="center" width="500">
    <tr>
      <td title="<?=$Trh102_sequencial;?>" width="80px">
        <b>Sequencial:</b>
      </td>
      <td>
        <?
          db_input("rh102_sequencial", 10, $Irh102_sequencial, true, "text", 1);
        ?>
      </td>
    </tr>
    <tr>
      <td title="<?=$Trh102_descricao;?>">
        <b>Descrição:</b>
      </td>
      <td>
        <?
          db_input("rh102_descricao", 50, $Irh102_descricao, true, "text", 1);
        ?>
      </td>
    </tr>
    <tr>
      <td title="<?=$Trh102_anousu." - ".$Trh102_mesusu;?>">
        <b>Ano / Mês:</b>
      </td>
      <td>
        <?
          db_input("rh102_anousu", 5, $Irh102_anousu, true, "text", 1);
          echo " / ";
          db_input("rh102_mesusu", 1, $Irh102_mesusu, true, "text", 1);
        ?>
      </td>
    </tr>
  </table>
  <p>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">&nbsp; 
    <input name="limpar" type="reset" id="limpar" value="Limpar" >&nbsp;
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_rhgeracaofolha.hide();">
  </p>
  </form>
</center>





<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td align="center" valign="top"> 
      <?
        $sCamposPadrao = "rh102_sequencial, rh102_descricao, rh102_dtproc, rh102_mesusu, rh102_anousu, rh102_instit";
        
        if ( isset($oPost->pesquisar) ) {
          
          $aArrayRepassa = array();
          $sWherePadrao  = " rh102_ativo = true ";

          if ( isset($oPost->rh102_sequencial) && trim($oPost->rh102_sequencial) != "" ) {
            
            $sSqlRhGeracaoFolha = $oRhGeracaoFolha->sql_query($oPost->rh102_sequencial, $sCamposPadrao, "rh102_sequencial", $sWherePadrao);
            $aArrayRepassa['chave_rh102_sequencial'] = $oPost->rh102_sequencial;
            
          } else if ( isset($oPost->rh102_descricao) && trim($oPost->rh102_descricao) != "" ) {
            
            $sWherePadrao .= " and rh102_descricao like '%{$oPost->rh102_descricao}%' ";
            $sSqlRhGeracaoFolha = $oRhGeracaoFolha->sql_query(null, $sCamposPadrao, "rh102_sequencial", $sWherePadrao);
                        
          } else if ( isset($oPost->rh102_anousu) && trim($oPost->rh102_anousu) != "" &&
                      isset($oPost->rh102_mesusu) && trim($oPost->rh102_mesusu) != ""  
                    ) {

            $sWherePadrao .= " and rh102_anousu = '{$oPost->rh102_anousu}' and rh102_mesusu = '{$oPost->rh102_mesusu}'";
            $sSqlRhGeracaoFolha = $oRhGeracaoFolha->sql_query(null, $sCamposPadrao, "rh102_sequencial", $sWherePadrao);
          }
        } else {
          $sSqlRhGeracaoFolha = $oRhGeracaoFolha->sql_query(null, $sCamposPadrao, "rh102_sequencial", "rh102_ativo = true");
        }

        db_lovrot($sSqlRhGeracaoFolha, 15, "()", "", $funcao_js, "", "NoMe", $aArrayRepassa);

      ?>
     </td>
   </tr>
</table>
</body>
</html>