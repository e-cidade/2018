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
include("dbforms/db_classesgenericas.php");
include("classes/db_gerfcom_classe.php");
include("classes/db_orctiporec_classe.php");
$aux = new cl_arquivo_auxiliar;
$clrotulo = new rotulocampo;
$clgerfcom = new cl_gerfcom;
$clorctiporec = new cl_orctiporec;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
$clrotulo->label('DBtxt27');
$clrotulo->label('DBtxt28');
db_postmemory($HTTP_POST_VARS);
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_emite(){
  recurso = js_campo_recebe_valores();
  complementar = "";
  if(document.form1.complementar){
    complementar = "&comp="+document.form1.complementar.value;
  }
  jan = window.open('pes2_sapconfreceitas002.php?recurso='+recurso+complementar+'&ponto='+document.form1.ponto.value+'&ano='+document.form1.DBtxt23.value+'&mes='+document.form1.DBtxt25.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="25%" height="18">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
  </tr>
</table>
<table  align="center">
  <form name="form1" method="post" action="">
  <tr>
    <td align="right" nowrap title="Digite o Ano / Mes de competência" >
      <strong>Ano / Mês:</strong>
    </td>
    <td>
      <?
      if(!isset($DBtxt23) || (isset($DBtxt23) && (trim($DBtxt23) == "" || $DBtxt23 == 0))){
        $DBtxt23 = db_anofolha();
      }
      db_input('DBtxt23',4,$IDBtxt23,true,'text',2,'')
      ?>
      &nbsp;/&nbsp;
      <?
      if(!isset($DBtxt25) || (isset($DBtxt25) && (trim($DBtxt25) == "" || $DBtxt25 == 0))){
        $DBtxt25 = db_mesfolha();
      }
      db_input('DBtxt25',2,$IDBtxt25,true,'text',2,'')
      ?>
    </td>
  </tr>
  <tr>
    <td align="right"><b>Cálculo:</b</td>
    <td>
      <?
      $x = array("s"=>"Salário","c"=>"Complementar","d"=>"13o. Salário","r"=>"Rescisão","a"=>"Adiantamento");
      db_select('ponto',$x,true,4,"onchange='js_submita(this.value);'");
      ?>
    </td>
  </tr>
  <?
  if(isset($ponto) && $ponto == "c"){
    $result_gerfcom = $clgerfcom->sql_record($clgerfcom->sql_query_file($DBtxt23,$DBtxt25,null,null,"distinct r48_semest,r48_semest"));
    if($clgerfcom->numrows > 0){
  ?>
  <tr>
    <td align="right"><b>Complementar:</b</td>
    <td>
     <?
     db_selectrecord('complementar', $result_gerfcom, true, 4, "", "", "", Array("0","Todos..."), "", 1);
     ?>
    </td>
  </tr>
  <?
    }else{
  ?>
  <tr>
    <td align="center" colspan="2">
      <b><font color="red">Sem complementar para este período.</font></b>
      <?
      db_input('complementar',1,0,true,'hidden',3,'')
      ?>
    </td>
  </tr>
  <?
    }
  }
  ?>
  <tr>
    <td colspan=2>
      <?
      $sql_exec = "";
      if(isset($lista) && count($lista) > 0){
        $in_ = "";
        for($i=0;$i<count($lista); $i++){
          $in_.= ($i==0?"":",").$lista[$i];
        }
        $sql_exec = $clorctiporec->sql_query_file(null,"o15_codigo,o15_descr","o15_codigo","o15_codigo in (".$in_.")");
      }
      $aux->cabecalho  = "<strong>MATRÍCULAS SELECIONADAS</strong>";
      $aux->codigo = "o15_codigo"; //chave de retorno da func
      $aux->descr  = "o15_descr";   //chave de retorno
      $aux->nomeobjeto = 'lista';
      $aux->funcao_js = 'js_mostra';
      $aux->funcao_js_hide = 'js_mostra1';
      $aux->sql_exec  = $sql_exec;
      $aux->func_arquivo = "func_orctiporec.php";  //func a executar
      $aux->nomeiframe = "db_iframe_lista";
      $aux->db_opcao = 2;
      $aux->tipo = 2;
      $aux->top = 0;
      $aux->linhas = 5;
      $aux->vwhidth = 300;
      $aux->obrigarselecao = false;
      $aux->funcao_gera_formulario();
      ?>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align = "center"> 
      <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
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
function js_submita(valor){
  if(valor == 'c' || document.form1.complementar){
    document.form1.o15_codigo.value = "";
    document.form1.o15_descr.value  = "";
    for(var i=0; i<document.form1.lista.length; i++){
      document.form1.lista.options[i].selected = true;
    }
    document.form1.submit();
  }
}
js_trocacordeselect();	
</script>