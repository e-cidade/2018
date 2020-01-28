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
//CLASSE DA ENTIDADE informacaodebito
class cl_informacaodebito { 
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
   var $k163_sequencial = 0; 
   var $k163_numpre = 0; 
   var $k163_data_dia = null; 
   var $k163_data_mes = null; 
   var $k163_data_ano = null; 
   var $k163_data = null; 
   var $k163_numpar = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k163_sequencial = int4 = Sequencial 
                 k163_numpre = int4 = Numpre 
                 k163_data = date = Data da Geração 
                 k163_numpar = int4 = Parcela 
                 ";
   //funcao construtor da classe 
   function cl_informacaodebito() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("informacaodebito"); 
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
       $this->k163_sequencial = ($this->k163_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k163_sequencial"]:$this->k163_sequencial);
       $this->k163_numpre = ($this->k163_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["k163_numpre"]:$this->k163_numpre);
       if($this->k163_data == ""){
         $this->k163_data_dia = ($this->k163_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k163_data_dia"]:$this->k163_data_dia);
         $this->k163_data_mes = ($this->k163_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k163_data_mes"]:$this->k163_data_mes);
         $this->k163_data_ano = ($this->k163_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k163_data_ano"]:$this->k163_data_ano);
         if($this->k163_data_dia != ""){
            $this->k163_data = $this->k163_data_ano."-".$this->k163_data_mes."-".$this->k163_data_dia;
         }
       }
       $this->k163_numpar = ($this->k163_numpar == ""?@$GLOBALS["HTTP_POST_VARS"]["k163_numpar"]:$this->k163_numpar);
     }else{
       $this->k163_sequencial = ($this->k163_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k163_sequencial"]:$this->k163_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k163_sequencial){ 
      $this->atualizacampos();
     if($this->k163_numpre == null ){ 
       $this->erro_sql = " Campo Numpre nao Informado.";
       $this->erro_campo = "k163_numpre";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k163_data == null ){ 
       $this->erro_sql = " Campo Data da Geração nao Informado.";
       $this->erro_campo = "k163_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k163_numpar == null ){ 
       $this->erro_sql = " Campo Parcela nao Informado.";
       $this->erro_campo = "k163_numpar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k163_sequencial == "" || $k163_sequencial == null ){
       $result = db_query("select nextval('informacaodebito_k163_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: informacaodebito_k163_sequencial_seq do campo: k163_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k163_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from informacaodebito_k163_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k163_sequencial)){
         $this->erro_sql = " Campo k163_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k163_sequencial = $k163_sequencial; 
       }
     }
     if(($this->k163_sequencial == null) || ($this->k163_sequencial == "") ){ 
       $this->erro_sql = " Campo k163_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into informacaodebito(
                                       k163_sequencial 
                                      ,k163_numpre 
                                      ,k163_data 
                                      ,k163_numpar 
                       )
                values (
                                $this->k163_sequencial 
                               ,$this->k163_numpre 
                               ,".($this->k163_data == "null" || $this->k163_data == ""?"null":"'".$this->k163_data."'")." 
                               ,$this->k163_numpar 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Informações do Débito ($this->k163_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Informações do Débito já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Informações do Débito ($this->k163_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k163_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k163_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,19745,'$this->k163_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3539,19745,'','".AddSlashes(pg_result($resaco,0,'k163_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3539,19746,'','".AddSlashes(pg_result($resaco,0,'k163_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3539,19747,'','".AddSlashes(pg_result($resaco,0,'k163_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3539,19776,'','".AddSlashes(pg_result($resaco,0,'k163_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k163_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update informacaodebito set ";
     $virgula = "";
     if(trim($this->k163_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k163_sequencial"])){ 
       $sql  .= $virgula." k163_sequencial = $this->k163_sequencial ";
       $virgula = ",";
       if(trim($this->k163_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "k163_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k163_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k163_numpre"])){ 
       $sql  .= $virgula." k163_numpre = $this->k163_numpre ";
       $virgula = ",";
       if(trim($this->k163_numpre) == null ){ 
         $this->erro_sql = " Campo Numpre nao Informado.";
         $this->erro_campo = "k163_numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k163_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k163_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k163_data_dia"] !="") ){ 
       $sql  .= $virgula." k163_data = '$this->k163_data' ";
       $virgula = ",";
       if(trim($this->k163_data) == null ){ 
         $this->erro_sql = " Campo Data da Geração nao Informado.";
         $this->erro_campo = "k163_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k163_data_dia"])){ 
         $sql  .= $virgula." k163_data = null ";
         $virgula = ",";
         if(trim($this->k163_data) == null ){ 
           $this->erro_sql = " Campo Data da Geração nao Informado.";
           $this->erro_campo = "k163_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k163_numpar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k163_numpar"])){ 
       $sql  .= $virgula." k163_numpar = $this->k163_numpar ";
       $virgula = ",";
       if(trim($this->k163_numpar) == null ){ 
         $this->erro_sql = " Campo Parcela nao Informado.";
         $this->erro_campo = "k163_numpar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k163_sequencial!=null){
       $sql .= " k163_sequencial = $this->k163_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k163_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19745,'$this->k163_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k163_sequencial"]) || $this->k163_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3539,19745,'".AddSlashes(pg_result($resaco,$conresaco,'k163_sequencial'))."','$this->k163_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k163_numpre"]) || $this->k163_numpre != "")
           $resac = db_query("insert into db_acount values($acount,3539,19746,'".AddSlashes(pg_result($resaco,$conresaco,'k163_numpre'))."','$this->k163_numpre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k163_data"]) || $this->k163_data != "")
           $resac = db_query("insert into db_acount values($acount,3539,19747,'".AddSlashes(pg_result($resaco,$conresaco,'k163_data'))."','$this->k163_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k163_numpar"]) || $this->k163_numpar != "")
           $resac = db_query("insert into db_acount values($acount,3539,19776,'".AddSlashes(pg_result($resaco,$conresaco,'k163_numpar'))."','$this->k163_numpar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Informações do Débito nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k163_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Informações do Débito nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k163_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k163_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k163_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k163_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19745,'$k163_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3539,19745,'','".AddSlashes(pg_result($resaco,$iresaco,'k163_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3539,19746,'','".AddSlashes(pg_result($resaco,$iresaco,'k163_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3539,19747,'','".AddSlashes(pg_result($resaco,$iresaco,'k163_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3539,19776,'','".AddSlashes(pg_result($resaco,$iresaco,'k163_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from informacaodebito
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k163_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k163_sequencial = $k163_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Informações do Débito nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k163_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Informações do Débito nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k163_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k163_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:informacaodebito";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k163_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from informacaodebito ";
     $sql2 = "";
     if($dbwhere==""){
       if($k163_sequencial!=null ){
         $sql2 .= " where informacaodebito.k163_sequencial = $k163_sequencial "; 
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
   function sql_query_file ( $k163_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from informacaodebito ";
     $sql2 = "";
     if($dbwhere==""){
       if($k163_sequencial!=null ){
         $sql2 .= " where informacaodebito.k163_sequencial = $k163_sequencial "; 
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
   * Retorna uma String SQL fazendo inner join entre informacaodebito, divida e divold através do numpre e numpar da divold
   * @param string $sCampos
   * @param integer $iNumpreOrigem
   * @param integer $iNumparOrigem
   * @return string
   */
  function sql_query_retorna_dados_origem ($sCampos = '*', $iNumpreOrigem, $iNumparOrigem) {
    
    $sSql  = " select {$sCampos}                                                                       ";
    $sSql .= "   from divida                                                                           ";
    $sSql .= "        inner join divold            on divida.v01_coddiv = divold.k10_coddiv            ";
    $sSql .= "        inner join informacaodebito  on divold.k10_numpre = informacaodebito.k163_numpre ";
    $sSql .= "                                    and divold.k10_numpar = informacaodebito.k163_numpar ";
    $sSql .= "  where divida.v01_numpre = {$iNumpreOrigem}                                             ";
    $sSql .= "    and divida.v01_numpar = {$iNumparOrigem}                                             ";
    return $sSql;
  }
}
?>