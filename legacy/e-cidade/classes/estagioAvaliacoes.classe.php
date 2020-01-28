<?
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

class estagioAvaliacao {
  
  function estagioAvaliacao($iCodAvaliacao,$setSession=true){

     if (!class_exists("rhestagioagendadata")){
         require_once "classes/db_rhestagioagendadata_classe.php";
     }
     if (!class_exists("db_utils")){
         require_once "libs/db_utils.php";
     }
     $campos = "*,nomeavaliador.z01_nome as nomeavaliador";
     $this->agendaData        = new cl_rhestagioagendadata(); 
     $this->agendaData->dados = null;
     $rAgendaData             = $this->agendaData->sql_record($this->agendaData->sql_query_nome($iCodAvaliacao,$campos));
     $this->agendaData->dados = db_utils::fieldsMemory($rAgendaData,0);
     $this->codEstagio        = $this->agendaData->dados->h57_rhestagio;
     $this->codAvaliacao      = $iCodAvaliacao;
     if (!isset($_SESSION["avaliacao"]) and $setSession){
         $this->gravarSessao();
     }

  }

  function setCodigoAvaliacao($iCodAvaliacao){
     $this->iCodAvaliacao = $iCodAvaliacao;
  }

  function getQuesitos($iCodQuesito=null,$limit=null){

     if (!class_exists("rhestagioquesito")){
        
        require_once "classes/db_rhestagioquesito_classe.php";
     }
     $sFiltroQuesito   = null;
     if ($iCodQuesito != ''){
        
        if ($iCodQuesito != ''){
           $sFiltroQuesito = " and h51_sequencial = {$iCodQuesito}";
        }
     }
     $this->estagioQuesitos = new cl_rhestagioquesito();
     $this->rQuesitos       = $this->estagioQuesitos->sql_record($this->estagioQuesitos->sql_query(null,"*",
                                                                "h51_sequencial $limit",
                                                                "h51_rhestagio = ".$this->getCodEstagio()
                                                                ."$sFiltroQuesito")); 
     $this->iTotquesitos = $this->estagioQuesitos->numrows;
     return $this->rQuesitos;
  }

  function setCodEstagio($iEstagio){
     $this->codEstagio = $iEstagio;
  }

  function getCodEstagio(){
     return $this->codEstagio;
  }

  function getQuestoesQuesito($iCodQuesito){

     if (!class_exists("rhestagioquesitopergunta")){
        require_once "classes/db_rhestagioquesitopergunta_classe.php";
     }
     $this->estagioQuestoes = new cl_rhestagioquesitopergunta();
     $this->rEstagioQuestao = $this->estagioQuestoes->sql_record($this->estagioQuestoes->sql_query(null,"*","h53_sequencial",
                                                      "h53_rhestagioquesito=$iCodQuesito"));
     $this->iTotQuestoes    =  $this->estagioQuestoes->numrows;
     return $this->rEstagioQuestao;
  }
  
  function getQuestaoRespostas($iCodQuestao){

     if (!class_exists("rhestagioquesitoresposta")){

        require_once "classes/db_rhestagioquesitoresposta_classe.php";
     }
     $this->estagioResposta = new cl_rhestagioquesitoresposta();
     $this->rEstagioResposta = $this->estagioResposta->sql_record($this->estagioResposta->sql_query(null,"*","h54_sequencial",
                                                      "h54_rhestagioquesitopergunta=$iCodQuestao"));
//     echo $this->estagioResposta->sql_query(null,"*","h54_sequencial",
//                                                           "h54_rhestagioquesitopergunta=$iCodQuestao")."<br>";
     $this->iTotRespostas  = $this->estagioResposta->numrows; 
     return $this->rEstagioResposta;
  }

