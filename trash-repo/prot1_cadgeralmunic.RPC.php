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

require_once ("libs/db_stdlib.php");
require_once ("std/db_stdClass.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/JSON.php");
require_once ("model/CgmFactory.model.php");
require_once ("model/endereco.model.php");

$oJson    = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));

$iInstit  = db_getsession("DB_instit");
$oRetorno = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = "";

switch ($oParam->exec) {
  
  case 'findCidadao':
    
    $oDaoCidado = db_utils::getDao('cidadao');
    
    $sCampos  = " ov02_sequencial, ov02_seq, ov02_nome as z01_nome, ov02_cnpjcpf, ov02_cep as z01_cep, ";
    $sCampos .= " ov02_endereco as z01_ender, ov02_numero as z01_numero, ov02_compl as z01_compl, "; 
    $sCampos .= " ov02_munic as z01_munic, ov02_bairro as z01_bairro, ov02_uf as z01_uf, ov02_ident as z01_ident, ";
    $sCampos .= " ((case when ov07_ddd = 0 then '' else ov07_ddd end) ||' '||ov07_numero) as z01_telef, "; 
    $sCampos .= " ov08_email as z01_email ";
    
    $sWhere   = "ov02_sequencial = $oParam->ov02_sequencial and ov02_ativo is true ";
    
    $sQueryCidadao  = $oDaoCidado->sql_query_importaCidadao(null, null, $sCampos, null, $sWhere);
    $rsQueryCidadao = $oDaoCidado->sql_record($sQueryCidadao);
    
    if ($rsQueryCidadao !== false) {
      
      $oCidadao = db_utils::fieldsMemory($rsQueryCidadao,0);
      $oRetorno->cidadao = $oCidadao;
      
      $sMsgPermissao  = "usuário:\n\n Você não tem permissão para incluir CPF/CNPJ zerado,"; 
      $sMsgPermissao .= "\n contate o administrador para obter esta permissão!\n\n";
      // Aqui Valida se o usuario tem permissao para manipular CPF zerado {00000000000}
      $lPermissaoCpfZerado = db_permissaomenu(db_getsession("DB_anousu"),604,4459);
        
      if ($lPermissaoCpfZerado == 'false' && trim($oCidadao->ov02_cnpjcpf == '00000000000')) {
            
        $oRetorno->status = 2;
        $oRetorno->message = urlencode($sMsgPermissao);
        echo $oJson->encode($oRetorno);    
        break;
      }
      // Aqui Valida se o usuario tem permissao para manipular CNPJ zerado {00000000000000}
      $lPermissaoCnpjZerado = db_permissaomenu(db_getsession("DB_anousu"),604,3775);
      if ($lPermissaoCnpjZerado == 'false' && trim($oCidadao->ov02_cnpjcpf == '00000000000000')) {
        
        $oRetorno->status = 2;
        $oRetorno->message = urlencode($sMsgPermissao);
        echo $oJson->encode($oRetorno);    
        break;
      }
      
      
      
      //Se houve retorno tenho fazer o cadastro de endereco para retornar a chave
      if (trim($oCidadao->z01_ender) != "") {
        
        $oEnderecoCidadao = endereco::buscaEnderecoCidadao($oCidadao->ov02_sequencial, $oCidadao->ov02_seq, false);
        
        //var_dump($oEnderecoCidadao);
        
        if ($oEnderecoCidadao !== false) {
          db_inicio_transacao();
          try {
                  
            $oEndereco = new endereco(null);    
            
            $oEndereco->setCodigoEstado(1);
            if (trim($oEnderecoCidadao[0]->db71_sequencial) != "") {
              $oEndereco->setCodigoEstado($oEnderecoCidadao[0]->db71_sequencial);
            }
            
            $oEndereco->setCodigoMunicipio(0);
            if (trim($oEnderecoCidadao[0]->db72_sequencial) != "") {
              $oEndereco->setCodigoMunicipio($oEnderecoCidadao[0]->db72_sequencial);
            } else if(trim($oEnderecoCidadao[0]->ov02_munic) != ""){
              $oEndereco->setDescricaoMunicipio(trim($oEnderecoCidadao[0]->ov02_munic));
              $oEndereco->setCodigoMunicipio(null);
            }

            $oEndereco->setCodigoBairro(0);
            if (trim($oEnderecoCidadao[0]->db73_sequencial) != "") {
              $oEndereco->setCodigoBairro($oEnderecoCidadao[0]->db73_sequencial);
            } else if(trim($oEnderecoCidadao[0]->ov02_bairro) != ""){
              $oEndereco->setDescricaoBairro(trim($oEnderecoCidadao[0]->ov02_bairro));
              $oEndereco->setCodigoBairro(null);
            }
            
            $oEndereco->setCodigoRua($oEnderecoCidadao[0]->db74_sequencial);
            if (trim($oEnderecoCidadao[0]->db74_sequencial) == "") {
              $oEndereco->setCodigoRua('');
              $oEndereco->setDescricaoRua($oEnderecoCidadao[0]->ov02_endereco);
            } 
                
            $oEndereco->setNumeroLocal($oEnderecoCidadao[0]->ov02_numero);
            
            $oEndereco->setComplementoEndereco($oEnderecoCidadao[0]->ov02_compl);     
            
            $oEndereco->setCepEndereco($oEnderecoCidadao[0]->ov02_cep);
            
            $oEndereco->setCadEnderRuaTipo(3);
                            
            $oEndereco->salvaEndereco();
            
            db_fim_transacao(false);

            $iCodigoEndereco  = $oEndereco->getCodigoEndereco();
                            
          } catch (Exception $erro) {
            
            db_fim_transacao(true);
            $oRetorno->message  = urlencode($erro->getMessage());
            $oRetorno->status   = 2;    
          }
          
          $oRetorno->endereco = endereco::findEnderecoByCodigo($iCodigoEndereco);
        } else {
          
          $oRetorno->endereco = false;
        }
              
      } else {
        
        $oRetorno->endereco = false;
      }
      
    } else {
      $oRetorno->status = 2;
      $oRetorno->message = urlencode("usuário:\n\n Falha ao importar os dados do cadastro do cidadão!\n\n");
    }
    
    echo $oJson->encode($oRetorno);
    break;
  
  case 'findCpfCnpj':
        
    $oCgm = CgmFactory::getInstanceByCnpjCpf($oParam->iCpfCnpj);
    if ($oCgm === false) {
      $oRetorno->z01_numcgm = false;
    } else {
      $oRetorno->z01_numcgm = $oCgm->getCodigo();
      $oRetorno->message = urlencode("usuário:\n\n CPF/CNPJ já cadastrado para o CGM {".$oCgm->getCodigo()."}\n\n");
    }
    
    
    echo $oJson->encode($oRetorno);
    break;
  
  case 'findCgm':
  
    $oCgm       = new stdClass();
    $oCgm = CgmFactory::getInstanceByCgm($oParam->numcgm);
    
    $sMsgPermissao  = "usuário:\n\n Você não tem permissão para incluir CPF/CNPJ zerado,"; 
    $sMsgPermissao .= "\n contate o administrador para obter esta permissão!\n\n";
    // Aqui Valida se o usuario tem permissao para manipular CPF zerado {00000000000}
    $lPermissaoCpfZerado = db_permissaomenu(db_getsession("DB_anousu"),604,4459);
      
    if ($oCgm->isFisico() && $lPermissaoCpfZerado == 'false' && trim($oCgm->getCpf()) == '00000000000') {
          
      $oRetorno->status = 2;
      $oRetorno->message = urlencode($sMsgPermissao);
      echo $oJson->encode($oRetorno);    
      break;
    }
    // Aqui Valida se o usuario tem permissao para manipular CNPJ zerado {00000000000000}
    $lPermissaoCnpjZerado = db_permissaomenu(db_getsession("DB_anousu"),604,3775);
    if ($oCgm->isJuridico() && !$lPermissaoCnpjZerado == 'false' && $oCgm->getCnpj() == '00000000000000') {
      
      $oRetorno->status = 2;
      $oRetorno->message = urlencode($sMsgPermissao);
      echo $oJson->encode($oRetorno);    
      break;
    }
        
    /* Verifico se o CGM é Físico */
    if ($oCgm->isFisico()) {
    
      $oCgmFisico = new stdClass();  
      
      $oCgmFisico->lfisico    = true;
      
      $oCgmFisico->z01_numcgm        = $oCgm->getCodigo();
      $oCgmFisico->z01_cpf           = $oCgm->getCpf();
      $oCgmFisico->z01_ident         = $oCgm->getIdentidade();
      $oCgmFisico->z01_nome          = urlencode($oCgm->getNome()); 
      $oCgmFisico->z01_pai           = urlencode($oCgm->getNomePai());
      $oCgmFisico->z01_mae           = urlencode($oCgm->getNomeMae());
      $oCgmFisico->z01_nasc          = $oCgm->getDataNascimento();
      $oCgmFisico->z01_estciv        = $oCgm->getEstadoCivil();
      $oCgmFisico->z01_sexo          = $oCgm->getSexo();
      $oCgmFisico->z01_nacion        = $oCgm->getNacionalidade();
      $oCgmFisico->municipio         = urlencode($oCgm->getMunicipio());
      $oCgmFisico->z01_profis        = urlencode($oCgm->getProfissao());
      $oCgmFisico->z01_telef         = $oCgm->getTelefone();
      $oCgmFisico->z01_telcel        = $oCgm->getCelular();
      $oCgmFisico->z01_email         = urlencode($oCgm->getEmail());
      $oCgmFisico->z01_telcon        = $oCgm->getTelefoneComercial();
      $oCgmFisico->z01_celcon        = $oCgm->getCelularComercial();
      $oCgmFisico->z01_emailc        = urlencode($oCgm->getEmailComercial());
      $oCgmFisico->z01_dtfalecimento = $oCgm->getDataFalecimento();
      $oCgmFisico->z01_identorgao    = $oCgm->getIdentOrgao();
      $oCgmFisico->z01_identdtexp    = $oCgm->getIdentDataExp();
      $oCgmFisico->z01_naturalidade  = $oCgm->getNaturalidade();
      $oCgmFisico->z01_escolaridade  = $oCgm->getEscolaridade();
      $oCgmFisico->z01_trabalha      = $oCgm->getTrabalha();
      $oCgmFisico->z01_localtrabalha = urlencode($oCgm->getLocalTrabalho());
      $oCgmFisico->z01_renda         = $oCgm->getRenda();
      $oCgmFisico->z01_pis           = $oCgm->getPIS();
      $oCgmFisico->z01_foto          = $oCgm->getFotoPrincipal();
      // dados para novo cadastro CGM
      $oCgmFisico->z01_fax           = $oCgm->getFax();
      $oCgmFisico->z01_cxpostal      = $oCgm->getCaixaPostal();
      $oCgmFisico->z01_cxposcon      = $oCgm->getCaixaPostalComercial();
      $oCgmFisico->z01_incest        = $oCgm->getInscricaoEstadual();
      $oCgmFisico->z01_obs           = urlencode($oCgm->getObs());
      
      $oCgmFisico->z04_rhcbo         = $oCgm->getCBO();
      
      $oRetorno->cgm = $oCgmFisico;
      
    /* Fim CGM Fisico */
    } else if ($oCgm->isJuridico()) {
      
      $oCgmJuridico = new stdClass();  
      
      $oCgmJuridico->lfisico = false;
      
      $oCgmJuridico->z01_numcgm      = $oCgm->getCodigo();
      $oCgmJuridico->z01_cgc         = $oCgm->getCnpj();
      $oCgmJuridico->z01_incest      = $oCgm->getInscricaoEstadual();
      $oCgmJuridico->municipio       = urlencode($oCgm->getMunicipio());
      $oCgmJuridico->z01_telef       = $oCgm->getTelefone();
      $oCgmJuridico->z01_telcel      = $oCgm->getCelular();
      $oCgmJuridico->z01_email       = urlencode($oCgm->getEmail());
      $oCgmJuridico->z01_telcon      = $oCgm->getTelefoneComercial();
      $oCgmJuridico->z01_celcon      = $oCgm->getCelularComercial();
      $oCgmJuridico->z01_emailc      = urlencode($oCgm->getEmailComercial());
      $oCgmJuridico->z01_nome        = urlencode($oCgm->getNome());
      $oCgmJuridico->z01_contato     = urlencode($oCgm->getContato());
      $oCgmJuridico->z01_nomefanta   = urlencode($oCgm->getNomeFantasia()); 
      $oCgmJuridico->z01_nomecomple  = urlencode($oCgm->getNomeCompleto());
      $oCgmJuridico->z01_foto        = $oCgm->getFotoPrincipal();
      $oCgmJuridico->nire            = $oCgm->getNire();
      // dados para novo cadastro CGM
      $oCgmJuridico->z01_fax         = $oCgm->getFax();
      $oCgmJuridico->z01_cxpostal    = $oCgm->getCaixaPostal();
      $oCgmJuridico->z01_cxposcon    = $oCgm->getCaixaPostalComercial();      
      $oCgmJuridico->z01_obs         = urlencode($oCgm->getObs());
      
      $oRetorno->cgm = $oCgmJuridico;  
    }
    
    $oRetorno->endereco = false;
    if (trim($oRetorno->cgm->z01_numcgm) != '') {
      
      $oRetorno->endereco = endereco::findCgmEnderecoByCgm($oRetorno->cgm->z01_numcgm);
      
      $oRetorno->tipoempresa = $oCgm->getTipoEmpresa();
      
      if ($oRetorno->tipoempresa !== false) {
       $oRetorno->tipoempresa[0]->db98_descricao = urlencode($oRetorno->tipoempresa[0]->db98_descricao);
      }
      
      $oRetorno->cgmmunicipio = $oCgm->getCgmMunicipio();
      
      $oRetorno->lPermissaoCidadao = db_permissaomenu(db_getsession("DB_anousu"),604,7901);
      
      $oDaoCidadoCgm = db_utils::getDao("cidadaocgm");
      $sWhere = " ov03_numcgm = ".$oRetorno->cgm->z01_numcgm;
      $sQueryCidadaoCgm = $oDaoCidadoCgm->sql_query_file(null, "*", null, $sWhere);
      $rsQueryCidadaoCgm = $oDaoCidadoCgm->sql_record($sQueryCidadaoCgm);
      
      if ($oDaoCidadoCgm->numrows > 0) {
        
        $oRetorno->cidadaocgm = db_utils::getColectionByRecord($rsQueryCidadaoCgm,0);       
      } else {
        $oRetorno->cidadaocgm = false;
      }
      
    }
    
    //print_r($oCgmJuridico);
    echo $oJson->encode($oRetorno);
    break;
    
  case 'incluirAlterar' :
    
    //exit;
    $sqlErro = false;

    $oRetorno->action     = $oParam->action;    
    
    db_inicio_transacao();
    db_query("select fc_putsession('DB_habilita_trigger_endereco','false')");
    
    if ($oParam->lPessoaFisica == true) {
      
      if ($oParam->action == "incluir") {
        
        $oCgm = CgmFactory::getInstanceByType(1);
        
      } else if ($oParam->action == "alterar") {
        
        $oCgm = CgmFactory::getInstanceByCgm($oParam->pessoa->z01_numcgm);
      }
      $oCgm->setCodigo($oParam->pessoa->z01_numcgm);
      $oCgm->setCpf($oParam->pessoa->z01_cgccpf);
      $oCgm->setIdentidade($oParam->pessoa->z01_ident);
      $oCgm->setNome(utf8_decode(db_stdClass::db_stripTagsJson($oParam->pessoa->z01_nome)));
      $oCgm->setNomePai(utf8_decode(db_stdClass::db_stripTagsJson($oParam->pessoa->z01_pai)));      
      $oCgm->setNomeMae(utf8_decode(db_stdClass::db_stripTagsJson($oParam->pessoa->z01_mae)));      
      $oCgm->setProfissao(utf8_decode(db_stdClass::db_stripTagsJson($oParam->pessoa->z01_profis)));      
      $oCgm->setEmail(utf8_decode(db_stdClass::db_stripTagsJson($oParam->pessoa->z01_email)));      
      $oCgm->setEmailComercial(utf8_decode(db_stdClass::db_stripTagsJson($oParam->pessoa->z01_emailc)));
      $oCgm->setNaturalidade(utf8_decode(db_stdClass::db_stripTagsJson($oParam->pessoa->z01_naturalidade)));
      $oCgm->setEscolaridade(utf8_decode(db_stdClass::db_stripTagsJson($oParam->pessoa->z01_escolaridade)));
      $oCgm->setIdentOrgao(utf8_decode(db_stdClass::db_stripTagsJson($oParam->pessoa->z01_identorgao)));
      $oCgm->setLocalTrabalho(utf8_decode(db_stdClass::db_stripTagsJson($oParam->pessoa->z01_localtrabalho)));
      $oCgm->setPIS($oParam->pessoa->z01_pis);
      $oCgm->setCBO($oParam->pessoa->z04_rhcbo);
      $oCgm->setEstadoCivil($oParam->pessoa->z01_estciv);      
      $oCgm->setSexo($oParam->pessoa->z01_sexo);      
      $oCgm->setNacionalidade($oParam->pessoa->z01_nacion);      
      $oCgm->setTelefone($oParam->pessoa->z01_telef);      
      $oCgm->setCelular($oParam->pessoa->z01_telcel);      
      $oCgm->setTelefoneComercial($oParam->pessoa->z01_telcon);      
      $oCgm->setCelularComercial($oParam->pessoa->z01_celcon);      
      $oCgm->setDataNascimento($oParam->pessoa->z01_nasc);      
      $oCgm->setDataFalecimento($oParam->pessoa->z01_dtfalecimento);
      $oCgm->setIdentDataExp($oParam->pessoa->z01_identdtexp);
      $oCgm->setCadastro($oParam->pessoa->z01_cadast);
      
      // Campos novos criados 
      $oCgm->setFax($oParam->pessoa->z01_fax);
      $oCgm->setCaixaPostal($oParam->pessoa->z01_cxpostal);
      $oCgm->setCaixaPostalComercial($oParam->pessoa->z01_cxposcon);
      $oCgm->setInscricaoEstadual($oParam->pessoa->z01_incest);
      $oCgm->setObs(utf8_decode(db_stdClass::db_stripTagsJson($oParam->pessoa->z01_obs)));
      
      if ($oParam->pessoa->z01_trabalha == 't') {
       $oCgm->setTrabalha(true);
      } else {
       $oCgm->setTrabalha(false);       
      }
      $oCgm->setRenda($oParam->pessoa->z01_renda);
       
      /*seta os endereços*/
      $oEnderecoPrimario   = endereco::findEnderecoByCodigo($oParam->endereco->idEndPrimario,   false);

      if ($oParam->endereco->idEndSecundario == '') {
        $oEnderecoSecundario = false;       
      } else {
       $oEnderecoSecundario = endereco::findEnderecoByCodigo($oParam->endereco->idEndSecundario, false);
      }
      $oCgm->setEnderecoPrimario($oParam->endereco->idEndPrimario);
      $oCgm->setEnderecoSecundario($oParam->endereco->idEndSecundario);
      if ($oEnderecoPrimario !== false) {
              
        $oCgm->setUf($oEnderecoPrimario[0]->ssigla);
        if ($oEnderecoPrimario[0]->scep != "") {
         $oCgm->setCep($oEnderecoPrimario[0]->scep);
        }
        $oCgm->setBairro($oEnderecoPrimario[0]->sbairro);
        $oCgm->setNumero($oEnderecoPrimario[0]->snumero);
        $oCgm->setMunicipio($oEnderecoPrimario[0]->smunicipio);
        $oCgm->setLogradouro($oEnderecoPrimario[0]->srua);
        $oCgm->setComplemento($oEnderecoPrimario[0]->scomplemento);
        
      } else {
        
        $sqlErro = true;
        $oRetorno->status = 2;
        $oRetorno->message = urlencode("endereco não informado");
      }
      
      if (!$sqlErro) {
        
        if ($oEnderecoSecundario !== false) {
                
          $oCgm->setUfComercial($oEnderecoSecundario[0]->ssigla);
          if ($oEnderecoSecundario[0]->scep != "") {
           $oCgm->setCepComercial($oEnderecoSecundario[0]->scep);
          }
          $oCgm->setBairroComercial($oEnderecoSecundario[0]->sbairro);
          $oCgm->setNumeroComercial($oEnderecoSecundario[0]->snumero);
          $oCgm->setMunicipioComercial($oEnderecoSecundario[0]->smunicipio);
          $oCgm->setLogradouroComercial($oEnderecoSecundario[0]->srua);
          $oCgm->setComplementoComercial($oEnderecoSecundario[0]->scomplemento);
          
        } else {
            
            $oCgm->setUfComercial('');
            $oCgm->setCepComercial('');
            $oCgm->setBairroComercial('');
            $oCgm->setNumeroComercial('');
            $oCgm->setMunicipioComercial('');
            $oCgm->setLogradouroComercial('');
            $oCgm->setComplementoComercial('');
       }
      }
      if (!$sqlErro) {          
        try {
          
          $oCgm->save();
          if ($oParam->action == "incluir") {
            
            $oRetorno->message = urlencode("usuario:\\n\\n Cgm incluído com sucesso (".$oCgm->getCodigo().")\\n\\n");
          } else if ($oParam->action == "alterar") {
            
            $oRetorno->message = urlencode("usuario:\\n\\n Cgm alterado com sucesso (".$oCgm->getCodigo().")\\n\\n");
          }
                  
        } catch (Exception $erro) {
          
          $sqlErro = true;
          $oRetorno->status = 2;
          $oRetorno->message = urlencode($erro->getMessage());    
        }
      }
      //Aqui vai manipular o cidadaocgm
      if (!$sqlErro) {
        if (trim($oParam->cidadao->ov02_sequencial) != "" && trim($oParam->cidadao->ov02_seq) != "") {

          $oDaoCidadoCgm    = db_utils::getDao("cidadaocgm");
          $sCampos = " * ";
          $sWhere  = " ov03_cidadao = ".$oParam->cidadao->ov02_sequencial." and ov03_seq = ".$oParam->cidadao->ov02_seq ;
          $sWhere .= " and ov03_numcgm  = ".$oCgm->getCodigo() ;
          
          $sQueryCidadaoCgm  = $oDaoCidadoCgm->sql_query_file(null, $sCampos, null, $sWhere);
          $rsQueryCidadaoCgm = $oDaoCidadoCgm->sql_record($sQueryCidadaoCgm);
          
          if ($oDaoCidadoCgm->numrows == 0) {
            
            $oDaoCidadoCgm->ov03_cidadao = $oParam->cidadao->ov02_sequencial;
            $oDaoCidadoCgm->ov03_seq     = $oParam->cidadao->ov02_seq;
            $oDaoCidadoCgm->ov03_numcgm  = $oCgm->getCodigo();
            $oDaoCidadoCgm->incluir(null);
            if ($oDaoCidadoCgm->erro_status == "0") {
              $oRetorno->status = 2;
              $oRetorno->message = urlencode($oDaoCidadoCgm->erro_msg);           
              $sqlErro = true;
            }  
          }
          
          if (!$sqlErro) {
            
            $oDaoCidado = db_utils::getDao("cidadao");
            $oDaoCidado->ov02_situacaocidadao = 1;
            $oDaoCidado->alterar_where($oParam->cidadao->ov02_sequencial,
                                       $oParam->cidadao->ov02_seq,
                                       "ov02_sequencial = ".$oParam->cidadao->ov02_sequencial." and 
                                        ov02_seq = ".$oParam->cidadao->ov02_seq
                                       );
            if ($oDaoCidado->erro_status == 0) {
  
              $oRetorno->status = 2;
              $oRetorno->message = urlencode($oDaoCidado->erro_msg);           
              $sqlErro = true;
            }
          }
        }
      }
    /*  
      $sqlErro = true;
        
      db_query("select fc_delsession('DB_habilita_trigger_endereco')");
      db_fim_transacao($sqlErro);
    */ 
    //Aqui manipula cgm Pessoa Jurídica  
    } else if ($oParam->lPessoaFisica == false) {
      
      $sqlErro = false;
      
      if ($oParam->action == "incluir") {
        
        $oCgm = CgmFactory::getInstanceByType(2);
        
      } else if ($oParam->action == "alterar") {
        
        $oCgm = CgmFactory::getInstanceByCgm($oParam->pessoa->z01_numcgm);
      }
      
      $oCgm->setCodigo($oParam->pessoa->z01_numcgm);
      $oCgm->setCnpj($oParam->pessoa->z01_cgccpf);
      $oCgm->setNome(utf8_decode(db_stdClass::db_stripTagsJson($oParam->pessoa->z01_nome)));
      $oCgm->setNomeCompleto(utf8_decode(db_stdClass::db_stripTagsJson($oParam->pessoa->z01_nomecomple)));
      $oCgm->setNomeFantasia(utf8_decode(db_stdClass::db_stripTagsJson($oParam->pessoa->z01_nomefanta)));
      $oCgm->setContato($oParam->pessoa->z01_contato);      
      $oCgm->setInscricaoEstadual($oParam->pessoa->z01_incest);      
      $oCgm->setTelefone($oParam->pessoa->z01_telef);      
      $oCgm->setCelular($oParam->pessoa->z01_telcel);      
      $oCgm->setEmail(utf8_decode(db_stdClass::db_stripTagsJson($oParam->pessoa->z01_email)));      
      $oCgm->setTelefoneComercial($oParam->pessoa->z01_telcon);      
      $oCgm->setCelularComercial($oParam->pessoa->z01_celcon);      
      $oCgm->setEmailComercial(utf8_decode(db_stdClass::db_stripTagsJson($oParam->pessoa->z01_emailc)));
      $oCgm->setNire($oParam->nire->z08_nire);
      //Campos novos criados
      $oCgm->setCadastro($oParam->pessoa->z01_cadast);
      $oCgm->setFax($oParam->pessoa->z01_fax);
      $oCgm->setCaixaPostal($oParam->pessoa->z01_cxpostal);
      $oCgm->setCaixaPostalComercial($oParam->pessoa->z01_cxposcon);
      $oCgm->setObs(utf8_decode(db_stdClass::db_stripTagsJson($oParam->pessoa->z01_obs)));
      

      
      /*seta os endereços*/
      $oEnderecoPrimario   = endereco::findEnderecoByCodigo($oParam->endereco->idEndPrimario,   false);
      if ($oParam->endereco->idEndSecundario == '') {
        $oEnderecoSecundario = false;       
      } else {
       $oEnderecoSecundario = endereco::findEnderecoByCodigo($oParam->endereco->idEndSecundario, false);
      }

      $oCgm->setEnderecoPrimario($oParam->endereco->idEndPrimario);
          
      $oCgm->setEnderecoSecundario($oParam->endereco->idEndSecundario);
      
      if ($oEnderecoPrimario !== false) {
              
        $oCgm->setUf($oEnderecoPrimario[0]->ssigla);
        if ($oEnderecoPrimario[0]->scep != "") {
         $oCgm->setCep($oEnderecoPrimario[0]->scep);
        }
        $oCgm->setBairro($oEnderecoPrimario[0]->sbairro);
        $oCgm->setNumero($oEnderecoPrimario[0]->snumero);
        $oCgm->setMunicipio($oEnderecoPrimario[0]->smunicipio);
        $oCgm->setLogradouro($oEnderecoPrimario[0]->srua);
        $oCgm->setComplemento($oEnderecoPrimario[0]->scomplemento);
        
      } else {
        db_fim_transacao(true);
        $oRetorno->status = 2;
        $oRetorno->message = urlencode("endereco não informado");
        echo $oJson->encode($oRetorno);
        exit();
      }
       
      if ($oEnderecoSecundario !== false) {
                
          $oCgm->setUfComercial($oEnderecoSecundario[0]->ssigla);
          if ($oEnderecoSecundario[0]->scep != "") {
           $oCgm->setCepComercial($oEnderecoSecundario[0]->scep);
          }
          $oCgm->setBairroComercial($oEnderecoSecundario[0]->sbairro);
          $oCgm->setNumeroComercial($oEnderecoSecundario[0]->snumero);
          $oCgm->setMunicipio($oEnderecoSecundario[0]->smunicipio);
          $oCgm->setLogradouroComercial($oEnderecoSecundario[0]->srua);
          $oCgm->setComplementoComercial($oEnderecoSecundario[0]->scomplemento);
          
      }
          
      try {
        
        $oCgm->save();
        if ($oParam->action == "incluir") {
          
          $oRetorno->message = urlencode("usuario:\\n\\n Cgm incluído com sucesso (".$oCgm->getCodigo().")\\n\\n");
        } else if ($oParam->action == "alterar") {
          
          $oRetorno->message = urlencode("usuario:\\n\\n Cgm alterado com sucesso (".$oCgm->getCodigo().")\\n\\n");
        }
        
      } catch (Exception $erro) {

        $sqlErro = true;
        $oRetorno->status = 2;
        $oRetorno->message = urlencode($erro->getMessage());    
      }
    //Aqui vai manipular o cidadaocgm
      
      if (!$sqlErro) {
        
        if (trim($oParam->cidadao->ov02_sequencial) != "" && trim($oParam->cidadao->ov02_seq) != "") {
        
          $oDaoCidadoCgm    = db_utils::getDao("cidadaocgm");
          $sCampos = " * ";
          $sWhere  = " ov03_cidadao = ".$oParam->cidadao->ov02_sequencial." and ov03_seq = ".$oParam->cidadao->ov02_seq ;
          $sWhere .= " and ov03_numcgm  = ".$oCgm->getCodigo() ;
          
          $sQueryCidadaoCgm  = $oDaoCidadoCgm->sql_query_file(null, $sCampos, null, $sWhere);
          $rsQueryCidadaoCgm = $oDaoCidadoCgm->sql_record($sQueryCidadaoCgm);
          
          if ($oDaoCidadoCgm->numrows == 0) {
            
            $oDaoCidadoCgm->ov03_cidadao = $oParam->cidadao->ov02_sequencial;
            $oDaoCidadoCgm->ov03_seq     = $oParam->cidadao->ov02_seq;
            $oDaoCidadoCgm->ov03_numcgm  = $oCgm->getCodigo();
            $oDaoCidadoCgm->incluir(null);
            if ($oDaoCidadoCgm->erro_status == "0") {
           
              $oRetorno->status = 2;
              $oRetorno->message = urlencode($oDaoCidadoCgm->erro_msg);           
              $sqlErro = true;
              
            }  
          }
          
          if (!$sqlErro) {
            
            $oDaoCidado = db_utils::getDao("cidadao");
            $oDaoCidado->ov02_situacaocidadao = 1;
            $oDaoCidado->alterar_where($oParam->cidadao->ov02_sequencial,
                                       $oParam->cidadao->ov02_seq,
                                       "ov02_sequencial = ".$oParam->cidadao->ov02_sequencial." and 
                                        ov02_seq = ".$oParam->cidadao->ov02_seq
                                       );
            if ($oDaoCidado->erro_status == "0") {
              
              $oRetorno->status = 2;
              $oRetorno->message = urlencode($oDaoCidado->erro_msg);           
              $sqlErro = true;
            }
          }
        }
      }
    }   
    
    if (!$sqlErro) {    
      /*----------------------------Processa Tipo Empresa--------------------------------------------------*/ 
      /**
       * Verifica se existe resgistro na cgmendereco se existir deleta
       */
      $oDaoCgmTipoEmpresa    = db_utils::getDao("cgmtipoempresa");
      $sQueryCgmTipoEmpresa  = $oDaoCgmTipoEmpresa->sql_query(null,"z03_sequencial",null,"z03_numcgm = ".$oCgm->getCodigo());
      $rsQueryCgmTipoEmpresa = $oDaoCgmTipoEmpresa->sql_record($sQueryCgmTipoEmpresa);
      /**
       * Se existrir registro deleta
       */
      if ($rsQueryCgmTipoEmpresa !== false) {
       
        $oDaoCgmTipoEmpresa->excluir(db_utils::fieldsMemory($rsQueryCgmTipoEmpresa,0)->z03_sequencial);
      }
      /**
       * Se Tipo Empresa for diferente de vazio inseri 
       */
      if (trim($oParam->tipoEmpresa->iTipoEmpresa) != "") {
        
        $oDaoCgmTipoEmpresa->z03_numcgm      = $oCgm->getCodigo();
        $oDaoCgmTipoEmpresa->z03_tipoempresa = $oParam->tipoEmpresa->iTipoEmpresa;
        $oDaoCgmTipoEmpresa->incluir(null);

        if ($oDaoCgmTipoEmpresa->erro_status == "0") {
          $oRetorno->status = 2;
          $oRetorno->message = urlencode($oDaoCgmTipoEmpresa->erro_msg);
          $sqlErro = true;
        }
          
      }
      
      /* ----------------------------Fim do Processo Tipo Empresa--------------------------------------------------*/
    } 
    db_fim_transacao($sqlErro);
    
    if (!$sqlErro) {

      $oRetorno->z01_numcgm = $oCgm->getCodigo();
    }
    
    echo $oJson->encode($oRetorno);
    break;
      
  case 'findEnderecoByCodigo' :
    
    $oRetorno->endereco = endereco::findEnderecoByCodigo($oParam->iCodigoEndereco); 
    echo $oJson->encode($oRetorno);
    break;
  
  case 'atualizarCgmCidadao' :
      
    db_inicio_transacao();
    
    $oCgm = CgmFactory::getInstanceByCgm($oParam->pessoa->z01_numcgm);
    
    $sMsgPermissao  = "usuário:\n\n Você não tem permissão para incluir CPF/CNPJ zerado,"; 
    $sMsgPermissao .= "\n contate o administrador para obter esta permissão!\n\n";
    // Aqui Valida se o usuario tem permissao para manipular CPF zerado {00000000000}
    $lPermissaoCpfZerado = db_permissaomenu(db_getsession("DB_anousu"),604,4459);
      
    if ($oCgm->isFisico() && $lPermissaoCpfZerado == 'false' && trim($oCgm->getCpf()) == '00000000000') {
          
      $oRetorno->status = 2;
      $oRetorno->message = urlencode($sMsgPermissao);
      echo $oJson->encode($oRetorno);    
      break;
    }
    // Aqui Valida se o usuario tem permissao para manipular CNPJ zerado {00000000000000}
    $lPermissaoCnpjZerado = db_permissaomenu(db_getsession("DB_anousu"),604,3775);
    if ($oCgm->isJuridico() && !$lPermissaoCnpjZerado == 'false' && $oCgm->getCnpj() == '00000000000000') {
      
      $oRetorno->status = 2;
      $oRetorno->message = urlencode($sMsgPermissao);
      echo $oJson->encode($oRetorno);    
      break;
    }
    
    $oCgm->setCodigo($oParam->pessoa->z01_numcgm);
    
    if (isset($oParam->pessoa->z01_ender)) {
      $oCgm->setEnderecoPrimario(utf8_decode(db_stdClass::db_stripTagsJson(($oParam->pessoa->z01_ender))));
    }
    if (isset($oParam->pessoa->z01_numero)) { 
      $oCgm->setNumero($oParam->pessoa->z01_numero);
    }
    if (isset($oParam->pessoa->z01_compl)) {
      $oCgm->setComplemento((utf8_decode(db_stdClass::db_stripTagsJson($oParam->pessoa->z01_compl))));
    }
    if (isset($oParam->pessoa->z01_telef)) { 
      $oCgm->setTelefone($oParam->pessoa->z01_telef);
    }
    if (isset($oParam->pessoa->z01_email)) {   
      $oCgm->setEmail((utf8_decode(db_stdClass::db_stripTagsJson($oParam->pessoa->z01_email))));
    }
    if (isset($oParam->pessoa->z01_cpf)) {
      
      if ($oCgm->isFisico()) {
        
        $oCgm->setCpf($oParam->pessoa->z01_cpf);
        //Nova Validação devido a criação dos nóvos campos
        if (isset($oParam->pessoa->z01_incest)) {
          $oCgm->setInscricaoEstadual($oParam->pessoa->z01_incest);
        }
      } else {
        $oCgm->setCnpj($oParam->pessoa->z01_cpf);
      }
    }
    //Nova Validação devido a criação dos nóvos campos
    if (isset($oParam->pessoa->z01_fax)) {
      $oCgm->setFax($oParam->pessoa->z01_fax);  
    }
    //Nova Validação devido a criação dos nóvos campos
    if (isset($oParam->pessoa->z01_cxpostal)) {
      $oCgm->setCaixaPostal($oParam->pessoa->z01_cxpostal);
    }
    //Nova Validação devido a criação dos nóvos campos
    if (isset($oParam->pessoa->z01_cxposcon)) {
      $oCgm->setCaixaPostalComercial($oParam->pessoa->z01_cxposcon);
    }
    if (isset($oParam->pessoa->z01_ident)) {
      $oCgm->setIdentidade($oParam->pessoa->z01_ident);
    }
    if (isset($oParam->pessoa->z01_munic)) { 
      $oCgm->setMunicipio($oParam->pessoa->z01_munic);
    }
    if (isset($oParam->pessoa->z01_bairro)) { 
      $oCgm->setBairro((utf8_decode(db_stdClass::db_stripTagsJson($oParam->pessoa->z01_bairro))));
    }
    if (isset($oParam->pessoa->z01_cep)) {
      $oCgm->setCep($oParam->pessoa->z01_cep);
    }
    if (isset($oParam->pessoa->z01_uf)) {   
      $oCgm->setUf($oParam->pessoa->z01_uf);
    }

    try {
        
      $oCgm->save();
     
      $oDaoCidado = db_utils::getDao("cidadao");
      $oDaoCidado->ov02_situacaocidadao = 1;
      $oDaoCidado->alterar_where($oParam->pessoa->ov02_sequencial,
                                 $oParam->pessoa->ov02_seq,
                                 "ov02_sequencial = ".$oParam->pessoa->ov02_sequencial." and 
                                 ov02_seq = ".$oParam->pessoa->ov02_seq
                                  );
      if ($oDaoCidado->erro_status == 0) {
        throw new Exception($oDaoCidado->erro_msg);           
      }      
      
      $oRetorno->message = urlencode("usuario:\\n\\n Cgm alterado com sucesso {".$oCgm->getCodigo()."}\\n\\n");
       
      db_fim_transacao(false);
        
    } catch (Exception $erro) {
        
      db_fim_transacao(true);
      $oRetorno->status = 2;
      $oRetorno->message = urlencode($erro->getMessage());    
    }
    
    echo $oJson->encode($oRetorno);
    break;
    
  case 'excluir' :
    
    db_inicio_transacao();
    
    
    try {
          
        $oCgm = CgmFactory::getInstanceByCgm($oParam->z01_numcgm);
        $oCgm->exclui();
        db_fim_transacao(false);
                         
        $oRetorno->message = urlencode("usuario:\\n\\n Cgm excluído com sucesso {".$oCgm->getCodigo()."}\\n\\n");
        $oRetorno->status = 1;
          
    } catch (Exception $erro) {
          
        db_fim_transacao(true);
        $oRetorno->status = 2;
        $oRetorno->message = urlencode($erro->getMessage());    
    }
    
    echo $oJson->encode($oRetorno);
    break;
    
  case 'adicionarFoto':
    
    $oCgm = CgmFactory::getInstanceByCgm($oParam->iCgm);
    try {
      
      db_inicio_transacao();
      $oCgm->adicionarFoto($oParam->arquivo, $oParam->principal, $oParam->ativa);
      $oRetorno->status = 1;
      unlink($oParam->arquivo);
      db_fim_transacao(false);
    } catch (Exception $eErro) {
      
      db_fim_transacao(true);
      $oRetorno->status = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
    }
    echo $oJson->encode($oRetorno);
    break;
    
  case 'getFotos':
    
    $oCgm   = CgmFactory::getInstanceByCgm($oParam->iCgm);
    $aFotos = $oCgm->getFotos();
    $oRetorno->itens = $aFotos;
    echo $oJson->encode($oRetorno);
    break;
    
  case 'excluirFoto':
    
    $oCgm = CgmFactory::getInstanceByCgm($oParam->iCgm);
    try {
      
      db_inicio_transacao();
      $oCgm->excluirFoto($oParam->iFoto);
      $oRetorno->status = 1;
      db_fim_transacao(false);
    } catch (Exception $eErro) {
      
      db_fim_transacao(true);
      $oRetorno->status = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
    }
    echo $oJson->encode($oRetorno);
    break ;
    
  case 'alterarFoto':
    
    $oCgm = CgmFactory::getInstanceByCgm($oParam->iCgm);
    try {
      
      db_inicio_transacao();
      $oCgm->alterarFoto($oParam->iFoto, $oParam->lPrincipal, $oParam->lAtiva);
      $oRetorno->status = 1;
      db_fim_transacao(false);
    } catch (Exception $eErro) {
      
      db_fim_transacao(true);
      $oRetorno->status = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
    }
    echo $oJson->encode($oRetorno);
    break ;
  
}

?>