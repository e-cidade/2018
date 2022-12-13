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
include("dbforms/db_funcoes.php");

include("libs/db_liborcamento.php");
include("classes/db_orcdotacao_classe.php");
include("classes/db_pagordem_classe.php");
include("classes/db_empempenho_classe.php");
include("classes/db_empelemento_classe.php");
include("classes/db_pagordemele_classe.php");
include("classes/db_empnota_classe.php");
include("classes/db_empnotaele_classe.php");
include ("classes/db_empempenhonl_classe.php");

$clempnota     = new cl_empnota;
$clempnotaele  = new cl_empnotaele;
$clpagordem    = new cl_pagordem;
$clpagordemele = new cl_pagordemele;
$clempempenho  = new cl_empempenho;
$clempelemento = new cl_empelemento;
$clorcdotacao  = new cl_orcdotacao;
$oDaoEmpenhoNl = new cl_empempenhonl;

include("classes/db_conlancam_classe.php");
include("classes/db_conlancamele_classe.php");
include("classes/db_conlancamlr_classe.php");
include("classes/db_conlancamcgm_classe.php");
include("classes/db_conlancamemp_classe.php");
include("classes/db_conlancamval_classe.php");
include("classes/db_conlancamdot_classe.php");
include("classes/db_conlancamdoc_classe.php");
include("classes/db_conlancamnota_classe.php");
include("classes/db_conplanoreduz_classe.php");

$clconplanoreduz  = new cl_conplanoreduz;
$clconlancam	  = new cl_conlancam;
$clconlancamele	  = new cl_conlancamele;
$clconlancamlr	  = new cl_conlancamlr;
$clconlancamcgm	  = new cl_conlancamcgm;
$clconlancamemp	  = new cl_conlancamemp;
$clconlancamval	  = new cl_conlancamval;
$clconlancamdot	  = new cl_conlancamdot;
$clconlancamdoc	  = new cl_conlancamdoc;
$clconlancamnota  = new cl_conlancamnota;


include("libs/db_libcontabilidade.php");
$cltranslan       = new cl_translan;

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

  $db_opcao = 22;
  $db_botao = false;

