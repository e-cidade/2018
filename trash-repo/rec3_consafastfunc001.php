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
include("classes/db_rhpessoal_classe.php");
include("classes/db_assenta_classe.php");
db_postmemory($HTTP_POST_VARS);
$clrhpessoal = new cl_rhpessoal;
$classenta = new cl_assenta;
$clrotulo = new rotulocampo;
$clrotulo->label('rh01_regist');
$clrotulo->label('z01_nome');
$clrotulo->label('h12_codigo');
$clrotulo->label('h12_assent');
$clrotulo->label('h12_descr');

if(isset($rh01_regist) && trim($rh01_regist) != ""){
  if(!isset($dataf_dia) || (isset($dataf_dia) && trim($dataf_dia) == "")){
    $dataf = date("Y-m-d",db_getsession("DB_datausu"));
    $dataf_dia = db_subdata($dataf,"d");
    $dataf_mes = db_subdata($dataf,"m");
    $dataf_ano = db_subdata($dataf,"a");
  }

  $result_assenta = $classenta->sql_record($classenta->sql_query_file(null," h16_assent, h16_dtconc ", " h16_dtconc, h16_assent"));
  if($classenta->numrows > 0){
    db_fieldsmemory($result_assenta, 0);
    $datai_dia = db_subdata($h16_dtconc,"d");
    $datai_mes = db_subdata($h16_dtconc,"m");
    $datai_ano = db_subdata($h16_dtconc,"a");
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
  <form name="form1" method="post">
  <tr> 
    <td nowrap title="<?=@$Trh01_regist?>">
      <?
      db_ancora(@$Lrh01_regist,"js_pesquisarh01_regist(true);",1);
      ?>
    </td>
    <td nowrap>
      <?
      db_input('rh01_regist',6,$Irh01_regist,true,'text',1,"onchange='js_pesquisarh01_regist(false);'")
      ?>
      <?
      db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
      ?>
    </td>
  </tr>
  <tr> 
    <td nowrap>
      <b>Período:</b>
    </td>
    <td nowrap>
      <?
      db_inputdata('datai',@$datai_dia,@$datai_mes,@$datai_ano,true,'text',1,"")
      ?>
      &nbsp;<b>a</b>&nbsp;
      <?
      db_inputdata('dataf',@$dataf_dia,@$dataf_mes,@$dataf_ano,true,'text',1,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th12_codigo?>">
      <?
      db_ancora(@$Lh12_codigo,"js_pesquisah12_codigo(true);",1);
      ?>
    </td>
    <td colspan="3"> 
      <?
      db_input('h12_codigo',6,$Ih12_codigo,true,'hidden',3,"")
      ?>
      <?
      db_input('h12_assent',6,$Ih12_assent,true,'text',1," onchange='js_pesquisah12_codigo(false);'")
      ?>
      <?
      db_input('h12_descr',40,$Ih12_descr,true,'text',3,'')
      ?>
    </td>
  </tr>
  <tr>
    <td colspan="2" align = "center"> 
      <input  name="consulta" id="consulta" type="button" value="Consulta" onclick="js_consulta();" >
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
function js_consulta(){
  erro = 0;

  datInici = document.form1.datai_ano.value + "-" + document.form1.datai_mes.value + "-" + document.form1.datai_dia.value;

  dtDatInici = new Date(document.form1.datai_ano.value, document.form1.datai_mes.value, document.form1.datai_dia.value);
  if(document.form1.rh01_regist.value == ""){
    alert("Informe a matrícula do funcionário!");
    erro ++;
    document.form1.rh01_regist.focus();
  }else if(document.form1.datai_ano.value == "" || document.form1.datai_mes.value == "" || document.form1.datai_dia.value == ""){
    datFinal = "";
    alert("Informe a data inicial!");
    erro ++;
    document.form1.datai_dia.focus();
  }else if(document.form1.dataf_ano.value != "" && document.form1.dataf_mes.value != "" && document.form1.dataf_dia.value != ""){
    datFinal = document.form1.dataf_ano.value + "-" + document.form1.dataf_mes.value + "-" + document.form1.dataf_dia.value;
    dtDatFinal = new Date(document.form1.dataf_ano.value, document.form1.dataf_mes.value, document.form1.dataf_dia.value);
    if(dtDatInici > dtDatFinal){
      alert("Período de data inválido!");
      erro ++;
      document.form1.dataf_dia.focus();
    }
  }

  if(erro == 0){
    qry = "?codAssen=" + document.form1.h12_codigo.value;
    qry+= "&codMatri=" + document.form1.rh01_regist.value;
    qry+= "&dataIni=" + datInici;
    qry+= "&dataFim=" + datFinal;
    js_OpenJanelaIframe('top.corpo','db_iframe_tipoasse','rec3_consafastfunc002.php' + qry,'Consulta',true);
  }
}
function js_pesquisarh01_regist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoal.php?funcao_js=parent.js_mostrapessoal1|rh01_regist|z01_nome&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',true);
  }else{
    if(document.form1.rh01_regist.value != ''){ 
      js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoal.php?pesquisa_chave='+document.form1.rh01_regist.value+'&funcao_js=parent.js_mostrapessoal&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',false);
    }else{
      document.form1.z01_nome.value = '';
      document.form1.submit();
    }
  }
}
function js_mostrapessoal(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.rh01_regist.focus(); 
    document.form1.rh01_regist.value = ''; 
  }else{
    document.form1.submit();
  }
}
function js_mostrapessoal1(chave1,chave2){
  document.form1.rh01_regist.value = chave1;
  document.form1.z01_nome.value   = chave2;
  db_iframe_rhpessoal.hide();
  document.form1.submit();
}

function js_pesquisah12_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tipoasse','func_tipoasse.php?chave_codigo=true&funcao_js=parent.js_mostratipoasse1|h12_codigo|h12_assent|h12_descr','Pesquisa',true);
  }else{
    if(document.form1.h12_assent.value != ''){ 
       js_OpenJanelaIframe('top.corpo','db_iframe_tipoasse','func_tipoasse.php?chave_assent='+document.form1.h12_assent.value+'&funcao_js=parent.js_mostratipoasse','Pesquisa',false);
    }else{
      document.form1.h12_descr.value = ''; 
      document.form1.h12_codigo.value = '';
    }
  }
}
function js_mostratipoasse(chave,chave2,erro){
  document.form1.h12_descr.value = chave2; 
  if(erro==true){ 
    document.form1.h12_assent.focus(); 
    document.form1.h12_assent.value = ''; 
    document.form1.h12_codigo.value = '';
  }else{
    document.form1.h12_codigo.value = chave;
  }
}
function js_mostratipoasse1(chave1,chave2,chave3){
  document.form1.h12_codigo.value = chave1;
  document.form1.h12_assent.value = chave2;
  document.form1.h12_descr.value = chave3;
  db_iframe_tipoasse.hide();
}
js_tabulacaoforms("form1","rh01_regist",true,0,"rh01_regist",true);
</script>