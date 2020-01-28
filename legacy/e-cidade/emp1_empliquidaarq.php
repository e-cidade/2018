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


  /*
   variáveis necessárias
   $dados = 'dd';
   $e60_numemp =  $e60_numemp;
   $vlrliq     =  $vlrliq;
  */


   //testa valores
   $result = $clempempenho->sql_record($clempempenho->sql_query_file($e60_numemp));
   db_fieldsmemory($result,0,true);

   $e60_vlrliq = db_formatar($e60_vlrliq,'p')+0; // 2526.90
   $vlrliq     = db_formatar($vlrliq,'p')+0; // 349.00
   $valorliquidar = db_formatar($e60_vlrliq+$vlrliq,'p')+0;

   $valorliquidar = db_formatar($valorliquidar,'p')+0; // 2875.9
   $e60_vlremp    = db_formatar($e60_vlremp,'p')+0; // 4935.90
   $e60_vlranu    = db_formatar($e60_vlranu,'p')+0; // 2060.00

   if(  db_formatar($e60_vlremp - $e60_vlranu,'p')+0 < db_formatar($valorliquidar,'f')+0   ){

//     echo "vlremp: $e60_vlremp\n";
//     echo "e60_vlranu: $e60_vlranu\n";
//     echo "valorliquidar: $valorliquidar\n";
//     exit;

     $erro_msg = "Não existe valor para liquidar. Verifique!";
     $sqlerro=true;
   }


   if($sqlerro==false){

    // este teste verifica se poderá ser feito lancamento na data e se tem saldo no empenho

    if($e60_anousu < db_getsession("DB_anousu"))
      $codteste = "33";
    else
      $codteste = "3";

    $sql = "select fc_verifica_lancamento(".$e60_numemp.",'".date("Y-m-d",db_getsession("DB_datausu"))."',".$codteste.",".$vlrliq.")";

    $result_erro = db_query($sql);

    //db_criatabela($result_erro);exit;

    $erro_msg = pg_result($result_erro,0,0);

    if(substr($erro_msg,0,2) > 0 ){

      $erro_msg = substr($erro_msg,3);
      $sqlerro = true;

    }

  }



  if($sqlerro==false){
     $clempempenho->e60_vlrliq = ($valorliquidar);
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

    //$arr_dados é um array com todos os elementos e seus valores
    //$dados =   $elemento-$valorliquidar#$elemento-$valorliquidar#elemen...
    $arr_dados = split("#",$dados);
    $tam = count($arr_dados);
    for($i=0; $i<$tam; $i++){
	  $arr_ele = split("-",$arr_dados[$i]);
          $elemento =  $arr_ele[0];
	  $valor    =  $arr_ele[1];

	  //array utilizado nos lancamento contabeis
	  $arr_codeleval[$elemento] = $valor;


          //rotina que pega os valores do empelemento
          $result09 = $clempelemento->sql_record($clempelemento->sql_query($e60_numemp,$elemento,"e64_vlrliq,e64_vlranu,e64_vlremp"));
          db_fieldsmemory($result09,0);

	  $valor = $valor + $e64_vlrliq  ;
          $valor = db_formatar($valor,'p')+0;

	  //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%\\
          //rotina que verifica se ainda tem disponivel
	      $e64_vlremp = db_formatar($e64_vlremp,'p')+0;
	      $e64_vlranu = db_formatar($e64_vlranu,'p')+0;
	      $dispon = $e64_vlremp - $e64_vlranu;
	      $dispon = db_formatar($dispon,'p')+0;

	      if((0+$valor) > ($dispon+0) ){
		   $erro_msg = "Elemento $elemento do empenho não pode liquidar $valor. Verifique!";
		   $sqlerro = true;
		   break;
	      }
         //======================



	  $clempelemento->e64_numemp  = $e60_numemp;
	  $clempelemento->e64_codele  = $elemento;
	  $clempelemento->e64_vlrliq  = "$valor" ;
	  $clempelemento->alterar($e60_numemp,$elemento);
	  $erro_msg=$clempelemento->erro_msg;
	  if($clempelemento->erro_status==0){
	      $sqlerro=true;
	      break;
	  }
	$clempelemento->e64_numemp  = null;
	$clempelemento->e64_vlremp  = null;      //valor dos itens
	$clempelemento->e64_vlrliq  = null;

    }
    //--------------------
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
              if( ($e70_vlrliq+0) >0 ){
                 $erro_msg = 'Nota já foi liquidada.';
		 $sqlerro=true;
		 break;
              }


	      //rotina que atualiza o empnotaele
	        if($sqlerro==false){
		  $clempnotaele->e70_codnota  = $nota;
		  $clempnotaele->e70_codele  = $e70_codele;

		  $clempnotaele->e70_vlrliq  = "$e70_valor" ;
		  $clempnotaele->alterar($nota,$e70_codele);
		  $erro_msg=$clempnotaele->erro_msg;
		  if($clempnotaele->erro_status==0){
		      $sqlerro=true;
		      break;
		  }
		}
	    }
	      //final

	  }
       }
    }
   //-----------------------------------------------------//
