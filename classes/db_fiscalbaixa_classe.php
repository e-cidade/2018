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

//MODULO: fiscal
//CLASSE DA ENTIDADE fiscalbaixa
class cl_fiscalbaixa { 
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
   var $y47_codnoti = 0; 
   var $y47_data_dia = null; 
   var $y47_data_mes = null; 
   var $y47_data_ano = null; 
   var $y47_data = null; 
   var $y47_motivo = 0; 
   var $y47_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 y47_codnoti = int8 = Código da Notificação 
                 y47_data = date = Data da Baixa 
                 y47_motivo = int4 = Código do motivo 
                 y47_obs = text = Observação da Baixa 
                 ";
   //funcao construtor da classe 
   function cl_fiscalbaixa() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("fiscalbaixa"); 
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
       $this->y47_codnoti = ($this->y47_codnoti == ""?@$GLOBALS["HTTP_POST_VARS"]["y47_codnoti"]:$this->y47_codnoti);
       if($this->y47_data == ""){
         $this->y47_data_dia = ($this->y47_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["y47_data_dia"]:$this->y47_data_dia);
         $this->y47_data_mes = ($this->y47_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["y47_data_mes"]:$this->y47_data_mes);
         $this->y47_data_ano = ($this->y47_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["y47_data_ano"]:$this->y47_data_ano);
         if($this->y47_data_dia != ""){
            $this->y47_data = $this->y47_data_ano."-".$this->y47_data_mes."-".$this->y47_data_dia;
         }
       }
       $this->y47_motivo = ($this->y47_motivo == ""?@$GLOBALS["HTTP_POST_VARS"]["y47_motivo"]:$this->y47_motivo);
       $this->y47_obs = ($this->y47_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["y47_obs"]:$this->y47_obs);
     }else{
       $this->y47_codnoti = ($this->y47_codnoti == ""?@$GLOBALS["HTTP_POST_VARS"]["y47_codnoti"]:$this->y47_codnoti);
     }
   }
   // funcao para inclusao
   function incluir ($y47_codnoti){ 
      $this->atualizacampos();
     if($this->y47_data == null ){ 
       $this->erro_sql = " Campo Data da Baixa nao Informado.";
       $this->erro_campo = "y47_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y47_motivo == null ){ 
       $this->erro_sql = " Campo Código do motivo nao Informado.";
       $this->erro_campo = "y47_motivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y47_obs == null ){ 
       $this->erro_sql = " Campo Observação da Baixa nao Informado.";
       $this->erro_campo = "y47_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->y47_codnoti = $y47_codnoti; 
     if(($this->y47_codnoti == null) || ($this->y47_codnoti == "") ){ 
       $this->erro_sql = " Campo y47_codnoti nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into fiscalbaixa(
                                       y47_codnoti 
                                      ,y47_data 
                                      ,y47_motivo 
                                      ,y47_obs 
                       )
                values (
                                $this->y47_codnoti 
                               ,".($this->y47_data == "null" || $this->y47_data == ""?"null":"'".$this->y47_data."'")." 
                               ,$this->y47_motivo 
                               ,'$this->y47_obs' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "fiscalbaixa ($this->y47_codnoti) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "fiscalbaixa já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "fiscalbaixa ($this->y47_codnoti) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y47_codnoti;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->y47_codnoti));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4968,'$this->y47_codnoti','I')");
       $resac = db_query("insert into db_acount values($acount,693,4968,'','".AddSlashes(pg_result($resaco,0,'y47_codnoti'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,693,4969,'','".AddSlashes(pg_result($resaco,0,'y47_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,693,4970,'','".AddSlashes(pg_result($resaco,0,'y47_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,693,4971,'','".AddSlashes(pg_result($resaco,0,'y47_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($y47_codnoti=null) { 
      $this->atualizacampos();
     $sql = " update fiscalbaixa set ";
     $virgula = "";
     if(trim($this->y47_codnoti)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y47_codnoti"])){ 
       $sql  .= $virgula." y47_codnoti = $this->y47_codnoti ";
       $virgula = ",";
       if(trim($this->y47_codnoti) == null ){ 
         $this->erro_sql = " Campo Código da Notificação nao Informado.";
         $this->erro_campo = "y47_codnoti";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y47_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y47_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["y47_data_dia"] !="") ){ 
       $sql  .= $virgula." y47_data = '$this->y47_data' ";
       $virgula = ",";
       if(trim($this->y47_data) == null ){ 
         $this->erro_sql = " Campo Data da Baixa nao Informado.";
         $this->erro_campo = "y47_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["y47_data_dia"])){ 
         $sql  .= $virgula." y47_data = null ";
         $virgula = ",";
         if(trim($this->y47_data) == null ){ 
           $this->erro_sql = " Campo Data da Baixa nao Informado.";
           $this->erro_campo = "y47_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->y47_motivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y47_motivo"])){ 
       $sql  .= $virgula." y47_motivo = $this->y47_motivo ";
       $virgula = ",";
       if(trim($this->y47_motivo) == null ){ 
         $this->erro_sql = " Campo Código do motivo nao Informado.";
         $this->erro_campo = "y47_motivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y47_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y47_obs"])){ 
       $sql  .= $virgula." y47_obs = '$this->y47_obs' ";
       $virgula = ",";
       if(trim($this->y47_obs) == null ){ 
         $this->erro_sql = " Campo Observação da Baixa nao Informado.";
         $this->erro_campo = "y47_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($y47_codnoti!=null){
       $sql .= " y47_codnoti = $this->y47_codnoti";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->y47_codnoti));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4968,'$this->y47_codnoti','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y47_codnoti"]))
           $resac = db_query("insert into db_acount values($acount,693,4968,'".AddSlashes(pg_result($resaco,$conresaco,'y47_codnoti'))."','$this->y47_codnoti',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y47_data"]))
           $resac = db_query("insert into db_acount values($acount,693,4969,'".AddSlashes(pg_result($resaco,$conresaco,'y47_data'))."','$this->y47_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y47_motivo"]))
           $resac = db_query("insert into db_acount values($acount,693,4970,'".AddSlashes(pg_result($resaco,$conresaco,'y47_motivo'))."','$this->y47_motivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y47_obs"]))
           $resac = db_query("insert into db_acount values($acount,693,4971,'".AddSlashes(pg_result($resaco,$conresaco,'y47_obs'))."','$this->y47_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "fiscalbaixa nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->y47_codnoti;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "fiscalbaixa nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->y47_codnoti;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y47_codnoti;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($y47_codnoti=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($y47_codnoti));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4968,'$y47_codnoti','E')");
         $resac = db_query("insert into db_acount values($acount,693,4968,'','".AddSlashes(pg_result($resaco,$iresaco,'y47_codnoti'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,693,4969,'','".AddSlashes(pg_result($resaco,$iresaco,'y47_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,693,4970,'','".AddSlashes(pg_result($resaco,$iresaco,'y47_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,693,4971,'','".AddSlashes(pg_result($resaco,$iresaco,'y47_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from fiscalbaixa
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($y47_codnoti != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " y47_codnoti = $y47_codnoti ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "fiscalbaixa nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$y47_codnoti;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "fiscalbaixa nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$y47_codnoti;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$y47_codnoti;
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
        $this->erro_sql   = "Record Vazio na Tabela:fiscalbaixa";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $y47_codnoti=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from fiscalbaixa ";
     $sql .= "      inner join fiscalmotivo  on  fiscalmotivo.y46_motivo = fiscalbaixa.y47_motivo";
     $sql .= "      inner join fiscal  on  fiscal.y30_codnoti = fiscalbaixa.y47_codnoti";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = fiscal.y30_setor";
     $sql2 = "";
     if($dbwhere==""){
       if($y47_codnoti!=null ){
         $sql2 .= " where fiscalbaixa.y47_codnoti = $y47_codnoti "; 
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
   function sql_query_file ( $y47_codnoti=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from fiscalbaixa ";
     $sql2 = "";
     if($dbwhere==""){
       if($y47_codnoti!=null ){
         $sql2 .= " where fiscalbaixa.y47_codnoti = $y47_codnoti "; 
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
   function sql_query_proc ( $y47_codnoti=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from fiscalbaixa ";
     $sql .= "      inner join fiscalmotivo  on  fiscalmotivo.y46_motivo = fiscalbaixa.y47_motivo";
     $sql .= "      inner join fiscal  on  fiscal.y30_codnoti = fiscalbaixa.y47_codnoti";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = fiscal.y30_setor";
     $sql .= "      left join fiscalbaixaproc  on  fiscalbaixa.y47_codnoti=fiscalbaixaproc.y48_codnoti";
     $sql .= "      left join protprocesso  on fiscalbaixaproc.y48_codproc = protprocesso.p58_codproc";
     $sql2 = "";
     if($dbwhere==""){
       if($y47_codnoti!=null ){
         $sql2 .= " where fiscalbaixa.y47_codnoti = $y47_codnoti "; 
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