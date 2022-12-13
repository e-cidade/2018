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

//MODULO: escola
//CLASSE DA ENTIDADE loteimpressaocartaoidentificacao
class cl_loteimpressaocartaoidentificacao { 
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
   var $ed305_sequencial = 0; 
   var $ed305_usuario = 0; 
   var $ed305_data_dia = null; 
   var $ed305_data_mes = null; 
   var $ed305_data_ano = null; 
   var $ed305_data = null; 
   var $ed305_hora = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed305_sequencial = int4 = Codigo sequencial 
                 ed305_usuario = int4 = Cod. Usuário 
                 ed305_data = date = Data 
                 ed305_hora = char(5) = Hora 
                 ";
   //funcao construtor da classe 
   function cl_loteimpressaocartaoidentificacao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("loteimpressaocartaoidentificacao"); 
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
       $this->ed305_sequencial = ($this->ed305_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed305_sequencial"]:$this->ed305_sequencial);
       $this->ed305_usuario = ($this->ed305_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["ed305_usuario"]:$this->ed305_usuario);
       if($this->ed305_data == ""){
         $this->ed305_data_dia = ($this->ed305_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed305_data_dia"]:$this->ed305_data_dia);
         $this->ed305_data_mes = ($this->ed305_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed305_data_mes"]:$this->ed305_data_mes);
         $this->ed305_data_ano = ($this->ed305_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed305_data_ano"]:$this->ed305_data_ano);
         if($this->ed305_data_dia != ""){
            $this->ed305_data = $this->ed305_data_ano."-".$this->ed305_data_mes."-".$this->ed305_data_dia;
         }
       }
       $this->ed305_hora = ($this->ed305_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["ed305_hora"]:$this->ed305_hora);
     }else{
       $this->ed305_sequencial = ($this->ed305_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed305_sequencial"]:$this->ed305_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ed305_sequencial){ 
      $this->atualizacampos();
     if($this->ed305_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "ed305_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed305_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "ed305_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed305_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "ed305_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed305_sequencial == "" || $ed305_sequencial == null ){
       $result = db_query("select nextval('loteimpressaocartaoidentificacao_ed305_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: loteimpressaocartaoidentificacao_ed305_sequencial_seq do campo: ed305_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed305_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from loteimpressaocartaoidentificacao_ed305_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed305_sequencial)){
         $this->erro_sql = " Campo ed305_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed305_sequencial = $ed305_sequencial; 
       }
     }
     if(($this->ed305_sequencial == null) || ($this->ed305_sequencial == "") ){ 
       $this->erro_sql = " Campo ed305_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into loteimpressaocartaoidentificacao(
                                       ed305_sequencial 
                                      ,ed305_usuario 
                                      ,ed305_data 
                                      ,ed305_hora 
                       )
                values (
                                $this->ed305_sequencial 
                               ,$this->ed305_usuario 
                               ,".($this->ed305_data == "null" || $this->ed305_data == ""?"null":"'".$this->ed305_data."'")." 
                               ,'$this->ed305_hora' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Lote de impressao dos cartões ($this->ed305_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Lote de impressao dos cartões já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Lote de impressao dos cartões ($this->ed305_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed305_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed305_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18860,'$this->ed305_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3344,18860,'','".AddSlashes(pg_result($resaco,0,'ed305_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3344,18861,'','".AddSlashes(pg_result($resaco,0,'ed305_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3344,18862,'','".AddSlashes(pg_result($resaco,0,'ed305_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3344,18863,'','".AddSlashes(pg_result($resaco,0,'ed305_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed305_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update loteimpressaocartaoidentificacao set ";
     $virgula = "";
     if(trim($this->ed305_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed305_sequencial"])){ 
       $sql  .= $virgula." ed305_sequencial = $this->ed305_sequencial ";
       $virgula = ",";
       if(trim($this->ed305_sequencial) == null ){ 
         $this->erro_sql = " Campo Codigo sequencial nao Informado.";
         $this->erro_campo = "ed305_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed305_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed305_usuario"])){ 
       $sql  .= $virgula." ed305_usuario = $this->ed305_usuario ";
       $virgula = ",";
       if(trim($this->ed305_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "ed305_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed305_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed305_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed305_data_dia"] !="") ){ 
       $sql  .= $virgula." ed305_data = '$this->ed305_data' ";
       $virgula = ",";
       if(trim($this->ed305_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "ed305_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed305_data_dia"])){ 
         $sql  .= $virgula." ed305_data = null ";
         $virgula = ",";
         if(trim($this->ed305_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "ed305_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed305_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed305_hora"])){ 
       $sql  .= $virgula." ed305_hora = '$this->ed305_hora' ";
       $virgula = ",";
       if(trim($this->ed305_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "ed305_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed305_sequencial!=null){
       $sql .= " ed305_sequencial = $this->ed305_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed305_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18860,'$this->ed305_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed305_sequencial"]) || $this->ed305_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3344,18860,'".AddSlashes(pg_result($resaco,$conresaco,'ed305_sequencial'))."','$this->ed305_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed305_usuario"]) || $this->ed305_usuario != "")
           $resac = db_query("insert into db_acount values($acount,3344,18861,'".AddSlashes(pg_result($resaco,$conresaco,'ed305_usuario'))."','$this->ed305_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed305_data"]) || $this->ed305_data != "")
           $resac = db_query("insert into db_acount values($acount,3344,18862,'".AddSlashes(pg_result($resaco,$conresaco,'ed305_data'))."','$this->ed305_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed305_hora"]) || $this->ed305_hora != "")
           $resac = db_query("insert into db_acount values($acount,3344,18863,'".AddSlashes(pg_result($resaco,$conresaco,'ed305_hora'))."','$this->ed305_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lote de impressao dos cartões nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed305_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lote de impressao dos cartões nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed305_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed305_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed305_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed305_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18860,'$ed305_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3344,18860,'','".AddSlashes(pg_result($resaco,$iresaco,'ed305_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3344,18861,'','".AddSlashes(pg_result($resaco,$iresaco,'ed305_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3344,18862,'','".AddSlashes(pg_result($resaco,$iresaco,'ed305_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3344,18863,'','".AddSlashes(pg_result($resaco,$iresaco,'ed305_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from loteimpressaocartaoidentificacao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed305_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed305_sequencial = $ed305_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lote de impressao dos cartões nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed305_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lote de impressao dos cartões nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed305_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed305_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:loteimpressaocartaoidentificacao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed305_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from loteimpressaocartaoidentificacao ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = loteimpressaocartaoidentificacao.ed305_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($ed305_sequencial!=null ){
         $sql2 .= " where loteimpressaocartaoidentificacao.ed305_sequencial = $ed305_sequencial "; 
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
   function sql_query_file ( $ed305_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from loteimpressaocartaoidentificacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed305_sequencial!=null ){
         $sql2 .= " where loteimpressaocartaoidentificacao.ed305_sequencial = $ed305_sequencial "; 
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
  
  function sql_query_lotes ( $ed305_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from loteimpressaocartaoidentificacao ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = loteimpressaocartaoidentificacao.ed305_usuario";
     $sql .= "      inner join loteimpressaocartaoidentificacaoaluno lic on lic.ed306_loteimpressaocartaoidentificacao = loteimpressaocartaoidentificacao.ed305_sequencial "; 
     $sql .= "      inner join aluno     on aluno.ed47_i_codigo    = lic.ed306_aluno  ";
     $sql .= "      inner join matricula on matricula.ed60_i_aluno = aluno.ed47_i_codigo ";
     $sql .= "      inner join turma     on turma.ed57_i_codigo    = matricula.ed60_i_turma ";
     $sql .= "      inner join escola    on escola.ed18_i_codigo   = turma.ed57_i_escola ";
     
     $sql2 = "";
     if($dbwhere==""){
       if($ed305_sequencial!=null ){
         $sql2 .= " where loteimpressaocartaoidentificacao.ed305_sequencial = $ed305_sequencial "; 
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