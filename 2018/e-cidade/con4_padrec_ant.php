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

class rec_ant {
  var $arq=null;

  function rec_ant($header){
    umask(74);
    $this->arq = fopen("tmp/REC_ANT.TXT",'w+');
    fputs($this->arq,$header);
    fputs($this->arq,"\r\n");
  }

  function acerta_valor ($valor,$quant){
    if($valor<0){
      $valor *= -1;
      $valor = "-".formatar($valor,$quant-1,'v');
    }else{
      $valor = formatar($valor,$quant,'v');
    }
    return $valor;
  }

  function processa($instit=1,$data_ini="",$data_fim="",$tribinst =null,$subelemento="") {
    global $o70_instit,$instituicoes,$o70_codrec,$o70_valor,$nomeinst,$o57_fonte,$o57_fontes,$janeiro,$fevereiro,$marco,$abril,$maio,$junho,$julho,$agosto,$setembro,$outubro,$novembro,$dezembro;
    $contador=0;

    $xtipo = 0;
    $origem = "B";
    $opcao = 3;

    $clreceita_saldo_mes = new cl_receita_saldo_mes;

    $anousu = db_getsession('DB_anousu')-1;
    $nomeArq = 'REC_ANT.TXT';

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

    if ($oDaoArquivosPad->numrows > 0){

      $oArquivo  = db_utils::fieldsMemory($rsDaoArquivosPad,0);
      $sArquivo   =  $oArquivo->c54_arquivo;

      fputs($this->arq, str_replace("\n\r", "", $sArquivo));
      fputs($this->arq,"\r\n");

      $contador = count(explode("\n",$sArquivo));


    } else {

      $clreceita_saldo_mes->anousu = (db_getsession('DB_anousu')-1);
      $clreceita_saldo_mes->dtini  = (db_getsession('DB_anousu')-1)."-01-01";
      $clreceita_saldo_mes->dtfim  = (db_getsession('DB_anousu')-1)."-12-31";

      $clreceita_saldo_mes->instit = $instit;
      $clreceita_saldo_mes->sql_record();

      $valortotal = 0;

      for($i=1;$i<$clreceita_saldo_mes->numrows;$i++){
        db_fieldsmemory($clreceita_saldo_mes->result,$i);
        // pesquisa orgaotrib
        $orgaotrib=$instituicoes[$o70_instit];

        $line  = formatar(substr($o57_fonte,1,14),20,'n'); // recompisoção

        if ($anousu > 2007) {
          if (db_conplano_grupo(@$o70_anousu,substr($o57_fonte,0,1)."%",9000) == false) {
            $line  = formatar(substr($o57_fonte,1,14),20,'n'); // recompisoção
          } else {
            $line  = formatar(substr($o57_fonte,0,15),20,'n'); // recompisoção
          }
        } else {
          $line  = formatar(substr($o57_fonte,1,14),20,'n'); // recompisoção
        }

        $line .= formatar($orgaotrib,4,'n');

        $line .= $this->acerta_valor($janeiro,13);
        $line .= $this->acerta_valor($fevereiro,13);
        $line .= $this->acerta_valor($marco,13);
        $line .= $this->acerta_valor($abril,13);
        $line .= $this->acerta_valor($maio,13);
        $line .= $this->acerta_valor($junho,13);
        $line .= $this->acerta_valor($julho,13);
        $line .= $this->acerta_valor($agosto,13);
        $line .= $this->acerta_valor($setembro,13);
        $line .= $this->acerta_valor($outubro,13);
        $line .= $this->acerta_valor($novembro,13);
        $line .= $this->acerta_valor($dezembro,13);


        if ($anousu > 2007){
          $sql_orcreceita = "select o70_concarpeculiar, o70_codigo
				                from orcreceita
				                where o70_anousu = $anousu and
				                o70_codrec = $o70_codrec";
          $res_orcreceita = @db_query($sql_orcreceita);
          if (@pg_numrows($res_orcreceita) != 0){
            $concarpeculiar = formatar(pg_result($res_orcreceita,0,"o70_concarpeculiar"),3,"n");
            $recurso = formatar(pg_result($res_orcreceita,0,"o70_codigo"),4,"n");
          } else {
            $concarpeculiar = "000";
            $recurso = "0000";
          }
          $line .= $concarpeculiar.$recurso;
        }

        $contador++;
        fputs($this->arq,$line);
        fputs($this->arq,"\r\n");

      }

    }

    //  trailer
    $contador = espaco(10-(strlen($contador)),'0').$contador;
    $line = "FINALIZADOR".$contador;
    fputs($this->arq,$line);
    fputs($this->arq,"\r\n");

    fclose($this->arq);

    $teste = "true";

    @db_query("drop table if exists work_plano");

    return $teste ;

  }


}

?>