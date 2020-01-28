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
    echo"<script>location.href='index.php?".base64_encode('erroscripts=3')."'</script>";
}
mens_help();
*/
db_mensagem("alvara_cab","alvara_rod");
include("classes/db_listainscrcab_classe.php");
include("classes/db_listainscr_classe.php");
include("classes/db_cgm_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);

$z01_numcgm = db_getsession("DB_login");

//echo "cgm = $cgmkk xx= $p12_codigo";
//$z01_numcgm= $cgmkk;

//echo $_SESSION['kk'];  
//echo $HTTP_SESSION_VARS["testee"];    
//$z01_numcgm = db_getsession("DB_login");
//$z01_numcgm = $HTTP_SESSION_VARS["DB_login"];
//echo $HTTP_SESSION_VARS["DB_login"];
//$cc= db_getsession("DB_login");
//echo"ccc=$cc";
//$z01_numcgm = $DB_login;
//############################ tirar depois #########################
//$z01_numcgm =10624;

//echo"cgm2 = $z01_numcgm xxx";
//###################################################################



/*
if(!isset($DB_LOGADO)){
$sql = "select fc_permissaodbpref(".db_getsession("DB_login").",1,".db_getsession("DB_login").")";
  $result1 = db_query($sql);
  if(pg_numrows($result1)==0){
    db_redireciona("centro_pref.php?".base64_encode("erroscripts=3"));
    exit;
  }
  $result1 = pg_result($result1,0,0);
  if($result1=="0"){
    db_redireciona("centro_pref.php?".base64_encode("erroscripts=3"));
    exit;
  }
  $z01_numcgm = db_getsession("DB_login");
}
*/
$cllistainscr = new cl_listainscr;
$cllistainscrcab = new cl_listainscrcab;
$db_opcao = 1;
$db_botao = true;
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir Inscrição na Lista"){
  $p12_cnpj = str_replace(".","",$p12_cnpj);
  $p12_cnpj = str_replace("/","",$p12_cnpj);
  $p12_cnpj = str_replace("-","",$p12_cnpj);  
  $HTTP_POST_VARS["p12_cnpj"] = $p12_cnpj;
  $result = $cllistainscr->sql_record($cllistainscr->sql_query("","","*",""," p12_codigo = $p12_codigo and trim(p12_cnpj) = '$p12_cnpj'"));
  if($cllistainscr->numrows > 0){
    $erro = true;
  }else{
    db_inicio_transacao();
    $cllistainscr->incluir($p12_codigo,$p12_inscr);
    db_fim_transacao();
  }
}elseif((isset($HTTP_POST_VARS["opcao"]) && $HTTP_POST_VARS["opcao"])=="Excluir"){
  db_inicio_transacao();
  $db_opcao = 2;
  $cllistainscr->excluir($p12_codigo_excluir,$p12_inscr_excluir);
  db_fim_transacao();
}elseif((isset($HTTP_POST_VARS["fechar"]))){
  db_inicio_transacao();
  $cllistainscrcab->p11_fechado = 't';
  $cllistainscrcab->p11_codigo = $p12_codigo;
  $cllistainscrcab->alterar($p12_codigo);
  db_fim_transacao();
}

if(isset($fechar)){
 
}

