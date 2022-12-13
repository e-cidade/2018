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

require_once("libs/db_stdlib.php");
require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");

$oRotulo = new rotulocampo;
$oRotulo->label("vc06_c_descr");
$oRotulo->label("vc07_i_vacina");

$clcriaabas = new cl_criaabas;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#CCCCCC">
<form name="formaba">
<table marginwidth="0" width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
      <br>
    </td>
  </tr>
  <tr>
    <td>
      <table>
        <tr>
          <td nowrap>
            <?=$Lvc07_i_vacina?>
          </td>
          <td> 
            <?
            db_input('vc07_i_vacina',10,$Ivc07_i_vacina,true,'text',3,"");
            db_input('vc06_c_descr',30,$Ivc06_c_descr,true,'text',3,"")
            ?>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
      <?
      $clcriaabas->identifica    = array("a1"=>"Doses da Vacina",
                                         "a2"=>"Limites",
                                         "a3"=>"Restrições",
                                         "a4"=>"Dependência");
      $sVacina                   = "vc07_i_vacina=$vc07_i_vacina&vc06_c_descr=$vc06_c_descr";
      $clcriaabas->src           = array("a1"=>"vac1_vac_vacinadose004.php?$sVacina","a2"=>"","a3"=>"","a4"=>"");
      $clcriaabas->sizecampo     = array("a1"=>20,"a2"=>20,"a3"=>20,"a4"=>20);
      $clcriaabas->disabled      = array("a1"=>"false","a2"=>"true","a3"=>"true","a4"=>"true");
      $clcriaabas->scrolling     = "no";
      $clcriaabas->iframe_height = "600";
      $clcriaabas->iframe_width  = "100%";
      $clcriaabas->abas_top      = "70";
      $clcriaabas->cria_abas();
      ?>
    </td>
  </tr>
</table>
</form>
</body>
</html>