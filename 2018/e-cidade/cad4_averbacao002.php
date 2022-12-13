<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBseller Servicos de Informatica
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
require_once("dbforms/db_funcoes.php");

$claverbaescritura       = new cl_averbaescritura;
$claverbaregimovel       = new cl_averbaregimovel;
$claverbaprocesso        = new cl_averbaprocesso;
$claverbacao             = new cl_averbacao;
$claverbacgm             = new cl_averbacgm;
$claverbacgmold          = new cl_averbacgmold;
$cliptubase              = new cl_iptubase;
$clpropri                = new cl_propri;
$clpromitente            = new cl_promitente;
$clarrematric            = new cl_arrematric;
$cldivida                = new cl_divida;
$claverbadecisaojudicial = new cl_averbadecisaojudicial;
$claverbaformalpartilha  = new cl_averbaformalpartilha;
$claverbaguia            = new cl_averbaguia;
$clcgm                   = new cl_cgm;

$claverbaescritura->rotulo->label();
$claverbaformalpartilha->rotulo->label();
$claverbaescritura->rotulo->label();
$claverbadecisaojudicial->rotulo->label();
$clcgm->rotulo->label();
$claverbaguia->rotulo->label();

db_postmemory($HTTP_POST_VARS);
$db_opcao = 22;
$db_botao = false;

