<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

include("dbforms/db_funcoes.php");
include("classes/db_lab_labsetor_classe.php");
include("classes/db_lab_requisicao_classe.php");
include("classes/db_lab_exame_classe.php");
include("classes/db_lab_requiitem_classe.php");
include("classes/db_lab_emissao_classe.php");
include("classes/db_lab_atributo_componente_classe.php");
include('libs/db_stdlibwebseller.php');
require_once('libs/db_utils.php');
$cllab_labsetor = new cl_lab_labsetor;
$cllab_requisicao = new cl_lab_requisicao;
$cllab_exame = new cl_lab_exame;
$clrotulo = new rotulocampo;
$clrotulo->label("la08_c_descr");
$clrotulo->label("la21_i_codigo");


/**
 * Função para descobrir o laboratorio que o usuario esta logado
 * @return inteiro Codigo do laboratorio logado 
 */
function laboratorioLogado(){
  
  require_once('libs/db_utils.php');
  $iUsuario = db_getsession('DB_id_usuario');
  $iDepto = db_getsession('DB_coddepto');
  $oLab_labusuario = db_utils::getdao('lab_labusuario');
  $oLab_labdepart = db_utils::getdao('lab_labdepart');
  $sql = $oLab_labusuario->sql_query(null,'la02_i_codigo, la02_c_descr',"la02_i_codigo", " la05_i_usuario = $iUsuario");
  $rResult=$oLab_labusuario->sql_record($sql);
  if ($oLab_labusuario->numrows == 0) {
      
      $sql = $oLab_labdepart->sql_query(null,'la02_i_codigo, la02_c_descr',"la02_i_codigo", " la03_i_departamento = $iDepto");
      $rResult=$oLab_labdepart->sql_record($sql);
      if ($oLab_labdepart->numrows == 0) {
          return false;
      }
  }
  $oLab = db_utils::getColectionByRecord($rResult);
  return $oLab[0]->la02_i_codigo;
  
}
$iLaboratorioLogado = laboratorioLogado();

