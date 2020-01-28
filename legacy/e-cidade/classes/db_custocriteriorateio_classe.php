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

//MODULO: Custos
//CLASSE DA ENTIDADE custocriteriorateio
class cl_custocriteriorateio { 
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
   var $cc08_sequencial = 0; 
   var $cc08_instit = 0; 
   var $cc08_coddepto = 0; 
   var $cc08_matunid = 0; 
   var $cc08_descricao = null; 
   var $cc08_obs = null; 
   var $cc08_ativo = 'f'; 
   var $cc08_automatico = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 cc08_sequencial = int4 = Sequencial 
                 cc08_instit = int4 = Instituição 
                 cc08_coddepto = int4 = Departamento 
                 cc08_matunid = int4 = Código da unidade 
                 cc08_descricao = varchar(50) = Descrição 
                 cc08_obs = text = Observação 
                 cc08_ativo = bool = Ativo 
                 cc08_automatico = bool = Criterio Automático 
                 ";
   //funcao construtor da classe 
   function cl_custocriteriorateio() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("custocriteriorateio"); 
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
       $this->cc08_sequencial = ($this->cc08_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["cc08_sequencial"]:$this->cc08_sequencial);
       $this->cc08_instit = ($this->cc08_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["cc08_instit"]:$this->cc08_instit);
       $this->cc08_coddepto = ($this->cc08_coddepto == ""?@$GLOBALS["HTTP_POST_VARS"]["cc08_coddepto"]:$this->cc08_coddepto);
       $this->cc08_matunid = ($this->cc08_matunid == ""?@$GLOBALS["HTTP_POST_VARS"]["cc08_matunid"]:$this->cc08_matunid);
       $this->cc08_descricao = ($this->cc08_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["cc08_descricao"]:$this->cc08_descricao);
       $this->cc08_obs = ($this->cc08_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["cc08_obs"]:$this->cc08_obs);
       $this->cc08_ativo = ($this->cc08_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["cc08_ativo"]:$this->cc08_ativo);
       $this->cc08_automatico = ($this->cc08_automatico == "f"?@$GLOBALS["HTTP_POST_VARS"]["cc08_automatico"]:$this->cc08_automatico);
     }else{
       $this->cc08_sequencial = ($this->cc08_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["cc08_sequencial"]:$this->cc08_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($cc08_sequencial){ 
      $this->atualizacampos();
     if($this->cc08_instit == null ){ 
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "cc08_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cc08_coddepto == null ){ 
       $this->erro_sql = " Campo Departamento nao Informado.";
       $this->erro_campo = "cc08_coddepto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cc08_matunid == null ){ 
       $this->erro_sql = " Campo Código da unidade nao Informado.";
       $this->erro_campo = "cc08_matunid";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cc08_descricao == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "cc08_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cc08_obs == null ){ 
       $this->erro_sql = " Campo Observação nao Informado.";
       $this->erro_campo = "cc08_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cc08_ativo == null ){ 
       $this->erro_sql = " Campo Ativo nao Informado.";
       $this->erro_campo = "cc08_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cc08_automatico == null ){ 
       $this->erro_sql = " Campo Criterio Automático nao Informado.";
       $this->erro_campo = "cc08_automatico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($cc08_sequencial == "" || $cc08_sequencial == null ){
       $result = db_query("select nextval('custocriteriorateio_cc08_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: custocriteriorateio_cc08_sequencial_seq do campo: cc08_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->cc08_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from custocriteriorateio_cc08_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $cc08_sequencial)){
         $this->erro_sql = " Campo cc08_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->cc08_sequencial = $cc08_sequencial; 
       }
     }
     if(($this->cc08_sequencial == null) || ($this->cc08_sequencial == "") ){ 
       $this->erro_sql = " Campo cc08_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into custocriteriorateio(
                                       cc08_sequencial 
                                      ,cc08_instit 
                                      ,cc08_coddepto 
                                      ,cc08_matunid 
                                      ,cc08_descricao 
                                      ,cc08_obs 
                                      ,cc08_ativo 
                                      ,cc08_automatico 
                       )
                values (
                                $this->cc08_sequencial 
                               ,$this->cc08_instit 
                               ,$this->cc08_coddepto 
                               ,$this->cc08_matunid 
                               ,'$this->cc08_descricao' 
                               ,'$this->cc08_obs' 
                               ,'$this->cc08_ativo' 
                               ,'$this->cc08_automatico' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Custo do critério do rateio ($this->cc08_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Custo do critério do rateio já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Custo do critério do rateio ($this->cc08_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cc08_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->cc08_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12583,'$this->cc08_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2197,12583,'','".AddSlashes(pg_result($resaco,0,'cc08_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2197,12584,'','".AddSlashes(pg_result($resaco,0,'cc08_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2197,13421,'','".AddSlashes(pg_result($resaco,0,'cc08_coddepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2197,12585,'','".AddSlashes(pg_result($resaco,0,'cc08_matunid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2197,12586,'','".AddSlashes(pg_result($resaco,0,'cc08_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2197,12587,'','".AddSlashes(pg_result($resaco,0,'cc08_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2197,12588,'','".AddSlashes(pg_result($resaco,0,'cc08_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2197,13449,'','".AddSlashes(pg_result($resaco,0,'cc08_automatico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($cc08_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update custocriteriorateio set ";
     $virgula = "";
     if(trim($this->cc08_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc08_sequencial"])){ 
       $sql  .= $virgula." cc08_sequencial = $this->cc08_sequencial ";
       $virgula = ",";
       if(trim($this->cc08_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "cc08_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc08_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc08_instit"])){ 
       $sql  .= $virgula." cc08_instit = $this->cc08_instit ";
       $virgula = ",";
       if(trim($this->cc08_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "cc08_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc08_coddepto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc08_coddepto"])){ 
       $sql  .= $virgula." cc08_coddepto = $this->cc08_coddepto ";
       $virgula = ",";
       if(trim($this->cc08_coddepto) == null ){ 
         $this->erro_sql = " Campo Departamento nao Informado.";
         $this->erro_campo = "cc08_coddepto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc08_matunid)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc08_matunid"])){ 
       $sql  .= $virgula." cc08_matunid = $this->cc08_matunid ";
       $virgula = ",";
       if(trim($this->cc08_matunid) == null ){ 
         $this->erro_sql = " Campo Código da unidade nao Informado.";
         $this->erro_campo = "cc08_matunid";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc08_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc08_descricao"])){ 
       $sql  .= $virgula." cc08_descricao = '$this->cc08_descricao' ";
       $virgula = ",";
       if(trim($this->cc08_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "cc08_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc08_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc08_obs"])){ 
       $sql  .= $virgula." cc08_obs = '$this->cc08_obs' ";
       $virgula = ",";
       if(trim($this->cc08_obs) == null ){ 
         $this->erro_sql = " Campo Observação nao Informado.";
         $this->erro_campo = "cc08_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc08_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc08_ativo"])){ 
       $sql  .= $virgula." cc08_ativo = '$this->cc08_ativo' ";
       $virgula = ",";
       if(trim($this->cc08_ativo) == null ){ 
         $this->erro_sql = " Campo Ativo nao Informado.";
         $this->erro_campo = "cc08_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc08_automatico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc08_automatico"])){ 
       $sql  .= $virgula." cc08_automatico = '$this->cc08_automatico' ";
       $virgula = ",";
       if(trim($this->cc08_automatico) == null ){ 
         $this->erro_sql = " Campo Criterio Automático nao Informado.";
         $this->erro_campo = "cc08_automatico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($cc08_sequencial!=null){
       $sql .= " cc08_sequencial = $this->cc08_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->cc08_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12583,'$this->cc08_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc08_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2197,12583,'".AddSlashes(pg_result($resaco,$conresaco,'cc08_sequencial'))."','$this->cc08_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc08_instit"]))
           $resac = db_query("insert into db_acount values($acount,2197,12584,'".AddSlashes(pg_result($resaco,$conresaco,'cc08_instit'))."','$this->cc08_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc08_coddepto"]))
           $resac = db_query("insert into db_acount values($acount,2197,13421,'".AddSlashes(pg_result($resaco,$conresaco,'cc08_coddepto'))."','$this->cc08_coddepto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc08_matunid"]))
           $resac = db_query("insert into db_acount values($acount,2197,12585,'".AddSlashes(pg_result($resaco,$conresaco,'cc08_matunid'))."','$this->cc08_matunid',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc08_descricao"]))
           $resac = db_query("insert into db_acount values($acount,2197,12586,'".AddSlashes(pg_result($resaco,$conresaco,'cc08_descricao'))."','$this->cc08_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc08_obs"]))
           $resac = db_query("insert into db_acount values($acount,2197,12587,'".AddSlashes(pg_result($resaco,$conresaco,'cc08_obs'))."','$this->cc08_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc08_ativo"]))
           $resac = db_query("insert into db_acount values($acount,2197,12588,'".AddSlashes(pg_result($resaco,$conresaco,'cc08_ativo'))."','$this->cc08_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc08_automatico"]))
           $resac = db_query("insert into db_acount values($acount,2197,13449,'".AddSlashes(pg_result($resaco,$conresaco,'cc08_automatico'))."','$this->cc08_automatico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Custo do critério do rateio nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->cc08_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Custo do critério do rateio nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->cc08_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cc08_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($cc08_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($cc08_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12583,'$cc08_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2197,12583,'','".AddSlashes(pg_result($resaco,$iresaco,'cc08_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2197,12584,'','".AddSlashes(pg_result($resaco,$iresaco,'cc08_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2197,13421,'','".AddSlashes(pg_result($resaco,$iresaco,'cc08_coddepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2197,12585,'','".AddSlashes(pg_result($resaco,$iresaco,'cc08_matunid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2197,12586,'','".AddSlashes(pg_result($resaco,$iresaco,'cc08_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2197,12587,'','".AddSlashes(pg_result($resaco,$iresaco,'cc08_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2197,12588,'','".AddSlashes(pg_result($resaco,$iresaco,'cc08_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2197,13449,'','".AddSlashes(pg_result($resaco,$iresaco,'cc08_automatico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from custocriteriorateio
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($cc08_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cc08_sequencial = $cc08_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Custo do critério do rateio nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$cc08_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Custo do critério do rateio nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$cc08_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$cc08_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:custocriteriorateio";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $cc08_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from custocriteriorateio ";
     $sql .= "      inner join db_config  on  db_config.codigo = custocriteriorateio.cc08_instit";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = custocriteriorateio.cc08_coddepto";
     $sql .= "      inner join matunid  on  matunid.m61_codmatunid = custocriteriorateio.cc08_matunid";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($cc08_sequencial!=null ){
         $sql2 .= " where custocriteriorateio.cc08_sequencial = $cc08_sequencial "; 
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
   function sql_query_file ( $cc08_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from custocriteriorateio ";
     $sql2 = "";
     if($dbwhere==""){
       if($cc08_sequencial!=null ){
         $sql2 .= " where custocriteriorateio.cc08_sequencial = $cc08_sequencial "; 
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
  
  function sql_custocriterios($cc08_sequencial=null, $campos="*", $ordem=null, $dbwhere="") {
    
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
     $sql .= " from custocriteriorateio ";
     $sql .= "      inner join db_config                         on  db_config.codigo            = cc08_instit";
     $sql .= "      inner join db_depart                         on  db_depart.coddepto          = cc08_coddepto";
     $sql .= "      left  join custocriteriopcmater              on cc08_sequencial              = cc10_custocriteriorateio";
     $sql .= "      left  join pcmater                           on pc01_codmater                = cc10_pcmater";
     $sql .= "      left  join custocriteriorateiobens           on cc06_custocriteriorateio     = cc08_sequencial";
     $sql .= "      left  join custoplanoanaliticabens           on cc06_custoplanoanaliticabens = cc05_sequencial";
     $sql .= "      left  join bens                              on cc05_bens                    = t52_bem";
     $sql2 = "";
     if($dbwhere==""){
       
       if($cc08_sequencial!=null ){
         $sql2 .= " where custocriteriorateio.cc08_sequencial = $cc08_sequencial "; 
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