<?php
/**
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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_portaria_classe.php"));

db_postmemory($_POST);
parse_str($_SERVER["QUERY_STRING"]);

$clrotulo   = new rotulocampo;
$clportaria = new cl_portaria;
$clportaria->rotulo->label("h31_sequencial");
$clportaria->rotulo->label("h31_numero");
$clrotulo->label("h42_descr");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInputDate.widget.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr>
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
	     <form name="form2" method="post" action="" >
          <tr>
            <td width="4%" align="right" nowrap title="Portarias emitidas entre as datas.">
              <b>Emitidas entre : </b>
            </td>
            <td width="96%" align="left" nowrap>
              <?
              if(!isset($dataini)){
                $oData = new DBDate(date('d/m/Y'));

                $datafim = $oData->getDate(DBDate::DATA_PTBR);
                $dataini = $oData->adiantarPeriodo(-30, 'd')->getDate(DBDate::DATA_PTBR);
              }
              db_input("dataini",10,$dataini,true,"text",2,"");

		          ?>
              <b> a </b>
              <?
		          db_input("datafim",10,$datafim,true,"text",2,"");
              ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Th31_numero?>">
              <?=$Lh31_numero?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("h31_numero",10,$Ih31_numero,true,"text",4,"","chave_h31_numero");
		       ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Th42_descr?>">
              <?=$Lh42_descr?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("h42_descr",40,$Ih42_descr,true,"text",4,"","chave_h42_descr");
		       ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_portaria.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?php

      $lErro = false;

      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_portaria.php")==true){
             include(modification("funcoes/db_func_portaria.php"));
           }else{
           $campos = "portaria.*";
           }
        }
        $campos = "distinct ".$campos;

        $sSqlVerificaLotacao = null;
        if (isset($filtro_lotacao) && $filtro_lotacao) {

            $sSqlVerificaLotacao = " and h16_regist in ( select distinct rh02_regist
                                                           from rhpessoalmov
                                                           INNER JOIN rhlota ON rhlota.r70_codigo = rhpessoalmov.rh02_lota
                                                           INNER JOIN db_usuariosrhlota ON rhlota.r70_codigo = db_usuariosrhlota.rh157_lotacao
                                                          where rh02_anousu = ".DBPessoal::getAnoFolha()."
                                                            and rh02_mesusu = ".DBPessoal::getMesFolha()."
                                                            AND rh157_usuario = ".db_getsession("DB_id_usuario").")";
        }

        if(isset($chave_h42_descr) && (trim($chave_h42_descr)!="") ){
	         $sql = $clportaria->sql_query_assentamento_funcional("",$campos,"h31_dtportaria desc,  h31_sequencial desc"," upper(h42_descr) like '$chave_h42_descr%' ", $sSqlVerificaLotacao, "2");
        }elseif(isset($chave_h31_numero) && (trim($chave_h31_numero)!="") ){
	         $sql = $clportaria->sql_query_assentamento_funcional("",$campos,"h31_numero"," h31_numero like '$chave_h31_numero' ", $sSqlVerificaLotacao, "2");
        }else if(isset($lcoletiva)) {
        	 $sql = $clportaria->sql_query_assentamento_funcional("",$campos,"h31_sequencial desc"," h31_sequencial in (  select h33_portaria from portariaassenta  group by h33_portaria having  count(h33_portaria) > 1 ) ", $sSqlVerificaLotacao, "2");
        }else{

            if ( empty($dataini) || empty($datafim) ) {
              $lErro = true;
            }

            if ( !$lErro ) {

             $sql = $clportaria->sql_query_assentamento_funcional("",$campos,"h31_dtportaria desc,  h31_sequencial desc"," h31_dtportaria between to_date('$dataini','dd/mm/yyyy') and to_date('$datafim','dd/mm/yyyy')", $sSqlVerificaLotacao, "2");
            }
        }
        $repassa = array();
        if(isset($chave_h42_descr)||isset($chave_h31_numero)){
          $repassa = array("chave_h31_numero"=>$chave_h31_numero,"chave_h42_descr"=>$chave_h42_descr);
        }

	      if(isset($sql) && trim($sql) != ""){
           db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa,false);
        }
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clportaria->sql_record($clportaria->sql_query_assentamento_funcional($pesquisa_chave), $sSqlVerificaLotacao, "2");
          if($clportaria->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$h31_sequencial',false);</script>";
          }else{
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }
        }else{
	       echo "<script>".$funcao_js."('',false);</script>";
        }
      }
      ?>
     </td>
   </tr>
</table>
<script>
(function(){
  new DBInputDate($('dataini'));
  new DBInputDate($('datafim'));
})();
</script>
</body>
</html>
<?
if(!isset($pesquisa_chave)){
  ?>
  <?
}
?>
<script>
js_tabulacaoforms("form2","chave_h31_sequencial",true,1,"chave_h31_sequencial",true);
</script>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
<?php

  if ( $lErro ) {
    db_msgbox("Data(s) não informada(s). Informe a data inicial e final.");
  }

?>