  function getDadosExame($iCodQuesito = null){
      
      if (!class_exists("services_json")){
         require_once "libs/JSON.php";
      }
      $objJson = new services_JSON();
      $strJson["status"]        = 1;
      $strJson["h57_regist"]    = $this->agendaData->dados->h57_regist;
      $strJson["z01_nome"]      = urlencode($this->agendaData->dados->z01_nome);
      $strJson["h64_data"]      = db_formatar($this->agendaData->dados->h64_data,"d");
      $strJson["h50_confobs"]   = $this->agendaData->dados->h50_confobs;
      $strJson["nomeavaliador"] = urlencode($this->agendaData->dados->nomeavaliador);
      $strJson["h56_avaliador"] = $this->agendaData->dados->h56_avaliador;
      $strJson["h56_comissao"]  = $this->agendaData->dados->h56_rhestagiocomissao;
      $strJson["h59_descr"]     = urlencode($this->agendaData->dados->h59_descr);
      if ($iCodQuesito == null){

         $this->getQuesitos(null);
         $strJson["numquesitos"] = $this->iTotquesitos;
         

      }else{
         $this->getQuesitos($iCodQuesito);
         $strJson["numquesitos"] = $this->iTotquesitos;
      }
      if ($this->rQuesitos){

        for ($i = 0; $i < $this->iTotquesitos; $i++){
          
          $sObsQuesito       = '';
          $sRecQuesito       = '';
          $iTotRespostasDada = 0;
          $oQuesitos   = db_utils::fieldsMemory($this->rQuesitos,$i);
          if (isset($_SESSION["avaliacao"]["obs"][$oQuesitos->h51_sequencial])){
             $sObsQuesito = urlencode($_SESSION["avaliacao"]["obs"][$oQuesitos->h51_sequencial]["obs"]);
             $sRecQuesito = urlencode($_SESSION["avaliacao"]["obs"][$oQuesitos->h51_sequencial]["rec"]);
          }
          $strJson["quesitos"][$i]["h51_sequencial"] = $oQuesitos->h51_sequencial;
          $strJson["quesitos"][$i]["h51_descr"]      = urlencode($oQuesitos->h51_descr);
          $strJson["quesitos"][$i]["obs"]            = $sObsQuesito;
          $strJson["quesitos"][$i]["rec"]            = $sRecQuesito;
          $strJson["quesitos"][$i]["respostaDadas"]  = 0;
         if ($this->getQuestoesQuesito($oQuesitos->h51_sequencial)){

            for ($j = 0; $j < $this->iTotQuestoes; $j++){
              
              $oQuestoes = db_utils::fieldsMemory($this->rEstagioQuestao,$j);
              $strJson["quesitos"][$i]["questoes"][$j]["h53_sequencial"] = $oQuestoes->h53_sequencial;
              $strJson["quesitos"][$i]["questoes"][$j]["h53_descr"]      = urlencode($oQuestoes->h53_descr);
              $strJson["quesitos"][$i]["questoes"][$j]["numrespostas"]   = 0;
              $strJson["quesitos"][$i]["questoes"][$j]["respostadada"]   = '';
              $strJson["quesitos"][$i]["questoes"][$j]["obsquestao"]     = '';
              $strJson["quesitos"][$i]["questoes"][$j]["obsrec"]         = '';
              if (isset($_SESSION["avaliacao"]["questoes"][$oQuestoes->h53_sequencial])){
                 $strJson["quesitos"][$i]["questoes"][$j]["respostadada"] = $_SESSION["avaliacao"]["questoes"][$oQuestoes->h53_sequencial];
                 $iTotRespostasDada++;
              }
              if (isset($_SESSION["avaliacao"]["obsquestoes"][$oQuestoes->h53_sequencial])){
                 $strJson["quesitos"][$i]["questoes"][$j]["obsquestao"] = urlencode($_SESSION["avaliacao"]["obsquestoes"][$oQuestoes->h53_sequencial]["obs"]);
                 $strJson["quesitos"][$i]["questoes"][$j]["obsrec"]     = urlencode($_SESSION["avaliacao"]["obsquestoes"][$oQuestoes->h53_sequencial]["rec"]);
              }
              if ($this->getQuestaoRespostas($oQuestoes->h53_sequencial)){
                 $strJson["quesitos"][$i]["questoes"][$j]["numrespostas"]   = $this->iTotRespostas;
                 for ($k = 0; $k < $this->iTotRespostas; $k++){
                    $oRespostas = db_utils::fieldsMemory($this->rEstagioResposta,$k);
                    $strJson["quesitos"][$i]["questoes"][$j]["respostas"][] = array(
                                                                    "h54_sequencial" => $oRespostas->h54_sequencial,
                                                                    "h54_descr"      => urlencode($oRespostas->h54_descr),
                                                                    "h52_pontos"     => urlencode($oRespostas->h52_pontos),
                                                                    "h52_descr"      => urlencode($oRespostas->h52_descr)
                                                                   );
                 
                 }
              }
            }
          }
          $strJson["quesitos"][$i]["respostaDadas"] = $iTotRespostasDada;
        }
      }
      return $objJson->encode($strJson);
   }