if(isset($requisicao)){
  if ($sSituacao != '6 - importado') {
  	if(!defined('DB_BIBLIOT')){
  	  define('DB_BIBLIOT','OK');
  	  define('FPDF_FONTPATH','font/');
      require('fpdf151/fpdf.php');
    }
  	include("fpdf151/pdf.php");
    require("lab4_emissaoresult002.php");
  }else{
    
    $oDaoLabEmissao = db_utils::getdao('lab_emissao');
    $sSql           = $oDaoLabEmissao->sql_query_file(null,"la34_o_laudo,la34_c_nomearq",
                                                    "la34_d_data desc,la34_c_hora desc","la34_i_requiitem=".$requiitem);
    $rsEmissao      = $oDaoLabEmissao->sql_record($sSql);
    if ($oDaoLabEmissao->numrows > 0) {
      
      $oEmissao = db_utils::fieldsmemory($rsEmissao,0);
      db_inicio_transacao();
      if (pg_lo_export($oEmissao->la34_o_laudo,'tmp/'.$oEmissao->la34_c_nomearq,$conn)) {
      
        ?>
        <script>
          jan = window.open('tmp/<?=$oEmissao->la34_c_nomearq?>',
                            '',
                            'width='+(screen.availWidth-5)+
                            ',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
        </script>
        <?
    
      } else {
        db_msgbox("Erro durante abertura do arquivo!");
      }
      db_fim_transacao();
    } else {
      db_msgbox("Arquivo importado, porém não à registro de emissão!");
    }
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
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<?if($iLaboratorioLogado==0){ ?>
    <table width='100%'>
      <tr>
        <td align='center'>
          <br><br>
          <font color='#FF0000' face='arial'>
            <b>Usuário ou departamento não consta como laboratório!<br>
            </b>
          </font>
        </td>
      </tr>
    </table>
    </center>
    <?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    exit;
  }?>
<table valign="top" marginwidth="0" width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
  <center>
  <br><br>
  <fieldset style='width: 75%;'> <legend><b>Emissão de Resultado</b></legend>
    <form name='form1'>
    <table>    
      <tr>
    <td nowrap title="<?=@$Tla22_i_codigo?>">
         <? db_ancora ( '<b>Requisição</b>', "js_pesquisala22_i_codigo(true);", "" );?>
        </td>
    <td> 
         <? db_input ( 'la22_i_codigo', 10, @$Ila22_i_codigo, true, 'text',"", " onchange='js_pesquisala22_i_codigo(false);'" )?>
         <? db_input ( 'z01_v_nome2', 50, @$Iz01_v_nome, true, 'text', 3, '' )?>
        </td>
    </tr> 
    <tr>
      <td nowrap title="requiitem">
        <? db_ancora ( '<b>Exame</b>', "js_pesquisala21_i_codigo(true);", "" );?>
      </td>
      <td> 
        <? db_input ( 'la08_i_codigo', 10, @$Ila08_i_codigo, true, 'text',"", " onchange='js_pesquisala21_i_codigo(false);'" )?>
        <? db_input ( 'la21_i_codigo', 10, @$Ila21_i_codigo, true, 'hidden',"", "")?>
        <? db_input ( 'la21_c_situacao', 10, @$Ila21_c_situacao, true, 'hidden',"", "")?>
        <? db_input ( 'la08_c_descr', 50, @$Ila08_c_descr, true, 'text', 3, '' )?>
       </td>
    </tr> 
       <tr>
        <td colspan='6' align='center' >
          <input name='start' type='button' value='Gerar' onclick="js_mandaDados()">
        </td>
       </tr>
      </table>
     </form>
    </fieldset>
   </center>
  </td>
 </tr>
</table>
<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script>
function js_limpaCamposTrocaReq() {

  document.form1.la21_i_codigo.value   = '';
  document.form1.la21_c_situacao.value = '';
  document.form1.la08_c_descr.value    = '';

}
  
function js_pesquisala22_i_codigo(mostra){
  if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_requisicao','func_lab_requisicao.php?iLaboratorioLogado=<?=$iLaboratorioLogado?>&autoriza=2&funcao_js=parent.js_mostrarequisicao1|la22_i_codigo|z01_v_nome','Pesquisa',true);
  }else{
  if(document.form1.la22_i_codigo.value != ''){ 
    js_OpenJanelaIframe('','db_iframe_requisicao','func_lab_requisicao.php?iLaboratorioLogado=<?=$iLaboratorioLogado?>&autoriza=2&pesquisa_chave='+document.form1.la22_i_codigo.value+'&funcao_js=parent.js_mostrarequisicao','Pesquisa',false);
  }else{
    document.form1.z01_v_nome2.value = ''; 
    }
  }
}

function js_mostrarequisicao(chave,erro){

  document.form1.z01_v_nome2.value = chave; 
  if(erro==true){ 
  document.form1.la22_i_codigo.focus(); 
  document.form1.la22_i_codigo.value = ''; 
  }
  js_limpaCamposTrocaReq();
}

function js_mostrarequisicao1(chave1,chave2){

  document.form1.la22_i_codigo.value = chave1;
  document.form1.z01_v_nome2.value = chave2;
  db_iframe_requisicao.hide();
  js_limpaCamposTrocaReq();

}
    
function js_pesquisala21_i_codigo(mostra){
  if(document.form1.la22_i_codigo.value == '') {

    alert('Escolha uma requisição primeiro.');
  js_limpaCamposTrocaReq();
  return false;

  }
  sPesq = 'la21_i_requisicao='+document.form1.la22_i_codigo.value+'&iLaboratorioLogado=<?=$iLaboratorioLogado?>&sSituacao=|7 - Conferido|,|6 - importado|,|3 - Entregue|&';
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_requiitem','func_lab_requiitem.php?'+sPesq+'funcao_js=parent.js_mostrarequiitem1|la08_i_codigo|la08_c_descr|la21_i_codigo|la21_c_situacao','Pesquisa',true);
  }else{
   if(document.form1.la08_i_codigo.value != ''){ 

      js_OpenJanelaIframe('','db_iframe_requiitem','func_lab_requiitem.php?'+sPesq+'pesquisa_chave='+document.form1.la08_i_codigo.value+'&funcao_js=parent.js_mostrarequiitem','Pesquisa',false);
   }else{
     
      document.form1.la08_c_descr.value    = '';
      document.form1.la21_c_situacao.value = '';
      
   }
  }
}

function js_mostrarequiitem(chave,erro,requiitem,situacao){
  document.form1.la08_c_descr.value = chave; 
  if(erro==true){ 
  document.form1.la08_i_codigo.focus(); 
  document.form1.la08_i_codigo.value = ''; 
  }else{
    document.form1.la21_i_codigo.value = requiitem;
    document.form1.la21_c_situacao.value = situacao; 
  }
}

function js_mostrarequiitem1(chave1,chave2,requiitem,situacao){
  document.form1.la08_i_codigo.value = chave1;
  document.form1.la08_c_descr.value = chave2;
  document.form1.la21_i_codigo.value = requiitem;
  document.form1.la21_c_situacao.value = situacao;
  db_iframe_requiitem.hide();   

}

function js_mandaDados() {
 
  oF = document.form1;
  if(!js_validaDados()) {
    return false;
  }
  iRequisicao   = 'requisicao='+oF.la22_i_codigo.value;
  iRequiitem    = '&requiitem='+oF.la21_i_codigo.value;
  sSituacao     = '&sSituacao='+oF.la21_c_situacao.value;  
  location.href = 'lab4_emissaoresult001.php?'+iRequisicao+iRequiitem+sSituacao;

}

function js_validaDados(){

  oF = document.form1;

  if(oF.la22_i_codigo.value == '') {

    alert('Preencha os dados do formulario.');
    return false;

  }
  if(oF.la21_i_codigo.value == '') {

      alert('Pesquise um exame.');
      return false;

  }
 
  return true;

}
</script>