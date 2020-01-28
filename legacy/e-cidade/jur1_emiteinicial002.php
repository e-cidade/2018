<?php
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
include(modification("libs/db_utils.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_iptubase_classe.php"));
include(modification("classes/db_cgm_classe.php"));
include(modification("classes/db_advog_classe.php"));
include(modification("classes/db_inicial_classe.php"));
include(modification("classes/db_inicialcert_classe.php"));
include(modification("classes/db_inicialmov_classe.php"));
include(modification("classes/db_inicialnumpre_classe.php"));
include(modification("classes/db_inicialnomes_classe.php"));
include(modification("classes/db_arrecad_classe.php"));

if(isset($_POST['alterar'])){
  $parametros = $_POST;
  unset($parametros['lista_veri_certid']);
  $lista_veri_certid = json_decode(str_replace('\\', '', $_POST['lista_veri_certid']));

  foreach ($lista_veri_certid as $chaveItem => $item) {

    $item = (array)$item;

    foreach ($item as $chave => $valor) {

      if(!empty($valor)) {
        $listaVeriCertid['certid']['certid'.$chaveItem] = $chave;
      }

      $listaVeriCertid['veri_certid']['veri_certid'.$chaveItem] = $chave;
    }
  }

  $parametros = array_merge($parametros, $listaVeriCertid['certid'], $listaVeriCertid['veri_certid']);
  db_postmemory($parametros);
  
} else {
  db_postmemory($_POST);
}

db_postmemory($_SERVER);
$botao    = 3;
$db_botao = 2;
$db_opcao = 3;
$verificachave = true;

$clinicial       = new cl_inicial;
$clinicialcert   = new cl_inicialcert;
$clinicialmov    = new cl_inicialmov;
$clinicialnomes  = new cl_inicialnomes;
$clinicialnumpre = new cl_inicialnumpre;

$cladvog   = new cl_advog;
$clarrecad = new cl_arrecad;

$cladvog->rotulo->label();
$clcgm = new cl_cgm;
$clcgm->rotulo->label("z01_numcgm");
$clcgm->rotulo->label("z01_nome");


$clrotulo = new rotulocampo;
$clrotulo->label("v50_inicial");
$clrotulo->label("v50_advog");
$clrotulo->label("v54_descr");
$clrotulo->label("v53_descr");
$clrotulo->label("v50_codlocal");
$clrotulo->label("v50_codvara");
$clrotulo->label("v51_certidao");

