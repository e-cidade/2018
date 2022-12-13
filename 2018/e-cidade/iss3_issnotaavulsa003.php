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
require("libs/db_utils.php");
include("dbforms/db_funcoes.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_issnotaavulsa_classe.php");
include("classes/db_parissqn_classe.php");
include("classes/db_issnotaavulsaservico_classe.php");
$clissnotaavulsa = new cl_Issnotaavulsa();
$get             = db_utils::postmemory($_GET);
$rsNota          = $clissnotaavulsa->sql_record($clissnotaavulsa->sql_query_emitidos($get->q51_sequencial));
$oNota           = db_utils::fieldsMemory($rsNota,0);
$db_opcao        = 1;
$reemite         = '';
if ($oNota->q69_sequencial == null){

   $reemite = " disabled ";

}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.texto {background-color:white}
.selecionados  {background-color:white;
               text-decoration:none;
               border-right:2px outset #2C7AFE;
               border-bottom:1px outset white;
               display:block;
               padding:3px;
               text-align:center;
               color:black
              }
.dados{ display:block;
        background-color:#CCCCCC;
        text-decoration:none;
        border-right:3px outset #A6A6A6;
        border-bottom:3px outset #EFEFEF;
        color:black;
        text-align:center;
        padding:3px;
      }  
</style>
<script>
function js_marca(obj){

   lista = document.getElementsByTagName("A");
   for (i = 0;i < lista.length;i++){
     if (lista[i].className == 'selecionados');

      lista[i].className = 'dados';
   }
   obj.className = 'selecionados';

}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" id='teste'>
<center>
<table width='100%' cellspacing=0>
<tr>
<td colspan='2'>
<fieldset><legend><b>Dados da Nota - Prestador</b></legend>
<table>
<tr>
    <td><b>Código:</b></td>
    <td class='texto'><?=$oNota->q51_sequencial?></td>
    <td><b>Número da Nota:</b></td>
    <td class='texto'><?=$oNota->q51_numnota?></td>
</tr>
<tr>
    <td><b>Inscrição Municipal</b></td>
    <td class='texto'><?=$oNota->q51_inscr?></td>
    <td><b>CGC/CPF:</b></td>
    <td class='texto'><?=$oNota->z01_cgccpf?></td>
</tr>
<tr>
    <td><b>Nome/Razão Social:</b></td>
    <td colspan="3" class='texto'><?=$oNota->z01_nome?></td>
</tr>    
</tr>
    <td><b>Endereço/Nº:</b></td>
    <td colspan="3" class='texto'><?=$oNota->z01_ender?></td>
    <td class='texto'><?=$oNota->z01_numero?></td>
</tr>    
</table>
</fieldset>
</td></tr>
<tr><td width='20%' valign='top' height='100%' rowspan='2'>
  <a class='selecionados' onclick='js_marca(this);this.blur()' href='iss3_issnotaavusatomador.php?q51_sequencial=<?=$get->q51_sequencial;?>' target='dados'><b>Tomador</b></a>
  <a class='dados' onclick='js_marca(this);this.blur()' href='iss3_issnotaavusaservicosprestados.php?q51_sequencial=<?=$get->q51_sequencial;?>' target='dados'><b>Serviços Prestados</b></a>
  <?
   if ($oNota->q52_numpre != null){
    echo "<a class='dados' onclick='js_marca(this);this.blur()'
             href='iss3_issnotaavulsasituacao.php?q51_sequencial=".$get->q51_sequencial."'
             target='dados'><b>Dados Pagamento</b></a>";
   }

  if ($oNota->q63_issnotaavulsa != null){

     echo "<a class='dados' onclick='js_marca(this);this.blur()' href='iss3_issnotaavulsacancelado.php?q51_sequencial=".$get->q51_sequencial."' target='dados'>";
     echo "<b>Dados Cancelamento</b></a>";

  }
?>
 
</td><td valign='top' height='100%' style='border:1px inset white'>
<iframe height='300' name='dados' frameborder='0' width='100%' src='iss3_issnotaavusatomador.php?q51_sequencial=<?=$get->q51_sequencial;?>'
 style='background-color:#CCCCCC'></iframe>
</td><td>
</table>
<center>
  <input type='button' value='reemitir nota' onclick='js_emiteNota(<?=$_GET["q51_sequencial"];?>)' <?=$reemite;?>>
  <input type='button' value='Voltar'  onclick='parent.db_iframe_pesquisanota.hide()'>
</center>
</body>
</html>
<script>
function js_emiteNota(num){

   url = "iss2_issnotaavulsanotafiscal002.php?q51_sequencial="+num;
   window.open(url,'','location=0');

}
</script>