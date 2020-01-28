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
include("classes/db_iptubase_classe.php");
include("classes/db_averba_classe.php");
include("classes/db_cgm_classe.php");
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$clcgm = new cl_cgm;
$cliptubase = new cl_iptubase;
$cliptubase->rotulo->label();
$cliptubase->rotulo->tlabel();
$rotulocampo = new rotulocampo;
$claverba = new cl_averba;
$claverba->rotulo->label();
$claverba->rotulo->tlabel();
$rotulocampo->label('z01_nome');
$rotulocampo->label('z01_numcgm');
$rotulocampo->label('j14_nome');
$rotulocampo->label('j34_setor');
$rotulocampo->label('j34_quadra');
$rotulocampo->label('j34_lote');


if(isset($incluir)){
  $cgmerrado=false;
  $result_cgm=$clcgm->sql_record($clcgm->sql_query_file($z01_numcgm)); 
  if ($clcgm->numrows!=0){
  db_inicio_transacao();
  $j55_codave="";
  $data = date("Y-m-d",db_getsession("DB_datausu"));
  $dat = split("-",$data);
  $claverba->j55_data=$data;
  $claverba->j55_data_dia=$dat[0];
  $claverba->j55_data_mes=$dat[1];
  $claverba->j55_data_ano=$dat[2];

  $claverba->j55_matric=$j01_matric;
  $claverba->j55_regimo=$j55_regimo;
  $claverba->j55_cidade=$j55_cidade;
  $claverba->j55_numcgm=$z01_numcgm2;
  $claverba->incluir($j55_codave);
  $cliptubase->j01_codave=$claverba->j55_codave;
  $cliptubase->j01_numcgm=$z01_numcgm;
  $cliptubase->alterar($j01_matric);
  db_fim_transacao();
  }else{
    $cgmerrado=true;
  }
}else{
    $result = $cliptubase->proprietario_record($cliptubase->proprietario_query($j01_matric,"j01_matric,z01_nome,z01_numcgm,j14_nome,j34_setor,j34_lote,j34_quadra,j01_codave"));
    @db_fieldsmemory($result,0);
    if($cliptubase->numrows!=0){
      if($j01_codave!=""){
       db_fieldsmemory($result,0);
      }else{
        db_redireciona("cad4_averba001.php?invalido");
      }
    }else{
      db_redireciona("cad4_averba001.php?invalido");
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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table height="430" width="790" border="0" cellspacing="0" cellpadding="0">
<form name="form1" method="post" action="">
  <tr>
    <td align="left" valign="center" bgcolor="#CCCCCC">
      <center>
      <table border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td>     
           <?=$Lj01_matric?>
          </td>
          <td> 
<?
  db_input('j01_matric',5,$Ij01_matric,true,'text',3,"");
?>
          </td>
        </tr>
        <tr> 
          <td>          
           <?=$Lz01_nome?>
          </td>
          <td> 
<?
  $z01_nome2=$z01_nome;
  $z01_numcgm2=$z01_numcgm;
  db_input('z01_nome',30,$Iz01_nome,true,'text',3,"","z01_nome2");
  db_input('z01_numcgm',30,$Iz01_numcgm,true,'hidden',3,"","z01_numcgm2");
?>
          </td>
        </tr>
        <tr> 
          <td>     
           <?=$Lj14_nome?>
          </td>
          <td> 
<?
  db_input('j14_nome',45,$Ij14_nome,true,'text',3,"");
?>
          </td>
        </tr>
        <tr> 
          <td>     
<?
  echo $Lj34_setor."/".$Lj34_quadra."/".$Lj34_lote
?>
          </td>
          <td> 
<?
  db_input('j34_setor',5,$Ij34_setor,true,'text',3,"");
  echo "/"; 
  db_input('j34_quadra',5,$Ij34_quadra,true,'text',3,"");
  echo "/"; 
  db_input('j34_lote',5,$Ij34_lote,true,'text',3,"");
?>
          </td>
        </tr>
        <tr>
          <td>
<?
  $result = $claverba->sql_record($claverba->sql_query($j01_matric,"max(j55_codave)","","j55_matric=$j01_matric"));
  $ultimo=pg_result($result,0,0); 
  $result = $claverba->sql_record($claverba->sql_query($j01_matric,"cgm.z01_nome,j55_cidade,j55_data,j55_regimo,j55_numcgm","","j01_matric=$j01_matric and j55_codave=$ultimo"));
  if($claverba->numrows!=0){
    db_fieldsmemory($result,0);
    $j55_cidade2=$j55_cidade;
    $j55_data2=$j55_data;
    $j55_regimo2=$j55_regimo;
    $j55_numcgm2=$j55_numcgm;
    $z01_nome3=$z01_nome;
    $j55_cidade="";
    $j55_data="";
    $j55_regimo="";
    $j55_numcgm="";
    $z01_nome="";
    $z01_numcgm="";
    $matriz=split("-", $j55_data2); 
    $ano=$matriz[2]; 
    $mes=$matriz[1]; 
    $dia=$matriz[0]; 
  }
?>  
            <b>ÚLTIMA AVERBAÇÃO</b>
          </td>
        </tr>
         <tr><td><?db_input('j01_numcgm',5,$Ij01_numcgm,true,'hidden',3,"")?></td></tr>  
        <tr> 
          <td>     
           <?=$Lj55_data?>
          </td>
          <td> 
<?
  db_inputdata('j55_data',@$j55_data_dia,@$j55_data_mes,@$j55_data_ano,true,'text',3,"","j55_data2");
?>
          </td>
        </tr>
        <tr> 
          <td>     
           <?=$Lj55_cidade?>
          </td>
          <td> 
<?
  db_input('j55_cidade',25,$Ij55_cidade,true,'text',3,"","j55_cidade2");
?>
          </td>
        </tr>
        <tr> 
          <td>     
           <?=$Lj55_regimo?>
          </td>
          <td> 
<?
  db_input('j55_regimo',25,$Ij55_regimo,true,'text',3,"","j55_regimo2");
?>
          </td>
        </tr>
        <tr> 
          <td>     
            <b>Vendedor</b>
          </td>
          <td> 
<?
  db_input('j55_numcgm',5,$Ij55_numcgm,true,'text',3,"","j55_numcgm2");
  db_input('z01_nome',45,$Iz01_nome,true,'text',3,"","z01_nome3");
?>
          </td>
        </tr>
        <tr>
          <td>
             <b>AVERBAÇÃO ATUAL</b>
          </td>
        </tr>  
        <tr> 
          <td>     
 <?
   $z01_nome="";
   $z01_numcgm="";
   db_ancora($Lz01_numcgm,' js_cgm(true); ',1);
?>
          </td>
          <td> 
<?
  db_input('z01_numcgm',5,$Iz01_numcgm,true,'text',1,"onchange='js_cgm(false)'");
  db_input('z01_nome',45,$Iz01_nome,true,'text',1,"");
?>
          </td>
        </tr>
        <tr> 
          <td>     
           <?=$Lj55_cidade?>
          </td>
          <td> 
<?
  db_input('j55_cidade',25,$Ij55_cidade,true,'text',1,"");
?>
          </td>
        </tr>
        <tr> 
          <td>     
           <?=$Lj55_regimo?>
          </td>
          <td> 
<?
  db_input('j55_regimo',25,$Ij55_regimo,true,'text',1,"");
?>
          </td>
        </tr>
      </table>
      <input name="incluir" type="submit" id="incluir" value="Incluir">
      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_averba()">
      <input name="voltar" type="button" id="voltar" value="Voltar" onclick="js_volta()">
      </center>
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
function js_volta(){
  location.href="cad4_averba001.php";
}  
function js_averba(){
    db_iframe.jan.location.href = 'func_averba.php?funcao_js=parent.js_mostrav';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
}
function js_mostrav(){
  db_iframe.hide();
}
function js_cgm(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_nome.php?funcao_js=parent.js_mostra1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_nome.php?pesquisa_chave='+document.form1.z01_numcgm.value+'&funcao_js=parent.js_mostra';
  }
}
function js_mostra1(chave1,chave2){
  document.form1.z01_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe.hide();
}
function js_mostra(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.z01_numcgm.value="";
    document.form1.z01_numcgm.focus();
  }
}
</script>
<?
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();

if(isset($incluir)||(isset($alterar))){
  if ($cgmerrado==false){
    if($claverba->erro_status=="0"){
      $claverba->erro(true,false);
      if($claverba->erro_campo!=""){
	echo "<script> document.form1.".$claverba->erro_campo.".style.backgroundColor='#99A9AE';</script>";
	echo "<script> document.form1.".$claverba->erro_campo.".focus();</script>";
      }
    }else{
      $claverba->erro(true,false);
    echo "<script>location.href='cad4_averba001.php'</script>";
    }
  }else{
    db_msgbox('CGM Inválido!!');
  }
}
?>