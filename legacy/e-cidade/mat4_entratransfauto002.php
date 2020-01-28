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
include("libs/JSON.php");
include("libs/db_usuariosonline.php");
include("classes/db_matestoqueinimei_classe.php");
include("classes/db_matestoqueinimeiari_classe.php");
include("classes/db_db_depusu_classe.php");
include("classes/db_db_usuarios_classe.php");
include("classes/db_matrequi_classe.php");
include("classes/db_matrequiitem_classe.php");
include("classes/db_atendrequi_classe.php");
include("classes/db_atendrequiitem_classe.php");
include("classes/db_atendrequiitemmei_classe.php");
include("classes/db_matestoqueitem_classe.php");
include("classes/db_matestoqueini_classe.php");
include("classes/db_matestoqueinil_classe.php");
include("classes/db_matestoqueinill_classe.php");
include("classes/db_matestoque_classe.php");
include("classes/db_matestoquetransf_classe.php");
include("classes/db_matparam_classe.php");
include("classes/db_db_almoxdepto_classe.php");
include("classes/db_db_departorg_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clmatestoqueinimei = new cl_matestoqueinimei;
$clmatestoqueinimeiari = new cl_matestoqueinimeiari;
$cldb_almoxdepto = new cl_db_almoxdepto;
$clmatestoqueini = new cl_matestoqueini;

$clmatestoqueinil = new cl_matestoqueinil;
$clmatestoqueinill = new cl_matestoqueinill;
$clmatestoque = new cl_matestoque;
$clmatestoquetransf = new cl_matestoquetransf;
$cldb_depusu        = new cl_db_depusu;
$cldb_usuarios      = new cl_db_usuarios;
$clrotulo           = new rotulocampo;
$objJSON            = new Services_JSON();
$clmatrequi = new cl_matrequi;
$clmatrequiitem = new cl_matrequiitem;
$clatendrequi = new cl_atendrequi;
$clatendrequiitem = new cl_atendrequiitem;
$clatendrequiitemmei = new cl_atendrequiitemmei;
$clmatestoqueitem = new cl_matestoqueitem;

$clrotulo->label("m41_codmatmater");
$clrotulo->label("m60_descr");
$clrotulo->label("m40_login");

if (isset($confirma)) {
  $sqlerro=false;
  $dados    = split("quant_","$valor");
  $arr_info = array();
  db_inicio_transacao();
  $valores = "";
  for ($w=1; $w<count($dados); $w++) {
    if ($dados[$w]=="") {
      continue;
    }
    $info = split("_",$dados[$w]);
    $codlanc = $info[0];
    $codmater = $info[1];
    $pos = $info[2];
    $quant = $info[3];
    $depto_inf = "depto_".$pos;
    $depto = $$depto_inf;
    
    $m80_codtipo = 7;
    // Em transferência
    
    $m80_login = db_getsession("DB_id_usuario");
    $m80_data = date("Y-m-d", db_getsession("DB_datausu"));
    $m80_coddepto = db_getsession("DB_coddepto");
    $m80_hora = date('H:i:s');
    
    $result_matestoque = $clmatestoqueitem->sql_record($clmatestoqueitem->sql_query(null, "distinct m70_codigo,m70_codmatmater,m70_quant,m70_valor,m70_coddepto,m71_codlanc,m71_valor,m71_quant,m71_quantatend,m71_data as dataimprime,m71_valor as valorimprime", "m71_data desc", "m71_codlanc=$codlanc  "));
    $numrows_matestoque = $clmatestoqueitem->numrows;
    
    if ($sqlerro == false &&  $numrows_matestoque > 0) {
      $clmatestoqueini->m80_codigo = null;
      $clmatestoqueini->m80_login = $m80_login;
      $clmatestoqueini->m80_data = $m80_data;
      $clmatestoqueini->m80_hora = $m80_hora;
      $clmatestoqueini->m80_obs = "Entrada da ordem de compra com tranferecia automatica.";
      $clmatestoqueini->m80_codtipo = $m80_codtipo;
      $clmatestoqueini->m80_coddepto = $m80_coddepto;
      $clmatestoqueini->incluir(null);
      $m82_matestoqueini = $clmatestoqueini->m80_codigo;
      $codmatestoqueini = $clmatestoqueini->m80_codigo;
      $valores = $m82_matestoqueini;
      if ($clmatestoqueini->erro_status == 0) {
        $erro_msg = $clmatestoqueini->erro_msg;
        $sqlerro = true;
      } else {
      }
      
      if ($sqlerro == false) {
        $clmatestoquetransf->m83_coddepto = $depto;
        $clmatestoquetransf->incluir(@ $m82_matestoqueini);
        if ($clmatestoquetransf->erro_status == 0) {
          $erro_msg = $clmatestoquetransf->erro_msg;
          $sqlerro = true;
        }
      }
    }
        
    $arr_oqueaindafalta = $quant;
    $arr_estoquediminui = Array();
    $arr_vlorquediminui = Array();
    if ($numrows_matestoque > 0) {
      for ($ii = 0; $ii < $numrows_matestoque; $ii ++) {
        db_fieldsmemory($result_matestoque, $ii);
        if (!isset($arr_estoquediminui[$m70_codigo])) {
          $arr_estoquediminui[$m70_codigo] = $m70_quant;
          $arr_vlorquediminui[$m70_codigo] = $m70_valor;
        }
        $naoatendida = $m71_quant - $m71_quantatend;
        $valorunitario = $m71_valor / $m71_quant;
        if ($sqlerro == false && $naoatendida > 0 && $arr_oqueaindafalta > 0) {
          if ($arr_oqueaindafalta >= $naoatendida) {
            $clmatestoqueitem->m71_quantatend = $naoatendida + $m71_quantatend;
            $retirar = $naoatendida;
            $arr_oqueaindafalta -= $naoatendida;
          } else {
            $clmatestoqueitem->m71_quantatend = $m71_quantatend + $arr_oqueaindafalta;
            $retirar = $arr_oqueaindafalta;
            $arr_oqueaindafalta -= $arr_oqueaindafalta;
          }
          
          if ($sqlerro == false) {
            $clmatestoque->m70_codigo = $m70_codigo;
            $quantretira = $arr_estoquediminui[$m70_codigo] - $retirar;
            $valorretira = 0 + db_formatar(($arr_vlorquediminui[$m70_codigo] - ($retirar * $valorunitario)), 'p', ' ', 0, 'e', 4);
            $clmatestoque->m70_quant = "$quantretira";
            
            if ($valorretira < 0) {
              $valorretira *= -1;
            }
            
            $clmatestoque->m70_valor = "$valorretira";
            $arr_estoquediminui[$m70_codigo] -= $retirar;
            $arr_vlorquediminui[$m70_codigo] -= ($retirar * $valorunitario);
            $clmatestoque->alterar($m70_codigo);
            if ($clmatestoque->erro_status == 0) {
              $erro_msg = $clmatestoque->erro_msg;
              $sqlerro = true;
            }
          }
          if (isset($m70_codigo) && trim($m70_codigo) != "") {
            $clmatestoqueitem->m71_codlanc = $m71_codlanc;
            $clmatestoqueitem->m71_codmatestoque = $m70_codigo;
            $clmatestoqueitem->m71_quant = $m71_quant;
            $clmatestoqueitem->alterar($m71_codlanc);
            if ($clmatestoqueitem->erro_status == 0) {
              $sqlerro = true;
              $erro_msg = $clmatestoqueitem->erro_msg;
            }
            $m80_matestoqueitem = $clmatestoqueitem->m71_codlanc;
          }
          if ($sqlerro == false) {
            $clmatestoqueinimei->m82_matestoqueitem = $m71_codlanc;
            $clmatestoqueinimei->m82_matestoqueini = $m82_matestoqueini;
            $clmatestoqueinimei->m82_quant = $retirar;
            $clmatestoqueinimei->incluir(@ $m82_codigo);
            $erro_msg = $clmatestoqueinimei->erro_msg;
            if ($clmatestoqueinimei->erro_status == 0) {
              $sqlerro = true;
            }
          }
        } else {
          break;
        }
      }
    }
    $m80_codtipo = 8;
    
    if ($sqlerro == false) {
      $clmatestoqueinil->m86_matestoqueini = $codmatestoqueini;
      $clmatestoqueinil->incluir(null);
      $vaipromatestoqueinill = $clmatestoqueinil->m86_codigo;
      if ($clmatestoqueinil->erro_status == 0) {
        $erro_msg = $clmatestoqueinil->erro_msg;
        $sqlerro = true;
      }
    }
    
    if ($sqlerro == false) {
      $clmatestoqueini->m80_codigo = null;
      $clmatestoqueini->m80_login = $m80_login;
      $clmatestoqueini->m80_data = $m80_data;
      $clmatestoqueini->m80_hora = $m80_hora;
      $clmatestoqueini->m80_obs = "entrada da ordem de compra com saida automatica";
      $clmatestoqueini->m80_codtipo = $m80_codtipo;
      $clmatestoqueini->m80_coddepto = $depto;
      $clmatestoqueini->incluir(null);
      $matestoqueininovo = $clmatestoqueini->m80_codigo;
      $erro_msg = $clmatestoqueini->erro_msg;
      if ($clmatestoqueini->erro_status == 0) {
        $sqlerro = true;
      }
    }
    
    if ($sqlerro == false) {
      $clmatestoqueinill->m87_matestoqueini = $matestoqueininovo;
      $clmatestoqueinill->m87_matestoqueinil = $vaipromatestoqueinill;
      $clmatestoqueinill->incluir($vaipromatestoqueinill);
      if ($clmatestoqueinill->erro_status == 0) {
        $erro_msg = $clmatestoqueinill->erro_msg;
        $sqlerro = true;
      }
    }
    
    $quant_cancel = "";
    $valor_cancel ="";
    $result_matestoque = $clmatestoqueini->sql_record($clmatestoqueini->sql_query_mater($codmatestoqueini, " distinct m70_codigo,m70_quant,m70_valor,m71_codlanc,m71_quant,m71_quantatend,m82_quant as quant_mei,m60_codmater", "m71_codlanc","matestoqueini.m80_codigo = $codmatestoqueini and matestoqueinimei.m82_matestoqueitem = $codlanc   "));
    $numrows_matestoque = $clmatestoqueini->numrows;
    for ($i = 0; $i < $numrows_matestoque; $i ++) {
      db_fieldsmemory($result_matestoque, $i);
      $valorunitarioitem = $m71_valor/$m71_quant;
      $result_estoque = $clmatestoque->sql_record($clmatestoque->sql_query_item(null, "m70_codigo as cod ,m70_quant as quant ,m70_valor as valor", "", "m70_codmatmater=$codmater and m70_coddepto=$depto and m71_codlanc = $codlanc "));
      if ($clmatestoque->numrows > 0) {
        db_fieldsmemory($result_estoque, 0);
        //$clmatestoque->m70_codmatmater = $m60_codmater;
        $clmatestoque->m70_valor = $valor + ($quant_mei * $valorunitarioitem);
        $clmatestoque->m70_quant = $quant + $quant_mei;
        $clmatestoque->m70_codigo = $cod;
        $clmatestoque->alterar($cod);
        $codigoinclui = $cod;
        if ($clmatestoque->erro_status == 0) {
          $erro_msg = $clmatestoque->erro_msg;
          $sqlerro = true;
        }
      } else {
        $clmatestoque->m70_codmatmater = $m60_codmater;
        $clmatestoque->m70_coddepto = $depto;
        //db_msgbox($val ."      *     ". $m71_quant ."===  ".$quant_mei );
       // db_msgbox($quant_mei ."      *     ". $valorunitarioitem );
        $clmatestoque->m70_valor = $quant_mei * $valorunitarioitem;
        $clmatestoque->m70_quant = $quant_mei;
        $clmatestoque->incluir(null);
        $codigoinclui = $clmatestoque->m70_codigo;
        if ($clmatestoque->erro_status == 0) {
          $erro_msg = $clmatestoque->erro_msg;
          $sqlerro = true;
        }
        $clmatestoque->m70_codmatmater = "";
        $clmatestoque->m70_coddepto = "";
      }
      if (isset($codigoinclui) && trim($codigoinclui) != "") {
        $clmatestoqueitem->m71_codmatestoque = $codigoinclui;
        $clmatestoqueitem->m71_data = date("Y-m-d", db_getsession("DB_datausu"));
        $clmatestoqueitem->m71_valor = $valorunitarioitem * $quant_mei;
        $clmatestoqueitem->m71_quant = $quant_mei;
        $clmatestoqueitem->m71_quantatend = '0';
        $clmatestoqueitem->incluir(null);
        if ($clmatestoqueitem->erro_status == 0) {
          $erro_msg = $clmatestoqueitem->erro_msg;
          $sqlerro = true;
        }
        $m80_matestoqueitem = $clmatestoqueitem->m71_codlanc;
      }
      if ($sqlerro == false) {
        $clmatestoqueinimei->m82_matestoqueitem = $m80_matestoqueitem;
        $clmatestoqueinimei->m82_matestoqueini = $matestoqueininovo;
        $clmatestoqueinimei->m82_quant = $quant_mei;
        $clmatestoqueinimei->incluir(null);
        if ($clmatestoqueinimei->erro_status == 0) {
          $erro_msg = $clmatestoqueiniimei->erro_msg;
          $sqlerro = true;
        }
      }
      
    }
    
  }
//  $sqlerro=true;
  db_fim_transacao($sqlerro);
  if ($sqlerro==true) {
    db_msgbox("Erro!!".@$erro_msg);
  } else {
    db_msgbox("Processamento efetuado com sucesso!!");
    echo "<script>location.href='mat1_entraordcom001.php';</script>";
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
<script>


</script>

<style>
<?$cor="#999999"?>
.bordas{
         border: 2px solid #cccccc;
         border-top-color: <?=$cor?>;
         border-right-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
         background-color: #999999;
}
<?$cor="999999"?>
.bordas_corp{
         border: 1px solid #cccccc;
         border-right-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
       }
</style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#CCCCCC"> 
<table  border="0" cellspacing="0" cellpadding="0" width='100%'>
  <tr> 
  <br>
  <br>
  <br>
    <td  align="left" valign="top" bgcolor="#CCCCCC"> 
    <form name='form1'>
    <center>

  <tr align = "center">
    <td  align = "center">
      <input name="voltar" type="button" value="Voltar" onclick="location.href='mat1_entraordcom001.php';" >
      <input name="confirma" type="submit"  value="Confirma" onclick='return js_buscaquant();'  >
      <br>
      <br>
    </td>
  </tr>
 <table border='1' cellspacing="0" cellpadding="0">   
 <?
db_input('valor',40,"",true,'hidden',3,'');
db_input("m80_codigo","10","",true,"hidden",3);
if (isset($m80_codigo) && $m80_codigo!= "") {
  
  $campos = "*";
  $result = $clmatestoqueinimei->sql_record($clmatestoqueinimei->sql_query_info(null,"$campos",null,"m82_matestoqueini=$m80_codigo"));
  $numrows = $clmatestoqueinimei->numrows;
  if ($numrows>0) {
    echo "
          <tr class='bordas'>
          <td class='bordas' align='center'><b><small>$RLm41_codmatmater</small></b></td>
          <td class='bordas' align='center'><b><small>$RLm60_descr</small></b></td>
          <td class='bordas' align='center'><b><small>Unid. Saída</small></b></td>
          <td class='bordas' align='center'><b><small><b>Quant. Disponível em Estoque<b></small></b></td>
          <td class='bordas' align='center'><b><small><b>Quant. Solicitada<b></small></b></td>
          <td class='bordas' align='center'><b><small><b>Almox<b></small></b></td>
          ";
  } else {
    echo"<b>Nenhum registro encontrado...</b>";
  }
  echo " </tr>";
  for ($i=0; $i<$numrows; $i++) {
    db_fieldsmemory($result,$i);
    echo "<tr>
          <td	class='bordas_corp' align='center'><small>$m60_codmater </small></td>
          <td	class='bordas_corp' align='center'><small>$m60_descr </small></td>
          <td	class='bordas_corp' align='center'><small>$m61_descr </small></td>
          <td	class='bordas_corp' align='center'><small>$m71_quant</small></td>";
    $q="q_$i" ;
    $$q=$m71_quant;
    db_input("q_$i",6,0,true,'hidden',3,"");

    $op = 1;
    $quantidade = "quant_".$m71_codlanc."_".$m60_codmater."_"."$i";
    $$quantidade = "$m71_quant";
    
    echo "<td class='bordas_corp' align='center'><small>";
    db_input("quant_".$m71_codlanc."_".$m60_codmater."_"."$i",6,0,true,'text',1,"onchange='js_testaquant(this.value,$m71_quant,$i,$m71_codlanc)'");
    echo "</small></td><td>";
    
      $result_almox = $cldb_almoxdepto->sql_record($cldb_almoxdepto->sql_query_inf(null,null,"distinct  m91_depto,m91_codigo,descrdepto"));
      if ($cldb_almoxdepto->numrows>0) {
          echo " <select onchange='js_preenchedepto(this.value,$i);'   name='depto_$i' >";
            echo "<option value=\"\" >Selecione um Almox.</option>\n";
        for ($x=0; $x<$cldb_almoxdepto->numrows; $x++) {
          db_fieldsmemory($result_almox,$x);
            echo "<option value=\"$m91_depto\" >$descrdepto</option>\n";
        }
          echo " </select>";
      } else {
        echo "Nenhum Almox. disponível!";
      }
    echo "</small></td>";
    echo"  </tr>";
  }
}
?>     
 </table>
    </form> 
    </center>
    </td>
  </tr>
</table>
<script>
function js_testaquant(valor,quant,pos,codlan)
{
  if (valor>quant) {
    alert("Informe uma quantidade valida!!");
    eval("document.form1.quant_"+codlan+"_"+pos+".value="+quant);
    eval("document.form1.quant_"+codlan+"_"+pos+".focus()");
  }
}
function js_preenchedepto(depto,pos)
{
  obj=document.form1;
  for (w=0; w<obj.elements.length; w++) {
    if (obj.elements[w].name.substr(0,6)=="depto_") {
      if (obj.elements[w].value==""){
        obj.elements[w].value = depto;
      }
    }    
  }
}
function js_buscaquant()
{
  obj=document.form1;
  valor = "";
  arr_info = new Array();
  var ii = 0;
  for (i=0; i<obj.elements.length; i++) {
    if (obj.elements[i].name.substr(0,6)=="quant_") {
      
      valor += obj.elements[i].name+"_"+obj.elements[i].value;
    }
  }
  
  document.form1.valor.value = valor;
  return true;
}
</script>
<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>