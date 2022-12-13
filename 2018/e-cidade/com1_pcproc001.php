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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_sql.php"));

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);

$clsolicita        = new cl_solicita;
$clsolicitem       = new cl_solicitem;
$clpcprocitem      = new cl_pcprocitem;
$clpcproc          = new cl_pcproc;
$clpcparam         = new cl_pcparam;
$clpcorcam         = new cl_pcorcam;
$clpcorcamitem     = new cl_pcorcamitem;
$clpcorcamitemproc = new cl_pcorcamitemproc;
$clpcorcamforne    = new cl_pcorcamforne;
$clpcorcamforne2   = new cl_pcorcamforne;
$clpcorcamval      = new cl_pcorcamval;
$clpcorcamjulg     = new cl_pcorcamjulg;
$clpcorcamtroca    = new cl_pcorcamtroca;
$db_botao          = true;
$db_opcao          = 1;
$result_pcparam = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit"),"pc30_horas,pc30_dias,pc30_contrandsol"));
db_fieldsmemory($result_pcparam,0);
if(isset($incluir) || isset($juntar)){
  $gerouorc = false;
  $sqlerro=false;
  if(isset($valores) && $valores!=""){
    db_inicio_transacao();
    if(isset($incluir)){

      $clpcproc->pc80_data          = date("Y-m-d",db_getsession("DB_datausu"));
      $clpcproc->pc80_usuario       = db_getsession("DB_id_usuario");
      $clpcproc->pc80_depto         = db_getsession("DB_coddepto");
      $clpcproc->pc80_resumo        = $pc10_resumo;
      $clpcproc->pc80_tipoprocesso  = $pc80_tipoprocesso;
      $clpcproc->incluir(null);
      $erro_msg   = $clpcproc->erro_msg;
      $pc80_codproc= $clpcproc->pc80_codproc;
      if($clpcproc->erro_status==0){
	      $sqlerro=true;
     	}
    }
    $arr_valores = split(",",$valores);
    if(isset($juntar)){
      $pc80_codproc = $juntar;
    }
    $arr_numero = Array();
    $arr_solici = Array();
    for($i=0;$i<sizeof($arr_valores);$i++){
      $arr_item  = split("_",$arr_valores[$i]);
      if(in_array($arr_item[1],$arr_numero)==false){
	   array_push($arr_numero,$arr_item[1]);
      }
      $pc11_codigo = $arr_item[2];
if ($pc30_contrandsol=='t'){
	  	 $sqltran = "select distinct x.p62_codtran

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
			if(pg_numrows($result_tran)!=0){
				for($w=0;$w<pg_numrows($result_tran);$w++){
					db_fieldsmemory($result_tran,$w);
					$recebetransf=recprocandsol($p62_codtran);
					if ($recebetransf==true){
						$sqlerro=true;
						break;
					}
				}

			}
	 }


      $clpcprocitem->pc81_codproc   = $pc80_codproc;
      $clpcprocitem->pc81_solicitem = $pc11_codigo;
      $clpcprocitem->incluir(@$pc81_codprocitem);
      if(!isset($arr_solici[$pc11_codigo])){
	$arr_solici[$pc11_codigo] = $clpcprocitem->pc81_codprocitem;
      }
      if($clpcprocitem->erro_status==0){
        $erro_msg   = $clpcprocitem->erro_msg;
	$sqlerro=true;
	break;
      }
    }
    $arr_importar = split(",",$importa);
    $arr_orcam = Array();
    $rowssizeof = sizeof($arr_importar);
    $arr_orcamfornexist  = Array();


    for($i=0;$i<$rowssizeof;$i++){
      if(trim($arr_importar[$i])!=""){
	$arr_importaritem = split("_",$arr_importar[$i]);
	$orcamento = $arr_importaritem[1];
	$item      = $arr_importaritem[2];
	$orcamitem = $arr_importaritem[3];
	if(isset($arr_solici[$item]) && $sqlerro==false){
	  if($gerouorc == false){
	    $clpcorcam->pc20_dtate = date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$pc30_dias,date("Y")));
	    $clpcorcam->pc20_hrate = $pc30_horas;
	    if(isset($juntar)){

	      $result_pc22_codorc = $clpcorcamitemproc->sql_record($clpcorcamitemproc->sql_query(null,null,"max(pc20_codorc) as pc20_codorc",""," pc80_codproc=$pc80_codproc"));
	      if($clpcorcamitemproc->numrows>0){
      		db_fieldsmemory($result_pc22_codorc,0);
	      }else{
          $clpcorcam->incluir(null);
          $pc20_codorc = $clpcorcam->pc20_codorc;
	      }
	    }else{
	      $clpcorcam->incluir(null);
	      $pc20_codorc = $clpcorcam->pc20_codorc;
	    }

	    if($clpcorcam->erro_status=="0"){

	      $erro_msg   = $clpcorcam->erro_msg;
	      $sqlerro=true;
	      break;

	    }else{
	      $erro_msg .= "\\n\\nOBS.: Foi gerado o orçamento número $pc20_codorc para este processo de compras.";
	    }
	    $gerouorc = true;
	  }
	  if($sqlerro==false && isset($pc20_codorc)){
	    $clpcorcamitem->pc22_codorc = $pc20_codorc;
	    $clpcorcamitem->incluir(null);
	    $pc22_orcamitem = $clpcorcamitem->pc22_orcamitem;
	    if($clpcorcamitem->erro_status==0){
	      $erro_msg   = $clpcorcamitem->erro_msg;
	      $sqlerro=true;
	      break;
	    }
	  }
	  if($sqlerro==false && isset($pc22_orcamitem)){
	    $clpcorcamitemproc->incluir($pc22_orcamitem,$arr_solici[$item]);
	    if($clpcorcamitemproc->erro_status==0){
	      $erro_msg   = $clpcorcamitemproc->erro_msg;
	      $sqlerro=true;
	      break;
	    }
	  }
	  if($sqlerro==false && isset($pc20_codorc)){
	    $result_fornecedores = $clpcorcamforne->sql_record($clpcorcamforne->sql_query_fornec(null,"pc21_numcgm,pc21_orcamforne as selforne",""," pc22_orcamitem=$orcamitem "));
	    $numrows_contafornec = $clpcorcamforne->numrows;
	    for($contaforne=0;$contaforne<$numrows_contafornec;$contaforne++){
	      db_fieldsmemory($result_fornecedores,$contaforne);
              if(!isset($arr_orcamfornexist[$pc21_numcgm."_".$pc20_codorc])){
		$clpcorcamforne->pc21_numcgm    = $pc21_numcgm;
		$clpcorcamforne->pc21_codorc    = $pc20_codorc;
		$clpcorcamforne->pc21_importado = 'true';
		$clpcorcamforne->incluir(null);
		$pc21_orcamforne = $clpcorcamforne->pc21_orcamforne;
                $arr_orcamfornexist[$pc21_numcgm."_".$pc20_codorc] = $pc21_orcamforne;
//	        db_msgbox($arr_orcamfornexist[$pc21_numcgm."_".$pc20_codorc]);
		if($clpcorcamforne->erro_status==0){
		  $erro_msg   = $clpcorcamforne->erro_msg;
		  $sqlerro=true;
		  break;
		}
              }
              $pc21_orcamforne = $arr_orcamfornexist[$pc21_numcgm."_".$pc20_codorc];
//	      db_msgbox($pc21_orcamforne);
	      $result_pcorcamval = $clpcorcamval->sql_record($clpcorcamval->sql_query_file($selforne,$orcamitem,"pc23_valor,pc23_quant,pc23_vlrun,pc23_obs"));
	      $numrows_pcorcamval = $clpcorcamval->numrows;
	      if($numrows_pcorcamval>0){
		db_fieldsmemory($result_pcorcamval,0);
		$clpcorcamval->pc23_valor = $pc23_valor;
		$clpcorcamval->pc23_quant = $pc23_quant;
		$clpcorcamval->pc23_vlrun = $pc23_vlrun;
		$clpcorcamval->pc23_obs   = addslashes(stripslashes(chop($pc23_obs)));
		$clpcorcamval->incluir($pc21_orcamforne,$pc22_orcamitem);
		if($clpcorcamval->erro_status==0){
		  $erro_msg   = $clpcorcamval->erro_msg;
		  $sqlerro=true;
		  break;
		}
		if($sqlerro==false && isset($pc21_orcamforne) && isset($pc22_orcamitem)){
		  $result_itemjulg = $clpcorcamjulg->sql_record($clpcorcamjulg->sql_query_file($orcamitem,$selforne,"pc24_pontuacao as pontuacao"));
		  $numrows_itemjulg= $clpcorcamjulg->numrows;
		  for($ii=0;$ii<$numrows_itemjulg;$ii++){
		    db_fieldsmemory($result_itemjulg,$ii);
		    $clpcorcamjulg->pc24_pontuacao = $pontuacao;
		    $clpcorcamjulg->incluir($pc22_orcamitem,$pc21_orcamforne);
		    if($clpcorcamjulg->erro_status==0){
		      $erro_msg = $clpcorcamjulg->erro_msg;
		      $sqlerro=true;
		      break;
		    }
		  }
		  if($sqlerro==true){
		    break;
		  }
		}
		if($sqlerro==false && isset($pc22_orcamitem)){
		  $result_itemtroca = $clpcorcamtroca->sql_record($clpcorcamtroca->sql_query_file(null,"pc25_motivo,pc25_forneant,pc25_forneatu","","pc25_orcamitem=$orcamitem"));
		  $numrows_itemtroca= $clpcorcamtroca->numrows;
		  for($ii=0;$ii<$numrows_itemtroca;$ii++){
		    db_fieldsmemory($result_itemtroca,$ii);
		    $clpcorcamtroca->pc25_orcamitem = $pc22_orcamitem;
		    $clpcorcamtroca->pc25_motivo    = addslashes(stripslashes(chop($pc25_motivo)));

        if (trim(@$pc25_forneant)==""){
             $clpcorcamtroca->pc25_forneant = $clpcorcamforne->pc21_orcamforne;
        } else {
             $clpcorcamtroca->pc25_forneant = $pc25_forneant;
        }

        if (trim(@$pc25_forneatu)==""){
             $clpcorcamtroca->pc25_forneatu = $clpcorcamforne->pc21_orcamforne;
        } else {
             $clpcorcamtroca->pc25_forneatu = $pc25_forneatu;
        }

		    $clpcorcamtroca->incluir(null);
		    if($clpcorcamtroca->erro_status==0){
		      $erro_msg = $clpcorcamtroca->erro_msg;
		      $sqlerro=true;
		      break;
		    }
		  }
		}
	      }
	    }
	    if($sqlerro==true){
	      break;
	    }
	  }
	}
      }
    }

    db_fim_transacao($sqlerro);
    if($sqlerro==false){
      unset($valores,$importa);
    }
  }else{
    $sqlerro=true;
    $erro_msg = "Não é possível incluir Processo de Compras sem informar Item(ns).";
  }
}

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/arrays.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body class="body-default" >
    <div class="container">
      <?php
        include(modification("forms/db_frmpcproc.php"));
      ?>
    </div>
    <?php
      db_menu( db_getsession("DB_id_usuario"),
               db_getsession("DB_modulo"),
               db_getsession("DB_anousu"),
               db_getsession("DB_instit") );
    ?>
  </body>
  <script>
    arr_dados = new Array();
    arr_impor = new Array();
  </script>
</html>
<?php
  if (isset($incluir)) {

    if (!$sqlerro) {

     echo "<script>
      if (confirm('Processo de Compras {$pc80_codproc} incluído com sucesso. Deseja emitir o documento?')){
        jan = window.open('com2_emiteprocessocompra002.php?pc80_codproc_inicial={$pc80_codproc}&pc80_codproc_final={$pc80_codproc}',
                           '',
                           'width='+(screen.availWidth - 5),
                           'height='+(screen.availHeight-40)+',scrollbars=1, location=0'
                          );
        jan.moveTo(0, 0);
      }";

      if ($pc80_tipoprocesso == 2) {
        echo "window.location = \"com4_processocompra001.php?acao=2&iCodigo={$pc80_codproc}\";";
      }

      echo "</script>";
    } else {
      db_msgbox($erro_msg);
    }
    if($sqlerro==true){
      if($clpcproc->erro_campo!=""){
        echo "<script> document.form1.".$clpcproc->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clpcproc->erro_campo.".focus();</script>";
      };
    }
  }
?>