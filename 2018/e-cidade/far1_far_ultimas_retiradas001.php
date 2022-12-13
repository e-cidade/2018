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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_utils.php");
include("dbforms/db_funcoes.php");


$db_opcao=1;
$db_botao=true;
$sql="select * from far_retiradaitens";
?>
<form name="form2" method="post" action="">
<center>
<fieldset><legend><b>Últimas Retiradas</b></legend>
	<table border="0">
			  <tr>
			    <td>
			      <?
			          $sql="select fa04_i_codigo,fa07_i_matrequi,fa04_d_data,m77_lote,fa06_t_posologia,fa06_f_quant from far_retiradaitens
                               inner join far_retirada on fa06_i_retirada=fa04_i_codigo
                               inner join far_matersaude on fa06_i_matersaude=fa01_i_codigo
                               inner join far_retiradarequi on fa04_i_codigo=fa07_i_retirada
                               left join far_retiradaitemlote on fa06_i_codigo=fa09_i_retiradaitens
                               left join matestoqueitemlote on fa09_i_matestoqueitem=m77_matestoqueitem
                             where
                               fa04_i_cgsund=$cgs
                               and fa06_i_matersaude=$medicamento
                             order by fa04_d_data desc";
                      $repassa = array("cgs"=>$cgs,"remedio"=>$medicamento);
			          //echo"SQL: $sql";
                      db_lovrot($sql,15,"()","","","","NoMe",$repassa);
			      ?>
			    </td>
			  </tr>
			  	</table>
</fieldset>
</center>
<p>
<input name="fechar" type="button" id="fechar" value="Fechar" onclick="js_fechar();">

</form>

<script type="text/javascript">

//Tempo estimado para fechar janela para não demorar no agendamento
window.setInterval(js_fechar, 60000 );

function js_fechar(){
	parent.db_iframe_ultimas.hide();
}
</script>