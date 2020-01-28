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
//CLASSE DA ENTIDADE habitgrupoprogramavalidade
class cl_habitgrupoprogramavalidade { 
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
   var $ht04_sequencial = 0; 
   var $ht04_habitgrupoprograma = 0; 
   var $ht04_datainicial_dia = null; 
   var $ht04_datainicial_mes = null; 
   var $ht04_datainicial_ano = null; 
   var $ht04_datainicial = null; 
   var $ht04_datafinal_dia = null; 
   var $ht04_datafinal_mes = null; 
   var $ht04_datafinal_ano = null; 
   var $ht04_datafinal = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ht04_sequencial = int4 = Sequencial 
                 ht04_habitgrupoprograma = int4 = Grupo de Programa 
                 ht04_datainicial = date = Data Inicial 
                 ht04_datafinal = date = Data Final 
                 ";
   //funcao construtor da classe 
   function cl_habitgrupoprogramavalidade() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("habitgrupoprogramavalidade"); 
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
       $this->ht04_sequencial = ($this->ht04_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ht04_sequencial"]:$this->ht04_sequencial);
       $this->ht04_habitgrupoprograma = ($this->ht04_habitgrupoprograma == ""?@$GLOBALS["HTTP_POST_VARS"]["ht04_habitgrupoprograma"]:$this->ht04_habitgrupoprograma);
       if($this->ht04_datainicial == ""){
         $this->ht04_datainicial_dia = ($this->ht04_datainicial_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ht04_datainicial_dia"]:$this->ht04_datainicial_dia);
         $this->ht04_datainicial_mes = ($this->ht04_datainicial_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ht04_datainicial_mes"]:$this->ht04_datainicial_mes);
         $this->ht04_datainicial_ano = ($this->ht04_datainicial_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ht04_datainicial_ano"]:$this->ht04_datainicial_ano);
         if($this->ht04_datainicial_dia != ""){
            $this->ht04_datainicial = $this->ht04_datainicial_ano."-".$this->ht04_datainicial_mes."-".$this->ht04_datainicial_dia;
         }
       }
       if($this->ht04_datafinal == ""){
         $this->ht04_datafinal_dia = ($this->ht04_datafinal_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ht04_datafinal_dia"]:$this->ht04_datafinal_dia);
         $this->ht04_datafinal_mes = ($this->ht04_datafinal_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ht04_datafinal_mes"]:$this->ht04_datafinal_mes);
         $this->ht04_datafinal_ano = ($this->ht04_datafinal_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ht04_datafinal_ano"]:$this->ht04_datafinal_ano);
         if($this->ht04_datafinal_dia != ""){
            $this->ht04_datafinal = $this->ht04_datafinal_ano."-".$this->ht04_datafinal_mes."-".$this->ht04_datafinal_dia;
         }
       }
     }else{
       $this->ht04_sequencial = ($this->ht04_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ht04_sequencial"]:$this->ht04_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ht04_sequencial){ 
      $this->atualizacampos();
     if($this->ht04_habitgrupoprograma == null ){ 
       $this->erro_sql = " Campo Grupo de Programa nao Informado.";
       $this->erro_campo = "ht04_habitgrupoprograma";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ht04_datainicial == null ){ 
       $this->ht04_datainicial = "null";
     }
     if($this->ht04_datafinal == null ){ 
       $this->ht04_datafinal = "null";
     }
     if($ht04_sequencial == "" || $ht04_sequencial == null ){
       $result = db_query("select nextval('habitgrupoprogramavalidade_ht04_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: habitgrupoprogramavalidade_ht04_sequencial_seq do campo: ht04_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ht04_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from habitgrupoprogramavalidade_ht04_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ht04_sequencial)){
         $this->erro_sql = " Campo ht04_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ht04_sequencial = $ht04_sequencial; 
       }
     }
     if(($this->ht04_sequencial == null) || ($this->ht04_sequencial == "") ){ 
       $this->erro_sql = " Campo ht04_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into habitgrupoprogramavalidade(
                                       ht04_sequencial 
                                      ,ht04_habitgrupoprograma 
                                      ,ht04_datainicial 
                                      ,ht04_datafinal 
                       )
                values (
                                $this->ht04_sequencial 
                               ,$this->ht04_habitgrupoprograma 
                               ,".($this->ht04_datainicial == "null" || $this->ht04_datainicial == ""?"null":"'".$this->ht04_datainicial."'")." 
                               ,".($this->ht04_datafinal == "null" || $this->ht04_datafinal == ""?"null":"'".$this->ht04_datafinal."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Validade do Grupo de Programa da Habita��o ($this->ht04_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Validade do Grupo de Programa da Habita��o j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Validade do Grupo de Programa da Habita��o ($this->ht04_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ht04_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ht04_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16963,'$this->ht04_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2992,16963,'','".AddSlashes(pg_result($resaco,0,'ht04_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2992,16967,'','".AddSlashes(pg_result($resaco,0,'ht04_habitgrupoprograma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2992,16965,'','".AddSlashes(pg_result($resaco,0,'ht04_datainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2992,16966,'','".AddSlashes(pg_result($resaco,0,'ht04_datafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ht04_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update habitgrupoprogramavalidade set ";
     $virgula = "";
     if(trim($this->ht04_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht04_sequencial"])){ 
       $sql  .= $virgula." ht04_sequencial = $this->ht04_sequencial ";
       $virgula = ",";
       if(trim($this->ht04_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ht04_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht04_habitgrupoprograma)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht04_habitgrupoprograma"])){ 
       $sql  .= $virgula." ht04_habitgrupoprograma = $this->ht04_habitgrupoprograma ";
       $virgula = ",";
       if(trim($this->ht04_habitgrupoprograma) == null ){ 
         $this->erro_sql = " Campo Grupo de Programa nao Informado.";
         $this->erro_campo = "ht04_habitgrupoprograma";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht04_datainicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht04_datainicial_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ht04_datainicial_dia"] !="") ){ 
       $sql  .= $virgula." ht04_datainicial = '$this->ht04_datainicial' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ht04_datainicial_dia"])){ 
         $sql  .= $virgula." ht04_datainicial = null ";
         $virgula = ",";
       }
     }
     if(trim($this->ht04_datafinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht04_datafinal_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ht04_datafinal_dia"] !="") ){ 
       $sql  .= $virgula." ht04_datafinal = '$this->ht04_datafinal' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ht04_datafinal_dia"])){ 
         $sql  .= $virgula." ht04_datafinal = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($ht04_sequencial!=null){
       $sql .= " ht04_sequencial = $this->ht04_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ht04_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16963,'$this->ht04_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht04_sequencial"]) || $this->ht04_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2992,16963,'".AddSlashes(pg_result($resaco,$conresaco,'ht04_sequencial'))."','$this->ht04_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht04_habitgrupoprograma"]) || $this->ht04_habitgrupoprograma != "")
           $resac = db_query("insert into db_acount values($acount,2992,16967,'".AddSlashes(pg_result($resaco,$conresaco,'ht04_habitgrupoprograma'))."','$this->ht04_habitgrupoprograma',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht04_datainicial"]) || $this->ht04_datainicial != "")
           $resac = db_query("insert into db_acount values($acount,2992,16965,'".AddSlashes(pg_result($resaco,$conresaco,'ht04_datainicial'))."','$this->ht04_datainicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht04_datafinal"]) || $this->ht04_datafinal != "")
           $resac = db_query("insert into db_acount values($acount,2992,16966,'".AddSlashes(pg_result($resaco,$conresaco,'ht04_datafinal'))."','$this->ht04_datafinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Validade do Grupo de Programa da Habita��o nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ht04_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Validade do Grupo de Programa da Habita��o nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ht04_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ht04_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ht04_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ht04_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16963,'$ht04_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2992,16963,'','".AddSlashes(pg_result($resaco,$iresaco,'ht04_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2992,16967,'','".AddSlashes(pg_result($resaco,$iresaco,'ht04_habitgrupoprograma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2992,16965,'','".AddSlashes(pg_result($resaco,$iresaco,'ht04_datainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2992,16966,'','".AddSlashes(pg_result($resaco,$iresaco,'ht04_datafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from habitgrupoprogramavalidade
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ht04_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ht04_sequencial = $ht04_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Validade do Grupo de Programa da Habita��o nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ht04_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Validade do Grupo de Programa da Habita��o nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ht04_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ht04_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:habitgrupoprogramavalidade";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ht04_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from habitgrupoprogramavalidade ";
     $sql .= "      inner join habitgrupoprograma  on  habitgrupoprograma.ht03_sequencial = habitgrupoprogramavalidade.ht04_habitgrupoprograma";
     $sql .= "      inner join habittipogrupoprograma  on  habittipogrupoprograma.ht02_sequencial = habitgrupoprograma.ht03_habittipogrupoprograma";
     $sql2 = "";
     if($dbwhere==""){
       if($ht04_sequencial!=null ){
         $sql2 .= " where habitgrupoprogramavalidade.ht04_sequencial = $ht04_sequencial "; 
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
   function sql_query_file ( $ht04_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from habitgrupoprogramavalidade ";
     $sql2 = "";
     if($dbwhere==""){
       if($ht04_sequencial!=null ){
         $sql2 .= " where habitgrupoprogramavalidade.ht04_sequencial = $ht04_sequencial "; 
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