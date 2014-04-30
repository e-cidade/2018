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
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
$clrotulo->label('DBtxt27');
$clrotulo->label('DBtxt28');
$clrotulo->label('rh27_rubric');
$clrotulo->label('rh27_descr');
db_postmemory($HTTP_POST_VARS);
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
<form name="form1">
<center>
<table>
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
  <?
  if(!isset($tipo)){
    $tipo = "l";
  }
  if(!isset($filtro)){
    $filtro = "i";
  }
  if(!isset($anofolha) || (isset($anofolha) && trim($anofolha) == "")){
    $anofolha = db_anofolha();
  }
  if(!isset($mesfolha) || (isset($mesfolha) && trim($mesfolha) == "")){
    $mesfolha = db_mesfolha();
  }
  ?>
  <tr>
    <td colspan="2" >
      
          <fieldset>
            <Legend align="left" style='color:#FF0000'>
              <b>Lancamento do PASEP para a folha</b>
            </Legend>
      <table>      
      <tr >
        <td align="left" nowrap title="Digite o Ano / Mes de competência" >
        <strong>Ano / Mês :&nbsp;&nbsp;</strong>
        </td>
        <td>
          <?
           $ano1 = db_anofolha();
           db_input('ano1',4,$IDBtxt23,true,'text',3,'')
          ?>
          &nbsp;/&nbsp;
          <?
           $mes1 = db_mesfolha();
           db_input('mes1',2,$IDBtxt25,true,'text',3,'')
          ?>
        </td>
      </tr>
    <tr>
      <td align="right" title="<?=$Trh27_rubric?>"> 
        <?
        db_ancora($Lrh27_rubric,'js_pesquisarh27_rubric(true);',2)
        ?>
      </td>
      <td> 
        <?
        db_input("rh27_rubric",8,$Irh27_rubric,true,'text',4,"onchange='js_pesquisarh27_rubric(false);'")
        ?>
        <?
        db_input("rh27_descr",40,$Irh27_descr,true,'text',3)
        ?>
      </td>
    </tr>
      <tr>
        <td align="right"><strong>Ponto:</strong>
        </td>
        <td>
          <?
          $arr_ponto = array("s"=>"Salário", "c"=>"Complementar");
          db_select('ponto',$arr_ponto,true,4);
          ?>
       </td>
     </tr>
</table>
</fieldset>

<center>
<table>
    <td colspan="2" align = "center"> 
      <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite(1);" >
    </td>
</table>
</center>
  </tr>
</table>
</center>
</form>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
var qual_rub = 0;
function js_emite(inserir){
  qry = "ano1="+document.form1.ano1.value;
  qry+= "&mes1="+document.form1.mes1.value;
  qry+= "&ponto="+document.form1.ponto.value;
  qry+= "&rh27_rubric="+document.form1.rh27_rubric.value;

 js_OpenJanelaIframe('top.corpo','db_iframe_rubinclref001','pes4_rhfopag96002.php?'+qry,'Gerando Arquivo',true);
//jan = window.open('pes4_rubinclref002.php'+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
//jan.moveTo(0,0);

}
function js_verificar(){
  if(document.form1.rh27_rubric.value == ""){
    alert("Informe o código da rubrica");
    document.form1.rh27_rubric.focus();
    return false;
  }
  return true;
}
function js_pesquisarh27_rubric(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?funcao_js=parent.js_mostrarubricas1|rh27_rubric|rh27_descr&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',true);
  }else{
       if(document.form1.rh27_rubric.value != ''){
         js_completa_rubricas(document.form1.rh27_rubric);
         js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?pesquisa_chave='+document.form1.rh27_rubric.value+'&funcao_js=parent.js_mostrarubricas&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',false);
       }else{
         document.form1.rh27_rubric.value = '';
         document.form1.rh27_descr.value  = '';
       }
  }
}
function js_mostrarubricas(chave,erro){
    document.form1.rh27_descr.value = chave;
  if(erro == true){
    document.form1.rh27_rubric.value = "";
    document.form1.rh27_rubric.focus();
  }
}
function js_mostrarubricas1(chave1,chave2){
    document.form1.rh27_rubric.value = chave1;
    document.form1.rh27_descr.value  = chave2;
  db_iframe_rhrubricas.hide();
}
function js_erro(msg){
  top.corpo.db_iframe_rubinclref001.hide();
  if(msg.substr(0,6) == 'Existe'){
    if(confirm(msg)){
      js_emite(2);    
    }
  }else{
    alert(msg);
  }
}
function js_limpa(){
  document.form1.reset();
}
</script>