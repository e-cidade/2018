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
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_conplanoexe_classe.php"));
require_once(modification("classes/db_conplano_classe.php"));
db_postmemory($_POST);
parse_str($_SERVER["QUERY_STRING"]);


$clconplanoexe = new cl_conplanoexe;
$clconplano = new cl_conplano;
$clconplanoexe->rotulo->label("c62_anousu");
$clconplanoexe->rotulo->label("c62_reduz");
$clconplanoexe->rotulo->label("c62_codrec");
$clconplano->rotulo->label("c60_descr");
$clconplano->rotulo->label("c60_estrut");

$anousu = db_getsession("DB_anousu");
$sWhere = " 1 = 1 ";

if (!empty($lContaCorrente) && $lContaCorrente == 'false') {
  $sWhere = " not exists ( select 1 from conplanocontacorrente where c18_codcon = c60_codcon and c18_anousu = c60_anousu )";
}

if(isset($codred)){
  // esta opcao esta setada quando clica no db_lov abaixo, ele chama a mesma funcao passando como parametro
  // o codred.
  $campos = " select c62_reduz,c60_descr,
		                            round(substr(fc_planosaldonovo,3,14)::float8,2)::float8 as saldo_anterior,
			                        round(substr(fc_planosaldonovo,17,14)::float8,2)::float8 as saldo_anterior_debito,
				                    round(substr(fc_planosaldonovo,31,14)::float8,2)::float8 as saldo_anterior_credito,
						  			round(substr(fc_planosaldonovo,45,14)::float8,2)::float8 as saldo_final,
						  			substr(fc_planosaldonovo,59,1)::varchar(1) as sinal_anterior,
						  			substr(fc_planosaldonovo,60,1)::varchar(1) as sinal_final
              from (
                 select c62_reduz,c60_descr,
	      				fc_planosaldonovo(".db_getsession("DB_anousu").",c61_reduz,'".db_getsession("DB_anousu")."-01-01','".$dataret."') 
	            		from conplanoreduz 
		                    inner join conplano on c60_codcon = c61_codcon and c60_anousu = c61_anousu
	                        inner join conplanoexe on c62_anousu = c61_anousu and c61_reduz = c62_reduz
		               where c61_instit =".db_getsession("DB_instit")." and  c61_anousu =".db_getsession("DB_anousu")." and   c61_reduz = $codred
                   ) as saldo ";

  $result = db_query($campos);
  // db_criatabela($result);exit;
  db_fieldsmemory($result,0);
  $funcao_js = split("\|",$funcao_js);
  echo "<script>".$funcao_js[0]."('$codred','$c60_descr','".db_formatar($saldo_final,'f')."','$sinal_final');</script>";
}
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload='document.form2.chave_c62_reduz.focus();'>
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr>
    <td height="63" align="center" valign="top">
      <table width="35%" border="0" align="center" cellspacing="0">
        <form name="form2" method="post" action="" >
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tc62_reduz?>">
              <?=$Lc62_reduz?>
            </td>
            <td width="96%" align="left" nowrap>
              <? db_input("c62_reduz",6,$Ic62_reduz,true,"text",4,"","chave_c62_reduz"); ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tc60_estrut?>">
              <?=$Lc60_estrut?>
            </td>
            <td width="96%" align="left" nowrap>
              <? db_input("c60_estrut",15,$Ic60_estrut,true,"text",4,"","chave_c60_estrut"); ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tc60_descr?>">
              <?=$Lc60_descr?>&nbsp;
            </td>
            <td width="96%" align="left" nowrap>
              <?  db_input("c60_descr",40,$Ic60_descr,true,"text",4,"","chave_c60_descr");   ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_conplanoexe.hide();">
            </td>
          </tr>
        </form>
      </table>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?
      $campos = "c62_reduz,c60_estrut,c61_instit,c60_descr,c60_finali";
      if(!isset($pesquisa_chave)){
        if(isset($chave_c62_reduz) && (trim($chave_c62_reduz)!="") ){
          $sql = $clconplanoexe->sql_descr(null,$chave_c62_reduz ,$campos,"c60_estrut","c61_instit =".db_getsession("DB_instit")." and c62_anousu =$anousu and c62_reduz=$chave_c62_reduz and {$sWhere}");
        }else if(isset($chave_c60_descr) && (trim($chave_c60_descr)!="") ){
          $sql = $clconplanoexe->sql_descr(null,"",$campos,"c60_estrut","upper(c60_descr) like '$chave_c60_descr%' and c61_instit =".db_getsession("DB_instit")." and c62_anousu=$anousu and {$sWhere}");
        }else if(isset($chave_c60_estrut) && (trim($chave_c60_estrut)!="") ){
          $sql = $clconplanoexe->sql_descr(null,"",$campos,"c60_estrut","c60_estrut like '$chave_c60_estrut%' and c61_instit =".db_getsession("DB_instit")." and c62_anousu=$anousu  and {$sWhere}");
        }else{
          $sql = $clconplanoexe->sql_descr(db_getsession('DB_anousu'),"",$campos,"c60_estrut","c61_instit =".db_getsession("DB_instit")." and c62_anousu=$anousu and {$sWhere}");
        }
        db_lovrot($sql,15,"()","",'js_mostrasaldo|c62_reduz|c6_descr','Estrutural|js_mostraestrutural');
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){

          $chave_c62_reduz = empty($chave_c62_reduz) ? null : $chave_c62_reduz;
          $result = $clconplanoexe->sql_record($clconplanoexe->sql_descr(null,$chave_c62_reduz ,$campos,"c62_reduz","c61_instit =".db_getsession("DB_instit")." and c62_anousu =$anousu and c62_reduz=$pesquisa_chave and {$sWhere}"));
          if($clconplanoexe->numrows!=0){
            db_fieldsmemory($result,0);
            $campos = "
	       select c62_reduz,c60_descr,
	                  round(substr(fc_planosaldonovo,3,14)::float8,2)::float8 as saldo_anterior,
		              round(substr(fc_planosaldonovo,17,14)::float8,2)::float8 as saldo_anterior_debito,
		              round(substr(fc_planosaldonovo,31,14)::float8,2)::float8 as saldo_anterior_credito,
		              round(substr(fc_planosaldonovo,45,14)::float8,2)::float8 as saldo_final,
		              substr(fc_planosaldonovo,59,1)::varchar(1) as sinal_anterior,
		              substr(fc_planosaldonovo,60,1)::varchar(1) as sinal_final
              from (
                 select c62_reduz,c60_descr,
                            fc_planosaldonovo(".db_getsession("DB_anousu").",c61_reduz,'".db_getsession("DB_anousu")."-01-01','".$dataret."') 
	            from conplanoreduz 
		               inner join conplano on c60_codcon = c61_codcon and c60_anousu=c61_anousu
	                   inner join conplanoexe on c62_anousu = c61_anousu and c61_reduz = c62_reduz
	            where
                   conplanoreduz.c61_anousu = $anousu and c61_instit =".db_getsession("DB_instit")." and
		           conplanoreduz.c61_reduz = $pesquisa_chave
                   ) as saldo ";
            $result = db_query($campos);
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$c60_descr',false,'".db_formatar($saldo_final,'f')."','$sinal_final');</script>";
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
</body>
</html>
<script>
  function js_mostrasaldo(codigo,descr){
    location.href='func_conplanoexelanc.php?funcao_js=<?=$funcao_js?>&codred='+codigo+'&dataret=<?=$dataret?>';
  }

  function js_mostraestrutural(codigo){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_estrutura','func_conplanoestrutural.php?conta='+codigo,'Pesquisa',true);
  }


</script>

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