//rotina que teste se é resto à pagar, se for entra na condição a baixo
//die();
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////LANÇAMENTO CONTÁBIL//////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

     //==============================================================================================//
     //			rotina que verifica os elementos estam incluidos na tabela conparlancam      //
     //==============================================================================================//

     if($sqlerro==false){
	  $result09 = $clempelemento->sql_record($clempelemento->sql_query($e60_numemp,null,"e64_codele,o56_elemento"));
	  $numrows09  = $clempelemento->numrows;
      }

    //===============================================================================================//



       //dados comuns
       $anousu  = db_getsession("DB_anousu");
       $datausu = date("Y-m-d",db_getsession("DB_datausu"));

      if($sqlerro==false){
	  for($i=0; $i<$numrows09; $i++){
		 db_fieldsmemory($result09,$i);//pegas os dados do empelemento
		 $valor_liquidar =  $arr_codeleval[$e64_codele];

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
		 //$result11 = $clconparlancam->sql_record($clconparlancam->sql_query_file(null,"c91_codele,c91_codconpas","","c91_codele=$e64_codele"));
		 //   db_fieldsmemory($result11,0);


		 //rotina que atualiza os array de creditos e debitos conforme o elemento
                       //rotina que verifica se o empenho naum é resto a pagar
		       //para testar if($e60_anousu ==  db_getsession("DB_anousu")){
		       if($e60_anousu <  db_getsession("DB_anousu")){
			     $cltranslan->db_trans_liquida_resto($e60_codcom,$e64_codele,$e60_anousu,$e60_numemp);
                             $c71_coddoc = '33';

		       }else{
			     //quando for 33
			     $arr_tipos = array(  "0"=>"33",
						  "1"=>"34"
					       );
			     if(substr($o56_elemento,0,2) == $arr_tipos[0]){
                                 $c71_coddoc = '3';
				 $cltranslan->db_trans_liquida($e60_codcom,$e64_codele,$e60_anousu);
				 //$cltranslan->db_trans_liquida_capital($e60_codcom,$e64_codele,$e60_anousu);
			     }else if(substr($o56_elemento,0,2) == $arr_tipos[1]){
                                 $c71_coddoc = '23';
				 $cltranslan->db_trans_liquida_capital($e60_codcom,$e64_codele,$e60_anousu);

			     }
		       }
		       $arr_debito  = $cltranslan->arr_debito;
		       $arr_credito = $cltranslan->arr_credito;
		       $arr_histori = $cltranslan->arr_histori;
    		       $arr_seqtranslr = $cltranslan->arr_seqtranslr;
/*
		       print_r($arr_debito);
		       print_r($arr_credito);
		       print_r($arr_histori);
*/
		 //final da rotina de atualização de arrays/////////

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
	       /*orcdotacaoval*/
	       //só executará se naum for resto a pagar
	       //rotina que verifica se naum eh resto a pagar
               if($e60_anousu ==  db_getsession("DB_anousu")){
		  if($sqlerro==false){
		    $result85 = db_query("select fc_lancam_dotacao($e60_coddot,'$datausu',$c71_coddoc,'$valor_liquidar') as dotacao");
		    db_fieldsmemory($result85,0);
		    if(substr($dotacao,0,1)==0){ //quando o primeiro caractere for igual a zero eh porque deu erro
		      $sqlerro = true;
		      $erro_msg = "Erro na atualização do orçamento \\n ".substr($dotacao,1);
		    }
		  }

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
	      /*fim-orcdotacaoval*/


		//rotina que inclui no conlancamval
		 for($t=0; $t<count($arr_credito); $t++){
                     //rotina que teste se a conta reduzida foi incluida no conplanoreduz
			  $clconplanoreduz->sql_record($clconplanoreduz->sql_query_file(null,null,'c61_codcon','',"c61_anousu = ".db_getsession("DB_anousu")." and c61_reduz=".$arr_debito[$t]));
			  if($clconplanoreduz->numrows==0){
			    $sqlerro=true;
			    $erro_msg = "Conta ".$arr_debito[$t]." não dísponivel para o exercicio!";

			  }
			  $clconplanoreduz->sql_record($clconplanoreduz->sql_query_file(null,null,'c61_codcon','',"c61_anousu = ".db_getsession("DB_anousu")." and c61_reduz=".$arr_credito[$t]));
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
		 /*fim-conlancamval*/
	   }
      }
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //FINAL LANÇAMENTO CONTÁBEIS////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>