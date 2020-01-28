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
require_once modification("dbforms/db_funcoes.php");

db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clpcproc     = new cl_pcproc;
$clpcprocitem = new cl_pcprocitem;
$clsolicita   = new cl_solicita;
$clpcproc->rotulo->label("pc80_codproc");
$clsolicita->rotulo->label("pc10_numero");

if (!isset($pesquisar)) {

  $iDia = date("d", db_getsession("DB_datausu"));
  $iMes = date("m", db_getsession("DB_datausu"));
  $iAno = date("Y", db_getsession("DB_datausu"));

  $pc80_datai_dia = $iDia;
  $pc80_datai_mes = $iMes;
  $pc80_datai_ano = $iAno;

  $pc80_dataf_dia = $iDia;
  $pc80_dataf_mes = $iMes;
  $pc80_dataf_ano = $iAno;


}
  $pc80_datai = "{$pc80_datai_ano}-{$pc80_datai_mes}-{$pc80_datai_dia}";
  $pc80_dataf = "{$pc80_dataf_ano}-{$pc80_dataf_mes}-{$pc80_dataf_dia}";

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
      <table border="0" align="center" cellspacing="0">
        <form name="form2" method="post" action="" >
           <tr>
             <td nowrap title="<?=$Tpc80_codproc?>">
               <?=$Lpc80_codproc?>
             </td>
             <td>
               <?
                 db_input("pc80_codproc",10,$Ipc80_codproc,true,"text",4,"","chave_pc80_codproc");
               ?>
             </td>
           </tr>
           <tr>
             <td nowrap title="<?=$Tpc10_numero?>">
               <?=$Lpc10_numero?>
             </td>
             <td>
               <?
                 db_input("pc10_numero",10,$Ipc10_numero,true,"text",4,"","chave_pc10_numero");
                 db_input("param",10,"",false,"hidden",3);
               ?>
             </td>
           </tr>

           <tr>
             <td nowrap>
               <b>Data Inicial:</b>
             </td>
             <td nowrap>
               <?
                db_inputdata("pc80_data",@$pc80_datai_dia,@$pc80_datai_mes,@$pc80_datai_ano,true,"text",1,"","pc80_datai");
               ?>
             </td>
           </tr>

           <tr>
             <td nowrap>
               <b>Data Final:</b>
             </td>
             <td nowrap>
               <?
                db_inputdata("pc80_data",@$pc80_dataf_dia,@$pc80_dataf_mes,@$pc80_dataf_ano,true,"text",1,"","pc80_dataf");
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

        if (isset($orc)) {
          $result_chave = $clpcprocitem->sql_record($clpcprocitem->sql_query_orcam(null," distinct pc81_codproc as chave_pc80_codproc",""," pc22_codorc=$orc "));
          if ($clpcprocitem->numrows>0) {
            db_fieldsmemory($result_chave,0);
          }
        }

        $where_pcprocitem = "";

        if (!isset($chave_pc10_numero) && !isset($chave_pc80_codproc) || (trim($chave_pc10_numero)=="" && trim($chave_pc80_codproc)=="")) {
          $where_pcprocitem  .= " and pc80_data between '{$pc80_datai}' and '{$pc80_dataf}'									   ";
        }

        if (isset($exc) || (isset($param) && trim($param) != "")) {

          $where_pcprocitem .= " and not exists (  																				                                                                     ";
          $where_pcprocitem .= "              select *																			                                                                   ";
          $where_pcprocitem .= "                from pcprocitem 																	                                                             ";
			    $where_pcprocitem .= "                     inner join empautitempcprocitem on empautitempcprocitem.e73_pcprocitem = pcprocitem.pc81_codprocitem      ";
			    $where_pcprocitem .= "                     inner join empautitem           on empautitem.e55_autori               = empautitempcprocitem.e73_autori  ";
			    $where_pcprocitem .= "                                                    and empautitem.e55_sequen               = empautitempcprocitem.e73_sequen  ";
          $where_pcprocitem .= "                     inner join empautoriza          on empautoriza.e54_autori              = empautitem.e55_autori 	         ";
          $where_pcprocitem .= "               where pcprocitem.pc81_codproc = pcproc.pc80_codproc 								                                             ";
          $where_pcprocitem .= "                 and empautoriza.e54_anulad is null												                                                     ";
          $where_pcprocitem .= "                )                                                                                                              ";
        }


        /**
         * Não deve trazer processo de compras onde a solicitação é de origem AUTOMÁTICA.
         * Esses procedimentos são criados em licitações do tipo NÃO GERA DESPESA
         */
        $where_pcprocitem .= " and not EXISTS ( SELECT 1 ";
        $where_pcprocitem .= "                FROM pcprocitem ";
        $where_pcprocitem .= "                JOIN solicitem on solicitem.pc11_codigo = pcprocitem.pc81_solicitem ";
        $where_pcprocitem .= "                JOIN solicita  on solicita.pc10_numero  = solicitem.pc11_numero ";
        $where_pcprocitem .= "               WHERE pcprocitem.pc81_codproc = pcproc.pc80_codproc ";
        $where_pcprocitem .= "                 and pc10_solicitacaotipo in (8) )";


        if (isset($campos)==false) {

          if (file_exists("funcoes/db_func_pcproc.php")==true) {
            include(modification("funcoes/db_func_pcproc.php"));
          } else {
            $campos = "pcproc.*";
          }

          $campos = " distinct ".$campos;

        }
        if (!isset($pesquisa_chave)) {
          if (isset($chave_pc80_codproc) && (trim($chave_pc80_codproc)!="") ) {
            $sql = $clpcproc->sql_query_autitem($chave_pc80_codproc,$campos,"pc80_codproc desc"," pc80_codproc=$chave_pc80_codproc ".$where_pcprocitem);
          } else if (isset($chave_pc10_numero) && (trim($chave_pc10_numero)!="") ) {
            $sql = $clpcproc->sql_query_autitem("",$campos,"pc80_codproc desc"," pc10_numero=$chave_pc10_numero ".$where_pcprocitem);
          } else if (isset($exc)) {
            $sql = $clpcproc->sql_query_usudepart(null,$campos,"pc80_codproc desc"," 1=1 ".$where_pcprocitem);
          } else {
            $sql = $clpcproc->sql_query_autitem("",$campos,"pc80_codproc desc"," 1=1 ".$where_pcprocitem);
          }
          //db_msgbox("aqui");
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",array(),false,array());


        } else {
          if ($pesquisa_chave!=null && $pesquisa_chave!="") {
            $result = $clpcproc->sql_record($clpcproc->sql_query_autitem(null,$campos,""," pc80_codproc=$pesquisa_chave ".$where_pcprocitem));
            if ($clpcproc->numrows!=0) {
              db_fieldsmemory($result,0);
              echo "<script>".$funcao_js."('$pc80_data',false);</script>";
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
