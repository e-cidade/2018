<?php
namespace ECidade\Educacao\Escola\Censo\SituacaoAluno\Dados;


class BuscaDadosEscola2016 {

  private $oDados = null;
  function __construct( $oCenso, $oEscola) {

    $sCampos  = "distinct escolagestorcenso.ed325_email as email_gestor ";
    $sCampos .= ",escola.ed18_c_codigoinep as codigo_escola_inep               ";
    $sCampos .= ",case                                  ";
    $sCampos .= "   when  cgmrh.z01_cgccpf is not null  ";
    $sCampos .= "     then cgmrh.z01_cgccpf             ";
    $sCampos .= "   else cgmcgm.z01_cgccpf              ";
    $sCampos .= " end as cpf_gestor            ";
    $sCampos .= ",case                                  ";
    $sCampos .= "   when cgmrh.z01_nome is not null     ";
    $sCampos .= "     then cgmrh.z01_nome               ";
    $sCampos .= "   else cgmcgm.z01_nome                ";
    $sCampos .= " end as nome_gestor         ";
    $sCampos .= ",case when trim(atividaderh.ed01_c_descr) = 'DIRETOR' then 1 else 2 end as cargo_gestor";
    $sCampos .= ",'' as separador_final ";
    $sWhere   = "escola.ed18_i_codigo = {$oEscola->getCodigo()} ";

    $oDaoEscolaGestorCenso = new \cl_escolagestorcenso();
    $sSqlEscolaGestorCenso = $oDaoEscolaGestorCenso->sql_query_dados_gestor(null, $sCampos, null, $sWhere);
    $rsEscolaGestorCenso   = db_query($sSqlEscolaGestorCenso);

    if ( !$rsEscolaGestorCenso ) {
      throw new \DBException("Erro ao buscar os dados da Escola.");
    }

    if ( pg_num_rows( $rsEscolaGestorCenso ) == 0 ) {
      throw new \BusinessException("Dados do gestor da escola não cadastrados. Acesse: Cadastros -> Dados da Escola -> aba Gestor.");
    }

    $this->oDados = \db_utils::fieldsMemory($rsEscolaGestorCenso, 0);
  }

  public function getDados() {

    $oValidacaoEscola = new DadosEscola2016();
    $oValidacaoEscola->popular( $this->oDados );

    return $oValidacaoEscola;
  }
}
