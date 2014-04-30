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
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
  db_inicio_transacao();
  $arr_valores = split(",",$valores);
  $sqlerro     = false;
  for($i=0;$i<count($arr_valores);$i++){
    $nomecampo    = $arr_valores[$i];
    $separacampo  = split("_",$arr_valores[$i]);
    $m70_codigo   = $separacampo[1];
    $m70_coddepto = $separacampo[2];
    $m80_codtipo  = 5;      // matestoquetipo = 4 - Saída manual
    
    $m80_login    = db_getsession("DB_id_usuario");
    $m80_data     = date("Y-m-d",db_getsession("DB_datausu"));
    $m80_coddepto = db_getsession("DB_coddepto");
    $m80_hora     = date('H:i:s');
    
    $result_matestoque  = $clmatestoqueitem->sql_record($clmatestoqueitem->sql_query(null,"distinct m70_codigo,m70_codmatmater,m70_quant,m70_valor,m70_coddepto,m71_codlanc,m71_valor,m71_quant,m71_quantatend,m71_data as dataimprime,m71_valor as valorimprime","m71_data desc","m70_codigo=$m70_codigo and m70_coddepto=$m70_coddepto and m71_quant>m71_quantatend"));
    $numrows_matestoque = $clmatestoqueitem->numrows;
    
    $arr_oqueaindafalta = $$nomecampo;
    $arr_estoquediminui = Array();
    $arr_vlorquediminui = Array();
    if($sqlerro == false && trim($m82_matestoqueini)==""){
      $clmatestoqueini->m80_login          = $m80_login;
      $clmatestoqueini->m80_data           = $m80_data;
      $clmatestoqueini->m80_hora           = $m80_hora;
      $clmatestoqueini->m80_obs            = $m80_obs;
      $clmatestoqueini->m80_codtipo        = $m80_codtipo;
      $clmatestoqueini->m80_coddepto       = $m80_coddepto;
      $clmatestoqueini->incluir(@$m80_codigo);
      if($clmatestoqueini->erro_status==0){
        $sqlerro=true;
      }
      $m82_matestoqueini = $clmatestoqueini->m80_codigo;
      $erro_msg = $clmatestoqueini->erro_msg;
    }
    for($ii=0;$ii<$numrows_matestoque;$ii++){
      db_fieldsmemory($result_matestoque,$ii);
      if(!isset($arr_estoquediminui[$m70_codigo])){
        $arr_estoquediminui[$m70_codigo] = $m70_quant;
        $arr_vlorquediminui[$m70_codigo] = $m70_valor;
      }
      $naoatendida   = $m71_quant - $m71_quantatend;
      $valorunitario = $m71_valor / $m71_quant;
      if($sqlerro == false && $naoatendida>0 && $arr_oqueaindafalta>0){
        if($arr_oqueaindafalta>=$naoatendida){
          $clmatestoqueitem->m71_quantatend = $naoatendida + $m71_quantatend;
          $retirar = $naoatendida; 
          $arr_oqueaindafalta -= $naoatendida;
        }else{
          $clmatestoqueitem->m71_quantatend = $m71_quantatend + $arr_oqueaindafalta;
          $retirar = $arr_oqueaindafalta; 
          $arr_oqueaindafalta -= $arr_oqueaindafalta;
        }
        
        if($sqlerro == false){
          $clmatestoque->m70_codigo = $m70_codigo;
          $quantretira = $arr_estoquediminui[$m70_codigo] - $retirar;
          $valorretira = 0 + db_formatar(($arr_vlorquediminui[$m70_codigo]-($retirar*$valorunitario)),'p',' ',0,'e',4);
          
          if ($valorretira < 0){
            $valorretira *= -1;
          }
          if ($quantretira == 0){
            $valorretira = 0;
          }
          
          $clmatestoque->m70_quant  = "$quantretira";
          $clmatestoque->m70_valor  = "$valorretira";
          $arr_estoquediminui[$m70_codigo] -= $retirar;
          $arr_vlorquediminui[$m70_codigo] -= ($retirar * $valorunitario);
          $clmatestoque->alterar($m70_codigo);
          if($clmatestoque->erro_status==0){
            $erro_msg = $clmatestoque->erro_msg;
            $sqlerro=true;
          }
        }
        if(isset($m70_codigo) && trim($m70_codigo)!=""){
          $clmatestoqueitem->m71_codlanc       = $m71_codlanc;
          $clmatestoqueitem->m71_codmatestoque = $m70_codigo;
          $clmatestoqueitem->m71_quant         = $m71_quant;
          $clmatestoqueitem->alterar($m71_codlanc);
          if($clmatestoqueitem->erro_status==0){
            $sqlerro=true;
            $erro_msg = $clmatestoqueitem->erro_msg;
          }
          $m80_matestoqueitem = $clmatestoqueitem->m71_codlanc;
        }
        if($sqlerro == false){
          $clmatestoqueinimei->m82_matestoqueitem = $m71_codlanc;
          $clmatestoqueinimei->m82_matestoqueini  = $m82_matestoqueini;
          $clmatestoqueinimei->m82_quant          = $retirar;
          $clmatestoqueinimei->incluir(@$m82_codigo);
          if($clmatestoqueinimei->erro_status==0){
            $erro_msg = $clmatestoqueinimei->erro_msg;
            $sqlerro=true;
          }
        }
      }else{
        break;
      }
    }
  }
  //  $sqlerro=true;
  db_fim_transacao($sqlerro);
}
if(isset($m70_codmatmater) && trim($m70_codmatmater)!=""){
  $where_deptodestino = "";
  $where_deptodestino = " and m70_coddepto=".db_getsession("DB_coddepto");
  $result_matestoque = $clmatestoqueini->sql_record($clmatestoqueini->sql_query_mater(null,"distinct m70_codigo,m60_codmater,m60_descr,coddepto,descrdepto,m70_quant","m70_codigo,descrdepto","m70_codmatmater=$m70_codmatmater $where_deptodestino and m71_quant>m71_quantatend and m86_codigo is null"));
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
  db_textarea('m80_obs',2,109,$Im80_obs,true,'text',$db_opcao,"");
  echo "
  </td>
  </tr>
  ";
  echo "
  <tr>
  <td nowrap class='bordas02' align='center'><strong>Estoque</strong></td>
  <td nowrap class='bordas02' align='center'><strong>Material</strong></td>
  <td nowrap class='bordas02' align='center'><strong>Descrição</strong></td>
  <td nowrap class='bordas02' align='center'><strong>Departamento</strong></td>
  <td nowrap class='bordas02' align='center'><strong>Qtd. lançada</strong></td>
  <td nowrap class='bordas02' align='center'><strong>Qtd. disponível</strong></td>
  <td nowrap class='bordas02' align='center'><strong>Saída</strong></td>
  </tr>
  ";
  for($i=0;$i<$numrows_matestoque;$i++){
    db_fieldsmemory($result_matestoque,$i);
    $result_sumquant = $clmatestoqueitem->sql_record($clmatestoqueitem->sql_query_file(null,"sum(m71_quant) as somaquant","","m71_codmatestoque=$m70_codigo and m71_quant>m71_quantatend"));
    db_fieldsmemory($result_sumquant,0);
    echo "
    <tr>
    <td nowrap class='bordas' align='center'>$m70_codigo</td>
    <td nowrap class='bordas' align='center'>$m60_codmater</td>
    <td class='bordas'>$m60_descr</td>
    <td class='bordas'>$descrdepto</td>
    <td nowrap class='bordas' align='right'>$somaquant</td>
    <td nowrap class='bordas' align='right'>$m70_quant</td>
    <td nowrap class='bordas' align='center'>
    ";
    $matitem = "cod_".$m70_codigo."_".$coddepto."_".$i;
    $$matitem=$m70_quant;
    if($i==0){
      $rcbfoco = "cod_".$m70_codigo."_".$coddepto."_".$i;
    }
    $valor = 1;
    if(($i+1)==$numrows_matestoque){
      $valor = 0;
    } 
    db_input($matitem,10,$Im70_quant,true,"text",4,"onchange='js_proximocampo(this.name,$valor,$m70_quant);'");
    echo "
    </td>
    </tr>
    ";	  
  }
}
db_input('m82_matestoqueini',40,0,true,"hidden",3);
db_input('valores',40,0,true,"hidden",3);
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
function js_verificadepart(valor){
  x = document.form1;
  for(i=0;i<x.length;i++){
  }
}
function js_proximocampo(nome,valor,quant){
  for(i=0;i<document.form1.length;i++){
    if(document.form1.elements[i].type=='text'){
      if(document.form1.elements[i].name==nome){
        if(document.form1.elements[i].value!=''){
          quantTXT = new Number(document.form1.elements[i].value);
          quant    = new Number(quant);
          if(quantTXT>quant || quantTXT<0){
            if(quantTXT>quant){
              alert("Quantidade informada deve ser inferior ou igual à quantidade disponível.");
            }else{
              alert("Quantidade inválida.");
            }
            document.form1.elements[i].value = "";
            document.form1.elements[i].focus() = "";
          }else{
            if(valor==1){
              document.form1.elements[i+1].select();
            }
          }
        }
      }
    }
  }
}
</script>
<?
if(isset($incluir)){
  db_msgbox($erro_msg);
}
if(isset($scriptextra) && trim($scriptextra)!=""){
  echo $scriptextra;
  db_msgbox($mostramsg);
  echo "<script>parent.location.href='mat1_matestoquesai001.php';</script>";
}
if(isset($rcbfoco)){
  echo "<script>document.form1.$rcbfoco.select();</script>";
}
?>