   function getQuesitosExame(){

      $this->getQuesitos();
      if ($this->rQuesitos){

        for ($i = 0; $i < $this->iTotquesitos; $i++){
          $totalRespostas     = 0;
          $oQuesitos          = db_utils::fieldsMemory($this->rQuesitos,$i);
          $this->getQuestoesQuesito($oQuesitos->h51_sequencial,$i);
          $sSQLTot   = " select coalesce(count(*),0) as total";
          $sSQLTot  .= "   from rhestagioavaliacao";
          $sSQLTot  .= "        inner join rhestagioavaliacaoresposta on h56_sequencial               = h58_rhestagioavaliacao";
          $sSQLTot  .= "        inner join rhestagioquesitoresposta   on h58_rhestagioquesitoresposta = h54_sequencial ";
          $sSQLTot  .= "        inner join rhestagioquesitopergunta   on h54_rhestagioquesitopergunta = h53_sequencial ";
          $sSQLTot  .= "  where h56_rhestagioagenda  = {$this->codAvaliacao}";
          $sSQLTot  .= "    and h53_rhestagioquesito = {$oQuesitos->h51_sequencial}";
          $rsTot     = pg_query($sSQLTot);
          $oTot      = db_utils::fieldsMemory($rsTot,0);
          $totalResp = 0;
          if ($oTot->total == $this->iTotQuestoes){
             $totalResp = 1;
          }
          $strJson["quesitos"][] = array (
                                          "h51_sequencial" => $oQuesitos->h51_sequencial,
                                          "h51_descr"      => urlencode($oQuesitos->h51_descr),  
                                          "totalresp"      => $totalResp
                                         );
        }
      }
      if (!class_exists("services_json")){
         require_once "libs/JSON.php";
      }
      $objJson = new services_JSON();
      return $objJson->encode($strJson);
   }

   function salvarResposta($iQuestao,$iResposta,$iTipo,$sRecomendacao='',$sObservacao=''){

     if (isset($_SESSION["avaliacao"])){
       //echo $iQuestao ." => ".$iResposta;
         switch ($iTipo){
          
          case 1://resposta dada para a questao
             $_SESSION["avaliacao"]["questoes"]["$iQuestao"] = $iResposta;
             break;   
          case 2: //obs da questao
             $_SESSION["avaliacao"]["obsquestoes"]["$iQuestao"] = array (
                                                                         "obs" => urldecode(trim($sObservacao)),
                                                                         "rec" => urldecode(trim($sRecomendacao)) 
                                                                        );
             break;   
          case 3: //Obs do quesito 
             $_SESSION["avaliacao"]["obs"]["$iQuestao"] = array(
                                                               "obs" => urldecode(trim($sObservacao)),
                                                               "rec" => urldecode(trim($sRecomendacao))
                                                               );
             break;   
           
         }
      }
   }
   function cancelarExame(){
      unset($_SESSION["avaliacao"]);
   }

