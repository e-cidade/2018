<?php
/**
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/db_classesgenericas.php"));

db_postmemory($_POST);

$classenta   = new cl_assenta;
$rotulocampo = new rotulocampo;
$rotulocampo->label("rh01_regist");
$rotulocampo->label("z01_nome");


$result_assenta = $classenta->sql_record($classenta->sql_query_file(null," h16_assent, h16_dtconc ", " h16_dtconc, h16_assent"));
if($classenta->numrows > 0){
  db_fieldsmemory($result_assenta, 0);
  $datai_dia = db_subdata($h16_dtconc,"d");
  $datai_mes = db_subdata($h16_dtconc,"m");
  $datai_ano = db_subdata($h16_dtconc,"a");
}
$result_tipoassent = $classenta->sql_record($classenta->sql_query_tipo(null, "h12_codigo, h12_assent || ' - ' || h12_descr as h12_descr", "h12_descr"));

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
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table  align="center">
  <form name="form1" method="post" action="" >
  <tr>
    <td align="right" title="<?=$Trh01_regist?>">
      <?
      db_ancora(@$Lrh01_regist, "js_pesquisarh01_regist(true);", 1);
      ?>
    </td>
    <td>
      <?
      db_input('rh01_regist', 8, $Irh01_regist, true, 'text', 1, " onchange='js_pesquisarh01_regist(false);'")
      ?>
      <?
      db_input('z01_nome', 30, $Iz01_nome, true, 'text', 3, '');
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="Período certidão" align="right">
      <b>Período:</b>
    </td>
    <td nowrap>
      <?
      db_inputdata("datai", @$datai_dia, @$datai_mes, @$datai_ano, true, 'text', 1);
      ?>
      <b>&nbsp;a&nbsp;</b>
      <?
      db_inputdata("dataf", @$dataf_dia, @$dataf_mes, @$dataf_ano, true, 'text', 1);
      ?>
    </td>
  </tr>
      <tr >
        <td align="right" ><strong>Ordem :&nbsp;&nbsp;</strong>
        </td>
        <td align="left">
          <?
            $arr_ordem = array("a"=>"Alfabetica","n"=>"Numerica","c"=>"Cargo","d"=>"Data");
            db_select('ordem',$arr_ordem,true,4,"");
	        ?>
	      </td>
      </tr>
      <tr >
        <td align="right" ><strong>Imprime Descricao :&nbsp;&nbsp;</strong>
        </td>
        <td align="left">
          <?
            $arr_descr = array("n"=>"Nao","s"=>"Sim");
            db_select('descr',$arr_descr,true,4,"");
	        ?>
	      </td>
      </tr>
  <tr>
    <td nowrap colspan="2">
    <?
    $arr_tipoassent_inicial = Array();
    $arr_tipoassent_final   = Array();
    if(isset($classenta->numrows)){
      for($i=0; $i<$classenta->numrows; $i++){
        db_fieldsmemory($result_tipoassent, $i);
        if(!isset($objeto2) || (isset($objeto2) && !in_array($h12_codigo, $objeto2))){
          $arr_tipoassent_inicial[$h12_codigo] = $h12_descr;
        }else{
          $arr_tipoassent_final[$h12_codigo] = $h12_descr;
        }
      }
    }
    db_multiploselect("valor","descr", "", "", $arr_tipoassent_inicial, $arr_tipoassent_final, 10, 350, "", "", true);
    ?>
    </td>
  </tr>
  <tr>
    <td colspan="2" align = "center"> 
      <input name="relatorio" id="relatorio" type="button" value="Relatório" onclick="js_emite();" >
    </td>
  </tr>
  </form>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_emite(){
  qry  = "&regist="+ document.form1.rh01_regist.value;
  qry += "&ordem="+ document.form1.ordem.value;
  qry += "&descr="+ document.form1.descr.value;
  qry += "&perinicial=" + document.form1.datai_ano.value+'-'+document.form1.datai_mes.value+'-'+document.form1.datai_dia.value;
  qry += "&perfinal=" + document.form1.dataf_ano.value+'-'+document.form1.dataf_mes.value+'-'+document.form1.dataf_dia.value;
  qry += "&tipos=" + js_db_multiploselect_retornaselecionados();
  jan = window.open('rec2_relassentaporperiodo002.php?'+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
function js_pesquisarh01_regist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rhpessoal','func_rhpessoal.php?filtro_lotacao=true&funcao_js=parent.js_mostrapessoal1|rh01_regist|z01_nome&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',true);
  }else{
    if(document.form1.rh01_regist.value != ''){ 
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rhpessoal','func_rhpessoal.php?filtro_lotacao=true&pesquisa_chave='+document.form1.rh01_regist.value+'&funcao_js=parent.js_mostrapessoal&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',false);
    }else{
      document.form1.z01_nome.value = '';
      js_seleciona_combo(document.form1.objeto2);
//      document.form1.submit();
    }
  }
}
function js_mostrapessoal(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){
    document.form1.rh01_regist.focus(); 
    document.form1.rh01_regist.value = ''; 
  }else{
    js_seleciona_combo(document.form1.objeto2);
//    document.form1.submit();
  }
}
function js_mostrapessoal1(chave1,chave2){
  document.form1.rh01_regist.value = chave1;
  document.form1.z01_nome.value   = chave2;
  db_iframe_rhpessoal.hide();
  js_seleciona_combo(document.form1.objeto2);
//  document.form1.submit();
}
function js_relatorio2(){
  var F = document.form1;
  var datai = "";
  var dataf = "";
  if(F.datai_dia.value != "" && F.datai_mes.value != "" && F.datai_ano.value != ""){
    datai = F.datai_ano.value+'-'+F.datai_mes.value+'-'+F.datai_dia.value;
  }
  if(F.dataf_dia.value != "" && F.dataf_mes.value != "" && F.dataf_ano.value != ""){
    dataf = F.dataf_ano.value+'-'+F.dataf_mes.value+'-'+F.dataf_dia.value;
  }
  if(datai == "" && dataf == ""){
    alert("Informe o período de admissão.");
    F.datai_dia.focus();
  }else{
    qry = "?datai="+datai;
    qry+= "&dataf="+dataf;
    qry+= "&ordem="+F.ordem.value;
    qry+= "&regime="+F.regime.value;
    if(F.listaponto.checked == true){
      qry+= "&fixo=s";
    }else{
      qry+= "&fixo=n";
    }
   qry+= "&lota="+F.lota.value;
//    jan = window.open('rec2_gradeefetividade002.php'+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scro
//    jan.moveTo(0,0);
  }
}
</script>