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

require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("libs/db_liborcamento.php");
include ("dbforms/db_classesgenericas.php");
include ("dbforms/db_funcoes.php");
include ("classes/db_pcproc_classe.php");
include ("classes/db_pcprocitem_classe.php");
include ("classes/db_pcdotac_classe.php");
include ("classes/db_orcdotacao_classe.php");
include ("classes/db_orcelemento_classe.php");
include ("classes/db_solicitempcmater_classe.php");
include ("classes/db_solicitemele_classe.php");
include ("classes/db_solicitem_classe.php");
include ("classes/db_pcmater_classe.php");
$clpcproc = new cl_pcproc;
$clpcprocitem = new cl_pcprocitem;
$clpcdotac = new cl_pcdotac;
$clorcdotacao = new cl_orcdotacao;
$clorcelemento = new cl_orcelemento;
$clsolicitempcmater = new cl_solicitempcmater;
$clsolicitemele = new cl_solicitemele;
$clsolicitem = new cl_solicitem;
$clpcmater = new cl_pcmater;
$clrotulo = new rotulocampo;
$clrotulo->label("pc01_descrmater");
$clrotulo->label("pc18_solicitem");
$clrotulo->label("pc18_codele");
$clrotulo->label("pc81_codprocitem");
$clrotulo->label("o56_elemento");
db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);
//db_postmemory($HTTP_POST_VARS,2);db_postmemory($HTTP_GET_VARS,2);
$db_opcao = 1;
$db_botao = false;

if(isset($incluir)){
  $sqlerro = false;
  $qualelemento = split(",",$valores);
  for($i=0;$i<sizeof($qualelemento);$i++){
    if($sqlerro==false){
      $subelemento = $qualelemento[$i];
      $solicitem = split("_",$subelemento);
      $pc18_solicitem = $solicitem[1];
      $clsolicitemele->incluir($pc18_solicitem,$$subelemento);
      $erro_msg = $clsolicitemele->erro_msg;
      if($clsolicitemele->erro_status==0){
	$sqlerro=true;
	break;
      }
    }
  }
}

