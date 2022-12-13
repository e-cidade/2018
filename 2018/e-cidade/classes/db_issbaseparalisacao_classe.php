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

//MODULO: issqn
//CLASSE DA ENTIDADE issbaseparalisacao
class cl_issbaseparalisacao { 
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
   var $q140_sequencial = 0; 
   var $q140_issbase = 0; 
   var $q140_issmotivoparalisacao = 0; 
   var $q140_datainicio_dia = null; 
   var $q140_datainicio_mes = null; 
   var $q140_datainicio_ano = null; 
   var $q140_datainicio = null; 
   var $q140_datafim_dia = null; 
   var $q140_datafim_mes = null; 
   var $q140_datafim_ano = null; 
   var $q140_datafim = null; 
   var $q140_observacao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q140_sequencial = int4 = Sequencial 
                 q140_issbase = int4 = Código issbase 
                 q140_issmotivoparalisacao = int4 = Motivo paralisação 
                 q140_datainicio = date = Data de inicio 
                 q140_datafim = date = Data final 
                 q140_observacao = text = Observação 
                 ";
   //funcao construtor da classe 
   function cl_issbaseparalisacao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("issbaseparalisacao"); 
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
       $this->q140_sequencial = ($this->q140_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q140_sequencial"]:$this->q140_sequencial);
       $this->q140_issbase = ($this->q140_issbase == ""?@$GLOBALS["HTTP_POST_VARS"]["q140_issbase"]:$this->q140_issbase);
       $this->q140_issmotivoparalisacao = ($this->q140_issmotivoparalisacao == ""?@$GLOBALS["HTTP_POST_VARS"]["q140_issmotivoparalisacao"]:$this->q140_issmotivoparalisacao);
       if($this->q140_datainicio == ""){
         $this->q140_datainicio_dia = ($this->q140_datainicio_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q140_datainicio_dia"]:$this->q140_datainicio_dia);
         $this->q140_datainicio_mes = ($this->q140_datainicio_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q140_datainicio_mes"]:$this->q140_datainicio_mes);
         $this->q140_datainicio_ano = ($this->q140_datainicio_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q140_datainicio_ano"]:$this->q140_datainicio_ano);
         if($this->q140_datainicio_dia != ""){
            $this->q140_datainicio = $this->q140_datainicio_ano."-".$this->q140_datainicio_mes."-".$this->q140_datainicio_dia;
         }
       }
       if($this->q140_datafim == ""){
         $this->q140_datafim_dia = ($this->q140_datafim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q140_datafim_dia"]:$this->q140_datafim_dia);
         $this->q140_datafim_mes = ($this->q140_datafim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q140_datafim_mes"]:$this->q140_datafim_mes);
         $this->q140_datafim_ano = ($this->q140_datafim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q140_datafim_ano"]:$this->q140_datafim_ano);
         if($this->q140_datafim_dia != ""){
            $this->q140_datafim = $this->q140_datafim_ano."-".$this->q140_datafim_mes."-".$this->q140_datafim_dia;
         }
       }
       $this->q140_observacao = ($this->q140_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["q140_observacao"]:$this->q140_observacao);
     }else{
       $this->q140_sequencial = ($this->q140_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q140_sequencial"]:$this->q140_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($q140_sequencial){ 
      $this->atualizacampos();
     if($this->q140_issbase == null ){ 
       $this->erro_sql = " Campo Código issbase nao Informado.";
       $this->erro_campo = "q140_issbase";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q140_issmotivoparalisacao == null ){ 
       $this->erro_sql = " Campo Motivo paralisação nao Informado.";
       $this->erro_campo = "q140_issmotivoparalisacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q140_datainicio == null ){ 
       $this->erro_sql = " Campo Data de inicio nao Informado.";
       $this->erro_campo = "q140_datainicio_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q140_datafim == null ){ 
       $this->q140_datafim = "null";
     }
     if($this->q140_observacao == null ){ 
       $this->erro_sql = " Campo Observação nao Informado.";
       $this->erro_campo = "q140_observacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q140_sequencial == "" || $q140_sequencial == null ){
       $result = db_query("select nextval('issbaseparalisacao_q140_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: issbaseparalisacao_q140_sequencial_seq do campo: q140_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q140_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from issbaseparalisacao_q140_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q140_sequencial)){
         $this->erro_sql = " Campo q140_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q140_sequencial = $q140_sequencial; 
       }
     }
     if(($this->q140_sequencial == null) || ($this->q140_sequencial == "") ){ 
       $this->erro_sql = " Campo q140_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into issbaseparalisacao(
                                       q140_sequencial 
                                      ,q140_issbase 
                                      ,q140_issmotivoparalisacao 
                                      ,q140_datainicio 
                                      ,q140_datafim 
                                      ,q140_observacao 
                       )
                values (
                                $this->q140_sequencial 
                               ,$this->q140_issbase 
                               ,$this->q140_issmotivoparalisacao 
                               ,".($this->q140_datainicio == "null" || $this->q140_datainicio == ""?"null":"'".$this->q140_datainicio."'")." 
                               ,".($this->q140_datafim == "null" || $this->q140_datafim == ""?"null":"'".$this->q140_datafim."'")." 
                               ,'$this->q140_observacao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Paralisação ($this->q140_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Paralisação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Paralisação ($this->q140_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q140_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->q140_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20172,'$this->q140_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3621,20172,'','".AddSlashes(pg_result($resaco,0,'q140_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3621,20173,'','".AddSlashes(pg_result($resaco,0,'q140_issbase'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3621,20174,'','".AddSlashes(pg_result($resaco,0,'q140_issmotivoparalisacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3621,20175,'','".AddSlashes(pg_result($resaco,0,'q140_datainicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3621,20176,'','".AddSlashes(pg_result($resaco,0,'q140_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3621,20177,'','".AddSlashes(pg_result($resaco,0,'q140_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q140_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update issbaseparalisacao set ";
     $virgula = "";
     if(trim($this->q140_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q140_sequencial"])){ 
       $sql  .= $virgula." q140_sequencial = $this->q140_sequencial ";
       $virgula = ",";
       if(trim($this->q140_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "q140_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q140_issbase)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q140_issbase"])){ 
       $sql  .= $virgula." q140_issbase = $this->q140_issbase ";
       $virgula = ",";
       if(trim($this->q140_issbase) == null ){ 
         $this->erro_sql = " Campo Código issbase nao Informado.";
         $this->erro_campo = "q140_issbase";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q140_issmotivoparalisacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q140_issmotivoparalisacao"])){ 
       $sql  .= $virgula." q140_issmotivoparalisacao = $this->q140_issmotivoparalisacao ";
       $virgula = ",";
       if(trim($this->q140_issmotivoparalisacao) == null ){ 
         $this->erro_sql = " Campo Motivo paralisação nao Informado.";
         $this->erro_campo = "q140_issmotivoparalisacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q140_datainicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q140_datainicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q140_datainicio_dia"] !="") ){ 
       $sql  .= $virgula." q140_datainicio = '$this->q140_datainicio' ";
       $virgula = ",";
       if(trim($this->q140_datainicio) == null ){ 
         $this->erro_sql = " Campo Data de inicio nao Informado.";
         $this->erro_campo = "q140_datainicio_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["q140_datainicio_dia"])){ 
         $sql  .= $virgula." q140_datainicio = null ";
         $virgula = ",";
         if(trim($this->q140_datainicio) == null ){ 
           $this->erro_sql = " Campo Data de inicio nao Informado.";
           $this->erro_campo = "q140_datainicio_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->q140_datafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q140_datafim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q140_datafim_dia"] !="") ){ 
       $sql  .= $virgula." q140_datafim = '$this->q140_datafim' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["q140_datafim_dia"])){ 
         $sql  .= $virgula." q140_datafim = null ";
         $virgula = ",";
       }
     }
     if(trim($this->q140_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q140_observacao"])){ 
       $sql  .= $virgula." q140_observacao = '$this->q140_observacao' ";
       $virgula = ",";
       if(trim($this->q140_observacao) == null ){ 
         $this->erro_sql = " Campo Observação nao Informado.";
         $this->erro_campo = "q140_observacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q140_sequencial!=null){
       $sql .= " q140_sequencial = $this->q140_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->q140_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20172,'$this->q140_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["q140_sequencial"]) || $this->q140_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3621,20172,'".AddSlashes(pg_result($resaco,$conresaco,'q140_sequencial'))."','$this->q140_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["q140_issbase"]) || $this->q140_issbase != "")
             $resac = db_query("insert into db_acount values($acount,3621,20173,'".AddSlashes(pg_result($resaco,$conresaco,'q140_issbase'))."','$this->q140_issbase',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["q140_issmotivoparalisacao"]) || $this->q140_issmotivoparalisacao != "")
             $resac = db_query("insert into db_acount values($acount,3621,20174,'".AddSlashes(pg_result($resaco,$conresaco,'q140_issmotivoparalisacao'))."','$this->q140_issmotivoparalisacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["q140_datainicio"]) || $this->q140_datainicio != "")
             $resac = db_query("insert into db_acount values($acount,3621,20175,'".AddSlashes(pg_result($resaco,$conresaco,'q140_datainicio'))."','$this->q140_datainicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["q140_datafim"]) || $this->q140_datafim != "")
             $resac = db_query("insert into db_acount values($acount,3621,20176,'".AddSlashes(pg_result($resaco,$conresaco,'q140_datafim'))."','$this->q140_datafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["q140_observacao"]) || $this->q140_observacao != "")
             $resac = db_query("insert into db_acount values($acount,3621,20177,'".AddSlashes(pg_result($resaco,$conresaco,'q140_observacao'))."','$this->q140_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Paralisação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q140_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Paralisação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q140_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q140_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q140_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($q140_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20172,'$q140_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3621,20172,'','".AddSlashes(pg_result($resaco,$iresaco,'q140_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3621,20173,'','".AddSlashes(pg_result($resaco,$iresaco,'q140_issbase'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3621,20174,'','".AddSlashes(pg_result($resaco,$iresaco,'q140_issmotivoparalisacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3621,20175,'','".AddSlashes(pg_result($resaco,$iresaco,'q140_datainicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3621,20176,'','".AddSlashes(pg_result($resaco,$iresaco,'q140_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3621,20177,'','".AddSlashes(pg_result($resaco,$iresaco,'q140_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from issbaseparalisacao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q140_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q140_sequencial = $q140_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Paralisação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q140_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Paralisação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q140_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q140_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:issbaseparalisacao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $q140_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issbaseparalisacao ";
     $sql .= "      inner join issbase  on  issbase.q02_inscr = issbaseparalisacao.q140_issbase";
     $sql .= "      inner join issmotivoparalisacao  on  issmotivoparalisacao.q141_sequencial = issbaseparalisacao.q140_issmotivoparalisacao";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = issbase.q02_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($q140_sequencial!=null ){
         $sql2 .= " where issbaseparalisacao.q140_sequencial = $q140_sequencial "; 
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
   function sql_query_file ( $q140_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issbaseparalisacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($q140_sequencial!=null ){
         $sql2 .= " where issbaseparalisacao.q140_sequencial = $q140_sequencial "; 
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