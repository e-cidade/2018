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

require_once ("interfaces/iPadArquivoTxtBase.interface.php");
require_once ("model/contabilidade/arquivos/sigfis/SigfisArquivoBase.model.php");

/**
 * 
 * Classe Responsável pela geração dos dados necessários para o arquivo Plano de Contas
 * @author Andrio Costa
 *
 */
class SigfisArquivoPlanoConta extends SigfisArquivoBase implements iPadArquivoTXTBase {

  protected $iCodigoLayout     = 204;
  protected $sNomeArquivo      = 'ContaCont';
  protected $sIndicadorEmpresa = 'N';
  
  /**
   * 
   * Busca os dados para gerar o Arquivo de Plano de Conta
   * 
   */
  public function gerarDados() {
    
    $oDbConfig    = new db_stdClass();
    $clConPlano   = db_utils::getDao('conplano');
    $oDadoConfig  = $oDbConfig->getDadosInstit();
    
    if ($oDadoConfig->db21_tipoinstit == 9 || $oDadoConfig->db21_tipoinstit == 10) {
      $this->sIndicadorEmpresa = 'S';
    } 

	  $this->setCodigoLayout(204);
    if( $iAnoSessao < 2013 ){
  	  $this->setCodigoLayout(109);
    }
    
    $sCampos = " conplano.c60_anousu, 				                                                                        ";
    $sCampos.= " conplano.c60_estrut,                                                                                 ";
    $sCampos.= " conplano.c60_codcon,                                                                                 "; 
    $sCampos.= " conplano.c60_codsis,                                                                                 ";
    $sCampos.= " conplano.c60_descr,                                                                                  ";
    $sCampos.= " conplanoreduz.c61_codigo,                                                                            ";
    $sCampos.= " coalesce(conplanoreduz.c61_reduz,0) as c61_reduz,                                                    ";
    $sCampos.= " conplanoreduz.c61_instit,                                                                            ";
    $sCampos.= " fc_nivel_plano2005(conplano.c60_estrut) as nivel,  																						      ";
    $sCampos.= " case when conplanoreduz.c61_reduz is not null then 1 else 2 end as recebe_lancamento,  				      ";
    $sCampos.= " case when conplano.c60_codsis = 6 then 1                                                             ";
    $sCampos.= "      when orcelemento.o56_codele is not null then 2                                                  ";
    $sCampos.= "      when orcfontes.o57_codfon is not null then 3                                                    ";
    $sCampos.= "      else 9 end as tipo_conta, 											                                                ";
    $sCampos.= " case when c63_banco   is null then '0' else c63_banco   end as banco,                                  ";
    $sCampos.= " case when c63_agencia is null then '' else c63_agencia end as agencia,                                ";
    $sCampos.= " case when c63_conta   is null then '' else c63_conta   end as conta                                   ";
    
    $sSqlConPlano = $clConPlano->sql_query_planocontas($this->iAnoUso, $sCampos, 'conplano.c60_estrut');
    //die( $sSqlConPlano );
    $rsConPlano   = $clConPlano->sql_record($sSqlConPlano);
    
    /*
     * Variáveis de contrele da Classe;
     */
    $ReservadoTCE = " ";
    $iMes         = substr($this->dtDataFinal, 5, 2);
    $this->addLog("=====Arquivo".$this->getNomeArquivo()." Erros:\n");
    if ($clConPlano->numrows > 0) {
      
      if (empty($this->sCodigoTribunal)) {
        throw new Exception("O código do tribunal deve ser informado para geração do arquivo");
      }
      
      for($i = 0; $i < $clConPlano->numrows; $i++) {
        
        $oDados      = new stdClass();
        $oDadosPlano = db_utils::fieldsMemory($rsConPlano, $i);

        if ( $oDadosPlano->c61_reduz > 0 and $oDadosPlano->c61_instit <> $oDadoConfig->codigo ) {
          continue;
        }
        
        $iCodigoContaTCE = '';
        $sNaturezaSaldo  = 'M';
        if ($oVinculo = SigfisVinculoConta::getVinculoConta($oDadosPlano->c60_codcon)) {
          
          $iCodigoContaTCE = $oVinculo->contatce;
          $sNaturezaSaldo  = $oVinculo->naturezasaldo;

        } else {
          
          $sErroLog  = "Conta {$oDadosPlano->c60_codcon} - {$oDadosPlano->c60_estrut} - {$oDadosPlano->c60_descr} ";
          $sErroLog .= "sem Vinculo com plano do SIGFIS\n";
          $this->addLog($sErroLog);
        }
        $iCodigoRecursoTCE = '';
        if ($oDadosPlano->recebe_lancamento == 1) {
          
          if ($oRecursoTCE = SigfisVinculoRecurso::getVinculoRecurso($oDadosPlano->c61_codigo)) {
            $iCodigoRecursoTCE = $oRecursoTCE->recursotce;
          } else {
  
           $sErroLog  = "Conta {$oDadosPlano->c60_codcon} - {$oDadosPlano->c60_estrut} - {$oDadosPlano->c60_descr} ";
           $sErroLog .= "possui recurso de código {$oDadosPlano->c61_codigo} sem Vinculo com os recursos do SIGFIS.\n";
           $this->addLog($sErroLog);

          }

        }
        
        //// nÃo listar contar que nÃo possuam vinculo com o SIGFIS
        if(trim($iCodigoContaTCE) ==''){
          continue;
        }

        $oDados->dt_AnoCriacao          = $oDadosPlano->c60_anousu;
        $oDados->tp_OrigemSaldo         = $sNaturezaSaldo; // 'M'; // Vem do XML;
        $oDados->cd_RecebeLanc          = $oDadosPlano->recebe_lancamento;
        $oDados->ST_EMPRESA             = $this->sIndicadorEmpresa;
        $oDados->dt_AnoMes              = $oDadosPlano->c60_anousu.$iMes;
        $oDados->nu_SequencialTC        = str_pad($iCodigoContaTCE,          4, ' ', STR_PAD_LEFT); // Vem do XML 
        $oDados->cd_Unidade             = str_pad($this->sCodigoTribunal,    4, ' ', STR_PAD_LEFT);
        $oDados->cd_ContaContabil       = str_pad($oDadosPlano->c60_estrut, 34, ' ', STR_PAD_RIGHT);
        $oDados->tp_ContaContabil       = str_pad($oDadosPlano->tipo_conta,  1, ' ', STR_PAD_LEFT);
        $oDados->nm_ContaContabil       = str_pad($oDadosPlano->c60_descr,  50, " ", STR_PAD_RIGHT);
        $oDados->nu_Nivel               = str_pad($oDadosPlano->nivel,       4, " ", STR_PAD_LEFT);
        $oDados->cd_Banco               = str_pad(substr($oDadosPlano->banco,0,4),   4, ' ', STR_PAD_LEFT);
        $oDados->cd_AgenciaBancaria     = str_pad(substr($oDadosPlano->agencia,0,12),12, ' ', STR_PAD_RIGHT);
        $oDados->cd_ContaBancaria       = str_pad(substr($oDadosPlano->conta,( $oDadosPlano->banco == 104?2:0),10),  10, ' ', STR_PAD_RIGHT);
        $oDados->Reservado_tce1         = str_pad($ReservadoTCE,            34, " ", STR_PAD_LEFT);
        $oDados->Reservado_tce2         = str_pad($ReservadoTCE,             4, " ", STR_PAD_LEFT);
        $oDados->cd_FonteGestor         = str_pad($oDadosPlano->c61_codigo,  4, " ", STR_PAD_LEFT);
       if($iAnoSessao < 2013 ){
         $oDados->codigolinha            = 396;
       }else{
         $oDados->Cd_Atrib_ContaCorrente = '0';
         $oDados->Cd_ContaCorrente       = str_pad(str_repeat(' ', 30),  30, ' ', STR_PAD_RICHT);
         $oDados->de_ContaCorrente       = str_pad(str_repeat(' ', 100),  100, ' ', STR_PAD_RICHT);
         $oDados->nu_Sequencial_PCASP    = str_pad(str_repeat(' ', 5),  5, " ", STR_PAD_LEFT);
         $oDados->codigolinha            = 669;
       }
        
        if ($oDadosPlano->agencia == '0' ) {
          $oDados->cd_AgenciaBancaria  = str_pad($oDadosPlano->agencia, 12, ' ', STR_PAD_LEFT);
        }
        if ($oDadosPlano->conta == '0') {
          $oDados->cd_ContaBancaria    = str_pad($oDadosPlano->conta, 10, ' ', STR_PAD_LEFT);
        }
        
        $this->aDados[] = $oDados; 
        
      }
    }
    $this->addLog("===== Fim do Arquivo: ".$this->getNomeArquivo()."\n");
    
    return $this->aDados;
  }
  
}