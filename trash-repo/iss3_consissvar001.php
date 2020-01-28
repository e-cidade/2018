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
db_postmemory($HTTP_SERVER_VARS);
$db_botao=true;
$db_opcao=2;
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_cgccpf");
$clrotulo->label("q02_inscr");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.q02_inscr.focus();">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

<script>
function js_testacamp(){
  obj=document.form1;
  var inscr = new Number(obj.q02_inscr.value);
  var numcgm = new Number(obj.z01_numcgm.value);
  var cgccpf = obj.z01_cgccpf.value;
  var query=""; 
  //(q05_ano >= $anoini and q05_mes >= $mesini) and (q05_ano <= $anofim and q05_mes <= $mesfim)
  
  if(obj.ano_ini.value!=0){
     query+="&ano_ini="+obj.ano_ini.value;
     if(obj.mes_ini.value!=0){
       query+="&mes_ini="+obj.mes_ini.value;
     }
  }else{
    if(obj.mes_ini.value!=0){
      alert("Selecione o ano inicial!");
      return false;
    }
  }  
  if(obj.ano_fim.value!=0){
     query+="&ano_fim="+obj.ano_fim.value;
     if(obj.mes_fim.value!=0){
       query+="&mes_fim="+obj.mes_fim.value;
     }
  }else{
    if(obj.mes_fim.value!=0){
      alert("Selecione o ano final!");
      return false;
    }
  }  
  if(inscr!="" && !isNaN(inscr)){
    if(query==""){
      js_OpenJanelaIframe('top.corpo','db_iframe_q02_inscr','iss3_consissvar002.php?q02_inscr='+inscr,'Pesquisa',true);
     }else{
     eval("js_OpenJanelaIframe('top.corpo','db_iframe_q02_inscr','iss3_consissvar002.php?q02_inscr='+inscr+'"+query+"','Pesquisa',true);");
     } 
    return true;
  }
  if(cgccpf!="" && !isNaN(cgccpf)){
    if(query==""){
      js_OpenJanelaIframe('top.corpo','db_iframe_z01_cgccpf','iss3_consissvar002.php?z01_cgccpf='+cgccpf,'Pesquisa',true);
    }else{
     eval("js_OpenJanelaIframe('top.corpo','db_iframe_z01_cgccpf','iss3_consissvar002.php?z01_cgccpf='+cgccpf+'"+query+"','Pesquisa',true);");
    }  
    return true;
  }
  if(numcgm!="" && !isNaN(numcgm)){
    if(query==""){
      js_OpenJanelaIframe('top.corpo','db_iframe_z01_numcgm','iss3_consissvar002.php?z01_numcgm='+numcgm,'Pesquisa',true);
    }else{
     eval("js_OpenJanelaIframe('top.corpo','db_iframe_z01_numcgm','iss3_consissvar002.php?z01_numcgm='+numcgm+'"+query+"','Pesquisa',true);");
    }  
    return true;
  }
}   
function js_invalido(hide,msg){
 eval("db_iframe_"+hide+".hide();");
  alert(msg);
} 
</script>
<table height="430" width="790" border="1" valign="top" cellspacing="0" cellpadding="0" bgcolor="#cccccc">
  <tr> 
  <td align="center" valign="top" bgcolor="#cccccc">     
  <form name="form1" method="post"  target="db_iframe_cons">
   <table border="0" cellspacing="0" cellpadding="0">
   <br>
   <br>
     <tr>   
       <td title="<?=$Tq02_inscr?>">
      <?
       db_ancora($Lq02_inscr,' js_inscr(true); ',1);
      ?>
       </td>
       <td> 
      <?
       db_input('q02_inscr',5,$Iq02_inscr,true,'text',1,"onchange='js_inscr(false)'");
      db_input('z01_nome',40,0,true,'text',3,"","z01_nomeinscr");
      ?>
       </td>
     </tr>
     <tr>   
      <td title="<?=$Tz01_numcgm?>">
      <?
       db_ancora($Lz01_nome,' js_cgm(true); ',1);
      ?>
       </td>
       <td> 
      <?
       db_input('z01_numcgm',5,$Iz01_numcgm,true,'text',1,"onchange='js_cgm(false)'");
       db_input('z01_nome',40,0,true,'text',3,"","z01_nomecgm");
      ?>
       </td>
     </tr>
     <tr>   
       <td title="<?=$Tz01_cgccpf?>">
      <?
       db_ancora($Lz01_cgccpf,' js_inscr(true); ',1);
      ?>
       </td>
       <td> 
      <?
       db_input('z01_cgccpf',20,$Iz01_cgccpf,true,'text',1,"","","white");
      ?>
       </td>
     </tr>
     <tr>
       <td>
         <b>Mês/Ano inicial:</b>
       </td>
       <td>
