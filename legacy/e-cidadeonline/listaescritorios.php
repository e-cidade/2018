<?
/*
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

session_start();
 
include("libs/db_conecta.php");
include("libs/db_stdlib.php");
include("libs/db_sql.php");
include("dbforms/db_funcoes.php");
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
$result = db_query("SELECT distinct m_publico,m_arquivo,m_descricao
                       FROM db_menupref 
                       WHERE m_arquivo = 'listaescritorios.php'
                       ORDER BY m_descricao
                       ");
db_fieldsmemory($result,0);

/*
if($m_publico != 't'){
  if(!session_is_registered("DB_acesso"))
    echo"<script>location.href='centro_pref.php?".base64_encode('erroscripts=3')."'</script>";
}
mens_help();
*/
db_mensagem("alvara_cab","alvara_rod");
include("classes/db_listainscrcab_classe.php");
include("classes/db_cgm_classe.php");
db_postmemory($HTTP_POST_VARS);
 
if(!isset($DB_LOGADO)){
$z01_numcgm = db_getsession("DB_login");
$cgm = $_SESSION["CGM"];
//echo"<br>numcgm = $z01_numcgm";
//echo"<br>cgm = $cgm";
 }
if ($z01_numcgm!=""){
	
$z01_numcgm= @$id_usuario;
//echo "$z01_numcgm";
$cllistainscrcab = new cl_listainscrcab;
$db_opcao = 1;
$db_botao = true;
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir Nova Lista"){
  db_inicio_transacao();
  $cllistainscrcab->incluir($p11_codigo);
  db_fim_transacao();
  //$cllistainscrcab->erro(true,false);
  db_redireciona("listaescritorios001.php?".base64_encode('p12_codigo='.$cllistainscrcab->p11_codigo)."");
}elseif((isset($HTTP_POST_VARS["alterar"]))){//die("kakaka");
  // echo "<script>location.href='listaescritorios001.php?p12_codigo=$p11_codigo_alterar&cgmkk='+document.form1.p11_numcgm.value;</script>";
  db_redireciona("listaescritorios001.php?".base64_encode('p12_codigo='.$p11_codigo_alterar)."");
}elseif((isset($HTTP_POST_VARS["opcao"]))){
  db_inicio_transacao();
  $cllistainscrcab->p11_fechado = 't';
  $cllistainscrcab->p11_codigo = $p11_codigo_fechar;
  $cllistainscrcab->alterar($p11_codigo_fechar);
  db_fim_transacao();
  //$cllistainscrcab->erro(true,true);
}
?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>
<script>
function maiusculo(obj) {
  var maiusc = new String(obj.value);
  obj.value = maiusc.toUpperCase();
}
function js_contato() {
  if(document.form1.p11_contato.value == ""){
    alert('Preencha o campo contato'); 
    document.form1.p11_contato.focus();
    return false;
  }else{
    return true;
  }
return false;  
}
</script>
<style type="text/css">
<?db_estilosite();
?>
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" onLoad="" <? mens_OnHelp() ?>>
<?
//mens_div();
?>
<center>
<form name="form1" action="" method="post">

              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td height="60" align="<?=$DB_align1?>">
                    <?=$DB_mens1?>
                  </td>
                </tr>
                <tr> 
                  <td align="center" valign="top">
                  <?
                  $clcgm = new cl_cgm;
                  $result = $clcgm->sql_record($clcgm->sql_query($z01_numcgm));
                  //die($clcgm->sql_query($z01_numcgm));
                  if($clcgm->numrows > 0 ){
                    db_fieldsmemory($result,0);
                    echo "<p><font size='2'><strong>Escritório Contábil</strong> ".@$z01_nome."</font></p>";
                  
                  $sqllista = "select * from listainscrcab where p11_numcgm=$z01_numcgm";
                  
                 // die($sqllista);
                  $result= db_query($sqllista);
                  $linhas = pg_num_rows($result);
                  if ($linhas > 0){ // se existir lista ... verificar se tem lista em aberto
                  	  $sql = "select * from listainscrcab where p11_numcgm=$z01_numcgm and p11_fechado is false and p11_processado is false";
                      $result= db_query($sql);
	                  $linhas2= pg_num_rows($result);
                  	  //db_fieldsmemory($result,0);
	                  if($linhas == 0 ){
	                   	  $mostra_contato= 't';
	                  }	  
                  }else{ // se não tiver nenhuma lista...se for a primeira lista
                   	  $mostra_contato= 't';
                  }
                  ?>
                    
                    <table width="80%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td align="right" width="50">
                       <? 
                        if(isset($mostra_contato) && $mostra_contato== 't' ){
                  	?>
                 	
                          <strong>Contato:</strong>
                        </td>
                        <td>
                          <input type="hidden" name="p11_codigo" value="" >
                          <input type="hidden" name="p11_numcgm" value="<?=$z01_numcgm?>" >
                          <input type="hidden" name="p11_data_dia" value="<?=date('d')?>" >
                          <input type="hidden" name="p11_data_mes" value="<?=date('m')?>" >
                          <input type="hidden" name="p11_data_ano" value="<?=date("Y")?>" >
                          <input type="hidden" name="p11_hora" value="<?=db_hora()?>" >
                          <input type="hidden" name="p11_fechado" value="f" >
                          <input type="hidden" name="p11_processado" value="f" >
                          <input type="text" name="p11_contato" size="30" onKeyUp="maiusculo(this)">
                          <input name="db_opcao" value ="Incluir Nova Lista" type="submit" onClick="return js_contato()">
                        </td>
                       
                       <?
                         }
                       ?>
                      </tr>
                    </table>
                    <p>
                    </p>
                    <table width="80%" class="tab">
                      <tr bgcolor="#3366cc"> 
                        <th align="center" nowrap colspan="3">
                          <strong>Lista(s) do escritório</strong>
                        </th>
                      </tr>
                      <tr>
                        <td align="center" colspan="3">
                          <!--<fieldset style="border: 1px solid black">
                          <legend align="center"><strong>Inscrições da lista</strong></legend>-->
                          <iframe frameborder="0" scrolling="auto" src="escritolista.php?<?=base64_encode('z01_numcgm='.$z01_numcgm)?>" name="inscricoes" width="100%" height="200">
                          </iframe>
                          <!--</fieldset>-->
                        </td>
                      </tr>
                  <?
                  }
                  ?>
                  
                  </td>
                </tr>
              </table>
                <tr> 
                  <td height="60" align="<?=$DB_align2?>">
                    <?=$DB_mens2?>
                  </td>
                </tr>
                      
            </td>
      </tr>
      </table>
</form>
<script>
onLoad = document.form1.p11_contato.focus(); 
</script>
</center>
</body>
<?
db_logs("","",0,"Listas do escritório.");
}else{
	msgbox("Somente para usuários logados.");
}
?>