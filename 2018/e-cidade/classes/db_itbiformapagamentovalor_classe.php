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
//CLASSE DA ENTIDADE itbiformapagamentovalor
class cl_itbiformapagamentovalor { 
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
   var $it26_sequencial = 0; 
   var $it26_itbitransacaoformapag = 0; 
   var $it26_guia = 0; 
   var $it26_valor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 it26_sequencial = int4 = Sequencial 
                 it26_itbitransacaoformapag = int4 = Forma Pagamento Transação 
                 it26_guia = int4 = Guia 
                 it26_valor = float4 = Valor 
                 ";
   //funcao construtor da classe 
   function cl_itbiformapagamentovalor() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("itbiformapagamentovalor"); 
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
       $this->it26_sequencial = ($this->it26_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["it26_sequencial"]:$this->it26_sequencial);
       $this->it26_itbitransacaoformapag = ($this->it26_itbitransacaoformapag == ""?@$GLOBALS["HTTP_POST_VARS"]["it26_itbitransacaoformapag"]:$this->it26_itbitransacaoformapag);
       $this->it26_guia = ($this->it26_guia == ""?@$GLOBALS["HTTP_POST_VARS"]["it26_guia"]:$this->it26_guia);
       $this->it26_valor = ($this->it26_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["it26_valor"]:$this->it26_valor);
     }else{
       $this->it26_sequencial = ($this->it26_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["it26_sequencial"]:$this->it26_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($it26_sequencial){ 
      $this->atualizacampos();
     if($this->it26_itbitransacaoformapag == null ){ 
       $this->erro_sql = " Campo Forma Pagamento Transação nao Informado.";
       $this->erro_campo = "it26_itbitransacaoformapag";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it26_guia == null ){ 
       $this->erro_sql = " Campo Guia nao Informado.";
       $this->erro_campo = "it26_guia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it26_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "it26_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($it26_sequencial == "" || $it26_sequencial == null ){
       $result = db_query("select nextval('itbiformapagamentovalor_it26_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: itbiformapagamentovalor_it26_sequencial_seq do campo: it26_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->it26_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from itbiformapagamentovalor_it26_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $it26_sequencial)){
         $this->erro_sql = " Campo it26_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->it26_sequencial = $it26_sequencial; 
       }
     }
     if(($this->it26_sequencial == null) || ($this->it26_sequencial == "") ){ 
       $this->erro_sql = " Campo it26_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into itbiformapagamentovalor(
                                       it26_sequencial 
                                      ,it26_itbitransacaoformapag 
                                      ,it26_guia 
                                      ,it26_valor 
                       )
                values (
                                $this->it26_sequencial 
                               ,$this->it26_itbitransacaoformapag 
                               ,$this->it26_guia 
                               ,$this->it26_valor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "itbiformapagamentovalor ($this->it26_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "itbiformapagamentovalor já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "itbiformapagamentovalor ($this->it26_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->it26_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->it26_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,13512,'$this->it26_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2363,13512,'','".AddSlashes(pg_result($resaco,0,'it26_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2363,13513,'','".AddSlashes(pg_result($resaco,0,'it26_itbitransacaoformapag'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2363,13514,'','".AddSlashes(pg_result($resaco,0,'it26_guia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2363,13515,'','".AddSlashes(pg_result($resaco,0,'it26_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($it26_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update itbiformapagamentovalor set ";
     $virgula = "";
     if(trim($this->it26_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it26_sequencial"])){ 
       $sql  .= $virgula." it26_sequencial = $this->it26_sequencial ";
       $virgula = ",";
       if(trim($this->it26_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "it26_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it26_itbitransacaoformapag)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it26_itbitransacaoformapag"])){ 
       $sql  .= $virgula." it26_itbitransacaoformapag = $this->it26_itbitransacaoformapag ";
       $virgula = ",";
       if(trim($this->it26_itbitransacaoformapag) == null ){ 
         $this->erro_sql = " Campo Forma Pagamento Transação nao Informado.";
         $this->erro_campo = "it26_itbitransacaoformapag";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it26_guia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it26_guia"])){ 
       $sql  .= $virgula." it26_guia = $this->it26_guia ";
       $virgula = ",";
       if(trim($this->it26_guia) == null ){ 
         $this->erro_sql = " Campo Guia nao Informado.";
         $this->erro_campo = "it26_guia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it26_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it26_valor"])){ 
       $sql  .= $virgula." it26_valor = $this->it26_valor ";
       $virgula = ",";
       if(trim($this->it26_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "it26_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($it26_sequencial!=null){
       $sql .= " it26_sequencial = $this->it26_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->it26_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13512,'$this->it26_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it26_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2363,13512,'".AddSlashes(pg_result($resaco,$conresaco,'it26_sequencial'))."','$this->it26_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it26_itbitransacaoformapag"]))
           $resac = db_query("insert into db_acount values($acount,2363,13513,'".AddSlashes(pg_result($resaco,$conresaco,'it26_itbitransacaoformapag'))."','$this->it26_itbitransacaoformapag',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it26_guia"]))
           $resac = db_query("insert into db_acount values($acount,2363,13514,'".AddSlashes(pg_result($resaco,$conresaco,'it26_guia'))."','$this->it26_guia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it26_valor"]))
           $resac = db_query("insert into db_acount values($acount,2363,13515,'".AddSlashes(pg_result($resaco,$conresaco,'it26_valor'))."','$this->it26_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "itbiformapagamentovalor nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->it26_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "itbiformapagamentovalor nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->it26_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->it26_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($it26_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($it26_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13512,'$it26_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2363,13512,'','".AddSlashes(pg_result($resaco,$iresaco,'it26_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2363,13513,'','".AddSlashes(pg_result($resaco,$iresaco,'it26_itbitransacaoformapag'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2363,13514,'','".AddSlashes(pg_result($resaco,$iresaco,'it26_guia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2363,13515,'','".AddSlashes(pg_result($resaco,$iresaco,'it26_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from itbiformapagamentovalor
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($it26_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " it26_sequencial = $it26_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "itbiformapagamentovalor nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$it26_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "itbiformapagamentovalor nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$it26_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$it26_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:itbiformapagamentovalor";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $it26_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from itbiformapagamentovalor ";
     $sql .= "      inner join itbi  on  itbi.it01_guia = itbiformapagamentovalor.it26_guia";
     $sql .= "      inner join itbitransacaoformapag  on  itbitransacaoformapag.it25_sequencial = itbiformapagamentovalor.it26_itbitransacaoformapag";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = itbi.it01_id_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = itbi.it01_coddepto";
     $sql .= "      inner join itbitransacao  as a on   a.it04_codigo = itbi.it01_tipotransacao";
     $sql .= "      inner join itbitransacao  as b on   b.it04_codigo = itbitransacaoformapag.it25_itbitransacao";
     $sql .= "      inner join itbiformapagamento  on  itbiformapagamento.it27_sequencial = itbitransacaoformapag.it25_itbiformapagamento";
     $sql2 = "";
     if($dbwhere==""){
       if($it26_sequencial!=null ){
         $sql2 .= " where itbiformapagamentovalor.it26_sequencial = $it26_sequencial "; 
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
   function sql_query_file ( $it26_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from itbiformapagamentovalor ";
     $sql2 = "";
     if($dbwhere==""){
       if($it26_sequencial!=null ){
         $sql2 .= " where itbiformapagamentovalor.it26_sequencial = $it26_sequencial "; 
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