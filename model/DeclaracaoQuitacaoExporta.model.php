<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

class DeclaracaoQuitacaoExporta extends DeclaracaoQuitacao {
  
  protected $sNomeAssinatura;
  
  protected $sCargo;
  
  protected $sMunicipio;
  
  protected $aDeclaracoes = array();
  
  public function geraPDF() {
    
    if(count($this->aDeclaracoes) == 0) {
      throw new Exception('Lista com o codigo das declarações que devem ser geradas não informado. ');    
    }
    
    $this->carregaNumpref();
    
    $clagata = new cl_dbagata("arrecadacao/declaracaoquitacao.agt");
    $api     = $clagata->api;
    
    $sCaminhoSalvoSxw = "tmp/declaracao_quitacao_anual_" . date('YmdHis') . db_getsession("DB_id_usuario") . ".sxw";
    
    $api->setOutputPath($sCaminhoSalvoSxw);
    
    $api->setParameter('$dataporextenso'       , $this->getDataExtenso());
    $api->setParameter('$declaracao'           , implode("','", $this->aDeclaracoes));
    $api->setParameter('$campo_nome_assinatura', $this->sNomeAssinatura);
    $api->setParameter('$cargo'                , $this->sCargo);
    
    try {
      
      $oDocumentoTemplate = new documentoTemplate(9);
        
    } catch (Exception $eException){
      
      $sErroMsg  = $eException->getMessage();
      db_redireciona("db_erros.php?fechar=true&db_erro={$sErroMsg}");
      
    }
    
    $lProcessado = $api->parseOpenOffice($oDocumentoTemplate->getArquivoTemplate());
    
    if($lProcessado){
      
      $sNomeRelatorio   = "tmp/declaracao_quitacao_anual_" . date('YmdHis') . db_getsession("DB_id_usuario") . ".pdf";               
      
      $sComandoConverte = db_stdClass::ex_oo2pdf($sCaminhoSalvoSxw, $sNomeRelatorio);
      
      if (!$sComandoConverte) {
        
        db_redireciona("db_erros.php?fechar=true&db_erro=Falha ao gerar PDF !!!");
        
      } else {
        
        db_redireciona($sNomeRelatorio);
        
      }
      
    } else {
      
      db_redireciona("db_erros.php?fechar=true&db_erro=Falha ao gerar relatório !!!");
      
    }

  }
  
  public function setDeclaracoes($aDeclaracoes) {
    
    if(!is_array($aDeclaracoes)) {
      $aDeclaracoes = array($aDeclaracoes);
    }
    
    $this->aDeclaracoes = $aDeclaracoes;
    
  }
  
  public function carregaNumpref() {
    
    $oDaoNumpref = db_utils::getDao('numpref');
    
    $sSqlNumpref = $oDaoNumpref->sql_query(db_getsession('DB_anousu'), db_getsession('DB_instit'));
    
    $rDaoNumpref = $oDaoNumpref->sql_record($sSqlNumpref);
    
    if($oDaoNumpref->numrows > 0) {
      $this->sNomeAssinatura = db_utils::fieldsMemory($rDaoNumpref, 0)->z01_nome;
      $this->sCargo          = db_utils::fieldsMemory($rDaoNumpref, 0)->rh37_descr;
    }
    
  }
  
  public function carregaDBConfig() {
    
    $oDaoDBConfig = db_utils::getDao('db_config');
    $rDaoDBConfig = $oDaoDBConfig->sql_record($oDaoDBConfig->sql_query_file(db_getsession('DB_instit')));
    
    if($oDaoDBConfig->numrows > 0) {
      
      $this->sMunicipio = db_utils::fieldsMemory($rDaoDBConfig, 0)->munic;
      
    }
    
  }
  
  public function getDataExtenso(){
    
    $this->carregaDBConfig();
    
    return $this->sMunicipio . ', ' . 
           date('d', db_getsession('DB_datausu')) .' de ' .  
           db_mes(date('m', db_getsession('DB_datausu')), 0) . ' de ' . 
           db_getsession('DB_anousu') ; 
    
  }
  
