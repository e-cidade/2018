<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
require_once(modification("libs/db_utils.php"));
include(modification("classes/db_inicial_classe.php"));
include(modification("classes/db_inicialcert_classe.php"));
include(modification("classes/db_inicialmov_classe.php"));
include(modification("classes/db_inicialnomes_classe.php"));
include(modification("classes/db_inicialnumpre_classe.php"));
include(modification("classes/db_arrecad_classe.php"));
include(modification("dbforms/db_funcoes.php"));

db_postmemory($HTTP_POST_VARS);

$clinicial = new cl_inicial;
$clinicialcert = new cl_inicialcert;
$clinicialmov = new cl_inicialmov;
$clinicialnomes = new cl_inicialnomes;
$clinicialnumpre = new cl_inicialnumpre;
$clarrecad = new cl_arrecad;

$clrotulo = new rotulocampo;
$clrotulo->label("v13_certid");
$clrotulo->label("v50_advog");
$clrotulo->label("v54_descr");
$clrotulo->label("v50_codlocal");
$clrotulo->label("z01_nome");

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script>
function js_submit(){
	if(document.form1.v13_certidini.value=="" && document.form1.v13_certidfim.value==""){
		alert(_M('tributario.juridico.jur4_gerainicial001.escolha_intervalo'));
		return false;
	}else{
		return true;
	}
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC>
<form class="container" name="form1" method="post" action="">
<fieldset>
  <legend>Procedimentos - Inicial/Inclusão (intervalo)</legend>
      <?
      if(isset($processar)){
	 	db_criatermometro('termometro','Concluido...','blue',1);
      }else{
      ?>
  <table class="form-container">
      <tr>
         <td>
         <?
       	 db_ancora('<b>Certidao Inicial: </b>',' js_certidini(true); ',1);
      	 ?>
      	 </td>
         <td>
      	 <?
         db_input('v13_certidini',5,$Iv13_certid,true,'text',1,"onchange='js_certidini(false)'");
         ?>
         <?
         db_ancora('<b>Certidao Final: </b>',' js_certidfim(true); ',1);
      	 ?>
      	 <?
       	 db_input('v13_certidfim',5,$Iv13_certid,true,'text',1,"onchange='js_certidfim(false)'");
      	 ?>
         </td>
      </tr>
      <tr>
      	<td><b>Agrupar por:</b></td>
      	<td>
      	<?
      	$tipo_arr = array("mi"=>"Matricula e Inscrição","c"=>"CGM","n"=>"Não Agrupar");
      	db_select("agrupa",$tipo_arr,true,"text",1);
      	?>
      	</td>
      </tr>
      <tr>
       	<td  title="<?=@$Tv50_advog?>">
      	<?
       	db_ancora("<strong>Advogado</strong>",' js_advog(true); ',1);
      	?>
       	</td>
       	<td>
      	<?
       	db_input('v50_advog',6,$Iv50_advog,true,'text',1,"onchange='js_advog(false)'");
       	db_input('z01_nome',40,$Iz01_nome,true,'text',3);
      	?>
       	</td>
     </tr>
  	 <tr>
    	<td nowrap title="<?=@$Tv50_codlocal?>">
       	<?
       	db_ancora("<strong>Local Foro</strong>","js_codlocal(true);",1);
       	?>
    	</td>
    	<td>
		<?
		db_input('v50_codlocal',6,$Iv50_codlocal,true,'text',1," onchange='js_codlocal(false);'")
		?>
       	<?
		db_input('v54_descr',40,$Iv54_descr,true,'text',3)
       	?>
    	</td>
  	  </tr>
    </table>
    </fieldset>
          <input  name="processar" id="processar" type="submit" value="Processar" onclick="return js_submit();" >
      <?
      }
      ?>
  </form>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_certidini(mostra){
  var certid=document.form1.v13_certidini.value;
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe','func_certidalt.php?funcao_js=parent.js_mostracertidini|0','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe','func_certidalt.php?pesquisa_chave='+certid+'&funcao_js=parent.js_mostracertidini1','Pesquisa',false);
  }
}
function js_mostracertidini(chave1){
  document.form1.v13_certidini.value = chave1;
  document.form1.v13_certidfim.value = chave1;
  db_iframe.hide();
}
function js_mostracertidini1(chave,erro){
  if(erro==true){
    alert(_M('tributario.juridico.jur4_gerainicial001.certidao_invalido'));
    document.form1.v13_certidini.focus();
  }
}
function js_certidfim(mostra){
  var certid=document.form1.v13_certidfim.value;
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe','func_certidalt.php?funcao_js=parent.js_mostracertidfim|0','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe','func_certidalt.php?pesquisa_chave='+certid+'&funcao_js=parent.js_mostracertidfim1','Pesquisa',false);
  }
}
function js_mostracertidfim(chave1){
	chave = new Number(chave1);
	ini = new Number(document.form1.v13_certidini.value);
	if (chave1<ini){
  		alert(_M('tributario.juridico.jur4_gerainicial001.certidao_invalido_final_menor_inicial'));
  		document.form1.v13_certidfim.value = "";
    	document.form1.v13_certidfim.focus();
    	db_iframe.hide();
  	}else{
  		document.form1.v13_certidfim.value = chave1;
  		db_iframe.hide();
  	}
}
function js_mostracertidfim1(chave,erro){
  if(erro==true){
    alert(_M('tributario.juridico.jur4_gerainicial001.certidao_invalido'));
    document.form1.v13_certidfim.value = "".
    document.form1.v13_certidfim.focus();
  }else{
  	ini = new Number(document.form1.v13_certidini.value);
  	fim = new Number(document.form1.v13_certidfim.value);
  	if (fim<=ini){
  		alert(_M('tributario.juridico.jur4_gerainicial001.certidao_invalido_final_menor_inicial'));
  		document.form1.v13_certidfim.value = "";
    	document.form1.v13_certidfim.focus();
  	}
  }
}
function js_codlocal(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_localiza','func_localiza.php?funcao_js=parent.js_mostralocaliza1|v54_codlocal|v54_descr','Pesquisa',true);
  }else{
     if(document.form1.v50_codlocal.value != ''){
        js_OpenJanelaIframe('','db_iframe_localiza','func_localiza.php?pesquisa_chave='+document.form1.v50_codlocal.value+'&funcao_js=parent.js_mostralocaliza','Pesquisa',false);
     }else{
       document.form1.v54_descr.value = '';
     }
  }
}
function js_mostralocaliza(chave,erro){
  document.form1.v54_descr.value = chave;
  if(erro==true){
    document.form1.v50_codlocal.focus();
    document.form1.v50_codlocal.value = '';
  }
}
function js_mostralocaliza1(chave1,chave2){
  document.form1.v50_codlocal.value = chave1;
  document.form1.v54_descr.value = chave2;
  db_iframe_localiza.hide();
}
function js_advog(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_advog','func_advog.php?funcao_js=parent.js_mostraadvog1|v57_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.v50_advog.value != ''){
        js_OpenJanelaIframe('','db_iframe_advog','func_advog.php?pesquisa_chave='+document.form1.v50_advog.value+'&funcao_js=parent.js_mostraadvog','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = '';
     }
  }
}
function js_mostraadvog(chave,erro){
  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.v50_advog.focus();
    document.form1.v50_advog.value = '';
  }
}
function js_mostraadvog1(chave1,chave2){
  document.form1.v50_advog.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_advog.hide();
}
</script>
<?
if (isset($processar)) {

	db_inicio_transacao();
	$sqlerro=false;
	$totini=0;
	$where = "and v13_certid  between $v13_certidini and $v13_certidfim ";

	$order_by = " order by ";
	if ($agrupa=="mi"){
		$order_by .= "matric,inscr";
	}else if ($agrupa=="c"){
		$order_by .= "numcgm";
	}else{
		$order_by .= "certid";
	}

	$sql = "select *
	          from ( select distinct
                          v13_certid as certid,
                          k00_matric as matric,
                          k00_inscr as inscr,
                          case
                             when arrematric.k00_numpre is not null
                               then ( select z01_cgmpri
                                        from proprietario_nome
                                       where arrematric.k00_matric = proprietario_nome.j01_matric
                                       limit 1 )
                             else arrecad.k00_numcgm
                          end as numcgm
			               from certid
	                     		inner join certdiv           on certdiv.v14_certid    = certid.v13_certid
	                     		inner join divida            on certdiv.v14_coddiv    = divida.v01_coddiv
    	               			inner join arrecad           on arrecad.k00_numpre    = divida.v01_numpre
			               			inner join arreinstit        on arreinstit.k00_numpre = arrecad.k00_numpre
			               			                            and arreinstit.k00_instit = ".db_getsession('DB_instit')."
		                   		left  join inicialcert       on certid.v13_certid     = inicialcert.v51_certidao
    	               			left  join arrematric        on arrecad.k00_numpre    = arrematric.k00_numpre
                          left  join arreinscr         on arrecad.k00_numpre    = arreinscr.k00_numpre
			               where divida.v01_instit = ".db_getsession('DB_instit') ."
			                 and certid.v13_instit = ".db_getsession('DB_instit') ."
			                 and inicialcert.v51_certidao is null
			                 {$where}

	      	           union

	      	          select distinct
                           certid.v13_certid as certid,
                           arrematric.k00_matric as matric,
                           arreinscr.k00_inscr as inscr,
                           case
                              when arrematric.k00_numpre is not null
                                then ( select z01_cgmpri
                                         from proprietario_nome
                                        where arrematric.k00_matric = proprietario_nome.j01_matric
                                        limit 1 )
                               else arrecad.k00_numcgm
                           end as numcgm
		          	      from certid
	                		     inner join certter           on certter.v14_certid    = certid.v13_certid
	                		     inner join termo             on termo.v07_parcel      = certter.v14_parcel
		          				                                 and termo.v07_instit      = ".db_getsession('DB_instit')."
                    	     inner join arrecad           on arrecad.k00_numpre    = termo.v07_numpre
		          				     inner join arreinstit        on arreinstit.k00_numpre = arrecad.k00_numpre
		          				                                 and arreinstit.k00_instit = ".db_getsession('DB_instit')."
                  		     left  join inicialcert       on certid.v13_certid     = inicialcert.v51_certidao
                           left  join arrematric        on arrecad.k00_numpre    = arrematric.k00_numpre
                			     left  join arreinscr         on arrecad.k00_numpre    = arreinscr.k00_numpre
		          	     where certid.v13_instit = ".db_getsession('DB_instit') ."
		          	       and inicialcert.v51_certidao is null
		          	       {$where}
 			           ) as x {$order_by}";

	$result  = db_query($sql);
	$numrows = pg_numrows($result);

  $matric_ant = "";
	$inscr_ant  = "";
	$cgm_ant    = "";
	$gera       = false;
	$ini_ini    = "";
	$ini_fim    = "";

	if ($numrows > 0){

		$gera = false;
		for ($w = 0; $w < $numrows; $w++){

			db_fieldsmemory($result,$w);
			db_atutermometro($w,$numrows,'termometro');

      $oCertidao = new Certidao($certid);

      if ($oCertidao->isCobrancaExtrajudicial()){

        $sqlerro  = true;
        $erro_msg = "Operação inválida! A Certidão $certid está sob Cobrança Extrajudicial.";
        break;
      }

			if ($agrupa == "mi"){

				if ($matric != ""){

					if ($matric != $matric_ant){
						$gera = true;
						$matric_ant = $matric;
					} else {
						$gera = false;
					}

				} else if ($inscr != "") {

          if ($inscr != $inscr_ant){
						$gera = true;
						$inscr_ant = $inscr;
					} else {
						$gera = false;
					}
				} else {
					$gera = true;
				}
			} else if ($agrupa == "c"){

				if ($numcgm != $cgm_ant){
					$gera = true;
					$cgm_ant = $numcgm;
				} else {
					$gera = false;
				}
			} else {
				$gera = true;
			}

      if ($gera == true) {

        $sSqlInicialCert  = $clinicialcert->sql_query(null, null, "v51_certidao", null, "inicialcert.v51_certidao = $certid and inicial.v50_situacao = 1");
        $rsSqlInicialCert = $clinicialcert->sql_record($sSqlInicialCert);

        if ($clinicialcert->numrows > 0) {
          $gera = false;
        }
      }

			if ($gera == true) {

				if ($sqlerro == false) {

					$usuario = db_getsession("DB_id_usuario");
					$data    = date("Y-m-d", db_getsession("DB_datausu"));

          $clinicial->v50_instit   = db_getsession("DB_instit");
					$clinicial->v50_advog    = $v50_advog;
					$clinicial->v50_data     = $data;
					$clinicial->v50_id_login = $usuario;
					$clinicial->v50_codlocal = $v50_codlocal;
					$clinicial->v50_codmov   = "0";
					$clinicial->v50_situacao = "1";
					$clinicial->incluir(null);

          $erro_msg = $clinicial->erro_msg;
					$inicial  = $clinicial->v50_inicial;

					if ($clinicial->erro_status == 0) {
					  $sqlerro = true;
					}

					if ($ini_ini == "") {
						$ini_ini = $inicial;
					}

					$ini_fim = $inicial;
				}

				if ($sqlerro == false) {

					$clinicialmov->atuinicialmov($inicial, "1");
					if ($clinicialmov->erro_status == 0) {
		  			$sqlerro  = true;
		  			$erro_msg = $clinicialmov->erro_msg;
					}
				}

				$totini++;
			}

			//Verifica se tem um aja incluida
			$sSqlInicialCert  = $clinicialcert->sql_query_file($inicial, $certid);
			$rsSqlInicialCert	= $clinicialcert->sql_record($sSqlInicialCert);

			if ($clinicialcert->numrows > 0){
				continue;
			}

			if($sqlerro == false){

				$clinicialcert->v51_certidao = $certid;
				$clinicialcert->v51_inicial  = $inicial;
				$clinicialcert->incluir($inicial, $certid);

        if ($clinicialcert->erro_status == 0){

          $sqlerro  = true;
	  			$erro_msg = $clinicialcert->erro_msg;
				}
			}

			if ($sqlerro == false){

				$sql_info = "select distinct
				                    k00_numpre,
				                    k00_numcgm
				               from ( select distinct
				                             k00_numpre,
				                             k00_numcgm
							                  from certid
					      		                 inner join certdiv     on certdiv.v14_certid = certid.v13_certid
					      		                 inner join divida      on certdiv.v14_coddiv = divida.v01_coddiv
										                                       and divida.v01_instit  = ".db_getsession('DB_instit')."
             				                 inner join arrenumcgm  on divida.v01_numpre  = arrenumcgm.k00_numpre
							                 where certid.v13_certid = {$certid}
							                   and certid.v13_instit = ".db_getsession('DB_instit') ."

	      	                     union

					      	            select distinct
					      	                   k00_numpre,
					      	                   k00_numcgm
							                  from certid
					      		                 inner join certter     on certter.v14_certid = certid.v13_certid
					      		                 inner join termo       on termo.v07_parcel   = certter.v14_parcel
										                                       and termo.v07_instit   = ".db_getsession('DB_instit') ."
								                     inner join arrenumcgm  on termo.v07_numpre   = arrenumcgm.k00_numpre
							                 where certid.v13_certid = {$certid}
							                   and v13_instit = ".db_getsession('DB_instit') ." ) as x ";

        $result_info  = db_query($sql_info);
				$numrows_info = pg_numrows($result_info);

        for ($i = 0; $i < $numrows_info; $i++){

          db_fieldsmemory($result_info, $i);
					if ($k00_numcgm == 0 or $k00_numpre == 0) {
	    			continue;
	  			}

	  			$result_nomes = $clinicialnomes->sql_record($clinicialnomes->sql_query_file($inicial, $k00_numcgm));
	  			if ($clinicialnomes->numrows == 0) {

	   				if ($sqlerro == false) {

	      			$clinicialnomes->v58_inicial = $inicial;
	      			$clinicialnomes->v58_numcgm  = $k00_numcgm;
	      			$clinicialnomes->incluir($inicial, $k00_numcgm);
	      			if ($clinicialnomes->erro_status == 0){

                $sqlerro  = true;
							  $erro_msg = $clinicialnomes->erro_msg;
							  break;
	      			}
	    			}
	  			}

	  			if ($sqlerro == false) {

            $numpre = $k00_numpre;
	    			$result_existeininum = $clinicialnumpre->sql_record($clinicialnumpre->sql_query_file(null, "*", null, "v59_inicial={$inicial} and v59_numpre={$k00_numpre}"));
	    			if ($clinicialnumpre->numrows == 0) {

	    				$clinicialnumpre->v59_inicial = $inicial;
	      			$clinicialnumpre->v59_numpre  = $numpre;
	      			$clinicialnumpre->incluir();

              $numpre = $k00_numpre;
	      			if ($clinicialnumpre->erro_status == 0) {

                $sqlerro  = true;
							  $erro_msg = $clinicialnumpre->erro_msg;
							  break;
	      			}
	    			}
	  			}

	  			if ($sqlerro == false) {

	  				$oDaoPardiv = db_utils::getDao('pardiv');
	  				$rsPardiv   = $oDaoPardiv->sql_record($oDaoPardiv->sql_query_file(db_getsession('DB_instit')));

	  				if($oDaoPardiv->numrows > 0) {

              $oPardiv  = db_utils::fieldsMemory($rsPardiv, 0);
	  					$iTipoini = $oPardiv->v04_tipoinicial;
	  				}

	    			$clarrecad->k00_tipo = $iTipoini;
	    			$clarrecad->alterar_arrecad("k00_numpre = $k00_numpre");

            if ($clarrecad->erro_status == 0){

              $sqlerro  = true;
	      			$erro_msg = $clarrecad->erro_msg;
	      			break;
	    			}
	  			}
				}
			}
		}

	} else {
		db_msgbox(_M('tributario.juridico.jur4_gerainicial001.certidao_nao_encontrada'));
		echo "<script>location.href='jur4_gerainicial001.php';</script>";
	}

	db_fim_transacao($sqlerro);
	if ($sqlerro==false&&$totini>0) {

		$oPamrs              = new stdClass();
		$oPamrs->iTotal      = $totini;
		$oPamrs->dataInicial = $ini_ini;
		$oPamrs->dataFinal   = $ini_fim;

		//db_msgbox("Foram geradas $totini inicial(ais)!!Da inicial $ini_ini ate $ini_fim!!");
		db_msgbox(_M('tributario.juridico.jur4_gerainicial001.totais_geradas', $oPamrs));
    echo "<script>location.href='jur4_gerainicial001.php';</script>";

  } else {
    db_msgbox($erro_msg);
		echo "<script>location.href='jur4_gerainicial001.php';</script>";
	}
}
?>
<script>

$("v13_certidini").addClassName("field-size2");
$("v13_certidfim").addClassName("field-size2");
$("v50_advog").addClassName("field-size2");
$("z01_nome").addClassName("field-size7");
$("v50_codlocal").addClassName("field-size2");
$("v54_descr").addClassName("field-size7");

</script>