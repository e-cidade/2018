<?php
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
db_postmemory($_POST);
parse_str($_SERVER["QUERY_STRING"]);

//$clempempenho = new cl_empempenho;
$clorcdotacao  = new cl_orcdotacao;   // dotações do orcamento
$clempempaut   = new cl_empempaut;    // liga a autorização ao empenho
$clempauthist  = new cl_empauthist;   // codigo do historico das autorizações
$clemphist     = new cl_emphist;      // historico das autorizações
$clempautoriza = new cl_empautoriza;  // autorização de empenho
$clempautidot  = new cl_empautidot;   // dotação das autorizações
$clempempenho   = new cl_empempenho;    // liga a autorização ao empenho

// $clempempenho->rotulo->label();
$clempautoriza->rotulo->label();
$clempempaut->rotulo->label();
$clempauthist->rotulo->label();
$clemphist->rotulo->label();
$clempautidot->rotulo->label();
$clempempenho->rotulo->label();

$rotulo = new rotulocampo;
$rotulo->label("coddepto");
$rotulo->label("login");
$lAcordo = false;
$iAcordo = "";

if (isset($e54_autori) and $e54_autori !=""){
    $res = $clempautoriza->sql_record($clempautoriza->sql_query($e54_autori));
    if ($clempautoriza->numrows > 0 ) {
          db_fieldsmemory($res,0,true);
          //-----
          $sql = $clempautidot->sql_query_dotacao($e54_autori,"e56_coddot,o56_elemento,o56_descr,e56_orctiporec, o15_descr,
                                                 fc_estruturaldotacao(o58_anousu,o58_coddot) as o58_estrutdespesa");
          $res = $clempautidot->sql_record($sql);
          if ($clempautidot->numrows > 0 ){
              db_fieldsmemory($res,0);
          }
          //------
          $rhist=$clempauthist->sql_record($clempauthist->sql_query($e54_autori));
          if ($clempauthist->numrows > 0){
                 db_fieldsmemory($rhist,0,true);
          }
	  //-------
	  $remp=$clempempaut->sql_record($clempempaut->sql_query_empenho(null,"e61_numemp,(e60_codemp||'/'||e60_anousu) as e60_codemp",null,"e61_autori=$e54_autori "));
          if ($clempempaut->numrows > 0){
                 db_fieldsmemory($remp,0,true);
          }
    }


    $oAcordo    = db_utils::getDao("acordoempautoriza");
    $sSqlAcordo = $oAcordo->sql_query_file ( null, $campos="*", null, "ac45_empautoriza = {$e54_autori}");
    $rsAcordo   = $oAcordo->sql_record($sSqlAcordo);
    if ($oAcordo->numrows > 0) {

      $lAcordo = true;
      $iAcordo = db_utils::fieldsMemory($rsAcordo, 0)->ac45_acordo;

    }
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
 // function js_abre_lancamentos(){
 //    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_clonlancam002','func_conlancam002.php?chavepesquisa='+"<?=$e54_autori  ?>",'Pesquisa',true);
 // }
 function js_abre_empempitem(){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_empempitem','emp1_empempitem005.php?e60_numemp='+"<?=@$e61_numemp ?>"+'&e55_autori='+"<?=@$e54_autori?>",'Pesquisa',true);
 }
 function pesquisa_cgm(){
   js_JanelaAutomatica('cgm','<?=@$e54_numcgm ?>');
 }
 function pesquisa_dot(){
   js_JanelaAutomatica('orcdotacao','<?=@$e56_coddot ?>','<?=@$e54_anousu ?>');
 }
 function pesquisa_emp(opc){
   js_JanelaAutomatica('empempenho','<?=@$e61_numemp ?>');
 }
 function js_abre_solicita(){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_solicita','func_solicita001.php?e55_autori='+"<?=$e54_autori?>",'Pesquisa',true);
 }
 function js_abre_pcproc(){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_pcproc','func_pcproc001.php?e55_autori='+"<?=$e54_autori?>",'Pesquisa',true);
 }
 function js_abre_acordo(iAcordo){

   js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_acordo','con4_consacordos003.php?lEmpenho=1&ac16_sequencial=' + iAcordo ,'Pesquisa Acordo',true);
}

</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<!---
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr>
    <td  align="center" valign="top">
 --->
    <table width="80%" border="0" align="center" cellspacing="0">

     <tr>
       <td  width="25%" align="right" nowrap title="<?=$Te54_autori ?>"><?=$Le54_autori ?></td>
       <td  colspan="2" width="10%" align="left" nowrap><? db_input("e54_autori",8,"",true,"text",3); ?> </td>
     </tr>
     <tr>
       <td align="right" nowrap>
	   <?  db_ancora($Le61_numemp,"pesquisa_emp();",1);     ?>
       <td nowrap>
     <?
	       db_input("e61_numemp",10,"",true,"text",3);      ?></td>
       <td colspan="2" nowrap>
	   <?  db_ancora($Le60_codemp,"pesquisa_emp();",1);
	       db_input("e60_codemp",12,"",true,"text",3);      ?></td>
    </tr>
    <tr>
      <td align="right" nowrap="nowrap">
        <b>Acordo:</b>
      </td>
      <td>
        <?
          $oAutorizacao = new AutorizacaoEmpenho($e54_autori);
          $contrato     = $oAutorizacao->getContrato();
          db_input("contrato",8,"",true,"text",3);
        ?>
      </td>
    </tr>
    <tr>
        <td  align="right" nowrap><?=$Le54_destin ?></td>
        <td colspan=2 align="left"  nowrap ><? db_input("e54_destin",40,"",true,"text",3); ?></td>
     </tr>
    <tr>
       <td   align="right" nowrap><?=$Le54_emiss?></td>
       <td   align="left" nowrap>
               <?  if (isset($e54_emiss) and ($e54_emiss != "")) {
		         list($e54_emiss_dia,$e54_emiss_mes,$e54_emiss_ano)= split('[/.-]',$e54_emiss);
	           }
		   db_inputdata('e54_emiss',@$e54_emiss_dia,@$e54_emiss_mes,@$e54_emiss_ano,true,'text',3,"");
	        ?> </td>
       <td   align="left" nowrap><?=$Le54_anulad?>
               <?  if (isset($e54_anulad) and ($e54_anulad != "")) {
		         list($e54_anulad_dia,$e54_anulad_mes,$e54_anulad_ano)= split('[/.-]',$e54_anulad);
	           }
		   db_inputdata('e54_anulad',@$e54_anulad_dia,@$e54_anulad_mes,@$e54_anulad_ano,true,'text',3,"");
	        ?> </td>

     </tr>
    <tr>
        <td align="right" nowrap><?=$Lcoddepto?></td>
        <td nowrap>
        <?
          db_input("coddepto",5,"",true,"text",3);
          db_input("descrdepto",40,"",true,"text",3);
        ?>&nbsp;
        </td>
        <td align="left" nowrap><?=$Llogin?>
        <?
          db_input("id_usuario",10,"",true,"text",3);
          db_input("login",20,"",true,"text",3);
        ?>
        </td>
    </tr>
     <tr>
       <td  align="right" nowrap title="<?=$Te54_numcgm ?>"><b><? db_ancora($Le54_numcgm,"pesquisa_cgm();",1);?></b></td>
       <td  colspan=2   align="left" nowrap title="<?=$Te54_numcgm ?>">
           <? db_input("e54_numcgm",8,"",true,"text",3);   db_input("z01_nome",40,"",true,"text",3); ?>
      </td>
     </tr>

     <tr><!---  dotacao --->
         <td  align="right" nowrap title="<?=$Te56_coddot ?>">
	  <?  db_ancora($Le56_coddot,"pesquisa_dot();",1); ?></td>
         <td  colspan=2   align="left" >
             <? db_input("e56_coddot",8,"",true,"text",3);
	        db_input("o58_estrutdespesa",50,"",true,"text",3);   ?> </td>
     </tr>
     <?
       if ($e56_orctiporec != '') {

         echo "<tr><!---  contrapartida---> ";
         echo " <td  align='right' nowrap title='{$Te56_coddot}'";
         echo "<b>Contrapartida</b></td>";
         echo "<td  colspan='2'   align='left' >";
         db_input("e56_orctiporec",8,"",true,"text",3);
         db_input("o15_descr",50,"",true,"text",3);
         echo "</td>";
         echo "</tr>";
      }
     ?>
     <tr>
          <td  align="right" > &nbsp; </td>
          <td  colspan=2   align="left" nowrap >
              <? db_input("o56_elemento",20,"",true,"text",3);
	         db_input("o56_descr",50,"",true,"text",3);   ?> </td>
     </tr>
     <tr> <!--- valor --->
       <td   align="right" nowrap ><?=$Le54_valor ?></td>
       <td   align="left" nowrap ><? db_input("e54_valor",8,"",true,"text",3);?></td>
       <td   align="left" nowrap >
          <?=$Le54_codtipo ?>
	  <? db_input("e54_codtipo",6,"",true,"text",3);
	     db_input("e41_descr",20,"",true,"text",3); ?>
      </td>
     </tr>

     <tr> <!---historico  --->
       <td  align="right" nowrap > <?=$Le57_codhist ?></td>
        <td  align="left" nowrap colspan="2" >
	<?  db_input("e57_codhist",6,"",true,"text",3);
	    db_input("e40_descr",40,"",true,"text",3);?> </td>
     </tr>
     <tr> <!--- resumo --->
        <td  align="right" nowrap ><?=$Le54_resumo ?>  </td>
        <td  colspan=2 rowspan=2  align="left"><?  db_textarea("e54_resumo",2,40,""); ?> </td>
     </tr>
      <tr> <!--- vlranulo --->
        <td> &nbsp; </td>
     </tr>

     <tr>
        <td colspan=3>
           <table border=0 align="center" width="100%">
	   <tr>
             <td align="center">
	          <input type=button value="Consulta Ítens"  onClick="js_abre_empempitem();">
  	          <input type=button value="Consulta Solicitação de Compras" onClick="js_abre_solicita();">
  	          <input type=button value="Consulta Processo de Compras" onClick="js_abre_pcproc();">
  	          <?php
  	            if ($lAcordo) {
  	              echo "<input type=button value='Consulta Acordos' onClick='js_abre_acordo(". $iAcordo .");'>";
  	            }
  	          ?>
	     </td>
	   </tr>
	  </table>
	</td>
     </tr>


     <tr>
        <td colspan=3> &nbsp;</td>
     </tr>

    </table>
</body>
</html>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
