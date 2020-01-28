<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
include("classes/db_agualeitura_classe.php");
include("classes/db_agualeituracancela_classe.php");
include("dbforms/db_funcoes.php");
require_once ("libs/db_app.utils.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($_POST);
$clagualeitura            = new cl_agualeitura;
$clagualeituracancela     = new cl_agualeituracancela;

$db_botao = false;
$db_opcao = 33;

if(isset($cancelar)){
  
  db_inicio_transacao();
  $db_opcao = 3;
  
  $clagualeitura->x21_status = 3; // 3 = cancelado
  $clagualeitura->alterar($x21_codleitura);
  
  
  $clagualeituracancela->x47_agualeitura = $x21_codleitura;
  $clagualeituracancela->x47_usuario     = db_getsession('DB_id_usuario');
  $clagualeituracancela->x47_data        = date("d/m/Y");
  $clagualeituracancela->x47_hora        = date("H:i"); 

  $clagualeituracancela->incluir($x47_sequencial);
  
  db_fim_transacao();
  
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   
   $campos   = "x21_codleitura, x21_codhidrometro, x21_exerc, x21_mes, x04_matric, x01_numcgm, x01_codrua, x01_numero, x01_letra, x01_zona, x01_qtdeconomia, ";
   $campos  .= "case when x01_multiplicador = 'f' then 'Não' else 'Sim' end as x01_multiplicador,"; 
   $campos  .= "x04_nrohidro, x04_qtddigito, x03_nomemarca, x15_diametro, x21_situacao, ";
   $campos  .= "x17_descr, x21_numcgm, cgm.z01_nome, x21_dtleitura, x21_leitura, x21_consumo, x21_excesso, a.z01_nome as z01_nomedad, j14_nome, x21_tipo, x21_status ";
    
   $result = $clagualeitura->sql_record($clagualeitura->sql_query_dados($chavepesquisa, $campos)); 
   db_fieldsmemory($result,0);

   $campos2  = "x21_situacao as x21_situacant,x17_descr as x17_descrant,x21_numcgm as x21_numcgmant,z01_nome as z01_nomeant, ";
   $campos2 .= "x21_dtleitura as x21_dtleituraant,x21_leitura as x21_leituraant,x21_consumo as x21_consumoant,x21_excesso as x21_excessoant";
   $orderBy  = "x21_codleitura desc limit 1";
   $sWhere   = "x21_codleitura < $chavepesquisa and x21_codhidrometro=$x21_codhidrometro ";
   
   $result_leituraant = $clagualeitura->sql_record($clagualeitura->sql_query_sitecgm(null,$campos2, $orderBy, $sWhere));
   
   if($clagualeitura->numrows > 0){
     db_fieldsmemory($result_leituraant,0);
   }

   $sql_leituraant = $clagualeitura->sql_query_file(null,
                                                    "*",
                                                    "x21_exerc desc, x21_mes desc, x21_codleitura desc limit 1",
                                                    "x21_codhidrometro=$x21_codhidrometro
   and cast(x21_exerc::varchar||'-'||x21_mes::varchar||'-01' as date) 
     > cast('{$x21_exerc}-{$x21_mes}-01' as date)");
                                                     
   //die("<br><br> $sql_leituraant ");
                                                     
   $result_leituraant = $clagualeitura->sql_record($sql_leituraant);
   /*if($clagualeitura->numrows > 0){
     $nexclui = true;
   }

   $db_botao = true;*/
   $sSqlAguaLeituraCancela  = $clagualeituracancela->sql_query_file(null, "x47_motivo", "x47_data, x47_hora DESC limit 1", "x47_agualeitura = $x21_codleitura");
   
   $rsSqlAquaLeituraCancela = $clagualeituracancela->sql_record($sSqlAguaLeituraCancela);
   
   if($clagualeituracancela->numrows > 0) {
     db_fieldsmemory($rsSqlAquaLeituraCancela, 0);
   }
   
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" height="18"  border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
      <center>
      <?
      include("forms/db_frmagualeituracancela.php");
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
if($cancelar) {
  if($clagualeitura->erro_status=="0"){
    $clagualeitura->erro(true,false);
  }else{
    $clagualeitura->erro(true,true);
  }
  
}

if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}

?>