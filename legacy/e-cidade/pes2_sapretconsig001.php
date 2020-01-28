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
include("classes/db_folha_classe.php");
include("classes/db_selecao_classe.php");
include("classes/db_gerfsal_classe.php");
include("classes/db_gerfadi_classe.php");
include("classes/db_gerffer_classe.php");
include("classes/db_gerfres_classe.php");
include("classes/db_gerfs13_classe.php");
include("classes/db_gerfcom_classe.php");
include("classes/db_gerffx_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clfolha = new cl_folha;
$clselecao = new cl_selecao;
$clgerfsal = new cl_gerfsal;
$clgerfadi = new cl_gerfadi;
$clgerffer = new cl_gerffer;
$clgerfres = new cl_gerfres;
$clgerfs13 = new cl_gerfs13;
$clgerfcom = new cl_gerfcom;
$clgerffx  = new cl_gerffx;
$clrotulo = new rotulocampo;
$clrotulo->label('r90_valor');
$clrotulo->label('r48_semest');
$db_opcao = 1;
$db_botao = true;
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
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
      <form name="form1" method="post" action="">
      <center>
      <table border="0">
        <tr>
          <td nowrap colspan="2" align="center">
          <?
          db_input("folhaselecion", 3, 0, true, 'hidden', 3);
          $arr_pontosgerfs_inicial = Array();
          $arr_pontosgerfs_final   = Array();
          $arr_pontos = Array(
                              "0" =>"Salário",
                              "1" =>"Adiantamento",
                              "2" =>"Férias",
                              "3" =>"Rescisão",
                              "4" =>"Saldo do 13o",
                              "5" =>"Complementar"
                             );
          if(isset($objeto1)){
            foreach ($objeto1 as $index) {
              $arr_pontosgerfs_inicial[$index] = $arr_pontos[$index];
            }
          }else{
            $arr_pontosgerfs_inicial = $arr_pontos;
          }
          if(isset($objeto2)){
            foreach ($objeto2 as $index) {
              $arr_pontosgerfs_final[$index] = $arr_pontos[$index];
            }
          }
          db_multiploselect("valor","descr", "", "", $arr_pontosgerfs_inicial, $arr_pontosgerfs_final, 6, 250, "", "", true, "js_complementar();");
          ?>
          </td>
        </tr>
        <?
        if(isset($objeto2) && count($objeto2) > 0 && isset($arr_pontosgerfs_final[5])){
          $result_semest = $clgerfcom->sql_record($clgerfcom->sql_query_file($anofolha,$mesfolha,null,null,"distinct r48_semest"));
          if($clgerfcom->numrows > 0){
            echo "
                  <tr>
                    <td align='right'><strong>Nro. Complementar:</strong></td>
                    <td>
                      <select name='r48_semest'>
                        <option value = 'T'>Todos
                      ";
                      for($i=0; $i<$clgerfcom->numrows; $i++){
                        db_fieldsmemory($result_semest, $i);
                        echo "<option value = '$r48_semest'>$r48_semest";
                      }
            echo "
                    </td>
                  </tr>
                 ";
          }else{
            echo "
                  <tr>
                    <td colspan='2' align='center'>
                      <font color='red'>Sem complementar para este período.</font>
                    </td>
                  </tr>
                 ";
          }
        }
        include("dbforms/db_classesgenericas.php");
        $geraform = new cl_formulario_rel_pes;
        if(!isset($anofolha) || (isset($anofolha) && trim($anofolha) == "")){
          $anofolha = db_anofolha();
        }
        if(!isset($mesfolha) || (isset($mesfolha) && trim($mesfolha) == "")){
          $mesfolha = db_mesfolha();
        }
        $geraform->gera_form($anofolha,$mesfolha);
        ?>
        <tr>
          <td nowrap align="left">
            <table>
              <?
              $aux = new cl_arquivo_auxiliar;
              $aux->cabecalho = "<strong>RUBRICAS SELECIONADOS</strong>";
              $aux->codigo = "rh27_rubric";
              $aux->descr  = "rh27_descr";
              $aux->nomeobjeto = "selrubri";
              $aux->funcao_js = 'js_geraform_mostrarub';
              $aux->funcao_js_hide = 'js_geraform_mostrarub1';
              $aux->func_arquivo = "func_rhrubricas.php";
              $aux->nomeiframe = "db_iframe_rhrubricas";
              $aux->executa_script_apos_incluir = "document.form1.rh27_rubric.focus();";
              $aux->executa_script_lost_focus_campo = "js_insSelectselrubri()";
              $aux->executa_script_change_focus = "document.form1.rh27_rubric.focus();";
              $aux->mostrar_botao_lancar = false;
              $aux->db_opcao = 2;
              $aux->tipo = 2;
              $aux->top = 20;
              $aux->linhas = 10;
              $aux->vwidth = "400";
              $aux->tamanho_campo_descricao = 38;
	      $aux->completar_com_zeros_codigo = true;
              $aux->funcao_gera_formulario();
              ?>
            </table>
          </td>
          <td nowrap align="left">
            <table>
              <?
              $aux = new cl_arquivo_auxiliar;
              $aux->cabecalho = "<strong>RECURSOS SELECIONADOS</strong>";
              $aux->codigo = "o15_codigo";
              $aux->descr  = "o15_descr";
              $aux->nomeobjeto = "recursos";
              $aux->funcao_js = 'js_geraform_mostrarec';
              $aux->funcao_js_hide = 'js_geraform_mostrarec1';
              $aux->func_arquivo = "func_orctiporec.php";
              $aux->nomeiframe = "db_iframe_orctiporec";
              $aux->executa_script_apos_incluir = "document.form1.o15_codigo.focus();";
              $aux->executa_script_lost_focus_campo = "js_insSelectrecursos()";
              $aux->executa_script_change_focus = "document.form1.o15_codigo.focus();";
              $aux->mostrar_botao_lancar = false;
              $aux->db_opcao = 2;
              $aux->tipo = 2;
              $aux->top = 20;
              $aux->linhas = 10;
              $aux->vwidth = "400";
              $aux->tamanho_campo_descricao = 38;
              $aux->funcao_gera_formulario();
              ?>
            </table>
          </td>
        </tr>
      </table>
      </center>
      <input name="incluir" type="button" id="db_opcao" onclick="js_enviardados();" value="Gerar">
      </form>
    </center>
    </td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_complementar(){
  valor = 0;
  for(i=0; i<document.form1.objeto2.length; i++){
    if(document.form1.objeto2.options[i].value == 5){
      valor = 5;
      break;
    }
  }
  if((valor == 5 && !document.form1.r48_semest) || (valor != 5 && document.form1.r48_semest)){
    js_seleciona_combo(document.form1.objeto1);
    js_seleciona_combo(document.form1.objeto2);
    document.form1.submit();
  }
}
function js_enviardados(){

  if(document.form1.anofolha.value == ""){
    alert("Informe o ano a ser pesquisado.");
    document.form1.anofolha.focus();
  }else if(document.form1.mesfolha.value == ""){
    alert("Informe o mês a ser pesquisado.");
    document.form1.mesfolha.focus();
  }else{

    stringretorno = "?ano=" + document.form1.anofolha.value;
    stringretorno+= "&mes=" + document.form1.mesfolha.value;
    
    stringretorno+= "&ponts=";
    virstrretorno = "";
    for(i=0;i<document.form1.objeto2.length;i++){
      stringretorno+= virstrretorno+document.form1.objeto2.options[i].value;
      virstrretorno = ",";
    }

    stringretorno+= "&rubrs=";
    virstrretorno = "";
    for(i=0;i<document.form1.selrubri.length;i++){
      stringretorno+= virstrretorno+document.form1.selrubri.options[i].value;
      virstrretorno = ",";
    }

    stringretorno+= "&recrs=";
    virstrretorno = "";
    for(i=0;i<document.form1.recursos.length;i++){
      stringretorno+= virstrretorno+document.form1.recursos.options[i].value;
      virstrretorno = ",";
    }

    if(document.form1.r48_semest && document.form1.r48_semest.value != ""){
      stringretorno+= "&semest="+document.form1.r48_semest.value;
      virstrretorno = ",";
    }

    jan = window.open('pes2_sapretconsig002.php' + stringretorno,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
    jan.moveTo(0,0);

  }
}
</script>