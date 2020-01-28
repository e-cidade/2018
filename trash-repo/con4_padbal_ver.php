<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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


 class bal_ver {
    var $arq=null;

  function bal_ver($header){
      umask(74);
      $this->arq = fopen("tmp/BAL_VER.TXT",'w+');
      fputs($this->arq,$header);
      fputs($this->arq,"\r\n");
  }

  function processa($instit=1,$data_ini="",$data_fim="",$tribinst,$subelemento="") {
    global $instituicoes,$contador,$nomeinst,$sinal_anterior,$sinal_final;

   $where = " c61_instit in ($instit)";

   $result = db_planocontassaldo_matriz(db_getsession("DB_anousu"),$data_ini,$data_fim,false,$where,'',false,'true');
//   db_criatabela($result);exit;

   $contador=0;

   $array_reduzidos_quebra_linha = array();
   $array_teste = array();
   $array_erro = array();

   for($x = 0; $x < pg_numrows($result);$x++){
       global $instituicoes,$c61_instit,$c61_reduz,$nivel,$estrutural,$saldo_anterior,$saldo_anterior_debito,$saldo_anterior_credito,$saldo_final,$c60_descr;
       db_fieldsmemory($result,$x);

       $line  = formatar($estrutural,20,'n');
       if($c61_instit == 0 || empty($c61_instit))
         $line .= "0000";
       else
         $line .= $instituicoes[$c61_instit];    // aqui é o codtrib, da tabela db_config

       if($sinal_anterior=='D'){
           $line .= formatar($saldo_anterior,13,'v');
           $line .= formatar(0,13,'v');
       }else{
           $line .= formatar(0,13,'v');
           $line .= formatar($saldo_anterior,13,'v');
       }
       $line .= formatar($saldo_anterior_debito,13,'v');
       $line .= formatar($saldo_anterior_credito,13,'v');

       if($sinal_final=='D'){

         $line .= formatar(dbround_php_52($saldo_final,2),13,'v');
       	 //$line .= formatar(round($saldo_final,2),13,'v');

         $line .= formatar(0,13,'v');

       }else{

         $line .= formatar(0,13,'v');

         $line .= formatar(dbround_php_52($saldo_final,2),13,'v');
         //$line .= formatar(round($saldo_final,2),13,'v');

       }

       if (!(gettype(strpos($c60_descr, "\n")) == "boolean")) {
         $array_reduzidos_quebra_linha[]=$c61_reduz;
         $c60_descr = str_replace("\n", ' ', $c60_descr);
       }

       $line .= formatar($c60_descr,148,'c');
       $line .= ($c61_reduz == 0?'S':'A');

       // pesquisa nivel da conta

       $sql = "select fc_nivel_plano2005('$estrutural') as nivel ";
       $resultsis = db_query($sql);
       $nivel = pg_result($resultsis,0,'nivel');

       $line .= formatar($nivel,2,'n');

       // pesquisa o sistema da conta orcamentaria, financeiro, etc
       $sql = "select c52_descrred
               from conplano
                   inner join consistema on c60_codsis = c52_codsis
	       where c60_anousu = ".db_getsession("DB_anousu")." and c60_estrut = '$estrutural'";

       //echo $sql ; die();

       $resultsis = db_query($sql);
       $sSistemaContabil = "";
       if(pg_numrows($resultsis)>0){

          $sSistemaContabil =  pg_result($resultsis,0,'c52_descrred');
          //$line .= pg_result($resultsis,0,'c52_descrred');
       }else{

          $sSistemaContabil = "F";
          //$line .= "F";
       }


       /**
        * novos campos
        * se for sistema antigo preenchmento em branco
        */

       //$sSistemaContabil              = " ";
       $sEscrituracao                 = " ";
       $sNaturezaInformacao           = " ";
       $sIndicadorSuperavitFinanceiro = " ";

       if (USE_PCASP) {

         //$estrutural

         $iEstrtutural = substr($estrutural, 0, 1);

         /*
          * @todo criar metodo estatico que revceba o estrutural e devolta a natureza
          * definimos natureza da Informaçao
          */
         switch ($iEstrtutural) {

           case  1:
           case  2:
           case  3:
           case  4:

             $sNaturezaInformacao = "P";
           break;


           case  5:
           case  6:
             $sNaturezaInformacao = "O";
           break;


           case  7:
           case  8:
             $sNaturezaInformacao = "C";
           break;

         }

         $sSistemaContabil              = " ";  // quando é PCASP esse é vazio

         // definimos escrituração

         $sSqlEscrituracao  = "    select distinct c60_codcon                                             ";
         $sSqlEscrituracao .= "      from conplano                                                        ";
         $sSqlEscrituracao .= "inner join conplanoreduz on conplano.c60_codcon = conplanoreduz.c61_codcon ";
         $sSqlEscrituracao .= "                        and conplano.c60_anousu = conplanoreduz.c61_anousu ";
         $sSqlEscrituracao .= "where c60_estrut = '{$estrutural}'                                         ";

         $rsEscrituracao   = db_query($sSqlEscrituracao);
         if (pg_num_rows($rsEscrituracao) > 0) {
           $sEscrituracao = "S";
         } else {
           $sEscrituracao = "N";
         }


         // definimos superavit
         $sSqlSuperavit  = "    select c60_identificadorfinanceiro              ";
         $sSqlSuperavit .= "      from conplano                                 ";
         //$sSqlSuperavit .= "inner join consistema on c60_codsis = c52_codsis  ";
	       $sSqlSuperavit .= "     where c60_anousu = ".db_getsession("DB_anousu") ;
	       $sSqlSuperavit .= "       and c60_estrut = '{$estrutural}'             ";
	       $rsSuperavit    = db_query($sSqlSuperavit);

	       if(pg_numrows($rsSuperavit) > 0){

	         $sIndicadorSuperavitFinanceiro =  pg_result($rsSuperavit,0,'c60_identificadorfinanceiro');
	         if ($sIndicadorSuperavitFinanceiro == "N") {
	           $sIndicadorSuperavitFinanceiro = "P";
	         }
	       }else{

	         $sIndicadorSuperavitFinanceiro = "P";
	       }

         //$sEscrituracao                 = "O";  // verificar na conplanoreduz pelo $estrutural
         //$sNaturezaInformacao           = "C";  // OK
         //$sIndicadorSuperavitFinanceiro = "A";  // verificar campo c52_descrred  (select c52_descrred from conplano inner join consistema on c60_codsis = c52_codsis where c60_anousu = 2013 and c60_estrut = '100000000000000';)
       }

       $line .= $sSistemaContabil;
       $line .= $sEscrituracao;
       $line .= $sNaturezaInformacao;
       $line .= $sIndicadorSuperavitFinanceiro;


       $contador ++;

       fputs($this->arq,$line);
       fputs($this->arq,"\r\n");

       $sql_nivel = "select fc_nivel_plano2005('$estrutural') as nivel";
       $result_nivel = db_query($sql_nivel) or die($sql_nivel);
       db_fieldsmemory($result_nivel,0);

       $array_teste[$x][0]=$estrutural;
       $array_teste[$x][1]=($c61_reduz==0?'S':'A');
       $array_teste[$x][2]=$nivel;
       if ($sinal_anterior=='D') {
         $saldo_anterior = $saldo_anterior*-1;
       }
       $array_teste[$x][3]=$saldo_anterior;

    }

     $maxnivelanalitico = 0;
     $maxnivelsintetico = 0;
     for ($x=0; $x < sizeof($array_teste); $x++) {
       if ($array_teste[$x][1] == "A") {
	       if ($array_teste[$x][2] > $maxnivelanalitico) {
	         $maxnivelanalitico = $array_teste[$x][2];
	        }
       }

       if ($array_teste[$x][1] == "S") {
         if ($array_teste[$x][2] > $maxnivelsintetico) {
           $maxnivelsintetico = $array_teste[$x][2];
         }
       }

     }

     $numerro=0;

     for ($nivel_atual=$maxnivelsintetico; $nivel_atual > 0; $nivel_atual--) {

       for ($x=0; $x < sizeof($array_teste); $x++) {

         if ($array_teste[$x][1] == "S" and $array_teste[$x][2] == $nivel_atual) {
           $estrutural_sintetico = $array_teste[$x][0];
           $soma_sintetico = $array_teste[$x][3];
           $soma_analitico = 0;

           for ($y=$x+1; $y < sizeof($array_teste); $y++) {

             if ($array_teste[$y][1] == "S" and $array_teste[$y][2] <= $nivel_atual) {

               break;
             } elseif ($array_teste[$y][1] == "A" and $array_teste[$y][2] > $nivel_atual) {
               $soma_analitico += $array_teste[$y][3];
             } elseif ($array_teste[$y][1] == "A" and $array_teste[$y][2] <= $nivel_atual and 1==2) {
               $array_erro[$numerro][0] = $array_teste[$y][0];
               $array_erro[$numerro][1] = 1;
               $numerro++;
               break;
             }
	         }

        	   if (dbround_php_52($soma_sintetico,2) != dbround_php_52($soma_analitico,2)) {

        	     $array_erro[$numerro][0] = $estrutural_sintetico;
        	     $array_erro[$numerro][1] = 2;
        	     $numerro++;
        	   }

	         }

       }


     }


     if (sizeof($array_erro) > 0) {

       echo "<br><b>PROVAVEIS ERROS NOS ESTRUTURAIS:</b><br>";
	     for ($x=0; $x <= sizeof($array_erro); $x++) {
	       echo $array_erro[$x][0] . "<br>";
       }
     }


    if (sizeof($array_reduzidos_quebra_linha) > 0) {
      $linha_reduzidos="";
      for ($x=0; $x < sizeof($array_reduzidos_quebra_linha); $x++) {
        $linha_reduzidos .= $array_reduzidos_quebra_linha[$x] . ($x == sizeof($array_reduzidos_quebra_linha) - 1?".":",");
      }
      echo "<font size='1' color='red'><br><b>AVISO: reduzidos de contas com descrição contendo quebras de linha: $linha_reduzidos<br>O sistema retirou as quebras de linha na geracao do TXT, mas você deve acertar isso para não ter problemas com outras rotinas, acessando o cadastro do plano de contas em Contabilidade->Cadastros->Plano de Contas->Alteração.</b><br></font>";
    }
    //  trailer
    $contador = espaco(10-(strlen($contador)),'0').$contador;
    $line = "FINALIZADOR".$contador;
    fputs($this->arq,$line);
    fputs($this->arq,"\r\n");

    fclose($this->arq);

    $teste = "true";

    @db_query("drop table work_pl");

    return $teste;
 }

}
?>