<?php
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


require_once ('model/tceEstruturaBasica.php');

class tceCadastroFuncionarios extends tceEstruturaBasica {

  const NOME_ARQUIVO = 'TCE_4820.TXT';

  const CODIGO_ARQUIVO = 35;
  
  public $iInstit = "";
  public $sInstituicoes = "";
  public $sDataIni = "";
  public $sDataFim = "";
  public $sCodRemessa = "";
  
  private $oLeiaute = null;
  
  /**
   * 
   */
  function __construct($iInstit,$sCodRemessa,$sDataIni,$sDataFim,$oData,$oLeiaute = null, $sInstituicoes) {

    try {
      parent::__construct( self::CODIGO_ARQUIVO, self::NOME_ARQUIVO );
    } catch ( Exception $e ) {
      throw $e->getMessage();
    }

    $this->iInstit     = $iInstit;
    $this->sInstituicoes = $sInstituicoes;
    $this->sDataIni    = $sDataIni;
    $this->sDataFim    = $sDataFim;
    $this->sCodRemessa = $sCodRemessa;
    if ($oLeiaute != null) {
      $this->oLeiaute =$oLeiaute;
    }
    
  
  }

  function getNomeArquivo(){
    return self::NOME_ARQUIVO;
  }
  
  
  function geraArquivo() {

    db_criatermometro('terTCE4820', 'Arquivo TCE4820...', 'blue', 1);

    $this->oTxtLayout->setByLineOfDBUtils( $this->cabecalhoPadrao( $this->iInstit, $this->sDataIni, $this->sDataFim, $this->sCodRemessa ), 1 );
    $rsFuncionarios = db_query( $this->sqlCadastroFuncionarios( $this->sInstituicoes, $this->sDataFim , $this->sDataIni ) );
    $iNumRows = pg_num_rows( $rsFuncionarios );
    $iTotalRegistros = 0;
    
    // db_criatabela($rsFuncionarios);
    
    for($i = 0; $i < $iNumRows; $i ++) {
      
      db_atutermometro($i, $iNumRows, "terTCE4820");
      
      $oFuncionario = db_utils::fieldsMemory( $rsFuncionarios, $i );
      if ($oFuncionario->regimejuridico == 'O') {
        $oFuncionario->observacoes = 'Extra Quadro';
      }
      $this->oTxtLayout->setByLineOfDBUtils( $oFuncionario, 3 );
      $iTotalRegistros ++;
    
    }
    
    $this->oTxtLayout->setByLineOfDBUtils( $this->rodapePadrao( $iTotalRegistros ), 5 );
    unset( $rsFuncionarios );
  
  }