if(isset($confirmar)){
  db_inicio_transacao();
  $sqlerro=false;

   $sql = "update empparametro set e39_anousu = e39_anousu where e39_anousu = ".db_getsession("DB_anousu");
   $res = db_query($sql);

  $result = $clempempenho->sql_record($clempempenho->sql_query_file($e60_numemp));
  db_fieldsmemory($result,0);

  $tot_estornar = $vlrliq_estornar ;
  //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%5555
  //rotina que verifica se ainda existe valor diponivel
    if( ($e60_vlrliq+0)  < ($tot_estornar+0)  ){
      $sqlerro=true;
      $erro_msg = 'Empenho sem valor  liquidado à estornar. Verifique!';
    }
  //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%


   if($sqlerro==false){

    // este teste verifica se poderá ser feito lancamento na data e se tem saldo no empenho

    if($e60_anousu < db_getsession("DB_anousu"))
      $codteste = "34";
    else
      $codteste = "4";

    $sql = "select fc_verifica_lancamento(".$e60_numemp.",'".date("Y-m-d",db_getsession("DB_datausu"))."',".$codteste.",".$tot_estornar.")";

    $result_erro = db_query($sql);

    //db_criatabela($result_erro);exit;

    $erro_msg = pg_result($result_erro,0,0);

    if(substr($erro_msg,0,2) > 0 ){

      $erro_msg = substr($erro_msg,3);
      $sqlerro = true;

    }

  }

  if($sqlerro==false){
    $tot = $e60_vlrliq - $tot_estornar;
//   echo "$tot = $e60_vlrliq - $tot_estornar";
  //  die();
    $clempempenho->e60_vlrliq = "$tot";
    $clempempenho->e60_numemp = $e60_numemp ;
    $clempempenho->alterar($e60_numemp);
    if($clempempenho->erro_status==0){
      $sqlerro=true;
      $erro_msg=$clempempenho->erro_msg;
    }else{
      $ok_msg=$clempempenho->erro_msg;
    }
  }
  if($sqlerro==false){
    //array que irá armazenar os valores de cada elemento para fazer os lancamentos contabeis
    $arr_codeleval = array();

    //$arr_dados é um array com todos os elementos
    $arr_dados = split("#",$dados);

    $tam = count($arr_dados);
    for($i=0; $i<$tam; $i++){
       $arr_ele = split("-",$arr_dados[$i]);
       $elemento = $arr_ele[0];
       $valor    = $arr_ele[1];

       //rotina que pega os valores do empelemento
       $result09 = $clempelemento->sql_record($clempelemento->sql_query($e60_numemp,$elemento,"e64_vlrliq"));
       db_fieldsmemory($result09,0);

       $arr_codeleval[$elemento] = $valor;
       //$arr_ele[0] é o codigo do elemento   $arr_ele[1] é o valor que será estornado  $arr_ele[2] é o valor que já foi anulado

	  $clempelemento->e64_numemp  = $e60_numemp;
	  $clempelemento->e64_codele  = $elemento;

	  //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%\\
          //rotina que verifica se ainda tem disponivel
	      if( ($valor+0) > ($e64_vlrliq+0) ){
		   $erro_msg = "Elemento $elemento do empenho não pode estornar $valor. Verifique!";
		   $sqlerro = true;
		   break;
	      }
         //======================

          if($sqlerro==false){
	    $tot = $e64_vlrliq - $valor;
	    $clempelemento->e64_vlrliq  = "$tot";
	    $clempelemento->alterar($e60_numemp,$elemento);
	    $erro_msg=$clempelemento->erro_msg;
	    if($clempelemento->erro_status==0){
		$sqlerro=true;
		break;
	    }
	  }
    }
  }

  //rotina pega as notas marcadas para atualizar os valores liquidados da notas
   if($sqlerro==false && isset($chaves) && $chaves!=''){
      $arr_notas = split("#",$chaves);
      $tam = count($arr_notas);
      for($i=0; $i<$tam; $i++){
	  $nota = $arr_notas[$i];

	  $result34 = $clempnotaele->sql_record($clempnotaele->sql_query_file($nota));
	  $numrows34 = $clempnotaele->numrows;
	  if($numrows34>0 && $sqlerro==false){
	    for($r=0; $r<$numrows34; $r++){
	      db_fieldsmemory($result34,$r);


              //rotina que verifica se a nota já não foi liquidada...
              if( (0+$e70_vlrliq) == 0 ){
                 $erro_msg = 'Nota já foi  estornada. Verifique!';
		 $sqlerro=true;
		 break;
              }

	      //rotina que atualiza o empnotaele
		$clempnotaele->e70_codnota  = $nota;
		$clempnotaele->e70_codele  = $e70_codele;
		$clempnotaele->e70_vlrliq  = '0' ;
		$clempnotaele->alterar($nota,$e70_codele);
		$erro_msg=$clempnotaele->erro_msg;
		if($clempnotaele->erro_status==0){
		    $sqlerro=true;
		    break;
		}
	    }
	      //final

	  }
       }
    }
   //-----------------------------------------------------//
//rotina que teste se é resto à pagar, se for entra na condição a baixo

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////LANÇAMENTO CONTÁBIL//////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        if($sqlerro==false){
	  $result09 = $clempelemento->sql_record($clempelemento->sql_query($e60_numemp,null,"e64_codele,o56_elemento"));
	  $numrows09  = $clempelemento->numrows;


	}
    /*final*/

	   /*inicio-conlancamval*/
	      $arr_tipos = array(  "0"=>"33",
				   "1"=>"34"
				 );

       //dados comuns
       $anousu  = db_getsession("DB_anousu");
       $datausu = date("Y-m-d",db_getsession("DB_datausu"));

      if($sqlerro==false){
	  for($i=0; $i<$numrows09; $i++){
		 db_fieldsmemory($result09,$i);//pegas os dados do empelemento
		 $valor_liquidar =  $arr_codeleval[$e64_codele];
	         if($valor_liquidar==0){
		   continue;
		 }

	       /*conlancam*/
	       if($sqlerro==false){
		  $clconlancam->c70_anousu = $anousu;
		  $clconlancam->c70_data   = $datausu;
		  $clconlancam->c70_valor  = $valor_liquidar;
		  $clconlancam->incluir(null);
		  $erro_msg=$clconlancam->erro_msg;
		  if($clconlancam->erro_status==0){
		    $sqlerro=true;
		  }else{
		    $c70_codlan = $clconlancam->c70_codlan;
		  }
      $lEvento = EventoContabil::vincularLancamentoNaInstituicao($clconlancam->c70_codlan , db_getsession("DB_instit"));
     $lEvento = EventoContabil::vincularOrdem($clconlancam->c70_codlan);
		}
	       /*fim-conlancam*/

	       /*conlancamele*/
	       if($sqlerro==false){
		  $clconlancamele->c67_codlan = $c70_codlan;
		  $clconlancamele->c67_codele = $e64_codele;
		  $clconlancamele->incluir($c70_codlan);
		  $erro_msg=$clconlancamele->erro_msg;
		  if($clconlancamele->erro_status==0){
		    $sqlerro=true;
		  }
		}
	       /*fim-conlancamele*/

	      /*conlancamnota*/

	      if($sqlerro==false && isset($chaves) && $chaves!=''){
                 $arr_notas = split("#",$chaves);
                 $tam = count($arr_notas);
                 for($inota=0; $inota<$tam; $inota++){
                   $clconlancamnota->c66_codnota = $arr_notas[$inota];
                   $clconlancamnota->c66_codlan = $c70_codlan;
                   $clconlancamnota->incluir($c70_codlan,$arr_notas[$inota]);
                   $erro_msg=$clconlancamnota->erro_msg;
                   if($clconlancamnota->erro_status==0){
		     $sqlerro=true;
		   }
		 }
	      }




	   /*conlancamcgm*/
	    if($sqlerro==false){
		$clconlancamcgm->c76_data   = $datausu;
		$clconlancamcgm->c76_codlan = $c70_codlan;
		$clconlancamcgm->c76_numcgm = $e60_numcgm;
		$clconlancamcgm->incluir($c70_codlan);
		$erro_msg=$clconlancamcgm->erro_msg;
		if($clconlancamcgm->erro_status==0){
		  $sqlerro=true;
		}
	    }
	   /*fim-conlancamcgm*/

	       /*conlancamemp*/
		if($sqlerro==false){
		    $clconlancamemp->c75_codlan = $c70_codlan;
		    $clconlancamemp->c75_numemp = $e60_numemp;
		    $clconlancamemp->c75_data   = $datausu;
		    $clconlancamemp->incluir($c70_codlan);
		    $erro_msg=$clconlancamemp->erro_msg;
		    if($clconlancamemp->erro_status==0){
		      $sqlerro=true;
		    }
		}
	       /*fim-conlancamemp*/






		 //rotina que pega os campos c91_codcondesp e c91_codconpas
		   //   $result11 = $clconparlancam->sql_record($clconparlancam->sql_query_file(null,"c91_codele,c91_codconpas","","c91_codele=$e64_codele"));
		   // db_fieldsmemory($result11,0);


		 //rotina que atualiza os array de creditos e debitos conforme o elemento
		  //testeif($e60_anousu ==  db_getsession("DB_anousu")){
		  if($e60_anousu <  db_getsession("DB_anousu")){
                        $c71_coddoc = '34';
                        $cltranslan->db_trans_estorna_liquida_resto($e60_codcom,$e64_codele,$e60_anousu,$e60_numemp);
		  }else{
		       if(substr($o56_elemento,0,2) == $arr_tipos[0]){
                           $c71_coddoc = '4';
                           $cltranslan->db_trans_estorna_liquida($e60_codcom,$e64_codele,$e60_anousu);
		       }else if(substr($o56_elemento,0,2) == $arr_tipos[1]){
                           $c71_coddoc = '24';
                           $cltranslan->db_trans_estorna_liquida_capital($e60_codcom,$e64_codele,$e60_anousu);
		       }

		  }


	       /*inicio-conlancamdoc*/
		if($sqlerro==false){
		  $clconlancamdoc->c71_data   = $datausu;
		  $clconlancamdoc->c71_coddoc  = $c71_coddoc;
		  $clconlancamdoc->c71_codlan  = $c70_codlan;
		  $clconlancamdoc->incluir($c70_codlan);
		  $erro_msg=$clconlancamdoc->erro_msg;
		  if($clconlancamdoc->erro_status==0){
		    $sqlerro=true;
		  }
		}
	       /*fim-conlancamdoc*/
               //Só entra se o o ano do empenho for atual
               if( $e60_anousu ==  db_getsession("DB_anousu")){
		  /*orcdotacaoval*/
		      if($sqlerro==false){

			$result85 = db_query("select fc_lancam_dotacao($e60_coddot,'$datausu',$c71_coddoc,'$valor_liquidar') as dotacao");
			db_fieldsmemory($result85,0);
			if(substr($dotacao,0,1)==0){ //quando o primeiro caractere for igual a zero eh porque deu erro
			  $sqlerro = true;
			  $erro_msg = "Erro na atualização do orçamento \\n ".substr($dotacao,1);
			}
		      }
		  /*fim-orcdotacaoval*/
		  /*inicio-conlancamdot*/
		   if($sqlerro==false){
		     $clconlancamdot->c73_data   = $datausu;
		     $clconlancamdot->c73_anousu  = $anousu;
		     $clconlancamdot->c73_coddot  = $e60_coddot;
		     $clconlancamdot->c73_codlan  = $c70_codlan;
		     $clconlancamdot->incluir($c70_codlan);
		     $erro_msg=$clconlancamdot->erro_msg;
		     if($clconlancamdot->erro_status==0){
		       $sqlerro=true;
		     }
		   }
		  /*fim-conlancamdot*/
	       }



                  /****************************************************/
		  /* rotina que verifica se os array com os lançamentos naum estão vazios*/
		    if(count($cltranslan->arr_debito)==0 && $sqlerro==false){
			$sqlerro = true;
			$erro_msg = 'Conta débito não cadastrada nas transações.';
		    }
		    if(count($cltranslan->arr_credito)==0 && $sqlerro==false){
			$sqlerro = true;
			$erro_msg = 'Conta crédito não cadastrada nas transações.';
		    }
		    if(count($cltranslan->arr_histori)==0 && $sqlerro==false){
			$sqlerro = true;
			$erro_msg = 'Histórico do lançamento nao encontrado.';
		    }
		  //final=========================================================


		  if($sqlerro==false){
		  $arr_debito  = $cltranslan->arr_debito;
		  $arr_credito = $cltranslan->arr_credito;
		  $arr_histori = $cltranslan->arr_histori;
	          $arr_seqtranslr = $cltranslan->arr_seqtranslr;
		 //final da rotina de atualização de arrays/////////

                    /*
                      echo "debito:";
		      print_r($arr_debito)."<br>";
                      echo "credito:<br>";
		       print_r($arr_credito);
		       die();
                */

		//rotina que inclui no conlancamval
		    for($t=0; $t<count($arr_credito); $t++){
                     //rotina que teste se a conta reduzida foi incluida no conplanoreduz
			  $clconplanoreduz->sql_record($clconplanoreduz->sql_query_file(null,null,'c61_codcon','',"c61_anousu=".db_getsession("DB_anousu")." and c61_reduz=".$arr_debito[$t]));
			  if($clconplanoreduz->numrows==0){
			    $sqlerro=true;
			    $erro_msg = "Conta ".$arr_debito[$t]." não dísponivel para o exercicio!";
			  }
			  $clconplanoreduz->sql_record($clconplanoreduz->sql_query_file(null,null,'c61_codcon','',"c61_anousu=".db_getsession("DB_anousu")." and c61_reduz=".$arr_credito[$t]));
			  if($clconplanoreduz->numrows==0){
			    $sqlerro=true;
			    $erro_msg = "Conta ".$arr_credito[$t]." não dísponivel para o exercicio!";
			  }
		      //final
		      if($sqlerro==false){
			$clconlancamval->c69_codlan  = $c70_codlan;
			$clconlancamval->c69_credito = $arr_credito[$t];
			$clconlancamval->c69_debito  = $arr_debito[$t];
			$clconlancamval->c69_codhist = $arr_histori[$t];
			$clconlancamval->c69_valor   = $valor_liquidar;
			$clconlancamval->c69_data    = $datausu;
			$clconlancamval->c69_anousu  = $anousu;
			$clconlancamval->incluir(null);
			$erro_msg=$clconlancamval->erro_msg;
			if($clconlancamval->erro_status==0){
			  $sqlerro=true;
			  break;
			}else{
			  $c69_sequen =  $clconlancamval->c69_sequen;
			}
		      }

		     /*conlancamlr   */
		     if($sqlerro==false){
			$clconlancamlr->c81_codlan      = $c69_sequen;
			$clconlancamlr->c81_seqtranslr  = $arr_seqtranslr[$t];
			$clconlancamlr->incluir($c69_sequen,$arr_seqtranslr[$t]);
			$erro_msg=$clconlancamlr->erro_msg;
			if($clconlancamlr->erro_status==0){
			  $sqlerro=true;
			  break;
			}
		     }
		     /*final*/
		   }
		 }
		 /*fim-conlancamval*/
	   }
      }
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //FINAL LANÇAMENTO CONTÁBEIS////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  db_fim_transacao($sqlerro);
}
if(isset($e60_numemp)){
  $db_opcao = 2;
  $db_botao = true;
   //rotina que traz os dados de empempenho
   $result = $clempempenho->sql_record($clempempenho->sql_query($e60_numemp));
   db_fieldsmemory($result,0,true);
    $rsNotaLiquidacao  = $oDaoEmpenhoNl->sql_record(
                        $oDaoEmpenhoNl->sql_query_file(null,"e68_numemp","","e68_numemp = {$e60_numemp}"));
   if ($oDaoEmpenhoNl->numrows > 0) {

      echo "<script>location.href='emp4_estornaliquidacao001.php?numemp={$e60_numemp}';</script>";
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
    <tr>
      <td width="360" height="18">&nbsp;</td>
      <td width="263">&nbsp;</td>
      <td width="25">&nbsp;</td>
      <td width="140">&nbsp;</td>
    </tr>
  </table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
	<?
	include("forms/db_frmempliquidaestorna.php");
	?>
    </center>
	</td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
if(isset($confirmar)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
  }else{
    db_msgbox($ok_msg);
  }
}
?>