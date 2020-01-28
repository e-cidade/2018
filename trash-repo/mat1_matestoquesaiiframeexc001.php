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
include("classes/db_matestoque_classe.php");
include("classes/db_matestoqueitem_classe.php");
include("classes/db_matestoqueini_classe.php");
include("classes/db_matestoqueinil_classe.php");
include("classes/db_matestoqueinill_classe.php");
include("classes/db_matestoqueinimei_classe.php");
include("classes/db_db_depart_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);
$clmatestoque = new cl_matestoque;
$clmatestoqueitem = new cl_matestoqueitem;
$clmatestoque2 = new cl_matestoque;
$clmatestoqueitem2 = new cl_matestoqueitem;
$clmatestoqueini = new cl_matestoqueini;
$clmatestoqueinil = new cl_matestoqueinil;
$clmatestoqueinill = new cl_matestoqueinill;
$clmatestoqueinimei = new cl_matestoqueinimei;
$cldb_depart = new cl_db_depart;
$clrotulo = new rotulocampo;
$clmatestoque->rotulo->label();
$clmatestoqueitem->rotulo->label();
$clmatestoqueini->rotulo->label();
$clmatestoqueinimei->rotulo->label();
$clrotulo->label("m60_codmater");
$clrotulo->label("m60_descr");
$clrotulo->label("descrdepto");
$clrotulo->label("m82_quant");
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
  db_inicio_transacao();
  $arr_valores = split(",",$valores);
  $sqlerro     = false;
  $codigoanterior = "";
  for($i=0;$i<count($arr_valores);$i++){
    $nomecampo    = $arr_valores[$i];
    $separacampo  = split("_",$arr_valores[$i]);
    $m80_codigo   = $separacampo[2];
    $m82_codigo   = $separacampo[1];
//    echo($clmatestoqueini->sql_query_mater(null,"m71_codlanc,m71_quant,m71_valor,(m71_valor/m71_quant) as valorunitario,m82_quant,matestoqueini.m80_codigo,m70_quant,m70_valor,m70_codigo","","m82_codigo=$m82_codigo and  matestoqueini.m80_codtipo=5 and m86_codigo is null"));
    $result_matestoque = $clmatestoqueini->sql_record($clmatestoqueini->sql_query_mater(null,"m71_codlanc,m71_quant,m71_quantatend,m71_valor,(m71_valor/m71_quant) as valorunitario,m82_quant,matestoqueini.m80_codigo,m70_quant,m70_valor,m70_codigo","","m82_codigo=$m82_codigo and  matestoqueini.m80_codtipo=5 and (b.m80_codtipo<>6 or b.m80_codigo is null) "));
    $numrows_matestoque = $clmatestoqueini->numrows;

    if($m80_codigo!=$codigoanterior){
      if($sqlerro == false){
	$clmatestoqueinil->m86_matestoqueini = $m80_codigo;
	$clmatestoqueinil->incluir(null);
	$vaipromatestoqueinill = $clmatestoqueinil->m86_codigo;
	if($clmatestoqueinil->erro_status==0){
	  $erro_msg = $clmatestoqueinil->erro_msg;
	  $sqlerro=true;
	}
      }

      if($sqlerro == false){
	$m80_codtipo  = 6;      // matestoquetipo = 6 - Saída manual cancelada
	$m80_login    = db_getsession("DB_id_usuario");
	$m80_data     = date("Y-m-d",db_getsession("DB_datausu"));
	$m80_coddepto = db_getsession("DB_coddepto");
	$m80_hora     = date('H:i:s');
      
	$clmatestoqueini->m80_login          = $m80_login;
	$clmatestoqueini->m80_data           = $m80_data;
	$clmatestoqueini->m80_hora           = $m80_hora;
	$clmatestoqueini->m80_obs            = $m80_obs;
	$clmatestoqueini->m80_codtipo        = $m80_codtipo;
	$clmatestoqueini->m80_coddepto       = $m80_coddepto;
	$clmatestoqueini->incluir(null);
	if($clmatestoqueini->erro_status==0){
	  $sqlerro=true;
	}
	$matestoqueininovo = $clmatestoqueini->m80_codigo;
	$erro_msg = $clmatestoqueini->erro_msg;
      }

      if($sqlerro==false){
	$clmatestoqueinill->m87_matestoqueini  = $matestoqueininovo;
	$clmatestoqueinill->m87_matestoqueinil = $vaipromatestoqueinill;
	$clmatestoqueinill->incluir($vaipromatestoqueinill);
	if($clmatestoqueinill->erro_status==0){
	  $erro_msg = $clmatestoqueinill->erro_msg;
	  $sqlerro=true;
	}
      }
      $codigoanterior = $m80_codigo;
    }

   
    for($ii=0;$ii<$numrows_matestoque;$ii++){
      db_fieldsmemory($result_matestoque,$ii);
      if($sqlerro == false){
	$quantsoma = $m70_quant + $m82_quant;
	$valorsoma = $m70_valor + ($m82_quant*$valorunitario);

	$clmatestoque->m70_codigo = $m70_codigo;
	$clmatestoque->m70_quant  = "$quantsoma";
	$clmatestoque->m70_valor  = "$valorsoma";
	$clmatestoque->alterar($m70_codigo);
	if($clmatestoque->erro_status==0){
	  $erro_msg = $clmatestoque->erro_msg;
	  $sqlerro=true;
	}
      }
      if(isset($m70_codigo) && trim($m70_codigo)!=""){
	$quantidadeatend = $m71_quantatend-$m82_quant;

	$clmatestoqueitem->m71_codlanc    = $m71_codlanc;
	$clmatestoqueitem->m71_quantatend = "$quantidadeatend";
	$clmatestoqueitem->alterar($m71_codlanc);
	if($clmatestoqueitem->erro_status==0){
	  $erro_msg = $clmatestoqueitem->erro_msg;
	  $sqlerro=true;
	}
	if($sqlerro == false){
	  $clmatestoqueinimei->m82_matestoqueitem = $m71_codlanc;
	  $clmatestoqueinimei->m82_matestoqueini  = $matestoqueininovo;
	  $clmatestoqueinimei->m82_quant          = $m82_quant;
	  $clmatestoqueinimei->incluir(null);
	  if($clmatestoqueinimei->erro_status==0){
	    $erro_msg = $clmatestoqueiniimei->erro_msg;
	    $sqlerro=true;
	  }
	}
      }
    }
  }
  //$sqlerro=true;
  db_fim_transacao($sqlerro);
}
if(isset($m70_codmatmater) && trim($m70_codmatmater)!=""){
  $where_deptodestino = "";
  $where_deptodestino = " and m70_coddepto=".db_getsession("DB_coddepto"); 
  $result_matestoque = $clmatestoqueini->sql_record($clmatestoqueini->sql_query_mater(null,"matestoqueini.m80_codigo,m71_codlanc,nome,descrdepto,matestoqueini.m80_data,matestoqueini.m80_obs,m82_codigo,m82_quant","matestoqueini.m80_codigo","m70_codmatmater=$m70_codmatmater $where_deptodestino and matestoqueini.m80_codtipo=5 and (b.m80_codtipo<>6 or b.m80_codigo is null) "));
  $numrows_matestoque= $clmatestoqueini->numrows;
  if($numrows_matestoque==0){
    $msgalert = "<tr>
                   <td align='center'>
                     <BR><BR><BR><BR><BR><BR><BR><BR>
                     <strong>Nenhum registro encontrado.</strong>
                   </td>
                 </tr>";
    $mostramsg = "Nenhum registro encontrado.";
  }
}else{
  $msgalert = "<tr>
                 <td align='center'>
                   <BR><BR><BR><BR><BR><BR><BR><BR>
                   <strong>Código do sequencial do lançamento não informado.</strong>
                 </td>
               </tr>";
  $mostramsg = "Código do sequencial do lançamento não informado.";
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.bordas{
         border: 1px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #cccccc;
}
.bordas01{
         border: 1px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #DEB887;
}
.bordas02{
         border: 2px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #999999;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<center>
<table width="750" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="290" align="left" valign="top" bgcolor="#CCCCCC"> 
    <form name="form1" method="post" action="">
    <center>
    <table border='0'>
      <?
      if(isset($msgalert) && trim($msgalert)!=""){
	echo $msgalert;
        $scriptextra = "<script>parent.document.getElementById('db_opcao').disabled=true;</script>";
      }else{
	echo "
	<tr>
	  <td nowrap class='bordas' align='right' colspan='1'><strong>Obs.:</strong></td>
	  <td nowrap class='bordas' align='left'  colspan='6'>
	  ";
          db_textarea('m80_obs',2,109,$Im80_obs,true,'text',1,"");
	  echo "
	  </td>
	</tr>
	";
	echo "
	<tr>
	  <td nowrap class='bordas02' align='center'><strong>M</strong></td>
	  <td nowrap class='bordas02' align='center'><strong>Item</strong></td>
	  <td class='bordas02' align='center'><strong>Departamento</strong></td>
	  <td class='bordas02' align='center'><strong>Usuário</strong></td>
	  <td nowrap class='bordas02' align='center'><strong>Data</strong></td>
	  <td nowrap class='bordas02' align='center'><strong>Saída</strong></td>
	</tr>
	";
	for($i=0;$i<$numrows_matestoque;$i++){
	  db_fieldsmemory($result_matestoque,$i);
	  $matitem = "cod_".$m82_codigo."_$m80_codigo";
	  echo "
	  <tr>
	    <td nowrap class='bordas' align='center'><input type='checkbox' name='$matitem' value='$matitem' checked></td>
	    <td nowrap class='bordas' align='center'>$m82_codigo</td>
	    <td class='bordas' align='left'>$descrdepto</td>
	    <td class='bordas' align='left'>$nome</td>
	    <td nowrap class='bordas' align='right'>".db_formatar($m80_data,"d")."</td>
	    <td nowrap class='bordas' align='center'>
	  ";
	  $matitem = "TXT_".$matitem;
          $$matitem=$m82_quant;
	  db_input($matitem,10,$Im82_quant,true,"text",3);
	  echo "
	    </td>
	  </tr>
	  ";	  
	}
      }
      db_input('valores',40,0,true,"hidden",3);
      db_input('m70_codmatmater',40,0,true,"hidden",3);
      ?>
    </table>
    </center>
    </form>
    </td>
  </tr>
</table>
</center>
</body>
</html>
<script>
</script>
<?
if(isset($incluir)){
  db_msgbox($erro_msg);
}
if(isset($scriptextra) && trim($scriptextra)!=""){
  echo $scriptextra;
  db_msgbox($mostramsg);
  echo "<script>parent.location.href='mat1_matestoquesai003.php';</script>";
}else{
  echo "<script>parent.document.getElementById('db_opcao').disabled=false;</script>";
}
?>