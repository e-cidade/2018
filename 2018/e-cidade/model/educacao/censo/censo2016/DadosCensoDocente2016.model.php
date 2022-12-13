<?php
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

class DadosCensoDocente2016 extends DadosCensoDocente2015 {

  /**
   * Retorna os dados básicos do Recurso humano
   * REGISTRO 30
   * @throws Exception
   * @return stdClass
   */
  protected function getDados() {

    $oDaoRecursoHumano     = new cl_rechumano();
    $sCamposRecursoHumano  = "ed20_i_codigoinep as identificacao_unica_docente_inep, ";
    $sCamposRecursoHumano .= "ed20_i_codigo as codigo_docente_entidade_escola ,";
    $sCamposRecursoHumano .= "case ";
    $sCamposRecursoHumano .= "  when cgmrh.z01_nomecomple <> '' ";
    $sCamposRecursoHumano .= "       then cgmrh.z01_nomecomple ";
    $sCamposRecursoHumano .= "  when cgmcgm.z01_nomecomple <> '' ";
    $sCamposRecursoHumano .= "       then cgmcgm.z01_nomecomple ";
    $sCamposRecursoHumano .= "  when cgmrh.z01_nome <> '' ";
    $sCamposRecursoHumano .= "       then cgmrh.z01_nome ";
    $sCamposRecursoHumano .= "  else cgmcgm.z01_nome ";
    $sCamposRecursoHumano .= "   end as nome_completo, ";
    $sCamposRecursoHumano .= "case when cgmrh.z01_numcgm is not null then cgmrh.z01_numcgm else cgmcgm.z01_numcgm end as numcgm, ";
    $sCamposRecursoHumano .= "case when cgmrh.z01_email is not null then upper(cgmrh.z01_email) else upper(cgmcgm.z01_email) end as email, ";
    $sCamposRecursoHumano .= "case when cgmrh.z01_nasc is not null then cgmrh.z01_nasc else cgmcgm.z01_nasc end as data_nascimento, ";
    $sCamposRecursoHumano .= "case when cgmrh.z01_sexo is not null then cgmrh.z01_sexo else cgmcgm.z01_sexo end as sexo, ";
    $sCamposRecursoHumano .= "case when cgmrh.z01_mae is not null then cgmrh.z01_mae else cgmcgm.z01_mae end as filiacao_1, ";
    $sCamposRecursoHumano .= "case when cgmrh.z01_pai is not null then cgmrh.z01_pai else cgmcgm.z01_pai end as filiacao_2, ";
    $sCamposRecursoHumano .= "ed20_i_nacionalidade as nacionalidade_docente,";
    $sCamposRecursoHumano .= "ed228_i_paisonu as pais_origem,";
    $sCamposRecursoHumano .= "ed20_i_censoufnat as uf_nascimento,";
    $sCamposRecursoHumano .= "ed20_i_censomunicnat as municipio_nascimento,";
    $sCamposRecursoHumano .= "ed20_i_raca as cor_raca,";
    $sCamposRecursoHumano .= "'' as numero_identificacao_social_nis, ";
    $sCamposRecursoHumano .= "0 as docente_deficiencia, ";
    $sCamposRecursoHumano .= "case when regimerh.rh30_naturezaregime is not null then  regimerh.rh30_naturezaregime ";
    $sCamposRecursoHumano .= "     else regimecgm.rh30_naturezaregime end as regime_trabalho,";
    $sCamposRecursoHumano .= 'ed20_i_escolaridade as escolaridade';

    $sWhereRecursoHumano  = "     ed20_i_codigo = {$this->iCodigoDocente} ";
    $sWhereRecursoHumano .= " and (ed01_funcaoatividade in (2,3,4) or (ed01_funcaoatividade = 1 and ed01_c_regencia = 'S'))";

    $sSqlRecursoHumano = $oDaoRecursoHumano->sql_query_censo( null, $sCamposRecursoHumano, null, $sWhereRecursoHumano );
    $rsRecursoHumano   = db_query( $sSqlRecursoHumano );

    if( !$rsRecursoHumano ) {

      $oMensagem           = new stdClass();
      $oMensagem->iDocente = $this->iCodigoDocente;
      $oMensagem->sErro    = pg_last_error();
      throw new DBException( _M( MSG_DADOS_CENSO_DOCENTE . 'erro_buscar_dados_docente', $oMensagem ) );
    }

    if( pg_num_rows( $rsRecursoHumano ) == 0 ) {

      $oMensagem           = new stdClass();
      $oMensagem->iDocente = $this->iCodigoDocente;
      throw new DBException( _M( MSG_DADOS_CENSO_DOCENTE . 'recurso_humano_nao_encontrado', $oMensagem ) );
    }

    $oDadosRecursoHumano       = db_utils::fieldsMemory( $rsRecursoHumano, 0 );
    $this->iCodigoCgm          = $oDadosRecursoHumano->numcgm;
    $oDadosRecursoHumano->sexo = $oDadosRecursoHumano->sexo == 'F' ? 2 : 1;
    $this->iCodigoInep         = $oDadosRecursoHumano->identificacao_unica_docente_inep;
    $aNecessidadesEspeciais    = $this->getNecessidadesEspeciais();
    $iDocenteNecessidade       = '';
    $iRegimeTrabalho           = 1;

    if( count( $aNecessidadesEspeciais ) > 0 ) {

      $oDadosRecursoHumano->docente_deficiencia = 1;
      $iDocenteNecessidade                      = '0';
    }

    $oDadosRecursoHumano->data_nascimento                 = db_formatar($oDadosRecursoHumano->data_nascimento, "d");
    $oDadosRecursoHumano->tipos_deficiencia_cegueira      = isset($aNecessidadesEspeciais[101]) ? 1 : $iDocenteNecessidade;
    $oDadosRecursoHumano->tipos_deficiencia_baixa_visao   = isset($aNecessidadesEspeciais[102]) ? 1 : $iDocenteNecessidade;
    $oDadosRecursoHumano->tipos_deficiencia_surdez        = isset($aNecessidadesEspeciais[103]) ? 1 : $iDocenteNecessidade;
    $oDadosRecursoHumano->tipos_deficiencia_auditiva      = isset($aNecessidadesEspeciais[104]) ? 1 : $iDocenteNecessidade;
    $oDadosRecursoHumano->tipos_deficiencia_surdocegueira = isset($aNecessidadesEspeciais[105]) ? 1 : $iDocenteNecessidade;
    $oDadosRecursoHumano->tipos_deficiencia_fisica        = isset($aNecessidadesEspeciais[106]) ? 1 : $iDocenteNecessidade;
    $oDadosRecursoHumano->tipos_deficiencia_intelectual   = isset($aNecessidadesEspeciais[107]) ? 1 : $iDocenteNecessidade;
    $oDadosRecursoHumano->tipos_deficiencia_multipla      = isset($aNecessidadesEspeciais[108]) ? 1 : $iDocenteNecessidade;

    switch( $oDadosRecursoHumano->regime_trabalho ) {

      case 1 :

        $iRegimeTrabalho = 1;
        break;

      case 2 :

        $iRegimeTrabalho = 3;
        break;

      case 3:

        $iRegimeTrabalho = 2;
        break;

      case 4:

        $iRegimeTrabalho = 4;
        break;
    }

    $oDadosRecursoHumano->nome_completo = trim($this->removeCaracteres($oDadosRecursoHumano->nome_completo, 1 ));
    $oDadosRecursoHumano->filiacao      = 0;

    if ( !empty($oDadosRecursoHumano->filiacao_1) || !empty($oDadosRecursoHumano->filiacao_2) ) {
      $oDadosRecursoHumano->filiacao = 1;
    }

    if ( empty($oDadosRecursoHumano->filiacao_1) && !empty($oDadosRecursoHumano->filiacao_2) ) {

      $oDadosRecursoHumano->filiacao_1 = $oDadosRecursoHumano->filiacao_2;
      $oDadosRecursoHumano->filiacao_2 = '';
    }

    $oDadosRecursoHumano->filiacao_1      = $this->removeCaracteres($oDadosRecursoHumano->filiacao_1, 1);
    $oDadosRecursoHumano->filiacao_2      = $this->removeCaracteres($oDadosRecursoHumano->filiacao_2, 1);
    $oDadosRecursoHumano->regime_trabalho = $iRegimeTrabalho;
    $this->oDadosGerais                   = $oDadosRecursoHumano;

    return $this->oDadosGerais;
  }
}