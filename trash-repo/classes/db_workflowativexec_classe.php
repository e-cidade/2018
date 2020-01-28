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

//MODULO: configuracoes
//CLASSE DA ENTIDADE workflowativexec
class cl_workflowativexec { 
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
   var $db113_sequencial = 0; 
   var $db113_workflowativ = 0; 
   var $db113_id_usuario = 0; 
   var $db113_dtexecucao_dia = null; 
   var $db113_dtexecucao_mes = null; 
   var $db113_dtexecucao_ano = null; 
   var $db113_dtexecucao = null; 
   var $db113_obs = null; 
   var $db113_concluido = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db113_sequencial = int4 = Código Sequencial 
                 db113_workflowativ = int4 = Código Work Flow Atividade 
                 db113_id_usuario = int4 = Código Usuário 
                 db113_dtexecucao = date = Data Execução 
                 db113_obs = text = Observação 
                 db113_concluido = bool = Concluído 
                 ";
   //funcao construtor da classe 
   function cl_workflowativexec() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("workflowativexec"); 
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
       $this->db113_sequencial = ($this->db113_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db113_sequencial"]:$this->db113_sequencial);
       $this->db113_workflowativ = ($this->db113_workflowativ == ""?@$GLOBALS["HTTP_POST_VARS"]["db113_workflowativ"]:$this->db113_workflowativ);
       $this->db113_id_usuario = ($this->db113_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["db113_id_usuario"]:$this->db113_id_usuario);
       if($this->db113_dtexecucao == ""){
         $this->db113_dtexecucao_dia = ($this->db113_dtexecucao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["db113_dtexecucao_dia"]:$this->db113_dtexecucao_dia);
         $this->db113_dtexecucao_mes = ($this->db113_dtexecucao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["db113_dtexecucao_mes"]:$this->db113_dtexecucao_mes);
         $this->db113_dtexecucao_ano = ($this->db113_dtexecucao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["db113_dtexecucao_ano"]:$this->db113_dtexecucao_ano);
         if($this->db113_dtexecucao_dia != ""){
            $this->db113_dtexecucao = $this->db113_dtexecucao_ano."-".$this->db113_dtexecucao_mes."-".$this->db113_dtexecucao_dia;
         }
       }
       $this->db113_obs = ($this->db113_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["db113_obs"]:$this->db113_obs);
       $this->db113_concluido = ($this->db113_concluido == "f"?@$GLOBALS["HTTP_POST_VARS"]["db113_concluido"]:$this->db113_concluido);
     }else{
       $this->db113_sequencial = ($this->db113_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db113_sequencial"]:$this->db113_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($db113_sequencial){ 
      $this->atualizacampos();
     if($this->db113_workflowativ == null ){ 
       $this->erro_sql = " Campo Código Work Flow Atividade nao Informado.";
       $this->erro_campo = "db113_workflowativ";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db113_id_usuario == null ){ 
       $this->erro_sql = " Campo Código Usuário nao Informado.";
       $this->erro_campo = "db113_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db113_dtexecucao == null ){ 
       $this->erro_sql = " Campo Data Execução nao Informado.";
       $this->erro_campo = "db113_dtexecucao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db113_obs == null ){ 
       $this->erro_sql = " Campo Observação nao Informado.";
       $this->erro_campo = "db113_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db113_concluido == null ){ 
       $this->erro_sql = " Campo Concluído nao Informado.";
       $this->erro_campo = "db113_concluido";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db113_sequencial == "" || $db113_sequencial == null ){
       $result = db_query("select nextval('workflowativexec_db113_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: workflowativexec_db113_sequencial_seq do campo: db113_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db113_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from workflowativexec_db113_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $db113_sequencial)){
         $this->erro_sql = " Campo db113_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db113_sequencial = $db113_sequencial; 
       }
     }
     if(($this->db113_sequencial == null) || ($this->db113_sequencial == "") ){ 
       $this->erro_sql = " Campo db113_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into workflowativexec(
                                       db113_sequencial 
                                      ,db113_workflowativ 
                                      ,db113_id_usuario 
                                      ,db113_dtexecucao 
                                      ,db113_obs 
                                      ,db113_concluido 
                       )
                values (
                                $this->db113_sequencial 
                               ,$this->db113_workflowativ 
                               ,$this->db113_id_usuario 
                               ,".($this->db113_dtexecucao == "null" || $this->db113_dtexecucao == ""?"null":"'".$this->db113_dtexecucao."'")." 
                               ,'$this->db113_obs' 
                               ,'$this->db113_concluido' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "workflowativexec ($this->db113_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "workflowativexec já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "workflowativexec ($this->db113_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db113_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db113_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17867,'$this->db113_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3156,17867,'','".AddSlashes(pg_result($resaco,0,'db113_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3156,17868,'','".AddSlashes(pg_result($resaco,0,'db113_workflowativ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3156,17869,'','".AddSlashes(pg_result($resaco,0,'db113_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3156,17870,'','".AddSlashes(pg_result($resaco,0,'db113_dtexecucao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3156,17871,'','".AddSlashes(pg_result($resaco,0,'db113_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3156,17872,'','".AddSlashes(pg_result($resaco,0,'db113_concluido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db113_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update workflowativexec set ";
     $virgula = "";
     if(trim($this->db113_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db113_sequencial"])){ 
       $sql  .= $virgula." db113_sequencial = $this->db113_sequencial ";
       $virgula = ",";
       if(trim($this->db113_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "db113_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db113_workflowativ)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db113_workflowativ"])){ 
       $sql  .= $virgula." db113_workflowativ = $this->db113_workflowativ ";
       $virgula = ",";
       if(trim($this->db113_workflowativ) == null ){ 
         $this->erro_sql = " Campo Código Work Flow Atividade nao Informado.";
         $this->erro_campo = "db113_workflowativ";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db113_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db113_id_usuario"])){ 
       $sql  .= $virgula." db113_id_usuario = $this->db113_id_usuario ";
       $virgula = ",";
       if(trim($this->db113_id_usuario) == null ){ 
         $this->erro_sql = " Campo Código Usuário nao Informado.";
         $this->erro_campo = "db113_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db113_dtexecucao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db113_dtexecucao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["db113_dtexecucao_dia"] !="") ){ 
       $sql  .= $virgula." db113_dtexecucao = '$this->db113_dtexecucao' ";
       $virgula = ",";
       if(trim($this->db113_dtexecucao) == null ){ 
         $this->erro_sql = " Campo Data Execução nao Informado.";
         $this->erro_campo = "db113_dtexecucao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["db113_dtexecucao_dia"])){ 
         $sql  .= $virgula." db113_dtexecucao = null ";
         $virgula = ",";
         if(trim($this->db113_dtexecucao) == null ){ 
           $this->erro_sql = " Campo Data Execução nao Informado.";
           $this->erro_campo = "db113_dtexecucao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->db113_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db113_obs"])){ 
       $sql  .= $virgula." db113_obs = '$this->db113_obs' ";
       $virgula = ",";
       if(trim($this->db113_obs) == null ){ 
         $this->erro_sql = " Campo Observação nao Informado.";
         $this->erro_campo = "db113_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db113_concluido)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db113_concluido"])){ 
       $sql  .= $virgula." db113_concluido = '$this->db113_concluido' ";
       $virgula = ",";
       if(trim($this->db113_concluido) == null ){ 
         $this->erro_sql = " Campo Concluído nao Informado.";
         $this->erro_campo = "db113_concluido";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($db113_sequencial!=null){
       $sql .= " db113_sequencial = $this->db113_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db113_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17867,'$this->db113_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db113_sequencial"]) || $this->db113_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3156,17867,'".AddSlashes(pg_result($resaco,$conresaco,'db113_sequencial'))."','$this->db113_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db113_workflowativ"]) || $this->db113_workflowativ != "")
           $resac = db_query("insert into db_acount values($acount,3156,17868,'".AddSlashes(pg_result($resaco,$conresaco,'db113_workflowativ'))."','$this->db113_workflowativ',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db113_id_usuario"]) || $this->db113_id_usuario != "")
           $resac = db_query("insert into db_acount values($acount,3156,17869,'".AddSlashes(pg_result($resaco,$conresaco,'db113_id_usuario'))."','$this->db113_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db113_dtexecucao"]) || $this->db113_dtexecucao != "")
           $resac = db_query("insert into db_acount values($acount,3156,17870,'".AddSlashes(pg_result($resaco,$conresaco,'db113_dtexecucao'))."','$this->db113_dtexecucao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db113_obs"]) || $this->db113_obs != "")
           $resac = db_query("insert into db_acount values($acount,3156,17871,'".AddSlashes(pg_result($resaco,$conresaco,'db113_obs'))."','$this->db113_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db113_concluido"]) || $this->db113_concluido != "")
           $resac = db_query("insert into db_acount values($acount,3156,17872,'".AddSlashes(pg_result($resaco,$conresaco,'db113_concluido'))."','$this->db113_concluido',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "workflowativexec nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db113_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "workflowativexec nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db113_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db113_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db113_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db113_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17867,'$db113_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3156,17867,'','".AddSlashes(pg_result($resaco,$iresaco,'db113_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3156,17868,'','".AddSlashes(pg_result($resaco,$iresaco,'db113_workflowativ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3156,17869,'','".AddSlashes(pg_result($resaco,$iresaco,'db113_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3156,17870,'','".AddSlashes(pg_result($resaco,$iresaco,'db113_dtexecucao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3156,17871,'','".AddSlashes(pg_result($resaco,$iresaco,'db113_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3156,17872,'','".AddSlashes(pg_result($resaco,$iresaco,'db113_concluido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from workflowativexec
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db113_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db113_sequencial = $db113_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "workflowativexec nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db113_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "workflowativexec nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db113_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db113_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:workflowativexec";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $db113_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from workflowativexec ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = workflowativexec.db113_id_usuario";
     $sql .= "      inner join workflowativ  on  workflowativ.db114_sequencial = workflowativexec.db113_workflowativ";
     $sql .= "      inner join workflow  as a on   a.db112_sequencial = workflowativ.db114_workflow";
     $sql2 = "";
     if($dbwhere==""){
       if($db113_sequencial!=null ){
         $sql2 .= " where workflowativexec.db113_sequencial = $db113_sequencial "; 
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
   function sql_query_file ( $db113_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from workflowativexec ";
     $sql2 = "";
     if($dbwhere==""){
       if($db113_sequencial!=null ){
         $sql2 .= " where workflowativexec.db113_sequencial = $db113_sequencial "; 
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