if(isset($alterar)){

  $erromsg = "";
  $sqlerro = false;

  db_inicio_transacao();

  $clinicial->alterar($v50_inicial);
  if ($clinicial->erro_status == 0) {

    $erromsg = $clinicial->erro_msg;
    $sqlerro = true;
    db_msgbox($erromsg);
    db_redireciona("jur1_emiteinicial002.php?chavepesquisa=$v50_inicial");
    break;
  }

  $sqlPardiv = "select v04_tipoinicial,v04_tipocertidao as tipocertidao from pardiv where v04_instit  = ".db_getsession('DB_instit') ;
  $rsPardiv  = db_query($sqlPardiv);
  $linhasPardiv = pg_num_rows($rsPardiv);
  if ($linhasPardiv > 0 ) {
    db_fieldsmemory($rsPardiv,0);
  }else{
    db_msgbox(_M('tributario.juridico.db_frmemiteinicial.configure_parametro'));
    $sqlerro = true;
  }


  for($i=0;$i<$numcheck;$i++){

    $w = "veri_certid".$i;
    $wx = "certid".$i;

    $buscaInicialCert = $clinicialcert->sql_query_file($v50_inicial,$$w);
    $rsVerificaCert = $clinicialcert->sql_record($buscaInicialCert);

    if ( $clinicialcert->numrows > 0 ) {

      if(empty($$wx)){

        // excluir inicial numpre pelos numpres das certidoes e dar update no arrecad k00_tipo = 19

        $sql  = " select distinct v01_numpre ";
        $sql .= " from ( select certid.v13_certid, ";
        $sql .= "               divida.v01_numpre ";
        $sql .= "           from certid ";
        $sql .= "                inner join inicialcert on inicialcert.v51_certidao = certid.v13_certid ";
        $sql .= "                inner join certdiv on certdiv.v14_certid = certid.v13_certid ";
        $sql .= "                inner join divida  on divida.v01_coddiv  = certdiv.v14_coddiv ";
        $sql .= "         where v51_inicial  = $v50_inicial ";
        $sql .= "           and v51_certidao = ".$$w;
        $sql .= "     union ";
        $sql .= "         select certid.v13_certid, ";
        $sql .= "               termo.v07_numpre ";
        $sql .= "           from certid ";
        $sql .= "                inner join inicialcert on inicialcert.v51_certidao = certid.v13_certid ";
        $sql .= "                inner join certter  on certter.v14_certid = certid.v13_certid ";
        $sql .= "                inner join termo    on termo.v07_parcel   = certter.v14_parcel ";
//        $sql .= "                inner join termodiv on termodiv.parcel    = termo.v07_parcel ";
//        $sql .= "                inner join divida   on divida.v01_coddiv  = termodiv.coddiv ";
        $sql .= "         where v51_inicial  = $v50_inicial ";
        $sql .= "           and v51_certidao = ".$$w." ) as x ";
        $rsExcArrecad = db_query($sql);
        $intNumrows   = pg_numrows($rsExcArrecad);

        // for excluindo da inicial numpre as certidoes desmarcadas e dando update no arrecad
        for($ii = 0;$ii < $intNumrows; $ii++){

          db_fieldsmemory($rsExcArrecad,$ii);

          $clinicialnumpre->excluir(null," v59_inicial = $v50_inicial and v59_numpre = $v01_numpre ");

          if($clinicialnumpre->erro_status == 0){
            $erromsg = $clinicialnumpre->erro_msg;
            $sqlerro = true;
            break;
          }

          $clarrecad->k00_tipo = $tipocertidao;
          $clarrecad->alterar_arrecad(" k00_numpre = $v01_numpre ");

          if($clarrecad->erro_status == 0){
            $erromsg = $clarrecad->erro_msg;
            $sqlerro = true;
            break;
          }
        }

        $clinicialcert->v51_inicial=$v50_inicial;
        $clinicialcert->v51_certidao=$$w;
        $clinicialcert->excluir($v50_inicial,$$w);

        if($clinicialcert->erro_status == 0){
          $erromsg = $clinicialcert->erro_msg;
          $sqlerro = true;
          break;
        }

      }
    } else {

      if(!empty($$wx)){


        if ($sqlerro==false){

          $clinicialcert->v51_certidao = $$wx;
          $clinicialcert->v51_inicial  = $v50_inicial;
          $clinicialcert->incluir($v50_inicial,$$wx);

          if ($clinicialcert->erro_status==0){
            $sqlerro = true;
            $erro    = $clinicialcert->erro_msg;
          }
        }

        if ($sqlerro==false){
          $sql = " select distinct 
        			 	k00_numcgm,
        			    k00_numpre 
        		  from (
        				  select distinct 
        				  		 k00_numcgm, 
        				  		 k00_numpre 
        					from inicial
						         inner join inicialcert     on v50_inicial 			 = v51_inicial 
						         inner join certid 	        on v13_certid		     = v51_certidao
						         						   and v13_instit 			 = ".db_getsession('DB_instit')."
						         left outer join certdiv    on v14_certid  			 = v13_certid
						         left outer join divida     on v14_coddiv 			 = v01_coddiv
						         						   and v01_instit			 = ".db_getsession('DB_instit')."
						         left outer join arrenumcgm on arrenumcgm.k00_numpre = v01_numpre
        				   where v50_inicial = {$v50_inicial} 
        				     and v50_instit  = ".db_getsession('DB_instit')."
        		union 
        		  select distinct 
        		  		 k00_numcgm, 
        		  		 k00_numpre 
        			from inicial
				         inner join inicialcert 	     on v50_inicial  = v51_inicial 
				         inner join certid 	             on v13_certid   = v51_certidao
				        							    and v13_instit   = ".db_getsession('DB_instit')."
				         left  join certter	       	     on v14_certid   = v51_certidao
				         left  join termo 	    	     on v07_parcel   = v14_parcel
				       								    and v07_instit   = ".db_getsession('DB_instit')."
				         left outer join arrenumcgm as x on x.k00_numpre = v07_numpre
        		   where v50_inicial = {$v50_inicial} 
        		   	 and v50_instit  = ".db_getsession('DB_instit')."  ) as x";

          $result = db_query($sql);
          $numso  = pg_num_rows($result);

          for($xr=0;$xr<$numso;$xr++){
            db_fieldsmemory($result,$xr);
            if ($k00_numcgm == 0 or $k00_numpre == 0){
              continue;
            }
            $result_nomes=$clinicialnomes->sql_record($clinicialnomes->sql_query_file($v50_inicial,$k00_numcgm));
            if($clinicialnomes->numrows==0){
              if ($sqlerro==false){
                $clinicialnomes->v58_inicial = $v50_inicial;
                $clinicialnomes->v58_numcgm  = $k00_numcgm;
                $clinicialnomes->incluir($v50_inicial,$k00_numcgm);
                if ($clinicialnomes->erro_status==0){
                  $sqlerro=true;
                  $erro=$clinicialnomes->erro_msg;
                  break;
                }
              }
            }
            if ($sqlerro==false){
              $result_existeininum=$clinicialnumpre->sql_record($clinicialnumpre->sql_query_file(null,"*",null,"v59_inicial={$v50_inicial} and v59_numpre=$k00_numpre"));
              if ($clinicialnumpre->numrows == 0){
                $clinicialnumpre->v59_inicial = $v50_inicial;
                $clinicialnumpre->v59_numpre  = $k00_numpre;
                $clinicialnumpre->incluir($v50_inicial,$k00_numpre);
                if ($clinicialnumpre->erro_status==0){
                  $sqlerro=true;
                  $erro=$clinicialnumpre->erro_msg;
                  break;
                }
              }
            }

            if ($sqlerro==false){

              $clarrecad->k00_tipo = $v04_tipoinicial;
              $clarrecad->alterar_arrecad("k00_numpre={$k00_numpre}");
              if ($clarrecad->erro_status==0){
                $sqlerro=true;
                $erro=$clarrecad->erro_msg;
                break;
              }
            }
          }

        }

      }
    }
  }
  if($clinicial->erro_status=="0"){
    $db_opcao = 2;
    $botao    = 1;
  }


//  $sqlerro = true;
  db_fim_transacao($sqlerro);

}else if(isset($chavepesquisa)){
  $v50_inicial=$chavepesquisa;
  $sql_ini = "select distinct 
                     localiza.v54_descr,
                     cgm.z01_nome as z01_nomeadvog,
					           inicial.v50_advog,
					           inicial.v50_codlocal,
					           termoini.parcel,
					           arrecad.k00_numpre
				        from inicial
               inner join db_config     on  db_config.codigo             = inicial.v50_instit
               inner join advog         on  advog.v57_numcgm             = inicial.v50_advog
               inner join cgm           on  cgm.z01_numcgm               = advog.v57_numcgm
               inner join localiza      on  localiza.v54_codlocal        = inicial.v50_codlocal
			         inner join inicialnumpre on  inicialnumpre.v59_inicial    = inicial.v50_inicial
			          left join termoini      on  termoini.inicial             = inicial.v50_inicial
			          left join termoanu      on  termoanu.v09_parcel          = termoini.parcel 
				        left join arrecad       on  arrecad.k00_numpre           = inicialnumpre.v59_numpre  
			         where v50_inicial = $v50_inicial
			           and termoanu.v09_parcel is null";
  $result = $clinicial->sql_record($sql_ini);
  if ($clinicial->numrows!=0){
    db_fieldsmemory($result,0);
    /*
   * Verifica se a Inicial pode ser alterada
   * Só podera ser alterada se não estiver parcelada, cancelada ou paga
   */
    if($k00_numpre == "" || $parcel != ""){
      db_msgbox(_M('tributario.juridico.db_frmemiteinicial.alteracao_nao_permitida'));
      db_redireciona(basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]));
    }

  }
  $db_opcao=2;
  $botao=1;
}