  function sqlCadastroFuncionarios($iInstit, $sDatafim, $sDataini) {

    list ( $iAnoUsuFim, $iMesUsuFim, $iDiaUsuFim ) = explode( "-", $sDatafim);
    list ( $iAnoUsuIni, $iMesUsuIni, $iDiaUsuIni ) = explode( "-", $sDataini);
    
    $sSqlFuncionarios = " select (cast(rh02_anousu::varchar||'-'||rh02_mesusu::varchar||'-01'::varchar as date))  as dataatualizacao, ";
    $sSqlFuncionarios .= "        rh01_regist                   as codigoregistrofuncionario, ";
    $sSqlFuncionarios .= "        z01_cgccpf                    as cpf, ";
    $sSqlFuncionarios .= "        z01_nome                      as nomefuncionario, ";
    $sSqlFuncionarios .= "        rh01_nasc                     as datanacimento, ";
    $sSqlFuncionarios .= "        rh01_admiss                   as dataadmissao, ";
    $sSqlFuncionarios .= "        coalesce(to_char(rh05_recis, 'DDMMYYYY')) as datademissao, ";
    $sSqlFuncionarios .= "        rh01_funcao                   as codigocargofuncionario, ";
    $sSqlFuncionarios .= "        rh37_descr                    as nomecargofuncionario, ";
    $sSqlFuncionarios .= "        rh02_lota                     as codigosetor, ";
    $sSqlFuncionarios .= "        r70_descr                     as nomesetor, ";
    $sSqlFuncionarios .= "        case  ";
    $sSqlFuncionarios .= "          when upper(rh01_sexo) = 'M' ";
    $sSqlFuncionarios .= "            then 1 ";
    $sSqlFuncionarios .= "          else 2 ";
    $sSqlFuncionarios .= "        end                           as sexofuncionario, ";
    $sSqlFuncionarios .= "        cast( cast( replace(trim( substr(db_fxxx(rh01_regist,rh02_anousu,rh02_mesusu,rh02_instit),46,11)),',','.') as numeric) as integer) as quantidadedependentesfinsirrf, ";
    $sSqlFuncionarios .= "        case  ";
    $sSqlFuncionarios .= "          when upper(rh30_vinculo) = 'A' then '01' ";
    $sSqlFuncionarios .= "          when upper(rh30_vinculo) = 'I' then '02' ";
    $sSqlFuncionarios .= "          when upper(rh30_vinculo) = 'P' then '03' ";
    $sSqlFuncionarios .= "          else '99' ";
    $sSqlFuncionarios .= "        end as situacaofuncionario, ";
    $sSqlFuncionarios .= "        case ";
    $sSqlFuncionarios .= "          when rh30_regime = 1 then 'E' ";
    $sSqlFuncionarios .= "          when rh30_regime = 2 then 'C' ";
    $sSqlFuncionarios .= "          else 'O' ";
    $sSqlFuncionarios .= "        end as regimejuridico, ";
    $sSqlFuncionarios .= "        case ";
    $sSqlFuncionarios .= "          when rh71_sequencial = 1 then 'E' ";
    $sSqlFuncionarios .= "          when rh71_sequencial = 2 then 'C' ";
    $sSqlFuncionarios .= "          when rh71_sequencial = 3 then 'T' ";
    $sSqlFuncionarios .= "          else 'O' ";
    $sSqlFuncionarios .= "        end as naturezacargo, ";
    $sSqlFuncionarios .= "        case ";
    $sSqlFuncionarios .= "          when cfpess.r11_tbprev = rh02_tbprev ";
    $sSqlFuncionarios .= "            then '02' ";
    $sSqlFuncionarios .= "          else case when rh02_tbprev <> 0 ";
    $sSqlFuncionarios .= "                 then '01' ";
    $sSqlFuncionarios .= "               else '99'";
    $sSqlFuncionarios .= "               end ";
    $sSqlFuncionarios .= "        end                           as regimeprevidenciario, ";
    $sSqlFuncionarios .= "        z01_ident                     as registrogeralindentificacao, ";
    $sSqlFuncionarios .= "        rh37_cbo                      as cbo, ";
    $sSqlFuncionarios .= "        rh16_pis                      as nitpispasep, ";
    $sSqlFuncionarios .= "        rh02_tpcont                   as categoriatrabalhador, ";
    $sSqlFuncionarios .= "        trim(z01_ender)               as endereco, ";
    $sSqlFuncionarios .= "        z01_munic                     as cidade, ";
    $sSqlFuncionarios .= "        z01_uf                        as unidadeferderacaouf, ";
    $sSqlFuncionarios .= "        z01_cep                       as cep, ";
    $sSqlFuncionarios .= "        case when rh02_tbprev = 0 then 'Servidor sem previdencia.' else '' end as observacoes ";
    $sSqlFuncionarios .= "   from rhpessoal ";
    $sSqlFuncionarios .= "        inner join cgm              on cgm.z01_numcgm                   = rhpessoal.rh01_numcgm ";
    $sSqlFuncionarios .= "        inner join rhpessoalmov     on rhpessoalmov.rh02_regist         = rhpessoal.rh01_regist ";
    $sSqlFuncionarios .= "                                   and rhpessoalmov.rh02_anousu         = {$iAnoUsuFim} ";
    $sSqlFuncionarios .= "                                   and rhpessoalmov.rh02_mesusu         = {$iMesUsuFim} ";
    $sSqlFuncionarios .= "                                   and rhpessoalmov.rh02_instit         in ({$this->sInstituicoes}) ";
    $sSqlFuncionarios .= "        inner join rhfuncao         on rhfuncao.rh37_funcao             = rhpessoalmov.rh02_funcao ";
    $sSqlFuncionarios .= "                                   and rhfuncao.rh37_instit             = rhpessoalmov.rh02_instit ";
    $sSqlFuncionarios .= "        left  join rhpesdoc         on rhpesdoc.rh16_regist             = rhpessoal.rh01_regist ";
    $sSqlFuncionarios .= "        left  join cfpess           on cfpess.r11_anousu                = rhpessoalmov.rh02_anousu ";
    $sSqlFuncionarios .= "                                   and cfpess.r11_mesusu                = rhpessoalmov.rh02_mesusu ";
    $sSqlFuncionarios .= "                                   and cfpess.r11_tbprev                = rhpessoalmov.rh02_tbprev ";
    $sSqlFuncionarios .= "                                   and cfpess.r11_instit                = rhpessoalmov.rh02_instit ";
    $sSqlFuncionarios .= "        inner join rhlota           on rhlota.r70_codigo                = rhpessoalmov.rh02_lota ";
    $sSqlFuncionarios .= "                                   and rhlota.r70_instit                = rh02_instit ";
    $sSqlFuncionarios .= "        inner join rhregime         on rhregime.rh30_codreg             = rhpessoalmov.rh02_codreg ";
    $sSqlFuncionarios .= "                                   and rhregime.rh30_instit             = rh02_instit ";
    $sSqlFuncionarios .= "        left  join rhnaturezaregime on rhnaturezaregime.rh71_sequencial = rhregime.rh30_naturezaregime ";
    $sSqlFuncionarios .= "        left  join rhpesrescisao    on rhpesrescisao.rh05_seqpes        = rhpessoalmov.rh02_seqpes ";
    $sSqlFuncionarios .= "   where rh02_instit in ({$this->sInstituicoes}) ";
    $sSqlFuncionarios .= "   and ( rhpesrescisao.rh05_recis is null
                        or rhpesrescisao.rh05_recis >= '{$iAnoUsuIni}-{$iMesUsuIni}-{$iDiaUsuIni}'
                        or rh02_regist in (select distinct r20_regist from gerfres where (r20_anousu >= {$iAnoUsuIni} 
                                             and r20_mesusu >= {$iMesUsuIni} ) and (r20_anousu <= {$iAnoUsuFim} and r20_mesusu <= {$iMesUsuFim} ) 
                                          )
                         ) order by rh01_regist ";

    //$sSqlFuncionarios .= "   where (rh05_seqpes is null or (rh05_recis between '{$this->sDataIni}'and '{$this->sDataFim}'))";

    
//    die($sSqlFuncionarios);
    
    return $sSqlFuncionarios;
  
  }

}

?>