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

require_once('libs/db_stdlib.php');
require_once('libs/db_conecta.php');
require_once('libs/db_sessoes.php');
require_once('libs/db_usuariosonline.php');
require_once('dbforms/db_funcoes.php');
require_once('dbforms/db_classesgenericas.php');

db_postmemory($HTTP_POST_VARS);

$cliframe_seleciona = new cl_iframe_seleciona;
$clcertid           = new cl_certid;
$clcertdiv          = new cl_certdiv;
$clcertter          = new cl_certter;
$clarrecad          = new cl_arrecad;
$clarreforo         = new cl_arreforo;
$clinicialcert      = new cl_inicialcert;
$cllistacda         = new cl_listacda;
$clinicialnumpre    = new cl_inicialnumpre;
$clacertid          = new cl_acertid;
$clacertdiv         = new cl_acertdiv;
$clacertter         = new cl_acertter;
$clrotulo           = new rotulocampo;
$clrotulo->label("v13_certid");
$clrotulo->label("v15_observacao");
$sqlerro  =false;
$abil     =false;
$erro_msg = '';

if ( isset($processar) ) {

  $oCertidao = new Certidao($v13_certid);

  if ($oCertidao->isCobrancaExtrajudicial()) {

    echo "<script>alert(\"Cancelamento Inválido! Certidão está em Cobrança Extrajudicial.\");</script>";
    echo "<script>location.href='div4_cancelcda001.php'</script>";
    exit;
  }
}

