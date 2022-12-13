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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("libs/db_utils.php");
require_once modification("dbforms/db_funcoes.php");

db_postmemory($_POST);

parse_str($_SERVER["QUERY_STRING"]);

$clpcproc 	  = new cl_pcproc;
$clpcprocitem = new cl_pcprocitem;
$clsolicita   = new cl_solicita;
$clpcparam 	  = new cl_pcparam;

$clpcproc->rotulo->label("pc80_codproc");
$clsolicita->rotulo->label("pc10_numero");

if (!isset($pesquisar)) {
  $iDia = date("d", db_getsession("DB_datausu"));
  $iMes = date("m", db_getsession("DB_datausu"));
  $iAno = date("Y", db_getsession("DB_datausu"));

  $dataini_dia = "01";
  $dataini_mes = $iMes;
  $dataini_ano = $iAno;

  $datafim_dia = $iDia;
  $datafim_mes = $iMes;
  $datafim_ano = $iAno;

  $dataini = "{$dataini_ano}-{$dataini_mes}-01"; // Primeiro Dia do Mes
  $datafim = "{$datafim_ano}-{$datafim_mes}-{$datafim_dia}";

}
$sWhereContrato = " and 1 = 1 ";

$lFiltrarProcessoAutomatico = !empty($lBuscarProcessoAutomatico) && $lBuscarProcessoAutomatico == "true";

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
            <td width="4%" align="right" nowrap title="<?=$Tpc80_codproc?>">
              <?=$Lpc80_codproc?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("pc80_codproc",10,$Ipc80_codproc,true,"text",4,"","chave_pc80_codproc");
		       ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tpc10_numero?>">
              <?=$Lpc10_numero?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("pc10_numero",10,$Ipc10_numero,true,"text",4,"","chave_pc10_numero");
		       ?>
            </td>
          </tr>

          <tr>
            <td width="4%" align="right" nowrap title="Data Inicial">
	            <b>Data Inicial:</b>
	          </td>
            <td width="96%" align="left" nowrap>
	            <?
					      db_inputdata("dataini",@$dataini_dia,@$dataini_mes,@$dataini_ano,true,"text",1,"","dataini");
	            ?>
	          </td>
	        </tr>

          <tr>
            <td width="4%" align="right" nowrap title="Data Final">
	            <b>Data Final:</b>
	          </td>
            <td width="96%" align="left" nowrap>
	            <?
					      db_inputdata("datafim",@$datafim_dia,@$datafim_mes,@$datafim_ano,true,"text",1,"","datafim");
	            ?>
	          </td>
	        </tr>

          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_pcproc.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?

      $sWhereSolicitaAnulada = " not exists (select 1 from solicitaanulada where pc67_solicita = pc10_numero) ";

      if (isset($orc)) {
        $result_chave = $clpcprocitem->sql_record($clpcprocitem->sql_query_orcam(null," distinct pc81_codproc as chave_pc80_codproc",""," pc22_codorc=$orc "));
        if ($clpcprocitem->numrows>0) {
          db_fieldsmemory($result_chave,0);
        }
      }
      if (isset($campos)==false) {
        if (file_exists("funcoes/db_func_pcproc.php")==true) {
          include(modification("funcoes/db_func_pcproc.php"));
        } else {
          $campos = "pcproc.*";
        }
      }
      $dbwhere = "";
      if (isset($imp)) {
        $dbwhere = " and (e55_sequen is null or (e55_sequen is not null and e54_anulad is not null))";
      }
      $where_lic="";
      if (isset($orclic)&&$orclic==true) {

        $where_lic  = " and l21_codigo is null  ";

        $rsConsultaPcparam = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit"), "pc30_contrandsol"));
        $oPcparam = db_utils::fieldsMemory($rsConsultaPcparam,0);
        if ($oPcparam->pc30_contrandsol == "t") {
          $where_lic .= " and ( case  												";
          $where_lic .= "         when p62_codtran is not null 						";
          $where_lic .= "     	 and p62_coddeptorec = ".db_getsession('DB_coddepto');
          $where_lic .= "     	then true 					 						";
          $where_lic .= "       end ) 	  					 						";
        }
      }
      if (isset($situacao) && !empty($situacao)) {
         $dbwhere .= " and pc80_situacao={$situacao}";
      }
      $campos = " distinct ".$campos;

      /**
       * Bloqueado para não trazer os processos de compra que já tem todos os itens vinculados a um orçamento
       */
      if (isset($lBloqueiaVinculadosOrcamento) && $lBloqueiaVinculadosOrcamento) {

        $dbwhere .= " and exists(select *                                                                ";
        $dbwhere .= "              from pcprocitem                                                       ";
        $dbwhere .= "                   left join pcorcamitemproc on pc31_pcprocitem = pc81_codprocitem  ";
        $dbwhere .= "             where pc31_pcprocitem is null and pc81_codproc = pcproc.pc80_codproc ) ";
      }

      if (!isset($pesquisa_chave)) {

        if (isset($lFiltroContrato) && $lFiltroContrato == 1 ){

          $sWhereContrato .= ' and acordopcprocitem.ac23_sequencial is null ';

        }

        if (isset($chave_pc80_codproc) && (trim($chave_pc80_codproc)!="") ) {

          $sql = $clpcproc->sql_query_proc_and(null,$campos, null,"pc80_codproc = ".$chave_pc80_codproc." $where_lic and db_depart.instit = ".db_getsession("DB_instit").$dbwhere. $sWhereContrato ." and {$sWhereSolicitaAnulada}");

        } else if (isset($chave_pc10_numero) && (trim($chave_pc10_numero)!="") ) {

          $sql = $clpcproc->sql_query_proc_and("",$campos, null," pc10_numero=$chave_pc10_numero $where_lic and db_depart.instit = ".db_getsession("DB_instit").$dbwhere . $sWhereContrato." and {$sWhereSolicitaAnulada}");
        } else {

          $sql = $clpcproc->sql_query_proc_and("",$campos, null," db_depart.instit = ".db_getsession("DB_instit").$dbwhere.$where_lic." and pc80_data between '{$dataini}' and '{$datafim}' $sWhereContrato and {$sWhereSolicitaAnulada}");
        }
        if (isset($iAtivo) && !empty($iAtivo)) {
          $sql .=  " and pc80_situacao = $iAtivo";
        }

      if ($lFiltrarProcessoAutomatico) {

        $sql .= " and pc10_solicitacaotipo not in (5,6) ";
        $sql .= " and not exists (select 1 from pcsugforn where pc40_solic = pc10_numero) ";
      }

        $sql .= " and pc10_solicitacaotipo <> 8 ";
        $sql .= " order by pc80_codproc desc ";
        $repassa = array("dataini" => $dataini, "datafim" => $datafim);

       // echo "<br><br>" . $sql . "<br>";

        db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);

      } else {



        if ($pesquisa_chave!=null && $pesquisa_chave!="") {

          if (isset($lFiltroContrato) && $lFiltroContrato == 1 ){

            $sWhereContrato .= ' and acordopcprocitem.ac23_sequencial is null ';

          }


          $sql = $clpcproc->sql_query_autitem(null,"*", null,"pc80_codproc = ".$pesquisa_chave." $where_lic and pc10_instit = ".db_getsession("DB_instit").$dbwhere . $sWhereContrato." and {$sWhereSolicitaAnulada}");
          if (isset($iAtivo) && !empty($iAtivo)) {
            $sql .= " and pc80_situacao = $iAtivo";
          }
          $sql .= " and pc10_solicitacaotipo <> 8 ";
          $sql .= " order by pc80_codproc desc ";


          $result = $clpcproc->sql_record($sql);
          if ($clpcproc->numrows!=0) {
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$pc80_codproc',false);</script>";
          } else {
            echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
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
