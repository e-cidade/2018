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
require_once(modification("classes/db_itbi_classe.php"));
require_once(modification("classes/db_itbinome_classe.php"));
include_once(modification("libs/db_app.utils.php"));

$situacao = "";
$tipo     = "";
$sWhereLogradouro = "";

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

if(!isset($setorCodigo)) {
	$setorCodigo = '';
}

if(!isset($quadra)) {
	$quadra = '';
}
if(!isset($lote)) {
	$lote = '';
}

$clitbi		   = new cl_itbi;
$clitbinome  = new cl_itbinome;

$clitbi->rotulo->label("it01_guia");

$clrotulo = new rotulocampo;
$clrotulo->label("j01_matric");
$clrotulo->label("j34_setor");
$clrotulo->label("j34_quadra");
$clrotulo->label("j34_lote");
$clrotulo->label("it18_nomelograd");

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?
	db_app::load('estilos.css');
	db_app::load('scripts.js, prototype.js, strings.js, DBViewPesquisaSetorQuadraLote.js, dbcomboBox.widget.js');
?>
</head>
<body bgcolor=#CCCCCC>
<form name="form2" id="form2" method="post" action="" >
<table align="center">
	<tr>
		<td title="<?=$Tit01_guia?>">
			<?=$Lit01_guia?>
		</td>
		<td>
    <?
    	db_input("it01_guia",10,$Iit01_guia,true,"text",4,"","chave_it16_guia");
		?>
		</td>
	</tr>

	<tr>
		<td>
		<?
    	db_ancora("<b>Matrícula :</b>",' js_matri(true); ',1);
		?>
		</td>
		<td>
		<?
			db_input('j01_matric',10,$Ij01_matric,true,'text',1,"onchange='js_matri(false)'");
			db_input('z01_nome',35,0,true,'text',3,"","z01_nomematri");
		?>
		</td>
	</tr>

	<tr>
		<td title="<?=@$Tj34_setor?>">
			<?=$Lj34_setor?>
		</td>
		<td>
		<?
			db_input('j34_setor',10,$Ij34_setor,true,'text',1);
		?>
		<?=$Lj34_quadra?>
		<?
			db_input('j34_quadra',10,$Ij34_quadra,true,'text',1);
		?>
		<?=$Lj34_lote?>
		<?
			db_input('j34_lote',10,$Ij34_lote,true,'text',1);
		?>
		</td>
	</tr>

	<tr>
    <td>
      <b>Logradouro :</b>
    </td>
    <td>
      <?
        db_input('logradouroid',40,'',true,'hidden',3);
        db_input('it18_nomelograd',40,$Iit18_nomelograd,true,'text',1);
      ?>
    </td>
  </tr>

  <tr>
    <td>
      <b>Tipo :</b>
    </td>
    <td>
      <?
        $aTipo = array( 't'=>'Todos',
                        'u'=>'Urbano',
                        'r'=>'Rural' );

        db_select('tipo',$aTipo,true,2," style='width:275px;'");
       ?>
    </td>
  </tr>

  <tr>
    <td>
      <b>Periodo de :</b>
    </td>
    <td>
      <?
        db_inputdata('dtIni', '', '', '', true, 'text', 1, '');
      ?>
      &nbsp;
      <b> a </b>
      &nbsp;
      <?
        db_inputdata('dtFim', '', '', '', true, 'text', 1, '');
      ?>
    </td>
	</tr>

  <!-- Se for o filtro para cancela ITBI não exibe a linha -->
  <tr <?php echo ( isset($lcancelaitbi) && $lcancelaitbi = 'cancela' ) ? 'style="display: none"' : ''?>>
    <td>
			<b>Situaçao:</b>
		</td>
		<td>
		<?
			$aSituacao = array( '1'=>'Todos',
													'2'=>'Aberto',
													'3'=>'Pago',
													'4'=>'Cancelado');

      /**
       * Se for o filtro para cancela ITBI
       * exibe somente as opções em aberto
       */
      if ( isset($lcancelaitbi) && $lcancelaitbi = 'cancela' ) {
        $aSituacao = array('2'=>'Aberto');
      }

			db_select('situacao',$aSituacao,true,2," style='width:275px;'");
		?>
		</td>
	</tr>

  <tr>
    <td colspan="2" align="center">
      <div id="pesquisa"></div>
    </td>
  </tr>

  <tr>
    <td colspan="2" align="center">
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar" type="reset" id="limpar" value="Limpar" >
      <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_itbicancela.hide();">
    </td>
  </tr>

