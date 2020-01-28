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

//MODULO: recursoshumanos
//CLASSE DA ENTIDADE rhestagioagendadata
class cl_rhestagioagendadata { 
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
   var $h64_sequencial = 0; 
   var $h64_estagioagenda = 0; 
   var $h64_data_dia = null; 
   var $h64_data_mes = null; 
   var $h64_data_ano = null; 
   var $h64_data = null; 
   var $h64_seqaval = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 h64_sequencial = int4 = Cod. Sequencial 
                 h64_estagioagenda = int4 = Código da Agenda 
                 h64_data = date = Data da Avaliação 
                 h64_seqaval = int4 = Número da Avaliação 
                 ";
   //funcao construtor da classe 
   function cl_rhestagioagendadata() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhestagioagendadata"); 
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
       $this->h64_sequencial = ($this->h64_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["h64_sequencial"]:$this->h64_sequencial);
       $this->h64_estagioagenda = ($this->h64_estagioagenda == ""?@$GLOBALS["HTTP_POST_VARS"]["h64_estagioagenda"]:$this->h64_estagioagenda);
       if($this->h64_data == ""){
         $this->h64_data_dia = ($this->h64_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["h64_data_dia"]:$this->h64_data_dia);
         $this->h64_data_mes = ($this->h64_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["h64_data_mes"]:$this->h64_data_mes);
         $this->h64_data_ano = ($this->h64_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["h64_data_ano"]:$this->h64_data_ano);
         if($this->h64_data_dia != ""){
            $this->h64_data = $this->h64_data_ano."-".$this->h64_data_mes."-".$this->h64_data_dia;
         }
       }
       $this->h64_seqaval = ($this->h64_seqaval == ""?@$GLOBALS["HTTP_POST_VARS"]["h64_seqaval"]:$this->h64_seqaval);
     }else{
       $this->h64_sequencial = ($this->h64_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["h64_sequencial"]:$this->h64_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($h64_sequencial){ 
      $this->atualizacampos();
     if($this->h64_estagioagenda == null ){ 
       $this->erro_sql = " Campo Código da Agenda nao Informado.";
       $this->erro_campo = "h64_estagioagenda";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h64_data == null ){ 
       $this->erro_sql = " Campo Data da Avaliação nao Informado.";
       $this->erro_campo = "h64_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h64_seqaval == null ){ 
       $this->h64_seqaval = "0";
     }
     if($h64_sequencial == "" || $h64_sequencial == null ){
       $result = db_query("select nextval('rhestagioagendadata_h64_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhestagioagendadata_h64_sequencial_seq do campo: h64_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->h64_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhestagioagendadata_h64_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $h64_sequencial)){
         $this->erro_sql = " Campo h64_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->h64_sequencial = $h64_sequencial; 
       }
     }
     if(($this->h64_sequencial == null) || ($this->h64_sequencial == "") ){ 
       $this->erro_sql = " Campo h64_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhestagioagendadata(
                                       h64_sequencial 
                                      ,h64_estagioagenda 
                                      ,h64_data 
                                      ,h64_seqaval 
                       )
                values (
                                $this->h64_sequencial 
                               ,$this->h64_estagioagenda 
                               ,".($this->h64_data == "null" || $this->h64_data == ""?"null":"'".$this->h64_data."'")." 
                               ,$this->h64_seqaval 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Datas da Agenda ($this->h64_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Datas da Agenda já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Datas da Agenda ($this->h64_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h64_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->h64_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10928,'$this->h64_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1887,10928,'','".AddSlashes(pg_result($resaco,0,'h64_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1887,10929,'','".AddSlashes(pg_result($resaco,0,'h64_estagioagenda'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1887,10930,'','".AddSlashes(pg_result($resaco,0,'h64_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1887,10966,'','".AddSlashes(pg_result($resaco,0,'h64_seqaval'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($h64_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhestagioagendadata set ";
     $virgula = "";
     if(trim($this->h64_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h64_sequencial"])){ 
       $sql  .= $virgula." h64_sequencial = $this->h64_sequencial ";
       $virgula = ",";
       if(trim($this->h64_sequencial) == null ){ 
         $this->erro_sql = " Campo Cod. Sequencial nao Informado.";
         $this->erro_campo = "h64_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h64_estagioagenda)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h64_estagioagenda"])){ 
       $sql  .= $virgula." h64_estagioagenda = $this->h64_estagioagenda ";
       $virgula = ",";
       if(trim($this->h64_estagioagenda) == null ){ 
         $this->erro_sql = " Campo Código da Agenda nao Informado.";
         $this->erro_campo = "h64_estagioagenda";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h64_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h64_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["h64_data_dia"] !="") ){ 
       $sql  .= $virgula." h64_data = '$this->h64_data' ";
       $virgula = ",";
       if(trim($this->h64_data) == null ){ 
         $this->erro_sql = " Campo Data da Avaliação nao Informado.";
         $this->erro_campo = "h64_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["h64_data_dia"])){ 
         $sql  .= $virgula." h64_data = null ";
         $virgula = ",";
         if(trim($this->h64_data) == null ){ 
           $this->erro_sql = " Campo Data da Avaliação nao Informado.";
           $this->erro_campo = "h64_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->h64_seqaval)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h64_seqaval"])){ 
        if(trim($this->h64_seqaval)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h64_seqaval"])){ 
           $this->h64_seqaval = "0" ; 
        } 
       $sql  .= $virgula." h64_seqaval = $this->h64_seqaval ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($h64_sequencial!=null){
       $sql .= " h64_sequencial = $this->h64_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->h64_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10928,'$this->h64_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h64_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1887,10928,'".AddSlashes(pg_result($resaco,$conresaco,'h64_sequencial'))."','$this->h64_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h64_estagioagenda"]))
           $resac = db_query("insert into db_acount values($acount,1887,10929,'".AddSlashes(pg_result($resaco,$conresaco,'h64_estagioagenda'))."','$this->h64_estagioagenda',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h64_data"]))
           $resac = db_query("insert into db_acount values($acount,1887,10930,'".AddSlashes(pg_result($resaco,$conresaco,'h64_data'))."','$this->h64_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h64_seqaval"]))
           $resac = db_query("insert into db_acount values($acount,1887,10966,'".AddSlashes(pg_result($resaco,$conresaco,'h64_seqaval'))."','$this->h64_seqaval',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Datas da Agenda nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->h64_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Datas da Agenda nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->h64_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h64_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($h64_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($h64_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10928,'$h64_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1887,10928,'','".AddSlashes(pg_result($resaco,$iresaco,'h64_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1887,10929,'','".AddSlashes(pg_result($resaco,$iresaco,'h64_estagioagenda'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1887,10930,'','".AddSlashes(pg_result($resaco,$iresaco,'h64_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1887,10966,'','".AddSlashes(pg_result($resaco,$iresaco,'h64_seqaval'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhestagioagendadata
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($h64_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " h64_sequencial = $h64_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Datas da Agenda nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$h64_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Datas da Agenda nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$h64_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$h64_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhestagioagendadata";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $h64_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhestagioagendadata ";
     $sql .= "      inner join rhestagioagenda  on  rhestagioagenda.h57_sequencial = rhestagioagendadata.h64_estagioagenda";
     $sql .= "      inner join db_config  on  db_config.codigo = rhestagioagenda.h57_instit";
     $sql .= "      inner join rhpessoal  on  rhpessoal.rh01_regist = rhestagioagenda.h57_regist";
     $sql .= "      inner join rhestagio  on  rhestagio.h50_sequencial = rhestagioagenda.h57_rhestagio";
     $sql2 = "";
     if($dbwhere==""){
       if($h64_sequencial!=null ){
         $sql2 .= " where rhestagioagendadata.h64_sequencial = $h64_sequencial "; 
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
   function sql_query_file ( $h64_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhestagioagendadata ";
     $sql2 = "";
     if($dbwhere==""){
       if($h64_sequencial!=null ){
         $sql2 .= " where rhestagioagendadata.h64_sequencial = $h64_sequencial "; 
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
   function sql_query_nome( $h64_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhestagioagendadata ";
     $sql .= "      inner join rhestagioagenda          on h57_sequencial           = rhestagioagendadata.h64_estagioagenda";
     $sql .= "      inner join rhpessoal                on rhpessoal.rh01_regist    = rhestagioagenda.h57_regist";
     $sql .= "      inner join rhfuncao                 on rhpessoal.rh01_funcao    = rhfuncao.rh37_funcao";
     $sql .= "                                         and rhpessoal.rh01_instit    = rhfuncao.rh37_instit";
     $sql .= "      inner join cgm                      on rhpessoal.rh01_numcgm    = cgm.z01_numcgm";
     $sql .= "      inner join rhestagio                on rhestagio.h50_sequencial = rhestagioagenda.h57_rhestagio";
     $sql .= "      left  join rhestagioavaliacao       on h64_sequencial           = h56_rhestagioagenda";
     $sql .= "      left  join rhpessoal registaval     on registaval.rh01_regist   = h56_avaliador";
     $sql .= "      left  join cgm   nomeavaliador      on registaval.rh01_numcgm   = nomeavaliador.z01_numcgm";
     $sql .= "      left  join rhestagiocomissao        on h56_rhestagiocomissao    = h59_sequencial";
     $sql2 = "";
     if($dbwhere==""){
       if($h64_sequencial!=null ){
         $sql2 .= " where rhestagioagendadata.h64_sequencial = $h64_sequencial "; 
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