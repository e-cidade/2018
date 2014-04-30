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

//MODULO: Habitacao
//CLASSE DA ENTIDADE habitsituacaoinscricao
class cl_habitsituacaoinscricao { 
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
   var $ht13_sequencial = 0; 
   var $ht13_descricao = null; 
   var $ht13_obs = null; 
   var $ht13_datainicial_dia = null; 
   var $ht13_datainicial_mes = null; 
   var $ht13_datainicial_ano = null; 
   var $ht13_datainicial = null; 
   var $ht13_datafinal_dia = null; 
   var $ht13_datafinal_mes = null; 
   var $ht13_datafinal_ano = null; 
   var $ht13_datafinal = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ht13_sequencial = int4 = Sequencial 
                 ht13_descricao = varchar(50) = Descrição 
                 ht13_obs = text = Observação 
                 ht13_datainicial = date = Data Inicial 
                 ht13_datafinal = date = Data Final 
                 ";
   //funcao construtor da classe 
   function cl_habitsituacaoinscricao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("habitsituacaoinscricao"); 
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
       $this->ht13_sequencial = ($this->ht13_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ht13_sequencial"]:$this->ht13_sequencial);
       $this->ht13_descricao = ($this->ht13_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["ht13_descricao"]:$this->ht13_descricao);
       $this->ht13_obs = ($this->ht13_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["ht13_obs"]:$this->ht13_obs);
       if($this->ht13_datainicial == ""){
         $this->ht13_datainicial_dia = ($this->ht13_datainicial_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ht13_datainicial_dia"]:$this->ht13_datainicial_dia);
         $this->ht13_datainicial_mes = ($this->ht13_datainicial_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ht13_datainicial_mes"]:$this->ht13_datainicial_mes);
         $this->ht13_datainicial_ano = ($this->ht13_datainicial_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ht13_datainicial_ano"]:$this->ht13_datainicial_ano);
         if($this->ht13_datainicial_dia != ""){
            $this->ht13_datainicial = $this->ht13_datainicial_ano."-".$this->ht13_datainicial_mes."-".$this->ht13_datainicial_dia;
         }
       }
       if($this->ht13_datafinal == ""){
         $this->ht13_datafinal_dia = ($this->ht13_datafinal_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ht13_datafinal_dia"]:$this->ht13_datafinal_dia);
         $this->ht13_datafinal_mes = ($this->ht13_datafinal_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ht13_datafinal_mes"]:$this->ht13_datafinal_mes);
         $this->ht13_datafinal_ano = ($this->ht13_datafinal_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ht13_datafinal_ano"]:$this->ht13_datafinal_ano);
         if($this->ht13_datafinal_dia != ""){
            $this->ht13_datafinal = $this->ht13_datafinal_ano."-".$this->ht13_datafinal_mes."-".$this->ht13_datafinal_dia;
         }
       }
     }else{
       $this->ht13_sequencial = ($this->ht13_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ht13_sequencial"]:$this->ht13_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ht13_sequencial){ 
      $this->atualizacampos();
     if($this->ht13_descricao == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "ht13_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ht13_datainicial == null ){ 
       $this->ht13_datainicial = "null";
     }
     if($this->ht13_datafinal == null ){ 
       $this->ht13_datafinal = "null";
     }
     if($ht13_sequencial == "" || $ht13_sequencial == null ){
       $result = db_query("select nextval('habitsituacaoinscricao_ht13_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: habitsituacaoinscricao_ht13_sequencial_seq do campo: ht13_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ht13_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from habitsituacaoinscricao_ht13_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ht13_sequencial)){
         $this->erro_sql = " Campo ht13_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ht13_sequencial = $ht13_sequencial; 
       }
     }
     if(($this->ht13_sequencial == null) || ($this->ht13_sequencial == "") ){ 
       $this->erro_sql = " Campo ht13_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into habitsituacaoinscricao(
                                       ht13_sequencial 
                                      ,ht13_descricao 
                                      ,ht13_obs 
                                      ,ht13_datainicial 
                                      ,ht13_datafinal 
                       )
                values (
                                $this->ht13_sequencial 
                               ,'$this->ht13_descricao' 
                               ,'$this->ht13_obs' 
                               ,".($this->ht13_datainicial == "null" || $this->ht13_datainicial == ""?"null":"'".$this->ht13_datainicial."'")." 
                               ,".($this->ht13_datafinal == "null" || $this->ht13_datafinal == ""?"null":"'".$this->ht13_datafinal."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Situação da Inscrição da Habitação ($this->ht13_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Situação da Inscrição da Habitação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Situação da Inscrição da Habitação ($this->ht13_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ht13_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ht13_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16994,'$this->ht13_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3001,16994,'','".AddSlashes(pg_result($resaco,0,'ht13_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3001,16995,'','".AddSlashes(pg_result($resaco,0,'ht13_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3001,16996,'','".AddSlashes(pg_result($resaco,0,'ht13_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3001,16997,'','".AddSlashes(pg_result($resaco,0,'ht13_datainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3001,16998,'','".AddSlashes(pg_result($resaco,0,'ht13_datafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ht13_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update habitsituacaoinscricao set ";
     $virgula = "";
     if(trim($this->ht13_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht13_sequencial"])){ 
       $sql  .= $virgula." ht13_sequencial = $this->ht13_sequencial ";
       $virgula = ",";
       if(trim($this->ht13_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ht13_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht13_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht13_descricao"])){ 
       $sql  .= $virgula." ht13_descricao = '$this->ht13_descricao' ";
       $virgula = ",";
       if(trim($this->ht13_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "ht13_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht13_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht13_obs"])){ 
       $sql  .= $virgula." ht13_obs = '$this->ht13_obs' ";
       $virgula = ",";
     }
     if(trim($this->ht13_datainicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht13_datainicial_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ht13_datainicial_dia"] !="") ){ 
       $sql  .= $virgula." ht13_datainicial = '$this->ht13_datainicial' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ht13_datainicial_dia"])){ 
         $sql  .= $virgula." ht13_datainicial = null ";
         $virgula = ",";
       }
     }
     if(trim($this->ht13_datafinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht13_datafinal_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ht13_datafinal_dia"] !="") ){ 
       $sql  .= $virgula." ht13_datafinal = '$this->ht13_datafinal' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ht13_datafinal_dia"])){ 
         $sql  .= $virgula." ht13_datafinal = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($ht13_sequencial!=null){
       $sql .= " ht13_sequencial = $this->ht13_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ht13_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16994,'$this->ht13_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht13_sequencial"]) || $this->ht13_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3001,16994,'".AddSlashes(pg_result($resaco,$conresaco,'ht13_sequencial'))."','$this->ht13_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht13_descricao"]) || $this->ht13_descricao != "")
           $resac = db_query("insert into db_acount values($acount,3001,16995,'".AddSlashes(pg_result($resaco,$conresaco,'ht13_descricao'))."','$this->ht13_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht13_obs"]) || $this->ht13_obs != "")
           $resac = db_query("insert into db_acount values($acount,3001,16996,'".AddSlashes(pg_result($resaco,$conresaco,'ht13_obs'))."','$this->ht13_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht13_datainicial"]) || $this->ht13_datainicial != "")
           $resac = db_query("insert into db_acount values($acount,3001,16997,'".AddSlashes(pg_result($resaco,$conresaco,'ht13_datainicial'))."','$this->ht13_datainicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht13_datafinal"]) || $this->ht13_datafinal != "")
           $resac = db_query("insert into db_acount values($acount,3001,16998,'".AddSlashes(pg_result($resaco,$conresaco,'ht13_datafinal'))."','$this->ht13_datafinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Situação da Inscrição da Habitação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ht13_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Situação da Inscrição da Habitação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ht13_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ht13_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ht13_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ht13_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16994,'$ht13_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3001,16994,'','".AddSlashes(pg_result($resaco,$iresaco,'ht13_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3001,16995,'','".AddSlashes(pg_result($resaco,$iresaco,'ht13_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3001,16996,'','".AddSlashes(pg_result($resaco,$iresaco,'ht13_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3001,16997,'','".AddSlashes(pg_result($resaco,$iresaco,'ht13_datainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3001,16998,'','".AddSlashes(pg_result($resaco,$iresaco,'ht13_datafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from habitsituacaoinscricao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ht13_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ht13_sequencial = $ht13_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Situação da Inscrição da Habitação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ht13_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Situação da Inscrição da Habitação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ht13_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ht13_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:habitsituacaoinscricao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ht13_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from habitsituacaoinscricao ";
     $sql2 = "";
     if($dbwhere==""){
       if($ht13_sequencial!=null ){
         $sql2 .= " where habitsituacaoinscricao.ht13_sequencial = $ht13_sequencial "; 
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
   function sql_query_file ( $ht13_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from habitsituacaoinscricao ";
     $sql2 = "";
     if($dbwhere==""){
       if($ht13_sequencial!=null ){
         $sql2 .= " where habitsituacaoinscricao.ht13_sequencial = $ht13_sequencial "; 
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