   function gravarSessao(){

     unset($_SESSION["avaliacao"]) ;
     $_SESSION["avaliacao"]["questoes"] = array();
     if (!class_exists("rhestagioavaliacao")){
        require_once("classes/db_rhestagioavaliacao_classe.php");
     }
     $this->estagioAvaliacao  = new cl_rhestagioavaliacao();
     $this->rEstagioAvaliacao = $this->estagioAvaliacao->sql_record(
                                                $this->estagioAvaliacao->sql_query(null,"*",null,
                                                "h56_rhestagioagenda = {$this->codAvaliacao}"));
     if ($this->estagioAvaliacao->numrows > 0){

        
       if (!class_exists("rhestagioavaliacaoresposta")){
          require_once("classes/db_rhestagioavaliacaoresposta_classe.php");
       }
       $this->avaliacaoResposta = new  cl_rhestagioavaliacaoresposta();
       if (!class_exists("rhestagioavaliacaoobs")){
          require_once("classes/db_rhestagioavaliacaoobs_classe.php");
       }
       $this->avaliacaoObs = new  cl_rhestagioavaliacaoObs();
       for ($i = 0; $i < $this->estagioAvaliacao->numrows; $i++){
           
         $oEstagioAvaliacao   = db_utils::fieldsMemory($this->rEstagioAvaliacao,$i);
         $rAvaliacaorespostas = $this->avaliacaoResposta->sql_record(
                                   $this->avaliacaoResposta->sql_query(null,"h53_sequencial,h58_rhestagioquesitoresposta",null,
                                                                      "h58_rhestagioavaliacao = {$oEstagioAvaliacao->h56_sequencial}"
                                                                      ));
         //echo $this->avaliacaoResposta->sql_query(null,"*",null, "h58_rhestagioavaliacao = {$oEstagioAvaliacao->h56_sequencial}");
         //exit;
         for ($j = 0;$j < $this->avaliacaoResposta->numrows; $j++){

            $oAvaliacaoResposta  = db_utils::fieldsMemory($rAvaliacaorespostas,$j);
            if ($oAvaliacaoResposta->h53_sequencial != ''){
               $_SESSION["avaliacao"]["questoes"][$oAvaliacaoResposta->h53_sequencial] = $oAvaliacaoResposta->h58_rhestagioquesitoresposta;
            }
         }
         
         $rAvaliacaoobs  = $this->avaliacaoObs->sql_record(
                           $this->avaliacaoObs->sql_querytipo(null,"*",null,
                                                                    "h61_rhestagioavaliacao = {$oEstagioAvaliacao->h56_sequencial}"
                                                                      ));
         //echo $this->avaliacaoObs->sql_querytipo(null,"*",null, "h61_rhestagioavaliacao = {$oEstagioAvaliacao->h56_sequencial}");
         //exit;
         for ($j = 0;$j < $this->avaliacaoObs->numrows; $j++){

            $oAvaliacaoObs  = db_utils::fieldsMemory($rAvaliacaoobs,$j);
            if ($oAvaliacaoObs->h63_sequencial != ''){
               $_SESSION["avaliacao"]["obs"][$oAvaliacaoObs->h63_rhestagioquesito]["obs"] = $oAvaliacaoObs->h61_observacoes;
               $_SESSION["avaliacao"]["obs"][$oAvaliacaoObs->h63_rhestagioquesito]["rec"] = $oAvaliacaoObs->h61_recomendacoes;
            }else if ($oAvaliacaoObs->h62_sequencial != ''){
               $_SESSION["avaliacao"]["obsquestoes"][$oAvaliacaoObs->h62_rhestagioquesitopergunta]["obs"] = $oAvaliacaoObs->h61_observacoes;
               $_SESSION["avaliacao"]["obsquestoes"][$oAvaliacaoObs->h62_rhestagioquesitopergunta]["rec"] = $oAvaliacaoObs->h61_recomendacoes;
                                                                                               
            }
         }
       }
     }
   }

