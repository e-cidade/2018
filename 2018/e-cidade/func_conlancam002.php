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
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_conlancamval_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_conlancam_classe.php");
require_once("classes/db_conlancamcompl_classe.php");
require_once("classes/db_conlancamdig_classe.php");
require_once("classes/db_conplano_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clconplano     = new cl_conplano;
$clconlancamval = new cl_conlancamval;
$clconlancamcompl = new cl_conlancamcompl;
$clconlancamdig   = new cl_conlancamdig;
$clconlancam      = new cl_conlancam;

$db_opcao = 33;
$db_botao = false;
$anousu = db_getsession("DB_anousu");

$sWhere = " where  c75_numemp=$chavepesquisa ";

if(isset($e69_codnota)){

	$sWhere = " where  c66_codnota=$e69_codnota ";

}


 if(isset($chavepesquisa)){
       $sql = " select c70_codlan,
                       c70_data,
		                   c53_descr,
		                   c70_valor,
		                   c82_reduz,
                       c60_descr,
		                   c72_complem,
                       e69_numero as dl_Nota_Fiscal,
                       e50_codord  ,
                       e50_data
                  from conlancamemp
                       inner join conlancam on c70_codlan = c75_codlan
                       inner join conlancamordem on conlancamordem.c03_codlan = conlancam.c70_codlan
									     left  outer join conlancampag on c82_codlan = c70_codlan
									     inner join conlancamdoc on c71_codlan   = c70_codlan
									     inner join conhistdoc on c53_coddoc     = c71_coddoc
									     left join conlancamcompl on c72_codlan  =c70_codlan
									     left join conlancamnota  on c66_codlan  =c70_codlan
									     left join conlancamord   on c80_codlan  =c70_codlan
									     left join empnota        on c66_codnota = e69_codnota
							         left join conplanoreduz on c61_reduz = conlancampag.c82_reduz and c61_anousu=c70_anousu
							         left join conplano on c60_codcon = conplanoreduz.c61_codcon and c60_anousu=c61_anousu
							         left join pagordem     on e50_codord  = c80_codord
            ";

   }
  $sql .= $sWhere;
  $sql .= " order by c75_data, c03_ordem, c75_codlan ";
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body style="background-color: #CCCCCC;" >
  <center>

     <script>

      function js_conlancam(codlan){
        js_OpenJanelaIframe('top.corpo','db_iframe_conlancam003','func_conlancam003.php?chavepesquisa='+codlan,'Pesquisa');
      }

     </script>

  <?
  if (isset($sql)) {
    $js_funcao="parent.js_infoLancamento|c70_codlan";
    db_lovrot($sql,15,"()","",$js_funcao,"","NoMe",array(),false,array());
  }
  ?>
    </form>
  </center>
</body>
</html>
<script>
</script>