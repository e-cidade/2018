<?
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
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_liclicita_classe.php"));
require_once(modification("classes/db_liclicitem_classe.php"));

db_postmemory($_GET);
db_postmemory($_POST);
$oGet = db_utils::postMemory($_GET);

parse_str($_SERVER["QUERY_STRING"]);

$clliclicitem = new cl_liclicitem;
$clliclicita  = new cl_liclicita;

$clliclicita->rotulo->label("l20_codigo");
$clliclicita->rotulo->label("l20_numero");
$clliclicita->rotulo->label("l20_edital");
$clrotulo = new rotulocampo;
$clrotulo->label("l03_descr");
$iAnoSessao = db_getsession("DB_anousu");

// Adiciona restricao no sql
$sWhereCredenciamento = "";

// Variavel bCredenciamento controla se vai exibir somente as de Chamamento Publico, Outras ou Todas 
// licitacoes na lookup
// Caso a variavel nao exista, exibe tudo
if(isset($lCredenciamento)){
  // Valor default exibe todas que nao sao Chamamento
  $sWhereCredenciamento = " pctipocompratribunal.l44_sequencial <> 54 and ";
  // Caso a variavel seja true, exibe somente as licitacoes de credenciamento
  if(!empty($lCredenciamento)){
    $sWhereCredenciamento = " pctipocompratribunal.l44_sequencial = 54 and ";
  } 
}

$sWhere          = "exists (select pc11_quant, pc23_valor
                              from solicitem
                                   inner join pcprocitem           on pc11_codigo = pc81_solicitem
                                   inner join liclicitem           on l21_codpcprocitem = pc81_codprocitem
                                   inner join pcorcamitemlic       on pc26_liclicitem   = l21_codigo
                                   inner join pcorcamitem          on pc26_orcamitem    = pc22_orcamitem
                                   inner join pcorcamjulg          on pc22_orcamitem    = pc24_orcamitem
                                                                  and pc24_pontuacao    = 1
                                   inner join pcorcamval           on  pc23_orcamitem    = pc24_orcamitem
                                                                  and  pc23_orcamforne   = pc24_orcamforne
                                   inner join liclicita licsaldo   on l21_codliclicita = licsaldo.l20_codigo
                                   inner join cflicita             on l20_codtipocom = l03_codigo
                                   inner join pctipocompratribunal on l03_pctipocompratribunal = l44_sequencial
                                   left join (select coalesce(sum(e55_quant),0) as quantidade,
                                                     coalesce(sum(e55_vltot),0) as valor,
                                                     e73_pcprocitem as item
                                                from empautitempcprocitem
                                                       inner join empautitem on e55_autori = e73_autori
                                                                            and e55_sequen = e73_sequen
                                                       inner join empautoriza on e55_autori = e54_autori
                                                 where e73_pcprocitem = liclicitem.l21_codpcprocitem
                                                   and e54_anulad is null
                                                   group by e73_pcprocitem ) as saldo_autorizacao on item = pc81_codprocitem
                            where (pc11_quant > coalesce(quantidade,0) or coalesce(pc23_valor,0) > valor)
                          and licsaldo.l20_codigo = liclicita.l20_codigo
                          )";