  /*
   ** salva os dados o exame no banco de dados
   ** @params array $aPars array com parametros para inclusao.
  */ 
   function salvarExame($aPars){

     if (!class_exists("rhestagioavaliacao")){
        require_once("classes/db_rhestagioavaliacao_classe.php");
     }
     $this->estagioAvaliacao  = new cl_rhestagioavaliacao();
     if (!class_exists("rhestagioavaliacaoresposta")){
        require_once("classes/db_rhestagioavaliacaoresposta_classe.php");
     }
     $this->avaliacaoResposta = new  cl_rhestagioavaliacaoresposta();
     if (!class_exists("rhestagioavaliacaoobs")){
       require_once("classes/db_rhestagioavaliacaoobs_classe.php");
     }
     $this->avaliacaoObs = new  cl_rhestagioavaliacaoobs();
     if (!class_exists("rhestagioavaliacaoobspergunta")){
        require_once("classes/db_rhestagioavaliacaoobspergunta_classe.php");
     }
     $this->avaliacaoObsPergunta = new  cl_rhestagioavaliacaoobspergunta();
     if (!class_exists("rhestagioavaliacaoobsquesito")){
        require_once("classes/db_rhestagioavaliacaoobsquesito_classe.php");
     }
     $this->avaliacaoObsQuesito = new  cl_rhestagioavaliacaoobsquesito();
     $this->lSqlErro = false;
     $this->sErroMsg = null;
     if (!is_array($aPars)){

       $this->lSqlErro = true;
       $this->sErroMsg = "parametro [1] não é um array valido"; 
       return false;
     }
     if (!$this->lSqlErro){
        db_inicio_transacao(); 
        $this->excluirAvaliacao();
        $this->estagioAvaliacao->h56_rhestagiocomissao = $aPars["h56_rhestagiocomissao"];
        $this->estagioAvaliacao->h56_rhestagioagenda   = $this->codAvaliacao;
        $this->estagioAvaliacao->h56_data              = $this->agendaData->dados->h64_data;
        $this->estagioAvaliacao->h56_avaliador         = $aPars["h56_avaliador"];
        $this->estagioAvaliacao->incluir(null);
        if ($this->estagioAvaliacao->erro_status == 0){

           $this->lSqlErro = true;
           $this->sErroMsg = "Erro [1]:Nao foi possivel incluir Avaliaçao:\n{$this->estagioAvaliacao->erro_msg}"; 
        }
        if (!$this->lSqlErro){
           $aQuestoes = $_SESSION["avaliacao"]["questoes"];
           if (count($aQuestoes) == 0){
           
              $this->lSqlErro = true;
              $this->sErroMsg = "Erro [2]:Nenhuma Questão foi preenchida.Avaliação não sera salva"; 
           }
           if (!$this->lSqlErro){
            
            foreach ($aQuestoes as $key => $value){

               $this->avaliacaoResposta->h58_rhestagioquesitoresposta = $value;
               $this->avaliacaoResposta->h58_rhestagioavaliacao       = $this->estagioAvaliacao->h56_sequencial;
               $this->avaliacaoResposta->incluir(null);
               if ($this->avaliacaoResposta->erro_status == 0){
                  
                  $this->lSqlErro = true;
                  $this->sErroMsg = "Erro [3]:Não foi possivel cadastrar resposta[$value]\n{$this->avaliacaoResposta->erro_msg}"; 
               }
            }
          }
        }
       //inclusao das observaçoes
       if (!$this->lSqlErro){
          if (isset($_SESSION["avaliacao"]["obsquestoes"])){
             $aQuestoes = $_SESSION["avaliacao"]["obsquestoes"];
             foreach ($aQuestoes as $key => $value){
               
               $this->avaliacaoObs->h61_rhestagioavaliacao = $this->estagioAvaliacao->h56_sequencial;
               $this->avaliacaoObs->h61_tipo               = $this->agendaData->dados->h50_confobs;
               $this->avaliacaoObs->h61_observacoes        = $value["obs"];
               $this->avaliacaoObs->h61_recomendacoes      = $value["rec"];
               $this->avaliacaoObs->incluir(null);
               if ($this->avaliacaoObs->erro_status != 0){

                  $this->avaliacaoObsPergunta->h62_rhestagioquesitopergunta = $key;
                  $this->avaliacaoObsPergunta->h62_rhestagioavaliacaoobs    = $this->avaliacaoObs->h61_sequencial;
                  $this->avaliacaoObsPergunta->incluir(null);
                  if ($this->avaliacaoObsPergunta->erro_status == 0){

                     $this->lSqlErro = true;
                     $this->sErroMsg = "erro[4]:\n{$this->avaliacaoObsPergunta->erro_msg}";
                  }      
               }
             }
          }
        }
        if (!$this->lSqlErro){
           if (isset($_SESSION["avaliacao"]["obs"])){
              
              $aQuestoes = $_SESSION["avaliacao"]["obs"];
              foreach ($aQuestoes as $key => $value){
                
               $this->avaliacaoObs->h61_rhestagioavaliacao = $this->estagioAvaliacao->h56_sequencial;
               $this->avaliacaoObs->h61_tipo               = $this->agendaData->dados->h50_confobs;
               $this->avaliacaoObs->h61_observacoes        = $value["obs"];
               $this->avaliacaoObs->h61_recomendacoes      = $value["rec"];
               $this->avaliacaoObs->incluir(null);
               if ($this->avaliacaoObs->erro_status != 0){

                  $this->avaliacaoObsQuesito->h63_rhestagioquesito = $key;
                  $this->avaliacaoObsQuesito->h63_rhestagioavaliacaoobs    = $this->avaliacaoObs->h61_sequencial;
                  $this->avaliacaoObsQuesito->incluir(null);
                  if ($this->avaliacaoObsQuesito->erro_status == 0){

                     $this->lSqlErro = true;
                     $this->sErroMsg = "erro[5]:\n{$this->avaliacaoObsQuesito->erro_msg}";
                      
                   }
                }
              }
            }
         }
         db_fim_transacao($this->lSqlErro);
         //db_fim_transacao(true);
         if ($this->lSqlErro){
            $retorno = array("retorno" => 0,"mensagem" =>urlencode($this->sErroMsg),"pesquisar" => 0);
         }else{
            $retorno = array("retorno" => 1,"mensagem" => "Avaliacao Salva com Sucesso.", "pesquisar" => 1);
         }
      }
      return $this->array2json($retorno);
   }
  /*
   ** Função para converter um array numa string json.
   ** @param $array array para ser convertida
  */ 
   function array2json($array){

      if (!class_exists("services_json")){
         require_once "libs/JSON.php";
      }
      $objJson = new services_JSON();
      return $objJson->encode($array);
   }

