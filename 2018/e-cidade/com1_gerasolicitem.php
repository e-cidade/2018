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
require_once(modification("classes/db_solicitem_classe.php"));
require_once(modification("classes/db_solicitemprot_classe.php"));
require_once(modification("classes/db_solicita_classe.php"));
require_once(modification("classes/db_pcparam_classe.php"));
require_once(modification("classes/db_pcorcamitemsol_classe.php"));
require_once(modification("classes/db_solandam_classe.php"));
require_once(modification("classes/db_solandpadraodepto_classe.php"));
db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);
$clsolicitem         = new cl_solicitem;
$clsolicitemprot     = new cl_solicitemprot;
$clsolicita          = new cl_solicita;
$clpcparam           = new cl_pcparam;
$clpcorcamitemsol    = new cl_pcorcamitemsol;
$clsolandam          = new cl_solandam;
$clsolandpadraodepto = new cl_solandpadraodepto;
$db_botao            = true;
$db_opcao            = 1;
if(isset($solicita) && trim($solicita)!=""){
  $where_liberado = "";
  $selecionalibera = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit"),"pc30_liberado,pc30_contrandsol"));
  if($clpcparam->numrows>0){
    db_fieldsmemory($selecionalibera,0);
    if($pc30_liberado=='f'){
      $where_liberado = " and pc11_liberado='t' ";
    }
  }

  $sCamposSolicitem   = "distinct ";
  $sCamposSolicitem  .="  solicitem.pc11_numero,                                                                                                  ";
  $sCamposSolicitem  .="                solicitem.pc11_codigo,                                                                                    ";
  $sCamposSolicitem  .="                solicitem.pc11_quant,                                                                                     ";
  $sCamposSolicitem  .="                solicitem.pc11_seq,                                                                                       ";
  $sCamposSolicitem  .="                solicitem.pc11_vlrun as valor_antigo,                                                                     ";
  $sCamposSolicitem  .="                                                                                                                          ";
  $sCamposSolicitem  .="                case when                                                                                                 ";
  $sCamposSolicitem  .="                  pc11_vlrun = 0                                                                                          ";
  $sCamposSolicitem  .="                    then (select pcorcamval.pc23_vlrun as pc11_vlrun                                                      ";
  $sCamposSolicitem  .="                            from solicitem s                                                                              ";
  $sCamposSolicitem  .="                                 inner join pcorcamitemsol on s.pc11_codigo               = pcorcamitemsol.pc29_solicitem ";
  $sCamposSolicitem  .="                                 inner join pcorcamitem    on pcorcamitem.pc22_orcamitem  = pcorcamitemsol.pc29_orcamitem ";
  $sCamposSolicitem  .="                                 inner join pcorcam        on pcorcam.pc20_codorc         = pcorcamitem.pc22_codorc       ";
  $sCamposSolicitem  .="                                 inner join pcorcamval     on pcorcamval.pc23_orcamitem   = pcorcamitem.pc22_orcamitem    ";
  $sCamposSolicitem  .="                                 inner join pcorcamforne   on pcorcamforne.pc21_codorc    = pcorcam.pc20_codorc           ";
  $sCamposSolicitem  .="                                                          and pcorcamforne.pc21_orcamforne = pcorcamval.pc23_orcamforne   ";
  $sCamposSolicitem  .="                                 inner join pcorcamjulg    on pcorcamjulg.pc24_orcamforne = pcorcamforne.pc21_orcamforne  ";
  $sCamposSolicitem  .="                                                          and pcorcamjulg.pc24_orcamitem  = pcorcamitem.pc22_orcamitem    ";
  $sCamposSolicitem  .="                                                          and pcorcamjulg.pc24_pontuacao  = 1                             ";
  $sCamposSolicitem  .="                           where s.pc11_codigo = solicitem.pc11_codigo)                                                   ";
  $sCamposSolicitem  .="                     else pc11_vlrun end as pc11_vlrun,                                                                   ";
  $sCamposSolicitem  .="                solicitem.pc11_resum,                                                                                     ";
  $sCamposSolicitem  .="                pcmater.pc01_codmater,                                                                                    ";
  $sCamposSolicitem  .="                pcmater.pc01_descrmater,                                                                                  ";
  $sCamposSolicitem  .="                pcmater.pc01_servico,                                                                                     ";
  $sCamposSolicitem  .="                solicitemunid.pc17_unid,                                                                                  ";
  $sCamposSolicitem  .="                solicitemunid.pc17_quant,                                                                                 ";
  $sCamposSolicitem  .="                matunid.m61_descr,                                                                                        ";
  $sCamposSolicitem  .="                matunid.m61_usaquant                                                                                      ";


  $sWhereSolicitem  = " pc11_numero = {$solicita} and ";
  $sWhereSolicitem .= " pc11_codigo not in (select distinct pc81_solicitem ";
  $sWhereSolicitem .= "                               from pcprocitem) {$where_liberado} ";

  $sSqlItensSolicitem = $clsolicitem->sql_query_pcmater(null,$sCamposSolicitem, "pc11_codigo", $sWhereSolicitem);

  $select_itens = $clsolicitem->sql_record($sSqlItensSolicitem);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>

var lMarcados = false;

function js_retornocampo(oCampo) {

  if (oCampo.checked) {

    if ((window.CurrentWindow || parent.CurrentWindow).corpo.arr_dados.indexOf(oCampo.name) == -1) {
      (window.CurrentWindow || parent.CurrentWindow).corpo.arr_dados.push(oCampo.name);
    }
  } else {
    (window.CurrentWindow || parent.CurrentWindow).corpo.arr_dados.remove(oCampo.name);
  }

  (window.CurrentWindow || parent.CurrentWindow).corpo.document.form1.valores.value = (window.CurrentWindow || parent.CurrentWindow).corpo.arr_dados.valueOf();
}

function js_setornosetimp(campo,SN){

  cont = 0;

  if (SN == true) {

    for (i = 0;i < (window.CurrentWindow || parent.CurrentWindow).corpo.arr_impor.length; i++ ){

      if ((window.CurrentWindow || parent.CurrentWindow).corpo.arr_impor[i] == campo) {

 	      cont++;
	      break;
      }
    }
  } else {
    cont = 0;
  }

  if (eval('document.form1.' + campo + '.checked') == true) {

    if (cont == 0) {
      (window.CurrentWindow || parent.CurrentWindow).corpo.arr_impor.push(campo);
    }
  } else {

    if (cont > 0) {
      (window.CurrentWindow || parent.CurrentWindow).corpo.arr_impor.splice(i,1);
    }
  }
  (window.CurrentWindow || parent.CurrentWindow).corpo.document.form1.importa.value = (window.CurrentWindow || parent.CurrentWindow).corpo.arr_impor.valueOf();
}

function js_marcacampos() {

  erro=0;
  campo = "";
  for(i=0;i<document.form1.length;i++){
    if(document.form1.elements[i].type == 'checkbox' && document.form1.elements[i].name.search('imp')==-1){
      if((window.CurrentWindow || parent.CurrentWindow).corpo.document.form1.valores.value.search(document.form1.elements[i].name)!=-1){
        document.form1.elements[i].checked = true;
      	campo = document.form1.elements[i].name;
      	for(ii=0;ii<(window.CurrentWindow || parent.CurrentWindow).corpo.arr_dados.length;ii++){
      	  if((window.CurrentWindow || parent.CurrentWindow).corpo.arr_dados[ii]==campo){
      	    erro++;
      	    break;
      	  }
      	}

      	if(erro==0 && campo!=""){
      	  (window.CurrentWindow || parent.CurrentWindow).corpo.arr_dados.push(campo);
      	}
      }
    }
  }
}

function js_marcacamposimp(){
  for(i=0;i<document.form1.length;i++){
    if(document.form1.elements[i].type == 'checkbox'){
      if((window.CurrentWindow || parent.CurrentWindow).corpo.document.form1.importa.value.search(document.form1.elements[i].name)!=-1){
        document.form1.elements[i].checked = true;
      }
    }
  }
}

/**
 * Marca ou desmarca todos os itens
 */
function js_marcar(){

  lMarcados = !lMarcados;

  for (var i = 0; i < document.form1.length; i++) {

    if (document.form1.elements[i].type == 'checkbox' && document.form1.elements[i].disabled == false && document.form1.elements[i].name.search('imp') == -1) {

      document.form1.elements[i].checked = lMarcados;
      js_retornocampo(document.form1.elements[i]);
    }
  }
}

</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.trcabecalhotabela {
	border-bottom:3px outset #FFF;
  height:20px;
}

.tdcabecalhotabela {
  border:1px outset #D3D3D3;
	background-color:#eeeeee;
  font-weight:bold;
  text-align:center;
  width:98%;
  border-collapse: collapse;
  padding:3px;
}

.trconteudotabela {
	border:1px outset #D3D3D3;
  padding:0;
  margin:0;
  white-space:nowrap;
  overflow:hidden;
  background-color:#FFF;
}

.tdconteudotabela {
	border:1px outset #D3D3D3;
  padding:0;
  margin:0;
  white-space:nowrap;
  overflow:hidden;
  background-color:#FFF;
  padding:3px;
}

</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form1">
<center>
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left" valign="top" bgcolor="#CCCCCC">
    <center>
    <?
    if($clsolicitem->numrows == 0){

      echo "<strong>Não existem itens para esta solicitação.</strong>\n";
    } else {

      echo "<center>";
      echo "<table id='girdItensSolicitacaobody' border='0' cellpadding='0' cellspacing='0' align='center' style='border:2px inset white'>\n";
      echo "<tr bgcolor='' class='trcabecalhotabela'>\n";
      echo "  <td nowrap class='tdcabecalhotabela' align='center'><strong>";db_ancora('M','js_marcar();',1);echo"</strong></td>\n";
      echo "  <td nowrap class='tdcabecalhotabela' align='center'><strong>Item</strong></td>\n";

      echo "  <td nowrap class='tdcabecalhotabela' align='center'><strong>Material</strong></td>\n";
      echo "  <td nowrap class='tdcabecalhotabela' align='center'><strong>Unidade</strong></td>\n";

      echo "  <td nowrap class='tdcabecalhotabela' align='center'><strong>Quantidade</strong></td>\n";
      echo "  <td nowrap class='tdcabecalhotabela' align='center'><strong>Valor Unitário</strong></td>\n";
      echo "  <td nowrap class='tdcabecalhotabela' align='center'><strong>Valor Total</strong></td>\n";
      echo "  <td nowrap class='tdcabecalhotabela' align='center'><strong>Referência</strong></td>\n";
      echo "  <td nowrap class='tdcabecalhotabela' align='center'><strong>Código</strong></td>\n";
      echo "  <td nowrap class='tdcabecalhotabela' align='center'><strong>Resumo</strong></td>\n";
      echo "  <td nowrap class='tdcabecalhotabela' align='center' title='Gerar, automaticamente, orçamento do processo de compras para este item, com base no orçamento de solicitação.'><strong>Imp</strong></td>\n";
      echo "</tr>\n";

      $readonly = "";

      for($i=0;$i<$clsolicitem->numrows;$i++){

	if($i % 2 == 0) {
	  $bgcolor = '#ccddcc';
	} else {
	  $bgcolor = '#aacccc';
	}
	db_fieldsmemory($select_itens,$i);
	$readonly = "";



	//---------------------------------------Controla Andamento da solicitação---------------------------------
	$result_conand = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit"),"pc30_contrandsol"));
	db_fieldsmemory($result_conand,0);
	if (isset($pc30_contrandsol)&&$pc30_contrandsol=='t'){
		$result_prot = $clsolicitemprot->sql_record($clsolicitemprot->sql_query_file(null,"*",null,"pc49_solicitem = $pc11_codigo"));
		if ($clsolicitemprot->numrows>0){
		$result_andam=$clsolandam->sql_record($clsolandam->sql_query_file(null,"*","pc43_codigo desc limit 1","pc43_solicitem=$pc11_codigo and pc43_ordem = 4"));
      	if ($clsolandam->numrows>0){
      		db_fieldsmemory($result_andam,0);
      	    $result_tipo=$clsolandpadraodepto->sql_record($clsolandpadraodepto->sql_query(null,"*",null,"pc47_solicitem=$pc11_codigo and pc47_ordem=$pc43_ordem"));
      	    if($clsolandpadraodepto->numrows>0){
      	    	db_fieldsmemory($result_tipo,0);
      	      	if ($pc47_pctipoandam!=3||$pc48_depto!=db_getsession("DB_coddepto")){
      	      		 $sqltran = "select distinct x.p62_codtran,
      x.pc11_numero,
x.pc11_codigo,
                            x.p62_dttran,
                            x.p62_hora,
                			x.descrdepto,
							x.login
			from ( select distinct p62_codtran,
                    p62_dttran,
                    p63_codproc,
                    descrdepto,
                    p62_hora,
                    login,
                    pc11_numero,
							      pc11_codigo,
                    pc81_codproc,
                    e55_autori,
							      e54_anulad
		           from proctransferproc
				            inner join solicitemprot        on pc49_protprocesso                   = proctransferproc.p63_codproc
				            inner join solicitem            on pc49_solicitem                      = pc11_codigo
				            inner join proctransfer         on p63_codtran                         = p62_codtran
										inner join db_depart            on coddepto                            = p62_coddepto
										inner join db_usuarios          on id_usuario                          = p62_id_usuario
										left  join pcprocitem           on pcprocitem.pc81_solicitem           = solicitem.pc11_codigo
                    left  join empautitempcprocitem on empautitempcprocitem.e73_pcprocitem = pcprocitem.pc81_codprocitem
                    left  join empautitem           on empautitem.e55_autori               = empautitempcprocitem.e73_autori
                                                   and empautitem.e55_sequen               = empautitempcprocitem.e73_sequen
										left join empautitem            on empautitem.e55_sequen               = pcprocitem.pc81_codprocitem
										left join empautoriza           on empautoriza.e54_autori              = empautitem.e55_autori
             where  p62_coddeptorec = ".db_getsession("DB_coddepto")."
         ) as x
				 left join proctransand 	on p64_codtran = x.p62_codtran
				 left join arqproc 	on p68_codproc = x.p63_codproc
			where p64_codtran is null and p68_codproc is null and x.pc11_codigo = $pc11_codigo";

			$result_tran=db_query($sqltran);
			if(pg_numrows($result_tran)==0){
      	        	$readonly = "disabled";
			}
        	    }
      	    }
      	}
      	$result_=$clsolicita->sql_record($clsolicita->sql_query_andsol("distinct pc11_numero,pc11_codigo,pc11_quant,pc11_seq,pc11_vlrun,pc11_resum,pc01_codmater,pc01_descrmater,pc01_servico,pc17_unid,pc17_quant,m61_descr,m61_usaquant","where pc11_codigo=$pc11_codigo and  p64_codtran is not null  and y.pc43_depto=".db_getsession("DB_coddepto")));
		if ($clsolicita->numrows==0){
			 $sqltran = "select distinct x.p62_codtran,
      x.pc11_numero,
x.pc11_codigo,
                            x.p62_dttran,
                            x.p62_hora,
                			x.descrdepto,
							x.login
			from ( select distinct p62_codtran,
                    p62_dttran,
                    p63_codproc,
                    descrdepto,
                    p62_hora,
                    login,
                    pc11_numero,
							      pc11_codigo,
                    pc81_codproc,
                    e55_autori,
							      e54_anulad
		           from proctransferproc
				            inner join solicitemprot        on pc49_protprocesso                   = proctransferproc.p63_codproc
				            inner join solicitem            on pc49_solicitem                      = pc11_codigo
				            inner join proctransfer         on p63_codtran                         = p62_codtran
									  inner join db_depart            on coddepto                            = p62_coddepto
				            inner join solandam             on pc43_solicitem                      = pc11_codigo
				                                           and pc43_depto                          = p62_coddepto
										inner join db_usuarios          on id_usuario                          = p62_id_usuario
										left  join pcprocitem           on pcprocitem.pc81_solicitem           = solicitem.pc11_codigo
                    left  join empautitempcprocitem on empautitempcprocitem.e73_pcprocitem = pcprocitem.pc81_codprocitem
                    left  join empautitem           on empautitem.e55_autori               = empautitempcprocitem.e73_autori
                                                   and empautitem.e55_sequen               = empautitempcprocitem.e73_sequen
										left  join empautoriza          on empautoriza.e54_autori              = empautitem.e55_autori
        		 where  p62_coddeptorec = ".db_getsession("DB_coddepto")." and pc43_ordem >= 3
         ) as x
         inner join solandpadrao  on pc47_solicitem = x.pc11_codigo
				 left join proctransand 	on p64_codtran = x.p62_codtran
				 left join arqproc 	on p68_codproc = x.p63_codproc
			where p64_codtran is null and p68_codproc is null and x.pc11_codigo = $pc11_codigo";

      $result_tran=db_query($sqltran);
			if(pg_numrows($result_tran)==0){
			$readonly="disabled";
			}
		}
	  	$result_transf = $clsolicitemprot->sql_record($clsolicitemprot->sql_query_transf(null,"*",null,"pc49_solicitem = $pc11_codigo and p64_codtran is null"));
    	if ($clsolicitemprot->numrows>0){
    		 $sqltran = "select distinct x.p62_codtran,
      x.pc11_numero,
x.pc11_codigo,
                            x.p62_dttran,
                            x.p62_hora,
                			x.descrdepto,
							x.login
			from ( select distinct p62_codtran,
                    p62_dttran,
                    p63_codproc,
                    descrdepto,
                    p62_hora,
                    login,
                    pc11_numero,
							      pc11_codigo,
                    pc81_codproc,
                    e55_autori,
							      e54_anulad
		           from proctransferproc
                    inner join solicitemprot        on pc49_protprocesso                   = proctransferproc.p63_codproc
                    inner join solicitem            on pc49_solicitem                      = pc11_codigo
                    inner join proctransfer         on p63_codtran                         = p62_codtran
										inner join db_depart            on coddepto                            = p62_coddepto
										inner join db_usuarios          on id_usuario                          = p62_id_usuario
										left  join pcprocitem           on pcprocitem.pc81_solicitem           = solicitem.pc11_codigo
                    left  join empautitempcprocitem on empautitempcprocitem.e73_pcprocitem = pcprocitem.pc81_codprocitem
                    left  join empautitem           on empautitem.e55_autori               = empautitempcprocitem.e73_autori
                                                   and empautitem.e55_sequen               = empautitempcprocitem.e73_sequen
										left join empautoriza           on empautoriza.e54_autori              = empautitem.e55_autori
       			 where  p62_coddeptorec = ".db_getsession("DB_coddepto")."
         ) as x
				 left join proctransand 	on p64_codtran = x.p62_codtran
				 left join arqproc 	on p68_codproc = x.p63_codproc
			where p64_codtran is null and p68_codproc is null and x.pc11_codigo = $pc11_codigo";

			$result_tran=db_query($sqltran);
			if(pg_numrows($result_tran)==0){
        	$readonly = " disabled ";
			}
        }
		}
	}
	//--------------------------------------------------------------------------------------------------------------------------------------

        echo "<tr class='trconteudotabela'>\n";
	      echo "  <td nowrap class='checkbox tdconteudotabela'><input type='checkbox' $readonly  name='item_".$pc11_numero."_".$pc11_codigo."' onclick='js_retornocampo(this);'></td>\n";
        echo "  <td nowrap class='tdconteudotabela' align='center'>$pc11_seq</td>\n";
      	echo "  <td class='tdconteudotabela' align='left'>  ".ucfirst(mb_strtolower($pc01_descrmater))."</td>\n";
      	echo "  <td nowrap class='tdconteudotabela' align='center'>" . (empty($m61_descr) ? "&nbsp;" : $m61_descr) . "</td>\n";
      	echo "  <td nowrap class='tdconteudotabela' align='center'>$pc11_quant</td>\n";
      	echo "  <td nowrap class='tdconteudotabela' align='right'>R$ ".db_formatar($pc11_vlrun, "f")."</td>\n";
      	echo "  <td nowrap class='tdconteudotabela' align='right'>R$ ".db_formatar(($pc11_vlrun*$pc11_quant),"f")."</td>\n";

        if((isset($pc01_servico) && (trim($pc01_servico)=="f" || trim($pc01_servico)=="")) || !isset($pc01_servico)){
          $unid = trim(substr($m61_descr,0,10));
          if($m61_usaquant=="t"){
            $unid .= " <BR>($pc17_quant UNIDADES)";
          }
        }else{
          $unid = "SERVIÇO";
        }

        echo "  <td nowrap='nowrap' class='tdconteudotabela' align='center'>$unid</td>\n";
	echo "  <td nowrap class='tdconteudotabela' align='center'>$pc01_codmater</td>\n";

        if(isset($pc11_resum) && trim($pc11_resum)==""){
          $pc11_resum="&nbsp;";
        }
	echo "  <td class='tdconteudotabela' bgcolor='$bgcolor' align='left'>".substr(stripslashes($pc11_resum),0,40)."</td>\n";
	$result_orcamitemsol = $clpcorcamitemsol->sql_record($clpcorcamitemsol->sql_query_solicitem(null,null,"pc22_codorc,pc22_orcamitem",""," pc29_solicitem=$pc11_codigo order by pc22_codorc desc"));
	if($clpcorcamitemsol->numrows>0){
	  db_fieldsmemory($result_orcamitemsol,0);
	  $orcamitemsol = "<input type='checkbox' name='imp_".$pc22_codorc."_".$pc11_codigo."_".$pc22_orcamitem."' checked onclick='js_setornosetimp(this.name,true);'>";
	  if(trim($pc22_codorc)==""){
	    $orcamitemsol = "<strong>Não</strong>";
	  }
	}else{
	  $orcamitemsol = "<strong>Não</strong>";
	}
	echo "  <td nowrap class='tdconteudotabela'>$orcamitemsol</td>\n";
        if($clpcorcamitemsol->numrows>0){
	  if(trim($pc22_codorc)!=""){
	    echo "<script>js_setornosetimp('imp_".$pc22_codorc."_".$pc11_codigo."_".$pc22_orcamitem."',false);</script>";
	  }
	}
        echo "</tr>\n";
      }
      echo "</table>\n";
      echo "</center>";
    }
    ?>
    </center>
    </td>
  </tr>
</table>
</center>
</form>
<script>
js_marcacampos();
js_marcacamposimp();
</script>
</body>
</html>