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

class brec_ant {

  var $arq=null;

  function brec_ant($header){
    umask(74);
    $this->arq = fopen("tmp/BREC_ANT.TXT",'w+');
    fputs($this->arq,$header);
    fputs($this->arq,"\n");
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




    $anousu = (db_getsession("DB_anousu")-1);
    $data_ini =  $anousu.'-01-01';
    $data_fim =  $anousu.'-12-31';
    $nomeArq = 'BREC_ANT.TXT';



   /*
    * verifica se ja existe arquivo no banco
    */
    $oDaoArquivosPad  = db_utils::getDao("conarquivospad");
    $rsDaoArquivosPad = $oDaoArquivosPad->sql_record($oDaoArquivosPad->sql_query(null,
                                                                                    "c54_codarq, c54_anousu, c54_nomearq, c54_arquivo",
                                                                                    "",
                                                                                    "c54_anousu      =  {$anousu}
                                                                                     and c54_nomearq = '{$nomeArq}'"
                                                                                    ));

    if ($oDaoArquivosPad->numrows > 0 ){

    	 $oArquivo  = db_utils::fieldsMemory($rsDaoArquivosPad,0);
       $sArquivo   =  $oArquivo->c54_arquivo;

       fputs($this->arq, str_replace("\n\r", "", $sArquivo));
       fputs($this->arq,"\r\n");

       $contador = count(explode("\n",$sArquivo));

    } else {

		    $result = db_receitasaldo(11,1,$opcao,true,$sele,$anousu,$data_ini,$data_fim,true);
		    $result = "select case when fc_conplano_grupo($anousu, substr(o57_fonte,1,1) || '%', 9000 ) is false
		               then substr(o57_fonte,2,14) else substr(o57_fonte,1,15) end as o57_fonte,
		    o57_descr,
		    saldo_inicial,
		    saldo_arrecadado_acumulado,
		    x.o70_codigo,
		    x.o70_codrec,
		    coalesce(o70_instit,0) as o70_instit,
		    fc_nivel_plano2005(x.o57_fonte) as nivel
		    from (".$result.") as x
		    left join orcreceita on orcreceita.o70_codrec = x.o70_codrec and o70_anousu=$anousu
		    order by o57_fonte
		    ";

        if (ParametroPCASP::utilizaPCASPNoAno($anousu)) {
          $result = analiseQueryPlanoOrcamento($result, $anousu);
        }

		    // echo $result; exit;
		    $result = db_query($result);
		    // db_criatabela($result);exit;

		    $tottotal = 0;
		    for($i=1;$i<pg_numrows($result);$i++){
		      $elemento_original = pg_result($result,$i,"o57_fonte");
		      $elemento = pg_result($result,$i,"o57_fonte");
		      $descr    = pg_result($result,$i,"o57_descr");
		      $saldo_inicial = pg_result($result,$i,"saldo_inicial");
		      $saldo_arrecadado_acumulado = pg_result($result,$i,"saldo_arrecadado_acumulado");
		      $o70_codigo   = pg_result($result,$i,"o70_codigo");
		      $descr        = pg_result($result,$i,"o57_descr");
		      if ($descr==""){
		        $descr="Descrição nao localizada - Migração";
		      }

		      $o70_codrec   = pg_result($result,$i,"o70_codrec");


		      $o70_instit   = pg_result($result,$i,"o70_instit");
		      $nivel        = pg_result($result,$i,"nivel");

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

		        if (substr($elemento_original,0,1) == "9") {
		        } else {
		           $nivel        = $nivel - 1;
		        }

		      } else {
		        $nivel        = $nivel - 1;
		      }


		      $line .= formatar($nivel,2,'n');


		      if ($anousu > 2007){
		         $line .= $concarpeculiar;
		      }

		      fputs($this->arq,$line);
		      fputs($this->arq,"\r\n");

		    }
    }
    //  trailer
    $contador = espaco(10-(strlen($contador)),'0').$contador;
    $line = "FINALIZADOR".$contador;
    fputs($this->arq,$line);
    fputs($this->arq, "\r\n");

    fclose($this->arq);

		@db_query("drop table work_receita");

    db_query("commit");

    $teste = "true";
    return $teste ;
  }


}

?>