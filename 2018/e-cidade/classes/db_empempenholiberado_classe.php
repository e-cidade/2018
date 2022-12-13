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
//CLASSE DA ENTIDADE empempenholiberado
class cl_empempenholiberado { 
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
   var $e22_sequencial = 0; 
   var $e22_numemp = 0; 
   var $e22_id_usuario = 0; 
   var $e22_data_dia = null; 
   var $e22_data_mes = null; 
   var $e22_data_ano = null; 
   var $e22_data = null; 
   var $e22_hora = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 e22_sequencial = int4 = Sequencial 
                 e22_numemp = int4 = Numero Empenho 
                 e22_id_usuario = int4 = Cod Usuario 
                 e22_data = date = Data 
                 e22_hora = char(5) = Hora 
                 ";
   //funcao construtor da classe 
   function cl_empempenholiberado() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("empempenholiberado"); 
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
       $this->e22_sequencial = ($this->e22_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e22_sequencial"]:$this->e22_sequencial);
       $this->e22_numemp = ($this->e22_numemp == ""?@$GLOBALS["HTTP_POST_VARS"]["e22_numemp"]:$this->e22_numemp);
       $this->e22_id_usuario = ($this->e22_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["e22_id_usuario"]:$this->e22_id_usuario);
       if($this->e22_data == ""){
         $this->e22_data_dia = ($this->e22_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["e22_data_dia"]:$this->e22_data_dia);
         $this->e22_data_mes = ($this->e22_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["e22_data_mes"]:$this->e22_data_mes);
         $this->e22_data_ano = ($this->e22_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["e22_data_ano"]:$this->e22_data_ano);
         if($this->e22_data_dia != ""){
            $this->e22_data = $this->e22_data_ano."-".$this->e22_data_mes."-".$this->e22_data_dia;
         }
       }
       $this->e22_hora = ($this->e22_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["e22_hora"]:$this->e22_hora);
     }else{
       $this->e22_sequencial = ($this->e22_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e22_sequencial"]:$this->e22_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($e22_sequencial){ 
      $this->atualizacampos();
     if($this->e22_numemp == null ){ 
       $this->erro_sql = " Campo Numero Empenho nao Informado.";
       $this->erro_campo = "e22_numemp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e22_id_usuario == null ){ 
       $this->erro_sql = " Campo Cod Usuario nao Informado.";
       $this->erro_campo = "e22_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e22_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "e22_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e22_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "e22_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($e22_sequencial == "" || $e22_sequencial == null ){
       $result = db_query("select nextval('empempenholiberado_e22_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: empempenholiberado_e22_sequencial_seq do campo: e22_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->e22_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from empempenholiberado_e22_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $e22_sequencial)){
         $this->erro_sql = " Campo e22_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->e22_sequencial = $e22_sequencial; 
       }
     }
     if(($this->e22_sequencial == null) || ($this->e22_sequencial == "") ){ 
       $this->erro_sql = " Campo e22_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into empempenholiberado(
                                       e22_sequencial 
                                      ,e22_numemp 
                                      ,e22_id_usuario 
                                      ,e22_data 
                                      ,e22_hora 
                       )
                values (
                                $this->e22_sequencial 
                               ,$this->e22_numemp 
                               ,$this->e22_id_usuario 
                               ,".($this->e22_data == "null" || $this->e22_data == ""?"null":"'".$this->e22_data."'")." 
                               ,'$this->e22_hora' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Empenho liberado ($this->e22_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Empenho liberado já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Empenho liberado ($this->e22_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e22_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->e22_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15315,'$this->e22_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2696,15315,'','".AddSlashes(pg_result($resaco,0,'e22_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2696,15316,'','".AddSlashes(pg_result($resaco,0,'e22_numemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2696,15317,'','".AddSlashes(pg_result($resaco,0,'e22_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2696,15318,'','".AddSlashes(pg_result($resaco,0,'e22_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2696,15319,'','".AddSlashes(pg_result($resaco,0,'e22_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($e22_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update empempenholiberado set ";
     $virgula = "";
     if(trim($this->e22_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e22_sequencial"])){ 
       $sql  .= $virgula." e22_sequencial = $this->e22_sequencial ";
       $virgula = ",";
       if(trim($this->e22_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "e22_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e22_numemp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e22_numemp"])){ 
       $sql  .= $virgula." e22_numemp = $this->e22_numemp ";
       $virgula = ",";
       if(trim($this->e22_numemp) == null ){ 
         $this->erro_sql = " Campo Numero Empenho nao Informado.";
         $this->erro_campo = "e22_numemp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e22_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e22_id_usuario"])){ 
       $sql  .= $virgula." e22_id_usuario = $this->e22_id_usuario ";
       $virgula = ",";
       if(trim($this->e22_id_usuario) == null ){ 
         $this->erro_sql = " Campo Cod Usuario nao Informado.";
         $this->erro_campo = "e22_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e22_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e22_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["e22_data_dia"] !="") ){ 
       $sql  .= $virgula." e22_data = '$this->e22_data' ";
       $virgula = ",";
       if(trim($this->e22_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "e22_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["e22_data_dia"])){ 
         $sql  .= $virgula." e22_data = null ";
         $virgula = ",";
         if(trim($this->e22_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "e22_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->e22_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e22_hora"])){ 
       $sql  .= $virgula." e22_hora = '$this->e22_hora' ";
       $virgula = ",";
       if(trim($this->e22_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "e22_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($e22_sequencial!=null){
       $sql .= " e22_sequencial = $this->e22_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->e22_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15315,'$this->e22_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e22_sequencial"]) || $this->e22_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2696,15315,'".AddSlashes(pg_result($resaco,$conresaco,'e22_sequencial'))."','$this->e22_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e22_numemp"]) || $this->e22_numemp != "")
           $resac = db_query("insert into db_acount values($acount,2696,15316,'".AddSlashes(pg_result($resaco,$conresaco,'e22_numemp'))."','$this->e22_numemp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e22_id_usuario"]) || $this->e22_id_usuario != "")
           $resac = db_query("insert into db_acount values($acount,2696,15317,'".AddSlashes(pg_result($resaco,$conresaco,'e22_id_usuario'))."','$this->e22_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e22_data"]) || $this->e22_data != "")
           $resac = db_query("insert into db_acount values($acount,2696,15318,'".AddSlashes(pg_result($resaco,$conresaco,'e22_data'))."','$this->e22_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e22_hora"]) || $this->e22_hora != "")
           $resac = db_query("insert into db_acount values($acount,2696,15319,'".AddSlashes(pg_result($resaco,$conresaco,'e22_hora'))."','$this->e22_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Empenho liberado nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e22_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Empenho liberado nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e22_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e22_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($e22_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($e22_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15315,'$e22_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2696,15315,'','".AddSlashes(pg_result($resaco,$iresaco,'e22_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2696,15316,'','".AddSlashes(pg_result($resaco,$iresaco,'e22_numemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2696,15317,'','".AddSlashes(pg_result($resaco,$iresaco,'e22_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2696,15318,'','".AddSlashes(pg_result($resaco,$iresaco,'e22_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2696,15319,'','".AddSlashes(pg_result($resaco,$iresaco,'e22_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from empempenholiberado
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($e22_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e22_sequencial = $e22_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Empenho liberado nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e22_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Empenho liberado nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e22_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e22_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:empempenholiberado";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $e22_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empempenholiberado ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = empempenholiberado.e22_id_usuario";
     $sql .= "      inner join empempenho  on  empempenho.e60_numemp = empempenholiberado.e22_numemp";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = empempenho.e60_instit";
     $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot = empempenho.e60_coddot";
     $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = empempenho.e60_codcom";
     $sql .= "      inner join emptipo  on  emptipo.e41_codtipo = empempenho.e60_codtipo";
     $sql .= "      inner join concarpeculiar  on  concarpeculiar.c58_sequencial = empempenho.e60_concarpeculiar";
     $sql .= "      inner join empelemento  on  e64_numemp = e60_numemp ";
     $sql2 = "";
     if($dbwhere==""){
       if($e22_sequencial!=null ){
         $sql2 .= " where empempenholiberado.e22_sequencial = $e22_sequencial "; 
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
   function sql_query_file ( $e22_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empempenholiberado ";
     $sql2 = "";
     if($dbwhere==""){
       if($e22_sequencial!=null ){
         $sql2 .= " where empempenholiberado.e22_sequencial = $e22_sequencial "; 
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