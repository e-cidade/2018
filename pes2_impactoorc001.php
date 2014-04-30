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
include("classes/db_rhpessoal_classe.php");
include("classes/db_rhpessoalmov_classe.php");
include("classes/db_rhregime_classe.php");
include("classes/db_rhpespadrao_classe.php");
include("dbforms/db_classesgenericas.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clrhpessoal = new cl_rhpessoal;
$clrhpessoalmov = new cl_rhpessoalmov;
$clrhpespadrao = new cl_rhpespadrao;
$clrhregime = new cl_rhregime;
$clrhpessoalmov->rotulo->label();
$rotulocampo = new rotulocampo;
$rotulocampo->label("DBtxt23");
$rotulocampo->label("DBtxt25");
$rotulocampo->label('rh30_regime');
$rotulocampo->label('rh30_descr');
$rotulocampo->label('rh30_vinculo');
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
  qry += '&padrao='+document.form1.rh03_padrao.value;
  qry += '&selecao='+document.form1.selecao.value;
  qry += '&tiposa='+document.form1.tiposa.value;
  qry += "&selec="+ selecionados;
  
  if(document.form1.selcargo){
    if(document.form1.selcargo.length > 0){
      faixacargo = js_campo_recebe_valores();
      qry+= "&fca="+faixacargo;
    }
  }else if(document.form1.cargoi){
    carini = document.form1.cargoi.value;
    carfim = document.form1.cargof.value;
    qry+= "&cai="+carini;
    qry+= "&caf="+carfim;
  }
  jan = window.open('pes2_impactoorc002.php' + qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}

</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

  <table  align="left">
      <form name="form1" method="post" action="" >
  <table  align="center">
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
        <?
if(!isset($tipo)){
   $tipo = "c";
 }
if(!isset($filtro)){
   $filtro = "i";
}
  $gform->tipores = true;
  $gform->usacarg = true;                      // PERMITIR SELEÇÃO DE Cargo
  $gform->selecao = true;
  $gform->manomes = true;
  $gform->ca1nome = "cargoi";                  // NOME DO CAMPO DO CARGO INICIAL
  $gform->ca2nome = "cargof";                  // NOME DO CAMPO DO CARGO FINAL
  $gform->ca3nome = "selcargo";
  $gform->ca4nome = "Cargo";
  $gform->resumopadrao = "g";                  // TIPO DE RESUMO PADRÃO
  $gform->filtropadrao = "i";
  $gform->strngtipores = "gc";              // OPÇÕES PARA MOSTRAR NO TIPO DE RESUMO g - geral,
  $gform->onchpad = true;                      // MUDAR AS OPÇÕES AO SELECIONAR OS TIPOS DE FILTRO OU RESUMO
      $gform->gera_form(db_anofolha(),db_mesfolha());
      ?>

</table>
<table  align="center">
  <tr>
    <td colspan="2" >
          <fieldset>
            <Legend align="left">
              <b>Selecione os Vinculos</b>
            </Legend>
            <?
            db_input("valor", 3, 0, true, 'hidden', 3);
            db_input("colunas_sselecionados", 3, 0, true, 'hidden', 3);
            db_input("colunas_nselecionados", 3, 0, true, 'hidden', 3);
             if(!isset($result_regime)){
                $result_regime = $clrhregime->sql_record($clrhregime->sql_query_file(null, "rh30_codreg, rh30_codreg||'-'||rh30_descr as rh30_descr", "rh30_codreg" , " rh30_instit = ".db_getsession('DB_instit') ));
                for($x=0; $x<$clrhregime->numrows; $x++){
                     db_fieldsmemory($result_regime,$x);
                     $arr_colunas[$rh30_codreg]= $rh30_descr;
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
             db_multiploselect("rh30_codreg","rh30_descr", "nselecionados", "sselecionados", $arr_colunas_inicial, $arr_colunas_final, 6, 250, "", "", true, "js_complementar('c');");
             ?>
          </fieldset>
    </td>
  </tr>
      <tr>
          <td nowrap title="Padrão" align="right">
            <?
            $db_opcao = 2;
            db_ancora("<B>Código do Padrão :</B>","js_pesquisarh03_padrao(true);",$db_opcao);
            ?>
          </td>
          <td nowrap>
            <?
            db_input('rh03_padrao',10,"",true,'text',$db_opcao,"onchange='js_pesquisarh03_padrao(false);'")
            ?>
            <?
            db_input('r02_descr',29,"",true,'text',3,'');
            ?>
          </td>
        </tr>

      <tr>
	     <td align="right"><strong>Tipo de Relatório :</strong>&nbsp;
       </td>
       <td align="left">
         <?
           $x = array("f"=>"Sintético","t"=>"Analítico");
           db_select('tiposa',$x,true,4,"");
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

</table>
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