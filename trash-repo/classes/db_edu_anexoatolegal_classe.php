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

//MODULO: escola
//CLASSE DA ENTIDADE edu_anexoatolegal
class cl_edu_anexoatolegal { 
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
   var $ed292_sequencial = 0; 
   var $ed292_atolegal = 0; 
   var $ed292_nomearquivo = null; 
   var $ed292_arquivo = 0; 
   var $ed292_obs = null; 
   var $ed292_ordem = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed292_sequencial = int4 = Código 
                 ed292_atolegal = int4 = Ato Legal 
                 ed292_nomearquivo = varchar(60) = Nome do Arquivo 
                 ed292_arquivo = oid = Arquivo 
                 ed292_obs = text = Observações 
                 ed292_ordem = int4 = Ordem 
                 ";
   //funcao construtor da classe 
   function cl_edu_anexoatolegal() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("edu_anexoatolegal"); 
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
       $this->ed292_sequencial = ($this->ed292_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed292_sequencial"]:$this->ed292_sequencial);
       $this->ed292_atolegal = ($this->ed292_atolegal == ""?@$GLOBALS["HTTP_POST_VARS"]["ed292_atolegal"]:$this->ed292_atolegal);
       $this->ed292_nomearquivo = ($this->ed292_nomearquivo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed292_nomearquivo"]:$this->ed292_nomearquivo);
       $this->ed292_arquivo = ($this->ed292_arquivo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed292_arquivo"]:$this->ed292_arquivo);
       $this->ed292_obs = ($this->ed292_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["ed292_obs"]:$this->ed292_obs);
       $this->ed292_ordem = ($this->ed292_ordem == ""?@$GLOBALS["HTTP_POST_VARS"]["ed292_ordem"]:$this->ed292_ordem);
     }else{
       $this->ed292_sequencial = ($this->ed292_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed292_sequencial"]:$this->ed292_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ed292_sequencial){ 
      $this->atualizacampos();
     if($this->ed292_atolegal == null ){ 
       $this->erro_sql = " Campo Ato Legal nao Informado.";
       $this->erro_campo = "ed292_atolegal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed292_nomearquivo == null ){ 
       $this->erro_sql = " Campo Nome do Arquivo nao Informado.";
       $this->erro_campo = "ed292_nomearquivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed292_arquivo == null ){ 
       $this->erro_sql = " Campo Arquivo nao Informado.";
       $this->erro_campo = "ed292_arquivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed292_ordem == null ){ 
       $this->erro_sql = " Campo Ordem nao Informado.";
       $this->erro_campo = "ed292_ordem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed292_sequencial == "" || $ed292_sequencial == null ){
       $result = db_query("select nextval('edu_anexoatolegal_ed292_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: edu_anexoatolegal_ed292_sequencial_seq do campo: ed292_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed292_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from edu_anexoatolegal_ed292_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed292_sequencial)){
         $this->erro_sql = " Campo ed292_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed292_sequencial = $ed292_sequencial; 
       }
     }
     if(($this->ed292_sequencial == null) || ($this->ed292_sequencial == "") ){ 
       $this->erro_sql = " Campo ed292_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into edu_anexoatolegal(
                                       ed292_sequencial 
                                      ,ed292_atolegal 
                                      ,ed292_nomearquivo 
                                      ,ed292_arquivo 
                                      ,ed292_obs 
                                      ,ed292_ordem 
                       )
                values (
                                $this->ed292_sequencial 
                               ,$this->ed292_atolegal 
                               ,'$this->ed292_nomearquivo' 
                               ,$this->ed292_arquivo 
                               ,'$this->ed292_obs' 
                               ,$this->ed292_ordem 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "edu_anexoatolegal ($this->ed292_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "edu_anexoatolegal já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "edu_anexoatolegal ($this->ed292_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed292_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed292_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18379,'$this->ed292_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3253,18379,'','".AddSlashes(pg_result($resaco,0,'ed292_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3253,18378,'','".AddSlashes(pg_result($resaco,0,'ed292_atolegal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3253,18376,'','".AddSlashes(pg_result($resaco,0,'ed292_nomearquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3253,18374,'','".AddSlashes(pg_result($resaco,0,'ed292_arquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3253,18375,'','".AddSlashes(pg_result($resaco,0,'ed292_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3253,18377,'','".AddSlashes(pg_result($resaco,0,'ed292_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed292_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update edu_anexoatolegal set ";
     $virgula = "";
     if(trim($this->ed292_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed292_sequencial"])){ 
       $sql  .= $virgula." ed292_sequencial = $this->ed292_sequencial ";
       $virgula = ",";
       if(trim($this->ed292_sequencial) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed292_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed292_atolegal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed292_atolegal"])){ 
       $sql  .= $virgula." ed292_atolegal = $this->ed292_atolegal ";
       $virgula = ",";
       if(trim($this->ed292_atolegal) == null ){ 
         $this->erro_sql = " Campo Ato Legal nao Informado.";
         $this->erro_campo = "ed292_atolegal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed292_nomearquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed292_nomearquivo"])){ 
       $sql  .= $virgula." ed292_nomearquivo = '$this->ed292_nomearquivo' ";
       $virgula = ",";
       if(trim($this->ed292_nomearquivo) == null ){ 
         $this->erro_sql = " Campo Nome do Arquivo nao Informado.";
         $this->erro_campo = "ed292_nomearquivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed292_arquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed292_arquivo"])){ 
       $sql  .= $virgula." ed292_arquivo = $this->ed292_arquivo ";
       $virgula = ",";
       if(trim($this->ed292_arquivo) == null ){ 
         $this->erro_sql = " Campo Arquivo nao Informado.";
         $this->erro_campo = "ed292_arquivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed292_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed292_obs"])){ 
       $sql  .= $virgula." ed292_obs = '$this->ed292_obs' ";
       $virgula = ",";
     }
     if(trim($this->ed292_ordem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed292_ordem"])){ 
       $sql  .= $virgula." ed292_ordem = $this->ed292_ordem ";
       $virgula = ",";
       if(trim($this->ed292_ordem) == null ){ 
         $this->erro_sql = " Campo Ordem nao Informado.";
         $this->erro_campo = "ed292_ordem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed292_sequencial!=null){
       $sql .= " ed292_sequencial = $this->ed292_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed292_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18379,'$this->ed292_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed292_sequencial"]) || $this->ed292_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3253,18379,'".AddSlashes(pg_result($resaco,$conresaco,'ed292_sequencial'))."','$this->ed292_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed292_atolegal"]) || $this->ed292_atolegal != "")
           $resac = db_query("insert into db_acount values($acount,3253,18378,'".AddSlashes(pg_result($resaco,$conresaco,'ed292_atolegal'))."','$this->ed292_atolegal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed292_nomearquivo"]) || $this->ed292_nomearquivo != "")
           $resac = db_query("insert into db_acount values($acount,3253,18376,'".AddSlashes(pg_result($resaco,$conresaco,'ed292_nomearquivo'))."','$this->ed292_nomearquivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed292_arquivo"]) || $this->ed292_arquivo != "")
           $resac = db_query("insert into db_acount values($acount,3253,18374,'".AddSlashes(pg_result($resaco,$conresaco,'ed292_arquivo'))."','$this->ed292_arquivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed292_obs"]) || $this->ed292_obs != "")
           $resac = db_query("insert into db_acount values($acount,3253,18375,'".AddSlashes(pg_result($resaco,$conresaco,'ed292_obs'))."','$this->ed292_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed292_ordem"]) || $this->ed292_ordem != "")
           $resac = db_query("insert into db_acount values($acount,3253,18377,'".AddSlashes(pg_result($resaco,$conresaco,'ed292_ordem'))."','$this->ed292_ordem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "edu_anexoatolegal nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed292_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "edu_anexoatolegal nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed292_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed292_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed292_sequencial=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed292_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18379,'$ed292_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3253,18379,'','".AddSlashes(pg_result($resaco,$iresaco,'ed292_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3253,18378,'','".AddSlashes(pg_result($resaco,$iresaco,'ed292_atolegal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3253,18376,'','".AddSlashes(pg_result($resaco,$iresaco,'ed292_nomearquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3253,18374,'','".AddSlashes(pg_result($resaco,$iresaco,'ed292_arquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3253,18375,'','".AddSlashes(pg_result($resaco,$iresaco,'ed292_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3253,18377,'','".AddSlashes(pg_result($resaco,$iresaco,'ed292_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from edu_anexoatolegal
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed292_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed292_sequencial = $ed292_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "edu_anexoatolegal nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed292_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "edu_anexoatolegal nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed292_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed292_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:edu_anexoatolegal";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed292_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from edu_anexoatolegal ";
     $sql .= "      inner join atolegal  on  atolegal.ed05_i_codigo = edu_anexoatolegal.ed292_atolegal";
     $sql .= "      inner join tipoato  on  tipoato.ed83_i_codigo = atolegal.ed05_i_tipoato";
     $sql2 = "";
     if($dbwhere==""){
       if($ed292_sequencial!=null ){
         $sql2 .= " where edu_anexoatolegal.ed292_sequencial = $ed292_sequencial "; 
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
   function sql_query_file ( $ed292_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from edu_anexoatolegal ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed292_sequencial!=null ){
         $sql2 .= " where edu_anexoatolegal.ed292_sequencial = $ed292_sequencial "; 
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