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

//MODULO: custos
//CLASSE DA ENTIDADE custoplano
class cl_custoplano { 
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
   var $cc01_sequencial = 0; 
   var $cc01_instit = 0; 
   var $cc01_estrutural = null; 
   var $cc01_descricao = null; 
   var $cc01_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 cc01_sequencial = int4 = Sequencial 
                 cc01_instit = int4 = Institui��o 
                 cc01_estrutural = varchar(20) = Estrutural 
                 cc01_descricao = varchar(50) = Descri��o 
                 cc01_obs = text = Observa��o 
                 ";
   //funcao construtor da classe 
   function cl_custoplano() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("custoplano"); 
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
       $this->cc01_sequencial = ($this->cc01_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["cc01_sequencial"]:$this->cc01_sequencial);
       $this->cc01_instit = ($this->cc01_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["cc01_instit"]:$this->cc01_instit);
       $this->cc01_estrutural = ($this->cc01_estrutural == ""?@$GLOBALS["HTTP_POST_VARS"]["cc01_estrutural"]:$this->cc01_estrutural);
       $this->cc01_descricao = ($this->cc01_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["cc01_descricao"]:$this->cc01_descricao);
       $this->cc01_obs = ($this->cc01_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["cc01_obs"]:$this->cc01_obs);
     }else{
       $this->cc01_sequencial = ($this->cc01_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["cc01_sequencial"]:$this->cc01_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($cc01_sequencial){ 
      $this->atualizacampos();
     if($this->cc01_instit == null ){ 
       $this->erro_sql = " Campo Institui��o nao Informado.";
       $this->erro_campo = "cc01_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cc01_estrutural == null ){ 
       $this->erro_sql = " Campo Estrutural nao Informado.";
       $this->erro_campo = "cc01_estrutural";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cc01_descricao == null ){ 
       $this->erro_sql = " Campo Descri��o nao Informado.";
       $this->erro_campo = "cc01_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }

     if($cc01_sequencial == "" || $cc01_sequencial == null ){
       $result = db_query("select nextval('custoplano_cc01_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: custoplano_cc01_sequencial_seq do campo: cc01_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->cc01_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from custoplano_cc01_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $cc01_sequencial)){
         $this->erro_sql = " Campo cc01_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->cc01_sequencial = $cc01_sequencial; 
       }
     }
     if(($this->cc01_sequencial == null) || ($this->cc01_sequencial == "") ){ 
       $this->erro_sql = " Campo cc01_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into custoplano(
                                       cc01_sequencial 
                                      ,cc01_instit 
                                      ,cc01_estrutural 
                                      ,cc01_descricao 
                                      ,cc01_obs 
                       )
                values (
                                $this->cc01_sequencial 
                               ,$this->cc01_instit 
                               ,'$this->cc01_estrutural' 
                               ,'$this->cc01_descricao' 
                               ,'$this->cc01_obs' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Plano do custo ($this->cc01_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Plano do custo j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Plano do custo ($this->cc01_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
     $this->erro_sql .= "Valores : ".$this->cc01_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->cc01_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12567,'$this->cc01_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2193,12567,'','".AddSlashes(pg_result($resaco,0,'cc01_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2193,12568,'','".AddSlashes(pg_result($resaco,0,'cc01_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2193,12569,'','".AddSlashes(pg_result($resaco,0,'cc01_estrutural'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2193,12570,'','".AddSlashes(pg_result($resaco,0,'cc01_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2193,12571,'','".AddSlashes(pg_result($resaco,0,'cc01_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($cc01_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update custoplano set ";
     $virgula = "";
     if(trim($this->cc01_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc01_sequencial"])){ 
       $sql  .= $virgula." cc01_sequencial = $this->cc01_sequencial ";
       $virgula = ",";
       if(trim($this->cc01_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "cc01_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc01_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc01_instit"])){ 
       $sql  .= $virgula." cc01_instit = $this->cc01_instit ";
       $virgula = ",";
       if(trim($this->cc01_instit) == null ){ 
         $this->erro_sql = " Campo Institui��o nao Informado.";
         $this->erro_campo = "cc01_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc01_estrutural)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc01_estrutural"])){ 
       $sql  .= $virgula." cc01_estrutural = '$this->cc01_estrutural' ";
       $virgula = ",";
       if(trim($this->cc01_estrutural) == null ){ 
         $this->erro_sql = " Campo Estrutural nao Informado.";
         $this->erro_campo = "cc01_estrutural";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc01_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc01_descricao"])){ 
       $sql  .= $virgula." cc01_descricao = '$this->cc01_descricao' ";
       $virgula = ",";
       if(trim($this->cc01_descricao) == null ){ 
         $this->erro_sql = " Campo Descri��o nao Informado.";
         $this->erro_campo = "cc01_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
	 if(trim($this->cc01_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc01_obs"])){ 
       $sql  .= $virgula." cc01_obs = '$this->cc01_obs' ";
       $virgula = ",";
     }

     $sql .= " where ";
     if($cc01_sequencial!=null){
       $sql .= " cc01_sequencial = $this->cc01_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->cc01_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12567,'$this->cc01_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc01_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2193,12567,'".AddSlashes(pg_result($resaco,$conresaco,'cc01_sequencial'))."','$this->cc01_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc01_instit"]))
           $resac = db_query("insert into db_acount values($acount,2193,12568,'".AddSlashes(pg_result($resaco,$conresaco,'cc01_instit'))."','$this->cc01_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc01_estrutural"]))
           $resac = db_query("insert into db_acount values($acount,2193,12569,'".AddSlashes(pg_result($resaco,$conresaco,'cc01_estrutural'))."','$this->cc01_estrutural',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc01_descricao"]))
           $resac = db_query("insert into db_acount values($acount,2193,12570,'".AddSlashes(pg_result($resaco,$conresaco,'cc01_descricao'))."','$this->cc01_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc01_obs"]))
           $resac = db_query("insert into db_acount values($acount,2193,12571,'".AddSlashes(pg_result($resaco,$conresaco,'cc01_obs'))."','$this->cc01_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }

     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Plano do custo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->cc01_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Plano do custo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->cc01_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cc01_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($cc01_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($cc01_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12567,'$cc01_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2193,12567,'','".AddSlashes(pg_result($resaco,$iresaco,'cc01_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2193,12568,'','".AddSlashes(pg_result($resaco,$iresaco,'cc01_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2193,12569,'','".AddSlashes(pg_result($resaco,$iresaco,'cc01_estrutural'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2193,12570,'','".AddSlashes(pg_result($resaco,$iresaco,'cc01_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2193,12571,'','".AddSlashes(pg_result($resaco,$iresaco,'cc01_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from custoplano
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($cc01_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cc01_sequencial = $cc01_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Plano do custo nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$cc01_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Plano do custo nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$cc01_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$cc01_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:custoplano";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $cc01_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from custoplano ";
     $sql .= "      inner join db_config  on  db_config.codigo = custoplano.cc01_instit";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($cc01_sequencial!=null ){
         $sql2 .= " where custoplano.cc01_sequencial = $cc01_sequencial "; 
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
   function sql_query_file ( $cc01_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from custoplano ";
     $sql2 = "";
     if($dbwhere==""){
       if($cc01_sequencial!=null ){
         $sql2 .= " where custoplano.cc01_sequencial = $cc01_sequencial "; 
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
  
  function sql_query_analitica ( $cc01_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from custoplano ";
     $sql .= "   inner join db_config  			     on  db_config.codigo = custoplano.cc01_instit                                          ";
     $sql .= "   inner join cgm  				         on  cgm.z01_numcgm = db_config.numcgm                                                  ";
     $sql .= "   left  join custoplanoanalitica  on  custoplanoanalitica.cc04_custoplano = cc01_sequencial                              ";
     $sql .= "   left  join custoplanotipoconta  on  custoplanotipoconta.cc03_custoplanoanalitica = custoplanoanalitica.cc04_sequencial ";
     $sql .= "   left  join db_depart            on  db_depart.coddepto = custoplanoanalitica.cc04_coddepto                             ";
     $sql2 = " ";
     if($dbwhere==""){
       if($cc01_sequencial!=null ){
         $sql2 .= " where custoplano.cc01_sequencial = $cc01_sequencial "; 
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