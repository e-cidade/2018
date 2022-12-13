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
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$db_opcao=3;
$permite=false;
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
if(isset($excluir)){
  db_inicio_transacao();
  $claverba->excluir($j55_codave);
  $result = $claverba->sql_record($claverba->sql_query_file("","max(j55_codave) as j55_codave",""," j55_matric = $j01_matric"));
  if(($result!= false) && $claverba->numrows > 0 ){
    db_fieldsmemory($result,0);
  }else{
    $j55_codave = 0;
  }
  $cliptubase->j01_numcgm = $j55_numcgm;
  $cliptubase->j01_codave = $j55_codave;
  $cliptubase->alterar($j01_matric);
  db_fim_transacao();
}else{ 
    if(isset($j01_matric)){
      $sql="select * from proprietario where j01_matric=$j01_matric";
      $result=pg_exec($sql);
      db_fieldsmemory($result,0);
      $result = $claverba->sql_record($claverba->sql_query("","max(j55_codave)","","j55_matric=$j01_matric"));
      $ultimo=pg_result($result,0,0); 
      $result = $claverba->sql_record($claverba->sql_query("","j55_codave#cgm.z01_nome as z01_nome2#j55_cidade#j55_data#j55_regimo#j55_numcgm","","j01_matric=$j01_matric and j55_codave=$ultimo"));
      if($claverba->numrows==0){
        db_redireciona("cad4_averba003.php?inexistente=true");
      }
      db_fieldsmemory($result,0);
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
  db_input('j01_matric',5,0,true,'text',3,"");
?>
          </td>
        </tr>
        <tr> 
          <td>          
           <?=$Lz01_nome?>
          </td>
         <td> 
<?
  db_input('z01_nome',45,$Iz01_nome,true,'text',3,"");
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
           <?=$Lj34_setor?>
          </td>
          <td> 
<?
  db_input('j34_setor',5,$Ij34_setor,true,'text',3,"");
  echo $Lj34_quadra;
  db_input('j34_quadra',5,$Ij34_quadra,true,'text',3,"");
  echo $Lj34_lote;
  db_input('j34_lote',5,$Ij34_lote,true,'text',3,"");
?>
          </td>
        </tr>
        <tr>
          <td>
             AVERBAÇÃO ATUAL
          </td>
        </tr>
<?
  db_input('j55_codave',5,$Ij55_codave,true,'hidden',3,"");
?> 
        <tr> 
          <td>     
<?
  db_ancora($Lz01_numcgm,' js_cgm(true); ',3);
?>
          </td>
          <td> 
<?
  db_input('j55_numcgm',5,$Ij55_numcgm,true,'text',3,"onchange='js_cgm(false)'");
  db_input('z01_nome',45,$Iz01_nome,true,'text',3,"","z01_nome2");
?>
          </td>
        </tr>
        <tr> 
          <td>     
           <?=$Lj55_data?>
          </td>
          <td> 
<?
  db_inputdata('j55_data',@$j55_data_dia,@$j55_data_mes,@$j55_data_ano,true,'text',3,"");
?>
          </td>
        </tr>
        <tr> 
          <td>     
           <?=$Lj55_cidade?>
          </td>
          <td> 
<?
  db_input('j55_cidade',25,$Ij55_cidade,true,'text',3,"");
?>
          </td>
        </tr>
        <tr> 
          <td>     
           <?=$Lj55_regimo?>
          </td>
          <td> 
<?
  db_input('j55_regimo',25,$Ij55_regimo,true,'text',3,"");
?>
          </td>
        </tr>
      </table>
      <input name="excluir" type="submit" id="excluir" value="Excluir">
      <input name="voltar" type="button" id="voltar" value="Voltar" onclick="js_volta();">
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
    location.href = 'cad4_averba003.php ';
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
    db_iframe.jan.location.href = 'func_nome.php?pesquisa_chave='+document.form1.j55_numcgm.value+'&funcao_js=parent.js_mostra';
  }
}
function js_mostra1(chave1,chave2){
  document.form1.j55_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe.hide();
}
function js_mostra(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true && erro!="1"){ 
    document.form1.j55_numcgm.focus();
    document.form1.j55_numcgm.value="";
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
if(isset($excluir)){
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
}
?>