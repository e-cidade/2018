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

//MODULO: ISSQN
//CLASSE DA ENTIDADE issbaselog
class cl_issbaselog { 
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
   var $q102_sequencial = 0; 
   var $q102_inscr = 0; 
   var $q102_issbaselogtipo = 0; 
   var $q102_data_dia = null; 
   var $q102_data_mes = null; 
   var $q102_data_ano = null; 
   var $q102_data = null; 
   var $q102_hora = null; 
   var $q102_obs = null; 
   var $q102_origem = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q102_sequencial = int4 = Sequencial 
                 q102_inscr = int4 = Inscrição 
                 q102_issbaselogtipo = int4 = ISS Base Log Tipo 
                 q102_data = date = Data 
                 q102_hora = char(5) = Hora 
                 q102_obs = text = Observação 
                 q102_origem = int4 = Origem 
                 ";
   //funcao construtor da classe 
   function cl_issbaselog() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("issbaselog"); 
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
       $this->q102_sequencial = ($this->q102_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q102_sequencial"]:$this->q102_sequencial);
       $this->q102_inscr = ($this->q102_inscr == ""?@$GLOBALS["HTTP_POST_VARS"]["q102_inscr"]:$this->q102_inscr);
       $this->q102_issbaselogtipo = ($this->q102_issbaselogtipo == ""?@$GLOBALS["HTTP_POST_VARS"]["q102_issbaselogtipo"]:$this->q102_issbaselogtipo);
       if($this->q102_data == ""){
         $this->q102_data_dia = ($this->q102_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q102_data_dia"]:$this->q102_data_dia);
         $this->q102_data_mes = ($this->q102_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q102_data_mes"]:$this->q102_data_mes);
         $this->q102_data_ano = ($this->q102_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q102_data_ano"]:$this->q102_data_ano);
         if($this->q102_data_dia != ""){
            $this->q102_data = $this->q102_data_ano."-".$this->q102_data_mes."-".$this->q102_data_dia;
         }
       }
       $this->q102_hora = ($this->q102_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["q102_hora"]:$this->q102_hora);
       $this->q102_obs = ($this->q102_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["q102_obs"]:$this->q102_obs);
       $this->q102_origem = ($this->q102_origem == ""?@$GLOBALS["HTTP_POST_VARS"]["q102_origem"]:$this->q102_origem);
     }else{
       $this->q102_sequencial = ($this->q102_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q102_sequencial"]:$this->q102_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($q102_sequencial){ 
      $this->atualizacampos();
     if($this->q102_inscr == null ){ 
       $this->erro_sql = " Campo Inscrição nao Informado.";
       $this->erro_campo = "q102_inscr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q102_issbaselogtipo == null ){ 
       $this->erro_sql = " Campo ISS Base Log Tipo nao Informado.";
       $this->erro_campo = "q102_issbaselogtipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q102_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "q102_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q102_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "q102_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q102_obs == null ){ 
       $this->erro_sql = " Campo Observação nao Informado.";
       $this->erro_campo = "q102_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q102_origem == null ){ 
       $this->erro_sql = " Campo Origem nao Informado.";
       $this->erro_campo = "q102_origem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q102_sequencial == "" || $q102_sequencial == null ){
       $result = db_query("select nextval('issbaselog_q102_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: issbaselog_q102_sequencial_seq do campo: q102_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q102_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from issbaselog_q102_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q102_sequencial)){
         $this->erro_sql = " Campo q102_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q102_sequencial = $q102_sequencial; 
       }
     }
     if(($this->q102_sequencial == null) || ($this->q102_sequencial == "") ){ 
       $this->erro_sql = " Campo q102_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into issbaselog(
                                       q102_sequencial 
                                      ,q102_inscr 
                                      ,q102_issbaselogtipo 
                                      ,q102_data 
                                      ,q102_hora 
                                      ,q102_obs 
                                      ,q102_origem 
                       )
                values (
                                $this->q102_sequencial 
                               ,$this->q102_inscr 
                               ,$this->q102_issbaselogtipo 
                               ,".($this->q102_data == "null" || $this->q102_data == ""?"null":"'".$this->q102_data."'")." 
                               ,'$this->q102_hora' 
                               ,'$this->q102_obs' 
                               ,$this->q102_origem 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "ISS Base Log ($this->q102_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "ISS Base Log já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "ISS Base Log ($this->q102_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q102_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q102_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15876,'$this->q102_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2785,15876,'','".AddSlashes(pg_result($resaco,0,'q102_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2785,15877,'','".AddSlashes(pg_result($resaco,0,'q102_inscr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2785,15878,'','".AddSlashes(pg_result($resaco,0,'q102_issbaselogtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2785,15879,'','".AddSlashes(pg_result($resaco,0,'q102_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2785,15880,'','".AddSlashes(pg_result($resaco,0,'q102_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2785,15881,'','".AddSlashes(pg_result($resaco,0,'q102_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2785,15882,'','".AddSlashes(pg_result($resaco,0,'q102_origem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q102_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update issbaselog set ";
     $virgula = "";
     if(trim($this->q102_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q102_sequencial"])){ 
       $sql  .= $virgula." q102_sequencial = $this->q102_sequencial ";
       $virgula = ",";
       if(trim($this->q102_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "q102_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q102_inscr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q102_inscr"])){ 
       $sql  .= $virgula." q102_inscr = $this->q102_inscr ";
       $virgula = ",";
       if(trim($this->q102_inscr) == null ){ 
         $this->erro_sql = " Campo Inscrição nao Informado.";
         $this->erro_campo = "q102_inscr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q102_issbaselogtipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q102_issbaselogtipo"])){ 
       $sql  .= $virgula." q102_issbaselogtipo = $this->q102_issbaselogtipo ";
       $virgula = ",";
       if(trim($this->q102_issbaselogtipo) == null ){ 
         $this->erro_sql = " Campo ISS Base Log Tipo nao Informado.";
         $this->erro_campo = "q102_issbaselogtipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q102_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q102_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q102_data_dia"] !="") ){ 
       $sql  .= $virgula." q102_data = '$this->q102_data' ";
       $virgula = ",";
       if(trim($this->q102_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "q102_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["q102_data_dia"])){ 
         $sql  .= $virgula." q102_data = null ";
         $virgula = ",";
         if(trim($this->q102_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "q102_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->q102_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q102_hora"])){ 
       $sql  .= $virgula." q102_hora = '$this->q102_hora' ";
       $virgula = ",";
       if(trim($this->q102_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "q102_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q102_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q102_obs"])){ 
       $sql  .= $virgula." q102_obs = '$this->q102_obs' ";
       $virgula = ",";
       if(trim($this->q102_obs) == null ){ 
         $this->erro_sql = " Campo Observação nao Informado.";
         $this->erro_campo = "q102_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q102_origem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q102_origem"])){ 
       $sql  .= $virgula." q102_origem = $this->q102_origem ";
       $virgula = ",";
       if(trim($this->q102_origem) == null ){ 
         $this->erro_sql = " Campo Origem nao Informado.";
         $this->erro_campo = "q102_origem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q102_sequencial!=null){
       $sql .= " q102_sequencial = $this->q102_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q102_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15876,'$this->q102_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q102_sequencial"]) || $this->q102_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2785,15876,'".AddSlashes(pg_result($resaco,$conresaco,'q102_sequencial'))."','$this->q102_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q102_inscr"]) || $this->q102_inscr != "")
           $resac = db_query("insert into db_acount values($acount,2785,15877,'".AddSlashes(pg_result($resaco,$conresaco,'q102_inscr'))."','$this->q102_inscr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q102_issbaselogtipo"]) || $this->q102_issbaselogtipo != "")
           $resac = db_query("insert into db_acount values($acount,2785,15878,'".AddSlashes(pg_result($resaco,$conresaco,'q102_issbaselogtipo'))."','$this->q102_issbaselogtipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q102_data"]) || $this->q102_data != "")
           $resac = db_query("insert into db_acount values($acount,2785,15879,'".AddSlashes(pg_result($resaco,$conresaco,'q102_data'))."','$this->q102_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q102_hora"]) || $this->q102_hora != "")
           $resac = db_query("insert into db_acount values($acount,2785,15880,'".AddSlashes(pg_result($resaco,$conresaco,'q102_hora'))."','$this->q102_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q102_obs"]) || $this->q102_obs != "")
           $resac = db_query("insert into db_acount values($acount,2785,15881,'".AddSlashes(pg_result($resaco,$conresaco,'q102_obs'))."','$this->q102_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q102_origem"]) || $this->q102_origem != "")
           $resac = db_query("insert into db_acount values($acount,2785,15882,'".AddSlashes(pg_result($resaco,$conresaco,'q102_origem'))."','$this->q102_origem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "ISS Base Log nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q102_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "ISS Base Log nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q102_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q102_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q102_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q102_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15876,'$q102_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2785,15876,'','".AddSlashes(pg_result($resaco,$iresaco,'q102_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2785,15877,'','".AddSlashes(pg_result($resaco,$iresaco,'q102_inscr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2785,15878,'','".AddSlashes(pg_result($resaco,$iresaco,'q102_issbaselogtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2785,15879,'','".AddSlashes(pg_result($resaco,$iresaco,'q102_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2785,15880,'','".AddSlashes(pg_result($resaco,$iresaco,'q102_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2785,15881,'','".AddSlashes(pg_result($resaco,$iresaco,'q102_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2785,15882,'','".AddSlashes(pg_result($resaco,$iresaco,'q102_origem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from issbaselog
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q102_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q102_sequencial = $q102_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "ISS Base Log nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q102_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "ISS Base Log nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q102_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q102_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:issbaselog";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $q102_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issbaselog ";
     $sql .= "      inner join issbase  on  issbase.q02_inscr = issbaselog.q102_inscr";
     $sql .= "      inner join issbaselogtipo  on  issbaselogtipo.q103_sequencial = issbaselog.q102_issbaselogtipo";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = issbase.q02_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($q102_sequencial!=null ){
         $sql2 .= " where issbaselog.q102_sequencial = $q102_sequencial "; 
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
   function sql_query_file ( $q102_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issbaselog ";
     $sql2 = "";
     if($dbwhere==""){
       if($q102_sequencial!=null ){
         $sql2 .= " where issbaselog.q102_sequencial = $q102_sequencial "; 
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