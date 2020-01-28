<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
include("classes/db_tabcurritipo_classe.php");
include("dbforms/db_classesgenericas.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$cltabcurritipo = new cl_tabcurritipo;
$rotulocampo = new rotulocampo;
$rotulocampo->label('h02_codigo');
$rotulocampo->label('h02_descr');
$gform = new cl_formulario_rel_pes;
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>

function js_emite(){
  selecionados = "";
  virgula_ssel = "";
  for(var i=0; i<document.form1.sselecionados.length; i++){
    selecionados+= virgula_ssel + document.form1.sselecionados.options[i].value;
    virgula_ssel = ",";
  }
  qry  = '?ano='+document.form1.anofolha.value;
  qry += '&mes='+document.form1.mesfolha.value;
  qry += '&tiposa='+document.form1.tiposa.value;
  qry += '&ordem='+document.form1.ordem.value;
  qry += "&selec="+ selecionados;
  
  jan = window.open('rec2_tabcurritipo002.php' + qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}

</script>  
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
      <form name="form1" method="post" action="" >
<center>
<table border="0" >
  <tr>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
      <tr >
        <td align="right" nowrap title="Digite o Ano / Mes de competência" >
        <strong>Ano / Mês :&nbsp;&nbsp;</strong>
        </td>
        <td align="left">
          <?
           if(!isset($anofolha) || (isset($anofolha) && trim($anofolha) == "")){
             $anofolha = db_anofolha();
           }
           if(!isset($mesfolha) || (isset($mesfolha) && trim($mesfolha) == "")){
             $mesfolha = db_mesfolha();
           }
           db_input('anofolha',4,'',true,'text',2,'')
          ?>
          &nbsp;/&nbsp;
          <?
           $DBtxt25 = db_mesfolha();
           db_input('mesfolha',2,'',true,'text',2,'')
          ?>
        </td>
      </tr>

<table  align="center" border="0">
  <tr>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" >
          <fieldset>
            <Legend align="left">
              <b>Tipos de Curso</b>
            </Legend>
            <?
            db_input("valor", 3, 0, true, 'hidden', 3);
            db_input("colunas_sselecionados", 3, 0, true, 'hidden', 3);
            db_input("colunas_nselecionados", 3, 0, true, 'hidden', 3);
             if(!isset($result_regime)){
                $result_tabcurritipo = $cltabcurritipo->sql_record($cltabcurritipo->sql_query_file(null, "h02_codigo, lpad(h02_codigo,2,'0')||'-'||h02_descr as h02_descr", "h02_codigo" ));
                for($x=0; $x<$cltabcurritipo->numrows; $x++){
                     db_fieldsmemory($result_tabcurritipo,$x);
                     $arr_colunas[$h02_codigo]= $h02_descr;
                }
              }
              $arr_colunas_final   = Array();
              $arr_colunas_inicial = Array();
              if(isset($colunas_sselecionados) && $colunas_sselecionados != ""){
                 $colunas_sselecionados = split(",",$colunas_sselecionados);
                 for($Ic=0;$Ic < count($colunas_sselecionados);$Ic++){
                    $arr_colunas_final[$colunas_sselecionados[$Ic]] = $arr_colunas[$colunas_sselecionados[$Ic]]; 
                 }
              }
              if(isset($colunas_nselecionados) && $colunas_nselecionados != ""){
                 $colunas_nselecionados = split(",",$colunas_nselecionados);
                 for($Ic=0;$Ic < count($colunas_nselecionados);$Ic++){
                    $arr_colunas_inicial[$colunas_nselecionados[$Ic]] = $arr_colunas[$colunas_nselecionados[$Ic]]; 
                 }
              }
              if(!isset($colunas_sselecionados) || !isset($colunas_sselecionados) || $colunas_sselecionados == ""){
                 $arr_colunas_final  = Array();
                 $arr_colunas_inicial = $arr_colunas;
              }
             db_multiploselect("h02_codigo","h02_descr", "nselecionados", "sselecionados", $arr_colunas_inicial, $arr_colunas_final, 6, 250, "", "", true );
             ?>
          </fieldset>
    </td>
  </tr>
      <tr>
	     <td align="right"><strong>Tipo de Relatório :</strong>&nbsp;
       </td>
       <td align="left">
         <?
           $x = array("a"=>"Analítico","s"=>"Sintético");
           db_select('tiposa',$x,true,4,"");
         ?>
  	   </td>
      </tr>
      <tr>
	     <td align="right"><strong>Ordem :</strong>&nbsp;
       </td>
       <td align="left">
         <?
           $xy = array("c"=>"Curso","a"=>"Alfabética");
           db_select('ordem',$xy,true,4,"");
         ?>
  	   </td>
      </tr>
      <tr>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align = "center"> 
          <input  name="relatorio" id="relatorio" type="button" value="Relatório" onclick="js_emite();" >
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
function js_pesquisarh03_padrao(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('top.corpo','db_iframe_padroes','func_padroes.php?funcao_js=parent.js_mostrapadrao1|r02_codigo|r02_descr&chave_r02_anousu='+document.form1.anofolha.value+'&chave_r02_mesusu='+document.form1.mesfolha.value,'Pesquisa',true,'0');
    }else{
      if(document.form1.rh03_padrao.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_padroes','func_padroes.php?pesquisa_chave='+document.form1.rh03_padrao.value+'&funcao_js=parent.js_mostrapadrao&chave_r02_anousu='+document.form1.anofolha.value+'&chave_r02_mesusu='+document.form1.mesfolha.value,'Pesquisa',false,'0');
      }else{
        document.form1.rh03_padrao.value = '';
        document.form1.r02_descr.value  = '';
      }
    }  
}
function js_mostrapadrao(chave,erro){
  document.form1.r02_descr.value = chave; 
  if(erro==true){ 
    document.form1.rh03_padrao.focus(); 
    document.form1.rh03_padrao.value = ''; 
  }
}
function js_mostrapadrao1(chave1,chave2){
  document.form1.rh03_padrao.value = chave1;
  document.form1.r02_descr.value  = chave2;
  db_iframe_padroes.hide();
}
function js_complementar(opcao){
  selecionados = "";
  virgula_ssel = "";

  for(var i=0; i<document.form1.sselecionados.length; i++){
    selecionados+= virgula_ssel + document.form1.sselecionados.options[i].value;
    virgula_ssel = ",";
  }
  document.form1.colunas_sselecionados.value = selecionados;

  selecionados = "";
  virgula_ssel = "";
  for(var i=0; i<document.form1.nselecionados.length; i++){
    selecionados+= virgula_ssel + document.form1.nselecionados.options[i].value;
    virgula_ssel = ",";
  }
  document.form1.colunas_nselecionados.value = selecionados;

}
</script>