  public function geraTXT($iOrigem, $iExercicio, $lTipoCgm = false, $dData = null) {
    
    if(empty($iOrigem)) {
      throw new Exception('Origem não informada');
    }
    
    if(empty($iExercicio)) {
      throw new Exception('Exercicio não informado');
    }
    
    $oDaoDeclQuitacao = db_utils::getDao('declaracaoquitacao');
    
    if($iOrigem == 1) {
      
      $sSqlTxtOrigem = $oDaoDeclQuitacao->sql_query_txt_cgm($iExercicio, $lTipoCgm);
      
    } elseif($iOrigem == 2) {
      
      $sSqlTxtOrigem = $oDaoDeclQuitacao->sql_query_txt_matric($iExercicio, $dData);
      
    } elseif($iOrigem == 3) {
      
      $sSqlTxtOrigem = $oDaoDeclQuitacao->sql_query_txt_inscr($iExercicio);
      
    }

    echo "<br>";
    
    db_criatermometro("termometro2", "Concluido...", "blue", 1, "<div id='processando2'><blink>Aguarde, processando arquivo...</blink></div>");
    flush();
    
    db_atutermometro(0, 100, 'termometro2', 1, "Carregando informações para inicio do processo");
    
    $rTxtOrigem = pg_query($sSqlTxtOrigem);
    
    $sArquivo      = "tmp/declaracao_quitacao_anual_" . date('YmdHis') . db_getsession("DB_id_usuario") . ".txt";
    
    $clLayoutTXT   = new db_layouttxt();
    
    $clLayoutTXT->db_layouttxt(86, $sArquivo);
    
    if(pg_num_rows($rTxtOrigem) > 0) {
      
      for($i = 0; $i < pg_num_rows($rTxtOrigem); $i++) {
        
        $oTxtOrigem = db_utils::fieldsMemory($rTxtOrigem, $i);
        
        $clLayoutTXT->setCampoTipoLinha(3);
        $clLayoutTXT->limpaCampos();
        
        $clLayoutTXT->setCampo('INSTITUICAO'     , $oTxtOrigem->instituicao);
        $clLayoutTXT->setCampo('ANO'             , $oTxtOrigem->ano);        
        $clLayoutTXT->setCampo('ORIGEM'          , $oTxtOrigem->origem);
        $clLayoutTXT->setCampo('ENDERECO'        , $oTxtOrigem->endereco);
        $clLayoutTXT->setCampo('COD_ORIGEM'      , $oTxtOrigem->cod_origem);
        $clLayoutTXT->setCampo('NOME_ORIGEM'     , $oTxtOrigem->nome_origem);
        $clLayoutTXT->setCampo('COD_CPF_CNPJ'    , $oTxtOrigem->cod_cpf_cnpj);
        $clLayoutTXT->setCampo('CARGO'           , $oTxtOrigem->cargo);
        $clLayoutTXT->setCampo('NOME_ASSINATURA' , $oTxtOrigem->nome_assinatura);
        $clLayoutTXT->setCampo('DECLARACAO'      , $oTxtOrigem->declaracao);
        $clLayoutTXT->setCampo('LOGRADOURO'      , $oTxtOrigem->logradouro);
        $clLayoutTXT->setCampo('NUMERO_DO_IMOVEL', $oTxtOrigem->numero);
        $clLayoutTXT->setCampo('COMPLEMENTO'     , $oTxtOrigem->complemento);
        $clLayoutTXT->setCampo('BAIRRO'          , $oTxtOrigem->bairro);
        $clLayoutTXT->setCampo('ROTA'            , $oTxtOrigem->rota);
        $clLayoutTXT->setCampo('ORIENTACAO'      , $oTxtOrigem->orientacao);
        
        $sSqlTxtDebitos = $oDaoDeclQuitacao->sql_query_txt_debitos($oTxtOrigem->declaracao);
        
        $rTxtDebitos    = pg_query($sSqlTxtDebitos);

        if(pg_num_rows($rTxtDebitos) > 0) {
          
          for($d = 0; $d < (pg_numrows($rTxtDebitos) > 30 ? 30 : pg_num_rows($rTxtDebitos)); $d++) {
            
            $oTxtDebitos = db_utils::fieldsMemory($rTxtDebitos, $d);
            
            $clLayoutTXT->setCampo('COD_TIPO_DEBITO'. ($d+1) , $oTxtDebitos->codigo_tipo_debito);
            $clLayoutTXT->setCampo('TIPO_DEBITO' . ($d+1)    , $oTxtDebitos->tipo_debito);
            
          }
          
        }
        
        $clLayoutTXT->geraDadosLinha();
        
        db_atutermometro($i, pg_num_rows($rTxtOrigem), 'termometro2', 1, "Processando arquivo - {$oTxtOrigem->declaracao} (" . ($i + 1) . "/ " . pg_num_rows($rTxtOrigem) . ") ");
      }
      
      $clLayoutTXT->fechaArquivo();
      
    }
    
    return $sArquivo;
    
  }
  
  public function geraLayoutTxt() {
    
    $sArquivo     = 'tmp/declaracao_quitacao_layout.txt';
    $clLayoutTxt  = new db_layouttxt(12, $sArquivo);
    $clLayoutTxt->setCampoTipoLinha(3);
    $clLayoutTxt->limpaCampos();
    
    $oDaoLayoutCampos = db_utils::getDao('db_layoutcampos');
    
    $sCampos  = "db52_nome, 
                 db52_descr, 
                 db52_layoutformat, 
                 db52_posicao as db52_posicao_inicial, 
                 db52_posicao - 1 + 
                (case when db52_tamanho = 0 
                  then db53_tamanho 
                  else db52_tamanho 
                 end) as db52_posicao_final";

    $sSqlLayoutCampos = $oDaoLayoutCampos->sql_query(null, $sCampos, "db52_posicao", 'db52_layoutlinha = 289');
    
    $rDaoLayoutCampos = $oDaoLayoutCampos->sql_record($sSqlLayoutCampos);

    for ($i = 0; $i < $oDaoLayoutCampos->numrows; $i++) {

      $oLayoutCampos = db_utils::fieldsMemory($rDaoLayoutCampos, $i);

      $clLayoutTxt->setCampo("posicao_inicial", $oLayoutCampos->db52_posicao_inicial);
      $clLayoutTxt->setCampo("posicao_final",   $oLayoutCampos->db52_posicao_final);
      $clLayoutTxt->setCampo("nome_campo",      $oLayoutCampos->db52_nome);
      $clLayoutTxt->setCampo("descricao",       $oLayoutCampos->db52_descr);
      $clLayoutTxt->geraDadosLinha();

    }

    $clLayoutTxt->fechaArquivo();

    return $sArquivo;
    
  }
  
}