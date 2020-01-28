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
//CLASSE DA ENTIDADE habittipogrupoprograma
class cl_habittipogrupoprograma { 
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
   var $ht02_sequencial = 0; 
   var $ht02_descricao = null; 
   var $ht02_obs = null; 
   var $ht02_datainicial_dia = null; 
   var $ht02_datainicial_mes = null; 
   var $ht02_datainicial_ano = null; 
   var $ht02_datainicial = null; 
   var $ht02_datafinal_dia = null; 
   var $ht02_datafinal_mes = null; 
   var $ht02_datafinal_ano = null; 
   var $ht02_datafinal = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ht02_sequencial = int4 = Sequencial 
                 ht02_descricao = varchar(50) = Descrição 
                 ht02_obs = text = Observação 
                 ht02_datainicial = date = Data Inicial 
                 ht02_datafinal = date = Data Final 
                 ";
   //funcao construtor da classe 
   function cl_habittipogrupoprograma() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("habittipogrupoprograma"); 
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
       $this->ht02_sequencial = ($this->ht02_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ht02_sequencial"]:$this->ht02_sequencial);
       $this->ht02_descricao = ($this->ht02_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["ht02_descricao"]:$this->ht02_descricao);
       $this->ht02_obs = ($this->ht02_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["ht02_obs"]:$this->ht02_obs);
       if($this->ht02_datainicial == ""){
         $this->ht02_datainicial_dia = ($this->ht02_datainicial_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ht02_datainicial_dia"]:$this->ht02_datainicial_dia);
         $this->ht02_datainicial_mes = ($this->ht02_datainicial_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ht02_datainicial_mes"]:$this->ht02_datainicial_mes);
         $this->ht02_datainicial_ano = ($this->ht02_datainicial_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ht02_datainicial_ano"]:$this->ht02_datainicial_ano);
         if($this->ht02_datainicial_dia != ""){
            $this->ht02_datainicial = $this->ht02_datainicial_ano."-".$this->ht02_datainicial_mes."-".$this->ht02_datainicial_dia;
         }
       }
       if($this->ht02_datafinal == ""){
         $this->ht02_datafinal_dia = ($this->ht02_datafinal_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ht02_datafinal_dia"]:$this->ht02_datafinal_dia);
         $this->ht02_datafinal_mes = ($this->ht02_datafinal_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ht02_datafinal_mes"]:$this->ht02_datafinal_mes);
         $this->ht02_datafinal_ano = ($this->ht02_datafinal_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ht02_datafinal_ano"]:$this->ht02_datafinal_ano);
         if($this->ht02_datafinal_dia != ""){
            $this->ht02_datafinal = $this->ht02_datafinal_ano."-".$this->ht02_datafinal_mes."-".$this->ht02_datafinal_dia;
         }
       }
     }else{
       $this->ht02_sequencial = ($this->ht02_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ht02_sequencial"]:$this->ht02_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ht02_sequencial){ 
      $this->atualizacampos();
     if($this->ht02_descricao == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "ht02_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ht02_datainicial == null ){ 
       $this->ht02_datainicial = "null";
     }
     if($this->ht02_datafinal == null ){ 
       $this->ht02_datafinal = "null";
     }
     if($ht02_sequencial == "" || $ht02_sequencial == null ){
       $result = db_query("select nextval('habittipogrupoprograma_ht02_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: habittipogrupoprograma_ht02_sequencial_seq do campo: ht02_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ht02_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from habittipogrupoprograma_ht02_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ht02_sequencial)){
         $this->erro_sql = " Campo ht02_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ht02_sequencial = $ht02_sequencial; 
       }
     }
     if(($this->ht02_sequencial == null) || ($this->ht02_sequencial == "") ){ 
       $this->erro_sql = " Campo ht02_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into habittipogrupoprograma(
                                       ht02_sequencial 
                                      ,ht02_descricao 
                                      ,ht02_obs 
                                      ,ht02_datainicial 
                                      ,ht02_datafinal 
                       )
                values (
                                $this->ht02_sequencial 
                               ,'$this->ht02_descricao' 
                               ,'$this->ht02_obs' 
                               ,".($this->ht02_datainicial == "null" || $this->ht02_datainicial == ""?"null":"'".$this->ht02_datainicial."'")." 
                               ,".($this->ht02_datafinal == "null" || $this->ht02_datafinal == ""?"null":"'".$this->ht02_datafinal."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tipo de Grupo de Programa da Habitação ($this->ht02_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tipo de Grupo de Programa da Habitação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tipo de Grupo de Programa da Habitação ($this->ht02_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ht02_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ht02_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16951,'$this->ht02_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2990,16951,'','".AddSlashes(pg_result($resaco,0,'ht02_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2990,16952,'','".AddSlashes(pg_result($resaco,0,'ht02_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2990,16953,'','".AddSlashes(pg_result($resaco,0,'ht02_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2990,16954,'','".AddSlashes(pg_result($resaco,0,'ht02_datainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2990,16955,'','".AddSlashes(pg_result($resaco,0,'ht02_datafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ht02_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update habittipogrupoprograma set ";
     $virgula = "";
     if(trim($this->ht02_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht02_sequencial"])){ 
       $sql  .= $virgula." ht02_sequencial = $this->ht02_sequencial ";
       $virgula = ",";
       if(trim($this->ht02_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ht02_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht02_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht02_descricao"])){ 
       $sql  .= $virgula." ht02_descricao = '$this->ht02_descricao' ";
       $virgula = ",";
       if(trim($this->ht02_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "ht02_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht02_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht02_obs"])){ 
       $sql  .= $virgula." ht02_obs = '$this->ht02_obs' ";
       $virgula = ",";
     }
     if(trim($this->ht02_datainicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht02_datainicial_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ht02_datainicial_dia"] !="") ){ 
       $sql  .= $virgula." ht02_datainicial = '$this->ht02_datainicial' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ht02_datainicial_dia"])){ 
         $sql  .= $virgula." ht02_datainicial = null ";
         $virgula = ",";
       }
     }
     if(trim($this->ht02_datafinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht02_datafinal_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ht02_datafinal_dia"] !="") ){ 
       $sql  .= $virgula." ht02_datafinal = '$this->ht02_datafinal' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ht02_datafinal_dia"])){ 
         $sql  .= $virgula." ht02_datafinal = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($ht02_sequencial!=null){
       $sql .= " ht02_sequencial = $this->ht02_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ht02_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16951,'$this->ht02_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht02_sequencial"]) || $this->ht02_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2990,16951,'".AddSlashes(pg_result($resaco,$conresaco,'ht02_sequencial'))."','$this->ht02_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht02_descricao"]) || $this->ht02_descricao != "")
           $resac = db_query("insert into db_acount values($acount,2990,16952,'".AddSlashes(pg_result($resaco,$conresaco,'ht02_descricao'))."','$this->ht02_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht02_obs"]) || $this->ht02_obs != "")
           $resac = db_query("insert into db_acount values($acount,2990,16953,'".AddSlashes(pg_result($resaco,$conresaco,'ht02_obs'))."','$this->ht02_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht02_datainicial"]) || $this->ht02_datainicial != "")
           $resac = db_query("insert into db_acount values($acount,2990,16954,'".AddSlashes(pg_result($resaco,$conresaco,'ht02_datainicial'))."','$this->ht02_datainicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht02_datafinal"]) || $this->ht02_datafinal != "")
           $resac = db_query("insert into db_acount values($acount,2990,16955,'".AddSlashes(pg_result($resaco,$conresaco,'ht02_datafinal'))."','$this->ht02_datafinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipo de Grupo de Programa da Habitação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ht02_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tipo de Grupo de Programa da Habitação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ht02_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ht02_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ht02_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ht02_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16951,'$ht02_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2990,16951,'','".AddSlashes(pg_result($resaco,$iresaco,'ht02_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2990,16952,'','".AddSlashes(pg_result($resaco,$iresaco,'ht02_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2990,16953,'','".AddSlashes(pg_result($resaco,$iresaco,'ht02_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2990,16954,'','".AddSlashes(pg_result($resaco,$iresaco,'ht02_datainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2990,16955,'','".AddSlashes(pg_result($resaco,$iresaco,'ht02_datafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from habittipogrupoprograma
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ht02_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ht02_sequencial = $ht02_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipo de Grupo de Programa da Habitação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ht02_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tipo de Grupo de Programa da Habitação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ht02_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ht02_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:habittipogrupoprograma";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ht02_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from habittipogrupoprograma ";
     $sql2 = "";
     if($dbwhere==""){
       if($ht02_sequencial!=null ){
         $sql2 .= " where habittipogrupoprograma.ht02_sequencial = $ht02_sequencial "; 
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
   function sql_query_file ( $ht02_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from habittipogrupoprograma ";
     $sql2 = "";
     if($dbwhere==""){
       if($ht02_sequencial!=null ){
         $sql2 .= " where habittipogrupoprograma.ht02_sequencial = $ht02_sequencial "; 
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