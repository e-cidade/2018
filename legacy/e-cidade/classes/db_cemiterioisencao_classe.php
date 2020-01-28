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

//MODULO: Cemiterio
//CLASSE DA ENTIDADE cemiterioisencao
class cl_cemiterioisencao { 
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
   var $cm34_sequencial = 0; 
   var $cm34_descricao = null; 
   var $cm34_tipo = 0; 
   var $cm34_datalimite_dia = null; 
   var $cm34_datalimite_mes = null; 
   var $cm34_datalimite_ano = null; 
   var $cm34_datalimite = null; 
   var $cm34_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 cm34_sequencial = int4 = Sequencial 
                 cm34_descricao = varchar(40) = Descri��o 
                 cm34_tipo = int4 = Tipo de Isen��o 
                 cm34_datalimite = date = Data Limite 
                 cm34_obs = text = Observa��o 
                 ";
   //funcao construtor da classe 
   function cl_cemiterioisencao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cemiterioisencao"); 
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
       $this->cm34_sequencial = ($this->cm34_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["cm34_sequencial"]:$this->cm34_sequencial);
       $this->cm34_descricao = ($this->cm34_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["cm34_descricao"]:$this->cm34_descricao);
       $this->cm34_tipo = ($this->cm34_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["cm34_tipo"]:$this->cm34_tipo);
       if($this->cm34_datalimite == ""){
         $this->cm34_datalimite_dia = ($this->cm34_datalimite_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["cm34_datalimite_dia"]:$this->cm34_datalimite_dia);
         $this->cm34_datalimite_mes = ($this->cm34_datalimite_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["cm34_datalimite_mes"]:$this->cm34_datalimite_mes);
         $this->cm34_datalimite_ano = ($this->cm34_datalimite_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["cm34_datalimite_ano"]:$this->cm34_datalimite_ano);
         if($this->cm34_datalimite_dia != ""){
            $this->cm34_datalimite = $this->cm34_datalimite_ano."-".$this->cm34_datalimite_mes."-".$this->cm34_datalimite_dia;
         }
       }
       $this->cm34_obs = ($this->cm34_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["cm34_obs"]:$this->cm34_obs);
     }else{
       $this->cm34_sequencial = ($this->cm34_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["cm34_sequencial"]:$this->cm34_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($cm34_sequencial){ 
      $this->atualizacampos();
     if($this->cm34_descricao == null ){ 
       $this->erro_sql = " Campo Descri��o nao Informado.";
       $this->erro_campo = "cm34_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm34_tipo == null ){ 
       $this->erro_sql = " Campo Tipo de Isen��o nao Informado.";
       $this->erro_campo = "cm34_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm34_datalimite == null ){ 
       $this->erro_sql = " Campo Data Limite nao Informado.";
       $this->erro_campo = "cm34_datalimite_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($cm34_sequencial == "" || $cm34_sequencial == null ){
       $result = db_query("select nextval('cemiterioisencao_cm34_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cemiterioisencao_cm34_sequencial_seq do campo: cm34_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->cm34_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cemiterioisencao_cm34_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $cm34_sequencial)){
         $this->erro_sql = " Campo cm34_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->cm34_sequencial = $cm34_sequencial; 
       }
     }
     if(($this->cm34_sequencial == null) || ($this->cm34_sequencial == "") ){ 
       $this->erro_sql = " Campo cm34_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cemiterioisencao(
                                       cm34_sequencial 
                                      ,cm34_descricao 
                                      ,cm34_tipo 
                                      ,cm34_datalimite 
                                      ,cm34_obs 
                       )
                values (
                                $this->cm34_sequencial 
                               ,'$this->cm34_descricao' 
                               ,$this->cm34_tipo 
                               ,".($this->cm34_datalimite == "null" || $this->cm34_datalimite == ""?"null":"'".$this->cm34_datalimite."'")." 
                               ,'$this->cm34_obs' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Isen��es do M�dulo Cemit�rio ($this->cm34_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Isen��es do M�dulo Cemit�rio j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Isen��es do M�dulo Cemit�rio ($this->cm34_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cm34_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->cm34_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14596,'$this->cm34_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2566,14596,'','".AddSlashes(pg_result($resaco,0,'cm34_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2566,14597,'','".AddSlashes(pg_result($resaco,0,'cm34_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2566,14598,'','".AddSlashes(pg_result($resaco,0,'cm34_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2566,14599,'','".AddSlashes(pg_result($resaco,0,'cm34_datalimite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2566,14600,'','".AddSlashes(pg_result($resaco,0,'cm34_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($cm34_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update cemiterioisencao set ";
     $virgula = "";
     if(trim($this->cm34_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm34_sequencial"])){ 
       $sql  .= $virgula." cm34_sequencial = $this->cm34_sequencial ";
       $virgula = ",";
       if(trim($this->cm34_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "cm34_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm34_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm34_descricao"])){ 
       $sql  .= $virgula." cm34_descricao = '$this->cm34_descricao' ";
       $virgula = ",";
       if(trim($this->cm34_descricao) == null ){ 
         $this->erro_sql = " Campo Descri��o nao Informado.";
         $this->erro_campo = "cm34_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm34_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm34_tipo"])){ 
       $sql  .= $virgula." cm34_tipo = $this->cm34_tipo ";
       $virgula = ",";
       if(trim($this->cm34_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo de Isen��o nao Informado.";
         $this->erro_campo = "cm34_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm34_datalimite)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm34_datalimite_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["cm34_datalimite_dia"] !="") ){ 
       $sql  .= $virgula." cm34_datalimite = '$this->cm34_datalimite' ";
       $virgula = ",";
       if(trim($this->cm34_datalimite) == null ){ 
         $this->erro_sql = " Campo Data Limite nao Informado.";
         $this->erro_campo = "cm34_datalimite_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["cm34_datalimite_dia"])){ 
         $sql  .= $virgula." cm34_datalimite = null ";
         $virgula = ",";
         if(trim($this->cm34_datalimite) == null ){ 
           $this->erro_sql = " Campo Data Limite nao Informado.";
           $this->erro_campo = "cm34_datalimite_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->cm34_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm34_obs"])){ 
       $sql  .= $virgula." cm34_obs = '$this->cm34_obs' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($cm34_sequencial!=null){
       $sql .= " cm34_sequencial = $this->cm34_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->cm34_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14596,'$this->cm34_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm34_sequencial"]) || $this->cm34_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2566,14596,'".AddSlashes(pg_result($resaco,$conresaco,'cm34_sequencial'))."','$this->cm34_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm34_descricao"]) || $this->cm34_descricao != "")
           $resac = db_query("insert into db_acount values($acount,2566,14597,'".AddSlashes(pg_result($resaco,$conresaco,'cm34_descricao'))."','$this->cm34_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm34_tipo"]) || $this->cm34_tipo != "")
           $resac = db_query("insert into db_acount values($acount,2566,14598,'".AddSlashes(pg_result($resaco,$conresaco,'cm34_tipo'))."','$this->cm34_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm34_datalimite"]) || $this->cm34_datalimite != "")
           $resac = db_query("insert into db_acount values($acount,2566,14599,'".AddSlashes(pg_result($resaco,$conresaco,'cm34_datalimite'))."','$this->cm34_datalimite',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm34_obs"]) || $this->cm34_obs != "")
           $resac = db_query("insert into db_acount values($acount,2566,14600,'".AddSlashes(pg_result($resaco,$conresaco,'cm34_obs'))."','$this->cm34_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Isen��es do M�dulo Cemit�rio nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->cm34_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Isen��es do M�dulo Cemit�rio nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->cm34_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cm34_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($cm34_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($cm34_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14596,'$cm34_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2566,14596,'','".AddSlashes(pg_result($resaco,$iresaco,'cm34_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2566,14597,'','".AddSlashes(pg_result($resaco,$iresaco,'cm34_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2566,14598,'','".AddSlashes(pg_result($resaco,$iresaco,'cm34_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2566,14599,'','".AddSlashes(pg_result($resaco,$iresaco,'cm34_datalimite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2566,14600,'','".AddSlashes(pg_result($resaco,$iresaco,'cm34_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cemiterioisencao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($cm34_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cm34_sequencial = $cm34_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Isen��es do M�dulo Cemit�rio nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$cm34_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Isen��es do M�dulo Cemit�rio nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$cm34_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$cm34_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:cemiterioisencao";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $cm34_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cemiterioisencao ";
     $sql2 = "";
     if($dbwhere==""){
       if($cm34_sequencial!=null ){
         $sql2 .= " where cemiterioisencao.cm34_sequencial = $cm34_sequencial "; 
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
   function sql_query_file ( $cm34_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cemiterioisencao ";
     $sql2 = "";
     if($dbwhere==""){
       if($cm34_sequencial!=null ){
         $sql2 .= " where cemiterioisencao.cm34_sequencial = $cm34_sequencial "; 
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