if (isset($cancelar)&&$cancelar!=""){


  $sqlerro=false;
  $certidao=$v13_certid;
  $info=split('#',$chaves);
  $inclui=true;

  if ($numreg!=0&&$numreg==count($info)){
    $result_inicial=$clinicialcert->sql_record($clinicialcert->sql_query_file(null,$certidao));
    if ($clinicialcert->numrows>0){
      db_fieldsmemory($result_inicial,0);
      db_msgbox("Esta CDA possui inicial Nº $v51_inicial!!Operação cancelada!!");
      echo "<script>location.href='div4_cancelcda001.php'</script>";
      $inclui=false;
    }
  }

  if ($inclui==true){
    db_inicio_transacao();

    if (count($info)>0){

      $clacertid->v15_certid     = $certidao;
      $clacertid->v15_data       = date('Y-m-d',db_getsession("DB_datausu"));
      $clacertid->v15_hora       = db_hora();
      $clacertid->v15_usuario    = db_getsession("DB_id_usuario");
      $clacertid->v15_instit     = db_getsession('DB_instit');
      $clacertid->v15_observacao = $v15_observacao;
      if ($numreg!=0&&$numreg==count($info)){
        $parcial=0;
      }else{
        $parcial=1;
      }
      $clacertid->v15_parcial = "$parcial";
      $clacertid->incluir(null);
      $v15_codigo=$clacertid->v15_codigo;
      if ($clacertid->erro_status==0){

        $sqlerro=true;
        $erro_msg=$clacertid->erro_msg;
      }
    }
    if (isset($tipodiv)&&$tipodiv=="normal"){

      $sqlerro=false;
      for($w=0;$w<count($info);$w++){
        $dados=split('-',$info[$w]);
        $coddiv = $dados[0];
        $numpre = $dados[1];
        $numpar = $dados[2];
        $result_div=$clcertdiv->sql_record($clcertdiv->sql_query_deb($certidao,null,"distinct V01_numpre,V01_numpar,v01_coddiv","","v01_instit = ".db_getsession('DB_instit')." and v13_instit=".db_getsession('DB_instit')." and v14_certid=$certidao and v14_coddiv=$coddiv and v01_numpre=$numpre and v01_numpar=$numpar"));
        if ($clcertdiv->numrows>0){
          $numrows_div=$clcertdiv->numrows;
          for($i=0;$i<$numrows_div;$i++){

            db_fieldsmemory($result_div,$i);
            $result_forotip=$clarreforo->sql_record($clarreforo->sql_query_file(null,"distinct k00_numpre,k00_numpar,k00_tipo",null,"k00_certidao=$certidao  and k00_numpre=$v01_numpre and k00_numpar=$v01_numpar"));
            if ($clarreforo->numrows>0){
              db_fieldsmemory($result_forotip,0);
            } else {
              db_msgbox('Nao existem registros desta certidao na tabela arreforo! Contate suporte!');
              $sqlErro = true;
            }
            if ($sqlerro==false){
              $clarrecad->k00_tipo=$k00_tipo;
              $clarrecad->alterar_arrecad("k00_numpre=$k00_numpre and k00_numpar=$k00_numpar");
              if ($clarrecad->erro_status==0){

                $sqlerro=true;
                $erro_msg=$clarrecad->erro_msg;
                break;
              }
            }
            $clarreforo->excluir(null,"k00_certidao=$certidao and k00_numpre=$k00_numpre and k00_numpar=$k00_numpar");
            if ($clarreforo->erro_status==0){
              $sqlerro=true;
              $erro_msg=$clarreforo->erro_msg;
              break;
            }
            if ($sqlerro==false){
              $result_certdiv=$clcertdiv->sql_record($clcertdiv->sql_query_file($certidao,$v01_coddiv));
              for($z=0;$z<$clcertdiv->numrows;$z++){

                db_fieldsmemory($result_certdiv,$z);
                $clacertdiv->v14_certid=$v14_certid;
                $clacertdiv->v14_coddiv=$v14_coddiv;
                $clacertdiv->v14_vlrcor=$v14_vlrcor;
                $clacertdiv->v14_vlrhis=$v14_vlrhis;
                $clacertdiv->v14_vlrjur=$v14_vlrjur;
                $clacertdiv->v14_vlrmul=$v14_vlrmul;
                $clacertdiv->v14_codacertid=$v15_codigo;
                $clacertdiv->incluir($v14_certid,$v14_coddiv);
                if ($clacertdiv->erro_status==0){
                  $sqlerro=true;
                  $erro_msg=$clacertdiv->erro_msg;
                }
              }
            }

            /**
             * Exclui da listacda
             */
            if ( $sqlerro == false ) {

              $cllistacda->excluir(null, "v81_certid = $certidao");

              if ($cllistacda->erro_status == "0") {

                $sqlerro  = true;
                $erro_msg = $cllistacda->erro_msg;
              }
            }

            if ($sqlerro==false){

              $clcertdiv->v14_certid=$certidao;
              $clcertdiv->v14_coddiv=$v01_coddiv;
              $clcertdiv->excluir($certidao,$v01_coddiv);
              if ($clcertdiv->erro_status==0){
                $sqlerro=true;
                $erro_msg=$clcertdiv->erro_msg;
              }
            }
          }
        }

      }
      if ($numreg!=0&&$numreg==count($info)){
        if ($sqlerro==false){
          $clcertid->excluir($certidao);
          if ($clcertid->erro_status==0){

            $sqlerro=true;
            $erro_msg=$clcertid->erro_msg;
          }
        }
      }
    }elseif (isset($tipodiv)&&$tipodiv=="parcel"){

      $sqlerro=false;
      for($w=0;$w<count($info);$w++){
        $dados=split('-',$info[$w]);
        $parcel = $dados[0];
        $numpre = $dados[1];
        $result_ter=$clcertter->sql_record($clcertter->sql_query_deb($certidao,null,"distinct v07_numpre,v07_parcel","","v14_certid=$certidao and v14_parcel=$parcel and v07_numpre=$numpre"));
        if ($clcertter->numrows>0){
          $numrows_ter=$clcertter->numrows;
          for($i=0;$i<$numrows_ter;$i++){
            db_fieldsmemory($result_ter,$i);
            $result_forotip=$clarreforo->sql_record($clarreforo->sql_query_file(null,"distinct k00_numpre,k00_numpar,k00_tipo",null,"k00_certidao=$certidao  and k00_numpre=$v07_numpre "));
            if ($clarreforo->numrows>0){
              db_fieldsmemory($result_forotip,0);
            } else {

              $erro_msg = "cda $certidao inconsistente! ";
              $sqlerro = true;
              break;
            }
            if ($sqlerro==false){

              $clarrecad->k00_tipo=$k00_tipo;
              $clarrecad->alterar_arrecad("k00_numpre=$k00_numpre");
              if ($clarrecad->erro_status==0){

                $sqlerro=true;
                $erro_msg=$clarrecad->erro_msg;
                break;
              }
            }
            $clarreforo->excluir(null,"k00_certidao=$certidao and k00_numpre=$k00_numpre");
            if ($clarreforo->erro_status==0){

              $sqlerro=true;
              $erro_msg=$clarreforo->erro_msg;
              break;
            }

            if ($sqlerro==false){

              $result_certter=$clcertter->sql_record($clcertter->sql_query_file($certidao,$v07_parcel));
              for($z=0;$z<$clcertter->numrows;$z++){

                db_fieldsmemory($result_certter,$z);
                $clacertter->excluir($v14_certid,$v14_parcel);
                $clacertter->v14_certid     = $v14_certid;
                $clacertter->v14_parcel     = $v14_parcel;
                $clacertter->v14_vlrcor     = $v14_vlrcor;
                $clacertter->v14_vlrhis     = $v14_vlrhis;
                $clacertter->v14_vlrjur     = $v14_vlrjur;
                $clacertter->v14_vlrmul     = $v14_vlrmul;
                $clacertter->v14_codacertid = $v15_codigo;
                $clacertter->incluir($v14_certid,$v14_parcel);
                if ($clacertter->erro_status==0){

                  $sqlerro=true;
                  $erro_msg=$clacertter->erro_msg;
                }
              }
            }

            if ($sqlerro==false){

              $clcertter->v14_certid=$certidao;
              $clcertter->v14_parcel=$v07_parcel;
              $clcertter->excluir($certidao,$v07_parcel);
              if ($clcertter->erro_status==0){
                $sqlerro=true;
                $erro_msg=$clcertter->erro_msg;
              }
            }
          }
        }
      }
      if ($numreg!=0&&$numreg==count($info)){

        if ($sqlerro==false){
          $clcertid->excluir($certidao);
          if ($clcertid->erro_status==0){

            $sqlerro=true;
            $erro_msg=$clcertid->erro_msg;
          }
        }
      }
    }

    /*********************************** SE O NUMPRE ESTIVER NA INICIALNUMPRE DELETA *******************************************/
    for($w=0;$w<count($info);$w++){
      $dados=split('-',$info[$w]);
      $numpre = $dados[1];
      $result_ininumpre = $clinicialnumpre->sql_record($clinicialnumpre->sql_query_file(null,"*",null," v59_numpre = $numpre "));
      if ($clinicialnumpre->numrows > 0){

        $sqlerro = false;
        db_fieldsmemory($result_ininumpre,0);
        $clinicialnumpre->v59_inicial = $v59_inicial;
        $clinicialnumpre->v59_numpre  = $v59_numpre;
        $clinicialnumpre->excluir(null," v59_numpre = $v59_numpre ");
        if ($clinicialnumpre->erro_status == 0){

          $sqlerro  = true;
          $erro_msg = $clinicialnumpre->erro_msg;
          db_msgbox($erro_msg);
        }
      }

    }
    /***************************************************************************************************************************/
    db_fim_transacao($sqlerro);
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script>
function js_submit_form(){
js_gera_chaves();
if (js_retorna_chaves().trim() == '') {

  alert('Nenhum débito selecionado. Verificar');
  return false;

}
document.form1.cancelar.value='cancelar';
document.form1.submit();
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
	<form class="container" name="form1" method="post" action="">
			<?if (isset($processar)&&$processar!=""){?>

		<table class="form-container">
			<tr>
				<td colspan=2><?php
				$certidao = $v13_certid;

				//Valida a CDA.
				$sSqlInicialCert = "select v51_inicial,

				                           ( select parcel
				                               from termoini
				                                    inner join termo on v07_parcel = parcel
				                              where inicial = v59_inicial
				                                and v07_situacao = 1 ) as parcel,
				                           k00_numpre
				                      from inicialcert
				                           inner join inicialnumpre on v51_inicial = v59_inicial
				                           left join termoini       on inicial     = v51_inicial
				                           left join arrecad        on k00_numpre  = v59_numpre
				                     where v51_certidao = " . $certidao;

				$rsInicialCert = $clinicialcert->sql_record($sSqlInicialCert);
				if ($clinicialcert->numrows>0){

          $aDebitosInicialCert = db_utils::getCollectionByRecord( $rsInicialCert );

          foreach ($aDebitosInicialCert as $aDebito) {

            if ($aDebito->parcel != "" || $aDebito->k00_numpre == ""){

              db_msgbox('Esta CDA possui inicial Nº '.$aDebito->v51_inicial.' - Quitada ou Parcelada\nTermo:'.$aDebito->parcel.'\nOperação cancelada');
              db_redireciona("div4_cancelcda001.php");
              $inclui=false;
            }
          }
        }

				db_input("v13_certid",6,$Iv13_certid,true,"hidden",3);
				$result_tip=$clcertid->sql_record($clcertid->sql_query_tip($certidao,"distinct v14_coddiv as normal,v14_parcel as parcel",null,"v13_certid = $certidao and v13_instit = ".db_getsession('DB_instit') ));
				if ($clcertid->numrows>0){
				  db_fieldsmemory($result_tip,0);
				}else{
				  $sql=$clcertid->sql_query_tip($certidao,"distinct v14_coddiv as normal,v14_parcel as parcel",null,"v13_certid = $certidao and v13_instit = ".db_getsession('DB_instit'));
				  $cliframe_seleciona->chaves = "";
				  $cliframe_seleciona->campos  = "";
				}
				if (isset($normal)&&$normal!=""){
				  $case = "case
				  when arrematric.k00_numpre is not null then
				  'M - ' || arrematric.k00_matric
				  when arreinscr.k00_numpre is not null then
				  'I - ' || arreinscr.k00_inscr
				  end as v01_obs ";

				  $sql = $clcertdiv->sql_query_deb($certidao,null,"distinct v14_coddiv,v01_numpre,v01_numpar,v01_proced,v03_descr,v01_exerc, z01_nome, $case","v01_exerc","v13_certid = $certidao and divida.v01_instit = ".db_getsession('DB_instit')." and certid.v13_instit = ".db_getsession('DB_instit'));
				  $cliframe_seleciona->chaves = "v14_coddiv,v01_numpre,v01_numpar";
				  $cliframe_seleciona->campos  = "z01_nome,v14_coddiv,v01_numpre,v01_numpar,v01_proced,v03_descr,v01_exerc,v01_obs";
				  $tipodiv="normal";
				  db_input("tipodiv",6,'',true,"hidden",3);
				}else if (isset($parcel)&&$parcel!=""){

				  $sql=$clcertter->sql_query_deb($certidao,null,"distinct v14_parcel,v07_numpre,v07_dtlanc, z01_nome","v14_parcel","v13_certid = $certidao and certid.v13_instit = ".db_getsession('DB_instit'));
				  $cliframe_seleciona->chaves = "v14_parcel,v07_numpre";
				  $cliframe_seleciona->campos  = "z01_nome,v14_parcel,v07_numpre,v07_dtlanc";
				  $tipodiv="parcel";
				  db_input("tipodiv",6,'',true,"hidden",3);
				}else{

				  $sql=$clcertid->sql_query_tip("","*","","1=2");
				  $cliframe_seleciona->chaves = "";
				  $cliframe_seleciona->campos  = "";
				}
				$result_num=db_query($sql);
				$numreg=pg_numrows($result_num);
				db_input("numreg",6,'',true,"hidden",3);
				$cliframe_seleciona->legenda="Débitos a Cancelar";
				$cliframe_seleciona->alignlegenda="left";
				$cliframe_seleciona->sql=$sql;
				$cliframe_seleciona->iframe_height ="250";
				$cliframe_seleciona->iframe_width  ="750";
				$cliframe_seleciona->dbscript      = "onclick='parent.js_controlanumpre(this.value,this.name);'";
				$cliframe_seleciona->iframe_nome ="numpres";
				$cliframe_seleciona->iframe_seleciona(1);
				?>
				</td>
			</tr>
			<tr>
			  <td title="<?=$Tv15_observacao?>" colspan="2">
			    <fieldset class="separator">
			      <legend><strong>Observação</strong></legend>
    			    <?php
    			      db_textarea('v15_observacao', 10, 100, $Iv15_observacao, true, 'text', 1, '','','',500);
    			    ?>
			    </fieldset>
			  </td>
			</tr>
			</table>
				<?}else{?>
			<fieldset>
			  <legend>Pesquisa CDA</legend>
			<table class="form-container">
			<tr>
				<td nowrap title="<?=$Tv13_certid?>">
				    <?
				      db_ancora($Lv13_certid,"js_pesquisa_certid(true);",1);
				    ?>
				</td>
				<td nowrap>
				  <?
				    db_input("v13_certid",10,$Iv13_certid,true,"text",4,"onchange='js_pesquisa_certid(false);'");
				  ?>
				</td>
			</tr>
			</table>
		</fieldset>
			<?}
			if (isset($processar)&&$processar!=""){?>

  				  <input name="cancelar1" id="Cancelar1" type="button" value="Processar" onclick="js_submit_form();">
  				  <input name="voltar" id="voltar" type="button" value="Voltar" onclick="location.href='div4_cancelcda001.php'">
  				  <?
  				    db_input("cancelar",6,"",true,"hidden",3);
  				  ?>
			<?}else{?>
  				  <input name="processar" id="processar" type="submit" value="Processar" onclick="return js_testacampo();">
			<?}
			?>

		<?
		db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
		?>
		</form>

</body>
</html>
<script>

function js_controlanumpre(valor,nome){
iframe = numpres;
arr    = valor.split("_");
numpre = arr[1];
for(x = 0; x < iframe.document.form1.elements.length; x ++){
  obj       = iframe.document.form1.elements[x];
  arratu    = obj.value.split("_");
  numpreatu = arratu[1];
  if (numpre == numpreatu && iframe.document.form1.elements.length > 1 && obj.checked == false && nome != obj.name){
    obj.checked = true;
  }else if (numpre == numpreatu && iframe.document.form1.elements.length > 1 && obj.checked == true && nome != obj.name){
    obj.checked = false;
  }
}
}

function js_pesquisa_certid(mostra){
if (mostra==true){
  js_OpenJanelaIframe('top.corpo','db_iframe_certid','func_certid.php?funcao_js=parent.js_mostracertid1|v13_certid','Pesquisa',true);
}else{
  if (document.form1.v13_certid.value != ''){
    js_OpenJanelaIframe('top.corpo','db_iframe_certid','func_certid.php?pesquisa_chave='+document.form1.v13_certid.value+'&funcao_js=parent.js_mostracertid','Pesquisa',false);
  }else{
    document.form1.v13_certid.value = '';
  }
}
}
function js_mostracertid(chave,erro){
if (erro==true){
  document.form1.v13_certid.value = '';
  document.form1.v13_certid.focus();
}
}
function js_mostracertid1(chave1){
document.form1.v13_certid.value = chave1;
db_iframe_certid.hide();
}
function js_testacampo(func){
if (document.form1.v13_certid.value==""){
  alert('Informe o número da certidão!');
  document.form1.v13_certid.focus();
  return false;
}else{
  return true;
}

}
</script>
<?
if (isset($cancelar)&&$cancelar!=""){
if ($sqlerro==true){
  db_msgbox($erro_msg);
}else{
  db_msgbox('Cancelamento efetuado com Sucesso!');
  echo "<script>location.href='div4_cancelcda001.php';</script>";
}
}
?>
<script>

$("v13_certid").addClassName("field-size2");

</script>