if(isset($processar)){
  $sqlerro=false;
  db_inicio_transacao();
  $result_tipo = $claverbacao->sql_record($claverbacao->sql_query($j75_codigo,"j75_regra,j75_matric"));
  db_fieldsmemory($result_tipo,0);
  if ($j75_regra==1){
    $result_princ=$cliptubase->sql_record($cliptubase->sql_query_file($j75_matric,"j01_numcgm"));
    db_fieldsmemory($result_princ,0);
    $claverbacgmold->j79_averbacao = $j75_codigo;
    $claverbacgmold->j79_numcgm = $j01_numcgm;
    $claverbacgmold->j79_tipo = 1;
    $claverbacgmold->j79_principal = "1";
    $claverbacgmold->incluir(null);
    if($claverbacgmold->erro_status==0){
      $sqlerro=true;
      $erro_msg = $claverbacgmold->erro_msg;
      //db_msgbox("Incluso Averbacgmold 1 !");
    }else{
      //db_msgbox("Incluso averbacgmold!!");
    }
    if ($sqlerro==false){
      $result_promitente = $clpromitente->sql_record($clpromitente->sql_query_file($j75_matric));
      for($w=0;$w<$clpromitente->numrows;$w++){
        db_fieldsmemory($result_promitente,$w);
        if ($sqlerro==false){
          $claverbacgmold->j79_averbacao = $j75_codigo;
          $claverbacgmold->j79_numcgm = $j41_numcgm;
          $claverbacgmold->j79_tipo = 2;
          if ($j41_tipopro=='t'){
            $claverbacgmold->j79_principal = '1';
          }else if ($j41_tipopro=='f'){
            $claverbacgmold->j79_principal = '0';
          }
          $claverbacgmold->incluir(null);
          if($claverbacgmold->erro_status==0){
            $sqlerro=true;
            $erro_msg = $claverbacgmold->erro_msg;
            //   db_msgbox("Incluso Averbacgmold!");
          }else{
            //db_msgbox("Incluso Averbacgmold!");
          }
        }
      }
    }
    if ($sqlerro==false){
      $clpromitente->j41_matric = $j75_matric;
      $clpromitente->excluir($j75_matric);
      if ($clpromitente->erro_status==0){
        $sqlerro=true;
        $erro_msg = $clpromitente->erro_msg;
      }else{
        //db_msgbox("Excluso Promitente!");
      }
    }
    if ($sqlerro==false){
      $result_propri = $clpropri->sql_record($clpropri->sql_query_file($j75_matric));
      for($w=0;$w<$clpropri->numrows;$w++){
        db_fieldsmemory($result_propri,$w);
        if ($sqlerro==false){
          $claverbacgmold->j79_averbacao = $j75_codigo;
          $claverbacgmold->j79_numcgm = $j42_numcgm;
          $claverbacgmold->j79_tipo = 1;
          $claverbacgmold->j79_principal = '0';
          $claverbacgmold->incluir(null);
          if($claverbacgmold->erro_status==0){
            $sqlerro=true;
            $erro_msg = $claverbacgmold->erro_msg;
            // db_msgbox("Incluso averbacgmold FOR!!");
            break;
          }else{
            //db_msgbox("Incluso averbacgmold FOR!!");
          }
        }
      }
    }
    if ($sqlerro==false){
      $clpropri->j42_matric = $j75_matric;
      $clpropri->excluir($j75_matric);
      if ($clpropri->erro_status==0){
        $sqlerro=true;
        $erro_msg = $clpropri->erro_msg;
        //db_msgbox("Erro Excluso propri!!");
      }else{
        //db_msgbox("Excluso propri!!");
      }
    }
    if ($sqlerro==false){
      $result_new_propri = $claverbacgm->sql_record($claverbacgm->sql_query_file(null,"*",null,"j76_averbacao=$j75_codigo"));
      for($w=0;$w<$claverbacgm->numrows;$w++){
        db_fieldsmemory($result_new_propri,$w);
        if ($sqlerro==false){
          if ($j76_principal=="f"){
            $clpropri->incluir($j75_matric,$j76_numcgm);
            if ($clpropri->erro_status==0){
              $sqlerro=true;
              $erro_msg = $clpropri->erro_msg;
            }else{
              //db_msgbox("Incluso propri!!");
            }
          }else if ($j76_principal=="t"){
            $cliptubase->j01_numcgm=$j76_numcgm;
            $cliptubase->j01_matric=$j75_matric;
            $cliptubase->alterar($j75_matric);
            if ($cliptubase->erro_status==0){
              $sqlerro=true;
              $erro_msg=$cliptubase->erro_msg;
            }else{
              //db_msgbox("Alterar iptubase!!");
            }
            if ($sqlerro==false){
              $result_div = $clarrematric->sql_record($clarrematric->sql_query_div(null,null,"*",null,"arrematric.k00_matric=$j75_matric"));
              $numrows_div = $clarrematric->numrows;
              for($x=0;$x<$numrows_div;$x++){
                db_fieldsmemory($result_div,$x);
                if ($sqlerro==false){
                  $cldivida->v01_numcgm = $j76_numcgm;
                  $cldivida->v01_coddiv = $v01_coddiv;
                  $cldivida->alterar($v01_coddiv);
                  if ($cldivida->erro_status==0){
                    $sqlerro=true;
                    $erro_msg=$cldivida->erro_msg;
                  }
                }
              }
            }
          }
        }
      }
    }
  }else if ($j75_regra==2){
    $result_promitente = $clpromitente->sql_record($clpromitente->sql_query_file($j75_matric));
    if ($clpromitente->numrows>0){
      if ($sqlerro==false){
        for($w=0;$w<$clpromitente->numrows;$w++){
          db_fieldsmemory($result_promitente,$w);
          if ($sqlerro==false){
            $claverbacgmold->j79_averbacao = $j75_codigo;
            $claverbacgmold->j79_numcgm = $j41_numcgm;
            $claverbacgmold->j79_tipo = 2;
            if ($j41_tipopro=='t'){
              $claverbacgmold->j79_principal = 1;
            }else if ($j41_tipopro=='f'){
              $claverbacgmold->j79_principal = "0";
            }
            $claverbacgmold->incluir(null);
            if($claverbacgmold->erro_status==0){
              $sqlerro=true;
              $erro_msg = $claverbacgmold->erro_msg;
            }else{
              //db_msgbox("Incluso Averbacgmold!");
            }
          }
        }
      }
      if ($sqlerro==false){
        $clpromitente->j41_matric = $j75_matric;
        $clpromitente->excluir($j75_matric);
        if ($clpromitente->erro_status==0){
          $sqlerro=true;
          $erro_msg = $clpromitente->erro_msg;
        }else{
          //db_msgbox("Excluso Promitente!");
        }
      }
    }else{
      $result_princ=$cliptubase->sql_record($cliptubase->sql_query_file($j75_matric,"j01_numcgm"));
      db_fieldsmemory($result_princ,0);
      $claverbacgmold->j79_averbacao = $j75_codigo;
      $claverbacgmold->j79_numcgm = $j01_numcgm;
      $claverbacgmold->j79_tipo = 1;
      $claverbacgmold->j79_principal = "1";
      $claverbacgmold->incluir(null);
      if($claverbacgmold->erro_status==0){
        $sqlerro=true;
        $erro_msg = $claverbacgmold->erro_msg;
        //db_msgbox("Incluso Averbacgmold 1 !");
      }else{
        //db_msgbox("Incluso averbacgmold!!");
      }
    }
    if ($sqlerro==false){
      $result_new_promitente = $claverbacgm->sql_record($claverbacgm->sql_query_file(null,"*",null,"j76_averbacao=$j75_codigo"));
      for($w=0;$w<$claverbacgm->numrows;$w++){
        db_fieldsmemory($result_new_promitente,$w);
        if ($sqlerro==false){

          $clpromitente->j41_promitipo = 'C';
          if ($j76_principal=='t'){
            $clpromitente->j41_tipopro  = 1;
          }else if ($j76_principal=='f'){
            $clpromitente->j41_tipopro  ="0";
          }


          $clpromitente->incluir($j75_matric,$j76_numcgm);
          if ($clpromitente->erro_status==0){
            $sqlerro=true;
            $erro_msg = $clpromitente->erro_msg;
            //db_msgbox("Erro $j76_principal ");
          }else{
            //db_msgbox("Incluso Promitente!");
          }
          $result_ativa_trigger = $cliptubase->sql_record($cliptubase->sql_query_file($j75_matric));
          db_fieldsmemory($result_ativa_trigger,0);
          $cliptubase->j01_numcgm = $j01_numcgm;
          $cliptubase->j01_matric = $j01_matric;
          $cliptubase->alterar($j75_matric);
          if ($cliptubase->erro_status==0){
            $sqlerro=true;
            $erro_msg=$cliptubase->erro_msg;
          }else{
            //db_msgbox("Alterar iptubase!!");
          }
        }
      }
    }
  }
  if ($sqlerro==false){
    $claverbacao->j75_situacao = 2;
    $claverbacao->j75_codigo = $j75_codigo;
    $claverbacao->alterar($j75_codigo);
    if($claverbacao->erro_status==0){
      $sqlerro=true;
      $erro_msg = $claverbacao->erro_msg;
    }else{
      //db_msgbox("Alterao averbacao!!");
    }

  }
  db_fim_transacao($sqlerro);
  $db_opcao = 3;
  $db_botao = true;
}else if(isset($chavepesquisa)){

  $db_opcao = 3;
  $db_botao = true;

  $result = $claverbacao->sql_record($claverbacao->sql_query($chavepesquisa));
  db_fieldsmemory($result,0);

  $result_proc = $claverbaprocesso->sql_record($claverbaprocesso->sql_query($j75_codigo,"j77_codproc,p58_numero, p58_ano, p58_requer"));

  if ($claverbaprocesso->numrows > 0) {

    db_fieldsmemory($result_proc,0);
    $p58_numero = $p58_numero . '/' . $p58_ano;
  }
  $result_reg = $claverbaregimovel->sql_record($claverbaregimovel->sql_query($j75_codigo));
  if ($claverbaregimovel->numrows>0){
    db_fieldsmemory($result_reg,0);
  }
  $result_escr = $claverbaescritura->sql_record($claverbaescritura->sql_query(null,"*",null,"j94_averbacao =".$j75_codigo));
  if ($claverbaescritura->numrows>0){
    db_fieldsmemory($result_escr,0);
  }
	if($j93_averbagrupo == 6){
	  $sqlguia = "select averbaguia.*,averbaguiaitbi.*,it03_nome
							    from averbaguia
							         left join averbaguiaitbi on j104_sequencial = j103_averbaguia
							         left join itbinome       on j104_guia       = it03_guia
							   where j104_averbacao = $chavepesquisa
								   and upper(it03_tipo) = 'C'
								 	 and it03_princ is true ";

	  $resultguia = db_query($sqlguia);
	  $linhasguia = pg_num_rows($resultguia);
	  if($linhasguia>0){
		db_fieldsmemory($resultguia,0);
		$nome = $it03_nome;
		$guia=1;
	  }else{
	  	//se não encotrar é pr é sem guia itbi
			$sqlGuiaSemItbi = "select * from averbaguia where j104_averbacao = $chavepesquisa ";
			$rsGuiaSemItbi  = db_query($sqlGuiaSemItbi);
			$linhasGuiaSemItbi = pg_num_rows($rsGuiaSemItbi);
			if($linhasGuiaSemItbi>0){
				db_fieldsmemory($rsGuiaSemItbi,0);
				$guianao = $j104_guia;
			  $guia = 2;
			}
	  }
	}
	if($j93_averbagrupo == 5){

	  $sqlsentenca = "select *
                      from averbadecisaojudicial
	                   where j101_averbacao = $chavepesquisa";
    $resultsentenca = db_query($sqlsentenca);
	  $linhassentenca = pg_num_rows($resultsentenca);
	  if($linhassentenca>0){
		  db_fieldsmemory($resultsentenca,0);
	  }
	}
	if($j93_averbagrupo == 4){

	  $sqlformal = "select *
                    from averbaformalpartilha
	                       left join averbaformalpartilhacgm on j102_averbaformalpartilha = j100_sequencial
	                 where j100_averbacao = $chavepesquisa";
	  $resultformal = db_query($sqlformal);
	  $linhasformal = pg_num_rows($resultformal);
	  if($linhasformal>0){
		db_fieldsmemory($resultformal,0);
		$z01_numcgm1 = $j102_numcgm;
	  }
	}
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
  <div class="container">
    <?php

      $botaoprocessar = true;
      require_once("forms/db_frmaverbacao.php");
    ?>
  </div>
  <?php
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
</body>
</html>
<?php
if(isset($processar)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($claverbacao->erro_campo!=""){
      echo "<script> document.form1.".$claverbacao->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$claverbacao->erro_campo.".focus();</script>";
    };
  }else{
    db_msgbox("Averbação Processada!");
    echo "<script>location.href = 'cad4_averbacao002.php';</script>";
  }
}

if($db_opcao==22||$db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>