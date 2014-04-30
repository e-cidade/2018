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

//MODULO: orcamento
//CLASSE DA ENTIDADE orcsuplemdespesappa
class cl_orcsuplemdespesappa { 
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
   var $o136_sequencial = 0; 
   var $o136_orcsuplem = 0; 
   var $o136_ppaestimativadespesa = 0; 
   var $o136_valor = 0; 
   var $o136_concarpeculiar = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o136_sequencial = int4 = Sequencial 
                 o136_orcsuplem = int4 = Suplementação 
                 o136_ppaestimativadespesa = int4 = Estimativa Despesa 
                 o136_valor = int4 = Valor 
                 o136_concarpeculiar = varchar(100) = C.Peculiar/ C. Aplicação 
                 ";
   //funcao construtor da classe 
   function cl_orcsuplemdespesappa() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcsuplemdespesappa"); 
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
       $this->o136_sequencial = ($this->o136_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o136_sequencial"]:$this->o136_sequencial);
       $this->o136_orcsuplem = ($this->o136_orcsuplem == ""?@$GLOBALS["HTTP_POST_VARS"]["o136_orcsuplem"]:$this->o136_orcsuplem);
       $this->o136_ppaestimativadespesa = ($this->o136_ppaestimativadespesa == ""?@$GLOBALS["HTTP_POST_VARS"]["o136_ppaestimativadespesa"]:$this->o136_ppaestimativadespesa);
       $this->o136_valor = ($this->o136_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["o136_valor"]:$this->o136_valor);
       $this->o136_concarpeculiar = ($this->o136_concarpeculiar == ""?@$GLOBALS["HTTP_POST_VARS"]["o136_concarpeculiar"]:$this->o136_concarpeculiar);
     }else{
       $this->o136_sequencial = ($this->o136_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o136_sequencial"]:$this->o136_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($o136_sequencial){ 
      $this->atualizacampos();
     if($this->o136_orcsuplem == null ){ 
       $this->erro_sql = " Campo Suplementação nao Informado.";
       $this->erro_campo = "o136_orcsuplem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o136_ppaestimativadespesa == null ){ 
       $this->erro_sql = " Campo Estimativa Despesa nao Informado.";
       $this->erro_campo = "o136_ppaestimativadespesa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o136_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "o136_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o136_concarpeculiar == null ){ 
       $this->erro_sql = " Campo C.Peculiar/ C. Aplicação nao Informado.";
       $this->erro_campo = "o136_concarpeculiar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o136_sequencial == "" || $o136_sequencial == null ){
       $result = db_query("select nextval('orcsuplemdespesappa_o136_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: orcsuplemdespesappa_o136_sequencial_seq do campo: o136_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o136_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from orcsuplemdespesappa_o136_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $o136_sequencial)){
         $this->erro_sql = " Campo o136_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o136_sequencial = $o136_sequencial; 
       }
     }
     if(($this->o136_sequencial == null) || ($this->o136_sequencial == "") ){ 
       $this->erro_sql = " Campo o136_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcsuplemdespesappa(
                                       o136_sequencial 
                                      ,o136_orcsuplem 
                                      ,o136_ppaestimativadespesa 
                                      ,o136_valor 
                                      ,o136_concarpeculiar 
                       )
                values (
                                $this->o136_sequencial 
                               ,$this->o136_orcsuplem 
                               ,$this->o136_ppaestimativadespesa 
                               ,$this->o136_valor 
                               ,'$this->o136_concarpeculiar' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Dotações da Suplementação ($this->o136_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Dotações da Suplementação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Dotações da Suplementação ($this->o136_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o136_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o136_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17672,'$this->o136_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3121,17672,'','".AddSlashes(pg_result($resaco,0,'o136_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3121,17673,'','".AddSlashes(pg_result($resaco,0,'o136_orcsuplem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3121,17674,'','".AddSlashes(pg_result($resaco,0,'o136_ppaestimativadespesa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3121,17676,'','".AddSlashes(pg_result($resaco,0,'o136_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3121,18160,'','".AddSlashes(pg_result($resaco,0,'o136_concarpeculiar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o136_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update orcsuplemdespesappa set ";
     $virgula = "";
     if(trim($this->o136_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o136_sequencial"])){ 
       $sql  .= $virgula." o136_sequencial = $this->o136_sequencial ";
       $virgula = ",";
       if(trim($this->o136_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "o136_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o136_orcsuplem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o136_orcsuplem"])){ 
       $sql  .= $virgula." o136_orcsuplem = $this->o136_orcsuplem ";
       $virgula = ",";
       if(trim($this->o136_orcsuplem) == null ){ 
         $this->erro_sql = " Campo Suplementação nao Informado.";
         $this->erro_campo = "o136_orcsuplem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o136_ppaestimativadespesa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o136_ppaestimativadespesa"])){ 
       $sql  .= $virgula." o136_ppaestimativadespesa = $this->o136_ppaestimativadespesa ";
       $virgula = ",";
       if(trim($this->o136_ppaestimativadespesa) == null ){ 
         $this->erro_sql = " Campo Estimativa Despesa nao Informado.";
         $this->erro_campo = "o136_ppaestimativadespesa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o136_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o136_valor"])){ 
       $sql  .= $virgula." o136_valor = $this->o136_valor ";
       $virgula = ",";
       if(trim($this->o136_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "o136_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o136_concarpeculiar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o136_concarpeculiar"])){ 
       $sql  .= $virgula." o136_concarpeculiar = '$this->o136_concarpeculiar' ";
       $virgula = ",";
       if(trim($this->o136_concarpeculiar) == null ){ 
         $this->erro_sql = " Campo C.Peculiar/ C. Aplicação nao Informado.";
         $this->erro_campo = "o136_concarpeculiar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o136_sequencial!=null){
       $sql .= " o136_sequencial = $this->o136_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o136_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17672,'$this->o136_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o136_sequencial"]) || $this->o136_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3121,17672,'".AddSlashes(pg_result($resaco,$conresaco,'o136_sequencial'))."','$this->o136_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o136_orcsuplem"]) || $this->o136_orcsuplem != "")
           $resac = db_query("insert into db_acount values($acount,3121,17673,'".AddSlashes(pg_result($resaco,$conresaco,'o136_orcsuplem'))."','$this->o136_orcsuplem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o136_ppaestimativadespesa"]) || $this->o136_ppaestimativadespesa != "")
           $resac = db_query("insert into db_acount values($acount,3121,17674,'".AddSlashes(pg_result($resaco,$conresaco,'o136_ppaestimativadespesa'))."','$this->o136_ppaestimativadespesa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o136_valor"]) || $this->o136_valor != "")
           $resac = db_query("insert into db_acount values($acount,3121,17676,'".AddSlashes(pg_result($resaco,$conresaco,'o136_valor'))."','$this->o136_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o136_concarpeculiar"]) || $this->o136_concarpeculiar != "")
           $resac = db_query("insert into db_acount values($acount,3121,18160,'".AddSlashes(pg_result($resaco,$conresaco,'o136_concarpeculiar'))."','$this->o136_concarpeculiar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dotações da Suplementação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o136_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Dotações da Suplementação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o136_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o136_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o136_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o136_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17672,'$o136_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3121,17672,'','".AddSlashes(pg_result($resaco,$iresaco,'o136_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3121,17673,'','".AddSlashes(pg_result($resaco,$iresaco,'o136_orcsuplem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3121,17674,'','".AddSlashes(pg_result($resaco,$iresaco,'o136_ppaestimativadespesa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3121,17676,'','".AddSlashes(pg_result($resaco,$iresaco,'o136_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3121,18160,'','".AddSlashes(pg_result($resaco,$iresaco,'o136_concarpeculiar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcsuplemdespesappa
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o136_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o136_sequencial = $o136_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dotações da Suplementação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o136_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Dotações da Suplementação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o136_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o136_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcsuplemdespesappa";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $o136_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcsuplemdespesappa ";
     $sql .= "      inner join orcsuplem  on  orcsuplem.o46_codsup = orcsuplemdespesappa.o136_orcsuplem";
     $sql .= "      inner join concarpeculiar  on  concarpeculiar.c58_sequencial = orcsuplemdespesappa.o136_concarpeculiar";
     $sql .= "      inner join orcsuplemtipo  on  orcsuplemtipo.o48_tiposup = orcsuplem.o46_tiposup";
     $sql .= "      inner join orcprojeto  on  orcprojeto.o39_codproj = orcsuplem.o46_codlei";
     $sql2 = "";
     if($dbwhere==""){
       if($o136_sequencial!=null ){
         $sql2 .= " where orcsuplemdespesappa.o136_sequencial = $o136_sequencial "; 
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
   function sql_query_file ( $o136_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcsuplemdespesappa ";
     $sql2 = "";
     if($dbwhere==""){
       if($o136_sequencial!=null ){
         $sql2 .= " where orcsuplemdespesappa.o136_sequencial = $o136_sequencial "; 
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
  
  function sql_query_dotacaoppa ( $o136_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcsuplemdespesappa ";
     $sql .= "      inner join ppaestimativadespesa on o136_ppaestimativadespesa = o07_sequencial ";
     $sql .= "      inner join ppadotacao    on o07_coddot         = o08_sequencial ";
     $sql .= "      inner join orcsuplem  on  orcsuplem.o46_codsup = orcsuplemdespesappa.o136_orcsuplem";
     $sql2 = "";
     if($dbwhere==""){
       if($o136_sequencial!=null ){
         $sql2 .= " where orcsuplemdespesappa.o136_sequencial = $o136_sequencial "; 
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