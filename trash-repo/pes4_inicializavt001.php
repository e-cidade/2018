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
include("libs/db_libpessoal.php");
include("dbforms/db_funcoes.php");
include("classes/db_vtffunc_classe.php");
$clvtffunc = new cl_vtffunc;
db_postmemory($HTTP_POST_VARS); 
if(isset($processar)){

  $ano = $anofolha;
  $mes = $mesfolha - 1;
  if($mes == 0){
    $ano -= 1;
    $mes  = 12;
  }

  $dbwhere  = "    rh02_anousu = ".$ano." ";
  $dbwhere .= "and rh02_mesusu = ".$mes." ";
  $dbwhere .= "and rh02_instit = ".db_getsession("DB_instit")." ";
  $dbwhere .= "and (rh05_recis is null or rh05_recis >= '".$anofolha."-".$mesfolha."-01') ";
  $dbwhere .= "and r17_difere = 'f' ";

  if($opcao_gml == "m"){
    if($opcao_filtro == "i"){
      $dbwhere.= " and rh01_regist between ".$r110_regisi." and ".$r110_regisf;
    }else if($opcao_filtro == "s" && trim($faixa_regis) != ""){
      $dbwhere.= " and rh01_regist in (".$faixa_regis.")";
    }
  }else if($opcao_gml == "l"){
    if($opcao_filtro == "i"){
      $dbwhere.= " and r70_estrut between '".$r110_lotaci."' and '".$r110_lotacf."'";
    }else if($opcao_filtro == "s" && trim($faixa_lotac) != ""){
      $dbwhere.= " and r70_estrut in ('".str_replace(",","','",$faixa_lotac)."')";
    }
  }

  $sql_vtffunc = $clvtffunc->sql_query_rhpessoal(null,null,null,null,null, " vtffunc.*, rh01_regist as r01_regist, rh05_recis as r01_recis, rh02_lota as r01_lotac ", " r17_regist, r17_codigo ", $dbwhere);
  db_selectmax("vtffuncant",$sql_vtffunc);
  if(count($vtffuncant) > 0){
    include("pes4_inicializavt002.php");
    $sqlerro = false;
  }else{
    $sqlerro = true;
    $erro_msg = "Verifique os dados informados ou vales \\nnão lançados no ano/mês base (".$ano."/".$mes.").";
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
<center>
<table width="60%" border="0" cellspacing="4" cellpadding="0">
  <tr><td colspan="2">&nbsp;</td></tr>
  <form name="form1" method="post">
    <?
    if(!isset($opcao_gml)){
      $opcao_gml = "m";
    }
    if(!isset($opcao_filtro)){
      $opcao_filtro = "s";
    }

    $ano = db_anofolha();
    $mes = db_mesfolha();

    include("dbforms/db_classesgenericas.php");
    $geraform = new cl_formulario_rel_pes;

    $geraform->filtropadrao = $opcao_filtro;        // NOME DO DAS LOTAÇÕES SELECIONADAS
    $geraform->resumopadrao = $opcao_gml;           // NOME DO DAS LOTAÇÕES SELECIONADAS

    $geraform->manomes = true;                      // PARA NÃO MOSTRAR ANO E MES DE COMPETÊNCIA DA FOLHA
    $geraform->desabam = true;                      // PARA DESABILITAR ANO E MES DE COMPETENCIA DA FOLHA

    $geraform->usaregi = true;                      // PERMITIR SELEÇÃO DE MATRÍCULAS
    $geraform->usalota = true;                      // PERMITIR SELEÇÃO DE LOTAÇÕES

    $geraform->re1nome = "r110_regisi";             // NOME DO CAMPO DA MATRÍCULA INICIAL
    $geraform->re2nome = "r110_regisf";             // NOME DO CAMPO DA MATRÍCULA FINAL
	
    $geraform->lo1nome = "r110_lotaci";             // NOME DO CAMPO DA LOTAÇÃO INICIAL
    $geraform->lo2nome = "r110_lotacf";             // NOME DO CAMPO DA LOTAÇÃO FINAL

    $geraform->trenome = "opcao_gml";               // NOME DO CAMPO TIPO DE RESUMO
    $geraform->tfinome = "opcao_filtro";            // NOME DO CAMPO TIPO DE FILTRO

    $geraform->campo_auxilio_regi = "faixa_regis";  // NOME DO DAS MATRÍCULAS SELECIONADAS
    $geraform->campo_auxilio_lota = "faixa_lotac";  // NOME DO DAS LOTAÇÕES SELECIONADAS

    $geraform->strngtipores = "gml";                // OPÇÕES PARA MOSTRAR NO TIPO DE RESUMO g - geral,
                                                    //                                       m - Matrícula,
                                                    //                                       r - Resumo
    $geraform->testarescisaoregi = "raf";
    $geraform->onchpad      = true;                 // MUDAR AS OPÇÕES AO SELECIONAR OS TIPOS DE FILTRO OU RESUMO
    $geraform->gera_form($ano,$mes);
    ?>
  <tr>
    <td colspan='2' align='center'>
      <input type="submit" name="processar" value="Processar" onclick="return js_enviar_dados();">
    </td>
  </tr>
  </form>
</table>
</center>
<? 
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<script>
function js_enviar_dados(){
  if(document.form1.selregist){
    valores = '';
    virgula = '';
    for(i=0; i < document.form1.selregist.length; i++){
      valores+= virgula+document.form1.selregist.options[i].value;
      virgula = ',';
    }
    document.form1.faixa_regis.value = valores;
    document.form1.selregist.selected = 0;
  }else if(document.form1.sellotac){
    valores = '';
    virgula = '';
    for(i=0; i < document.form1.sellotac.length; i++){
      valores+= virgula+document.form1.sellotac.options[i].value;
      virgula = ',';
    }
    document.form1.faixa_lotac.value = valores;
    document.form1.sellotac.selected = 0;
  }

  return true;
}
js_trocacordeselect();
if(document.form1.selregist){
  js_tabulacaoforms("form1","rh01_regist",true,1,"rh01_regist",true);
}else if(document.form1.sellotac){
  js_tabulacaoforms("form1","rh01_regist",true,1,"rh01_regist",true);
}else if(document.form1.r110_regisi){
  js_tabulacaoforms("form1","r110_regisi",true,1,"r110_regisi",true);
}else if(document.form1.r110_lotaci){
  js_tabulacaoforms("form1","r110_lotaci",true,1,"r110_lotaci",true);
}else{
  if(document.form1.opcao_filtro){
    js_tabulacaoforms("form1","opcao_filtro",true,1,"opcao_filtro",true);
  }else{
    js_tabulacaoforms("form1","opcao_gml",true,1,"opcao_gml",true);
  }
}
</script>
</html>
<?
if(isset($processar)){
  if($sqlerro == true){
    db_msgbox($erro_msg);
  }
}
?>