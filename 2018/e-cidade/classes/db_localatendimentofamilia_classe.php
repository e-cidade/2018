<?
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

//MODULO: social
//CLASSE DA ENTIDADE localatendimentofamilia
class cl_localatendimentofamilia { 
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
   var $as23_sequencial = 0; 
   var $as23_localatendimentosocial = 0; 
   var $as23_cidadaofamilia = 0; 
   var $as23_datavinculo_dia = null; 
   var $as23_datavinculo_mes = null; 
   var $as23_datavinculo_ano = null; 
   var $as23_datavinculo = null; 
   var $as23_fimatendimento_dia = null; 
   var $as23_fimatendimento_mes = null; 
   var $as23_fimatendimento_ano = null; 
   var $as23_fimatendimento = null; 
   var $as23_observacao = null; 
   var $as23_ativo = 'f'; 
   var $as23_db_usuario = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 as23_sequencial = int4 = Código Local Atendimento Família 
                 as23_localatendimentosocial = int4 = Local Atendimento Social 
                 as23_cidadaofamilia = int4 = Cidadão Família 
                 as23_datavinculo = date = Data de Vínculo 
                 as23_fimatendimento = date = Data do Fim do Atendimento 
                 as23_observacao = text = Observação 
                 as23_ativo = bool = Ativo 
                 as23_db_usuario = int4 = Usuário 
                 ";
   //funcao construtor da classe 
   function cl_localatendimentofamilia() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("localatendimentofamilia"); 
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
       $this->as23_sequencial = ($this->as23_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["as23_sequencial"]:$this->as23_sequencial);
       $this->as23_localatendimentosocial = ($this->as23_localatendimentosocial == ""?@$GLOBALS["HTTP_POST_VARS"]["as23_localatendimentosocial"]:$this->as23_localatendimentosocial);
       $this->as23_cidadaofamilia = ($this->as23_cidadaofamilia == ""?@$GLOBALS["HTTP_POST_VARS"]["as23_cidadaofamilia"]:$this->as23_cidadaofamilia);
       if($this->as23_datavinculo == ""){
         $this->as23_datavinculo_dia = ($this->as23_datavinculo_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["as23_datavinculo_dia"]:$this->as23_datavinculo_dia);
         $this->as23_datavinculo_mes = ($this->as23_datavinculo_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["as23_datavinculo_mes"]:$this->as23_datavinculo_mes);
         $this->as23_datavinculo_ano = ($this->as23_datavinculo_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["as23_datavinculo_ano"]:$this->as23_datavinculo_ano);
         if($this->as23_datavinculo_dia != ""){
            $this->as23_datavinculo = $this->as23_datavinculo_ano."-".$this->as23_datavinculo_mes."-".$this->as23_datavinculo_dia;
         }
       }
       if($this->as23_fimatendimento == ""){
         $this->as23_fimatendimento_dia = ($this->as23_fimatendimento_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["as23_fimatendimento_dia"]:$this->as23_fimatendimento_dia);
         $this->as23_fimatendimento_mes = ($this->as23_fimatendimento_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["as23_fimatendimento_mes"]:$this->as23_fimatendimento_mes);
         $this->as23_fimatendimento_ano = ($this->as23_fimatendimento_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["as23_fimatendimento_ano"]:$this->as23_fimatendimento_ano);
         if($this->as23_fimatendimento_dia != ""){
            $this->as23_fimatendimento = $this->as23_fimatendimento_ano."-".$this->as23_fimatendimento_mes."-".$this->as23_fimatendimento_dia;
         }
       }
       $this->as23_observacao = ($this->as23_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["as23_observacao"]:$this->as23_observacao);
       $this->as23_ativo = ($this->as23_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["as23_ativo"]:$this->as23_ativo);
       $this->as23_db_usuario = ($this->as23_db_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["as23_db_usuario"]:$this->as23_db_usuario);
     }else{
       $this->as23_sequencial = ($this->as23_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["as23_sequencial"]:$this->as23_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($as23_sequencial){ 
      $this->atualizacampos();
     if($this->as23_localatendimentosocial == null ){ 
       $this->erro_sql = " Campo Local Atendimento Social nao Informado.";
       $this->erro_campo = "as23_localatendimentosocial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->as23_cidadaofamilia == null ){ 
       $this->erro_sql = " Campo Cidadão Família nao Informado.";
       $this->erro_campo = "as23_cidadaofamilia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->as23_datavinculo == null ){ 
       $this->erro_sql = " Campo Data de Vínculo nao Informado.";
       $this->erro_campo = "as23_datavinculo_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->as23_fimatendimento == null ){ 
       $this->as23_fimatendimento = "null";
     }
     if($this->as23_ativo == null ){ 
       $this->as23_ativo = "f";
     }
     if($this->as23_db_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "as23_db_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($as23_sequencial == "" || $as23_sequencial == null ){
       $result = db_query("select nextval('localatendimentofamilia_as23_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: localatendimentofamilia_as23_sequencial_seq do campo: as23_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->as23_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from localatendimentofamilia_as23_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $as23_sequencial)){
         $this->erro_sql = " Campo as23_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->as23_sequencial = $as23_sequencial; 
       }
     }
     if(($this->as23_sequencial == null) || ($this->as23_sequencial == "") ){ 
       $this->erro_sql = " Campo as23_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into localatendimentofamilia(
                                       as23_sequencial 
                                      ,as23_localatendimentosocial 
                                      ,as23_cidadaofamilia 
                                      ,as23_datavinculo 
                                      ,as23_fimatendimento 
                                      ,as23_observacao 
                                      ,as23_ativo 
                                      ,as23_db_usuario 
                       )
                values (
                                $this->as23_sequencial 
                               ,$this->as23_localatendimentosocial 
                               ,$this->as23_cidadaofamilia 
                               ,".($this->as23_datavinculo == "null" || $this->as23_datavinculo == ""?"null":"'".$this->as23_datavinculo."'")." 
                               ,".($this->as23_fimatendimento == "null" || $this->as23_fimatendimento == ""?"null":"'".$this->as23_fimatendimento."'")." 
                               ,'$this->as23_observacao' 
                               ,'$this->as23_ativo' 
                               ,$this->as23_db_usuario 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Local de Atendimento da Família ($this->as23_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Local de Atendimento da Família já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Local de Atendimento da Família ($this->as23_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->as23_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->as23_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19996,'$this->as23_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3585,19996,'','".AddSlashes(pg_result($resaco,0,'as23_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3585,19997,'','".AddSlashes(pg_result($resaco,0,'as23_localatendimentosocial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3585,19998,'','".AddSlashes(pg_result($resaco,0,'as23_cidadaofamilia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3585,19999,'','".AddSlashes(pg_result($resaco,0,'as23_datavinculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3585,20000,'','".AddSlashes(pg_result($resaco,0,'as23_fimatendimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3585,20001,'','".AddSlashes(pg_result($resaco,0,'as23_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3585,20002,'','".AddSlashes(pg_result($resaco,0,'as23_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3585,20003,'','".AddSlashes(pg_result($resaco,0,'as23_db_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($as23_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update localatendimentofamilia set ";
     $virgula = "";
     if(trim($this->as23_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as23_sequencial"])){ 
       $sql  .= $virgula." as23_sequencial = $this->as23_sequencial ";
       $virgula = ",";
       if(trim($this->as23_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Local Atendimento Família nao Informado.";
         $this->erro_campo = "as23_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as23_localatendimentosocial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as23_localatendimentosocial"])){ 
       $sql  .= $virgula." as23_localatendimentosocial = $this->as23_localatendimentosocial ";
       $virgula = ",";
       if(trim($this->as23_localatendimentosocial) == null ){ 
         $this->erro_sql = " Campo Local Atendimento Social nao Informado.";
         $this->erro_campo = "as23_localatendimentosocial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as23_cidadaofamilia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as23_cidadaofamilia"])){ 
       $sql  .= $virgula." as23_cidadaofamilia = $this->as23_cidadaofamilia ";
       $virgula = ",";
       if(trim($this->as23_cidadaofamilia) == null ){ 
         $this->erro_sql = " Campo Cidadão Família nao Informado.";
         $this->erro_campo = "as23_cidadaofamilia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as23_datavinculo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as23_datavinculo_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["as23_datavinculo_dia"] !="") ){ 
       $sql  .= $virgula." as23_datavinculo = '$this->as23_datavinculo' ";
       $virgula = ",";
       if(trim($this->as23_datavinculo) == null ){ 
         $this->erro_sql = " Campo Data de Vínculo nao Informado.";
         $this->erro_campo = "as23_datavinculo_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["as23_datavinculo_dia"])){ 
         $sql  .= $virgula." as23_datavinculo = null ";
         $virgula = ",";
         if(trim($this->as23_datavinculo) == null ){ 
           $this->erro_sql = " Campo Data de Vínculo nao Informado.";
           $this->erro_campo = "as23_datavinculo_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->as23_fimatendimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as23_fimatendimento_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["as23_fimatendimento_dia"] !="") ){ 
       $sql  .= $virgula." as23_fimatendimento = '$this->as23_fimatendimento' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["as23_fimatendimento_dia"])){ 
         $sql  .= $virgula." as23_fimatendimento = null ";
         $virgula = ",";
       }
     }
     if(trim($this->as23_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as23_observacao"])){ 
       $sql  .= $virgula." as23_observacao = '$this->as23_observacao' ";
       $virgula = ",";
     }
     if(trim($this->as23_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as23_ativo"])){ 
       $sql  .= $virgula." as23_ativo = '$this->as23_ativo' ";
       $virgula = ",";
     }
     if(trim($this->as23_db_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as23_db_usuario"])){ 
       $sql  .= $virgula." as23_db_usuario = $this->as23_db_usuario ";
       $virgula = ",";
       if(trim($this->as23_db_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "as23_db_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($as23_sequencial!=null){
       $sql .= " as23_sequencial = $this->as23_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->as23_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,19996,'$this->as23_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as23_sequencial"]) || $this->as23_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3585,19996,'".AddSlashes(pg_result($resaco,$conresaco,'as23_sequencial'))."','$this->as23_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as23_localatendimentosocial"]) || $this->as23_localatendimentosocial != "")
             $resac = db_query("insert into db_acount values($acount,3585,19997,'".AddSlashes(pg_result($resaco,$conresaco,'as23_localatendimentosocial'))."','$this->as23_localatendimentosocial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as23_cidadaofamilia"]) || $this->as23_cidadaofamilia != "")
             $resac = db_query("insert into db_acount values($acount,3585,19998,'".AddSlashes(pg_result($resaco,$conresaco,'as23_cidadaofamilia'))."','$this->as23_cidadaofamilia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as23_datavinculo"]) || $this->as23_datavinculo != "")
             $resac = db_query("insert into db_acount values($acount,3585,19999,'".AddSlashes(pg_result($resaco,$conresaco,'as23_datavinculo'))."','$this->as23_datavinculo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as23_fimatendimento"]) || $this->as23_fimatendimento != "")
             $resac = db_query("insert into db_acount values($acount,3585,20000,'".AddSlashes(pg_result($resaco,$conresaco,'as23_fimatendimento'))."','$this->as23_fimatendimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as23_observacao"]) || $this->as23_observacao != "")
             $resac = db_query("insert into db_acount values($acount,3585,20001,'".AddSlashes(pg_result($resaco,$conresaco,'as23_observacao'))."','$this->as23_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as23_ativo"]) || $this->as23_ativo != "")
             $resac = db_query("insert into db_acount values($acount,3585,20002,'".AddSlashes(pg_result($resaco,$conresaco,'as23_ativo'))."','$this->as23_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as23_db_usuario"]) || $this->as23_db_usuario != "")
             $resac = db_query("insert into db_acount values($acount,3585,20003,'".AddSlashes(pg_result($resaco,$conresaco,'as23_db_usuario'))."','$this->as23_db_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Local de Atendimento da Família nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->as23_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Local de Atendimento da Família nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->as23_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->as23_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($as23_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($as23_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,19996,'$as23_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3585,19996,'','".AddSlashes(pg_result($resaco,$iresaco,'as23_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3585,19997,'','".AddSlashes(pg_result($resaco,$iresaco,'as23_localatendimentosocial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3585,19998,'','".AddSlashes(pg_result($resaco,$iresaco,'as23_cidadaofamilia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3585,19999,'','".AddSlashes(pg_result($resaco,$iresaco,'as23_datavinculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3585,20000,'','".AddSlashes(pg_result($resaco,$iresaco,'as23_fimatendimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3585,20001,'','".AddSlashes(pg_result($resaco,$iresaco,'as23_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3585,20002,'','".AddSlashes(pg_result($resaco,$iresaco,'as23_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3585,20003,'','".AddSlashes(pg_result($resaco,$iresaco,'as23_db_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from localatendimentofamilia
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($as23_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " as23_sequencial = $as23_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Local de Atendimento da Família nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$as23_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Local de Atendimento da Família nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$as23_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$as23_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:localatendimentofamilia";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $as23_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from localatendimentofamilia ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = localatendimentofamilia.as23_db_usuario";
     $sql .= "      inner join cidadaofamilia  on  cidadaofamilia.as04_sequencial = localatendimentofamilia.as23_cidadaofamilia";
     $sql .= "      inner join localatendimentosocial  on  localatendimentosocial.as16_sequencial = localatendimentofamilia.as23_localatendimentosocial";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = localatendimentosocial.as16_db_depart";
     $sql2 = "";
     if($dbwhere==""){
       if($as23_sequencial!=null ){
         $sql2 .= " where localatendimentofamilia.as23_sequencial = $as23_sequencial "; 
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
   function sql_query_file ( $as23_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from localatendimentofamilia ";
     $sql2 = "";
     if($dbwhere==""){
       if($as23_sequencial!=null ){
         $sql2 .= " where localatendimentofamilia.as23_sequencial = $as23_sequencial "; 
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