?>
    <html>
    <head>
        <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <meta http-equiv="Expires" CONTENT="0">
        <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
        <link href="estilos.css" rel="stylesheet" type="text/css">
        <style type="text/css">
            td {
                font-family: Arial, Helvetica, sans-serif;
                font-size: 12px;
            }
            input {
                font-family: Arial, Helvetica, sans-serif;
                font-size: 12px;
                height: 17px;
                border: 1px solid #999999;
            }
        </style>
    </head>
    <body bgcolor=#CCCCCC>
    <?
    include(modification("forms/db_frmemiteinicial.php"));
    ?>
    <?
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
    </body>
    </html>
<?
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();

if(isset($alterar)){
  if(!$sqlerro){
    db_msgbox(_M('tributario.juridico.db_frmemiteinicial.processamento_concluido'));
    if($clinicial->erro_campo!=""){
      echo "<script> document.form1.".$clinicial->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clinicial->erro_campo.".focus();</script>";
    }
  }else{

    $oParms        = new stdClass();
    $oParms->sErro = $erromsg;
    db_msgbox(_M('tributario.juridico.db_frmemiteinicial.processamento_concluido', $oParms));
    ?>
      <script>
        function js_AbreJanelaRelatorio() {
          //  jan = window.open('div2_inicial_002.php?inicial=<?=$v50_inicial?>','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
          jan.moveTo(0,0);
        }
        js_AbreJanelaRelatorio();
      </script>
    <?
//     db_redireciona("jur1_emiteinicial002.php");
  }
}
?>