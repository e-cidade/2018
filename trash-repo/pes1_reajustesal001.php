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
include("classes/db_rhpessoal_classe.php");
include("classes/db_rhpessoalmov_classe.php");
include("dbforms/db_classesgenericas.php");
$clrhpessoal = new cl_rhpessoal;
$clrhpessoalmov = new cl_rhpessoalmov;
$aux = new cl_arquivo_auxiliar;
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
$clrotulo->label('DBtxt27');
$clrotulo->label('DBtxt28');
db_postmemory($HTTP_POST_VARS);

if (isset($incluir) ) {
  
  $sqlerro = false;
  $dbwhere = " rh02_anousu = ".$anofolha." and rh02_mesusu = ".$mesfolha." and rh02_salari > 0 ";
  if(isset($matini) || isset($matfim)){
    if(trim($matini) != "" && trim($matfim) != ""){
      $dbwhere.= " and rh01_regist between ".$matini." and ".$matfim; 
    }else if(trim($matini) != ""){
      $dbwhere.= " and rh01_regist >= ".$matini; 
    }else if(trim($matfim) != ""){
      $dbwhere.= " and rh01_regist <= ".$matfim; 
    }
  }
  
  if (isset($selmatri) && count($selmatri) > 0){
    $sMatric = implode(",",$selmatri); 
    $dbwhere.= " and rh01_regist in ({$sMatric}) ";
  }
  
  if(isset($lotini) || isset($lotfim)){
    if(trim($lotini) != "" && trim($lotfim) != ""){
      $dbwhere.= " and r70_estrut between '".$lotini."' and '".$lotfim."' "; 
    }else if(trim($lotini) != ""){
      $dbwhere.= " and r70_estrut >= '".$lotini."' ";
    }else if(trim($lotfim) != ""){
      $dbwhere.= " and r70_estrut <= '".$lotfim."' ";
    }
  }
  
  if(isset($sellotac) && count($sellotac) > 0){
    $campo_auxilio_lota = "";
    for($i=0; $i<count($sellotac); $i++){
      $campo_auxilio_lota.= ($i==0?"":",")."'".$sellotac[$i]."'";
    }
    if(isset($campo_auxilio_lota) && trim($campo_auxilio_lota) != ""){
      $dbwhere.= " and r70_estrut in (".$campo_auxilio_lota.") ";
    }
  }
  
  
  if(isset($carini) || isset($carfim)){
    if(trim($carini) != "" && trim($carfim) != ""){
      $dbwhere.= " and rh02_funcao between {$carini} and {$carfim}"; 
    }else if(trim($carini) != ""){
      $dbwhere.= " and rh02_funcao >= {$carini}";
    }else if(trim($carfim) != ""){
      $dbwhere.= " and rh02_funcao <= {$carfim}";
    }
  }
  
  if (isset($selcargo) && count($selcargo) > 0) {
    $sCargos = implode(",",$selcargo);
    $dbwhere.= " and rh02_funcao in (".$sCargos.") ";
  }
  
  $result_rhpessoal = $clrhpessoal->sql_record($clrhpessoal->sql_query_cgmmov(null,"rh02_salari,rh02_seqpes as seqpes","",$dbwhere));
  if ($clrhpessoal->numrows == 0) {

    $erro_msg = "[ 1 ] - Nenhum registro encontrado.";
    $sqlerro = true;
    
  } else {
    
    db_inicio_transacao();
    for ($i=0;$i<$clrhpessoal->numrows;$i++) {
      
      db_fieldsmemory($result_rhpessoal,$i);
      
      $valors = ($rh02_salari + ($rh02_salari * ($perce / 100)));
      
      $clrhpessoalmov->rh02_seqpes = $seqpes;
      $clrhpessoalmov->rh02_salari = round($valors,2);
      $clrhpessoalmov->alterar($seqpes);
      if($clrhpessoalmov->erro_status==0){
        $erro_msg = "[ 2 ] - ".$clrhpessoalmov->erro_msg; 
        $sqlerro=true;
        break;
      }
      
    }

    db_fim_transacao($sqlerro);
        
    if ($sqlerro == false) {
      $erro_msg = "[ 3 ] - ".$clrhpessoal->numrows." registros alterados com sucesso. \\nVerifique salário informado nas movimentações dos funcionários.";
    }

  }
  
  db_msgbox($erro_msg);
  
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
  <tr>
    <td>
      <?
      include("forms/db_frmreajustesal.php");
      ?>
    </td>
  </tr>
</table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
js_setfocus(true);
</script>