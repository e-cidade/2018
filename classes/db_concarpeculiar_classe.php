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

//MODULO: contabilidade
//CLASSE DA ENTIDADE concarpeculiar
class cl_concarpeculiar { 
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
   var $c58_sequencial = null; 
   var $c58_descr = null; 
   var $c58_tipo = 0; 
   var $c58_db_estruturavalor = 0; 
   var $c58_estrutural = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 c58_sequencial = varchar(100) = Sequencial 
                 c58_descr = varchar(50) = Descrição 
                 c58_tipo = int4 = Tipo de Conta 
                 c58_db_estruturavalor = int4 = Código da Estrutura 
                 c58_estrutural = varchar(100) = Código 
                 ";
   //funcao construtor da classe 
   function cl_concarpeculiar() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("concarpeculiar"); 
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
       $this->c58_sequencial = ($this->c58_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c58_sequencial"]:$this->c58_sequencial);
       $this->c58_descr = ($this->c58_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["c58_descr"]:$this->c58_descr);
       $this->c58_tipo = ($this->c58_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["c58_tipo"]:$this->c58_tipo);
       $this->c58_db_estruturavalor = ($this->c58_db_estruturavalor == ""?@$GLOBALS["HTTP_POST_VARS"]["c58_db_estruturavalor"]:$this->c58_db_estruturavalor);
       $this->c58_estrutural = ($this->c58_estrutural == ""?@$GLOBALS["HTTP_POST_VARS"]["c58_estrutural"]:$this->c58_estrutural);
     }else{
       $this->c58_sequencial = ($this->c58_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c58_sequencial"]:$this->c58_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($c58_sequencial){ 
      $this->atualizacampos();
     if($this->c58_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "c58_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c58_tipo == null ){ 
       $this->erro_sql = " Campo Tipo de Conta nao Informado.";
       $this->erro_campo = "c58_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c58_db_estruturavalor == null ){ 
       $this->erro_sql = " Campo Código da Estrutura nao Informado.";
       $this->erro_campo = "c58_db_estruturavalor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c58_estrutural == null ){ 
       $this->erro_sql = " Campo Código nao Informado.";
       $this->erro_campo = "c58_estrutural";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->c58_sequencial = $c58_sequencial; 
     if(($this->c58_sequencial == null) || ($this->c58_sequencial == "") ){ 
       $this->erro_sql = " Campo c58_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into concarpeculiar(
                                       c58_sequencial 
                                      ,c58_descr 
                                      ,c58_tipo 
                                      ,c58_db_estruturavalor 
                                      ,c58_estrutural 
                       )
                values (
                                '$this->c58_sequencial' 
                               ,'$this->c58_descr' 
                               ,$this->c58_tipo 
                               ,$this->c58_db_estruturavalor 
                               ,'$this->c58_estrutural' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Caracteristicas Pecualiares ($this->c58_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Caracteristicas Pecualiares já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Caracteristicas Pecualiares ($this->c58_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c58_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->c58_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10813,'$this->c58_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1862,10813,'','".AddSlashes(pg_result($resaco,0,'c58_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1862,10814,'','".AddSlashes(pg_result($resaco,0,'c58_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1862,10815,'','".AddSlashes(pg_result($resaco,0,'c58_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1862,18122,'','".AddSlashes(pg_result($resaco,0,'c58_db_estruturavalor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1862,18123,'','".AddSlashes(pg_result($resaco,0,'c58_estrutural'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($c58_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update concarpeculiar set ";
     $virgula = "";
     if(trim($this->c58_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c58_sequencial"])){ 
       $sql  .= $virgula." c58_sequencial = '$this->c58_sequencial' ";
       $virgula = ",";
       if(trim($this->c58_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "c58_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c58_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c58_descr"])){ 
       $sql  .= $virgula." c58_descr = '$this->c58_descr' ";
       $virgula = ",";
       if(trim($this->c58_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "c58_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c58_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c58_tipo"])){ 
       $sql  .= $virgula." c58_tipo = $this->c58_tipo ";
       $virgula = ",";
       if(trim($this->c58_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo de Conta nao Informado.";
         $this->erro_campo = "c58_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c58_db_estruturavalor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c58_db_estruturavalor"])){ 
       $sql  .= $virgula." c58_db_estruturavalor = $this->c58_db_estruturavalor ";
       $virgula = ",";
       if(trim($this->c58_db_estruturavalor) == null ){ 
         $this->erro_sql = " Campo Código da Estrutura nao Informado.";
         $this->erro_campo = "c58_db_estruturavalor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c58_estrutural)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c58_estrutural"])){ 
       $sql  .= $virgula." c58_estrutural = '$this->c58_estrutural' ";
       $virgula = ",";
       if(trim($this->c58_estrutural) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "c58_estrutural";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($c58_sequencial!=null){
       $sql .= " c58_sequencial = '$this->c58_sequencial'";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->c58_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10813,'$this->c58_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c58_sequencial"]) || $this->c58_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,1862,10813,'".AddSlashes(pg_result($resaco,$conresaco,'c58_sequencial'))."','$this->c58_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c58_descr"]) || $this->c58_descr != "")
           $resac = db_query("insert into db_acount values($acount,1862,10814,'".AddSlashes(pg_result($resaco,$conresaco,'c58_descr'))."','$this->c58_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c58_tipo"]) || $this->c58_tipo != "")
           $resac = db_query("insert into db_acount values($acount,1862,10815,'".AddSlashes(pg_result($resaco,$conresaco,'c58_tipo'))."','$this->c58_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c58_db_estruturavalor"]) || $this->c58_db_estruturavalor != "")
           $resac = db_query("insert into db_acount values($acount,1862,18122,'".AddSlashes(pg_result($resaco,$conresaco,'c58_db_estruturavalor'))."','$this->c58_db_estruturavalor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c58_estrutural"]) || $this->c58_estrutural != "")
           $resac = db_query("insert into db_acount values($acount,1862,18123,'".AddSlashes(pg_result($resaco,$conresaco,'c58_estrutural'))."','$this->c58_estrutural',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Caracteristicas Pecualiares nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c58_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Caracteristicas Pecualiares nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c58_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c58_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($c58_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($c58_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10813,'$c58_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1862,10813,'','".AddSlashes(pg_result($resaco,$iresaco,'c58_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1862,10814,'','".AddSlashes(pg_result($resaco,$iresaco,'c58_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1862,10815,'','".AddSlashes(pg_result($resaco,$iresaco,'c58_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1862,18122,'','".AddSlashes(pg_result($resaco,$iresaco,'c58_db_estruturavalor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1862,18123,'','".AddSlashes(pg_result($resaco,$iresaco,'c58_estrutural'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from concarpeculiar
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c58_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c58_sequencial = '$c58_sequencial' ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Caracteristicas Pecualiares nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c58_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Caracteristicas Pecualiares nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c58_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c58_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:concarpeculiar";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $c58_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from concarpeculiar ";
     $sql2 = "";
     if($dbwhere==""){
       if($c58_sequencial!=null ){
         $sql2 .= " where concarpeculiar.c58_sequencial = '$c58_sequencial' "; 
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
   function sql_query_file ( $c58_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from concarpeculiar ";
     $sql2 = "";
     if($dbwhere==""){
       if($c58_sequencial!=null ){
         $sql2 .= " where concarpeculiar.c58_sequencial = '$c58_sequencial' "; 
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
   /**
   * Busca os dados de uma característica incluíndo a tabela db_estruturavalor
   *
   * @param string $c58_sequencial
   * @param string $campos
   * @param string $ordem
   * @param string $dbwhere
   * @return string
   */
  function buscaDadosCaracteristica( $c58_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "" ){
    
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
     $sql .= " from concarpeculiar ";
     $sql .= "      inner join db_estruturavalor on concarpeculiar.c58_sequencial = db_estruturavalor.db121_estrutural ";
     $sql2 = "";
     if($dbwhere==""){
       if($c58_sequencial!=null ){
         $sql2 .= " where concarpeculiar.c58_sequencial = '$c58_sequencial' "; 
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