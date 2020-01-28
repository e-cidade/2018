<?
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

//MODULO: pessoal
//CLASSE DA ENTIDADE rharqbanco
class cl_rharqbanco { 
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
   var $rh34_instit = 0; 
   var $rh34_codarq = 0; 
   var $rh34_descr = null; 
   var $rh34_codban = null; 
   var $rh34_agencia = 0; 
   var $rh34_dvagencia = null; 
   var $rh34_conta = null; 
   var $rh34_dvconta = null; 
   var $rh34_convenio = null; 
   var $rh34_sequencial = 0; 
   var $rh34_where = null; 
   var $rh34_ativo = 'f'; 
   var $rh34_parametrotransmissaoheader = null; 
   var $rh34_parametrotransmissaolote = null; 
   var $rh34_codigocompromisso = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh34_instit = int4 = Cod. Instituição 
                 rh34_codarq = int4 = Código do Arquivo 
                 rh34_descr = varchar(40) = Descrição do Cadastro 
                 rh34_codban = varchar(10) = Código do Banco FEBRABAN 
                 rh34_agencia = int4 = Agência 
                 rh34_dvagencia = varchar(2) = Dígito da Agência 
                 rh34_conta = varchar(15) = Conta Corrente 
                 rh34_dvconta = varchar(2) = Dígito da Conta 
                 rh34_convenio = varchar(20) = Número do Convênio 
                 rh34_sequencial = int4 = Sequencial do Arquivo 
                 rh34_where = varchar(150) = Condição/Fórmula 
                 rh34_ativo = bool = Ativo 
                 rh34_parametrotransmissaoheader = varchar(2) = Parâmetro Transmissão Header do Arquivo 
                 rh34_parametrotransmissaolote = varchar(2) = Parâmetro Transmissão Header do Lote 
                 rh34_codigocompromisso = varchar(4) = Código do Compromisso 
                 ";
   //funcao construtor da classe 
   function cl_rharqbanco() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rharqbanco"); 
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
       $this->rh34_instit = ($this->rh34_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh34_instit"]:$this->rh34_instit);
       $this->rh34_codarq = ($this->rh34_codarq == ""?@$GLOBALS["HTTP_POST_VARS"]["rh34_codarq"]:$this->rh34_codarq);
       $this->rh34_descr = ($this->rh34_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["rh34_descr"]:$this->rh34_descr);
       $this->rh34_codban = ($this->rh34_codban == ""?@$GLOBALS["HTTP_POST_VARS"]["rh34_codban"]:$this->rh34_codban);
       $this->rh34_agencia = ($this->rh34_agencia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh34_agencia"]:$this->rh34_agencia);
       $this->rh34_dvagencia = ($this->rh34_dvagencia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh34_dvagencia"]:$this->rh34_dvagencia);
       $this->rh34_conta = ($this->rh34_conta == ""?@$GLOBALS["HTTP_POST_VARS"]["rh34_conta"]:$this->rh34_conta);
       $this->rh34_dvconta = ($this->rh34_dvconta == ""?@$GLOBALS["HTTP_POST_VARS"]["rh34_dvconta"]:$this->rh34_dvconta);
       $this->rh34_convenio = ($this->rh34_convenio == ""?@$GLOBALS["HTTP_POST_VARS"]["rh34_convenio"]:$this->rh34_convenio);
       $this->rh34_sequencial = ($this->rh34_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh34_sequencial"]:$this->rh34_sequencial);
       $this->rh34_where = ($this->rh34_where == ""?@$GLOBALS["HTTP_POST_VARS"]["rh34_where"]:$this->rh34_where);
       $this->rh34_ativo = ($this->rh34_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["rh34_ativo"]:$this->rh34_ativo);
       $this->rh34_parametrotransmissaoheader = ($this->rh34_parametrotransmissaoheader == ""?@$GLOBALS["HTTP_POST_VARS"]["rh34_parametrotransmissaoheader"]:$this->rh34_parametrotransmissaoheader);
       $this->rh34_parametrotransmissaolote = ($this->rh34_parametrotransmissaolote == ""?@$GLOBALS["HTTP_POST_VARS"]["rh34_parametrotransmissaolote"]:$this->rh34_parametrotransmissaolote);
       $this->rh34_codigocompromisso = ($this->rh34_codigocompromisso == ""?@$GLOBALS["HTTP_POST_VARS"]["rh34_codigocompromisso"]:$this->rh34_codigocompromisso);
     }else{
       $this->rh34_instit = ($this->rh34_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh34_instit"]:$this->rh34_instit);
       $this->rh34_codarq = ($this->rh34_codarq == ""?@$GLOBALS["HTTP_POST_VARS"]["rh34_codarq"]:$this->rh34_codarq);
     }
   }
   // funcao para inclusao
   function incluir ($rh34_codarq,$rh34_instit){ 
      $this->atualizacampos();
     if($this->rh34_descr == null ){ 
       $this->erro_sql = " Campo Descrição do Cadastro não informado.";
       $this->erro_campo = "rh34_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh34_codban == null ){ 
       $this->erro_sql = " Campo Código do Banco FEBRABAN não informado.";
       $this->erro_campo = "rh34_codban";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh34_agencia == null ){ 
       $this->erro_sql = " Campo Agência não informado.";
       $this->erro_campo = "rh34_agencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh34_dvagencia == null ){ 
       $this->erro_sql = " Campo Dígito da Agência não informado.";
       $this->erro_campo = "rh34_dvagencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh34_conta == null ){ 
       $this->erro_sql = " Campo Conta Corrente não informado.";
       $this->erro_campo = "rh34_conta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh34_dvconta == null ){ 
       $this->erro_sql = " Campo Dígito da Conta não informado.";
       $this->erro_campo = "rh34_dvconta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh34_convenio == null ){ 
       $this->erro_sql = " Campo Número do Convênio não informado.";
       $this->erro_campo = "rh34_convenio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh34_sequencial == null ){ 
       $this->erro_sql = " Campo Sequencial do Arquivo não informado.";
       $this->erro_campo = "rh34_sequencial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh34_ativo == null ){ 
       $this->erro_sql = " Campo Ativo não informado.";
       $this->erro_campo = "rh34_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh34_codarq == "" || $rh34_codarq == null ){
       $result = db_query("select nextval('rharqbanco_codarq_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rharqbanco_codarq_seq do campo: rh34_codarq"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh34_codarq = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rharqbanco_codarq_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh34_codarq)){
         $this->erro_sql = " Campo rh34_codarq maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh34_codarq = $rh34_codarq; 
       }
     }
     if(($this->rh34_codarq == null) || ($this->rh34_codarq == "") ){ 
       $this->erro_sql = " Campo rh34_codarq nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->rh34_instit == null) || ($this->rh34_instit == "") ){ 
       $this->erro_sql = " Campo rh34_instit nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rharqbanco(
                                       rh34_instit 
                                      ,rh34_codarq 
                                      ,rh34_descr 
                                      ,rh34_codban 
                                      ,rh34_agencia 
                                      ,rh34_dvagencia 
                                      ,rh34_conta 
                                      ,rh34_dvconta 
                                      ,rh34_convenio 
                                      ,rh34_sequencial 
                                      ,rh34_where 
                                      ,rh34_ativo 
                                      ,rh34_parametrotransmissaoheader 
                                      ,rh34_parametrotransmissaolote 
                                      ,rh34_codigocompromisso 
                       )
                values (
                                $this->rh34_instit 
                               ,$this->rh34_codarq 
                               ,'$this->rh34_descr' 
                               ,'$this->rh34_codban' 
                               ,$this->rh34_agencia 
                               ,'$this->rh34_dvagencia' 
                               ,'$this->rh34_conta' 
                               ,'$this->rh34_dvconta' 
                               ,'$this->rh34_convenio' 
                               ,$this->rh34_sequencial 
                               ,'$this->rh34_where' 
                               ,'$this->rh34_ativo' 
                               ,'$this->rh34_parametrotransmissaoheader' 
                               ,'$this->rh34_parametrotransmissaolote' 
                               ,'$this->rh34_codigocompromisso' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Arquivos dos bancos ($this->rh34_codarq."-".$this->rh34_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Arquivos dos bancos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Arquivos dos bancos ($this->rh34_codarq."-".$this->rh34_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh34_codarq."-".$this->rh34_instit;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh34_codarq,$this->rh34_instit  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7298,'$this->rh34_codarq','I')");
         $resac = db_query("insert into db_acountkey values($acount,9901,'$this->rh34_instit','I')");
         $resac = db_query("insert into db_acount values($acount,1212,9901,'','".AddSlashes(pg_result($resaco,0,'rh34_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1212,7298,'','".AddSlashes(pg_result($resaco,0,'rh34_codarq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1212,7307,'','".AddSlashes(pg_result($resaco,0,'rh34_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1212,7299,'','".AddSlashes(pg_result($resaco,0,'rh34_codban'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1212,7300,'','".AddSlashes(pg_result($resaco,0,'rh34_agencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1212,7301,'','".AddSlashes(pg_result($resaco,0,'rh34_dvagencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1212,7302,'','".AddSlashes(pg_result($resaco,0,'rh34_conta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1212,7303,'','".AddSlashes(pg_result($resaco,0,'rh34_dvconta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1212,7305,'','".AddSlashes(pg_result($resaco,0,'rh34_convenio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1212,7306,'','".AddSlashes(pg_result($resaco,0,'rh34_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1212,7308,'','".AddSlashes(pg_result($resaco,0,'rh34_where'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1212,7304,'','".AddSlashes(pg_result($resaco,0,'rh34_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1212,20556,'','".AddSlashes(pg_result($resaco,0,'rh34_parametrotransmissaoheader'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1212,20557,'','".AddSlashes(pg_result($resaco,0,'rh34_parametrotransmissaolote'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1212,20558,'','".AddSlashes(pg_result($resaco,0,'rh34_codigocompromisso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh34_codarq=null,$rh34_instit=null) { 
      $this->atualizacampos();
     $sql = " update rharqbanco set ";
     $virgula = "";
     if(trim($this->rh34_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh34_instit"])){ 
       $sql  .= $virgula." rh34_instit = $this->rh34_instit ";
       $virgula = ",";
       if(trim($this->rh34_instit) == null ){ 
         $this->erro_sql = " Campo Cod. Instituição não informado.";
         $this->erro_campo = "rh34_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh34_codarq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh34_codarq"])){ 
       $sql  .= $virgula." rh34_codarq = $this->rh34_codarq ";
       $virgula = ",";
       if(trim($this->rh34_codarq) == null ){ 
         $this->erro_sql = " Campo Código do Arquivo não informado.";
         $this->erro_campo = "rh34_codarq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh34_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh34_descr"])){ 
       $sql  .= $virgula." rh34_descr = '$this->rh34_descr' ";
       $virgula = ",";
       if(trim($this->rh34_descr) == null ){ 
         $this->erro_sql = " Campo Descrição do Cadastro não informado.";
         $this->erro_campo = "rh34_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh34_codban)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh34_codban"])){ 
       $sql  .= $virgula." rh34_codban = '$this->rh34_codban' ";
       $virgula = ",";
       if(trim($this->rh34_codban) == null ){ 
         $this->erro_sql = " Campo Código do Banco FEBRABAN não informado.";
         $this->erro_campo = "rh34_codban";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh34_agencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh34_agencia"])){ 
       $sql  .= $virgula." rh34_agencia = $this->rh34_agencia ";
       $virgula = ",";
       if(trim($this->rh34_agencia) == null ){ 
         $this->erro_sql = " Campo Agência não informado.";
         $this->erro_campo = "rh34_agencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh34_dvagencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh34_dvagencia"])){ 
       $sql  .= $virgula." rh34_dvagencia = '$this->rh34_dvagencia' ";
       $virgula = ",";
       if(trim($this->rh34_dvagencia) == null ){ 
         $this->erro_sql = " Campo Dígito da Agência não informado.";
         $this->erro_campo = "rh34_dvagencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh34_conta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh34_conta"])){ 
       $sql  .= $virgula." rh34_conta = '$this->rh34_conta' ";
       $virgula = ",";
       if(trim($this->rh34_conta) == null ){ 
         $this->erro_sql = " Campo Conta Corrente não informado.";
         $this->erro_campo = "rh34_conta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh34_dvconta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh34_dvconta"])){ 
       $sql  .= $virgula." rh34_dvconta = '$this->rh34_dvconta' ";
       $virgula = ",";
       if(trim($this->rh34_dvconta) == null ){ 
         $this->erro_sql = " Campo Dígito da Conta não informado.";
         $this->erro_campo = "rh34_dvconta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh34_convenio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh34_convenio"])){ 
       $sql  .= $virgula." rh34_convenio = '$this->rh34_convenio' ";
       $virgula = ",";
       if(trim($this->rh34_convenio) == null ){ 
         $this->erro_sql = " Campo Número do Convênio não informado.";
         $this->erro_campo = "rh34_convenio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh34_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh34_sequencial"])){ 
       $sql  .= $virgula." rh34_sequencial = $this->rh34_sequencial ";
       $virgula = ",";
       if(trim($this->rh34_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial do Arquivo não informado.";
         $this->erro_campo = "rh34_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh34_where)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh34_where"])){ 
       $sql  .= $virgula." rh34_where = '$this->rh34_where' ";
       $virgula = ",";
     }
     if(trim($this->rh34_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh34_ativo"])){ 
       $sql  .= $virgula." rh34_ativo = '$this->rh34_ativo' ";
       $virgula = ",";
       if(trim($this->rh34_ativo) == null ){ 
         $this->erro_sql = " Campo Ativo não informado.";
         $this->erro_campo = "rh34_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh34_parametrotransmissaoheader)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh34_parametrotransmissaoheader"])){ 
       $sql  .= $virgula." rh34_parametrotransmissaoheader = '$this->rh34_parametrotransmissaoheader' ";
       $virgula = ",";
     }
     if(trim($this->rh34_parametrotransmissaolote)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh34_parametrotransmissaolote"])){ 
       $sql  .= $virgula." rh34_parametrotransmissaolote = '$this->rh34_parametrotransmissaolote' ";
       $virgula = ",";
     }
     if(trim($this->rh34_codigocompromisso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh34_codigocompromisso"])){ 
       $sql  .= $virgula." rh34_codigocompromisso = '$this->rh34_codigocompromisso' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($rh34_codarq!=null){
       $sql .= " rh34_codarq = $this->rh34_codarq";
     }
     if($rh34_instit!=null){
       $sql .= " and  rh34_instit = $this->rh34_instit";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh34_codarq,$this->rh34_instit));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,7298,'$this->rh34_codarq','A')");
           $resac = db_query("insert into db_acountkey values($acount,9901,'$this->rh34_instit','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh34_instit"]) || $this->rh34_instit != "")
             $resac = db_query("insert into db_acount values($acount,1212,9901,'".AddSlashes(pg_result($resaco,$conresaco,'rh34_instit'))."','$this->rh34_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh34_codarq"]) || $this->rh34_codarq != "")
             $resac = db_query("insert into db_acount values($acount,1212,7298,'".AddSlashes(pg_result($resaco,$conresaco,'rh34_codarq'))."','$this->rh34_codarq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh34_descr"]) || $this->rh34_descr != "")
             $resac = db_query("insert into db_acount values($acount,1212,7307,'".AddSlashes(pg_result($resaco,$conresaco,'rh34_descr'))."','$this->rh34_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh34_codban"]) || $this->rh34_codban != "")
             $resac = db_query("insert into db_acount values($acount,1212,7299,'".AddSlashes(pg_result($resaco,$conresaco,'rh34_codban'))."','$this->rh34_codban',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh34_agencia"]) || $this->rh34_agencia != "")
             $resac = db_query("insert into db_acount values($acount,1212,7300,'".AddSlashes(pg_result($resaco,$conresaco,'rh34_agencia'))."','$this->rh34_agencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh34_dvagencia"]) || $this->rh34_dvagencia != "")
             $resac = db_query("insert into db_acount values($acount,1212,7301,'".AddSlashes(pg_result($resaco,$conresaco,'rh34_dvagencia'))."','$this->rh34_dvagencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh34_conta"]) || $this->rh34_conta != "")
             $resac = db_query("insert into db_acount values($acount,1212,7302,'".AddSlashes(pg_result($resaco,$conresaco,'rh34_conta'))."','$this->rh34_conta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh34_dvconta"]) || $this->rh34_dvconta != "")
             $resac = db_query("insert into db_acount values($acount,1212,7303,'".AddSlashes(pg_result($resaco,$conresaco,'rh34_dvconta'))."','$this->rh34_dvconta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh34_convenio"]) || $this->rh34_convenio != "")
             $resac = db_query("insert into db_acount values($acount,1212,7305,'".AddSlashes(pg_result($resaco,$conresaco,'rh34_convenio'))."','$this->rh34_convenio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh34_sequencial"]) || $this->rh34_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1212,7306,'".AddSlashes(pg_result($resaco,$conresaco,'rh34_sequencial'))."','$this->rh34_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh34_where"]) || $this->rh34_where != "")
             $resac = db_query("insert into db_acount values($acount,1212,7308,'".AddSlashes(pg_result($resaco,$conresaco,'rh34_where'))."','$this->rh34_where',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh34_ativo"]) || $this->rh34_ativo != "")
             $resac = db_query("insert into db_acount values($acount,1212,7304,'".AddSlashes(pg_result($resaco,$conresaco,'rh34_ativo'))."','$this->rh34_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh34_parametrotransmissaoheader"]) || $this->rh34_parametrotransmissaoheader != "")
             $resac = db_query("insert into db_acount values($acount,1212,20556,'".AddSlashes(pg_result($resaco,$conresaco,'rh34_parametrotransmissaoheader'))."','$this->rh34_parametrotransmissaoheader',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh34_parametrotransmissaolote"]) || $this->rh34_parametrotransmissaolote != "")
             $resac = db_query("insert into db_acount values($acount,1212,20557,'".AddSlashes(pg_result($resaco,$conresaco,'rh34_parametrotransmissaolote'))."','$this->rh34_parametrotransmissaolote',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh34_codigocompromisso"]) || $this->rh34_codigocompromisso != "")
             $resac = db_query("insert into db_acount values($acount,1212,20558,'".AddSlashes(pg_result($resaco,$conresaco,'rh34_codigocompromisso'))."','$this->rh34_codigocompromisso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Arquivos dos bancos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh34_codarq."-".$this->rh34_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Arquivos dos bancos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh34_codarq."-".$this->rh34_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh34_codarq."-".$this->rh34_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh34_codarq=null,$rh34_instit=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($rh34_codarq,$rh34_instit));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,7298,'$rh34_codarq','E')");
           $resac  = db_query("insert into db_acountkey values($acount,9901,'$rh34_instit','E')");
           $resac  = db_query("insert into db_acount values($acount,1212,9901,'','".AddSlashes(pg_result($resaco,$iresaco,'rh34_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1212,7298,'','".AddSlashes(pg_result($resaco,$iresaco,'rh34_codarq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1212,7307,'','".AddSlashes(pg_result($resaco,$iresaco,'rh34_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1212,7299,'','".AddSlashes(pg_result($resaco,$iresaco,'rh34_codban'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1212,7300,'','".AddSlashes(pg_result($resaco,$iresaco,'rh34_agencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1212,7301,'','".AddSlashes(pg_result($resaco,$iresaco,'rh34_dvagencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1212,7302,'','".AddSlashes(pg_result($resaco,$iresaco,'rh34_conta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1212,7303,'','".AddSlashes(pg_result($resaco,$iresaco,'rh34_dvconta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1212,7305,'','".AddSlashes(pg_result($resaco,$iresaco,'rh34_convenio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1212,7306,'','".AddSlashes(pg_result($resaco,$iresaco,'rh34_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1212,7308,'','".AddSlashes(pg_result($resaco,$iresaco,'rh34_where'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1212,7304,'','".AddSlashes(pg_result($resaco,$iresaco,'rh34_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1212,20556,'','".AddSlashes(pg_result($resaco,$iresaco,'rh34_parametrotransmissaoheader'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1212,20557,'','".AddSlashes(pg_result($resaco,$iresaco,'rh34_parametrotransmissaolote'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1212,20558,'','".AddSlashes(pg_result($resaco,$iresaco,'rh34_codigocompromisso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rharqbanco
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh34_codarq != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh34_codarq = $rh34_codarq ";
        }
        if($rh34_instit != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh34_instit = $rh34_instit ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Arquivos dos bancos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh34_codarq."-".$rh34_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Arquivos dos bancos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh34_codarq."-".$rh34_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh34_codarq."-".$rh34_instit;
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
        $this->erro_sql   = "Record Vazio na Tabela:rharqbanco";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $rh34_codarq=null,$rh34_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rharqbanco ";
     $sql .= "      inner join db_config  on  db_config.codigo = rharqbanco.rh34_instit";
     $sql .= "      inner join db_bancos  on  db_bancos.db90_codban = rharqbanco.rh34_codban";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql2 = "";
     if($dbwhere==""){
       if($rh34_codarq!=null ){
         $sql2 .= " where rharqbanco.rh34_codarq = $rh34_codarq "; 
       } 
       if($rh34_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rharqbanco.rh34_instit = $rh34_instit "; 
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
   // funcao do sql 
   function sql_query_file ( $rh34_codarq=null,$rh34_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rharqbanco ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh34_codarq!=null ){
         $sql2 .= " where rharqbanco.rh34_codarq = $rh34_codarq "; 
       } 
       if($rh34_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rharqbanco.rh34_instit = $rh34_instit "; 
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