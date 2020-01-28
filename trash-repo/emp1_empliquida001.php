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

include("libs/db_liborcamento.php");
include("classes/db_orcdotacao_classe.php");
include("classes/db_empempenho_classe.php");
include("classes/db_empelemento_classe.php");
include("classes/db_pagordemele_classe.php");
$clpagordemele = new cl_pagordemele;
$clempempenho = new cl_empempenho;
$clempelemento= new cl_empelemento;
$clorcdotacao = new cl_orcdotacao;

include("classes/db_empnota_classe.php");
include("classes/db_empnotaele_classe.php");

$clempnota = new cl_empnota;
$clempnotaele = new cl_empnotaele;



include("classes/db_conplanoreduz_classe.php");
$clconplanoreduz  = new cl_conplanoreduz;

include("libs/db_libcontabilidade.php");
$cltranslan       = new cl_translan;


include("classes/db_conlancam_classe.php");
include("classes/db_conlancamele_classe.php");
include("classes/db_conlancamlr_classe.php");
include("classes/db_conlancamcgm_classe.php");
include("classes/db_conlancamemp_classe.php");
include("classes/db_conlancamval_classe.php");
include("classes/db_conlancamdot_classe.php");
include("classes/db_conlancamdoc_classe.php");
include("classes/db_conlancamnota_classe.php");
include ("classes/db_empempenhonl_classe.php");


$clconlancam	  = new cl_conlancam;
$clconlancamele	  = new cl_conlancamele;
$clconlancamlr	  = new cl_conlancamlr;
$clconlancamcgm	  = new cl_conlancamcgm;
$clconlancamemp	  = new cl_conlancamemp;
$clconlancamval	  = new cl_conlancamval;
$clconlancamdot	  = new cl_conlancamdot;
$clconlancamdoc	  = new cl_conlancamdoc;
$clconlancamnota  = new cl_conlancamnota;
$oDaoEmpenhoNl    = new cl_empempenhonl;
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
  $db_opcao = 22;
  $db_botao = false;

if(isset($confirmar)){
   db_inicio_transacao();
   $sqlerro=false;
   
   $sql = "update empparametro set e39_anousu = e39_anousu where e39_anousu = ".db_getsession("DB_anousu");
   $res = pg_query($sql);

   //$arr_dados é um array com todos os elementos e seus valores
   //$dados =   $elemento-$valorliquidar#$elemento-$valorliquidar#elemen...
   //$dados = 'dd';
   //$e60_numemp =  $e60_numemp;
   //$vlrliq     =  $vlrliq;
   include("emp1_empliquidaarq.php");
  db_fim_transacao($sqlerro);
}
if(isset($e60_numemp)){
  $db_opcao = 2;
  $db_botao = true;
   //rotina que traz os dados de empempenho
   $result = $clempempenho->sql_record($clempempenho->sql_query($e60_numemp)); 
   db_fieldsmemory($result,0,true);
   $rsNotaLiquidacao  = $oDaoEmpenhoNl->sql_record(
                        $oDaoEmpenhoNl->sql_query_file(null,"e68_numemp","","e68_numemp = {$e60_numemp}"));  
   if ($oDaoEmpenhoNl->numrows > 0) {
         
      echo "<script>location.href='emp4_liquida001.php?numemp={$e60_numemp}';</script>"; 
   }  
}  
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js">
    </script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="<?=$db_opcao==22?"document.form1.pesquisar.click();":"document.form1.vlrliq.select();"?>
">
    <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
      <tr>
        <td width="360" height="18">
          &nbsp;
        </td>
        <td width="263">
          &nbsp;
        </td>
        <td width="25">
          &nbsp;
        </td>
        <td width="140">
          &nbsp;
        </td>
      </tr>
    </table>
    <table width="790" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
          <center>
            <?
	include("forms/db_frmempliquida.php");
	?>
          </center>
        </td>
      </tr>
    </table>
    <?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
  </body>
</html>
<?
if(isset($confirmar)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
  }else{
    echo "<script>
          retorno = confirm('$ok_msg\\nGera Ordem de Pagamento?');
          if(retorno==true){
            location.href = 'emp1_pagordem001.php?emite_automatico=$e60_numemp';
	  }else{
            location.href = 'emp1_empliquida001.php?novo=true&e60_numemp=$e60_numemp';
	  }
          </script>";
  }  
}
if($db_opcao==22){
//  echo "<script>document.form1.pesquisar.click();</script>";
}
?>