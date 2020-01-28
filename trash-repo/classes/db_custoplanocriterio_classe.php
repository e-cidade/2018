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
//CLASSE DA ENTIDADE custoplanocriterio
class cl_custoplanocriterio { 
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
   var $cc07_sequencial = 0; 
   var $cc07_custocriteriorateio = 0; 
   var $cc07_custoplanoanalitica = 0; 
   var $cc07_quantidade = 0; 
   var $cc07_percentual = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 cc07_sequencial = int4 = Sequêncial 
                 cc07_custocriteriorateio = int4 = Custo critério rateio 
                 cc07_custoplanoanalitica = int4 = Custo plano analítico 
                 cc07_quantidade = float8 = Quantidade 
                 cc07_percentual = float8 = Percentual 
                 ";
   //funcao construtor da classe 
   function cl_custoplanocriterio() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("custoplanocriterio"); 
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
       $this->cc07_sequencial = ($this->cc07_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["cc07_sequencial"]:$this->cc07_sequencial);
       $this->cc07_custocriteriorateio = ($this->cc07_custocriteriorateio == ""?@$GLOBALS["HTTP_POST_VARS"]["cc07_custocriteriorateio"]:$this->cc07_custocriteriorateio);
       $this->cc07_custoplanoanalitica = ($this->cc07_custoplanoanalitica == ""?@$GLOBALS["HTTP_POST_VARS"]["cc07_custoplanoanalitica"]:$this->cc07_custoplanoanalitica);
       $this->cc07_quantidade = ($this->cc07_quantidade == ""?@$GLOBALS["HTTP_POST_VARS"]["cc07_quantidade"]:$this->cc07_quantidade);
       $this->cc07_percentual = ($this->cc07_percentual == ""?@$GLOBALS["HTTP_POST_VARS"]["cc07_percentual"]:$this->cc07_percentual);
     }else{
       $this->cc07_sequencial = ($this->cc07_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["cc07_sequencial"]:$this->cc07_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($cc07_sequencial){ 
      $this->atualizacampos();
     if($this->cc07_custocriteriorateio == null ){ 
       $this->erro_sql = " Campo Custo critério rateio nao Informado.";
       $this->erro_campo = "cc07_custocriteriorateio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cc07_custoplanoanalitica == null ){ 
       $this->erro_sql = " Campo Custo plano analítico nao Informado.";
       $this->erro_campo = "cc07_custoplanoanalitica";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cc07_quantidade == null ){ 
       $this->erro_sql = " Campo Quantidade nao Informado.";
       $this->erro_campo = "cc07_quantidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cc07_percentual == null ){ 
       $this->erro_sql = " Campo Percentual nao Informado.";
       $this->erro_campo = "cc07_percentual";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($cc07_sequencial == "" || $cc07_sequencial == null ){
       $result = db_query("select nextval('custocriterio_cc07_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: custocriterio_cc07_sequencial_seq do campo: cc07_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->cc07_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from custocriterio_cc07_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $cc07_sequencial)){
         $this->erro_sql = " Campo cc07_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->cc07_sequencial = $cc07_sequencial; 
       }
     }
     if(($this->cc07_sequencial == null) || ($this->cc07_sequencial == "") ){ 
       $this->erro_sql = " Campo cc07_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into custoplanocriterio(
                                       cc07_sequencial 
                                      ,cc07_custocriteriorateio 
                                      ,cc07_custoplanoanalitica 
                                      ,cc07_quantidade 
                                      ,cc07_percentual 
                       )
                values (
                                $this->cc07_sequencial 
                               ,$this->cc07_custocriteriorateio 
                               ,$this->cc07_custoplanoanalitica 
                               ,$this->cc07_quantidade 
                               ,$this->cc07_percentual 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Custo dos critérios de planos ($this->cc07_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Custo dos critérios de planos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Custo dos critérios de planos ($this->cc07_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cc07_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->cc07_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12578,'$this->cc07_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2196,12578,'','".AddSlashes(pg_result($resaco,0,'cc07_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2196,12579,'','".AddSlashes(pg_result($resaco,0,'cc07_custocriteriorateio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2196,12580,'','".AddSlashes(pg_result($resaco,0,'cc07_custoplanoanalitica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2196,12581,'','".AddSlashes(pg_result($resaco,0,'cc07_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2196,12582,'','".AddSlashes(pg_result($resaco,0,'cc07_percentual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($cc07_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update custoplanocriterio set ";
     $virgula = "";
     if(trim($this->cc07_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc07_sequencial"])){ 
       $sql  .= $virgula." cc07_sequencial = $this->cc07_sequencial ";
       $virgula = ",";
       if(trim($this->cc07_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequêncial nao Informado.";
         $this->erro_campo = "cc07_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc07_custocriteriorateio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc07_custocriteriorateio"])){ 
       $sql  .= $virgula." cc07_custocriteriorateio = $this->cc07_custocriteriorateio ";
       $virgula = ",";
       if(trim($this->cc07_custocriteriorateio) == null ){ 
         $this->erro_sql = " Campo Custo critério rateio nao Informado.";
         $this->erro_campo = "cc07_custocriteriorateio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc07_custoplanoanalitica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc07_custoplanoanalitica"])){ 
       $sql  .= $virgula." cc07_custoplanoanalitica = $this->cc07_custoplanoanalitica ";
       $virgula = ",";
       if(trim($this->cc07_custoplanoanalitica) == null ){ 
         $this->erro_sql = " Campo Custo plano analítico nao Informado.";
         $this->erro_campo = "cc07_custoplanoanalitica";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc07_quantidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc07_quantidade"])){ 
       $sql  .= $virgula." cc07_quantidade = $this->cc07_quantidade ";
       $virgula = ",";
       if(trim($this->cc07_quantidade) == null ){ 
         $this->erro_sql = " Campo Quantidade nao Informado.";
         $this->erro_campo = "cc07_quantidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc07_percentual)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc07_percentual"])){ 
       $sql  .= $virgula." cc07_percentual = $this->cc07_percentual ";
       $virgula = ",";
       if(trim($this->cc07_percentual) == null ){ 
         $this->erro_sql = " Campo Percentual nao Informado.";
         $this->erro_campo = "cc07_percentual";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($cc07_sequencial!=null){
       $sql .= " cc07_sequencial = $this->cc07_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->cc07_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12578,'$this->cc07_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc07_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2196,12578,'".AddSlashes(pg_result($resaco,$conresaco,'cc07_sequencial'))."','$this->cc07_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc07_custocriteriorateio"]))
           $resac = db_query("insert into db_acount values($acount,2196,12579,'".AddSlashes(pg_result($resaco,$conresaco,'cc07_custocriteriorateio'))."','$this->cc07_custocriteriorateio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc07_custoplanoanalitica"]))
           $resac = db_query("insert into db_acount values($acount,2196,12580,'".AddSlashes(pg_result($resaco,$conresaco,'cc07_custoplanoanalitica'))."','$this->cc07_custoplanoanalitica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc07_quantidade"]))
           $resac = db_query("insert into db_acount values($acount,2196,12581,'".AddSlashes(pg_result($resaco,$conresaco,'cc07_quantidade'))."','$this->cc07_quantidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc07_percentual"]))
           $resac = db_query("insert into db_acount values($acount,2196,12582,'".AddSlashes(pg_result($resaco,$conresaco,'cc07_percentual'))."','$this->cc07_percentual',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Custo dos critérios de planos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->cc07_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Custo dos critérios de planos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->cc07_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cc07_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($cc07_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($cc07_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12578,'$cc07_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2196,12578,'','".AddSlashes(pg_result($resaco,$iresaco,'cc07_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2196,12579,'','".AddSlashes(pg_result($resaco,$iresaco,'cc07_custocriteriorateio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2196,12580,'','".AddSlashes(pg_result($resaco,$iresaco,'cc07_custoplanoanalitica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2196,12581,'','".AddSlashes(pg_result($resaco,$iresaco,'cc07_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2196,12582,'','".AddSlashes(pg_result($resaco,$iresaco,'cc07_percentual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from custoplanocriterio
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($cc07_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cc07_sequencial = $cc07_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Custo dos critérios de planos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$cc07_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Custo dos critérios de planos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$cc07_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$cc07_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:custoplanocriterio";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $cc07_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from custoplanocriterio ";
     $sql .= "      inner join custoplanoanalitica  on  custoplanoanalitica.cc04_sequencial = custoplanocriterio.cc07_custoplanoanalitica";
     $sql .= "      inner join custocriteriorateio  on  custocriteriorateio.cc08_sequencial = custoplanocriterio.cc07_custocriteriorateio";
     $sql .= "      inner join custoplano  on  custoplano.cc01_sequencial = custoplanoanalitica.cc04_custoplano";
     $sql .= "      inner join db_config  on  db_config.codigo = custocriteriorateio.cc08_instit";
     $sql .= "      inner join matunid  on  matunid.m61_codmatunid = custocriteriorateio.cc08_matunid";
     $sql2 = "";
     if($dbwhere==""){
       if($cc07_sequencial!=null ){
         $sql2 .= " where custoplanocriterio.cc07_sequencial = $cc07_sequencial "; 
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
   function sql_query_file ( $cc07_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from custoplanocriterio ";
     $sql2 = "";
     if($dbwhere==""){
       if($cc07_sequencial!=null ){
         $sql2 .= " where custoplanocriterio.cc07_sequencial = $cc07_sequencial "; 
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