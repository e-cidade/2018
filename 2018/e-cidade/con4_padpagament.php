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


class pagament {
  var $arq=null;

  function pagament($header){
    umask(74);
    $this->arq = fopen("tmp/PAGAMENT.TXT",'w+');
    fputs($this->arq,$header);
    fputs($this->arq,"\r\n");
  }

  function processa($instit=1,$data_ini="",$data_fim="",$tribinst="",$subelemento="") {

    global $contador,$nomeinst;
    $contador = 0;

    $where = " and e60_instit in ($instit)";
    $sql = "
      select e60_anousu                as ano,
	           trim(e60_codemp)::integer as codemp,
	           c75_codlan,
	           c75_data,
	           round(c70_valor,2)        as c70_valor,
	           case when c53_tipo =30 then
 	              '+'  else '-'
 	           end as sinal,
 	           case when c72_complem is null
 	             then null
 	             else c72_complem end || ' Ordem Pagto:' || c80_codord
 	           as historico,
 	           c75_codlan  as operacao,
 	           c82_reduz   as conta_pagadora,
 	           case when c69_debito = c82_reduz
 	             then  c69_credito
 	             else  c69_debito
 	           end as contra_partida,
 	           db_config.codtrib as orgao_unidade,
 	           e60_instit,
 	           c80_codord
        from conlancamemp
             inner join conlancam    on c70_codlan       = c75_codlan
             inner join conlancampag on c82_codlan       = c70_codlan
             inner join conlancamval on c69_codlan       = c70_codlan
                                    and  ( c69_debito    =  c82_reduz or c69_credito = c82_reduz)
             inner join empempenho   on e60_numemp       = c75_numemp
             inner join db_config    on db_config.codigo = empempenho.e60_instit
             inner join conlancamdoc on c71_codlan       = c75_codlan
             inner join conhistdoc   on c53_coddoc       = c71_coddoc
                                    and (c53_tipo = 30 or c53_tipo = 31)
             left  join conlancamord   on c80_codlan = c70_codlan
             left  join conlancamcompl on c72_codlan = c70_codlan
       where c75_data between '{$data_ini}' and '{$data_fim}'
         and e60_emiss <= '{$data_fim}' {$where}

       union all

      select e60_anousu as ano,
             trim(e60_codemp)::integer as codemp,
             c75_codlan,
             c75_data,
             round(c70_valor,2) as c70_valor,
             case when c53_tipo = 30 then
                '+'  else '-'
             end as sinal,
             case when c72_complem is null then null else c72_complem end || ' Ordem Pagto:' || c80_codord as historico,
             c75_codlan as operacao,
             c82_reduz as conta_pagadora,
             case when c69_debito = c82_reduz then
               c69_credito  else c69_debito
             end as contra_partida,
             db_config.codtrib as orgao_unidade,
             e60_instit,
             c80_codord
        from empresto
        	   inner join conlancamemp    on c75_numemp = e91_numemp
      	     inner join conlancam       on c70_codlan = c75_codlan
      	     inner join conlancampag    on c82_codlan = c70_codlan
             inner join conlancamval    on c69_codlan = c70_codlan
                                       and  ( c69_debito   =  c82_reduz or c69_credito = c82_reduz)
      	     inner join empempenho      on e60_numemp = c75_numemp
      	     inner join db_config       on db_config.codigo = empempenho.e60_instit
             inner join conlancamdoc    on c71_codlan = c75_codlan
      	     inner join conhistdoc      on c53_coddoc = c71_coddoc
      	                               and (c53_tipo = 30 or c53_tipo = 31)
      	     left  join conlancamord   on c80_codlan = c70_codlan
      	     left  join conlancamcompl on c72_codlan = c70_codlan
       where e91_anousu = ".db_getsession("DB_anousu")."
         and e91_rpcorreto is true
         and c75_data between '{$data_ini}' and '{$data_fim}'
         and e60_emiss <= '{$data_fim}' {$where}
       order by ano,codemp ";

    $res  = db_query($sql);
    $rows = pg_numrows($res);
    for ($x = 0; $x < $rows; $x++) {

      $oStdPagamento = db_utils::fieldsMemory($res, $x);

      $ano         = $oStdPagamento->ano;
      $instituicao = $oStdPagamento->e60_instit;
      $empenho     = $ano.str_pad($instituicao, 2, "0", STR_PAD_LEFT)."0".formatar($oStdPagamento->codemp, 6, 'n');
      $lancamento  = formatar($oStdPagamento->c75_codlan, 20, 'n');
      $data        = formatar($oStdPagamento->c75_data, 8, 'd');
      $valor       = formatar($oStdPagamento->c70_valor, 13, 'v');
      $sinal       = $oStdPagamento->sinal;
      $sObsoleto   = str_pad(" ", 120, " ", STR_PAD_RIGHT);
      $historico   = $oStdPagamento->historico;

      $liquidacao = "";
      if (!empty($oStdPagamento->c80_codord)) {
        $sSqlBuscaLiquidacao = "
          select c80_codlan
            from conlancamord
                 inner join conlancamdoc on c71_codlan = c80_codlan
                 inner join conhistdoc on c53_coddoc = c71_coddoc
           where c53_tipo = 20
             and c80_codord = {$oStdPagamento->c80_codord} limit 1;
        ";
        $rsBuscaCodigoLiquidacao = db_query($sSqlBuscaLiquidacao);
        if (!$rsBuscaCodigoLiquidacao) {
          die("Ocorreu um erro ao buscar o código do lançamento de liquidação.");
        }
        $liquidacao = db_utils::fieldsMemory($rsBuscaCodigoLiquidacao, 0)->c80_codlan;
      }

      $liquidacao = formatar($liquidacao, 20, 'n');;

      $historico = trim($historico);
      if (empty($historico)) {
        $historico = "Lançamento de Pagamento Número: {$lancamento}";
      }

      $historico = addcslashes($historico,"\r\n");
      $historico = formatar($historico, 400, 'c');
      $operacao  = formatar($oStdPagamento->operacao, 30, 'c');

      // estrutural da conta a debito
      $sql = "select c60_estrut
                from conplanoreduz
                     inner join conplano on c60_codcon = c61_codcon and c60_anousu = c61_anousu
               where c61_reduz = {$oStdPagamento->conta_pagadora}";
      $resdeb = db_query($sql);

      // caso nao exista coloque o reduzido
      $conta_pagadora = formatar($oStdPagamento->conta_pagadora, 20, 'n');
      if (pg_num_rows($resdeb) > 0) {

        $oStdConta = db_utils::fieldsMemory($resdeb, 0);
        $conta_pagadora = formatar($oStdConta->c60_estrut, 20, 'n');
      }

      // estrutural da conta a credito
      $sql = "select c60_estrut
                from conplanoreduz
                  inner join   conplano on
                                       c60_codcon = c61_codcon
                                   and c60_anousu = c61_anousu
                where c61_reduz = {$oStdPagamento->contra_partida}";
      $resdeb = db_query($sql);

      // caso nao exista coloque o reduzido
      $contra_partida = formatar($oStdPagamento->contra_partida,20,'n');
      if (pg_num_rows($resdeb) > 0) {

        $oStdEstrutural = db_utils::fieldsMemory($resdeb, 0);
        $contra_partida = formatar($oStdEstrutural->c60_estrut,20,'n');
      }

      $orgao_unidade      = formatar($oStdPagamento->orgao_unidade,4,'n');

      $conta_debito  = $conta_pagadora;
      $conta_credito = $contra_partida;
      if ($sinal == "+") {

        $conta_debito  = $contra_partida;
        $conta_credito = $conta_pagadora;
      }

      $line = $empenho.$lancamento.$data.$valor.$sinal.$sObsoleto.$operacao.$conta_debito.$orgao_unidade.$conta_credito.$orgao_unidade.$historico.$liquidacao;
      fputs($this->arq,$line);
      fputs($this->arq,"\r\n");
      $contador = $contador+1; // incrementa contador global
    }

    //  trailer
    $contador = espaco(10-(strlen($contador)),'0').$contador;
    $line = "FINALIZADOR".$contador;
    fputs($this->arq,$line);
    fputs($this->arq,"\r\n");

    fclose($this->arq);
    $teste = "true";
    return $teste;
  }
}
