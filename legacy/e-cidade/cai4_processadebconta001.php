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
require_once(modification("libs/db_utils.php"));

require_once(modification("classes/db_db_config_classe.php"));
require_once(modification("classes/db_cadban_classe.php"));
require_once(modification("classes/db_disarq_classe.php"));
require_once(modification("classes/db_disbanco_classe.php"));

require_once(modification("classes/db_debcontapedidotiponumpre_classe.php"));
require_once(modification("classes/db_debcontapedido_classe.php"));
require_once(modification("classes/db_debcontapedidocgm_classe.php"));
require_once(modification("classes/db_debcontapedidomatric_classe.php"));
require_once(modification("classes/db_debcontapedidoinscr_classe.php"));

require_once(modification("classes/db_debcontaarquivoregped_classe.php"));
require_once(modification("classes/db_debcontaarquivoreg_classe.php"));
require_once(modification("classes/db_debcontaarquivo_classe.php"));
require_once(modification("classes/db_debcontaarquivoregmov_classe.php"));
require_once(modification("classes/db_debcontaarquivoregret_classe.php"));

require_once(modification("dbforms/db_funcoes.php"));

db_postmemory($_POST);

$erro   = false;
$instit = db_getsession("DB_instit");


$clcadban 			            = new cl_cadban;
$cldisarq 			            = new cl_disarq;
$cldisbanco 		            = new cl_disbanco;
$cldb_config                = new cl_db_config;

$cldebcontapedidotiponumpre = new cl_debcontapedidotiponumpre;
$cldebcontapedido 		      = new cl_debcontapedido;
$cldebcontapedidocgm 		    = new cl_debcontapedidocgm;
$cldebcontapedidomatric 	  = new cl_debcontapedidomatric;
$cldebcontapedidoinscr 		  = new cl_debcontapedidoinscr;

$cldebcontaarquivoregped 	  = new cl_debcontaarquivoregped;
$cldebcontaarquivoreg 		  = new cl_debcontaarquivoreg;
$cldebcontaarquivo 		      = new cl_debcontaarquivo;
$cldebcontaarquivoreg 		  = new cl_debcontaarquivoreg;
$cldebcontaarquivoregret	  = new cl_debcontaarquivoregret;
$cldebcontaarquivoregmov	  = new cl_debcontaarquivoregmov;

$db_opcao = 1;
$db_botao = true;
$situacao = 0;

$iInstitSessao = db_getsession("DB_instit");

$result = $cldb_config->sql_record($cldb_config->sql_query_file($iInstitSessao, "cgc"));
db_fieldsmemory($result, 0);

