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

//MODULO: itbi
//CLASSE DA ENTIDADE itbiavaliaformapagamentovalor
class cl_itbiavaliaformapagamentovalor { 
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
   var $it24_sequencial = 0; 
   var $it24_itbitransacaoformapag = 0; 
   var $it24_itbiavalia = 0; 
   var $it24_valor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 it24_sequencial = int4 = Sequencial 
                 it24_itbitransacaoformapag = int4 = Transação 
                 it24_itbiavalia = int4 = Avalia 
                 it24_valor = float4 = Valor 
                 ";
   //funcao construtor da classe 
   function cl_itbiavaliaformapagamentovalor() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("itbiavaliaformapagamentovalor"); 
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
       $this->it24_sequencial = ($this->it24_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["it24_sequencial"]:$this->it24_sequencial);
       $this->it24_itbitransacaoformapag = ($this->it24_itbitransacaoformapag == ""?@$GLOBALS["HTTP_POST_VARS"]["it24_itbitransacaoformapag"]:$this->it24_itbitransacaoformapag);
       $this->it24_itbiavalia = ($this->it24_itbiavalia == ""?@$GLOBALS["HTTP_POST_VARS"]["it24_itbiavalia"]:$this->it24_itbiavalia);
       $this->it24_valor = ($this->it24_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["it24_valor"]:$this->it24_valor);
     }else{
       $this->it24_sequencial = ($this->it24_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["it24_sequencial"]:$this->it24_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($it24_sequencial){ 
      $this->atualizacampos();
     if($this->it24_itbitransacaoformapag == null ){ 
       $this->erro_sql = " Campo Transação nao Informado.";
       $this->erro_campo = "it24_itbitransacaoformapag";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it24_itbiavalia == null ){ 
       $this->erro_sql = " Campo Avalia nao Informado.";
       $this->erro_campo = "it24_itbiavalia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it24_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "it24_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($it24_sequencial == "" || $it24_sequencial == null ){
       $result = db_query("select nextval('itbiavaliaformapagamentovalor_it24_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: itbiavaliaformapagamentovalor_it24_sequencial_seq do campo: it24_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->it24_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from itbiavaliaformapagamentovalor_it24_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $it24_sequencial)){
         $this->erro_sql = " Campo it24_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->it24_sequencial = $it24_sequencial; 
       }
     }
     if(($this->it24_sequencial == null) || ($this->it24_sequencial == "") ){ 
       $this->erro_sql = " Campo it24_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into itbiavaliaformapagamentovalor(
                                       it24_sequencial 
                                      ,it24_itbitransacaoformapag 
                                      ,it24_itbiavalia 
                                      ,it24_valor 
                       )
                values (
                                $this->it24_sequencial 
                               ,$this->it24_itbitransacaoformapag 
                               ,$this->it24_itbiavalia 
                               ,$this->it24_valor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "itbiavaliaformapagamentovalor ($this->it24_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "itbiavaliaformapagamentovalor já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "itbiavaliaformapagamentovalor ($this->it24_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->it24_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->it24_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,13489,'$this->it24_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2360,13489,'','".AddSlashes(pg_result($resaco,0,'it24_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2360,13490,'','".AddSlashes(pg_result($resaco,0,'it24_itbitransacaoformapag'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2360,13491,'','".AddSlashes(pg_result($resaco,0,'it24_itbiavalia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2360,13492,'','".AddSlashes(pg_result($resaco,0,'it24_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($it24_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update itbiavaliaformapagamentovalor set ";
     $virgula = "";
     if(trim($this->it24_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it24_sequencial"])){ 
       $sql  .= $virgula." it24_sequencial = $this->it24_sequencial ";
       $virgula = ",";
       if(trim($this->it24_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "it24_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it24_itbitransacaoformapag)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it24_itbitransacaoformapag"])){ 
       $sql  .= $virgula." it24_itbitransacaoformapag = $this->it24_itbitransacaoformapag ";
       $virgula = ",";
       if(trim($this->it24_itbitransacaoformapag) == null ){ 
         $this->erro_sql = " Campo Transação nao Informado.";
         $this->erro_campo = "it24_itbitransacaoformapag";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it24_itbiavalia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it24_itbiavalia"])){ 
       $sql  .= $virgula." it24_itbiavalia = $this->it24_itbiavalia ";
       $virgula = ",";
       if(trim($this->it24_itbiavalia) == null ){ 
         $this->erro_sql = " Campo Avalia nao Informado.";
         $this->erro_campo = "it24_itbiavalia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it24_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it24_valor"])){ 
       $sql  .= $virgula." it24_valor = $this->it24_valor ";
       $virgula = ",";
       if(trim($this->it24_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "it24_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($it24_sequencial!=null){
       $sql .= " it24_sequencial = $this->it24_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->it24_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13489,'$this->it24_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it24_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2360,13489,'".AddSlashes(pg_result($resaco,$conresaco,'it24_sequencial'))."','$this->it24_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it24_itbitransacaoformapag"]))
           $resac = db_query("insert into db_acount values($acount,2360,13490,'".AddSlashes(pg_result($resaco,$conresaco,'it24_itbitransacaoformapag'))."','$this->it24_itbitransacaoformapag',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it24_itbiavalia"]))
           $resac = db_query("insert into db_acount values($acount,2360,13491,'".AddSlashes(pg_result($resaco,$conresaco,'it24_itbiavalia'))."','$this->it24_itbiavalia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it24_valor"]))
           $resac = db_query("insert into db_acount values($acount,2360,13492,'".AddSlashes(pg_result($resaco,$conresaco,'it24_valor'))."','$this->it24_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "itbiavaliaformapagamentovalor nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->it24_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "itbiavaliaformapagamentovalor nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->it24_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->it24_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($it24_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($it24_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13489,'$it24_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2360,13489,'','".AddSlashes(pg_result($resaco,$iresaco,'it24_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2360,13490,'','".AddSlashes(pg_result($resaco,$iresaco,'it24_itbitransacaoformapag'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2360,13491,'','".AddSlashes(pg_result($resaco,$iresaco,'it24_itbiavalia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2360,13492,'','".AddSlashes(pg_result($resaco,$iresaco,'it24_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from itbiavaliaformapagamentovalor
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($it24_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " it24_sequencial = $it24_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "itbiavaliaformapagamentovalor nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$it24_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "itbiavaliaformapagamentovalor nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$it24_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$it24_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:itbiavaliaformapagamentovalor";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $it24_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from itbiavaliaformapagamentovalor ";
     $sql .= "      inner join itbiavalia  on  itbiavalia.it14_guia = itbiavaliaformapagamentovalor.it24_itbiavalia";
     $sql .= "      inner join itbitransacaoformapag  on  itbitransacaoformapag.it25_sequencial = itbiavaliaformapagamentovalor.it24_itbitransacaoformapag";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = itbiavalia.it14_id_usuario";
     $sql .= "      inner join itbi  on  itbi.it01_guia = itbiavalia.it14_guia";
     $sql .= "      inner join itbitransacao  as a on   a.it04_codigo = itbitransacaoformapag.it25_itbitransacao";
     $sql .= "      inner join itbiformapagamento  on  itbiformapagamento.it27_sequencial = itbitransacaoformapag.it25_itbiformapagamento";
     $sql2 = "";
     if($dbwhere==""){
       if($it24_sequencial!=null ){
         $sql2 .= " where itbiavaliaformapagamentovalor.it24_sequencial = $it24_sequencial "; 
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
   function sql_query_file ( $it24_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from itbiavaliaformapagamentovalor ";
     $sql2 = "";
     if($dbwhere==""){
       if($it24_sequencial!=null ){
         $sql2 .= " where itbiavaliaformapagamentovalor.it24_sequencial = $it24_sequencial "; 
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