$sWhereContratos = " and 1 = 1 ";
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr>
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
	     <form name="form2" method="post" action="" >
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tl20_codigo?>">
              <?=$Ll20_codigo?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("l20_codigo",10,$Il20_codigo,true,"text",4,"","chave_l20_codigo");
		       ?>
            </td>
            </tr>

             <tr>
            <td width="4%" align="right" nowrap title="<?=$Tl20_edital?>">
              <?=$Ll20_edital?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
           db_input("l20_edital",10,$Il20_edital,true,"text",4,"","chave_l20_edital");
           ?>
            </td>
            </tr>

            <tr>
            <td width="4%" align="right" nowrap title="<?=$Tl20_numero?>">
              <?=$Ll20_numero?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("l20_numero",10,$Il20_numero,true,"text",4,"","chave_l20_numero");
		       ?>
            </td>
          </tr>
           <tr>

          <tr>
          <td width="4%" align="right" nowrap title="<?=$Tl03_descr?>">
              <?=$Ll03_descr?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
	        db_input("l03_descr",60,$Il03_descr,true,"text",4,"","chave_l03_descr");
                db_input("param",10,"",false,"hidden",3);
	      ?>
            </td>
          </tr>
          <tr>
            <td align="right">
              <b>Ano:</b>
            </td>
            <td>
              <?php
                db_input("l20_anousu", 10, "int", true, "text", 1, null, null, null, null, 4);
              ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_liclicita.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?
      $and            = "and ";
      $dbwhere        = " 1=1 and";
      if (isset($tipo) && trim($tipo)!=""){
        $dbwhere   = " l08_altera is true and ";
      }
			if (isset($situacao) && trim($situacao) != ''){
        $dbwhere .= " l20_licsituacao in ($situacao) and ";
      }

      if (!empty($oGet->validasaldo)){
        $dbwhere .= " $sWhere and ";
      }

      $sWhereModalidade = "";
      if (!empty($iModalidadeLicitacao)) {
        $sWhereModalidade = "and l20_codtipocom = {$iModalidadeLicitacao}";
      }

      $dbwhere_instit = " l20_instit = ".db_getsession("DB_instit"). "{$sWhereModalidade}";

      // Não deixa exibir nas autorizacoes as licitacoes que nao geram despesas
      if(db_getsession('DB_itemmenu_acessado') == 4718){
        $dbwhere_instit .= " and l20_tipo = 1 ";
      }


      // Não deixa exibir as licitações de itens que estao vinculados a contratos.
      if (isset($lContratos) && $lContratos == 1 ) {
        $sWhereContratos .= " and ac24_sequencial is null ";
        $dbwhere_instit  .= $sWhereContratos;
      }


      if(!isset($pesquisa_chave)){

        if(isset($campos)==false){

           if(file_exists("funcoes/db_func_liclicita.php")==true){
             include(modification("funcoes/db_func_liclicita.php"));
           }else{
           $campos = "liclicita.*, liclicitasituacao.l11_sequencial";
           }
        }

        $campos  .= ", (select max(l11_sequencial) as l11_sequencial from liclicitasituacao where l11_liclicita = l20_codigo) as l11_sequencial ";
        // Adiciona a restricao de Chamamento, caso passado por parametro na url
        $dbwhere .= $sWhereCredenciamento;
        if(isset($chave_l20_codigo) && (trim($chave_l20_codigo)!="") ){
	        $sql = $clliclicita->sql_queryContratos(null,"distinct " . $campos,"l20_codigo","$dbwhere  l20_codigo = $chave_l20_codigo and $dbwhere_instit");
        }else if(isset($chave_l20_numero) && (trim($chave_l20_numero)!="") ){
	         $sql = $clliclicita->sql_queryContratos(null,"distinct " .$campos,"l20_codigo","$dbwhere l20_numero=$chave_l20_numero  and $dbwhere_instit");
	      }else if(isset($chave_l03_descr) && (trim($chave_l03_descr)!="") ){
	         $sql = $clliclicita->sql_queryContratos(null,"distinct " .$campos,"l20_codigo","$dbwhere l03_descr like '$chave_l03_descr%'  and $dbwhere_instit");
        }else if(isset($chave_l03_codigo) && (trim($chave_l03_codigo)!="") ){
	         $sql = $clliclicita->sql_queryContratos(null,"distinct " .$campos,"l20_codigo","$dbwhere l03_codigo=$chave_l03_codigo  and $dbwhere_instit");
        }else if(isset($chave_l20_edital) && (trim($chave_l20_edital)!="")){
          $sql = $clliclicita->sql_queryContratos(null,"distinct " .$campos,"l20_codigo","$dbwhere l20_edital=$chave_l20_edital  and $dbwhere_instit");
        }else if(isset($l20_anousu) && (trim($l20_anousu)!="")){
          $sql = $clliclicita->sql_queryContratos(null,"distinct " .$campos,"l20_codigo","$dbwhere $dbwhere_instit and l20_anousu = {$l20_anousu}");
        }else{
          $sql = $clliclicita->sql_queryContratos("","distinct " .$campos,"l20_codigo","$dbwhere $dbwhere_instit and l20_anousu = {$iAnoSessao}");
        }

        if (isset($param) && trim($param) != ""){
	         $dbwhere = " and (e55_sequen is null or (e55_sequen is not null and e54_anulad is not null))";
           if(isset($chave_l20_codigo) && (trim($chave_l20_codigo)!="") ){
	           $sql = $clliclicitem->sql_query_inf(null,$campos,"l20_codigo","$dbwhere l20_codigo = $chave_l20_codigo");
	         }else if(isset($chave_l20_numero) && (trim($chave_l20_numero)!="") ){
	           $sql = $clliclicitem->sql_query_inf(null,$campos,"l20_codigo","$dbwhere l20_numero=$chave_l20_numero");
	         }else if(isset($chave_l03_descr) && (trim($chave_l03_descr)!="") ){
	           $sql = $clliclicitem->sql_query_inf(null,$campos,"l20_codigo","$dbwhere l03_descr like '$chave_l03_descr%'");
           }else if(isset($chave_l03_codigo) && (trim($chave_l03_codigo)!="") ){
	           $sql = $clliclicitem->sql_query_inf(null,$campos,"l20_codigo","$dbwhere l03_codigo=$chave_l03_codigo");
           } else {
             $sql = $clliclicitem->sql_query_inf("",$campos,"l20_codigo","$dbwhere 1=1");
           }
	      }
       // db_lovrot($sql.' desc ',15,"()","",$funcao_js);

        $aRepassa = array();
        db_lovrot($sql.' desc ',15,"()","",$funcao_js, null,'NoMe', $aRepassa, false);


      } else {


        if ($pesquisa_chave != null && $pesquisa_chave != "") {

            if (isset($param) && trim($param) != ""){

              $result = $clliclicitem->sql_record($clliclicitem->sql_query_inf($pesquisa_chave));

              if ($clliclicitem->numrows!=0) {

                db_fieldsmemory($result,0);
                echo "<script>".$funcao_js."('$l20_codigo',false);</script>";
              }else{
  	            echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
              }
	          } else {
                 $result = $clliclicita->sql_record($clliclicita->sql_queryContratos(null,"*",null,"$dbwhere l20_codigo = $pesquisa_chave $and $dbwhere_instit "));


                 if($clliclicita->numrows != 0){

                     db_fieldsmemory($result,0);
                     echo "<script>".$funcao_js."('$l20_codigo',false);</script>";

                 } else {

	                 echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
                 }
	         }

        } else {
	       echo "<script>".$funcao_js."('',false);</script>";
        }
      }
      ?>
     </td>
   </tr>
</table>
</body>
</html>
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
  </script>
  <?
}
?>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