<?       
$result=array("0"=>"...","1"=>"Janeiro","2"=>"Feveireiro","3"=>"Março","4"=>"Abril","5"=>"Maio","6"=>"Junho","7"=>"Julho","8"=>"Agosto","9"=>"Setembro","10"=>"Outubro","11"=>"Novembro","12"=>"Dezembro");
db_select("mes_ini",$result,true,$db_opcao,"","","","","");

$anos=array();
$anoatual=date("Y",db_getsession("DB_datausu"));
$anos[0]="...";
for($i=$anoatual; $i>($anoatual-15); $i--){
 $anos[$i]=$i;
}  
db_select("ano_ini",$anos,true,$db_opcao,"","","","","");
?>       
       </td>
     </tr>
     <tr>
       <td>
         <b>Mês/Ano final:</b>
       </td>
       <td>
<?       
$result=array("0"=>"...","1"=>"Janeiro","2"=>"Feveireiro","3"=>"Março","4"=>"Abril","5"=>"Maio","6"=>"Junho","7"=>"Julho","8"=>"Agosto","9"=>"Setembro","10"=>"Outubro","11"=>"Novembro","12"=>"Dezembro");
db_select("mes_fim",$result,true,$db_opcao,"","","","","");

$anos=array();
$anos[0]="...";
$anoatual=date("Y",db_getsession("DB_datausu"));
for($i=$anoatual; $i>($anoatual-15); $i--){
 $anos[$i]=$i;
}  
db_select("ano_fim",$anos,true,$db_opcao,"","","","","");
?>       
       </td>
     </tr>
     <tr>
       <td colspan="2" align="center">
     <br>
	   <input type="button" name="pesquisar" value="Pesquisar" onclick="return js_testacamp()" >
       </td>   	 
     </tr>	 
    </table> 	 
  </form>
  </td>
  </tr>
</table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_inscr(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_inscr','func_issbase.php?funcao_js=parent.js_mostrainscr|q02_inscr|z01_nome','Pesquisa',true);
  }else{
    var inscr=document.form1.q02_inscr.value;
    if(inscr!=""){
      js_OpenJanelaIframe('top.corpo','db_iframe_inscr','func_issbase.php?pesquisa_chave='+inscr+'&funcao_js=parent.js_mostrainscr1','Pesquisa',false);
    }else{
      document.form1.z01_nomeinscr.value="";
    }    
  }
}
function js_mostrainscr(chave1,chave2){
  document.form1.q02_inscr.value = chave1;
  document.form1.z01_nomeinscr.value = chave2;
  db_iframe_inscr.hide();
}
function js_mostrainscr1(chave,erro){
  document.form1.z01_nomeinscr.value = chave; 
  if(erro==true){ 
    document.form1.q02_inscr.focus(); 
    document.form1.q02_inscr.value = ''; 
  }
}


function js_cgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_numcgm','func_nome.php?funcao_js=parent.js_mostracgm|0|1','Pesquisa',true);
  }else{
    var cgm=document.form1.z01_numcgm.value;
    if(cgm!=""){
      js_OpenJanelaIframe('top.corpo','db_iframe_numcgm','func_nome.php?pesquisa_chave='+cgm+'&funcao_js=parent.js_mostracgm1','Pesquisa',false);
    }else{
      document.form1.z01_nomecgm.value="";
    }  
  }
}
function js_mostracgm(chave1,chave2){
  document.form1.z01_numcgm.value = chave1;
  document.form1.z01_nomecgm.value = chave2;
  db_iframe_numcgm.hide();
}
function js_mostracgm1(erro,chave){
  document.form1.z01_nomecgm.value = chave; 
  if(erro==true){ 
    document.form1.z01_numcgm.focus(); 
    document.form1.z01_numcgm.value = ''; 
  }
}
</script>
<?
if(isset($registro) && $registro=="pago"){
  db_msgbox("Já foram pagos ISSVAR para esta inscrição inscrição..");
}
if(isset($registro) && $registro=="invalido"){
  db_msgbox("Nenhum registro encontrado para esta inscrição..");
}
?>