if (isset($processar)) {

  db_postmemory($_FILES["arqret"]);

  $arq_name    = basename($name);
  $arq_type    = $type;
  $arq_tmpname = basename($tmp_name);
  $arq_size    = $size;
  $arq_array = file($tmp_name);

  system("cp -f ".$tmp_name." ".ECIDADE_PATH."tmp");
  system("rename ".ECIDADE_PATH."tmp/".$arq_tmpname." ".ECIDADE_PATH."tmp/".$arq_name);
  
  $sSqlBuscaBanco = $clcadban->sql_query("","*",""," k15_codbco = $d63_banco and k15_codage = '$k15_codage' and k15_instit = $instit");
  $resultcadban   = $clcadban->sql_record($sSqlBuscaBanco);

  if ($clcadban->numrows == 0) {

    $erro_msg =  "Banco / Agencia não cadastrados para esta instituição.";
    $erro = true;
  }

  if ($erro == false) {
    db_fieldsmemory($resultcadban,0);

    $_tamanprilinha = $arq_array[0];
    $atipo          = substr($arq_array[0],0,3);
    $totalproc      = sizeof($arq_array)-2;
    $priregistro    = 1;
    $acodbco        = substr($arq_array[0],substr($k15_posbco,0,3),substr($k15_posbco,3,3));

    if (strlen($_tamanprilinha) != $k15_taman) {
      $erro_msg =  "Tamanho do registro [".strlen($arq_array[0])."] Sistema : [" .$k15_taman."] inválido";
      $erro = true;
    } else {

      if ($k15_codbco != $acodbco) {
        $erro_msg =  "Banco Digitado [$k15_codbco] não confere com o arquivo [$acodbco] especificado.";
        $erro = true;
      } else {

        $resultdisarq = $cldisarq->sql_record($cldisarq->sql_query("","*",""," arqret = '$arq_name'"));
        if ($cldisarq->numrows > 0) {
          db_fieldsmemory($resultdisarq,0);
        }

      }

      $totalvalorpago=0;

      for ($i=0; $i <= $totalproc; $i++) {

        $vlrpago	= (substr($arq_array[$i], substr($k15_posvlr, 0, 3) - 1 , substr($k15_posvlr, 3, 3)) / 100) + 0;
        $totalvalorpago += $vlrpago;

      }

      $situacao = 1;

    }

  } else {

    $alert = "alert('Ocorreu algum erro durante o processamento!\\nErro: $erro_msg')";
    echo "<script>$alert;</script>";
    db_redireciona();
  }

  if (isset($processar) and (isset($arq_name))) {
    
    $situacao = 2;
    global $arq_array;
    $arq_array = file(ECIDADE_PATH."tmp/".$arq_tmpname);
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" style="margin-top: 10px;">
<table width="790" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="360" height="15">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
	    <?php
        include(modification("forms/db_frmprocessadebconta.php"));
      ?>
    </center>
	</td>
  </tr>
</table>
<?php
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?php

if (isset($geradebcta)) {
 
  unset($processar, $geradebcta);
  
  $arq_array = file($arq_tmpname);
  $totalproc = count($arq_array);
  $sqlerro   = false;

  db_inicio_transacao();
  $sSql = $clcadban->sql_query("","*",""," k15_codbco = $d63_banco and k15_codage = '$k15_codage' and k15_instit = $instit");
  $resultcadban = $clcadban->sql_record($sSql);
  
  if ($clcadban->numrows == 0) {
    
    $erro_msg =  "Banco / Agencia não cadastrados para esta instituição.";
    $erro     = true;
    $sqlerro  = true;
  }

  if ($sqlerro == false) {

    db_fieldsmemory($resultcadban,0);

    $dtarquivo  = substr($arq_array[0],substr($k15_pdano,0,3)-1,substr($k15_pdano,3,3));
    $dtarquivo .= "-".substr($arq_array[0],substr($k15_pdmes,0,3)-1,substr($k15_pdmes,3,3));
    $dtarquivo .= "-".substr($arq_array[0],substr($k15_posdta,0,3)-1,substr($k15_posdta,3,3));
    
    $cldisarq->k15_codbco = $d63_banco;
    $cldisarq->k15_codage = $k15_codage;
    $cldisarq->arqret     = $arq_name;
    $cldisarq->dtretorno  = date('Y-m-d',db_getsession("DB_datausu"));
    $cldisarq->id_usuario = db_getsession("DB_id_usuario");
    $cldisarq->dtarquivo  = $dtarquivo;
    $cldisarq->textoret	  = implode("", $arq_array);
    $cldisarq->k00_conta  = $k15_conta;
    $cldisarq->autent	    = "false";
    $cldisarq->instit     = db_getsession("DB_instit");
    $cldisarq->md5        = 'null';
    $cldisarq->incluir(null);

    if ($cldisarq->erro_status == 0) {

      $sqlerro  = true;
      $erro_msg = "disarq - " . $cldisarq->erro_msg;
    }
  }
  
  
  if ($sqlerro == false) {

    $achou_arrecant = 0;

    $k15_numpreori = $k15_numpre;
    $k15_numparori = $k15_numpar;
    $priregistro   = 1;

    if ($sqlerro == false) {
      
      $verifica_arq         = false;
      $valor_processado     = 0;
      $valor_nao_processado = 0;

      $_debug = false;

      db_criatermometro('termometro', 'Concluido...', 'blue', 1);
      flush();

      //
      // Processa Registros do Arquivo para Gravar em DISBANCO
      //
     
      for ($i=0; $i < $totalproc; $i++) {
      
        db_atutermometro($i, $totalproc, 'termometro');

        // Testa tipo do registro
        if (substr($arq_array[$i],0,1) <> "F") {
          continue;
        }

        // grava arquivo disbanco
        $debcta = substr($arq_array[$i],substr($k15_debcta,0,3)-1,substr($k15_debcta,3,3));
        
        if ($_debug) {
          
          echo "processando: $i - total $totalproc - debcta $debcta <br>";
          flush();
        }

        $numbco = substr($arq_array[$i],substr($k15_numbco,0,3)-1,substr($k15_numbco,3,3));
        $dtarq  = $dtarquivo;
        
        $dtpago  = substr($arq_array[$i], substr($k15_ppano, 0, 3) - 1, substr($k15_ppano, 3, 3));
        $dtpago .= "-".substr($arq_array[$i], substr($k15_ppmes , 0, 3) - 1, substr($k15_ppmes, 3, 3));
        $dtpago .= "-".substr($arq_array[$i], substr($k15_pospag, 0, 3) - 1, substr($k15_pospag, 3, 3));
        
        $vlrpago	= empty($k15_posvlr) ? 0 : (substr($arq_array[$i], substr($k15_posvlr, 0, 3) - 1, substr($k15_posvlr, 3, 3)) / 100) + 0;
        $vlrjuros	= empty($k15_posjur) ? 0 : (substr($arq_array[$i], substr($k15_posjur, 0, 3) - 1, substr($k15_posjur, 3, 3)) / 100) + 0;
        $vlrmulta	= empty($k15_posmul) ? 0 : (substr($arq_array[$i], substr($k15_posmul, 0, 3) - 1, substr($k15_posmul, 3, 3)) / 100) + 0;
        $vlracres	= empty($k15_posacr) ? 0 : (substr($arq_array[$i], substr($k15_posacr, 0, 3) - 1, substr($k15_posacr, 3, 3)) / 100) + 0;
        $vlrdesco	= empty($k15_posdes) ? 0 : (substr($arq_array[$i], substr($k15_posdes, 0, 3) - 1, substr($k15_posdes, 3, 3)) / 100) + 0;

        $convenio	=  substr($arq_array[$i], substr($k15_poscon, 0, 3) - 1, substr($k15_poscon, 3, 3));
        $cedente	=  substr($arq_array[$i], substr($k15_posced, 0, 3) - 1, substr($k15_posced, 3, 3));

        $tiporet  = substr($arq_array[$i], 67, 2);

        if ($debcta != "") {

          $sqldebcontapedido = $cldebcontapedido->sql_query("","d63_codigo",""," trim(d63_idempresa) = '" . trim($debcta) . "'");
          if ($_debug) {
            
            echo "  >>  passo 1 <br>";
            flush();
          }
          
          $resultdebcontapedido = $cldebcontapedido->sql_record($sqldebcontapedido);
          if ($cldebcontapedido->numrows == 0) {
            
            $erro_msg = "Não foi encontrado Cadastro no Debito em Conta com ID Empresa (".trim($debcta)."). Arquivo Recusado!!";
            $sqlerro  = true;
            break;
          }
          db_fieldsmemory($resultdebcontapedido,0);
          /*

          ESSA PORCAO DE CODIGO FOI UTILIZADA PARA PROCESSAMENTO DE ARQUIVO DE RETORNO SEM REMESSA GERADA NO DBPORTAL2(utilizado no DAEB para processar remessas geradas no sistema anterior)

          $sqlprocura = "select d68_matric from debcontapedidomatric where d68_codigo = $d63_codigo";
          $resultprocura = db_query($sqlprocura) or die($sqlprocura);
          if (pg_numrows($resultprocura) == 0) {
            //die("\nerro 1\n");
            die($sqlprocura);
          }
          db_fieldsmemory($resultprocura,0);

          $sqlprocura = "
          select distinct numpre, numpar from(select arrecad.k00_numpre as numpre,
          arrecad.k00_numpar as numpar
          from arrematric
          inner join arrecad on arrecad.k00_numpre = arrematric.k00_numpre
          where k00_matric = $d68_matric and k00_tipo = 37 and k00_numpar = 2
          union
          select arrecant.k00_numpre as numpre,
          arrecant.k00_numpar as numpar
          from arrematric
          inner join arrecant on arrecant.k00_numpre = arrematric.k00_numpre
          where k00_matric = $d68_matric and k00_tipo = 37 and k00_numpar = 2
          ) as x
          ";
          $resultprocura = db_query($sqlprocura) or die($sqlprocura);
          if (pg_numrows($resultprocura) > 0) {
            db_fieldsmemory($resultprocura,0);
          } else {
            //die("\nerro 2\n");
            die($sqlprocura);
          }
          */

          $numpre = substr($arq_array[$i],73,8);
          $numpar = substr($arq_array[$i],82,3);

          // Efetuar a troca do NUMPRE pela work_arreinstit
          //
          // D A E B
          //
          if ($cgc == '90940172000138') {
            //
            $sqlwork  = "select k00_numpre_dst ";
            $sqlwork .= "  from work_arreinstit ";
            $sqlwork .= " where k00_numpre_ori = {$numpre} ";

            $rsWork = db_query($sqlwork);

            if(pg_numrows($rsWork)>0) {
              db_fieldsmemory($rsWork, 0);
              $numpre = $k00_numpre_dst;
            }
          }

          $sqlarrecad  = "select k00_dtvenc, k00_tipo from arrecad where k00_numpre = $numpre and k00_numpar = $numpar ";
          $sqlarrecad .= "union ";
          $sqlarrecad .= "select k00_dtvenc, k00_tipo from arrecant where k00_numpre = $numpre and k00_numpar = $numpar ";
          $sqlarrecad .= "union ";
          $sqlarrecad .= "select k00_dtvenc, k00_tipo from arreold where k00_numpre = $numpre and k00_numpar = $numpar ";

          //die($sqlarrecad);
          if ($_debug) {
            
            echo "  >>  passo 2 <br>";
            flush();
          }

          $resultarrecad = db_query($sqlarrecad) or die($sqlarrecad);
          if (pg_numrows($resultarrecad) == 0) {
            
            $sqlerro = true;
            $erro_msg = "linha: ".($i+1)." idempresa: $debcta numpre: $numpre - numpar: $numpar - tipo: $k00_tipo nao encontrado em arrecad/arrecant/arreold";
            break;
          } else {
            db_fieldsmemory($resultarrecad, 0);
          }

          if ($sqlerro == false) {
            $sql = $cldebcontaarquivo->sql_query_tipo("", "distinct d72_nsa", "",
                                                 "    d72_tipo   = 1
                                                  and d72_numpar = $k00_numpar
                                                  and d72_banco  = $d63_banco
                                                  and d72_instit = ".db_getsession("DB_instit")."
                                                  and case
																									      when d79_arretipo is not null then
																									        d79_arretipo = $k00_tipo
																												else
																													d72_arretipo = $k00_tipo
																											end
                                                  ");
            if ($_debug) {
              
              echo "  >>  passo 3 <br>";
              flush();
              if ($i == 40) {
                echo "$sql <br>";
              }
            }

            $resultdebcontaarquivo = $cldebcontaarquivo->sql_record($sql);

            if ($cldebcontaarquivo->numrows == 0) {
              
              $erro_msg = "linha: ".($i+1)." idempresa: $debcta numpar: $numpar - tipo: 1 - arretipo: $k00_tipo - banco: $d63_banco nao encontrado no debcontaarquivo";
              $sqlerro = true;
              break;
            } else {
              db_fieldsmemory($resultdebcontaarquivo, 0);
            }

            //$sqldebcontaarquivo = $cldebcontaarquivo->sql_query("","d72_nsa",""," d72_tipo = 2 and d72_numpar = $numpar and d72_arretipo = $k00_tipo and d72_banco = $d63_banco");
            $sqldebcontaarquivo  = "select count(*) as d99_conta ";
            $sqldebcontaarquivo .= "  from debcontaarquivo ";
            $sqldebcontaarquivo .= "       inner join debcontaarquivoreg     on d73_codigo = d72_codigo ";
            $sqldebcontaarquivo .= "       inner join debcontaarquivoregret  on d76_debcontaarqreg  = d73_sequencial ";
            $sqldebcontaarquivo .= "                                        and d76_debcontatiporet = 0 ";
            $sqldebcontaarquivo .= "       inner join arretipo               on arretipo.k00_tipo = debcontaarquivo.d72_arretipo ";
            $sqldebcontaarquivo .= "       inner join bancos                 on bancos.codbco     = debcontaarquivo.d72_banco ";
            $sqldebcontaarquivo .= "       inner join cadtipo                on cadtipo.k03_tipo  = arretipo.k03_tipo ";
            $sqldebcontaarquivo .= " where d72_tipo     = 2 ";
            $sqldebcontaarquivo .= "   and d72_numpar   = $numpar ";
            $sqldebcontaarquivo .= "   and d72_arretipo = $k00_tipo ";
            $sqldebcontaarquivo .= "   and d72_banco    = $d63_banco ";
            $sqldebcontaarquivo .= "   and d72_instit   = ".db_getsession("DB_instit");
            $sqldebcontaarquivo .= "   and extract(year from d72_data) = ".db_getsession("DB_anousu");

            if ($_debug) {
              
              echo "  >>  passo 4 <br>";
              flush();
            }

            $resultdebcontaarquivo = $cldebcontaarquivo->sql_record($sqldebcontaarquivo);
            db_fieldsmemory($resultdebcontaarquivo, 0);
            
            if ($d99_conta == 0 && $verifica_arq==false) {
                $verifica_arq = true;
                $cldebcontaarquivo->d72_nsa      = $d72_nsa;
                $cldebcontaarquivo->d72_tipo     = 2;
                // retorno
                $cldebcontaarquivo->d72_data     = $cldisarq->dtretorno;
                $cldebcontaarquivo->d72_hora     = db_hora();
                $cldebcontaarquivo->d72_usuario  = $cldisarq->id_usuario;
                $cldebcontaarquivo->d72_nome     = $arq_name;
                $cldebcontaarquivo->d72_conteudo = implode("", $arq_array);
                $cldebcontaarquivo->d72_numpar	 = $numpar;
                $cldebcontaarquivo->d72_arretipo = $k00_tipo;
                $cldebcontaarquivo->d72_banco		 = $d63_banco;
                $cldebcontaarquivo->d72_instit   = $instit;

                if ($_debug) {
                  
                  echo "  >>  passo 5 <br>";
                  flush();
                }

                $cldebcontaarquivo->incluir(null);

                if ($cldebcontaarquivo->erro_status == 0) {
                  
                  $sqlerro = true;
                  $erro_msg = "debcontaarquivo - " . $cldebcontaarquivo->erro_msg;
                  break;
                }

              } else {
                
                if ($i == 1) {
                  
                  $sqlerro = true;
                  $erro_msg = "debcontaarquivo - Arquivo $arq_name já processado...";
                  break;
                }
              }

              $cldebcontaarquivoreg->d73_codigo = $cldebcontaarquivo->d72_codigo;
              $cldebcontaarquivoreg->d73_tipo 	= 2;
              ///// retorno do envio
              if ($_debug) {
                
                echo "  >>  passo 6 <br>";
                flush();
              }

              $cldebcontaarquivoreg->incluir(null);
              if ($cldebcontaarquivoreg->erro_status == 0) {
                
                $sqlerro = true;
                $erro_msg = "debcontaarquivoreg - " . $cldebcontaarquivoreg->erro_msg;
                break;
              }
              
              if ($sqlerro == false) {

                $cldebcontaarquivoregret->d76_debcontatiporet = $tiporet;
                $cldebcontaarquivoregret->d76_debcontaarqreg 	= $cldebcontaarquivoreg->d73_sequencial;

                if ($_debug) {
                  
                  echo "  >>  passo 7 <br>";
                  flush();
                }

                $cldebcontaarquivoregret->incluir(null);
                if ($cldebcontaarquivoregret->erro_status == 0) {
                  
                  $sqlerro = true;
                  $erro_msg = "debcontaarquivoregret - " . $cldebcontaarquivoregret->erro_msg;
                  break;
                }

                if ($sqlerro == false) {

                  $cldebcontaarquivoregmov->d75_codigo 	= $cldebcontaarquivoreg->d73_sequencial;
                  $cldebcontaarquivoregmov->d75_venc	= $k00_dtvenc;
                  $cldebcontaarquivoregmov->d75_valor	= $vlrpago+$vlrjuros+$vlrmulta+$vlracres-$vlrdesco;
                  $cldebcontaarquivoregmov->d75_numpar	= $numpar;

                  if ($_debug) {
                    
                    echo "  >>  passo 8 <br>";
                    flush();
                  }

                  $cldebcontaarquivoregmov->incluir(null);
                  if ($cldebcontaarquivoregmov->erro_status == 0) {
                    
                    $sqlerro = true;
                    $erro_msg = "debcontaarquivoregmov - " . $cldebcontaarquivoregmov->erro_msg;
                    break;
                  }

                  if ($sqlerro == false) {

                    $cldebcontaarquivoregped->d80_arquivoreg 	= $cldebcontaarquivoreg->d73_sequencial;
                    $cldebcontaarquivoregped->d80_pedido 	= $d63_codigo;

                    if ($_debug) {
                      
                      echo "  >>  passo 9 <br>";
                      flush();
                    }

                    $cldebcontaarquivoregped->incluir(null);
                    if ($cldebcontaarquivoregped->erro_status == 0) {
                      
                      $sqlerro = true;
                      $erro_msg = "debcontaarquivoregped - " . $cldebcontaarquivoregped->erro_msg;
                      break;
                    }

                  }

                }

              }

            }

          } else {

            $k15_numpre = $k15_numpreori;
            $k15_numpar = $k15_numparori;
            $numpre     = substr($arq_array[$i],substr($k15_numpre,0,3)-1,substr($k15_numpre,3,3));
            $numpar     = substr($arq_array[$i],substr($k15_numpar,0,3)-1,substr($k15_numpar,3,3));
          }

          if ( ($sqlerro == false) && ( $cldebcontaarquivoregret->d76_debcontatiporet == 0 or $cldebcontaarquivoregret->d76_debcontatiporet == 31 ) ) {

            $cldisbanco->codret     = $cldisarq->codret;
            $cldisbanco->k15_codbco = $k15_codbco;
            $cldisbanco->k15_codage = $k15_codage;
            $cldisbanco->k00_numbco = $numbco;
            $cldisbanco->dtarq      = $dtarq;
            $cldisbanco->dtpago     = $dtpago;
            $cldisbanco->vlrpago    = $vlrpago;
            $cldisbanco->vlrjuros   = "$vlrjuros";
            $cldisbanco->vlrmulta   = $vlrmulta;
            $cldisbanco->vlracres   = $vlracres;
            $cldisbanco->vlrdesco   = $vlrdesco;
            $cldisbanco->cedente    = $cedente;
            $cldisbanco->vlrtot     = $vlrpago+$vlrjuros+$vlrmulta+$vlracres-$vlrdesco;
            $cldisbanco->vlrcalc		= $vlrpago+$vlrjuros+$vlrmulta+$vlracres-$vlrdesco;
            $cldisbanco->classi     = "false";
            $cldisbanco->k00_numpre = $numpre+0;
            $cldisbanco->k00_numpar	= $numpar+0;
            $cldisbanco->convenio   = $convenio;
            $cldisbanco->k00_numbco = "0";
            $cldisbanco->instit     = db_getsession("DB_instit");
            $cldisbanco->dtcredito  = $dtpago;

            if ($_debug) {
              echo "  >>  passo 10 <br>";
              flush();
            }
            
            $cldisbanco->incluir(null);
            if ($cldisbanco->erro_status==0) {
              $sqlerro = true;
              $erro_msg = "disbanco - " . $cldisbanco->erro_msg;
              break;
            }

          } else {
            $valor_nao_processado += $vlrpago;
          }

        }

      }

      if ($sqlerro == true) {

        db_fim_transacao(true);        
        $alert = "alert('Ocorreu algum erro durante o processamento!\\nErro: $erro_msg')";
        echo "<script>$alert;</script>";
        db_redireciona();
      } else {

        $sql = "select dtarq, sum(vlrpago) from disbanco where codret = " . $cldisbanco->codret .  " group by dtarq";
        $result = db_query($sql);

        $total = 0;
        $_msg = "";
        for ($x = 0; $x < pg_numrows($result); $x++) {
          
          db_fieldsmemory($result,$x,true);
          $_msg .= "\\nCodRet: ".$cldisarq->codret."\\nData: $dtarq\\nValor Processado: R$" . db_formatar($sum,"f");
          $total += $sum;
        }
        $_msg .= "\\nValor Nao Processado: R$" . db_formatar($valor_nao_processado,"f");
        $_msg .= "\\nTotal Arquivo: R$" . db_formatar($total + $valor_nao_processado,"f");

        if ($achou_arrecant == 0) {
          
          db_fim_transacao($sqlerro);
          echo "<script>alert('Arquivo Processado com SUCESSO!!\\n$_msg');</script>";
          
          
        } else {
          echo "<script>alert('Arquivo Nao Processado porque existem pagamentos!')';</script>";
        }

      }

  } else {
    db_fim_transacao(true);        
    $alert = "alert('Ocorreu algum erro durante o processamento!\\nErro: $erro_msg')";
    echo "<script>$alert;</script>";
    db_redireciona();
  }

  }
?>