   /*
   **
   */

   function excluirAvaliacao(){
    
     $this->lSqlErro     = false;
     $SQLAvaliacao  = "select h56_sequencial";
     $SQLAvaliacao .= "  from rhestagioavaliacao";
     $SQLAvaliacao .= " where h56_rhestagioagenda = {$this->codAvaliacao}";
     $rAvaliacao   = pg_query($SQLAvaliacao);
     if (pg_num_rows($rAvaliacao) > 0){
       $oAvaliacao  = db_utils::fieldsMemory($rAvaliacao,0);
       $sDeleteObs  = "delete ";
       $sDeleteObs .= "  from rhestagioavaliacaoobspergunta ";
       $sDeleteObs .= " using rhestagioavaliacaoobs";
       $sDeleteObs .= " where h61_rhestagioavaliacao = {$oAvaliacao->h56_sequencial}";
       if (!pg_query($sDeleteObs)){
           $this->lSqlErro = true;
           $this->sErroMsg = "Erro[6]: Nao foi possivel Excluir Avaliacão:\n".pg_last_Error();
       }
       //deletando da 
       if (!$this->lSqlErro){

          $sDeleteObs  = "delete ";
          $sDeleteObs .= "  from rhestagioavaliacaoobsquesito";
          $sDeleteObs .= " using rhestagioavaliacaoobs";
          $sDeleteObs .= " where h61_rhestagioavaliacao = {$oAvaliacao->h56_sequencial}";
          if (!@pg_query($sDeleteObs)){
             $this->lSqlErro = true;
             $this->sErroMsg = "Erro[7]: Nao foi possivel Excluir Avaliacão:\n".pg_last_Error();
          }
        }
        if (!$this->lSqlErro){
           $sDeleteObs  = "delete ";
           $sDeleteObs .= "  from rhestagioavaliacaoobs ";
           $sDeleteObs .= " where h61_rhestagioavaliacao = {$oAvaliacao->h56_sequencial}";
           if (!@pg_query($sDeleteObs)){
              $this->lSqlErro = true;
              $this->sErroMsg = "Erro[8]: Nao foi possivel Excluir Avaliacão:\n".pg_last_Error();
          }
        }
        if (!$this->lSqlErro){
           $sDeleteperg  = "delete ";
           $sDeleteperg .= "  from rhestagioavaliacaoresposta ";
           $sDeleteperg .= " where h58_rhestagioavaliacao = {$oAvaliacao->h56_sequencial}";
           if (!@pg_query($sDeleteperg)){
              $this->lSqlErro = true;
              $this->sErroMsg = "Erro[9]: Nao foi possivel Excluir Avaliacão:\n".pg_last_Error();
          }
        }
        if (!$this->lSqlErro){
           $sDeleteaval  = "delete ";
           $sDeleteaval .= "  from rhestagioavaliacao ";
           $sDeleteaval .= " where h56_sequencial = {$oAvaliacao->h56_sequencial}";
           if (!pg_query($sDeleteaval)){
              $this->lSqlErro = true;
              $this->sErroMsg = "Erro[10]: Nao foi possivel Excluir Avaliacão:\n".pg_last_Error();
          }
        }
     }
   }