$passou = false;
if(isset($pc80_codproc)){
  $passou = true;
  $result_procitem = $clpcprocitem->sql_record($clpcprocitem->sql_query_file(null,"pc81_codproc as pc80_codproc,pc81_solicitem as pc18_solicitem,pc81_codprocitem","pc81_solicitem","pc81_codproc=$pc80_codproc"));
  $numrows_pcprocitem = $clpcprocitem->numrows ;
  if($numrows_pcprocitem==0){
    $db_opcao = 3;
    $db_botao = true;
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
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
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<center>
<table width="730" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="360" align="left" valign="top" bgcolor="#CCCCCC"> 
      <form name="form1">
      <center>
      <table height="20" border="0">
	<tr>  
	  <td>
	  <center>
	  <?
	  db_input("valores",100,0,true,"hidden",3);
          $semitem  = false;
          if($passou==true){
	    if($numrows_pcprocitem>0){
	      echo "<table height='20' border='1' width='90%'>\n";
	      for($i=0;$i<$numrows_pcprocitem;$i++){
		db_fieldsmemory($result_procitem,$i);
		$result_pcmater = $clpcprocitem->sql_record($clpcprocitem->sql_query_pcmater(null,"pc01_codmater,pc01_descrmater,pc11_codigo,pc11_seq,m61_descr,m61_usaquant,pc17_codigo,pc17_quant,pc17_unid,pc81_codprocitem,pc01_servico",""," pc81_codprocitem = $pc81_codprocitem and pc18_solicitem is null"));
		$numrows_pcmater = $clpcprocitem->numrows;
		if($numrows_pcmater>0){
		  db_fieldsmemory($result_pcmater,0);
		  if($i==0){
		    echo "  <tr>\n";
		    echo "    <td nowrap class='bordas02' colspan='6'><strong>Itens a alterar - Processo de compras N&ordm; $pc80_codproc</strong></td>\n";
		    echo "  </tr>\n";
		    echo "  <tr>\n";
		    echo "    <td nowrap class='bordas02' title='Item em que será incluído o elemento selecionado' align='center'><strong>";db_ancora("M","js_marcatodos();",1);echo"</strong>\n</td>\n";  
		    echo "    <td nowrap class='bordas02' title='Sequencial do item na solicitação'                align='center'><strong>Seq. sol.</strong></td>\n";
		    echo "    <td nowrap class='bordas02' title='Código do item no processo de compras'            align='center'><strong>Item proc.</strong></td>\n";
		    echo "    <td nowrap class='bordas02' title='Código do tipo de material'                       align='center'><strong>Código material</strong></td>\n";
		    echo "    <td nowrap class='bordas02' title='Descrição do tipo de material'                    align='center'><strong>Material</strong></td>\n";
		    echo "    <td nowrap class='bordas02' title='Referência do item. Ex: Caixa, unidade, ...'      align='center'><strong>Referência</strong></td>\n";
		    echo "  </tr>\n";
		  }
		  echo "  <tr>\n";
		  echo "    <td nowrap class='bordas' title='Item em que será incluído o elemento selecionado' align='center'><input type='checkbox' name='item_".$pc11_codigo."' value='ele_".$pc11_codigo."' onclick='js_buscavalores();'>\n</td>\n";  
		  echo "    <td nowrap class='bordas' title='Sequencial do item na solicitação'                align='center'><strong>$pc11_seq</strong></td>\n";
		  echo "    <td nowrap class='bordas' title='Código do item no processo de compras'            align='center'><strong>$pc81_codprocitem</strong></td>\n";
		  echo "    <td nowrap class='bordas' title='Código do tipo de material'                       align='center'><strong>$pc01_codmater</strong></td>\n";
		  echo "    <td class='bordas' title='Descrição do tipo de material'><strong>$pc01_descrmater</strong></td>\n";
		  if(trim($pc17_codigo)!=""){
		    echo "    <td nowrap class='bordas' align='center' title='Referência do item. Ex: Caixa, unidade, ...'>\n";
		    echo "      <strong>\n";
		    echo "        $m61_descr ";
		    if(($m61_usaquant)=="t"){	
		    echo "        ($pc17_quant UNIDADES)";
		    }
		    echo "      </strong>\n";
		    echo "    </td>\n";
		  }else if($pc01_servico=='t'){
		    echo "    <td nowrap class='bordas' title='Referência do item. Ex: Caixa, unidade, ...'>\n";
		    echo "      <strong>\n";
		    echo "        SERVIÇO";
		    echo "      </strong>\n";
		    echo "    </td>\n";
		  }
		  echo "  </tr>\n";
		  $result_elemendotac = $clpcdotac->sql_record($clpcdotac->sql_query_descrdot($pc11_codigo,db_getsession("DB_anousu"),null,"substr(o56_elemento,1,7) as elemento"));
		  if($clpcdotac->numrows>0){
		    db_fieldsmemory($result_elemendotac,0);
		    $where_elemento = "";
		    if(isset($elemento)){
		      $where_elemento = " substr(o56_elemento,1,7)=\'$elemento\' ";
		    }
		    $nomeelemento = "ele_".$pc11_codigo;
		    $result_orcelemento = $clorcelemento->sql_record($clorcelemento->sql_query_pcmater(null,"o56_codele as $nomeelemento,o56_elemento,o56_descr","o56_codele"," o56_anousu = ".db_getsession("DB_anousu")." and o56_elemento like '".$elemento."%' and pc01_codmater=$pc01_codmater"));
		    $numrows_elementos = $clorcelemento->numrows;
		    $arr_elementos = Array();
		    for($ii=0;$ii<$numrows_elementos;$ii++){
		      db_fieldsmemory($result_orcelemento,$ii);
		      $arr_elementos[$$nomeelemento] = $o56_elemento.' - '.$o56_descr;
		    }
		    if(sizeof($arr_elementos)>0){
		      echo "  <tr>\n";
		      echo "    <td nowrap class='bordas' colspan='6' title='Sub-elemento do item'>\n";
		      echo "      <strong>\n";
		      echo "        Sub-elemento:";
				    db_select($nomeelemento,$arr_elementos,$Ipc18_codele,1);
		      echo "      </strong>\n";
		      echo "    </td>\n";
		      echo "  </tr>\n";
		    }else{
		      echo "  <tr>\n";
		      echo "    <td nowrap class='bordas' colspan='6' align='center'><strong>Nenhum sub-elemento compatível com este material, contate o suporte.</strong></td>\n";
		      echo "  </tr>\n";
		    }
		  }else{
		    echo "  <tr>\n";
		    echo "    <td nowrap class='bordas' colspan='6' align='center'><strong>Item sem elemento da dotação.</strong></td>\n";
		    echo "  </tr>\n";
		  }
		  if(($i+1)!=$numrows_pcprocitem){
		    echo "  <tr>\n";
		    echo "    <td nowrap colspan='6'>&nbsp;</td>\n";
		    echo "  </tr>\n";
		  }
		  $ntemitem = false;
		}else{
		  if(!isset($ntemitem)){
		    $semitem = true;		    
	            $erro_msg = "Usuário: \\n\\nSub-elementos dos itens deste processo de compras já foram incluídos.\\n\\nAdministrador:";
		  }else{
		    $semitem = false;		    
		  }
		  continue;
		}
	      }
	      if($semitem==true){
		echo "<tr>";
		echo "  <td>";
		echo "    <strong>Sub-elementos dos itens deste processo já incluídos.</strong>";
		echo "  </td>";
		echo "</tr>";
	      }
	      echo "</table>\n";
	    }else{
	      echo "<strong>Sub-elementos dos itens deste processo já incluídos.</strong>";
	      $semitem  = true;
	      $erro_msg = "Usuário: \\n\\nSub-elementos dos itens deste processo de compras já foram incluídos.\\n\\nAdministrador:";
	      $sqlerro  = true;
	    }
	  }else if(!isset($pc80_codproc)){
	    echo "<strong>Número do processo de compras não informado.</strong>";
          }
	  ?>
	  </center>
          </td>
	</tr>
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
function js_marcatodos(){
  x = document.form1;
  for(i=0;i<x.length;i++){
    if(x.elements[i].type=='checkbox'){
      if(x.elements[i].checked==true){
	x.elements[i].checked = false;
      }else{
	x.elements[i].checked = true;
      }
    }
  }
  js_buscavalores();
}
function js_buscavalores(){
  x = document.form1;
  x.valores.value = "";
  vir = "";
  for(i=0;i<x.length;i++){
    if(x.elements[i].type=='checkbox'){
      if(x.elements[i].checked==true){
	x.valores.value += vir+x.elements[i].value;
	vir = ",";
      }
    }
  }
}
function js_pesquisapc16_codmater(mostra){
  qry = "&o56_codele="+document.form1.o56_codele.value;	
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pcmater','func_pcmater.php?funcao_js=parent.js_mostrapcmater1|pc01_codmater|pc01_descrmater'+qry,'Pesquisa',true);
  }else{
     if(document.form1.pc16_codmater.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_pcmater','func_pcmater.php?pesquisa_chave='+document.form1.pc16_codmater.value+'&funcao_js=parent.js_mostrapcmater'+qry,'Pesquisa',false);
     }else{
       document.form1.pc01_descrmater.value = ''; 
     }
  }
}
function js_mostrapcmater(chave,erro){
  document.form1.pc01_descrmater.value = chave; 
  if(erro==true){ 
    document.form1.pc16_codmater.focus(); 
    document.form1.pc16_codmater.value = ''; 
  }  
}
function js_mostrapcmater1(chave1,chave2){
  document.form1.pc16_codmater.value = chave1;  
  document.form1.pc01_descrmater.value = chave2;
  db_iframe_pcmater.hide();
}
</script>
<?
if(isset($incluir) || $semitem==true){
  if($sqlerro==true){
    db_msgbox($erro_msg);
  }else if(isset($incluir)){
    echo "<script>top.corpo.location.href = 'com1_solicitemele001.php';</script>";
  }
}
?>