</table>
</form>
<table align="center">
  <tr>
    <td align="center" valign="top">
      <?

        if( empty($situacao) && isset($lcancelaitbi) && $lcancelaitbi = 'cancela'){
          $situacao = 2;
        }

		    $sWhere  = "     it16_guia is null 				                                                       ";
		    $sWhere .= "     and case 						 	                                                         ";
			  $sWhere .= "   	 when db_usuarios.usuext = 1 then                                                ";
			  $sWhere .= " 		   case                                                                          ";
			  $sWhere .= " 		 	 when itbi.it01_id_usuario = ".db_getsession('DB_id_usuario')." then true      ";
			  $sWhere .= " 		     else false                                                                  ";
			  $sWhere .= " 		   end                                                                           ";
			  $sWhere .= " 		 else                                                                            ";
			  $sWhere .= " 	     case                                                                          ";
			  $sWhere .= " 	 	   when itbi.it01_coddepto = ".db_getsession('DB_coddepto')." then true          ";
			  $sWhere .= " 		   else false                                                                    ";
			  $sWhere .= "       end                                                                           ";
			  $sWhere .= "     end                                                                             ";
			  /*$sWhere .= "    and case
			                         when itbinome.it03_guia is not null then
			                           case
			                             when itbinome.it03_princ is true and itbinome.it03_tipo = 'C' then true
			                             else false
			                           end
                               else true
                            end  ";
		    */
			  if ( isset($liberada) && $liberada == 'true') {
			  	$sWhere .= " and it14_guia  is not null                                                        ";
			  } else if ( isset($liberada) && $liberada == 'false') {
			  	$sWhere .= " and it14_guia  is null                                                            ";
			  }

			  if (isset($j01_matric) && trim($j01_matric) != "" ) {
          $sWhere .= " and it06_matric = $j01_matric";
        }

        if ( isset($dtfim) && isset($dtini) ) {

          $dtIni = implode("-",array_reverse(explode("/",$dtini)));
          $dtFim = implode("-",array_reverse(explode("/",$dtfim)));

          if ( !empty($dtIni) && !empty($dtFim) ) {
            $sWhere        .= " and it01_data between '{$dtIni}' and '{$dtFim}'";
          } else if ( !empty($dtIni) ) {
            $sWhere        .= " and it01_data >= '{$dtIni}' ";
          } else if ( !empty($dtFim) ) {
            $sWhere        .= " and it01_data <= '{$dtFim}' ";
          }

        }

        if ( isset($it18_nomelograd) && $it18_nomelograd != "" ) {
            $sWhereLogradouro = " where logradouro = '{$it18_nomelograd}' ";
        }

        if ( isset($j34_setor) && $j34_setor != "") {
          $sWhere  .= " and j34_setor = '" . str_pad($j34_setor,4,"0",STR_PAD_LEFT)."'";
        }

        if ( isset($j34_quadra) && $j34_quadra != "" ) {
          $sWhere  .= " and j34_quadra = '" . str_pad($j34_quadra,4,"0",STR_PAD_LEFT)."'";
        }

        if ( isset($j34_lote) && $j34_lote != "" ) {
          $sWhere  .= " and j34_lote = '" . str_pad($j34_lote,4,"0",STR_PAD_LEFT)."'";
        }

        if(isset($setorCodigo) || isset($quadra) || isset($lote)) {
	        if(isset($setor) and $setor != '') {
						$sWhere .= " and j05_codigoproprio = '{$setorCodigo}' ";
					}
					if(isset($quadra) and $quadra != '') {
						$sWhere .= " and j06_quadraloc = '{$quadra}' ";
					}
					if(isset($lote) and $lote != '') {
						$sWhere .= " and j06_lote = '{$lote}' ";
					}
        }

        if ( $situacao == "2" ) {
          $sWhere         .= " and arrepaga.k00_numpre is null";
          $sWhere         .= " and it16_guia is null";
        } else if ( $situacao == "3" ) {
          $sWhere         .= " and arrepaga.k00_numpre is not null";
        } else if ( $situacao == "4" ) {
          $sWhere         .= " and it16_guia is not null";
        }

        if ( $tipo == "u"  ) {
          $sWhere     .= " and it05_guia is not null ";
        } else if ( $tipo == "r"  ) {
          $sWhere     .= " and it18_guia is not null ";
        }

		      if(!isset($pesquisa_chave)){

		        $campos  = "distinct on (itbi.it01_guia) itbi.it01_guia, itbi.it01_data, itbi.it01_hora, itbinome.it03_nome,              ";
		        $campos .= " itbi.it01_tipotransacao,                                                                 ";
		        $campos .= " itbi.it01_areaterreno, itbi.it01_areaedificada, itbi.it01_obs, itbi.it01_valortransacao, ";
		        $campos .= " itbi.it01_areatrans, itbi.it01_mail, itbi.it01_finalizado, itbi.it01_origem,             ";
		        $campos .= " itbi.it01_id_usuario, itbi.it01_coddepto, itbi.it01_valorterreno, itbi.it01_valorconstr, ";
						$campos .= " case                                                                                     ";
						$campos .= "     when itbidadosimovel.it22_itbi is not null then it22_descrlograd                     ";
						$campos .= "     else itbirural.it18_nomelograd                                                       ";
						$campos .= " end as logradouro,                                                                       ";
		        $campos .= " itbicancela.*";

		        if(isset($chave_it16_guia) && (trim($chave_it16_guia)!="") ){
		           $sql = $clitbi->sql_query_itbi(null,$campos,"it01_guia",$sWhere." and it01_guia = $chave_it16_guia ",$sWhereLogradouro);
		        }else{
		           $sql = $clitbi->sql_query_itbi(null,$campos,"it01_guia",$sWhere,$sWhereLogradouro);
		        }

		        db_lovrot($sql,15,"()","",$funcao_js);

		      }else{

		        if($pesquisa_chave!=null && $pesquisa_chave!=""){

		          $sql = $clitbi->sql_query_canc(null,$campos,"it01_guia",$sWhere." and it01_guia = $pesquisa_chave ");
		          $result = db_query($sql);

		          if($clitbi->numrows!=0){
		            db_fieldsmemory($result,0);
		            echo "<script>".$funcao_js."('$it16_data',false);</script>";
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
</form>
<script>
function js_matri(mostra){
  var matri=document.form2.j01_matric.value;

  w = document.body.clientWidth - 20;
  h = document.body.clientHeight - 20;
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe3','func_matricitbi.php?valida=false&funcao_js=parent.js_mostramatri|0|1','Pesquisa',true, null, null, w, h);
  }else{
    js_OpenJanelaIframe('','db_iframe3','func_matricitbi.php?pesquisa_chave='+matri+'&funcao_js=parent.js_mostramatri1','Pesquisa',false);
  }
}
function js_mostramatri(chave1,chave2){
  document.form2.j01_matric.value = chave1;
  document.form2.z01_nomematri.value = chave2;
  db_iframe3.hide();
}
function js_mostramatri1(chave,erro){
  document.form2.z01_nomematri.value = chave;
  if(erro==true){
    document.form2.j01_matric.focus();
    document.form2.j01_matric.value = '';
  }
}
var oPesquisa = new DBViewPesquisaSetorQuadraLote('pesquisa', 'oPesquisa');
    oPesquisa.show();
    oPesquisa.appendForm();
<?
	echo "oPesquisa.setValues('{$setorCodigo}','{$quadra}','{$lote}');";
?>
</script>
</body>
</html>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
