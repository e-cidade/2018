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

//MODULO: orcamento
//CLASSE DA ENTIDADE ppaestimativa
class cl_ppaestimativa { 
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
   var $o05_sequencial = 0; 
   var $o05_ppaversao = 0; 
   var $o05_anoreferencia = 0; 
   var $o05_base = 'f'; 
   var $o05_valor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o05_sequencial = int4 = Código Sequencial 
                 o05_ppaversao = int4 = Versão do PPA 
                 o05_anoreferencia = int4 = Ano de Referência 
                 o05_base = bool = Base para  Cálculo 
                 o05_valor = float8 = Valor Estimado 
                 ";
   //funcao construtor da classe 
   function cl_ppaestimativa() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("ppaestimativa"); 
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
       $this->o05_sequencial = ($this->o05_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o05_sequencial"]:$this->o05_sequencial);
       $this->o05_ppaversao = ($this->o05_ppaversao == ""?@$GLOBALS["HTTP_POST_VARS"]["o05_ppaversao"]:$this->o05_ppaversao);
       $this->o05_anoreferencia = ($this->o05_anoreferencia == ""?@$GLOBALS["HTTP_POST_VARS"]["o05_anoreferencia"]:$this->o05_anoreferencia);
       $this->o05_base = ($this->o05_base == "f"?@$GLOBALS["HTTP_POST_VARS"]["o05_base"]:$this->o05_base);
       $this->o05_valor = ($this->o05_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["o05_valor"]:$this->o05_valor);
     }else{
       $this->o05_sequencial = ($this->o05_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o05_sequencial"]:$this->o05_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($o05_sequencial){ 
      $this->atualizacampos();
     if($this->o05_ppaversao == null ){ 
       $this->erro_sql = " Campo Versão do PPA nao Informado.";
       $this->erro_campo = "o05_ppaversao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o05_anoreferencia == null ){ 
       $this->erro_sql = " Campo Ano de Referência nao Informado.";
       $this->erro_campo = "o05_anoreferencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o05_base == null ){ 
       $this->erro_sql = " Campo Base para  Cálculo nao Informado.";
       $this->erro_campo = "o05_base";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o05_valor == null ){ 
       $this->erro_sql = " Campo Valor Estimado nao Informado.";
       $this->erro_campo = "o05_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o05_sequencial == "" || $o05_sequencial == null ){
       $result = db_query("select nextval('ppaestimativa_o05_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: ppaestimativa_o05_sequencial_seq do campo: o05_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o05_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from ppaestimativa_o05_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $o05_sequencial)){
         $this->erro_sql = " Campo o05_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o05_sequencial = $o05_sequencial; 
       }
     }
     if(($this->o05_sequencial == null) || ($this->o05_sequencial == "") ){ 
       $this->erro_sql = " Campo o05_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into ppaestimativa(
                                       o05_sequencial 
                                      ,o05_ppaversao 
                                      ,o05_anoreferencia 
                                      ,o05_base 
                                      ,o05_valor 
                       )
                values (
                                $this->o05_sequencial 
                               ,$this->o05_ppaversao 
                               ,$this->o05_anoreferencia 
                               ,'$this->o05_base' 
                               ,$this->o05_valor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "ppaestimativa ($this->o05_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "ppaestimativa já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "ppaestimativa ($this->o05_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o05_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o05_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,13597,'$this->o05_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2382,13597,'','".AddSlashes(pg_result($resaco,0,'o05_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2382,13598,'','".AddSlashes(pg_result($resaco,0,'o05_ppaversao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2382,13609,'','".AddSlashes(pg_result($resaco,0,'o05_anoreferencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2382,13610,'','".AddSlashes(pg_result($resaco,0,'o05_base'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2382,13611,'','".AddSlashes(pg_result($resaco,0,'o05_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o05_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update ppaestimativa set ";
     $virgula = "";
     if(trim($this->o05_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o05_sequencial"])){ 
       $sql  .= $virgula." o05_sequencial = $this->o05_sequencial ";
       $virgula = ",";
       if(trim($this->o05_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "o05_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o05_ppaversao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o05_ppaversao"])){ 
       $sql  .= $virgula." o05_ppaversao = $this->o05_ppaversao ";
       $virgula = ",";
       if(trim($this->o05_ppaversao) == null ){ 
         $this->erro_sql = " Campo Versão do PPA nao Informado.";
         $this->erro_campo = "o05_ppaversao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o05_anoreferencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o05_anoreferencia"])){ 
       $sql  .= $virgula." o05_anoreferencia = $this->o05_anoreferencia ";
       $virgula = ",";
       if(trim($this->o05_anoreferencia) == null ){ 
         $this->erro_sql = " Campo Ano de Referência nao Informado.";
         $this->erro_campo = "o05_anoreferencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o05_base)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o05_base"])){ 
       $sql  .= $virgula." o05_base = '$this->o05_base' ";
       $virgula = ",";
       if(trim($this->o05_base) == null ){ 
         $this->erro_sql = " Campo Base para  Cálculo nao Informado.";
         $this->erro_campo = "o05_base";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o05_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o05_valor"])){ 
       $sql  .= $virgula." o05_valor = $this->o05_valor ";
       $virgula = ",";
       if(trim($this->o05_valor) == null ){ 
         $this->erro_sql = " Campo Valor Estimado nao Informado.";
         $this->erro_campo = "o05_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o05_sequencial!=null){
       $sql .= " o05_sequencial = $this->o05_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o05_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13597,'$this->o05_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o05_sequencial"]) || $this->o05_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2382,13597,'".AddSlashes(pg_result($resaco,$conresaco,'o05_sequencial'))."','$this->o05_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o05_ppaversao"]) || $this->o05_ppaversao != "")
           $resac = db_query("insert into db_acount values($acount,2382,13598,'".AddSlashes(pg_result($resaco,$conresaco,'o05_ppaversao'))."','$this->o05_ppaversao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o05_anoreferencia"]) || $this->o05_anoreferencia != "")
           $resac = db_query("insert into db_acount values($acount,2382,13609,'".AddSlashes(pg_result($resaco,$conresaco,'o05_anoreferencia'))."','$this->o05_anoreferencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o05_base"]) || $this->o05_base != "")
           $resac = db_query("insert into db_acount values($acount,2382,13610,'".AddSlashes(pg_result($resaco,$conresaco,'o05_base'))."','$this->o05_base',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o05_valor"]) || $this->o05_valor != "")
           $resac = db_query("insert into db_acount values($acount,2382,13611,'".AddSlashes(pg_result($resaco,$conresaco,'o05_valor'))."','$this->o05_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "ppaestimativa nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o05_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "ppaestimativa nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o05_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o05_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o05_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o05_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13597,'$o05_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2382,13597,'','".AddSlashes(pg_result($resaco,$iresaco,'o05_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2382,13598,'','".AddSlashes(pg_result($resaco,$iresaco,'o05_ppaversao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2382,13609,'','".AddSlashes(pg_result($resaco,$iresaco,'o05_anoreferencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2382,13610,'','".AddSlashes(pg_result($resaco,$iresaco,'o05_base'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2382,13611,'','".AddSlashes(pg_result($resaco,$iresaco,'o05_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from ppaestimativa
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o05_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o05_sequencial = $o05_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "ppaestimativa nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o05_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "ppaestimativa nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o05_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o05_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:ppaestimativa";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $o05_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ppaestimativa ";
     $sql .= "      inner join ppaversao  on  ppaversao.o119_sequencial = ppaestimativa.o05_ppaversao";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = ppaversao.o119_idusuario";
     $sql .= "      inner join ppalei  on  ppalei.o01_sequencial = ppaversao.o119_ppalei";
     $sql2 = "";
     if($dbwhere==""){
       if($o05_sequencial!=null ){
         $sql2 .= " where ppaestimativa.o05_sequencial = $o05_sequencial "; 
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
   function sql_query_file ( $o05_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ppaestimativa ";
     $sql2 = "";
     if($dbwhere==""){
       if($o05_sequencial!=null ){
         $sql2 .= " where ppaestimativa.o05_sequencial = $o05_sequencial "; 
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