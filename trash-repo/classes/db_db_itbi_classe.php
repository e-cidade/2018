<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

//MODULO: prefeitura
//CLASSE DA ENTIDADE db_itbi
class cl_db_itbi { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $matricula = 0; 
   var $areaterreno = 0; 
   var $areaedificada = 0; 
   var $nomecomprador = null; 
   var $cgccpfcomprador = null; 
   var $enderecocomprador = null; 
   var $municipiocomprador = null; 
   var $bairrocomprador = null; 
   var $cepcomprador = null; 
   var $ufcomprador = null; 
   var $tipotransacao = null; 
   var $valortransacao = 0; 
   var $caracteristicas = null; 
   var $mfrente = 0; 
   var $mladodireito = 0; 
   var $mfundos = 0; 
   var $mladoesquerdo = 0; 
   var $email = null; 
   var $obs = null; 
   var $liberado = 0; 
   var $datavencimento_dia = null; 
   var $datavencimento_mes = null; 
   var $datavencimento_ano = null; 
   var $datavencimento = null; 
   var $aliquota = 0; 
   var $id_itbi = 0; 
   var $dataliber_dia = null; 
   var $dataliber_mes = null; 
   var $dataliber_ano = null; 
   var $dataliber = null; 
   var $valoravaliacao = 0; 
   var $valorpagamento = 0; 
   var $obsliber = null; 
   var $loginn = null; 
   var $numpre = null; 
   var $datasolicitacao_dia = null; 
   var $datasolicitacao_mes = null; 
   var $datasolicitacao_ano = null; 
   var $datasolicitacao = null; 
   var $libpref = 0; 
   var $valoravterr = 0; 
   var $valoravconst = 0; 
   var $numerocomprador = 0; 
   var $complcomprador = null; 
   var $cxpostcomprador = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 matricula = int4 = Matrícula 
                 areaterreno = float8 = Área terreno 
                 areaedificada = float8 = Área edificada 
                 nomecomprador = varchar(40) = Comprador 
                 cgccpfcomprador = varchar(14) = CGC/CPF 
                 enderecocomprador = varchar(40) = Endereço 
                 municipiocomprador = varchar(20) = Município 
                 bairrocomprador = varchar(20) = Bairro 
                 cepcomprador = char(8) = CEP 
                 ufcomprador = char(2) = UF 
                 tipotransacao = varchar(20) = Transação 
                 valortransacao = float8 = Valor 
                 caracteristicas = varchar(20) = Características 
                 mfrente = float8 = Frente 
                 mladodireito = float8 = Direito 
                 mfundos = float8 = Fundos 
                 mladoesquerdo = float8 = Esquerdo 
                 email = varchar(50) = email 
                 obs = text = Observação 
                 liberado = int4 = Liberado 
                 datavencimento = date = Data 
                 aliquota = float8 = Aliquota 
                 id_itbi = int4 = Guia 
                 dataliber = date = Data Liber 
                 valoravaliacao = float8 = Valor avaliado 
                 valorpagamento = float8 = Valor pago 
                 obsliber = text = Obs Liberação 
                 loginn = char(10) = Login 
                 numpre = char(15) = Nº arrecadação 
                 datasolicitacao = date = Data solic 
                 libpref = float8 = Liberação da Prefeitura 
                 valoravterr = float8 = Valor Avaliação Terreno 
                 valoravconst = float8 = Valor Avaliação Prédio 
                 numerocomprador = int4 = Número 
                 complcomprador = varchar(20) = Complemento 
                 cxpostcomprador = varchar(20) = Caixa Postal 
                 ";
   //funcao construtor da classe 
   function cl_db_itbi() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_itbi"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
   function erro($mostra,$retorna) { 
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->matricula = ($this->matricula == ""?@$GLOBALS["HTTP_POST_VARS"]["matricula"]:$this->matricula);
       $this->areaterreno = ($this->areaterreno == ""?@$GLOBALS["HTTP_POST_VARS"]["areaterreno"]:$this->areaterreno);
       $this->areaedificada = ($this->areaedificada == ""?@$GLOBALS["HTTP_POST_VARS"]["areaedificada"]:$this->areaedificada);
       $this->nomecomprador = ($this->nomecomprador == ""?@$GLOBALS["HTTP_POST_VARS"]["nomecomprador"]:$this->nomecomprador);
       $this->cgccpfcomprador = ($this->cgccpfcomprador == ""?@$GLOBALS["HTTP_POST_VARS"]["cgccpfcomprador"]:$this->cgccpfcomprador);
       $this->enderecocomprador = ($this->enderecocomprador == ""?@$GLOBALS["HTTP_POST_VARS"]["enderecocomprador"]:$this->enderecocomprador);
       $this->municipiocomprador = ($this->municipiocomprador == ""?@$GLOBALS["HTTP_POST_VARS"]["municipiocomprador"]:$this->municipiocomprador);
       $this->bairrocomprador = ($this->bairrocomprador == ""?@$GLOBALS["HTTP_POST_VARS"]["bairrocomprador"]:$this->bairrocomprador);
       $this->cepcomprador = ($this->cepcomprador == ""?@$GLOBALS["HTTP_POST_VARS"]["cepcomprador"]:$this->cepcomprador);
       $this->ufcomprador = ($this->ufcomprador == ""?@$GLOBALS["HTTP_POST_VARS"]["ufcomprador"]:$this->ufcomprador);
       $this->tipotransacao = ($this->tipotransacao == ""?@$GLOBALS["HTTP_POST_VARS"]["tipotransacao"]:$this->tipotransacao);
       $this->valortransacao = ($this->valortransacao == ""?@$GLOBALS["HTTP_POST_VARS"]["valortransacao"]:$this->valortransacao);
       $this->caracteristicas = ($this->caracteristicas == ""?@$GLOBALS["HTTP_POST_VARS"]["caracteristicas"]:$this->caracteristicas);
       $this->mfrente = ($this->mfrente == ""?@$GLOBALS["HTTP_POST_VARS"]["mfrente"]:$this->mfrente);
       $this->mladodireito = ($this->mladodireito == ""?@$GLOBALS["HTTP_POST_VARS"]["mladodireito"]:$this->mladodireito);
       $this->mfundos = ($this->mfundos == ""?@$GLOBALS["HTTP_POST_VARS"]["mfundos"]:$this->mfundos);
       $this->mladoesquerdo = ($this->mladoesquerdo == ""?@$GLOBALS["HTTP_POST_VARS"]["mladoesquerdo"]:$this->mladoesquerdo);
       $this->email = ($this->email == ""?@$GLOBALS["HTTP_POST_VARS"]["email"]:$this->email);
       $this->obs = ($this->obs == ""?@$GLOBALS["HTTP_POST_VARS"]["obs"]:$this->obs);
       $this->liberado = ($this->liberado == ""?@$GLOBALS["HTTP_POST_VARS"]["liberado"]:$this->liberado);
       if($this->datavencimento == ""){
         $this->datavencimento_dia = ($this->datavencimento_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["datavencimento_dia"]:$this->datavencimento_dia);
         $this->datavencimento_mes = ($this->datavencimento_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["datavencimento_mes"]:$this->datavencimento_mes);
         $this->datavencimento_ano = ($this->datavencimento_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["datavencimento_ano"]:$this->datavencimento_ano);
         if($this->datavencimento_dia != ""){
            $this->datavencimento = $this->datavencimento_ano."-".$this->datavencimento_mes."-".$this->datavencimento_dia;
         }
       }
       $this->aliquota = ($this->aliquota == ""?@$GLOBALS["HTTP_POST_VARS"]["aliquota"]:$this->aliquota);
       $this->id_itbi = ($this->id_itbi == ""?@$GLOBALS["HTTP_POST_VARS"]["id_itbi"]:$this->id_itbi);
       if($this->dataliber == ""){
         $this->dataliber_dia = ($this->dataliber_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["dataliber_dia"]:$this->dataliber_dia);
         $this->dataliber_mes = ($this->dataliber_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["dataliber_mes"]:$this->dataliber_mes);
         $this->dataliber_ano = ($this->dataliber_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["dataliber_ano"]:$this->dataliber_ano);
         if($this->dataliber_dia != ""){
            $this->dataliber = $this->dataliber_ano."-".$this->dataliber_mes."-".$this->dataliber_dia;
         }
       }
       $this->valoravaliacao = ($this->valoravaliacao == ""?@$GLOBALS["HTTP_POST_VARS"]["valoravaliacao"]:$this->valoravaliacao);
       $this->valorpagamento = ($this->valorpagamento == ""?@$GLOBALS["HTTP_POST_VARS"]["valorpagamento"]:$this->valorpagamento);
       $this->obsliber = ($this->obsliber == ""?@$GLOBALS["HTTP_POST_VARS"]["obsliber"]:$this->obsliber);
       $this->loginn = ($this->loginn == ""?@$GLOBALS["HTTP_POST_VARS"]["loginn"]:$this->loginn);
       $this->numpre = ($this->numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["numpre"]:$this->numpre);
       if($this->datasolicitacao == ""){
         $this->datasolicitacao_dia = ($this->datasolicitacao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["datasolicitacao_dia"]:$this->datasolicitacao_dia);
         $this->datasolicitacao_mes = ($this->datasolicitacao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["datasolicitacao_mes"]:$this->datasolicitacao_mes);
         $this->datasolicitacao_ano = ($this->datasolicitacao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["datasolicitacao_ano"]:$this->datasolicitacao_ano);
         if($this->datasolicitacao_dia != ""){
            $this->datasolicitacao = $this->datasolicitacao_ano."-".$this->datasolicitacao_mes."-".$this->datasolicitacao_dia;
         }
       }
       $this->libpref = ($this->libpref == ""?@$GLOBALS["HTTP_POST_VARS"]["libpref"]:$this->libpref);
       $this->valoravterr = ($this->valoravterr == ""?@$GLOBALS["HTTP_POST_VARS"]["valoravterr"]:$this->valoravterr);
       $this->valoravconst = ($this->valoravconst == ""?@$GLOBALS["HTTP_POST_VARS"]["valoravconst"]:$this->valoravconst);
       $this->numerocomprador = ($this->numerocomprador == ""?@$GLOBALS["HTTP_POST_VARS"]["numerocomprador"]:$this->numerocomprador);
       $this->complcomprador = ($this->complcomprador == ""?@$GLOBALS["HTTP_POST_VARS"]["complcomprador"]:$this->complcomprador);
       $this->cxpostcomprador = ($this->cxpostcomprador == ""?@$GLOBALS["HTTP_POST_VARS"]["cxpostcomprador"]:$this->cxpostcomprador);
     }else{
       $this->matricula = ($this->matricula == ""?@$GLOBALS["HTTP_POST_VARS"]["matricula"]:$this->matricula);
     }
   }
   // funcao para inclusao
   function incluir ($matricula){ 
      $this->atualizacampos();
     if($this->areaterreno == null ){ 
       $this->erro_sql = " Campo Área terreno nao Informado.";
       $this->erro_campo = "areaterreno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->areaedificada == null ){ 
       $this->erro_sql = " Campo Área edificada nao Informado.";
       $this->erro_campo = "areaedificada";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->nomecomprador == null ){ 
       $this->erro_sql = " Campo Comprador nao Informado.";
       $this->erro_campo = "nomecomprador";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cgccpfcomprador == null ){ 
       $this->erro_sql = " Campo CGC/CPF nao Informado.";
       $this->erro_campo = "cgccpfcomprador";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->enderecocomprador == null ){ 
       $this->erro_sql = " Campo Endereço nao Informado.";
       $this->erro_campo = "enderecocomprador";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->municipiocomprador == null ){ 
       $this->erro_sql = " Campo Município nao Informado.";
       $this->erro_campo = "municipiocomprador";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bairrocomprador == null ){ 
       $this->erro_sql = " Campo Bairro nao Informado.";
       $this->erro_campo = "bairrocomprador";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cepcomprador == null ){ 
       $this->erro_sql = " Campo CEP nao Informado.";
       $this->erro_campo = "cepcomprador";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ufcomprador == null ){ 
       $this->erro_sql = " Campo UF nao Informado.";
       $this->erro_campo = "ufcomprador";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tipotransacao == null ){ 
       $this->erro_sql = " Campo Transação nao Informado.";
       $this->erro_campo = "tipotransacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->valortransacao == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "valortransacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->caracteristicas == null ){ 
       $this->erro_sql = " Campo Características nao Informado.";
       $this->erro_campo = "caracteristicas";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->mfrente == null ){ 
       $this->erro_sql = " Campo Frente nao Informado.";
       $this->erro_campo = "mfrente";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->mladodireito == null ){ 
       $this->erro_sql = " Campo Direito nao Informado.";
       $this->erro_campo = "mladodireito";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->mfundos == null ){ 
       $this->erro_sql = " Campo Fundos nao Informado.";
       $this->erro_campo = "mfundos";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->mladoesquerdo == null ){ 
       $this->erro_sql = " Campo Esquerdo nao Informado.";
       $this->erro_campo = "mladoesquerdo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->obs == null ){ 
       $this->erro_sql = " Campo Observação nao Informado.";
       $this->erro_campo = "obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->liberado == null ){ 
       $this->erro_sql = " Campo Liberado nao Informado.";
       $this->erro_campo = "liberado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->datavencimento == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "datavencimento_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->aliquota == null ){ 
       $this->erro_sql = " Campo Aliquota nao Informado.";
       $this->erro_campo = "aliquota";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->id_itbi == null ){ 
       $this->erro_sql = " Campo Guia nao Informado.";
       $this->erro_campo = "id_itbi";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dataliber == null ){ 
       $this->erro_sql = " Campo Data Liber nao Informado.";
       $this->erro_campo = "dataliber_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->valoravaliacao == null ){ 
       $this->erro_sql = " Campo Valor avaliado nao Informado.";
       $this->erro_campo = "valoravaliacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->valorpagamento == null ){ 
       $this->erro_sql = " Campo Valor pago nao Informado.";
       $this->erro_campo = "valorpagamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->obsliber == null ){ 
       $this->erro_sql = " Campo Obs Liberação nao Informado.";
       $this->erro_campo = "obsliber";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->loginn == null ){ 
       $this->erro_sql = " Campo Login nao Informado.";
       $this->erro_campo = "loginn";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->numpre == null ){ 
       $this->erro_sql = " Campo Nº arrecadação nao Informado.";
       $this->erro_campo = "numpre";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->datasolicitacao == null ){ 
       $this->erro_sql = " Campo Data solic nao Informado.";
       $this->erro_campo = "datasolicitacao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->libpref == null ){ 
       $this->erro_sql = " Campo Liberação da Prefeitura nao Informado.";
       $this->erro_campo = "libpref";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->valoravterr == null ){ 
       $this->erro_sql = " Campo Valor Avaliação Terreno nao Informado.";
       $this->erro_campo = "valoravterr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->valoravconst == null ){ 
       $this->erro_sql = " Campo Valor Avaliação Prédio nao Informado.";
       $this->erro_campo = "valoravconst";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->numerocomprador == null ){ 
       $this->erro_sql = " Campo Número nao Informado.";
       $this->erro_campo = "numerocomprador";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($id_itbi == "" || $id_itbi == null ){
       $result = db_query("select nextval('db_itbi_id_itbi_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_itbi_id_itbi_seq do campo: id_itbi"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->id_itbi = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_itbi_id_itbi_seq");
       if(($result != false) && (pg_result($result,0,0) < $id_itbi)){
         $this->erro_sql = " Campo id_itbi maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->id_itbi = $id_itbi; 
       }
     }
     if(($this->matricula == null) || ($this->matricula == "") ){ 
       $this->erro_sql = " Campo matricula nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_itbi(
                                       matricula 
                                      ,areaterreno 
                                      ,areaedificada 
                                      ,nomecomprador 
                                      ,cgccpfcomprador 
                                      ,enderecocomprador 
                                      ,municipiocomprador 
                                      ,bairrocomprador 
                                      ,cepcomprador 
                                      ,ufcomprador 
                                      ,tipotransacao 
                                      ,valortransacao 
                                      ,caracteristicas 
                                      ,mfrente 
                                      ,mladodireito 
                                      ,mfundos 
                                      ,mladoesquerdo 
                                      ,email 
                                      ,obs 
                                      ,liberado 
                                      ,datavencimento 
                                      ,aliquota 
                                      ,id_itbi 
                                      ,dataliber 
                                      ,valoravaliacao 
                                      ,valorpagamento 
                                      ,obsliber 
                                      ,loginn 
                                      ,numpre 
                                      ,datasolicitacao 
                                      ,libpref 
                                      ,valoravterr 
                                      ,valoravconst 
                                      ,numerocomprador 
                                      ,complcomprador 
                                      ,cxpostcomprador 
                       )
                values (
                                $this->matricula 
                               ,$this->areaterreno 
                               ,$this->areaedificada 
                               ,'$this->nomecomprador' 
                               ,'$this->cgccpfcomprador' 
                               ,'$this->enderecocomprador' 
                               ,'$this->municipiocomprador' 
                               ,'$this->bairrocomprador' 
                               ,'$this->cepcomprador' 
                               ,'$this->ufcomprador' 
                               ,'$this->tipotransacao' 
                               ,$this->valortransacao 
                               ,'$this->caracteristicas' 
                               ,$this->mfrente 
                               ,$this->mladodireito 
                               ,$this->mfundos 
                               ,$this->mladoesquerdo 
                               ,'$this->email' 
                               ,'$this->obs' 
                               ,$this->liberado 
                               ,".($this->datavencimento == "null" || $this->datavencimento == ""?"null":"'".$this->datavencimento."'")." 
                               ,$this->aliquota 
                               ,$this->id_itbi 
                               ,".($this->dataliber == "null" || $this->dataliber == ""?"null":"'".$this->dataliber."'")." 
                               ,$this->valoravaliacao 
                               ,$this->valorpagamento 
                               ,'$this->obsliber' 
                               ,'$this->loginn' 
                               ,'$this->numpre' 
                               ,".($this->datasolicitacao == "null" || $this->datasolicitacao == ""?"null":"'".$this->datasolicitacao."'")." 
                               ,$this->libpref 
                               ,$this->valoravterr 
                               ,$this->valoravconst 
                               ,$this->numerocomprador 
                               ,'$this->complcomprador' 
                               ,'$this->cxpostcomprador' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Itbi ($this->matricula) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Itbi já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Itbi ($this->matricula) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->matricula;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->matricula));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,991,'$this->matricula','I')");
       $resac = db_query("insert into db_acount values($acount,185,991,'','".AddSlashes(pg_result($resaco,0,'matricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,185,1037,'','".AddSlashes(pg_result($resaco,0,'areaterreno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,185,1038,'','".AddSlashes(pg_result($resaco,0,'areaedificada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,185,1039,'','".AddSlashes(pg_result($resaco,0,'nomecomprador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,185,1040,'','".AddSlashes(pg_result($resaco,0,'cgccpfcomprador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,185,1041,'','".AddSlashes(pg_result($resaco,0,'enderecocomprador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,185,1042,'','".AddSlashes(pg_result($resaco,0,'municipiocomprador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,185,1043,'','".AddSlashes(pg_result($resaco,0,'bairrocomprador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,185,1044,'','".AddSlashes(pg_result($resaco,0,'cepcomprador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,185,1045,'','".AddSlashes(pg_result($resaco,0,'ufcomprador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,185,1046,'','".AddSlashes(pg_result($resaco,0,'tipotransacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,185,1047,'','".AddSlashes(pg_result($resaco,0,'valortransacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,185,1048,'','".AddSlashes(pg_result($resaco,0,'caracteristicas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,185,1049,'','".AddSlashes(pg_result($resaco,0,'mfrente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,185,1050,'','".AddSlashes(pg_result($resaco,0,'mladodireito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,185,1051,'','".AddSlashes(pg_result($resaco,0,'mfundos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,185,1052,'','".AddSlashes(pg_result($resaco,0,'mladoesquerdo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,185,574,'','".AddSlashes(pg_result($resaco,0,'email'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,185,994,'','".AddSlashes(pg_result($resaco,0,'obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,185,1053,'','".AddSlashes(pg_result($resaco,0,'liberado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,185,1054,'','".AddSlashes(pg_result($resaco,0,'datavencimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,185,1055,'','".AddSlashes(pg_result($resaco,0,'aliquota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,185,1032,'','".AddSlashes(pg_result($resaco,0,'id_itbi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,185,1057,'','".AddSlashes(pg_result($resaco,0,'dataliber'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,185,1058,'','".AddSlashes(pg_result($resaco,0,'valoravaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,185,1059,'','".AddSlashes(pg_result($resaco,0,'valorpagamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,185,1060,'','".AddSlashes(pg_result($resaco,0,'obsliber'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,185,1061,'','".AddSlashes(pg_result($resaco,0,'loginn'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,185,1062,'','".AddSlashes(pg_result($resaco,0,'numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,185,1063,'','".AddSlashes(pg_result($resaco,0,'datasolicitacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,185,1064,'','".AddSlashes(pg_result($resaco,0,'libpref'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,185,2488,'','".AddSlashes(pg_result($resaco,0,'valoravterr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,185,2489,'','".AddSlashes(pg_result($resaco,0,'valoravconst'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,185,2507,'','".AddSlashes(pg_result($resaco,0,'numerocomprador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,185,2508,'','".AddSlashes(pg_result($resaco,0,'complcomprador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,185,2509,'','".AddSlashes(pg_result($resaco,0,'cxpostcomprador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($matricula=null) { 
      $this->atualizacampos();
     $sql = " update db_itbi set ";
     $virgula = "";
     if(trim($this->matricula)!="" || isset($GLOBALS["HTTP_POST_VARS"]["matricula"])){ 
       $sql  .= $virgula." matricula = $this->matricula ";
       $virgula = ",";
       if(trim($this->matricula) == null ){ 
         $this->erro_sql = " Campo Matrícula nao Informado.";
         $this->erro_campo = "matricula";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->areaterreno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["areaterreno"])){ 
       $sql  .= $virgula." areaterreno = $this->areaterreno ";
       $virgula = ",";
       if(trim($this->areaterreno) == null ){ 
         $this->erro_sql = " Campo Área terreno nao Informado.";
         $this->erro_campo = "areaterreno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->areaedificada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["areaedificada"])){ 
       $sql  .= $virgula." areaedificada = $this->areaedificada ";
       $virgula = ",";
       if(trim($this->areaedificada) == null ){ 
         $this->erro_sql = " Campo Área edificada nao Informado.";
         $this->erro_campo = "areaedificada";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->nomecomprador)!="" || isset($GLOBALS["HTTP_POST_VARS"]["nomecomprador"])){ 
       $sql  .= $virgula." nomecomprador = '$this->nomecomprador' ";
       $virgula = ",";
       if(trim($this->nomecomprador) == null ){ 
         $this->erro_sql = " Campo Comprador nao Informado.";
         $this->erro_campo = "nomecomprador";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cgccpfcomprador)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cgccpfcomprador"])){ 
       $sql  .= $virgula." cgccpfcomprador = '$this->cgccpfcomprador' ";
       $virgula = ",";
       if(trim($this->cgccpfcomprador) == null ){ 
         $this->erro_sql = " Campo CGC/CPF nao Informado.";
         $this->erro_campo = "cgccpfcomprador";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->enderecocomprador)!="" || isset($GLOBALS["HTTP_POST_VARS"]["enderecocomprador"])){ 
       $sql  .= $virgula." enderecocomprador = '$this->enderecocomprador' ";
       $virgula = ",";
       if(trim($this->enderecocomprador) == null ){ 
         $this->erro_sql = " Campo Endereço nao Informado.";
         $this->erro_campo = "enderecocomprador";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->municipiocomprador)!="" || isset($GLOBALS["HTTP_POST_VARS"]["municipiocomprador"])){ 
       $sql  .= $virgula." municipiocomprador = '$this->municipiocomprador' ";
       $virgula = ",";
       if(trim($this->municipiocomprador) == null ){ 
         $this->erro_sql = " Campo Município nao Informado.";
         $this->erro_campo = "municipiocomprador";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bairrocomprador)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bairrocomprador"])){ 
       $sql  .= $virgula." bairrocomprador = '$this->bairrocomprador' ";
       $virgula = ",";
       if(trim($this->bairrocomprador) == null ){ 
         $this->erro_sql = " Campo Bairro nao Informado.";
         $this->erro_campo = "bairrocomprador";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cepcomprador)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cepcomprador"])){ 
       $sql  .= $virgula." cepcomprador = '$this->cepcomprador' ";
       $virgula = ",";
       if(trim($this->cepcomprador) == null ){ 
         $this->erro_sql = " Campo CEP nao Informado.";
         $this->erro_campo = "cepcomprador";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ufcomprador)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ufcomprador"])){ 
       $sql  .= $virgula." ufcomprador = '$this->ufcomprador' ";
       $virgula = ",";
       if(trim($this->ufcomprador) == null ){ 
         $this->erro_sql = " Campo UF nao Informado.";
         $this->erro_campo = "ufcomprador";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tipotransacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tipotransacao"])){ 
       $sql  .= $virgula." tipotransacao = '$this->tipotransacao' ";
       $virgula = ",";
       if(trim($this->tipotransacao) == null ){ 
         $this->erro_sql = " Campo Transação nao Informado.";
         $this->erro_campo = "tipotransacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->valortransacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["valortransacao"])){ 
       $sql  .= $virgula." valortransacao = $this->valortransacao ";
       $virgula = ",";
       if(trim($this->valortransacao) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "valortransacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->caracteristicas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["caracteristicas"])){ 
       $sql  .= $virgula." caracteristicas = '$this->caracteristicas' ";
       $virgula = ",";
       if(trim($this->caracteristicas) == null ){ 
         $this->erro_sql = " Campo Características nao Informado.";
         $this->erro_campo = "caracteristicas";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->mfrente)!="" || isset($GLOBALS["HTTP_POST_VARS"]["mfrente"])){ 
       $sql  .= $virgula." mfrente = $this->mfrente ";
       $virgula = ",";
       if(trim($this->mfrente) == null ){ 
         $this->erro_sql = " Campo Frente nao Informado.";
         $this->erro_campo = "mfrente";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->mladodireito)!="" || isset($GLOBALS["HTTP_POST_VARS"]["mladodireito"])){ 
       $sql  .= $virgula." mladodireito = $this->mladodireito ";
       $virgula = ",";
       if(trim($this->mladodireito) == null ){ 
         $this->erro_sql = " Campo Direito nao Informado.";
         $this->erro_campo = "mladodireito";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->mfundos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["mfundos"])){ 
       $sql  .= $virgula." mfundos = $this->mfundos ";
       $virgula = ",";
       if(trim($this->mfundos) == null ){ 
         $this->erro_sql = " Campo Fundos nao Informado.";
         $this->erro_campo = "mfundos";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->mladoesquerdo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["mladoesquerdo"])){ 
       $sql  .= $virgula." mladoesquerdo = $this->mladoesquerdo ";
       $virgula = ",";
       if(trim($this->mladoesquerdo) == null ){ 
         $this->erro_sql = " Campo Esquerdo nao Informado.";
         $this->erro_campo = "mladoesquerdo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->email)!="" || isset($GLOBALS["HTTP_POST_VARS"]["email"])){ 
       $sql  .= $virgula." email = '$this->email' ";
       $virgula = ",";
     }
     if(trim($this->obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["obs"])){ 
       $sql  .= $virgula." obs = '$this->obs' ";
       $virgula = ",";
       if(trim($this->obs) == null ){ 
         $this->erro_sql = " Campo Observação nao Informado.";
         $this->erro_campo = "obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->liberado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["liberado"])){ 
       $sql  .= $virgula." liberado = $this->liberado ";
       $virgula = ",";
       if(trim($this->liberado) == null ){ 
         $this->erro_sql = " Campo Liberado nao Informado.";
         $this->erro_campo = "liberado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->datavencimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["datavencimento_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["datavencimento_dia"] !="") ){ 
       $sql  .= $virgula." datavencimento = '$this->datavencimento' ";
       $virgula = ",";
       if(trim($this->datavencimento) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "datavencimento_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["datavencimento_dia"])){ 
         $sql  .= $virgula." datavencimento = null ";
         $virgula = ",";
         if(trim($this->datavencimento) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "datavencimento_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->aliquota)!="" || isset($GLOBALS["HTTP_POST_VARS"]["aliquota"])){ 
       $sql  .= $virgula." aliquota = $this->aliquota ";
       $virgula = ",";
       if(trim($this->aliquota) == null ){ 
         $this->erro_sql = " Campo Aliquota nao Informado.";
         $this->erro_campo = "aliquota";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->id_itbi)!="" || isset($GLOBALS["HTTP_POST_VARS"]["id_itbi"])){ 
       $sql  .= $virgula." id_itbi = $this->id_itbi ";
       $virgula = ",";
       if(trim($this->id_itbi) == null ){ 
         $this->erro_sql = " Campo Guia nao Informado.";
         $this->erro_campo = "id_itbi";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dataliber)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dataliber_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["dataliber_dia"] !="") ){ 
       $sql  .= $virgula." dataliber = '$this->dataliber' ";
       $virgula = ",";
       if(trim($this->dataliber) == null ){ 
         $this->erro_sql = " Campo Data Liber nao Informado.";
         $this->erro_campo = "dataliber_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["dataliber_dia"])){ 
         $sql  .= $virgula." dataliber = null ";
         $virgula = ",";
         if(trim($this->dataliber) == null ){ 
           $this->erro_sql = " Campo Data Liber nao Informado.";
           $this->erro_campo = "dataliber_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->valoravaliacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["valoravaliacao"])){ 
       $sql  .= $virgula." valoravaliacao = $this->valoravaliacao ";
       $virgula = ",";
       if(trim($this->valoravaliacao) == null ){ 
         $this->erro_sql = " Campo Valor avaliado nao Informado.";
         $this->erro_campo = "valoravaliacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->valorpagamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["valorpagamento"])){ 
       $sql  .= $virgula." valorpagamento = $this->valorpagamento ";
       $virgula = ",";
       if(trim($this->valorpagamento) == null ){ 
         $this->erro_sql = " Campo Valor pago nao Informado.";
         $this->erro_campo = "valorpagamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->obsliber)!="" || isset($GLOBALS["HTTP_POST_VARS"]["obsliber"])){ 
       $sql  .= $virgula." obsliber = '$this->obsliber' ";
       $virgula = ",";
       if(trim($this->obsliber) == null ){ 
         $this->erro_sql = " Campo Obs Liberação nao Informado.";
         $this->erro_campo = "obsliber";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->loginn)!="" || isset($GLOBALS["HTTP_POST_VARS"]["loginn"])){ 
       $sql  .= $virgula." loginn = '$this->loginn' ";
       $virgula = ",";
       if(trim($this->loginn) == null ){ 
         $this->erro_sql = " Campo Login nao Informado.";
         $this->erro_campo = "loginn";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["numpre"])){ 
       $sql  .= $virgula." numpre = '$this->numpre' ";
       $virgula = ",";
       if(trim($this->numpre) == null ){ 
         $this->erro_sql = " Campo Nº arrecadação nao Informado.";
         $this->erro_campo = "numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->datasolicitacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["datasolicitacao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["datasolicitacao_dia"] !="") ){ 
       $sql  .= $virgula." datasolicitacao = '$this->datasolicitacao' ";
       $virgula = ",";
       if(trim($this->datasolicitacao) == null ){ 
         $this->erro_sql = " Campo Data solic nao Informado.";
         $this->erro_campo = "datasolicitacao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["datasolicitacao_dia"])){ 
         $sql  .= $virgula." datasolicitacao = null ";
         $virgula = ",";
         if(trim($this->datasolicitacao) == null ){ 
           $this->erro_sql = " Campo Data solic nao Informado.";
           $this->erro_campo = "datasolicitacao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->libpref)!="" || isset($GLOBALS["HTTP_POST_VARS"]["libpref"])){ 
       $sql  .= $virgula." libpref = $this->libpref ";
       $virgula = ",";
       if(trim($this->libpref) == null ){ 
         $this->erro_sql = " Campo Liberação da Prefeitura nao Informado.";
         $this->erro_campo = "libpref";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->valoravterr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["valoravterr"])){ 
       $sql  .= $virgula." valoravterr = $this->valoravterr ";
       $virgula = ",";
       if(trim($this->valoravterr) == null ){ 
         $this->erro_sql = " Campo Valor Avaliação Terreno nao Informado.";
         $this->erro_campo = "valoravterr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->valoravconst)!="" || isset($GLOBALS["HTTP_POST_VARS"]["valoravconst"])){ 
       $sql  .= $virgula." valoravconst = $this->valoravconst ";
       $virgula = ",";
       if(trim($this->valoravconst) == null ){ 
         $this->erro_sql = " Campo Valor Avaliação Prédio nao Informado.";
         $this->erro_campo = "valoravconst";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->numerocomprador)!="" || isset($GLOBALS["HTTP_POST_VARS"]["numerocomprador"])){ 
       $sql  .= $virgula." numerocomprador = $this->numerocomprador ";
       $virgula = ",";
       if(trim($this->numerocomprador) == null ){ 
         $this->erro_sql = " Campo Número nao Informado.";
         $this->erro_campo = "numerocomprador";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->complcomprador)!="" || isset($GLOBALS["HTTP_POST_VARS"]["complcomprador"])){ 
       $sql  .= $virgula." complcomprador = '$this->complcomprador' ";
       $virgula = ",";
     }
     if(trim($this->cxpostcomprador)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cxpostcomprador"])){ 
       $sql  .= $virgula." cxpostcomprador = '$this->cxpostcomprador' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($matricula!=null){
       $sql .= " matricula = $this->matricula";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->matricula));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,991,'$this->matricula','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["matricula"]))
           $resac = db_query("insert into db_acount values($acount,185,991,'".AddSlashes(pg_result($resaco,$conresaco,'matricula'))."','$this->matricula',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["areaterreno"]))
           $resac = db_query("insert into db_acount values($acount,185,1037,'".AddSlashes(pg_result($resaco,$conresaco,'areaterreno'))."','$this->areaterreno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["areaedificada"]))
           $resac = db_query("insert into db_acount values($acount,185,1038,'".AddSlashes(pg_result($resaco,$conresaco,'areaedificada'))."','$this->areaedificada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["nomecomprador"]))
           $resac = db_query("insert into db_acount values($acount,185,1039,'".AddSlashes(pg_result($resaco,$conresaco,'nomecomprador'))."','$this->nomecomprador',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cgccpfcomprador"]))
           $resac = db_query("insert into db_acount values($acount,185,1040,'".AddSlashes(pg_result($resaco,$conresaco,'cgccpfcomprador'))."','$this->cgccpfcomprador',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["enderecocomprador"]))
           $resac = db_query("insert into db_acount values($acount,185,1041,'".AddSlashes(pg_result($resaco,$conresaco,'enderecocomprador'))."','$this->enderecocomprador',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["municipiocomprador"]))
           $resac = db_query("insert into db_acount values($acount,185,1042,'".AddSlashes(pg_result($resaco,$conresaco,'municipiocomprador'))."','$this->municipiocomprador',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bairrocomprador"]))
           $resac = db_query("insert into db_acount values($acount,185,1043,'".AddSlashes(pg_result($resaco,$conresaco,'bairrocomprador'))."','$this->bairrocomprador',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cepcomprador"]))
           $resac = db_query("insert into db_acount values($acount,185,1044,'".AddSlashes(pg_result($resaco,$conresaco,'cepcomprador'))."','$this->cepcomprador',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ufcomprador"]))
           $resac = db_query("insert into db_acount values($acount,185,1045,'".AddSlashes(pg_result($resaco,$conresaco,'ufcomprador'))."','$this->ufcomprador',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tipotransacao"]))
           $resac = db_query("insert into db_acount values($acount,185,1046,'".AddSlashes(pg_result($resaco,$conresaco,'tipotransacao'))."','$this->tipotransacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["valortransacao"]))
           $resac = db_query("insert into db_acount values($acount,185,1047,'".AddSlashes(pg_result($resaco,$conresaco,'valortransacao'))."','$this->valortransacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["caracteristicas"]))
           $resac = db_query("insert into db_acount values($acount,185,1048,'".AddSlashes(pg_result($resaco,$conresaco,'caracteristicas'))."','$this->caracteristicas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["mfrente"]))
           $resac = db_query("insert into db_acount values($acount,185,1049,'".AddSlashes(pg_result($resaco,$conresaco,'mfrente'))."','$this->mfrente',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["mladodireito"]))
           $resac = db_query("insert into db_acount values($acount,185,1050,'".AddSlashes(pg_result($resaco,$conresaco,'mladodireito'))."','$this->mladodireito',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["mfundos"]))
           $resac = db_query("insert into db_acount values($acount,185,1051,'".AddSlashes(pg_result($resaco,$conresaco,'mfundos'))."','$this->mfundos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["mladoesquerdo"]))
           $resac = db_query("insert into db_acount values($acount,185,1052,'".AddSlashes(pg_result($resaco,$conresaco,'mladoesquerdo'))."','$this->mladoesquerdo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["email"]))
           $resac = db_query("insert into db_acount values($acount,185,574,'".AddSlashes(pg_result($resaco,$conresaco,'email'))."','$this->email',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["obs"]))
           $resac = db_query("insert into db_acount values($acount,185,994,'".AddSlashes(pg_result($resaco,$conresaco,'obs'))."','$this->obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["liberado"]))
           $resac = db_query("insert into db_acount values($acount,185,1053,'".AddSlashes(pg_result($resaco,$conresaco,'liberado'))."','$this->liberado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["datavencimento"]))
           $resac = db_query("insert into db_acount values($acount,185,1054,'".AddSlashes(pg_result($resaco,$conresaco,'datavencimento'))."','$this->datavencimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["aliquota"]))
           $resac = db_query("insert into db_acount values($acount,185,1055,'".AddSlashes(pg_result($resaco,$conresaco,'aliquota'))."','$this->aliquota',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["id_itbi"]))
           $resac = db_query("insert into db_acount values($acount,185,1032,'".AddSlashes(pg_result($resaco,$conresaco,'id_itbi'))."','$this->id_itbi',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dataliber"]))
           $resac = db_query("insert into db_acount values($acount,185,1057,'".AddSlashes(pg_result($resaco,$conresaco,'dataliber'))."','$this->dataliber',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["valoravaliacao"]))
           $resac = db_query("insert into db_acount values($acount,185,1058,'".AddSlashes(pg_result($resaco,$conresaco,'valoravaliacao'))."','$this->valoravaliacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["valorpagamento"]))
           $resac = db_query("insert into db_acount values($acount,185,1059,'".AddSlashes(pg_result($resaco,$conresaco,'valorpagamento'))."','$this->valorpagamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["obsliber"]))
           $resac = db_query("insert into db_acount values($acount,185,1060,'".AddSlashes(pg_result($resaco,$conresaco,'obsliber'))."','$this->obsliber',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["loginn"]))
           $resac = db_query("insert into db_acount values($acount,185,1061,'".AddSlashes(pg_result($resaco,$conresaco,'loginn'))."','$this->loginn',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["numpre"]))
           $resac = db_query("insert into db_acount values($acount,185,1062,'".AddSlashes(pg_result($resaco,$conresaco,'numpre'))."','$this->numpre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["datasolicitacao"]))
           $resac = db_query("insert into db_acount values($acount,185,1063,'".AddSlashes(pg_result($resaco,$conresaco,'datasolicitacao'))."','$this->datasolicitacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["libpref"]))
           $resac = db_query("insert into db_acount values($acount,185,1064,'".AddSlashes(pg_result($resaco,$conresaco,'libpref'))."','$this->libpref',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["valoravterr"]))
           $resac = db_query("insert into db_acount values($acount,185,2488,'".AddSlashes(pg_result($resaco,$conresaco,'valoravterr'))."','$this->valoravterr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["valoravconst"]))
           $resac = db_query("insert into db_acount values($acount,185,2489,'".AddSlashes(pg_result($resaco,$conresaco,'valoravconst'))."','$this->valoravconst',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["numerocomprador"]))
           $resac = db_query("insert into db_acount values($acount,185,2507,'".AddSlashes(pg_result($resaco,$conresaco,'numerocomprador'))."','$this->numerocomprador',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["complcomprador"]))
           $resac = db_query("insert into db_acount values($acount,185,2508,'".AddSlashes(pg_result($resaco,$conresaco,'complcomprador'))."','$this->complcomprador',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cxpostcomprador"]))
           $resac = db_query("insert into db_acount values($acount,185,2509,'".AddSlashes(pg_result($resaco,$conresaco,'cxpostcomprador'))."','$this->cxpostcomprador',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itbi nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->matricula;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Itbi nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->matricula;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->matricula;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($matricula=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($matricula));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,991,'$matricula','E')");
         $resac = db_query("insert into db_acount values($acount,185,991,'','".AddSlashes(pg_result($resaco,$iresaco,'matricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,185,1037,'','".AddSlashes(pg_result($resaco,$iresaco,'areaterreno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,185,1038,'','".AddSlashes(pg_result($resaco,$iresaco,'areaedificada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,185,1039,'','".AddSlashes(pg_result($resaco,$iresaco,'nomecomprador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,185,1040,'','".AddSlashes(pg_result($resaco,$iresaco,'cgccpfcomprador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,185,1041,'','".AddSlashes(pg_result($resaco,$iresaco,'enderecocomprador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,185,1042,'','".AddSlashes(pg_result($resaco,$iresaco,'municipiocomprador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,185,1043,'','".AddSlashes(pg_result($resaco,$iresaco,'bairrocomprador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,185,1044,'','".AddSlashes(pg_result($resaco,$iresaco,'cepcomprador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,185,1045,'','".AddSlashes(pg_result($resaco,$iresaco,'ufcomprador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,185,1046,'','".AddSlashes(pg_result($resaco,$iresaco,'tipotransacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,185,1047,'','".AddSlashes(pg_result($resaco,$iresaco,'valortransacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,185,1048,'','".AddSlashes(pg_result($resaco,$iresaco,'caracteristicas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,185,1049,'','".AddSlashes(pg_result($resaco,$iresaco,'mfrente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,185,1050,'','".AddSlashes(pg_result($resaco,$iresaco,'mladodireito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,185,1051,'','".AddSlashes(pg_result($resaco,$iresaco,'mfundos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,185,1052,'','".AddSlashes(pg_result($resaco,$iresaco,'mladoesquerdo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,185,574,'','".AddSlashes(pg_result($resaco,$iresaco,'email'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,185,994,'','".AddSlashes(pg_result($resaco,$iresaco,'obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,185,1053,'','".AddSlashes(pg_result($resaco,$iresaco,'liberado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,185,1054,'','".AddSlashes(pg_result($resaco,$iresaco,'datavencimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,185,1055,'','".AddSlashes(pg_result($resaco,$iresaco,'aliquota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,185,1032,'','".AddSlashes(pg_result($resaco,$iresaco,'id_itbi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,185,1057,'','".AddSlashes(pg_result($resaco,$iresaco,'dataliber'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,185,1058,'','".AddSlashes(pg_result($resaco,$iresaco,'valoravaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,185,1059,'','".AddSlashes(pg_result($resaco,$iresaco,'valorpagamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,185,1060,'','".AddSlashes(pg_result($resaco,$iresaco,'obsliber'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,185,1061,'','".AddSlashes(pg_result($resaco,$iresaco,'loginn'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,185,1062,'','".AddSlashes(pg_result($resaco,$iresaco,'numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,185,1063,'','".AddSlashes(pg_result($resaco,$iresaco,'datasolicitacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,185,1064,'','".AddSlashes(pg_result($resaco,$iresaco,'libpref'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,185,2488,'','".AddSlashes(pg_result($resaco,$iresaco,'valoravterr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,185,2489,'','".AddSlashes(pg_result($resaco,$iresaco,'valoravconst'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,185,2507,'','".AddSlashes(pg_result($resaco,$iresaco,'numerocomprador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,185,2508,'','".AddSlashes(pg_result($resaco,$iresaco,'complcomprador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,185,2509,'','".AddSlashes(pg_result($resaco,$iresaco,'cxpostcomprador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_itbi
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($matricula != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " matricula = $matricula ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itbi nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$matricula;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Itbi nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$matricula;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$matricula;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:db_itbi";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $matricula=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from db_itbi ";
     $sql2 = "";
     if($dbwhere==""){
       if($matricula!=null ){
         $sql2 .= " where db_itbi.matricula = $matricula "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_file ( $matricula=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from db_itbi ";
     $sql2 = "";
     if($dbwhere==""){
       if($matricula!=null ){
         $sql2 .= " where db_itbi.matricula = $matricula "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_itbi ( $j01_matric=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from iptubase ";
     $sql .= "      inner join lote  on  lote.j34_idbql = iptubase.j01_idbql";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = iptubase.j01_numcgm";
     $sql .= "      inner join bairro  on  bairro.j13_codi = lote.j34_bairro";
     $sql .= "      inner join setor  on  setor.j30_codi = lote.j34_setor";
     $sql .= "      left outer join iptuant on iptubase.j01_matric = iptuant.j40_matric";
     $sql .= "      inner join db_itbi on j01_matric = matricula";
     $sql2 = "";
     if($dbwhere==""){
       if($j01_matric!=null ){
         $sql2 .= " where iptubase.j01_matric = $j01_matric ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
}
?>