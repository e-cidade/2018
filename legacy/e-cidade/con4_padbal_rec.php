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


class bal_rec {
  var $arq=null;

  function bal_rec($header){
    umask(74);
    $this->arq = fopen("tmp/BAL_REC.TXT",'w+');
    fputs($this->arq,$header);
    fputs($this->arq,"\r\n");

  }

  function processa($instit=1,$data_ini="",$data_fim="",$orgaotrib="",$subelemento="") {
    global $contador,$instituicoes;
    $contador=0;

    $tipo_mesini = 1;
    $tipo_mesfim = 1;
    $tipo_impressao = 1;
    // 1 = orcamento
    // 2 = balanco
    $origem = 'B';
    $opcao = 3;

    $sele = " o70_instit in ($instit)";

    $anousu = db_getsession("DB_anousu");
    $result = db_receitasaldo(11,1,$opcao,true,$sele,$anousu,$data_ini,$data_fim,true);


    $result = "select case  when $anousu <= 2007 then
      substr(o57_fonte,2,14)
      else
        case  when fc_conplano_grupo($anousu, substr(o57_fonte,1,1) || '%', 9000 ) is false then
          substr(o57_fonte,2,14)
          else
            substr(o57_fonte,1,15)
              end
              end as o57_fonte,
              substr(o57_fonte,1,1) as teste,
                  o57_descr,
                  saldo_inicial,
                  saldo_arrecadado_acumulado,
                  x.o70_codigo,
                  x.o70_codrec,
                  x.saldo_inicial_prevadic,
                  coalesce(o70_instit,0) as o70_instit,
                  fc_nivel_plano2005(x.o57_fonte) as nivel
                    from (".$result.") as x
                    left join orcreceita on orcreceita.o70_codrec = x.o70_codrec and o70_anousu=$anousu
                    order by o57_fonte
                    ";
          //       where case when substr(o57_fonte,1,15) = '900000000000000' then false else true end

          $result = db_query(analiseQueryPlanoOrcamento($result));

          $array_teste = array();
          $array_erro = array();

          $tottotal = 0;

          for($i=1;$i<pg_numrows($result);$i++){

            $elemento_original = pg_result($result,$i,"o57_fonte");
            $elemento = pg_result($result,$i,"o57_fonte");
            $descr    = pg_result($result,$i,"o57_descr");
            $saldo_inicial = pg_result($result,$i,"saldo_inicial");
            $saldo_arrecadado_acumulado = pg_result($result,$i,"saldo_arrecadado_acumulado");
            $o70_codigo   = pg_result($result,$i,"o70_codigo");
            $descr        = pg_result($result,$i,"o57_descr");
            $o70_codrec   = pg_result($result,$i,"o70_codrec");

            if ($anousu > 2007){
              $sql_orcreceita = "select o70_concarpeculiar
                from orcreceita
                where o70_anousu = $anousu and
                o70_codrec = $o70_codrec";
              $res_orcreceita = @db_query($sql_orcreceita);
              if (@pg_numrows($res_orcreceita) != 0){
                $concarpeculiar = formatar(pg_result($res_orcreceita,0,"o70_concarpeculiar"),3,"n");
              } else {
                $concarpeculiar = "000";
              }
            }

            $o70_instit   = pg_result($result,$i,"o70_instit");
            $nivel        = pg_result($result,$i,"nivel");

            if ($anousu > 2007) {

              if (substr($elemento_original,0,1) == "9") {
                if ($concarpeculiar == 0 and 1==2) {
                  $concarpeculiar = 101;
                }
              } else {
                $nivel        = $nivel - 1;
              }

            } else {
              $nivel        = $nivel - 1;
            }

            $contador ++;
            $line  = formatar($elemento,20,'n');

            $orgaotrib=$instituicoes[$o70_instit];

            $line .= formatar($orgaotrib,4,'n');

            // $line .= $orgaotrib;
            //---------------------------------------------------
            if ($saldo_inicial < 0 ){
              $line .= "-".formatar(abs($saldo_inicial),12,'v');
            } else
              $line .= formatar(abs($saldo_inicial),13,'v');
            //---------------------------------------------------
            if ($saldo_arrecadado_acumulado < 0){
              $line .= "-".formatar(abs($saldo_arrecadado_acumulado),12,'v');
            }else
              $line .= "+".formatar(abs($saldo_arrecadado_acumulado),12,'v');
            //---------------------------------------------------
            $line .= formatar($o70_codigo,4,'n');
            $line .= formatar($descr,170,'c');
            $line .= ($o70_codrec==0?'S':'A');
            $line .= formatar($nivel,2,'n');

            // A partir de 2008, vigora o uso de CARACTERISTICA PECULIAR
            if ($anousu > 2007){
              $line .= $concarpeculiar;
            }

            if ($anousu >= 2014) {

              $nValorInicialMaisPrevisaoAdicional = pg_result($result,$i,"saldo_inicial_prevadic");
              $sSinal = "+";
              if ($nValorInicialMaisPrevisaoAdicional < 0) {
                $sSinal = "-";
              }
              $line .= $sSinal.(formatar(abs($nValorInicialMaisPrevisaoAdicional), 12, 'v'));
            }

            fputs($this->arq,$line);
            fputs($this->arq,"\r\n");

            $array_teste[$i][0]=$elemento;
            $array_teste[$i][1]=($o70_codrec==0?'S':'A');
            $array_teste[$i][2]=$nivel;
            $array_teste[$i][3]=$saldo_inicial;

          }

          $maxnivelanalitico = 0;
          $maxnivelsintetico = 0;
          for ($x=1; $x <= sizeof($array_teste); $x++) {
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

          //     die("maxnivelanalitico: $maxnivelanalitico - maxnivelsintetico: $maxnivelsintetico");

          if (1==2) {

            $erro = false;

            for ($nivel_atual=$maxnivelanalitico; $nivel_atual > 0; $nivel_atual--) {

              //       echo "<br>nivel_atual: $nivel_atual";

              for ($x=1; $x < sizeof($array_teste); $x++) {

                //	 echo "<br>nivel_atual: $nivel_atual - " . $array_teste[$x][1] . " - " . $array_teste[$x][2];

                $proxletra = ($x == sizeof($array_teste)?"A":$array_teste[$x+1][1]);

                if (($array_teste[$x][1] == "S" and $array_teste[$x][2] == $nivel_atual-1) and $proxletra == "A") {

                  $valor_sintetico = $array_teste[$x][3];
                  $soma_analitico=0;

                  for ($y=$x+1; $y < sizeof($array_teste); $y++) {
                    //	     echo "<br>   somando em: " . $array_teste[$y][1] . " - " . $array_teste[$y][2] . " - nivel_atual: $nivel_atual";
                    if ($array_teste[$y][1] == "A" and $array_teste[$y][2] >= $nivel_atual) {
                      $soma_analitico += $array_teste[$y][3];
                    } else {
                      break;
                    }
                  }

                  if (dbround_php_52($valor_sintetico,2) != dbround_php_52($soma_analitico,2)) {
                    echo "<br>elemento: " . $array_teste[$x][0] . " nao fecha - sintetico: $valor_sintetico - analitico: $soma_analitico<br>";
                    //	     $erro=true;
                    break;
                  }

                }

              }

              if ($erro == true) {
                break;
              }

            }

          }

          $numerro=0;

          for ($nivel_atual=$maxnivelsintetico; $nivel_atual > 0; $nivel_atual--) {

            for ($x=1; $x <= sizeof($array_teste); $x++) {

              if ($array_teste[$x][1] == "S" and $array_teste[$x][2] == $nivel_atual) {
                $estrutural_sintetico = $array_teste[$x][0];
                $soma_sintetico = $array_teste[$x][3];
                $soma_analitico = 0;

                //	   echo $array_teste[$x][0] . " - " . $array_teste[$x][1] . " - " . $array_teste[$x][2] .  "<br><br>";

                for ($y=$x+1; $y < sizeof($array_teste); $y++) {

                  //	     echo "   " . $array_teste[$y][0] . " - " . $array_teste[$y][1] . " - " . $array_teste[$y][2];

                  if ($array_teste[$y][1] == "S" and $array_teste[$y][2] <= $nivel_atual) {
                    //	       echo "- saindo<br>";
                    break;
                  } elseif ($array_teste[$y][1] == "A" and $array_teste[$y][2] > $nivel_atual) {
                    //	       echo "- somando<br>";
                    $soma_analitico += $array_teste[$y][3];
                  } elseif ($array_teste[$y][1] == "A" and $array_teste[$y][2] <= $nivel_atual and 1==2) {
                    //	       echo "provavel erro " . $array_teste[$y][0] . "<br>";
                    $array_erro[$numerro][0] = $array_teste[$y][0];
                    $array_erro[$numerro][1] = 1;
                    $numerro++;
                    break;
                  }

                }

                if (dbround_php_52($soma_sintetico,2) != dbround_php_52($soma_analitico,2)) {
                  //	     die("xxx: " . $array_teste[$x][0] . " - sint: $soma_sintetico - anal: $soma_analitico");
                  $array_erro[$numerro][0] = $estrutural_sintetico;
                  $array_erro[$numerro][1] = 2;
                  $numerro++;
                  //	     break;
                }

              }

            }

            //       break;

          }

          if (sizeof($array_erro) > 0) {
            echo "<br><b>PROVAVEIS ERROS NOS ESTRUTURAIS:</b><br>";
            for ($x=0; $x < sizeof($array_erro); $x++) {
              echo $array_erro[$x][0] . "<br>";
            }

          }

          $sqlorcreceita = analiseQueryPlanoOrcamento("select orcreceita.*, c60_estrut
                            from orcreceita
                            left join conplano on o70_codfon = c60_codcon and o70_anousu = c60_anousu
                            left join conplanoreduz on o70_codfon = c61_codcon and o70_anousu = c61_anousu and o70_instit = c61_instit
                            where o70_anousu = $anousu and c61_reduz is null");

          $resultorcreceita = db_query($sqlorcreceita) or die($sqlorcreceita);
          if (pg_numrows($resultorcreceita) > 0) {

            echo "<br><b>ERRO - RECEITAS DO ORCAMENTO SEM REDUZIDO NO PLANO DE CONTAS:</b><br>";

            for ($x=0;$x < pg_numrows($resultorcreceita); $x++) {
              $o70_codrec = pg_result($resultorcreceita,$x,"o70_codrec");
              $o70_codfon = pg_result($resultorcreceita,$x,"o70_codfon");
              $c60_estrut = pg_result($resultorcreceita,$x,"c60_estrut");

              echo "REDUZIDO ORCAMENTO: $o70_codrec - CODCON: $o70_codfon - ESTRUTURAL: $c60_estrut" . "<br>";

            }
          }


          // trailer
          $contador = espaco(10-(strlen($contador)),'0').$contador;
          $line = "FINALIZADOR".$contador;
          fputs($this->arq,$line);
          fputs($this->arq,"\r\n");

          fclose($this->arq);

          db_query("commit");

          $teste = "true";

          @db_query("drop table work_receita");

          return $teste ;
  }
}

?>