?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>
<script>
function js_vericampos(){
 var alerta="";
  jcnpj=document.form1.p12_cnpj.value;
  jinscricao=document.form1.p12_inscr.value;
  if(jinscricao==""){
    alerta+="Inscrição\n";
  }
  if(jcnpj==""){
    alerta +="CNPJ\n";
  }
  if(alerta!=""){
    alert("Verifique os seguintes campos:\n"+alerta);
    return false;
  }else{
    return true;
  }
}
function js_cgccpf(obj){
  js_verificaCGCCPF(obj,'');
}
function maiusculo(obj) {
  var maiusc = new String(obj.value);
  obj.value = maiusc.toUpperCase();
}
function js_cnpj(obj){
  var retorno = js_verificaCGCCPF(obj,'');
  if(retorno == false)
    obj.focus();
  else  
    document.submit();
}
</script>
<style type="text/css">
<?db_estilosite();
?>
</style>
<link href="config/estilos.css" rel="stylesheet" type="text/css">
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" onLoad="" <? mens_OnHelp() ?>>
<?
//mens_div();
?>
<center>
<form name="form1" method="post" action="">
              <table width="100%" border="0" cellspacing="0" cellpadding="0" class="texto">
                <tr> 
                  <td height="60" align="<?=$DB_align1?>">
                    <?=$DB_mens1?>
                  </td>
                </tr>
                <tr> 
                  <td align="center" valign="top">
                  <?
                            
                  $clcgm = new cl_cgm;
                 // $result = $clcgm->sql_record($clcgm->sql_query($z01_numcgm));
                  $sql = "select * from cgm where z01_numcgm=$z01_numcgm";
                  $result=db_query($sql);
                  $linhas=pg_num_rows($result);
                
                  
                  if($linhas > 0 ){
                    db_fieldsmemory($result,0);
                    echo "<p><font size='2'><strong>Escritório Contábil</strong> ".@$z01_nome."</font></p>";
                  ?>
                    <table width="80%" border="1" cellspacing="0" cellpadding="0" class="texto">
                      <tr bgcolor="#3366cc"> 
                        <td align="center" nowrap colspan="2">
                          <strong>Dados da Inscrição</strong>
                        </td>
                      </tr>
                      <tr> 
                        <td align="left" nowrap  colspan="2">
                          <table width="100%" border="0" cellspacing="2" cellpadding="0">
                            <tr>
                              <td align='right'>
                                <strong>Inscrição:</strong>
                              </td>
                              <td>
                                <input type="text" name="p12_inscr" title="clique no botão ao lado para pesquisar as inscrição" size="6" >
                                <input type="button" name="pesquisa" class="botao" value="Busca Dados" onClick="(document.form1.p12_inscr.value == ''?alert('Preencha uma inscrição'):js_inscr())"><br>
                              </td>
                            </tr>
                            <tr>
                              <td align="right">
                                <strong>Nome:</strong>
                              </td>
                              <td colspan="2">
                                <input type="text" name="z01_nome"  size="40" readonly>
                              </td>
                            </tr>
                            <tr>
                              <td align="right">
                                <strong> CNPJ:</strong>
                              </td>
                              <td>
                                <input type="text" name="p12_cnpj" size="18" maxlength="18"  onKeyDown="FormataCNPJ(this, event)" onBlur="(this.value == '')?'':js_cgccpf(this)">
                                <strong> FONE:</strong>
                                <input type="text" name="p12_fone" size="13" >
                              </td>
                            </tr>
                            <input type="hidden" name="p12_codigo" value="<?=@$p12_codigo?>">
                            <tr height="25">
                              <td width="90">
                              </td>
                              <td align="left" colspan="3" nowrap>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="submit" name="db_opcao" class="botao" value="Incluir Inscrição na Lista" onClick="return js_vericampos()">
                              </td>
                              <td align="right">
                                <input type="submit" name="fechar" class="botao" value="Fechar Lista" <?=(isset($p12_codigo) && $p12_codigo != ""?"":"disabled")?> onClick="return confirm('Após fechar a lista ela não pode mais ser alterada\ndeseja fechar a lista?')"><br>
                              </td>
                            </tr>
                          </table>  
                                
      <script>
      function js_inscr(){
        js_OpenJanelaIframe('','db_iframe_inscr','func_issbase.php?funcao_js=parent.js_inscricao&pesquisa_chave='+document.form1.p12_inscr.value+'&z01_numcgm=<?=$z01_numcgm?>','Pesquisa',false);
      }
      function js_inscricao(chave,chave1,chave2){
        document.form1.z01_nome.value = chave; 
        document.form1.p12_cnpj.value = chave1;
        if(chave2 == true){  
          document.form1.p12_inscr.value = ''; 
          document.form1.p12_inscr.focus();
        }
        if(chave1 != ""){
          document.form1.p12_cnpj.focus(); 
        }
      }
      onLoad = document.form1.p12_inscr.focus();
      </script>
                        </td>
                      </tr>
                      <tr>
                        <td align="center">
                          <fieldset style="border: 1px solid black">
                          <legend align="center"><strong>Inscrições da lista</strong></legend>
                          <iframe frameborder="0" scrolling="auto" src="inscrlista.php?<?=base64_encode('p12_codigo='.$p12_codigo)?>" name="inscricoes" width="100%" height="200">
                          </iframe>
                          </fieldset>
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
</center>
</body>
</html>
<?
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir Inscrição na Lista"){
  //$cllistainscr->erro(true,false);
  if($erro == true){
    echo "<script>alert('Este CNPJ já esta cadastrado na lista\\nverifique!')</script>";
  }
  db_redireciona("listaescritorios001.php?".base64_encode('p12_codigo='.($cllistainscr->p12_codigo == ""?$p12_codigo:$cllistainscr->p12_codigo))."");
}elseif((isset($HTTP_POST_VARS["opcao"]) && $HTTP_POST_VARS["opcao"])=="Excluir"){
  //$cllistainscr->erro(true,false);
  db_redireciona("listaescritorios001.php?".base64_encode('p12_codigo='.$p12_codigo_excluir)."");
}elseif((isset($HTTP_POST_VARS["fechar"]))){
  //$cllistainscrcab->erro(true,false);
  db_redireciona("listaescritorios.php");
}
db_logs("","",0,"Digita inscrições na Lista de inscrições do escritório.");
?>