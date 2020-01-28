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

//MODULO: cadastro
//CLASSE DA ENTIDADE iptucalcpadraolog
class cl_iptucalcpadraolog { 
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
   var $j19_sequencial = 0; 
   var $j19_iptucalcpadrao = 0; 
   var $j19_usuario = 0; 
   var $j19_data_dia = null; 
   var $j19_data_mes = null; 
   var $j19_data_ano = null; 
   var $j19_data = null; 
   var $j19_hora = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j19_sequencial = int8 = Código 
                 j19_iptucalcpadrao = int8 = Código do calculo padrão 
                 j19_usuario = int8 = Usuário 
                 j19_data = date = Data 
                 j19_hora = char(5) = Hora 
                 ";
   //funcao construtor da classe 
   function cl_iptucalcpadraolog() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("iptucalcpadraolog"); 
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
       $this->j19_sequencial = ($this->j19_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["j19_sequencial"]:$this->j19_sequencial);
       $this->j19_iptucalcpadrao = ($this->j19_iptucalcpadrao == ""?@$GLOBALS["HTTP_POST_VARS"]["j19_iptucalcpadrao"]:$this->j19_iptucalcpadrao);
       $this->j19_usuario = ($this->j19_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["j19_usuario"]:$this->j19_usuario);
       if($this->j19_data == ""){
         $this->j19_data_dia = ($this->j19_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["j19_data_dia"]:$this->j19_data_dia);
         $this->j19_data_mes = ($this->j19_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["j19_data_mes"]:$this->j19_data_mes);
         $this->j19_data_ano = ($this->j19_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["j19_data_ano"]:$this->j19_data_ano);
         if($this->j19_data_dia != ""){
            $this->j19_data = $this->j19_data_ano."-".$this->j19_data_mes."-".$this->j19_data_dia;
         }
       }
       $this->j19_hora = ($this->j19_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["j19_hora"]:$this->j19_hora);
     }else{
       $this->j19_sequencial = ($this->j19_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["j19_sequencial"]:$this->j19_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($j19_sequencial){ 
      $this->atualizacampos();
     if($this->j19_iptucalcpadrao == null ){ 
       $this->erro_sql = " Campo Código do calculo padrão nao Informado.";
       $this->erro_campo = "j19_iptucalcpadrao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j19_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "j19_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j19_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "j19_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j19_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "j19_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($j19_sequencial == "" || $j19_sequencial == null ){
       $result = db_query("select nextval('iptucalcpadraolog_j19_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: iptucalcpadraolog_j19_sequencial_seq do campo: j19_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->j19_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from iptucalcpadraolog_j19_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $j19_sequencial)){
         $this->erro_sql = " Campo j19_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->j19_sequencial = $j19_sequencial; 
       }
     }
     if(($this->j19_sequencial == null) || ($this->j19_sequencial == "") ){ 
       $this->erro_sql = " Campo j19_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into iptucalcpadraolog(
                                       j19_sequencial 
                                      ,j19_iptucalcpadrao 
                                      ,j19_usuario 
                                      ,j19_data 
                                      ,j19_hora 
                       )
                values (
                                $this->j19_sequencial 
                               ,$this->j19_iptucalcpadrao 
                               ,$this->j19_usuario 
                               ,".($this->j19_data == "null" || $this->j19_data == ""?"null":"'".$this->j19_data."'")." 
                               ,'$this->j19_hora' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Log do calculo padrão ($this->j19_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Log do calculo padrão já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Log do calculo padrão ($this->j19_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j19_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j19_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11014,'$this->j19_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1898,11014,'','".AddSlashes(pg_result($resaco,0,'j19_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1898,11015,'','".AddSlashes(pg_result($resaco,0,'j19_iptucalcpadrao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1898,11016,'','".AddSlashes(pg_result($resaco,0,'j19_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1898,11017,'','".AddSlashes(pg_result($resaco,0,'j19_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1898,11018,'','".AddSlashes(pg_result($resaco,0,'j19_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j19_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update iptucalcpadraolog set ";
     $virgula = "";
     if(trim($this->j19_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j19_sequencial"])){ 
       $sql  .= $virgula." j19_sequencial = $this->j19_sequencial ";
       $virgula = ",";
       if(trim($this->j19_sequencial) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "j19_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j19_iptucalcpadrao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j19_iptucalcpadrao"])){ 
       $sql  .= $virgula." j19_iptucalcpadrao = $this->j19_iptucalcpadrao ";
       $virgula = ",";
       if(trim($this->j19_iptucalcpadrao) == null ){ 
         $this->erro_sql = " Campo Código do calculo padrão nao Informado.";
         $this->erro_campo = "j19_iptucalcpadrao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j19_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j19_usuario"])){ 
       $sql  .= $virgula." j19_usuario = $this->j19_usuario ";
       $virgula = ",";
       if(trim($this->j19_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "j19_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j19_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j19_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["j19_data_dia"] !="") ){ 
       $sql  .= $virgula." j19_data = '$this->j19_data' ";
       $virgula = ",";
       if(trim($this->j19_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "j19_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["j19_data_dia"])){ 
         $sql  .= $virgula." j19_data = null ";
         $virgula = ",";
         if(trim($this->j19_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "j19_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->j19_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j19_hora"])){ 
       $sql  .= $virgula." j19_hora = '$this->j19_hora' ";
       $virgula = ",";
       if(trim($this->j19_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "j19_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j19_sequencial!=null){
       $sql .= " j19_sequencial = $this->j19_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j19_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11014,'$this->j19_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j19_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1898,11014,'".AddSlashes(pg_result($resaco,$conresaco,'j19_sequencial'))."','$this->j19_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j19_iptucalcpadrao"]))
           $resac = db_query("insert into db_acount values($acount,1898,11015,'".AddSlashes(pg_result($resaco,$conresaco,'j19_iptucalcpadrao'))."','$this->j19_iptucalcpadrao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j19_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1898,11016,'".AddSlashes(pg_result($resaco,$conresaco,'j19_usuario'))."','$this->j19_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j19_data"]))
           $resac = db_query("insert into db_acount values($acount,1898,11017,'".AddSlashes(pg_result($resaco,$conresaco,'j19_data'))."','$this->j19_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j19_hora"]))
           $resac = db_query("insert into db_acount values($acount,1898,11018,'".AddSlashes(pg_result($resaco,$conresaco,'j19_hora'))."','$this->j19_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Log do calculo padrão nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j19_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Log do calculo padrão nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j19_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j19_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j19_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j19_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11014,'$j19_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1898,11014,'','".AddSlashes(pg_result($resaco,$iresaco,'j19_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1898,11015,'','".AddSlashes(pg_result($resaco,$iresaco,'j19_iptucalcpadrao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1898,11016,'','".AddSlashes(pg_result($resaco,$iresaco,'j19_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1898,11017,'','".AddSlashes(pg_result($resaco,$iresaco,'j19_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1898,11018,'','".AddSlashes(pg_result($resaco,$iresaco,'j19_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from iptucalcpadraolog
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j19_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j19_sequencial = $j19_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Log do calculo padrão nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j19_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Log do calculo padrão nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j19_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j19_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:iptucalcpadraolog";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $j19_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from iptucalcpadraolog ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = iptucalcpadraolog.j19_usuario";
     $sql .= "      inner join iptucalcpadrao  on  iptucalcpadrao.j10_sequencial = iptucalcpadraolog.j19_iptucalcpadrao";
     $sql .= "      inner join iptubase  on  iptubase.j01_matric = iptucalcpadrao.j10_matric";
     $sql2 = "";
     if($dbwhere==""){
       if($j19_sequencial!=null ){
         $sql2 .= " where iptucalcpadraolog.j19_sequencial = $j19_sequencial "; 
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
   function sql_query_file ( $j19_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from iptucalcpadraolog ";
     $sql2 = "";
     if($dbwhere==""){
       if($j19_sequencial!=null ){
         $sql2 .= " where iptucalcpadraolog.j19_sequencial = $j19_sequencial "; 
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