   function getRespostaQuestao($iCodQuestao){

     if (isset($_SESSION["avaliacao"]["questoes"][$iCodQuestao])){
         return $_SESSION["avaliacao"]["questoes"][$iCodQuestao];
     }
   }

   function getObsQuestao($iCodQuestao, $sTipo ){

     if (isset($_SESSION["avaliacao"]["obsquestoes"][$iCodQuestao][$sTipo])){
         return $_SESSION["avaliacao"]["obsquestoes"][$iCodQuestao][$sTipo];
     }
   }

   function getObsQuesito($iCodQuesito, $sTipo ){

     if (isset($_SESSION["avaliacao"]["obs"][$iCodQuesito][$sTipo])){
         return $_SESSION["avaliacao"]["obs"][$iCodQuesito][$sTipo];
     }
   }
   function getPresidenteComissao(){
     
     if (!class_exists("rhestagioavaliacao")){
         require_once "classes/db_rhestagioavaliacao_classe.php";
     }
     $this->oEstagioAvaliacao = new cl_rhestagioavaliacao();
     $rAvaliacao =  $this->oEstagioAvaliacao->sql_record($this->oEstagioAvaliacao->sql_query_comissao(null,"distinct z01_nome",null, 
                                                         "h56_rhestagioagenda = ".$this->codAvaliacao." and
                                                          h60_tipo = 1" ));
    if ($this->oEstagioAvaliacao->numrows > 0){

         $oAvaliacao = db_utils::fieldsMemory($rAvaliacao,0);
         return $oAvaliacao->z01_nome;

    }
   }

}