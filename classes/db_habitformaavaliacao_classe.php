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
//CLASSE DA ENTIDADE habitformaavaliacao
class cl_habitformaavaliacao { 
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
   var $ht07_sequencial = 0; 
   var $ht07_descricao = null; 
   var $ht07_obs = null; 
   var $ht07_datainicial_dia = null; 
   var $ht07_datainicial_mes = null; 
   var $ht07_datainicial_ano = null; 
   var $ht07_datainicial = null; 
   var $ht07_datafinal_dia = null; 
   var $ht07_datafinal_mes = null; 
   var $ht07_datafinal_ano = null; 
   var $ht07_datafinal = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ht07_sequencial = int4 = Sequencial 
                 ht07_descricao = varchar(50) = Descrição 
                 ht07_obs = text = Observação 
                 ht07_datainicial = date = Data Inicial 
                 ht07_datafinal = date = Data Final 
                 ";
   //funcao construtor da classe 
   function cl_habitformaavaliacao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("habitformaavaliacao"); 
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
       $this->ht07_sequencial = ($this->ht07_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ht07_sequencial"]:$this->ht07_sequencial);
       $this->ht07_descricao = ($this->ht07_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["ht07_descricao"]:$this->ht07_descricao);
       $this->ht07_obs = ($this->ht07_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["ht07_obs"]:$this->ht07_obs);
       if($this->ht07_datainicial == ""){
         $this->ht07_datainicial_dia = ($this->ht07_datainicial_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ht07_datainicial_dia"]:$this->ht07_datainicial_dia);
         $this->ht07_datainicial_mes = ($this->ht07_datainicial_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ht07_datainicial_mes"]:$this->ht07_datainicial_mes);
         $this->ht07_datainicial_ano = ($this->ht07_datainicial_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ht07_datainicial_ano"]:$this->ht07_datainicial_ano);
         if($this->ht07_datainicial_dia != ""){
            $this->ht07_datainicial = $this->ht07_datainicial_ano."-".$this->ht07_datainicial_mes."-".$this->ht07_datainicial_dia;
         }
       }
       if($this->ht07_datafinal == ""){
         $this->ht07_datafinal_dia = ($this->ht07_datafinal_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ht07_datafinal_dia"]:$this->ht07_datafinal_dia);
         $this->ht07_datafinal_mes = ($this->ht07_datafinal_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ht07_datafinal_mes"]:$this->ht07_datafinal_mes);
         $this->ht07_datafinal_ano = ($this->ht07_datafinal_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ht07_datafinal_ano"]:$this->ht07_datafinal_ano);
         if($this->ht07_datafinal_dia != ""){
            $this->ht07_datafinal = $this->ht07_datafinal_ano."-".$this->ht07_datafinal_mes."-".$this->ht07_datafinal_dia;
         }
       }
     }else{
       $this->ht07_sequencial = ($this->ht07_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ht07_sequencial"]:$this->ht07_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ht07_sequencial){ 
      $this->atualizacampos();
     if($this->ht07_descricao == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "ht07_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ht07_datainicial == null ){ 
       $this->ht07_datainicial = "null";
     }
     if($this->ht07_datafinal == null ){ 
       $this->ht07_datafinal = "null";
     }
     if($ht07_sequencial == "" || $ht07_sequencial == null ){
       $result = db_query("select nextval('habitformaavaliacao_ht07_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: habitformaavaliacao_ht07_sequencial_seq do campo: ht07_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ht07_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from habitformaavaliacao_ht07_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ht07_sequencial)){
         $this->erro_sql = " Campo ht07_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ht07_sequencial = $ht07_sequencial; 
       }
     }
     if(($this->ht07_sequencial == null) || ($this->ht07_sequencial == "") ){ 
       $this->erro_sql = " Campo ht07_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into habitformaavaliacao(
                                       ht07_sequencial 
                                      ,ht07_descricao 
                                      ,ht07_obs 
                                      ,ht07_datainicial 
                                      ,ht07_datafinal 
                       )
                values (
                                $this->ht07_sequencial 
                               ,'$this->ht07_descricao' 
                               ,'$this->ht07_obs' 
                               ,".($this->ht07_datainicial == "null" || $this->ht07_datainicial == ""?"null":"'".$this->ht07_datainicial."'")." 
                               ,".($this->ht07_datafinal == "null" || $this->ht07_datafinal == ""?"null":"'".$this->ht07_datafinal."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Forma de Avaliação da Habitação ($this->ht07_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Forma de Avaliação da Habitação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Forma de Avaliação da Habitação ($this->ht07_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ht07_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ht07_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16971,'$this->ht07_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2995,16971,'','".AddSlashes(pg_result($resaco,0,'ht07_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2995,16972,'','".AddSlashes(pg_result($resaco,0,'ht07_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2995,16973,'','".AddSlashes(pg_result($resaco,0,'ht07_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2995,16974,'','".AddSlashes(pg_result($resaco,0,'ht07_datainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2995,16975,'','".AddSlashes(pg_result($resaco,0,'ht07_datafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ht07_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update habitformaavaliacao set ";
     $virgula = "";
     if(trim($this->ht07_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht07_sequencial"])){ 
       $sql  .= $virgula." ht07_sequencial = $this->ht07_sequencial ";
       $virgula = ",";
       if(trim($this->ht07_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ht07_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht07_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht07_descricao"])){ 
       $sql  .= $virgula." ht07_descricao = '$this->ht07_descricao' ";
       $virgula = ",";
       if(trim($this->ht07_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "ht07_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht07_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht07_obs"])){ 
       $sql  .= $virgula." ht07_obs = '$this->ht07_obs' ";
       $virgula = ",";
     }
     if(trim($this->ht07_datainicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht07_datainicial_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ht07_datainicial_dia"] !="") ){ 
       $sql  .= $virgula." ht07_datainicial = '$this->ht07_datainicial' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ht07_datainicial_dia"])){ 
         $sql  .= $virgula." ht07_datainicial = null ";
         $virgula = ",";
       }
     }
     if(trim($this->ht07_datafinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht07_datafinal_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ht07_datafinal_dia"] !="") ){ 
       $sql  .= $virgula." ht07_datafinal = '$this->ht07_datafinal' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ht07_datafinal_dia"])){ 
         $sql  .= $virgula." ht07_datafinal = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($ht07_sequencial!=null){
       $sql .= " ht07_sequencial = $this->ht07_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ht07_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16971,'$this->ht07_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht07_sequencial"]) || $this->ht07_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2995,16971,'".AddSlashes(pg_result($resaco,$conresaco,'ht07_sequencial'))."','$this->ht07_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht07_descricao"]) || $this->ht07_descricao != "")
           $resac = db_query("insert into db_acount values($acount,2995,16972,'".AddSlashes(pg_result($resaco,$conresaco,'ht07_descricao'))."','$this->ht07_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht07_obs"]) || $this->ht07_obs != "")
           $resac = db_query("insert into db_acount values($acount,2995,16973,'".AddSlashes(pg_result($resaco,$conresaco,'ht07_obs'))."','$this->ht07_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht07_datainicial"]) || $this->ht07_datainicial != "")
           $resac = db_query("insert into db_acount values($acount,2995,16974,'".AddSlashes(pg_result($resaco,$conresaco,'ht07_datainicial'))."','$this->ht07_datainicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht07_datafinal"]) || $this->ht07_datafinal != "")
           $resac = db_query("insert into db_acount values($acount,2995,16975,'".AddSlashes(pg_result($resaco,$conresaco,'ht07_datafinal'))."','$this->ht07_datafinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Forma de Avaliação da Habitação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ht07_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Forma de Avaliação da Habitação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ht07_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ht07_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ht07_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ht07_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16971,'$ht07_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2995,16971,'','".AddSlashes(pg_result($resaco,$iresaco,'ht07_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2995,16972,'','".AddSlashes(pg_result($resaco,$iresaco,'ht07_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2995,16973,'','".AddSlashes(pg_result($resaco,$iresaco,'ht07_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2995,16974,'','".AddSlashes(pg_result($resaco,$iresaco,'ht07_datainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2995,16975,'','".AddSlashes(pg_result($resaco,$iresaco,'ht07_datafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from habitformaavaliacao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ht07_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ht07_sequencial = $ht07_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Forma de Avaliação da Habitação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ht07_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Forma de Avaliação da Habitação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ht07_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ht07_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:habitformaavaliacao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ht07_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from habitformaavaliacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($ht07_sequencial!=null ){
         $sql2 .= " where habitformaavaliacao.ht07_sequencial = $ht07_sequencial "; 
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
   function sql_query_file ( $ht07_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from habitformaavaliacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($ht07_sequencial!=null ){
         $sql2 .= " where habitformaavaliacao.ht07_sequencial = $ht07_sequencial "; 
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