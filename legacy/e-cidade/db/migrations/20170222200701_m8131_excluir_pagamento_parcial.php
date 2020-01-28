<?php
use Classes\PostgresMigration;

/**
 * Class M8131ExcluirPagamentoParcial
 */
class M8131ExcluirPagamentoParcial extends PostgresMigration
{

  const PERCENTUAL_DESCONTO = 15;

  const NOME_TABELA = 'bkp_iptu_8131';

  public function up() {

    $this->criarTabelaCasosErrados();
    $this->gerarRecibos();
  }

  /**
   * Gera os recibos que não tiveram valor pago até o momento
   */
  private function gerarRecibos() {

    $casosErrados = $this->fetchAll("select * from ".self::NOME_TABELA." where abatimento is null;");

    $sDataPagamento = null;
    foreach ($casosErrados as $recibo) {

      $recibo = (object)$recibo;

      if (empty($sDataPagamento)) {
        $dataVencimento = (object)$this->fetchRow("select k00_dtvenc from recibounica where k00_numpre = {$recibo->numpre} and k00_percdes = ".self::PERCENTUAL_DESCONTO);
        $sDataPagamento = $dataVencimento->k00_dtvenc;
      }

      $this->execute("
        select fc_putsession('db_instit', '{$recibo->instituicao}');
        select fc_putsession('db_anousu', '2017');
      ");

      if (!empty($sDataPagamento)) {

        $sql = <<<STRING
          delete from recibopaga where k00_numnov = {$recibo->numnov};
          select fc_recibo({$recibo->numnov}, '{$sDataPagamento}', '{$sDataPagamento}', 2017);
STRING;

        $this->execute($sql);
      }
    }
  }


  /**
   * Busca todos os recibos que não foram pagos ainda e estão com valor errado
   */
  private function criarTabelaCasosErrados() {

    $this->execute(
      <<<STRING
      drop table if exists bkp_iptu_8131;
create table bkp_iptu_8131 as
    select k127_abatimento as abatimento,
           numpre,
           numnov,
           dtpaga,
           valor_recibo,
           valor_barras,
           codbar,
           instituicao
      from (select a.k00_numpre as numpre,
                   a.k00_numnov as numnov,
                   a.k00_dtpaga as dtpaga,
                   round(sum(a.k00_valor),2) as valor_recibo,
                   round(substr(k00_codbar,5,11)::numeric/100,2) valor_barras,
                   b.k00_codbar as codbar ,
                   k00_instit as instituicao
              from recibopaga a
                   inner join recibocodbar b on a.k00_numnov = b.k00_numpre
                   inner join iptunump  on j20_numpre = a.k00_numpre
                                       and j20_anousu = 2017
                   inner join arreinstit on arreinstit.k00_numpre = a.k00_numpre
             where a.k00_dtoper >= '2017-01-01'
             group by a.k00_numpre,a.k00_numnov, a.k00_dtpaga,b.k00_codbar, arreinstit.k00_instit
             order by a.k00_numpre,a.k00_numnov) as y
   left join abatimentorecibo on numnov = k127_numpreoriginal
   where valor_recibo > valor_barras
     and exists (select 1 from arrecad where k00_numpre = numpre);

STRING
    );
  }

  public function down() {
    /* sem rollback */
  }
}
