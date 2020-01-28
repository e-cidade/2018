<?php

use Classes\PostgresMigration;

class M6622 extends PostgresMigration
{

public function up(){
    $sql = <<<'SQL'

    create or replace function fc_calculaprecomedio(integer, integer, float8, boolean)
  returns numeric as
$$

declare

  iCodigoMatestoqueiniMei        alias for $1;
  iCodigoMatestoqueini           alias for $2;
  nQuantidadeMovimento           alias for $3;
  lRecursivo                     alias for $4;

  nPrecoMedio                    numeric default 0;
  iMaterial                      integer;
  iInstituicao                   integer;
  iAlmoxarifado                  integer;
  nValorEstoque                  numeric;
  nQuantidadeEstoque             numeric default 0;
  nValorEstoqueDiferenca         numeric default 0;
  nQuantidadeEstoqueDiferenca    numeric default 0;
  iTipoMovimento                 integer;
  iCodigoEstoque                 integer;
  iCodigoMovimento               integer;
  iCodigoEntradaItem             integer;
  nValorUnitario                 numeric default 0;
  dtMovimento                    date;
  tHora                          timestamp;
  tHoraMovimento                 time;
  lTemPrecoMedio                 boolean default false;
  rValoresPosteriores            record;
  lServico                       boolean;
  iDepto                         integer;
  nQuantidadeSaidasPosteriores   numeric default 0;
  nQuantidadeEntradasPosteriores numeric default 0;
  nSaidasNoPeriodo               numeric default 0;
  nSaldoNoPeriodo                numeric default 0;
  nSaldoAposPeriodo              numeric default 0;
  sMensagemEstoque               varchar;
  lEntradaAposPeriodo            boolean default false;
  sSqlPrecoMedio                 varchar;
begin

  iInstituicao = fc_getsession('DB_instit');
  if iInstituicao is null then
    raise exception 'Instituicao não informada.';
  end if;

  /**
   * Consultamos o codigo do material,
   * atraves da tabela matestoqueitem, com o campo new.m82_matestoqueitem.
   */
  select m70_codmatmater,
    (case when  m71_quant > 0 then
      coalesce(m71_valor/m71_quant, 0)
     else 0 end),
    m71_servico,
    m70_coddepto,
    m71_codlanc
  into iMaterial,
    nValorUnitario,
    lServico,
    iAlmoxarifado,
    iCodigoEntradaItem
  from matestoqueitem
    inner join matestoque       on m70_codigo  = m71_codmatestoque
    inner join matestoqueinimei on m71_codlanc = m82_matestoqueitem
  where m82_codigo  = iCodigoMatestoqueiniMei;

  /**
   * Consultamos o tipo da movimentacao
   */
  select m80_codtipo,
    m81_tipo,
    to_timestamp(m80_data || ' ' || m80_hora, 'YYYY-MM-DD HH24:MI:SS'),
    m80_data,
    m80_hora,
    m80_coddepto,
    instit
  into iCodigoMovimento,
    iTipoMovimento,
    tHora,
    dtMovimento,
    tHoraMovimento,
    iDepto,
    iInstituicao
  from matestoqueini
    inner join matestoquetipo on m81_codtipo = m80_codtipo
    inner join DB_DEPART on m80_coddepto     = coddepto
  where m80_codigo = iCodigoMatestoqueini;

  /**
   * Soma a quantidade em estoque do item na instituicao
   *
   */
  select coalesce(sum(CASE when m81_tipo = 1 then round(m82_quant, 2) when m81_tipo = 2 then round(m82_quant,2)*-1 end), 0),
    round(coalesce(sum(CASE when m81_tipo = 1 then round(round(m82_quant, 2)*m89_valorunitario, 5)
                       when m81_tipo = 2 then round(m82_quant, 2)*round(case when m81_codtipo in (4, 19) then m89_valorunitario else m89_precomedio end, 5)*-1 end), 0) , 2)
  into nQuantidadeEstoque,
    nValorEstoque
  from matestoque
    inner join db_depart          on m70_coddepto       = coddepto
    inner join matestoqueitem     on m70_codigo         = m71_codmatestoque
    inner join matestoqueinimei   on m82_matestoqueitem = m71_codlanc
    inner join matestoqueinimeipm on m82_codigo         = m89_matestoqueinimei
    inner join matestoqueini      on m82_matestoqueini  = m80_codigo
    inner join matestoquetipo     on m81_codtipo        = m80_codtipo
  where instit           = iInstituicao
        and m70_codmatmater  = iMaterial
        and to_timestamp(m80_data || ' ' || m80_hora, 'YYYY-MM-DD HH24:MI:SS') <= tHora
        and m82_codigo <> iCodigoMatestoqueiniMei
        and m70_coddepto = iAlmoxarifado
        and m81_tipo not in(4,5)
        and m71_servico is false;

  /**
    * verificamos se o item possui no mesmo movimento entradas para o mesmo item de estoque
    */
  SELECT coalesce(sum(CASE when m81_tipo = 1 then round(m82_quant, 2)
                      when m81_tipo = 2 then round(m82_quant,2)*-1 end), 0) as saldodif,
    round(coalesce(sum(CASE when m81_tipo = 1 then round(round(round(m82_quant, 2)*m89_valorunitario, 5), 2)
                       when m81_tipo = 2 then round(round(m82_quant, 2)*round( case when m81_codtipo in (4, 19) then m89_valorunitario else m89_precomedio end, 5), 2)*-1 end), 0), 2)
  into nQuantidadeEstoqueDiferenca,
    nValorEstoqueDiferenca
  from matestoqueinimei
    inner join matestoqueitem     on m71_codlanc          = m82_matestoqueitem
    inner join matestoque         on m71_codmatestoque    = m70_codigo
    inner join matestoqueinimeipm on m89_matestoqueinimei = m82_codigo
    inner join matestoqueini      on m82_matestoqueini    = m80_codigo
    inner join matestoquetipo     on m80_codtipo          = m81_codtipo
  where m70_codmatmater   = iMaterial
        and m82_matestoqueini = iCodigoMatestoqueini
        and m82_codigo        > iCodigoMatestoqueiniMei
        and m70_coddepto = iAlmoxarifado
        and m81_tipo not in(4,5)
        and m71_servico is false;
  nQuantidadeEstoque := nQuantidadeEstoque - nQuantidadeEstoqueDiferenca;
  nValorEstoque      := nValorEstoque      - nValorEstoqueDiferenca;

  /**
   * Verificamos o ultimo preco medio da data do material para o item.
   */

  select round(m85_precomedio, 5)
  into nPrecoMedio
  from matmaterprecomedio
  where m85_matmater = iMaterial
        and m85_instit   = iInstituicao
        and m85_coddepto = iAlmoxarifado
        and to_timestamp(m85_data || ' ' || m85_hora, 'YYYY-MM-DD HH24:MI:SS') <= tHora
  order by to_timestamp(m85_data || ' ' || m85_hora, 'YYYY-MM-DD HH24:MI:SS') desc limit 1;

  if ( not found or nPrecoMedio = 0 ) and iCodigoMovimento in (8) then

    select round(m85_precomedio, 5)
    into nPrecoMedio
    from matmaterprecomedio
    where m85_matmater = iMaterial
          and m85_instit   = iInstituicao
          and m85_precomedio > 0
          and m85_coddepto = ( select m80_coddepto
                               from matestoqueini
                                 inner join matestoqueinil  inil  on inil.m86_matestoqueini   = matestoqueini.m80_codigo
                                 inner join matestoqueinill inill on inill.m87_matestoqueinil = inil.m86_codigo
                               where inill.m87_matestoqueini = iCodigoMatestoqueini limit 1)
          and to_timestamp(m85_data || ' ' || m85_hora, 'YYYY-MM-DD HH24:MI:SS') <= tHora
    order by to_timestamp(m85_data || ' ' || m85_hora, 'YYYY-MM-DD HH24:MI:SS') desc limit 1;

    update matmaterprecomedio
    set m85_precomedio = nPrecoMedio
    where m85_matmater = iMaterial
          and m85_instit   = iInstituicao
          and m85_coddepto = iAlmoxarifado
          and to_timestamp(m85_data || ' ' || m85_hora, 'YYYY-MM-DD HH24:MI:SS') <= tHora;

  end if;

  if nQuantidadeEstoque = 0 then
    nValorEstoque := 0;
  end if;
  if  found then
    lTemPrecoMedio = true;
  end if;
  nPrecoMedio := coalesce(nPrecoMedio, 0);
  /**
   * Verificamos as entradas no estoque (refletem no calculo do preço medio)
   * algumas entradas, que na verdade são cancelamentos de saidas, devem entrar no estoque
   * pelo preco médio atual, não alterando o preço do calculo médio.
   */
  if iCodigoMovimento in(8, 1, 3, 12, 14, 15, 25) then

    /**
     * como o sistema já inclui as informações do estoque na hora de verificarmos o preço médio,
     * devemos deduzir a quantidade da entrada, (nQuantidade - m82_quant). a regra do calculo do preço médio é:
     * pegamos a quantidade anterior em estoque, e multiplicamos pelo ultimo preço médio.
     * - Somamos a nova entrada (quantidade e valor da entrada,) e dividimos o valor encontrado pela quantidade
     * encontrada. o resultado dessa divisão, encontramos o preço médio.
     */
    --nValorEstoque      = round(nQuantidadeEstoque * nPrecoMedio, 2);
    nQuantidadeEstoque = nQuantidadeEstoque  + nQuantidadeMovimento;
    nValorEstoque      = round(nValorEstoque + (nQuantidadeMovimento*nValorUnitario), 2);
    nPrecoMedio        = 0;
    if nQuantidadeEstoque > 0 then
      nPrecoMedio    = round( nValorEstoque / nQuantidadeEstoque, 5);
    end if;
    /**
     * Excluimos o preço medio para o movimento/hora
     */
    delete from matmaterprecomedio
    where m85_matmater = imaterial
          and m85_instit   = iInstituicao
          and m85_coddepto = iAlmoxarifado
          and to_timestamp(m85_data || ' ' || m85_hora, 'YYYY-MM-DD HH24:MI:SS') >= tHora;

    insert into matmaterprecomedio
    (m85_sequencial,
     m85_matmater,
     m85_instit,
     m85_precomedio,
     m85_data,
     m85_hora,
     m85_coddepto
    )
    values (nextval('matmaterprecomedio_m85_sequencial_seq'),
            iMaterial,
            iInstituicao,
            round(nPrecoMedio, 5),
            dtMovimento,
            tHoraMovimento,
            iAlmoxarifado
    );

  elsif iCodigoMovimento in (4, 19) then


    select round( (m71_valor/m71_quant) , 2)
    into nValorUnitario
    from matestoqueinill
      inner join matestoqueinil on m86_codigo = m87_matestoqueinil
      inner join matestoqueini on m86_matestoqueini = m80_codigo
      inner join matestoqueinimei on m80_codigo = m82_matestoqueini
      inner join matestoqueitem on m82_matestoqueitem = m71_codlanc
    where m87_matestoqueini = iCodigoMatestoqueini;


  elsif iTipoMovimento = 2 and iCodigoMovimento not in(8, 9) then

    nValorUnitario = round(nPrecoMedio, 5);

  elsif iCodigoMovimento in(7, 6, 18, 9) then

    nValorUnitario = round(nPrecoMedio, 5);

  elsif iCodigoMovimento in (21) then

    /**
     * caso  a transferencia seja confirmada,
     * temos que fazer a entrada no estoque ao mesmo valor da saida, pois a movimentacao no estoque
     * nao existe a movimentacao de valores.
     * o codigo da transferencia está na tabela mastoqueinil/matestoqueinill
     */
    select round(m89_precomedio, 5)
    into nPrecoMedio
    from matestoqueinill
      inner join matestoqueinil     on m87_matestoqueinil = m86_codigo
      inner join matestoqueinimei   on m86_matestoqueini  = m82_matestoqueini
      inner join matestoqueinimeipm on m82_codigo         = m89_matestoqueinimei
      inner join matestoqueitem     on m82_matestoqueitem = m71_codlanc
      inner join matestoque         on m70_codigo         = m71_codmatestoque
    where m70_codmatmater   = iMaterial
          and m87_matestoqueini = iCodigoMatestoqueini
          and m71_servico is false;

    nValorUnitario = round(nPrecoMedio, 5);
  end if;

  delete from matestoqueinimeipm where m89_matestoqueinimei = iCodigoMatestoqueiniMei;
  insert into matestoqueinimeipm
  (m89_sequencial,
   m89_matestoqueinimei,
   m89_precomedio,
   m89_valorunitario,
   m89_valorfinanceiro
  )
  values (nextval('matestoqueinimeipm_m89_sequencial_seq'),
          iCodigoMatestoqueiniMei,
          round(nPrecoMedio, 5),
          round(nValorUnitario, 5),
          round(nQuantidadeMovimento * round(nValorUnitario, 5), 2)
  );
  return round(nPrecoMedio, 5);
end;
$$
language 'plpgsql';    

SQL;
  
    $this->execute($sql);
  }

public function down(){}

}
