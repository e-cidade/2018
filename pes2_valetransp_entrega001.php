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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
$gform = new cl_formulario_rel_pes;
$clrotulo = new rotulocampo;
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
function js_verifica(){
  var anoi = new Number(document.form1.datai_ano.value);
  var anof = new Number(document.form1.dataf_ano.value);
  if(anoi.valueOf() > anof.valueOf()){
    alert('Intervalo de data invalido. Velirique !.');
    return false;
  }
  return true;
}


function js_emite(){
  js_gerar_consrel();
	qry  = '?ordem='+document.form1.ordem.value;
	qry += '&vale='+document.form1.vale.value;
	qry += '&endereco='+document.form1.endereco.value;
  qry += '&'+document.form1.valores_campos_rel.value;
  jan = window.open('pes2_valetransp_entrega002.php'+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
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

  <table  align="center">
    <form name="form1" method="post" action="" onsubmit="return js_verifica();">
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
  <tr>
    <td nowrap colspan="2">
    <?
  $gform->strngtipores = "glt";                // OPÇÕES PARA MOSTRAR NO TIPO DE RESUMO g - geral,
  $gform->tipores = true;

  $gform->usalota = true;                      // PERMITIR SELEÇÃO DE LOTAÇÕES
  $gform->usaloca = true;                      // Usar lotação.

  $gform->lo1nome = "lotaci";                  // NOME DO CAMPO DA LOTAÇÃO INICIAL
  $gform->lo2nome = "lotacf";                  // NOME DO CAMPO DA LOTAÇÃO FINAL
  $gform->lo3nome = "sellotac";

  $gform->tr1nome = "local1";                  // Nome do campo LOCAL 1.
  $gform->tr2nome = "local2";                  // Nome do campo LOCAL 2.
  $gform->tr3nome = "sellocal";                // Nome do objeto de seleção de locais.

  $gform->trenome = "tipo";                    // NOME DO CAMPO TIPO DE RESUMO
  $gform->tfinome = "opcao_filtro";            // NOME DO CAMPO TIPO DE FILTRO

  $gform->resumopadrao = "l";                  // TIPO DE RESUMO PADRÃO
  $gform->tipresumo = "Tipo       ";
  $gform->campo_auxilio_lota = "faixa_lotac";         // NOME DO DAS LOTAÇÕES SELECIONADAS
  $gform->campo_auxilio_loca = "faixa_local";  // Nome do campo de auxílio dos locais selecionados.

  $gform->onchpad = true;                      // MUDAR AS OPÇÕES AO SELECIONAR OS TIPOS DE FILTRO OU RESUMO

  $gform->desabam = false;
  $gform->manomes = true;
  $gform->gera_form(db_anofolha(),db_mesfolha());
  ?>
	</td>
      </tr>
      <tr>
        <td ><b>Ordem</b</td>
        <td >
         <?
           $x = array("a"=>"Alfabética","n"=>"Numérica");
           db_select('ordem',$x,true,4,"");
         ?>
	
      </tr>
      <tr>
        <td ><b>Vale</b</td>
        <td >
         <?
           $xv = array("t"=>"Todos","a"=>"Ativos","i"=>"Inativos");
           db_select('vale',$xv,true,4,"");
         ?>
	
	</td>
      </tr>
      <tr>
        <td ><b>Imprime Endereço</b</td>
        <td >
         <?
           $arr_end = array("n"=>"Não","s"=>"Sim");
           db_select('endereco',$arr_end,true,4,"");
         ?>
	
	</td>
      </tr>
														       
      <tr>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
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