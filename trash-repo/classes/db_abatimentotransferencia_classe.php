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

//MODULO: arrecadacao
//CLASSE DA ENTIDADE abatimentotransferencia
class cl_abatimentotransferencia { 
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
   var $k158_sequencial = 0; 
   var $k158_abatimentoutilizacao = 0; 
   var $k158_abatimentoorigem = 0; 
   var $k158_abatimentodestino = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k158_sequencial = int4 = Sequencial 
                 k158_abatimentoutilizacao = int4 = Código abatimento utilização 
                 k158_abatimentoorigem = int4 = Sequencial da tabela abatimento 
                 k158_abatimentodestino = int4 = Sequencial da tabela abatimento 
                 ";
   //funcao construtor da classe 
   function cl_abatimentotransferencia() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("abatimentotransferencia"); 
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
       $this->k158_sequencial = ($this->k158_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k158_sequencial"]:$this->k158_sequencial);
       $this->k158_abatimentoutilizacao = ($this->k158_abatimentoutilizacao == ""?@$GLOBALS["HTTP_POST_VARS"]["k158_abatimentoutilizacao"]:$this->k158_abatimentoutilizacao);
       $this->k158_abatimentoorigem = ($this->k158_abatimentoorigem == ""?@$GLOBALS["HTTP_POST_VARS"]["k158_abatimentoorigem"]:$this->k158_abatimentoorigem);
       $this->k158_abatimentodestino = ($this->k158_abatimentodestino == ""?@$GLOBALS["HTTP_POST_VARS"]["k158_abatimentodestino"]:$this->k158_abatimentodestino);
     }else{
       $this->k158_sequencial = ($this->k158_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k158_sequencial"]:$this->k158_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k158_sequencial){ 
      $this->atualizacampos();
     if($this->k158_abatimentoutilizacao == null ){ 
       $this->erro_sql = " Campo Código abatimento utilização nao Informado.";
       $this->erro_campo = "k158_abatimentoutilizacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k158_abatimentoorigem == null ){ 
       $this->erro_sql = " Campo Sequencial da tabela abatimento nao Informado.";
       $this->erro_campo = "k158_abatimentoorigem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k158_abatimentodestino == null ){ 
       $this->erro_sql = " Campo Sequencial da tabela abatimento nao Informado.";
       $this->erro_campo = "k158_abatimentodestino";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k158_sequencial == "" || $k158_sequencial == null ){
       $result = db_query("select nextval('abatimentotransferencia_k158_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: abatimentotransferencia_k158_sequencial_seq do campo: k158_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k158_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from abatimentotransferencia_k158_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k158_sequencial)){
         $this->erro_sql = " Campo k158_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k158_sequencial = $k158_sequencial; 
       }
     }
     if(($this->k158_sequencial == null) || ($this->k158_sequencial == "") ){ 
       $this->erro_sql = " Campo k158_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into abatimentotransferencia(
                                       k158_sequencial 
                                      ,k158_abatimentoutilizacao 
                                      ,k158_abatimentoorigem 
                                      ,k158_abatimentodestino 
                       )
                values (
                                $this->k158_sequencial 
                               ,$this->k158_abatimentoutilizacao 
                               ,$this->k158_abatimentoorigem 
                               ,$this->k158_abatimentodestino 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Abatimento Transferência ($this->k158_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Abatimento Transferência já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Abatimento Transferência ($this->k158_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k158_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k158_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,19617,'$this->k158_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3485,19617,'','".AddSlashes(pg_result($resaco,0,'k158_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3485,19612,'','".AddSlashes(pg_result($resaco,0,'k158_abatimentoutilizacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3485,19613,'','".AddSlashes(pg_result($resaco,0,'k158_abatimentoorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3485,19614,'','".AddSlashes(pg_result($resaco,0,'k158_abatimentodestino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k158_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update abatimentotransferencia set ";
     $virgula = "";
     if(trim($this->k158_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k158_sequencial"])){ 
       $sql  .= $virgula." k158_sequencial = $this->k158_sequencial ";
       $virgula = ",";
       if(trim($this->k158_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "k158_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k158_abatimentoutilizacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k158_abatimentoutilizacao"])){ 
       $sql  .= $virgula." k158_abatimentoutilizacao = $this->k158_abatimentoutilizacao ";
       $virgula = ",";
       if(trim($this->k158_abatimentoutilizacao) == null ){ 
         $this->erro_sql = " Campo Código abatimento utilização nao Informado.";
         $this->erro_campo = "k158_abatimentoutilizacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k158_abatimentoorigem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k158_abatimentoorigem"])){ 
       $sql  .= $virgula." k158_abatimentoorigem = $this->k158_abatimentoorigem ";
       $virgula = ",";
       if(trim($this->k158_abatimentoorigem) == null ){ 
         $this->erro_sql = " Campo Sequencial da tabela abatimento nao Informado.";
         $this->erro_campo = "k158_abatimentoorigem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k158_abatimentodestino)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k158_abatimentodestino"])){ 
       $sql  .= $virgula." k158_abatimentodestino = $this->k158_abatimentodestino ";
       $virgula = ",";
       if(trim($this->k158_abatimentodestino) == null ){ 
         $this->erro_sql = " Campo Sequencial da tabela abatimento nao Informado.";
         $this->erro_campo = "k158_abatimentodestino";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k158_sequencial!=null){
       $sql .= " k158_sequencial = $this->k158_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k158_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19617,'$this->k158_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k158_sequencial"]) || $this->k158_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3485,19617,'".AddSlashes(pg_result($resaco,$conresaco,'k158_sequencial'))."','$this->k158_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k158_abatimentoutilizacao"]) || $this->k158_abatimentoutilizacao != "")
           $resac = db_query("insert into db_acount values($acount,3485,19612,'".AddSlashes(pg_result($resaco,$conresaco,'k158_abatimentoutilizacao'))."','$this->k158_abatimentoutilizacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k158_abatimentoorigem"]) || $this->k158_abatimentoorigem != "")
           $resac = db_query("insert into db_acount values($acount,3485,19613,'".AddSlashes(pg_result($resaco,$conresaco,'k158_abatimentoorigem'))."','$this->k158_abatimentoorigem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k158_abatimentodestino"]) || $this->k158_abatimentodestino != "")
           $resac = db_query("insert into db_acount values($acount,3485,19614,'".AddSlashes(pg_result($resaco,$conresaco,'k158_abatimentodestino'))."','$this->k158_abatimentodestino',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Abatimento Transferência nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k158_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Abatimento Transferência nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k158_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k158_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k158_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k158_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19617,'$k158_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3485,19617,'','".AddSlashes(pg_result($resaco,$iresaco,'k158_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3485,19612,'','".AddSlashes(pg_result($resaco,$iresaco,'k158_abatimentoutilizacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3485,19613,'','".AddSlashes(pg_result($resaco,$iresaco,'k158_abatimentoorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3485,19614,'','".AddSlashes(pg_result($resaco,$iresaco,'k158_abatimentodestino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from abatimentotransferencia
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k158_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k158_sequencial = $k158_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Abatimento Transferência nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k158_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Abatimento Transferência nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k158_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k158_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:abatimentotransferencia";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k158_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from abatimentotransferencia ";
     $sql .= "      inner join abatimento  on  abatimento.k125_sequencial = abatimentotransferencia.k158_abatimentoorigem and  abatimento.k125_sequencial = abatimentotransferencia.k158_abatimentodestino";
     $sql .= "      inner join abatimentoutilizacao  on  abatimentoutilizacao.k157_sequencial = abatimentotransferencia.k158_abatimentoutilizacao";
     $sql .= "      inner join db_config  on  db_config.codigo = abatimento.k125_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = abatimento.k125_usuario";
     $sql .= "      inner join tipoabatimento  on  tipoabatimento.k126_sequencial = abatimento.k125_tipoabatimento";
     $sql .= "      inner join db_usuarios  as a on   a.id_usuario = abatimentoutilizacao.k157_usuario";
     $sql .= "      inner join abatimento  on  abatimento.k125_sequencial = abatimentoutilizacao.k157_abatimento";
     $sql2 = "";
     if($dbwhere==""){
       if($k158_sequencial!=null ){
         $sql2 .= " where abatimentotransferencia.k158_sequencial = $k158_sequencial "; 
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
   function sql_query_file ( $k158_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from abatimentotransferencia ";
     $sql2 = "";
     if($dbwhere==""){
       if($k158_sequencial!=null ){
         $sql2 .= " where abatimentotransferencia.k158_sequencial = $k158_sequencial "; 
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