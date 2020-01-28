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

//MODULO: Caixa
//CLASSE DA ENTIDADE tabplansaldorecursomov
class cl_tabplansaldorecursomov { 
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
   var $k113_sequencial = 0; 
   var $k113_recurso = 0; 
   var $k113_conta = 0; 
   var $k113_valor = 0; 
   var $k113_data_dia = null; 
   var $k113_data_mes = null; 
   var $k113_data_ano = null; 
   var $k113_data = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k113_sequencial = int4 = Código Sequêncial 
                 k113_recurso = int4 = Código do Recurso 
                 k113_conta = int4 = Cód. Plano Contas 
                 k113_valor = float8 = Valor 
                 k113_data = date = Data 
                 ";
   //funcao construtor da classe 
   function cl_tabplansaldorecursomov() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tabplansaldorecursomov"); 
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
       $this->k113_sequencial = ($this->k113_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k113_sequencial"]:$this->k113_sequencial);
       $this->k113_recurso = ($this->k113_recurso == ""?@$GLOBALS["HTTP_POST_VARS"]["k113_recurso"]:$this->k113_recurso);
       $this->k113_conta = ($this->k113_conta == ""?@$GLOBALS["HTTP_POST_VARS"]["k113_conta"]:$this->k113_conta);
       $this->k113_valor = ($this->k113_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["k113_valor"]:$this->k113_valor);
       if($this->k113_data == ""){
         $this->k113_data_dia = ($this->k113_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k113_data_dia"]:$this->k113_data_dia);
         $this->k113_data_mes = ($this->k113_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k113_data_mes"]:$this->k113_data_mes);
         $this->k113_data_ano = ($this->k113_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k113_data_ano"]:$this->k113_data_ano);
         if($this->k113_data_dia != ""){
            $this->k113_data = $this->k113_data_ano."-".$this->k113_data_mes."-".$this->k113_data_dia;
         }
       }
     }else{
       $this->k113_sequencial = ($this->k113_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k113_sequencial"]:$this->k113_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k113_sequencial){ 
      $this->atualizacampos();
     if($this->k113_recurso == null ){ 
       $this->erro_sql = " Campo Código do Recurso nao Informado.";
       $this->erro_campo = "k113_recurso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k113_conta == null ){ 
       $this->erro_sql = " Campo Cód. Plano Contas nao Informado.";
       $this->erro_campo = "k113_conta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k113_valor == null ){ 
       $this->k113_valor = "0";
     }
     if($this->k113_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "k113_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k113_sequencial == "" || $k113_sequencial == null ){
       $result = db_query("select nextval('tabplansaldorecursomov_k113_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tabplansaldorecursomov_k113_sequencial_seq do campo: k113_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k113_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tabplansaldorecursomov_k113_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k113_sequencial)){
         $this->erro_sql = " Campo k113_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k113_sequencial = $k113_sequencial; 
       }
     }
     if(($this->k113_sequencial == null) || ($this->k113_sequencial == "") ){ 
       $this->erro_sql = " Campo k113_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tabplansaldorecursomov(
                                       k113_sequencial 
                                      ,k113_recurso 
                                      ,k113_conta 
                                      ,k113_valor 
                                      ,k113_data 
                       )
                values (
                                $this->k113_sequencial 
                               ,$this->k113_recurso 
                               ,$this->k113_conta 
                               ,$this->k113_valor 
                               ,".($this->k113_data == "null" || $this->k113_data == ""?"null":"'".$this->k113_data."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Movimentações dos Saldos Extras ($this->k113_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Movimentações dos Saldos Extras já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Movimentações dos Saldos Extras ($this->k113_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k113_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k113_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14541,'$this->k113_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2561,14541,'','".AddSlashes(pg_result($resaco,0,'k113_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2561,14542,'','".AddSlashes(pg_result($resaco,0,'k113_recurso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2561,14544,'','".AddSlashes(pg_result($resaco,0,'k113_conta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2561,14543,'','".AddSlashes(pg_result($resaco,0,'k113_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2561,14545,'','".AddSlashes(pg_result($resaco,0,'k113_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k113_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update tabplansaldorecursomov set ";
     $virgula = "";
     if(trim($this->k113_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k113_sequencial"])){ 
       $sql  .= $virgula." k113_sequencial = $this->k113_sequencial ";
       $virgula = ",";
       if(trim($this->k113_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequêncial nao Informado.";
         $this->erro_campo = "k113_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k113_recurso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k113_recurso"])){ 
       $sql  .= $virgula." k113_recurso = $this->k113_recurso ";
       $virgula = ",";
       if(trim($this->k113_recurso) == null ){ 
         $this->erro_sql = " Campo Código do Recurso nao Informado.";
         $this->erro_campo = "k113_recurso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k113_conta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k113_conta"])){ 
       $sql  .= $virgula." k113_conta = $this->k113_conta ";
       $virgula = ",";
       if(trim($this->k113_conta) == null ){ 
         $this->erro_sql = " Campo Cód. Plano Contas nao Informado.";
         $this->erro_campo = "k113_conta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k113_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k113_valor"])){ 
        if(trim($this->k113_valor)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k113_valor"])){ 
           $this->k113_valor = "0" ; 
        } 
       $sql  .= $virgula." k113_valor = $this->k113_valor ";
       $virgula = ",";
     }
     if(trim($this->k113_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k113_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k113_data_dia"] !="") ){ 
       $sql  .= $virgula." k113_data = '$this->k113_data' ";
       $virgula = ",";
       if(trim($this->k113_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "k113_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k113_data_dia"])){ 
         $sql  .= $virgula." k113_data = null ";
         $virgula = ",";
         if(trim($this->k113_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "k113_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($k113_sequencial!=null){
       $sql .= " k113_sequencial = $this->k113_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k113_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14541,'$this->k113_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k113_sequencial"]) || $this->k113_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2561,14541,'".AddSlashes(pg_result($resaco,$conresaco,'k113_sequencial'))."','$this->k113_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k113_recurso"]) || $this->k113_recurso != "")
           $resac = db_query("insert into db_acount values($acount,2561,14542,'".AddSlashes(pg_result($resaco,$conresaco,'k113_recurso'))."','$this->k113_recurso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k113_conta"]) || $this->k113_conta != "")
           $resac = db_query("insert into db_acount values($acount,2561,14544,'".AddSlashes(pg_result($resaco,$conresaco,'k113_conta'))."','$this->k113_conta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k113_valor"]) || $this->k113_valor != "")
           $resac = db_query("insert into db_acount values($acount,2561,14543,'".AddSlashes(pg_result($resaco,$conresaco,'k113_valor'))."','$this->k113_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k113_data"]) || $this->k113_data != "")
           $resac = db_query("insert into db_acount values($acount,2561,14545,'".AddSlashes(pg_result($resaco,$conresaco,'k113_data'))."','$this->k113_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Movimentações dos Saldos Extras nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k113_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Movimentações dos Saldos Extras nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k113_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k113_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k113_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k113_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14541,'$k113_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2561,14541,'','".AddSlashes(pg_result($resaco,$iresaco,'k113_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2561,14542,'','".AddSlashes(pg_result($resaco,$iresaco,'k113_recurso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2561,14544,'','".AddSlashes(pg_result($resaco,$iresaco,'k113_conta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2561,14543,'','".AddSlashes(pg_result($resaco,$iresaco,'k113_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2561,14545,'','".AddSlashes(pg_result($resaco,$iresaco,'k113_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tabplansaldorecursomov
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k113_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k113_sequencial = $k113_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Movimentações dos Saldos Extras nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k113_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Movimentações dos Saldos Extras nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k113_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k113_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:tabplansaldorecursomov";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k113_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tabplansaldorecursomov ";
     $sql .= "      inner join tabplan  on  tabplan.k02_codigo = tabplansaldorecursomov.k113_conta";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = tabplansaldorecursomov.k113_recurso";
     $sql .= "      inner join conplanoexe  on  conplanoexe.c62_anousu = tabplan.k02_anousu and  conplanoexe.c62_reduz = tabplan.k02_reduz";
     $sql2 = "";
     if($dbwhere==""){
       if($k113_sequencial!=null ){
         $sql2 .= " where tabplansaldorecursomov.k113_sequencial = $k113_sequencial "; 
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
   function sql_query_file ( $k113_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tabplansaldorecursomov ";
     $sql2 = "";
     if($dbwhere==""){
       if($k113_sequencial!=null ){
         $sql2 .= " where tabplansaldorecursomov.k113_sequencial = $k113_sequencial "; 
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